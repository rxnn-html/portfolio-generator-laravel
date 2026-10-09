@props([
    'label',
    'model',            {{-- Alpine variable, e.g. "f.email" or "row.school" --}}
    'name',             {{-- JavaScript expression for the field name, e.g. "'email'" --}}
    'required' => false,
    'type' => 'text',
    'placeholder' => '',
    'rows' => 0,        {{-- more than 0 = a textarea --}}
    'max' => null,
    'hint' => null,
    'autocomplete' => null,
])

@php
    $class = 'w-full rounded-xl border border-gray-300 bg-white px-3.5 py-2.5 text-sm text-gray-900 placeholder-gray-400 shadow-xs transition focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/25';
@endphp

<label class="block">
    <span class="mb-1.5 flex items-center justify-between gap-2 text-sm font-medium text-gray-700">
        <span>{{ $label }}@if ($required)<span class="text-red-500"> *</span>@endif</span>
        @if ($rows && $max)
            <span class="text-xs tabular-nums text-gray-400" x-text="String({{ $model }} ?? '').length + '/{{ $max }}'"></span>
        @endif
    </span>

    @if ($rows)
        <textarea rows="{{ $rows }}" x-model="{{ $model }}" :name="{{ $name }}"
                  @if ($max) maxlength="{{ $max }}" @endif
                  placeholder="{{ $placeholder }}" class="{{ $class }} resize-y"></textarea>
    @else
        <input type="{{ $type }}" x-model="{{ $model }}" :name="{{ $name }}"
               @if ($max) maxlength="{{ $max }}" @endif
               @if ($autocomplete) autocomplete="{{ $autocomplete }}" @endif
               placeholder="{{ $placeholder }}" class="{{ $class }}">
    @endif

    @if ($hint)
        <span class="mt-1.5 block text-xs text-gray-500">{{ $hint }}</span>
    @endif
</label>