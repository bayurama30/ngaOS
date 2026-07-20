<button {{ $attributes->merge(['type' => 'submit', 'class' => 'btn-danger inline-flex items-center']) }}>
    {{ $slot }}
</button>
