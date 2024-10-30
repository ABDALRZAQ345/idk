<?php

namespace App\Services\Recitation;

use App\Models\Student;
use App\Models\SurahRecitation;

class SurahRecitationService extends RecitationService
{
    public function __construct()
    {
        parent::__construct(SurahRecitation::class, 'surah_recitations');
    }

    public function addSurahRecitation(Student $student, int $surahId, $rate = null)
    {
        return $this->addRecitation($student, [
            'surah_id' => $surahId,
            'rate' => $rate,
        ]);
    }
    public function updateSurahRecitation(Student $student, int $id, int $surahId, $rate = null)
    {
        return $this->updateRecitation($student, $id, [
            'surah_id' => $surahId,
            'rate' => $rate,
        ]);
    }
}
