<?php

namespace App\Services\Recitation;

use App\Models\PageRecitation;
use App\Models\Student;

class PageRecitationService extends RecitationService
{
    public function __construct()
    {
        parent::__construct(PageRecitation::class, 'page_recitations');
    }

    public function addPageRecitation(Student $student, int $startPage, int $endPage, $rate = null): array
    {
        return $this->addRecitation($student, [
            'start_page' => $startPage,
            'end_page' => $endPage,
            'rate' => $rate,
        ]);

    }

    public function updatePageRecitation(Student $student, int $id, int $startPage, int $endPage, $rate = null): array
    {
        return $this->updateRecitation($student, $id, [
            'start_page' => $startPage,
            'end_page' => $endPage,
            'rate' => $rate,
        ]);
    }
}
