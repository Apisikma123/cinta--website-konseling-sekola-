<?php $__env->startSection('content'); ?>
<div class="w-full px-4 sm:px-6 lg:px-8 space-y-6">

    
    <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-3">
        <div>
            <h2 class="text-xl font-bold text-gray-900">Dashboard Admin</h2>
            <p class="text-sm text-gray-500 mt-1">Ringkasan data sistem bimbingan konseling</p>
        </div>
        <div class="flex items-center gap-2 bg-white border border-gray-200 rounded-lg px-3 py-1.5 text-sm text-gray-600">
            <i class="fas fa-calendar-day text-xs text-purple-600"></i>
            <span class="font-medium"><?php echo e(now()->translatedFormat('d F Y')); ?></span>
        </div>
    </div>

    
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
            <div class="flex items-start justify-between gap-3">
                <div>
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Sekolah Aktif</p>
                    <p class="text-2xl font-bold text-gray-900 mt-2"><?php echo e($stats['active_schools']); ?></p>
                    <p class="text-xs text-gray-500 mt-1">dari <?php echo e($stats['total_schools']); ?> total</p>
                </div>
                <div class="w-10 h-10 bg-emerald-50 rounded-lg flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-school text-emerald-600"></i>
                </div>
            </div>
        </div>

        
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
            <div class="flex items-start justify-between gap-3">
                <div>
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Sekolah Nonaktif</p>
                    <p class="text-2xl font-bold text-gray-900 mt-2"><?php echo e($stats['inactive_schools']); ?></p>
                    <p class="text-xs text-gray-500 mt-1">Memerlukan aktivasi</p>
                </div>
                <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-school text-gray-400"></i>
                </div>
            </div>
        </div>

        
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
            <div class="flex items-start justify-between gap-3">
                <div>
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Guru Terverifikasi</p>
                    <p class="text-2xl font-bold text-gray-900 mt-2"><?php echo e($stats['active_teachers']); ?></p>
                    <p class="text-xs text-gray-500 mt-1">Sudah disetujui</p>
                </div>
                <div class="w-10 h-10 bg-purple-50 rounded-lg flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-users text-purple-600"></i>
                </div>
            </div>
        </div>

        
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
            <div class="flex items-start justify-between gap-3">
                <div>
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Menunggu Persetujuan</p>
                    <p class="text-2xl font-bold text-gray-900 mt-2"><?php echo e($stats['pending_teachers']); ?></p>
                    <p class="text-xs text-gray-500 mt-1">Perlu disetujui</p>
                </div>
                <div class="w-10 h-10 bg-amber-50 rounded-lg flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-user-clock text-amber-600"></i>
                </div>
            </div>
        </div>
    </div>

    
    <div class="grid grid-cols-2 gap-3">
        <div class="bg-emerald-50 border border-emerald-200 rounded-lg p-3 text-center">
            <p class="text-2xl font-bold text-emerald-700"><?php echo e($schoolsChart['aktif']); ?></p>
            <p class="text-xs font-medium text-emerald-600 mt-0.5">Sekolah Aktif</p>
        </div>
        <div class="bg-gray-100 border border-gray-200 rounded-lg p-3 text-center">
            <p class="text-2xl font-bold text-gray-700"><?php echo e($schoolsChart['nonaktif']); ?></p>
            <p class="text-xs font-medium text-gray-600 mt-0.5">Sekolah Nonaktif</p>
        </div>
    </div>

    
    <?php if($stats['pending_teachers'] > 0): ?>
    <div class="bg-amber-50 border border-amber-200 rounded-lg px-4 py-3 flex items-center justify-between gap-3 flex-wrap">
        <div class="flex items-center gap-3">
            <i class="fas fa-bell text-amber-600"></i>
            <p class="text-sm text-amber-800">
                Ada <strong><?php echo e($stats['pending_teachers']); ?></strong> guru yang menunggu persetujuan
            </p>
        </div>
        <a href="/admin/approve-teachers" class="text-sm font-medium text-purple-600 hover:text-purple-700">
            Tinjau sekarang <i class="fas fa-arrow-right text-xs ml-1"></i>
        </a>
    </div>
    <?php endif; ?>

    
    <?php if($teachers->count()): ?>
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="px-5 py-3.5 border-b border-gray-100 flex items-center justify-between flex-wrap gap-3">
            <div class="flex items-center gap-2">
                <i class="fas fa-users text-xs text-purple-600"></i>
                <span class="text-sm font-semibold text-gray-900">Guru Terdaftar</span>
            </div>
            <a href="/admin/teachers" class="text-sm font-medium text-purple-600 hover:text-purple-700">
                Lihat semua <i class="fas fa-arrow-right text-xs ml-1"></i>
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full min-w-[640px]">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="px-5 py-2.5 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Nama</th>
                        <th class="px-4 py-2.5 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Email</th>
                        <th class="px-4 py-2.5 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Sekolah</th>
                        <th class="px-4 py-2.5 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <?php $__currentLoopData = $teachers->take(5); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $teacher): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        $teacherInitials = strtoupper(substr(explode(' ', $teacher->name)[0] ?? '', 0, 1)) . strtoupper(substr(explode(' ', $teacher->name)[1] ?? '', 0, 1));
                    ?>
                    <tr class="hover:bg-gray-50">
                        <td class="px-5 py-3">
                            <div class="flex items-center gap-2.5">
                                <?php if($teacher->profile_photo): ?>
                                    <img src="<?php echo e(asset('storage/' . $teacher->profile_photo)); ?>"
                                         alt="<?php echo e($teacher->name); ?>"
                                         class="w-8 h-8 rounded-full object-cover flex-shrink-0 border border-gray-200"
                                         onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                    <div class="w-8 h-8 rounded-full bg-purple-100 text-purple-600 flex items-center justify-center text-xs font-bold flex-shrink-0 hidden">
                                        <?php echo e($teacherInitials ?: strtoupper(substr($teacher->name, 0, 1))); ?>

                                    </div>
                                <?php else: ?>
                                    <div class="w-8 h-8 rounded-full bg-purple-100 text-purple-600 flex items-center justify-center text-xs font-bold flex-shrink-0">
                                        <?php echo e($teacherInitials ?: strtoupper(substr($teacher->name, 0, 1))); ?>

                                    </div>
                                <?php endif; ?>
                                <span class="text-sm font-semibold text-gray-900"><?php echo e($teacher->name); ?></span>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-600"><?php echo e($teacher->email); ?></td>
                        <td class="px-4 py-3 text-sm text-gray-600"><?php echo e($teacher->school ?? '—'); ?></td>
                        <td class="px-4 py-3">
                            <?php if($teacher->is_active): ?>
                                <span class="inline-flex items-center gap-1.5 text-xs font-medium px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>Aktif
                                </span>
                            <?php else: ?>
                                <span class="inline-flex items-center gap-1.5 text-xs font-medium px-2.5 py-1 rounded-full bg-gray-100 text-gray-600 border border-gray-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span>Nonaktif
                                </span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php else: ?>
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm py-14 text-center">
        <div class="w-12 h-12 bg-gray-100 rounded-xl flex items-center justify-center mx-auto mb-3">
            <i class="fas fa-users text-xl text-gray-300"></i>
        </div>
        <p class="text-sm font-semibold text-gray-900">Belum ada data</p>
    </div>
    <?php endif; ?>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', ['title' => 'Dashboard'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\ngoding\sistem-cinta\resources\views/admin/dashboard.blade.php ENDPATH**/ ?>