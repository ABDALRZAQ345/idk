<?php

namespace App\Http\Controllers\Recitation;

use App\Http\Controllers\Controller;
use App\Http\Requests\Recitation\PageRecitationRequest;
use App\Models\Mosque;
use App\Models\Student;
use App\Services\Recitation\PageRecitationService;

class PageRecitationController extends Controller
{
    protected PageRecitationService $pagerecitationService;

    public function __construct(PageRecitationService $pagerecitationService)
    {
        $this->pagerecitationService = $pagerecitationService;
    }

    //
    public function index(Mosque $mosque, Student $student)
    {
        $page_recitation = $this->pagerecitationService->getRecitations($student, $mosque);

        return response()->json([
            'page_recitation' => $page_recitation,
        ]);
    }

    public function store(PageRecitationRequest $request, Student $student)
    {
        $validated = $request->validated();

        $data = $this->pagerecitationService->addPageRecitation($student, $validated['start_page'], $validated['end_page']);

        return response()->json([
            'message' => $data['message'],
        ], $data['status']);
    }

    public function update(PageRecitationRequest $request, Student $student, $page_recitation_id)
    {
        $validated = $request->validated();
        $data = $this->pagerecitationService->updatePageRecitation($student, $page_recitation_id, $validated['start_page'], $validated['end_page']);

        return response()->json([
            'message' => $data['message'],
        ], $data['status']);
    }

    public function delete(Student $student, $page_recitation_id)
    {
        $this->pagerecitationService->deleteRecitation($student, $page_recitation_id);

        return response()->json([
            'message' => 'deleted successfully',
        ]);
    }
}
