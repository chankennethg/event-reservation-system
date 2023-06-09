<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class ListTicketRequest extends FormRequest
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
            'sort_by' => 'string',
            'direction' => 'string|in:asc,desc|required_with:sort_by',
            'page' => 'integer',
            'limit' => 'integer',
            'uuid' => 'string|exists:tickets,uuid',
            'event_uuid' => 'string|exists:tickets,event_uuid',
        ];
    }
}
