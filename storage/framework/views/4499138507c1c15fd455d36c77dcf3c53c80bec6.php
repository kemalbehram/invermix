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
                                <div class="card-header card_header_bg_blue" >
                                    <div class="card-head-row card-tools-still-right">
                                        <h4 class="card-title text-white">
                                            <i class="fas fa-plus"></i><?php echo e(__('Añadir Modalidad de Inversión')); ?>

                                        </h4>
                                    </div>
                                </div>
                                <div class="card-body pb-0 table-responsive">
                                   <form id="add_new_pack" action="/admin/create/package" method="post" >
                                       <?php echo csrf_field(); ?>
                                        <div class="form-group row">
                                            <div class="col-sm-12">
                                                <label for="package_name"><?php echo e(__('Nombre Modalidad')); ?></label>
                                                <input id="package_name" type="text" class="regTxtBox" name="package_name" value="" required autocomplete="package_name" autofocus placeholder="Nombre modalidad de inversión">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-sm-6">
                                                <label for="min"><?php echo e(__('Inversión Mínima')); ?></label>
                                                <input id="min" type="number" class="regTxtBox" name="min" value="" required autocomplete="min" autofocus placeholder="Inversión mínima">
                                            </div>
                                             <div class="col-sm-6">
                                                <label for="max" class=""><?php echo e(__('Inversión Máxima')); ?></label>
                                                <input id="max" type="number" class="regTxtBox" name="max" value="" required autocomplete="max" autofocus placeholder="Inversión máxima">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-sm-12">
                                                <label for="daily_interest"><?php echo e(__('Interés de la Modalidad (%)')); ?></label>
                                                <input id="daily_interest" step="0.1" type="number" class="regTxtBox" name="interest" value="" required autocomplete="daily_interest" autofocus placeholder="Interés porcentual para todo el período de inversión">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-sm-6">
                                                <label for="period"><?php echo e(__('Período total de inversión (Días)')); ?></label>
                                                <input id="period" step="1" type="number" class="regTxtBox" name="period" value="" required autocomplete="period" autofocus placeholder="Período total de inversión (Días)">
                                            </div>
                                             <div class="col-sm-6">
                                                <label for="interval" class=""><?php echo e(__('Intervalo de retiro (Días)')); ?></label>
                                                <input id="interval" type="number" class="regTxtBox" name="interval" value="" required autocomplete="interval" autofocus placeholder="Intervalo de retiro (Días)">
                                            </div>
                                        </div>
                                   </form>
                                   <div class="form-group row">
                                        <div class="col-sm-12 text-center">
                                            <br><br>
                                            <button class="btn btn-info btn_form" onclick="load_post_ajax('/admin/create/package', 'add_new_pack', 'add_pack')"><i class="fa fa-plus"></i> <?php echo e(__('Añadir')); ?> </button>
                                        </div>
                                    </div>

                                   <br><br>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.atlantis.layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\invermix\invermix\resources\views/admin/add_package.blade.php ENDPATH**/ ?>