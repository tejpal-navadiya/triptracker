<?php

namespace App\Http\Requests;

use App\Models\MasterUser;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
class MasterProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
     public function rules(): array
    {
        $user = Auth::guard('masteradmins')->user();
        // dd($user );
        return [
            'users_name' => ['required', 'string', 'max:255'],
            'users_city_name' => ['required', 'string', 'max:255'],
            'users_pincode' => ['required', 'string', 'max:255'],
            'country_id' => ['required', 'integer'],
            'state_id' => ['required', 'integer'],
        ];
    }
    
}
