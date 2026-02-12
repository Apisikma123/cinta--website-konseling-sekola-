<?php
    $variant = $variant ?? 'primary';
    $base = 'inline-flex items-center px-4 py-2 rounded-md font-semibold text-sm uppercase tracking-widest focus:outline-none focus:ring-2 focus:ring-offset-2 transition ease-in-out duration-150';

    $variants = [
        'primary' => $base . ' bg-gradient-to-r from-purple-600 to-pink-600 text-white hover:from-purple-700 hover:to-pink-700 shadow',
        'success' => $base . ' bg-purple-600 text-white hover:bg-purple-700 shadow',
        'outline' => $base . ' border border-purple-200 text-purple-700 bg-white hover:bg-purple-50',
        'ghost' => $base . ' text-gray-700 bg-transparent hover:bg-gray-100',
        'danger' => $base . ' bg-red-600 text-white hover:bg-red-700',
    ];

    $classes = $variants[$variant] ?? $variants['primary'];
?>

<button <?php echo e($attributes->merge(['type' => $attributes->get('type', 'submit'), 'class' => $classes])); ?>>
    <?php echo e($slot); ?>

</button><?php /**PATH D:\ngoding\sistem-cinta\resources\views/components/button.blade.php ENDPATH**/ ?>