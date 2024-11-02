<?php

namespace App\Http\Resources;

use App\Models\Surah;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SurahPointResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $data = parent::toArray($request);
        $surah_name = Surah::findOrFail($data['surah_id'])->name;

        return [
            'surah_id' => $data['surah_id'],
            'surah_name' => $surah_name,
            'points' => $data['points'],
        ];
    }
}
