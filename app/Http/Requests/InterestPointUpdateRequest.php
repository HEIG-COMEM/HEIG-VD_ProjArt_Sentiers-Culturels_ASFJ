<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class InterestPointUpdateRequest
 * 
 * This class is a FormRequest class that is used to validate the incoming request to update an interest point.
 */
class InterestPointUpdateRequest extends FormRequest
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
            "location" => "required|array|max:2",
            "location.*" => "required|numeric",
            "description" => "required|string",
            "image" => "nullable|image",
            "badge_uuid" => "nullable|uuid"
        ];
    }
}
