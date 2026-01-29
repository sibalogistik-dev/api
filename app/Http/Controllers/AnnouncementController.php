<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponseHelper;
use App\Http\Requests\AnnouncementIndexRequest;
use App\Http\Requests\AnnouncementStoreRequest;
use App\Http\Requests\AnnouncementUpdateRequest;
use App\Models\Announcement;
use App\Services\AnnouncementService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Storage;

class AnnouncementController extends Controller
{
    protected $announcementService;

    public function __construct(AnnouncementService $announcementService)
    {
        $this->announcementService = $announcementService;
    }

    public function index(AnnouncementIndexRequest $request)
    {
        try {
            $validated              = $request->validated();
            $announcementQ          = Announcement::query()->filter($validated);
            $announcement           = isset($validated['paginate']) && $validated['paginate'] ? $announcementQ->paginate($validated['perPage'] ?? 10) : $announcementQ->get();
            $transformedItems       = $announcement instanceof LengthAwarePaginator ? $announcement->getCollection() : $announcement;
            $transformedAnnounce    = $transformedItems->map(function ($item) {
                return [
                    'id'                => $item->id,
                    'title'             => $item->title,
                    'content'           => $item->content,
                    'total_recipients'  => count($item->recipients),
                    'total_read'        => count($item->recipients()->where('is_read', true)->get()),
                    'created_at'        => $item->created_at
                ];
            });

            if ($announcement instanceof LengthAwarePaginator) {
                return ApiResponseHelper::success('Employee training data', $announcement->setCollection($transformedAnnounce));
            }
            return ApiResponseHelper::success('Employee training data', $transformedAnnounce);
        } catch (Exception $e) {
            return ApiResponseHelper::error('Failed to get announcement data', $e->getMessage());
        }
    }

    public function store(AnnouncementStoreRequest $request)
    {
        try {
            $announcement = $this->announcementService->create($request->validated());
            return ApiResponseHelper::success('Announcement data has been added successfully', $announcement);
        } catch (Exception $e) {
            return ApiResponseHelper::error('Error when saving announcement data', $e->getMessage());
        }
    }

    public function show($announcement)
    {
        try {
            $announce = Announcement::find($announcement);
            if (!$announce) {
                throw new Exception('Announcement data not found');
            }
            $data = [
                'id'            => $announce->id,
                'title'         => $announce->title,
                'content'       => $announce->content,
                'recipients'    => $announce->recipients->map(function ($karyawan) {
                    return [
                        'employee_id' => $karyawan->id,
                        'name'        => $karyawan->name,
                        'is_read'     => $karyawan->pivot->is_read,
                    ];
                }),
                'created_at'    => $announce->created_at
            ];
            return ApiResponseHelper::success("Announcement's details", $data);
        } catch (Exception $e) {
            return ApiResponseHelper::error("Failed to get Announcement's data", $e->getMessage());
        }
    }

    public function update(AnnouncementUpdateRequest $request, $announcement)
    {
        //
    }

    public function destroy($id)
    {
        $announcement = Announcement::findOrFail($id);
        $announcement->recipients()->detach();
        if ($announcement->image_url) {
            Storage::disk('public')->delete($announcement->image_url);
        }
        $announcement->delete();
        return response()->json([
            'success' => true,
            'message' => 'Pengumuman dan data penerima berhasil dihapus.'
        ]);
    }
}
