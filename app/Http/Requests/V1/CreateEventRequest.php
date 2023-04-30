<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class CreateEventRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string',
            'description' => 'required|string',
            'location' => 'required|string',
            'price' => 'required|decimal:0,2',
            'attendee_limit' => 'nullable',
            'starts_at' => 'required|after:today|date_format:"Y-m-d H:i:s"',
            'ends_at' => 'required|after:starts_at|date_format:"Y-m-d H:i:s"',
            'reservation_starts_at' => 'required|before:starts_at|date_format:"Y-m-d H:i:s"',
            'reservation_ends_at' => 'required|before:starts_at|after:reservation_starts_at|date_format:"Y-m-d H:i:s"',
        ];
    }
}
