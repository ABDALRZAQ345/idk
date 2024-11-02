<?php

namespace App\Http\Controllers;

use App\Http\Requests\PointRequest;
use App\Http\Resources\SurahPointCollection;
use App\Services\Point\PointService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class PointController extends Controller
{
    protected PointService $pointService;

    public function __construct(PointService $pointService)
    {
        $this->pointService = $pointService;
    }

    public function index(): JsonResponse
    {
        $user = Auth::user();
        $mosque = $user->mosque;
        $page_points = $mosque->page_points;
        $section_points = $mosque->section_points;
        $surah_points = $mosque->surah_points;

        return response()->json([
            'page_points' => $page_points,
            'section_points' => $section_points,
            'surah_points' => new SurahPointCollection($surah_points),
        ]);

    }
    ///

    /**
     * @throws \Exception
     */
    public function update(PointRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $mosque = Auth::user()->mosque;
        $this->pointService->update_points_info($mosque, $validated);

        return response()->json([
            'message' => 'update success',
        ]);
    }

    public function delete(): JsonResponse
    {

        $mosque = Auth::user()->mosque;
        $mosque->points()->delete();

        return response()->json([
            'message' => 'all points deleted successfully',
        ]);
    }
}
