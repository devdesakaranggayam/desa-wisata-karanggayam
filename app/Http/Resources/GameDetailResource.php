<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GameDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $questions = $this->questions->map(function($item) {
            $answer = $item->answers->map(function($a) {
                return $a->only(['id','answer_text','is_correct']);
            });
            
            return [
                'id' => $item->id,
                'question_text' => $item->question_text,
                'thumbnail_url' => $item->thumbnail_url,
                'answers' => $answer
            ];
        });
        
        return [
            "id" => $this->id,
            "name" => $this->nama,
            "type" => $this->type,
            "min_correct_answer" => $this->passing_score,
            "icon_url" => $this->icon_url,
            "questions" => $questions
        ];
    }
}
