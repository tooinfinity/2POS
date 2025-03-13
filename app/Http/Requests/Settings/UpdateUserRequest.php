<?php

declare(strict_types=1);

namespace App\Http\Requests\Settings;

use App\Enums\RoleEnum;
use App\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Override;

final class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        /** @var User|null $userToUpdate */
        $userToUpdate = $this->route('user');
        $currentUser = $this->user();

        if (! $currentUser || ! $userToUpdate) {
            return false;
        }

        return $currentUser->hasRole(RoleEnum::Administrator->value) &&
            $currentUser->id !== $userToUpdate->id;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($this->route('user')),
            ],
            'password' => [
                'required',
                'confirmed',
                Password::min(8)->mixedCase()->numbers()->symbols(),
            ],
            'role' => [
                'sometimes',
                'string',
                Rule::exists('roles', 'name')->whereNot('name', RoleEnum::Administrator->value),
            ],
            'permissions' => ['sometimes', 'array'],
            'permissions.*' => ['exists:permissions,id'],
        ];
    }

    /**
     * Get the validation messages that apply to the request.
     *
     * @return array<string, string>
     */
    #[Override]
    public function messages(): array
    {
        return [
            'email.unique' => 'This email is already registered',
            'role.exists' => 'Selected role does not exist',
            'permissions.*.exists' => 'One or more selected permissions are invalid',
            'password.min' => 'Password must be at least 8 characters',
        ];
    }

    protected function prepareForValidation(): void
    {
        $email = $this->input('email');
        if (is_string($email)) {
            $this->merge([
                'email' => mb_strtolower($email),
            ]);
        }
    }
}
