<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PortfolioRequest extends FormRequest
{
    public const PLATFORMS = ['GitHub', 'Facebook', 'LinkedIn', 'Instagram', 'Website'];

    public function authorize(): bool
    {
        return true; // no login in this project
    }

    // Runs BEFORE validation: trims text, drops empty rows, keeps only the fields we know
    protected function prepareForValidation(): void
    {
        $text = fn ($value) => is_string($value) && trim($value) !== '' ? trim($value) : null;

        $rows = function ($list, array $keys, ?string $mustHave = null) use ($text) {
            return collect(is_array($list) ? $list : [])
                ->filter(fn ($row) => is_array($row))
                ->map(fn ($row) => collect($keys)->mapWithKeys(fn ($key) => [$key => $text($row[$key] ?? null)])->all())
                ->filter(fn ($row) => $mustHave
                    ? $row[$mustHave] !== null
                    : collect($row)->contains(fn ($value) => $value !== null))
                ->values()
                ->all();
        };

        $skills = collect(is_array($this->input('skills')) ? $this->input('skills') : [])
            ->map($text)
            ->filter()
            ->unique(fn ($skill) => mb_strtolower($skill))
            ->values()
            ->all();

        $this->merge([
            'education'    => $rows($this->input('education'), ['school', 'degree', 'year', 'description']),
            'skills'       => $skills,
            'projects'     => $rows($this->input('projects'), ['project_name', 'description', 'technologies', 'project_link']),
            'experiences'  => $rows($this->input('experiences'), ['job_title', 'company', 'start_date', 'end_date', 'description']),
            // A social row with no URL is meaningless, so it is dropped
            'social_links' => $rows($this->input('social_links'), ['platform', 'url'], 'url'),
        ]);
    }

    public function rules(): array
    {
        return [
            'full_name'      => ['required', 'string', 'max:100'],
            'email'          => ['required', 'email', 'max:254'],
            'contact_number' => ['nullable', 'regex:/^[0-9+()\-\s]{7,20}$/'],
            'address'        => ['nullable', 'string', 'max:200'],
            'about_me'       => ['nullable', 'string', 'max:1500'],
            'image'          => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp', 'max:2048'], // 2 MB

            'education'               => ['array', 'max:10'],
            'education.*.school'      => ['required', 'string', 'max:150'],
            'education.*.degree'      => ['nullable', 'string', 'max:150'],
            'education.*.year'        => ['nullable', 'string', 'max:30'],
            'education.*.description' => ['nullable', 'string', 'max:1000'],

            'skills'   => ['array', 'max:30'],
            'skills.*' => ['string', 'max:50'],

            'projects'                    => ['array', 'max:20'],
            'projects.*.project_name'     => ['required', 'string', 'max:150'],
            'projects.*.description'      => ['nullable', 'string', 'max:1000'],
            'projects.*.technologies'     => ['nullable', 'string', 'max:200'],
            'projects.*.project_link'     => ['nullable', 'url:http,https', 'max:500'],

            'experiences'                 => ['array', 'max:20'],
            'experiences.*.job_title'     => ['required', 'string', 'max:150'],
            'experiences.*.company'       => ['required', 'string', 'max:150'],
            'experiences.*.start_date'    => ['nullable', 'string', 'max:30'],
            'experiences.*.end_date'      => ['nullable', 'string', 'max:30'],
            'experiences.*.description'   => ['nullable', 'string', 'max:1000'],

            'social_links'            => ['array', 'max:10'],
            'social_links.*.platform' => ['required', 'in:'.implode(',', self::PLATFORMS)],
            'social_links.*.url'      => ['required', 'url:http,https', 'max:500'],
        ];
    }

    // Friendlier names in error messages
    public function attributes(): array
    {
        return [
            'full_name' => 'full name',
            'contact_number' => 'contact number',
            'about_me' => 'about me',
            'education.*.school' => 'education school',
            'projects.*.project_name' => 'project name',
            'projects.*.project_link' => 'project link',
            'experiences.*.job_title' => 'job title',
            'experiences.*.company' => 'company',
            'social_links.*.url' => 'social link URL',
            'social_links.*.platform' => 'social link platform',
        ];
    }
}