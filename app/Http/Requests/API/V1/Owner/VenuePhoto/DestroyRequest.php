<?php

namespace App\Http\Requests\API\V1\Owner\VenuePhoto;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class DestroyRequest extends FormRequest
{
    public function rules(): array
    {
        return [

        ];
    }

    public function authorize(): bool
    {
        return Auth::guard('api_owner')->check();
    }
}
