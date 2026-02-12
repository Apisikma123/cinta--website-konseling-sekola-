@forelse($counselors as $counselor)
    <div class="fade-in-up">
        <x-counselor-card :counselor="$counselor" />
    </div>
@empty
    <div class="bg-white rounded-lg border border-gray-200 p-12 text-center col-span-full">
        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <i class="fas fa-user-clock text-2xl text-gray-400"></i>
        </div>
        <h3 class="text-lg font-semibold text-gray-900 mb-2">Belum Ada Data Guru</h3>
        <p class="text-gray-600 mb-6">Data guru akan segera ditambahkan.</p>
    </div>
@endforelse