<?php

namespace App\Services\Recitation;

use App\Models\SectionRecitation;
use App\Models\Student;

class SectionRecitationService extends RecitationService
{
    public function __construct()
    {
        parent::__construct(SectionRecitation::class, 'section_recitations');
    }

    public function addSectionRecitation(Student $student, int $sectionId, $rate = null): array
    {
        return $this->addRecitation($student, [
            'section_id' => $sectionId,
            'rate' => $rate,
        ]);
    }

    public function updateSectionRecitation(Student $student, int $id, int $sectionId, $rate = null)
    {
        return $this->updateRecitation($student, $id, [
            'section_id' => $sectionId,
            'rate' => $rate,
        ]);
    }
}
