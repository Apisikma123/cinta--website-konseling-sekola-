<form {{ $attributes->merge(['class' => 'space-y-6']) }}>
    @csrf
    @method($method ?? 'POST')
    
    {{ $slot }}
</form>
