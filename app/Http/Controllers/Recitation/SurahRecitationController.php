<?php

namespace App\Http\Controllers\Recitation;

use App\Http\Controllers\Controller;
use App\Http\Requests\Recitation\SurahRecitationRequest;
use App\Models\Mosque;
use App\Models\Student;
use App\Services\Recitation\SurahRecitationService;

class SurahRecitationController extends Controller
{
    protected SurahRecitationService $SurahRecitationService;

    public function __construct(SurahRecitationService $SurahRecitationService)
    {
        $this->SurahRecitationService = $SurahRecitationService;
    }

    public function index(Mosque $mosque, Student $student)
    {
        $Surah_recitation = $this->SurahRecitationService->getRecitations($student, $mosque);

        return response()->json([
            'Surah_recitation' => $Surah_recitation,
        ]);

    }

    public function store(SurahRecitationRequest $request, Student $student)
    {
        $validated = $request->validated();
        $data = $this->SurahRecitationService->addSurahRecitation($student, $validated['surah_id']);

        return response()->json([
            'message' => $data['message'],
        ], $data['status']);

    }

    public function update(SurahRecitationRequest $request, Student $student, $surah_recitation_id)
    {
        $validated = $request->validated();
        $data = $this->SurahRecitationService->updateSurahRecitation($student, $surah_recitation_id, $validated['surah_id']);

        return response()->json([
            'message' => $data['message'],
        ], $data['status']);
    }

    public function delete(Student $student, $surahRecitation_id)
    {

        $this->SurahRecitationService->deleteRecitation($student, $surahRecitation_id);

        return response()->json([
            'message' => 'deleted successfully',
        ]);
    }
    //
}
