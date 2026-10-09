@php $steps = ['Your details', 'Choose a template', 'Preview']; @endphp

<ol class="mb-8 flex items-center gap-2 sm:gap-3" aria-label="Progress">
    @foreach ($steps as $i => $label)
        @php $done = $i < $current; $active = $i === $current; @endphp
        <li class="flex flex-1 items-center gap-2 last:flex-none sm:gap-3">
            <span @if ($active) aria-current="step" @endif
                  class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full text-sm font-bold
                  {{ $done ? 'bg-emerald-500 text-white' : ($active ? 'bg-indigo-600 text-white ring-4 ring-indigo-500/20' : 'bg-gray-100 text-gray-400') }}">
                {!! $done ? '&#10003;' : $i + 1 !!}
            </span>
            <span class="text-sm font-medium {{ $active ? 'inline text-gray-900' : 'hidden text-gray-500 sm:inline' }}">{{ $label }}</span>
            @if (! $loop->last) <span class="h-px flex-1 bg-gray-200"></span> @endif
        </li>
    @endforeach
</ol>