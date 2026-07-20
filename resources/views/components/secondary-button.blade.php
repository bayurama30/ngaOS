<button {{ $attributes->merge(['type' => 'button', 'class' => 'btn-secondary inline-flex items-center']) }}>
    {{ $slot }}
</button>
