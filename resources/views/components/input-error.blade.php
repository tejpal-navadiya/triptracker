@props(['messages'])

@if ($messages)
    <ul {{ $attributes->merge(['class' => 'text-sm login_ul text-danger pl-0 mb-0']) }}>
        @foreach ((array) $messages as $message)
            <li>{{ $message }}</li>
        @endforeach
    </ul>
@endif
