<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RouteUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "title" => "required|string",
            "tags" => "required|array|min:1",
            "tags.*" => "required|integer",
            "seasons" => "required|array|min:1",
            "seasons.*" => "required|integer",
            "difficulty_id" => "required|integer",
            "description" => "required|string",
            "interestpoints" => "required|array|min:2",
            "interestpoints.*" => "required|integer",
            "image" => "nullable|image",
            "badge_uuid" => "nullable|uuid",
        ];
    }
}
