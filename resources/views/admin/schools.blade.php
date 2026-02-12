@extends('layouts.admin', ['title' => 'Data Sekolah'])

@section('content')
<div x-data="schoolManager()" class="w-full px-4 sm:px-6 lg:px-8 space-y-6">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-3">
        <div>
            <h2 class="text-xl font-bold text-gray-900">Data Sekolah</h2>
            <p class="text-sm text-gray-500 mt-1">Kelola daftar sekolah dalam sistem</p>
        </div>
        <button @click="openModal()" class="inline-flex items-center gap-2 bg-primary hover:bg-primary/90 text-white text-sm font-medium px-4 py-2.5 rounded-lg transition-colors duration-150">
            <i class="fas fa-plus text-xs"></i> Tambah Sekolah
        </button>
    </div>

    {{-- Table --}}
    @if($schools->count())
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full min-w-[600px]">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="px-5 py-2.5 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Nama Sekolah</th>
                        <th class="px-4 py-2.5 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Kota</th>
                        <th class="px-4 py-2.5 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                        <th class="px-5 py-2.5 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($schools as $school)
                    @php
                        $schoolInitial = strtoupper(substr($school->name, 0, 1));
                    @endphp
                    <tr class="hover:bg-gray-50">
                        <td class="px-5 py-3">
                            <div class="flex items-center gap-2.5">
                                <div class="w-8 h-8 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center text-xs font-bold flex-shrink-0">
                                    {{ $schoolInitial }}
                                </div>
                                <span class="text-sm font-semibold text-gray-900">{{ $school->name }}</span>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-600">{{ $school->city ?? '—' }}</td>
                        <td class="px-4 py-3">
                            @if($school->is_active)
                                <span class="inline-flex items-center gap-1.5 text-xs font-medium px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>Aktif
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 text-xs font-medium px-2.5 py-1 rounded-full bg-gray-100 text-gray-600 border border-gray-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span>Nonaktif
                                </span>
                            @endif
                        </td>
                        <td class="px-5 py-3 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <button @click='editSchool(@json($school))'
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 text-sm font-medium text-primary bg-primary/10 border border-primary/20 rounded-lg hover:bg-primary/20 transition-colors duration-150">
                                    <i class="fas fa-pen text-xs"></i> Edit
                                </button>

                                <form id="delete-school-{{ $school->id }}" action="/admin/schools/{{ $school->id }}" method="POST">
                                    @csrf @method('DELETE')
                                    <button type="button"
                                            onclick="SwalUtils.delete(() => document.getElementById('delete-school-{{ $school->id }}').submit())"
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 text-sm font-medium text-red-600 bg-white border border-red-200 rounded-lg hover:bg-red-50 transition-colors duration-150">
                                        <i class="fas fa-trash-can text-xs"></i> Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if($schools->hasPages())
        <div class="px-5 py-3 border-t border-gray-100 bg-gray-50">
            {{ $schools->links() }}
        </div>
        @endif
    </div>
    @else
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm py-14 text-center">
        <div class="w-12 h-12 bg-gray-100 rounded-xl flex items-center justify-center mx-auto mb-3">
            <i class="fas fa-school text-xl text-gray-300"></i>
        </div>
        <p class="text-sm font-semibold text-gray-900">Belum ada data</p>
        <p class="text-sm text-gray-500 mt-1">Tambahkan sekolah pertama dengan tombol di atas</p>
    </div>
    @endif

    {{-- Modal --}}
    <div x-show="modal" x-cloak class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-gray-900/50"
         x-transition:enter="transition-opacity ease-out duration-200" x-transition:leave="transition-opacity ease-in duration-150">
        <div @click.away="modal = false" class="bg-white rounded-xl shadow-xl w-full max-w-md overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                <h3 class="text-base font-semibold text-gray-900" x-text="editMode ? 'Edit Sekolah' : 'Tambah Sekolah'"></h3>
                <button @click="modal = false" class="text-gray-400 hover:text-gray-600"><i class="fas fa-xmark"></i></button>
            </div>

            <form :action="editMode ? '/admin/schools/' + form.id : '/admin/schools'" method="POST" class="p-5 space-y-4">
                @csrf
                <template x-if="editMode"><input type="hidden" name="_method" value="PUT"></template>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Sekolah</label>
                    <input type="text" name="name" x-model="form.name" required
                           class="w-full py-2 px-3 border border-gray-300 rounded-lg text-base focus:ring-2 focus:ring-primary focus:border-transparent outline-none">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Kota</label>
                    <input type="text" name="city" x-model="form.city"
                           class="w-full py-2 px-3 border border-gray-300 rounded-lg text-base focus:ring-2 focus:ring-purple-500 focus:border-transparent outline-none">
                </div>
                <div class="flex items-center gap-2">
                    <input type="checkbox" name="is_active" value="1" x-model="form.is_active"
                           class="rounded border-gray-300 text-primary focus:ring-primary">
                    <label class="text-sm text-gray-700 font-medium">Sekolah Aktif</label>
                </div>

                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" @click="modal = false" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-200 rounded-lg hover:bg-gray-50">Batal</button>
                    <button type="submit" class="px-6 py-2 text-sm font-medium text-white bg-primary rounded-lg hover:bg-primary/90" x-text="editMode ? 'Simpan' : 'Tambah'"></button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
function schoolManager() {
    return {
        modal: false,
        editMode: false,
        form: { id: null, name: '', city: '', is_active: true },
        openModal() {
            this.editMode = false;
            this.form = { id: null, name: '', city: '', is_active: true };
            this.modal = true;
        },
        editSchool(school) {
            this.editMode = true;
            this.form = { id: school.id, name: school.name, city: school.city || '', is_active: Boolean(school.is_active) };
            this.modal = true;
        }
    };
}
</script>
@endpush
@endsection
