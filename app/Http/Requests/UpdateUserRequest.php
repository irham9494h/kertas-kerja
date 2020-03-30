<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [

        ];
    }

    public function messages()
    {
        return [
            'nip.required' => 'NIP tidak boleh kosong.',
            'nip.unique' => 'NIP telah terdaftar.',
            'nip.min' => 'NIP minimal 14 karakter',

            'username.required' => 'Username tidak boleh kosong.',
            'username.unique' => 'Username tidak tersedia.',
            'username.min' => 'Username tidak boleh kurang dari 3 karakter.',

            'nama.required' => 'Nama tidak boleh kosong.',
            'nama.string' => 'Nama harus string/karakter yang dibenarkan.',
            'nama.min' => 'Nama minimal 3 karakter.',

            'role.required' => 'Role pengguna harus dipilih.'
        ];
    }
}
