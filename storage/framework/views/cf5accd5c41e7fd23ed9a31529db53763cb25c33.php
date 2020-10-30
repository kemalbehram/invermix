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
                                            <i class="fas fa-plus"></i><?php echo e(__('Añadir Compañía')); ?>

                                        </h4>
                                    </div>
                                </div>
                                <div class="card-body pb-0 table-responsive">
                                   <form id="add_new_pack" action="/admin/create/package" method="post" >
                                       <?php echo csrf_field(); ?>
                                        <div class="form-group row">
                                            <div class="col-sm-12">
                                                <label for="package_name"><?php echo e(__('Nombre Compañía')); ?></label>
                                                <input id="package_name" type="text" class="regTxtBox" name="name_comp" value="" required autocomplete="name_comp" autofocus placeholder="Nombre de compañía">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-sm-6">
                                                <label for="min"><?php echo e(__('RNC')); ?></label>
                                                <input id="min" type="number" class="regTxtBox" name="rnc" value="" required autocomplete="min" autofocus placeholder="RNC">
                                            </div>
                                            <div class="col-sm-6">
                                                <label for="max" class=""><?php echo e(__('Correo Electrónico')); ?></label>
                                                <input id="email" type="email" class="regTxtBox" name="email" value="" required autocomplete="email" autofocus placeholder="Correo electrónico">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-sm-6">
                                                <label for="min"><?php echo e(__('Capital Apertura')); ?></label>
                                                <input id="min" type="number" class="regTxtBox" name="o_capital" value="" required autocomplete="o_capital" autofocus placeholder="Capital de Apertura">
                                            </div>

                                                <div class="col-sm-6">
                                                    <label for="min"><?php echo e(__('Capital Disponible')); ?></label>
                                                    <input id="min" type="number" class="regTxtBox" name="a_capital" value="" required autocomplete="a_capital" autofocus placeholder="Capital de Apertura">
                                                </div>
                                        </div>

                                        <div class="form-group row">
                                            <div class="col-sm-6">
                                                <label for="min"><?php echo e(__('Bonos Aperturados')); ?></label>
                                                <input id="min" type="number" class="regTxtBox" name="sold_bonus" value="" required autocomplete="sold_bonus" autofocus placeholder="Bonos Aperturados">
                                            </div>

                                                <div class="col-sm-6">
                                                    <label for="min"><?php echo e(__('Bonos Vendidos')); ?></label>
                                                    <input id="min" type="number" class="regTxtBox" name="a_bonus" value="" required autocomplete="a_bonus" autofocus placeholder="Bonos Vendidos">
                                                </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-sm-6">
                                                <label for="min"><?php echo e(__('Bonos Disponible')); ?></label>
                                                <input id="min" type="number" class="regTxtBox" name="bonus_cost" value="" required autocomplete="bonus_cost" autofocus placeholder="Bonos Disponible">
                                            </div>

                                                <div class="col-sm-6">
                                                    <label for="min"><?php echo e(__('Bonos Costo')); ?></label>
                                                    <input id="min" type="number" class="regTxtBox" name="bonus_cost" value="" required autocomplete="mbonus_costin" autofocus placeholder="Bonos Costo">
                                                </div>
                                        </div>

                                        <div class="form-group row">
                                             <div class="col-sm-6">
                                                <label for="interval" class=""><?php echo e(__('Moneda')); ?></label>
                                                <input id="currency" type="currency" class="regTxtBox" name="currency_id" value="" required autocomplete="currency_id" autofocus placeholder="Moneda de la compañía">
                                            </div>
                                        </div>
                                   </form>
                                   <div class="form-group row">
                                        <div class="col-sm-12 text-center">
                                            <br><br>
                                            <button class="btn btn-info btn_form" onclick="load_post_ajax('/admin/create/company', 'add_new_pack', 'add_pack')"><i class="fa fa-plus"></i> <?php echo e(__('Añadir')); ?> </button>
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

<?php echo $__env->make('admin.atlantis.layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Applications/MAMP/htdocs/invermix/resources/views/admin/companies/form.blade.php ENDPATH**/ ?>