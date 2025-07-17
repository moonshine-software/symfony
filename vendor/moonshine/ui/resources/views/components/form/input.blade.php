<input {{ $attributes->merge([
    'class' => !in_array($attributes->get('type'), ['checkbox', 'radio', 'color', 'range'])
        ? 'form-input'
        : 'form-' . $attributes->get('type', 'input'),
    'type' => 'text'])
    }}
/>
