<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Mosque;
use App\Services\Recitation\PageRecitationService;
use App\Services\Recitation\SectionRecitationService;
use App\Services\Recitation\SurahRecitationService;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\Auth;
use Spatie\QueryBuilder\QueryBuilder;

class StudentProfileController extends Controller
{
    protected SurahRecitationService $SurahRecitationService;

    protected PageRecitationService $pageRecitationService;

    protected SectionRecitationService $sectionRecitationService;

    public function __construct(SurahRecitationService $SurahRecitationService, PageRecitationService $pageRecitationService, SectionRecitationService $sectionRecitationService)
    {
        $this->SurahRecitationService = $SurahRecitationService;
        $this->pageRecitationService = $pageRecitationService;
        $this->sectionRecitationService = $sectionRecitationService;
    }

    //
    public function index(Mosque $mosque)
    {

        $student = Auth::user();
        $mosque = $student->mosques()
            ->where('mosques.id', $mosque->id)
            ->select(['mosques.name', 'mosques.id', 'mosques.location'])
            ->firstOrFail();
        $total_points = $student->points()->where('mosque_id', $mosque->id)->sum('points');
        $group = $student->group($mosque->id);

        return response()->json([
            'student' => $student,
            'mosque' => $mosque,
            'total_points' => $total_points,
            'group' => $group,
        ]);
    }

    public function pageRecitations(Mosque $mosque): \Illuminate\Http\JsonResponse
    {
        $student = Auth::user();
        $page_recitation = $this->pageRecitationService->getRecitations($student, $mosque);

        return response()->json([
            $page_recitation,
        ]);
    }

    public function surahRecitations(Mosque $mosque): \Illuminate\Http\JsonResponse
    {
        $student = Auth::user();
        $Surah_recitation = $this->SurahRecitationService->getRecitations($student, $mosque);

        return response()->json([
            $Surah_recitation,
        ]);
    }

    public function sectionRecitations(Mosque $mosque): \Illuminate\Http\JsonResponse
    {
        $student = Auth::user();
        $section_recitation = $this->sectionRecitationService->getRecitations($student, $mosque);

        return response()->json([
            $section_recitation,
        ]);
    }

    public function points(Mosque $mosque): \Illuminate\Http\JsonResponse
    {

        $student = Auth::user();
        $mosque=$student->mosques()->FindOrFail($mosque->id);
        $points = $student->points()->where('mosque_id', $mosque->id)->paginate(20)->toArray();
        $totalPoints = $student->points()->where('mosque_id', $mosque->id)->sum('points');
        $points['total_points'] = $totalPoints;

        return response()->json([
            $points,
        ]);
    }

    public function activities(Mosque $mosque): \Illuminate\Http\JsonResponse
    {
        $student = Auth::user();
        $mosque=$student->mosques()->FindOrFail($mosque->id);
        $activities = QueryBuilder::for(Activity::class)
            ->allowedFilters(['finished'])
            ->where('mosque_id', $mosque->id)
            ->orderBy('start_date')
            ->paginate(20);
        return response()->json([
            $activities,
        ]);
    }
}
