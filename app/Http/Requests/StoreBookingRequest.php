<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBookingRequest extends FormRequest
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
            //
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone_number' => 'required|string|max:20',
            'started_at' => 'required|date',
            'total_participant' => 'required|integer|min:1',
            'participants' => 'required|array|min:1',
            'participants.*.participant_name' => 'required|string|max:255',
            'participants.*.identity_user' => 'required|string|max:255',
            'participants.*.contingen' => 'nullable|string|max:255',
            'participants.*.type_id' => 'required|integer|exists:types,id',
            'participants.*.initial_id' => 'required|integer|exists:initials,id',
        ];
    }
}
