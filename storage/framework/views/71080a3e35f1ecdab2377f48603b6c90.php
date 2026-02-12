<?php
$nameParts = explode(' ', auth()->user()->name);
$initials = strtoupper(substr($nameParts[0] ?? '', 0, 1)) . strtoupper(substr($nameParts[1] ?? '', 0, 1));
?>

<!-- Brand -->
<div class="flex items-center justify-between px-5 py-4 border-b border-gray-200">
    <div class="flex items-center gap-3">
        <img src="<?php echo e(asset('img/icon.png')); ?>" alt="Logo" class="w-8 h-8 rounded-lg object-contain flex-shrink-0">
        <span class="text-sm font-bold text-gray-900 tracking-tight">Admin Panel</span>
    </div>
    <button @click="sidebarOpen = false" class="lg:hidden p-1 text-gray-400 hover:text-gray-600 rounded">
        <i class="fas fa-xmark text-base"></i>
    </button>
</div>

<!-- User Card -->
<div class="px-4 py-3 border-b border-gray-200">
    <div class="flex items-center gap-3 bg-gray-50 border border-gray-200 rounded-lg p-2.5">
        <?php if(auth()->user()->profile_photo): ?>
            <img src="<?php echo e(asset('storage/' . auth()->user()->profile_photo)); ?>" 
                 alt="<?php echo e(auth()->user()->name); ?>"
                 class="w-9 h-9 rounded-full object-cover flex-shrink-0 border border-gray-200">
        <?php else: ?>
            <div class="w-9 h-9 rounded-full bg-purple-600 text-white flex items-center justify-center text-xs font-bold flex-shrink-0">
                <?php echo e($initials ?: strtoupper(substr(auth()->user()->name, 0, 1))); ?>

            </div>
        <?php endif; ?>
        <div class="min-w-0 flex-1">
            <p class="text-sm font-semibold text-gray-900 truncate leading-tight"><?php echo e(auth()->user()->name); ?></p>
            <p class="text-xs text-purple-600 font-medium mt-0.5">Administrator</p>
        </div>
    </div>
</div>

<!-- Navigation -->
<nav class="flex-1 px-3 py-3 overflow-y-auto space-y-1">
    <p class="text-[10px] font-semibold text-gray-400 uppercase tracking-widest px-3 pt-1 pb-2">Menu Utama</p>

    <a href="/admin/dashboard" @click="sidebarOpen = false"
       class="flex items-center gap-3 py-2.5 px-4 rounded-lg text-sm font-medium transition-colors duration-150 <?php echo e(request()->is('admin/dashboard') ? 'bg-purple-100 text-purple-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900'); ?>">
        <i class="fas fa-table-cells-large w-5 text-center <?php echo e(request()->is('admin/dashboard') ? 'text-purple-600' : 'text-gray-400'); ?>"></i>
        <span>Dashboard</span>
    </a>

    <a href="/admin/approve-teachers" @click="sidebarOpen = false"
       class="flex items-center gap-3 py-2.5 px-4 rounded-lg text-sm font-medium transition-colors duration-150 <?php echo e(request()->is('admin/approve-teachers') ? 'bg-purple-100 text-purple-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900'); ?>">
        <i class="fas fa-user-check w-5 text-center <?php echo e(request()->is('admin/approve-teachers') ? 'text-purple-600' : 'text-gray-400'); ?>"></i>
        <span>Persetujuan Guru</span>
    </a>

    <a href="/admin/teachers" @click="sidebarOpen = false"
       class="flex items-center gap-3 py-2.5 px-4 rounded-lg text-sm font-medium transition-colors duration-150 <?php echo e(request()->is('admin/teachers') ? 'bg-purple-100 text-purple-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900'); ?>">
        <i class="fas fa-users w-5 text-center <?php echo e(request()->is('admin/teachers') ? 'text-purple-600' : 'text-gray-400'); ?>"></i>
        <span>Data Guru</span>
    </a>

    <a href="/admin/schools" @click="sidebarOpen = false"
       class="flex items-center gap-3 py-2.5 px-4 rounded-lg text-sm font-medium transition-colors duration-150 <?php echo e(request()->is('admin/schools') ? 'bg-purple-100 text-purple-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900'); ?>">
        <i class="fas fa-school w-5 text-center <?php echo e(request()->is('admin/schools') ? 'text-purple-600' : 'text-gray-400'); ?>"></i>
        <span>Data Sekolah</span>
    </a>

    <a href="<?php echo e(route('admin.testimonials')); ?>" @click="sidebarOpen = false"
       class="flex items-center gap-3 py-2.5 px-4 rounded-lg text-sm font-medium transition-colors duration-150 <?php echo e(request()->routeIs('admin.testimonials') ? 'bg-purple-100 text-purple-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900'); ?>">
        <i class="fas fa-star w-5 text-center <?php echo e(request()->routeIs('admin.testimonials') ? 'text-purple-600' : 'text-amber-400'); ?>"></i>
        <span>Testimoni</span>
    </a>

    <div class="my-2 border-t border-gray-200"></div>
    <p class="text-[10px] font-semibold text-gray-400 uppercase tracking-widest px-3 pt-1 pb-2">Akun</p>

    <a href="<?php echo e(route('admin.profile')); ?>" @click="sidebarOpen = false"
       class="flex items-center gap-3 py-2.5 px-4 rounded-lg text-sm font-medium transition-colors duration-150 <?php echo e(request()->routeIs('admin.profile') ? 'bg-purple-100 text-purple-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900'); ?>">
        <i class="fas fa-id-badge w-5 text-center <?php echo e(request()->routeIs('admin.profile') ? 'text-purple-600' : 'text-gray-400'); ?>"></i>
        <span>Profil</span>
    </a>

    <a href="<?php echo e(route('admin.settings')); ?>" @click="sidebarOpen = false"
       class="flex items-center gap-3 py-2.5 px-4 rounded-lg text-sm font-medium transition-colors duration-150 <?php echo e(request()->routeIs('admin.settings') ? 'bg-purple-100 text-purple-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900'); ?>">
        <i class="fas fa-sliders w-5 text-center <?php echo e(request()->routeIs('admin.settings') ? 'text-purple-600' : 'text-gray-400'); ?>"></i>
        <span>Pengaturan</span>
    </a>
</nav>

<!-- Logout -->
<div class="p-3 border-t border-gray-200">
    <form method="POST" action="<?php echo e(route('logout')); ?>">
        <?php echo csrf_field(); ?>
        <button type="submit"
                class="flex items-center gap-3 py-2.5 px-4 rounded-lg text-sm font-medium text-red-500 hover:bg-red-50 w-full transition-colors duration-150">
            <i class="fas fa-arrow-right-from-bracket w-5 text-center"></i>
            <span>Keluar</span>
        </button>
    </form>
</div>
<?php /**PATH D:\ngoding\sistem-cinta\resources\views/layouts/partials/admin-sidebar-content.blade.php ENDPATH**/ ?>