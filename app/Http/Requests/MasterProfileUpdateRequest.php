<?php

namespace App\Http\Requests;

use App\Models\MasterUser;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use App\Models\MasterUserDetails;

class MasterProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
     public function rules(): array
    {

        // dd($users_id);
        return [
            'users_first_name' => ['required', 'string', 'max:255'],
            'users_last_name' => ['required', 'string', 'max:255'],
           
            'users_phone' => ['nullable', 'string', 'max:255'],
            'users_bio' => ['required', 'string', 'max:255']
        ];
    }
    
}
