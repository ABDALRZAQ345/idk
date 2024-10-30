<?php

namespace App\Http\Controllers\Recitation;

use App\Http\Controllers\Controller;
use App\Http\Requests\Recitation\SectionRecitationRequest;
use App\Models\Mosque;
use App\Models\Student;
use App\Services\Recitation\SectionRecitationService;
use Illuminate\Http\JsonResponse;

class SectionRecitationController extends Controller
{
    protected SectionRecitationService $sectionRecitationService;

    public function __construct(SectionRecitationService $sectionRecitationService)
    {
        $this->sectionRecitationService = $sectionRecitationService;
    }

    public function index(Mosque $mosque, Student $student): JsonResponse
    {
        $section_recitation = $this->sectionRecitationService->getRecitations($student, $mosque);

        return response()->json([
            $section_recitation,
        ]);

    }

    public function store(SectionRecitationRequest $request, Student $student): JsonResponse
    {
        $validated = $request->validated();
        $data = $this->sectionRecitationService->addSectionRecitation($student, $validated['section_id']);

        return response()->json([
            'message' => $data['message'],
        ], $data['status']);

    }

    public function update(SectionRecitationRequest $request, Student $student, $section_recitation_id): JsonResponse
    {
        $validated = $request->validated();
        $data = $this->sectionRecitationService->updateSectionRecitation($student, $section_recitation_id, $validated['section_id']);

        return response()->json([
            'message' => $data['message'],
        ], $data['status']);
    }

    public function delete(Student $student, $sectionRecitation_id): JsonResponse
    {
        $this->sectionRecitationService->deleteRecitation($student, $sectionRecitation_id);

        return response()->json([
            'message' => 'deleted successfully',
        ]);
    }
    //
}
