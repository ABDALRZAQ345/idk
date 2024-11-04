<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StudentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    protected $mosque_id;

    public function __construct($resource, $mosque_id)
    {
        parent::__construct($resource);
        $this->mosque_id = $mosque_id;
    }

    public function toArray(Request $request): array
    {
        $totalPoints = $this->getPointsSum($this->mosque_id);

        return [
            'id' => $this->id,
            'name' => $this->name,
            'birth_date' => $this->birth_date,
            'phone_number' => $this->phone_number,
            'total_points' => $totalPoints,
        ];
    }
}
