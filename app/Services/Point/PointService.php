<?php

namespace App\Services\Point;

use App\Models\Mosque;
use Illuminate\Support\Facades\DB;
use Mockery\Exception;

class PointService
{
    /**
     * @throws \Exception
     */
    public function update_points_info(Mosque $mosque, $data): void
    {

        try {
            DB::beginTransaction();
            $mosque->page_points = $data['page_points'];
            $mosque->section_points = $data['section_points'];
            foreach ($data['surah_points'] as $index => $surahPoint) {
                $mosque->surah_points()->where('surah_id', $index + 1)->update([
                    'points' => $surahPoint['points'],
                ]);
            }
            $mosque->save();
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }

    }
}
