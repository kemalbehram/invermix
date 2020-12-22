<?php ($packs = search_pack()); ?>

<?php $__env->startSection('content'); ?>
        <div class="main-panel">
            <div class="content">
                <?php echo $__env->make('admin.atlantis.main_bar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                <div class="page-inner mt--5">
                    <?php echo $__env->make('admin.atlantis.overview', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                    <div id="prnt"></div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header card_header_bg_blue">
                                    <div class="card-head-row card-tools-still-right">
                                        <h4 class="card-title text-white"> <i class="fas fa-briefcase"></i> <?php echo e(__('Compañías')); ?> </h4>
                                        <div class="card-tools">
                                            <a href="/admin/create/company" class="btn btn-default"><i class="fa fa-plus"></i> <?php echo e(__('Añadir')); ?> </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body pb-0 table-responsive">
                                   <?php echo $__env->make('admin.temp.companie', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <?php echo $__env->make('admin.companies.edit', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.atlantis.layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Applications/MAMP/htdocs/invermix/resources/views/admin/companies/index.blade.php ENDPATH**/ ?>