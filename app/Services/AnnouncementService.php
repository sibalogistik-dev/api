<?php

namespace App\Services;

use App\Models\Announcement;
use App\Models\AnnouncementRecipient;
use App\Models\Karyawan;
use App\Models\User;
use App\Notifications\Employee\NewAnnouncement;
use Exception;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Laravel\Facades\Image;

class AnnouncementService
{
    public function create(array $data)
    {
        $filePaths = [];
        DB::beginTransaction();
        try {
            $uploads = $data;
            $filePaths['image_url']    = null;
            if (!empty($data['image_url'])) {
                $filePaths['image_url'] = $this->storeFile($data['image_url'], 'uploads/announcement', 90);
                $uploads['image_url']   = $filePaths['image_url'];
            }else{
                $uploads['image_url']   = 'uploads/announcement/default.webp';
            }
            $announcement   = Announcement::create($uploads);

            if ($announcement) {
                for ($i = 0; $i < count($data['recipients']); $i++) {
                    $announcementRecipient = AnnouncementRecipient::create([
                        'employee_id'       => $data['recipients'][$i],
                        'announcement_id'   => $announcement->id,
                        'is_read'           => false,
                    ]);
                    if ($announcementRecipient) {
                        $user = Karyawan::find($data['recipients'][$i])?->user;
                        $this->notifyNewAnnouncement(
                            $user,
                            'info',
                            'Ada pengumuman baru buat kamu!',
                            $data['title'],
                            '/notifikasi'
                        );
                        $firebaseNotificationService = new FcmService();
                        $firebaseNotificationService->sendNotification(
                            $user->id,
                            'employee',
                            'Pengumuman Baru',
                            'Anda memiliki training baru : ' . $data['title']
                        );
                    } else {
                        throw new Exception('Failed to save announcement recipient data');
                    }
                }
            }

            DB::commit();
            return $announcement;
        } catch (Exception $e) {
            DB::rollBack();
            if ($filePaths !== '') {
                foreach ($filePaths as $path) {
                    if ($path) {
                        Storage::disk('public')->delete($path);
                    }
                }
            }
            throw new Exception('Failed to save announcement data: ' . $e->getMessage());
        }
    }

    public function update(Announcement $announcement, array $data)
    {
        $filePaths = [];
        DB::beginTransaction();
        try {
            $uploads = $data;
            if (!empty($data['image_url'])) {
                $filePaths['image_url']   = $this->storeFile($data['image_url'], 'uploads/image_url', 90);
                $uploads['image_url']     = $filePaths['image_url'];
            }
            $announcement->update($uploads);
            DB::commit();
            return $announcement;
        } catch (Exception $e) {
            DB::rollBack();
            foreach ($filePaths as $path) {
                Storage::disk('public')->delete($path);
            }
            throw new Exception('Failed to update asset maintenance data: ' . $e->getMessage());
        }
    }

    private function storeFile(UploadedFile $file, string $path)
    {
        $isImage = Str::startsWith($file->getMimeType(), 'image/');
        $filename = now()->format('Ymd-His') . '-' . Str::random(10);
        $fullPath = $path . '/' . $filename;

        if ($isImage) {
            $fullPath .= '.png';

            $image = Image::read($file->getRealPath())->toPng();

            Storage::disk('public')->put($fullPath, (string) $image);

            return $fullPath;
        }

        $extension = $file->getClientOriginalExtension();
        $fullPath .= '.' . $extension;

        Storage::disk('public')->putFileAs($path, $file, basename($fullPath));

        return $fullPath;
    }

    public function notifyNewAnnouncement(
        User $user,
        string $status,
        string $title,
        string $message,
        ?string $url = null
    ) {
        $user->notify(new NewAnnouncement([
            'title'   => $title,
            'message' => $message,
            'status'  => $status,
            'url'     => $url,
        ]));
    }
}
