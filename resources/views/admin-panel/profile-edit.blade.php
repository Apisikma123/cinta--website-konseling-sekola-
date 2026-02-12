@extends('layouts.admin-panel', ['title' => 'Edit Profil'])

@section('content')
<div class="max-w-2xl">
    <!-- Page Header -->
    <div class="mb-8">
        <h2 class="text-2xl font-bold text-gray-900">Edit Profil</h2>
        <p class="text-gray-600 mt-1">Perbarui informasi profil Anda.</p>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-lg border border-gray-200 p-6 sm:p-8">
        <x-admin-panel.form action="#" method="POST" class="space-y-6">
            <!-- Profile Photo Section -->
            <div class="pb-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Foto Profil</h3>
                
                <div class="flex items-center gap-6">
                    <div class="w-20 h-20 rounded-lg bg-purple-100 text-purple-600 flex items-center justify-center font-bold text-2xl flex-shrink-0">
                        DR
                    </div>
                    
                    <div class="flex-1">
                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-purple-400 transition-colors duration-200 cursor-pointer">
                            <i data-feather="upload-cloud" class="w-8 h-8 text-gray-400 mx-auto mb-2"></i>
                            <p class="text-sm font-medium text-gray-900">Klik untuk upload foto</p>
                            <p class="text-xs text-gray-500 mt-1">PNG, JPG hingga 1MB</p>
                            <input type="file" accept="image/*" class="hidden">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Personal Information -->
            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Informasi Pribadi</h3>
                
                <div class="space-y-6">
                    <x-admin-panel.form-field 
                        name="name" 
                        label="Nama Lengkap" 
                        type="text"
                        value="Dr. Rini Suryani"
                        required />

                    <x-admin-panel.form-field 
                        name="email" 
                        label="Email" 
                        type="email"
                        value="rini.suryani@email.com"
                        required />

                    <x-admin-panel.form-field 
                        name="phone" 
                        label="Nomor Telepon" 
                        type="tel"
                        value="+62 812 3456 7890" />

                    <x-admin-panel.form-field 
                        name="school" 
                        label="Sekolah" 
                        type="text"
                        value="SMA Negeri 1 Jakarta"
                        hint="Nama sekolah tempat Anda mengajar" />
                </div>
            </div>

            <!-- Security Section -->
            <div class="pt-6 border-t border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Keamanan</h3>
                
                <div class="space-y-6">
                    <x-admin-panel.form-field 
                        name="current_password" 
                        label="Password Saat Ini" 
                        type="password"
                        hint="Masukkan password Anda untuk mengubah password" />

                    <x-admin-panel.form-field 
                        name="new_password" 
                        label="Password Baru" 
                        type="password"
                        hint="Minimal 8 karakter" />

                    <x-admin-panel.form-field 
                        name="confirm_password" 
                        label="Konfirmasi Password" 
                        type="password" />
                </div>
            </div>

            <!-- Form Actions -->
            <div class="pt-6 border-t border-gray-200 flex gap-3 justify-end">
                <a href="#" class="px-6 py-2 border border-gray-300 rounded-lg font-medium text-gray-700 hover:bg-gray-50 transition-colors duration-200">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2 bg-purple-600 text-white rounded-lg font-medium hover:bg-purple-700 transition-colors duration-200">
                    Simpan Perubahan
                </button>
            </div>
        </x-admin-panel.form>
    </div>

    <!-- Danger Zone -->
    <div class="mt-8 bg-white rounded-lg border border-red-200 p-6 sm:p-8">
        <h3 class="text-lg font-semibold text-red-900 mb-2">Zona Berbahaya</h3>
        <p class="text-sm text-red-700 mb-4">Tindakan berikut tidak dapat dibatalkan.</p>
        
        <button class="px-6 py-2 bg-red-600 text-white rounded-lg font-medium hover:bg-red-700 transition-colors duration-200">
            Hapus Akun
        </button>
    </div>
</div>
@endsection
