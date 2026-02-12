<div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
    <!-- Table Header -->
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-200">
                    {{ $slot }}
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                {{ $body }}
            </tbody>
        </table>
    </div>

    <!-- Empty State -->
    @if(isset($empty) && $empty)
        <div class="py-12 text-center">
            <i data-feather="inbox" class="w-12 h-12 text-gray-300 mx-auto mb-3"></i>
            <p class="text-gray-500 font-medium">Tidak ada data</p>
        </div>
    @endif
</div>

<style>
    table th {
        @apply px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider;
    }

    table td {
        @apply px-6 py-4 text-sm text-gray-900;
    }

    table tbody tr {
        @apply hover:bg-gray-50 transition-colors duration-200;
    }
</style>
