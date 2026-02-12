<div class="bg-white rounded-lg p-4 border border-gray-100">
    <div class="space-y-3">
        @foreach($steps as $idx => $step)
            <div class="flex items-start gap-3">
                <div class="w-8 h-8 rounded-full bg-purple-100 text-purple-700 flex items-center justify-center font-semibold">{{ $idx + 1 }}</div>
                <div class="text-sm text-gray-700">{{ $step }}</div>
            </div>
        @endforeach
    </div>
</div>