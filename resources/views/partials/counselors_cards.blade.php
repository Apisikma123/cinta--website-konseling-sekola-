@foreach($counselors as $counselor)
    <div class="fade-in-up h-full" style="animation-delay: 0.05s">
        <x-counselor-card :counselor="$counselor" />
    </div>
@endforeach
