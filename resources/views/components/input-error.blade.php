@props(['for'])

@error($for)
    <p {{ $attributes->merge(['class' => 'error-message']) }}>{{ $message }}</p>
@enderror
