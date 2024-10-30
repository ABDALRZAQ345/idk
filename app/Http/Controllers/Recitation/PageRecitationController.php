<?php

namespace App\Http\Controllers\Recitation;

use App\Http\Controllers\Controller;
use App\Http\Requests\Recitation\PageRecitationRequest;
use App\Models\Mosque;
use App\Models\Student;
use App\Services\Recitation\PageRecitationService;
use Illuminate\Http\JsonResponse;

class PageRecitationController extends Controller
{
    protected PageRecitationService $pageRecitationService;

    public function __construct(PageRecitationService $pageRecitationService)
    {
        $this->pageRecitationService = $pageRecitationService;
    }

    //
    public function index(Mosque $mosque, Student $student): JsonResponse
    {
        $page_recitation = $this->pageRecitationService->getRecitations($student, $mosque);

        return response()->json([
            $page_recitation,
        ]);
    }

    public function store(PageRecitationRequest $request, Student $student): JsonResponse
    {
        $validated = $request->validated();

        $data = $this->pageRecitationService->addPageRecitation($student, $validated['start_page'], $validated['end_page']);

        return response()->json([
            'message' => $data['message'],
        ], $data['status']);
    }

    public function update(PageRecitationRequest $request, Student $student, $page_recitation_id): JsonResponse
    {
        $validated = $request->validated();
        $data = $this->pageRecitationService->updatePageRecitation($student, $page_recitation_id, $validated['start_page'], $validated['end_page']);

        return response()->json([
            'message' => $data['message'],
        ], $data['status']);
    }

    public function delete(Student $student, $page_recitation_id): JsonResponse
    {
        $this->pageRecitationService->deleteRecitation($student, $page_recitation_id);

        return response()->json([
            'message' => 'deleted successfully',
        ]);
    }
}
