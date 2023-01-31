<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\File;
use Illuminate\Validation\Rules\Password;

class BffRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        // you can modify these rules as desired.

        $rules = [
            // validate email address format and domain.
            'email' => ['bail', 'sometimes', 'string', 'email:rfc,dns,spoof', 'indisposable'],

            // validate phone number country and type.
            'phone' => ['bail', 'sometimes', 'string', 'phone:EG,SA,mobile'],

            // validate password strength and checks whether it appears on the have I been pwned list.
            'password' => ['bail', 'sometimes', 'string', Password::min(8)
                ->letters()
                ->mixedCase()
                ->numbers()
                ->symbols()
                ->uncompromised()
            ],

            'file' => $this->fileValidationRule(),
            'image' => $this->imageValidationRule(),
        ];

        return $rules + $this->dynamicFileRules($rules);
    }

    protected function dynamicFileRules(array $rules): array
    {
        $files = $this->files->all();

        if (! $files) {
            return [];
        }

        $files = Arr::dot($files);

        foreach ($files as $key => $file) {
            if (isset($rules[$key])) {
                continue;
            }

            $rules[$key] = (substr($file->getMimeType(), 0, 5) == 'image')
                ? $this->imageValidationRule()
                : $this->fileValidationRule();
        }

        return $rules;
    }

    protected function fileValidationRule(): array
    {
        return [
            'bail',
            'sometimes',
            File::types([
                'jpg', 'png', 'gif', 'webp',
                'pdf', 'csv', 'txt',
                'docx', 'xlsx', 'pptx',
                'doc',  'xls', 'ppt',
                'odt', 'ods', 'odp',
                // 'zip', 'rar', '7z',
            ])
                ->max(10 * 1024), // 10MB

            // please don't change this rule.
            // it used to scan all uploaded files with Cisco ClamAV.
            'clamav',
        ];
    }

    protected function imageValidationRule(): array
    {
        return [
            'bail',
            'sometimes',
            File::types(['jpg', 'png', 'gif', 'webp'])
                ->image()
                ->max(10 * 1024), // 10MB

            // please don't change this rule.
            // it used to scan all uploaded files with Cisco ClamAV.
            'clamav',
        ];
    }
}
