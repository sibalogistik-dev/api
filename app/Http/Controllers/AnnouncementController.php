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
            $validated      = $request->validated();
            $announcementQ  = Announcement::query()->filter($validated);
            $announcement   = isset($validated['paginate']) && $validated['paginate'] ? $announcementQ->paginate($validated['perPage'] ?? 10) : $announcementQ->get();
            return ApiResponseHelper::success('Announcement data', $announcement);
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
        //
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
