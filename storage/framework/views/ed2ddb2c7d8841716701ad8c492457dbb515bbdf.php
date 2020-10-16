<?php $__env->startSection('content'); ?>
    <div id="app">
        <form-transaction _host="<?php echo e(url('/')); ?>" _payload="<?php echo e($payload); ?>"></form-transaction>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('coinpayment::layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\dh\core\vendor\hexters\coinpayment\src\Providers/../Resources/views/transaction/make/show.blade.php ENDPATH**/ ?>