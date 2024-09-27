<button {{ $attributes->merge(['type' => 'submit', 'class' => 'btn login_btn']) }}>
    {{ $slot }}
</button>
