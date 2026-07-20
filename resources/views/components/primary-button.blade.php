<button {{ $attributes->merge(['type' => 'submit', 'class' => 'btn-primary inline-flex items-center']) }}>
    {{ $slot }}
</button>
