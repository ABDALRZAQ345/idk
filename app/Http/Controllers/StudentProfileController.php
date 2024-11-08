<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Lesson;
use App\Models\Mosque;
use App\Models\Student;
use App\Services\Recitation\PageRecitationService;
use App\Services\Recitation\SectionRecitationService;
use App\Services\Recitation\SurahRecitationService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
    public function index(Student $student,Mosque $mosque)
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

    public function pageRecitations(Student $student,Mosque $mosque): \Illuminate\Http\JsonResponse
    {
        $student = Auth::user();
        $page_recitation = $this->pageRecitationService->getRecitations($student, $mosque);

        return response()->json([
            $page_recitation,
        ]);
    }

    public function surahRecitations(Student $student,Mosque $mosque): \Illuminate\Http\JsonResponse
    {
        $student = Auth::user();
        $Surah_recitation = $this->SurahRecitationService->getRecitations($student, $mosque);

        return response()->json([
            $Surah_recitation,
        ]);
    }

    public function sectionRecitations(Student $student,Mosque $mosque): \Illuminate\Http\JsonResponse
    {
        $student = Auth::user();
        $section_recitation = $this->sectionRecitationService->getRecitations($student, $mosque);

        return response()->json([
            $section_recitation,
        ]);
    }

    public function points(Student $student,Mosque $mosque): \Illuminate\Http\JsonResponse
    {

        $student = Auth::user();
        $mosque = $student->mosques()->FindOrFail($mosque->id);
        $points = $student->points()->where('mosque_id', $mosque->id)->paginate(20)->toArray();
        $totalPoints = $student->points()->where('mosque_id', $mosque->id)->sum('points');
        $points['total_points'] = $totalPoints;

        return response()->json([
            $points,
        ]);
    }

    public function activities(Request $request,Student $student, Mosque $mosque): \Illuminate\Http\JsonResponse
    {
        $student = Auth::user();
        $mosque = $student->mosques()->FindOrFail($mosque->id);

        $group = $student->group($mosque->id);

        $activities = Activity::whereRelation('groups', function ($query) use ($group) {
            $query->where('group_id', $group->id);
        });

        if ($request->has('filter.finished')) {
            if ($request->boolean('filter.finished')) {
                $activities = $activities->where('end_date', '<=', Carbon::now());
            } else {
                $activities = $activities->where('end_date', '>', Carbon::now());
            }
        }
        $activities = $activities->orderBy('start_date')->paginate(10);

        return response()->json([
            $activities,
        ]);
    }
    public function lessons(Request $request,Student $student, Mosque $mosque): \Illuminate\Http\JsonResponse
    {
        $student = Auth::user();
        $mosque = $student->mosques()->FindOrFail($mosque->id);

        $group = $student->group($mosque->id);

        $lessons = Lesson::whereRelation('groups', function ($query) use ($group) {
            $query->where('group_id', $group->id);
        });

        if ($request->has('filter.canceled')) {
            if ($request->boolean('filter.canceled')) {
                $lessons = $lessons->where('canceled',  true);
            } else {
                $lessons = $lessons->where('canceled', false);
            }
        }

        $lessons = $lessons->orderBy('start_date')->paginate(10);

        return response()->json([
            $lessons,
        ]);
    }
}
