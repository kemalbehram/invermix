<div class="sparkline8-graph dashone-comment  dashtwo-messages">
    <div class="comment-phara">
        <div class="row comment-adminpr">
            <?php
                $invs = App\packages::where('status', 1)->orderby('id', 'asc')->get();
            ?>
            <?php if($user->phone != ''): ?>
                <?php if(isset($invs) && count($invs) > 0): ?>
                    <?php $__currentLoopData = $invs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $inv): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="col-sm-4">
                            <div class="panel card pack-container" style="" align="center">
                                <div class="panel-head" style="">
                                    <h3 class="txt_transform"><?php echo e(__('Plan')); ?> <?php echo e($inv->package_name); ?> </h3>
                                </div>
                                <div class="" align="center" >
                                    <br>
                                        <h4 class="u_case" >
                                            <strong><?php echo e(__('Período de Inversión')); ?></strong>
                                        </h4>
                                        <div style="font-size: 40px;">
                                            <b>
                                                <?php echo e($inv->period); ?>

                                            </b>
                                        </div>
                                        <span class="pk_num">
                                                <?php echo e(__('Días')); ?>

                                        </span>
                                </div>
                                <br>
                                <span align="center">..............................</span>
                                <div class="" align="center" style="">
                                        <h4 class="u_case" >
                                            <strong><?php echo e(__('Inversión Mín.')); ?></strong>
                                        </h4>
                                        <span class="pk_num"><?php echo e('RD$'); ?> <?php echo e(number_format($inv->min),2); ?></span>
                                        <br>
                                        <span class="note"><?php echo e('US$'); ?> <?php echo e(number_format($inv->mindol),2); ?></span>
                                        <br>
                                        <br>
                                        <h4 class="u_case">
                                            <strong><?php echo e(__('Inversión Máx.')); ?></strong>
                                        </h4>

                                        <span class="pk_num"><?php echo e('RD$'); ?> <?php echo e(number_format($inv->max),2); ?></span>
                                        <br>
                                        <span class="note"><?php echo e('US$'); ?> <?php echo e(number_format($inv->maxdol),2); ?></span>

                                </div>


                                <span align="center">..............................</span>
                                <div class="" align="center">
                                    <h4 class="u_case">
                                        <strong>Interés Total</strong>
                                    </h4>
                                     <span class="pk_num"><?php echo e($inv->daily_interest*$inv->period*100); ?>%</span>
                                </div>
                                <br>
                                 <div class="" align="center">
                                    <h4 class="u_case">
                                       <strong> Intervalo de Retiro</strong>
                                    </h4>
                                    <span class="pk_num"><?php echo e($inv->days_interval); ?> Días</span>
                                </div>
                                <br>
                                <div class="" align="center">
                                    <p><?php echo e(__('Capital accesible una vez transcurrida la inversión.')); ?></p>
                                </div>
                                <div class="" align="center">
                                        <a id="<?php echo e($inv->id); ?>" href="javascript:void(0)" class="collcc btn btn-info" onclick="confirm_inv('<?php echo e($inv->id); ?>', '<?php echo e($inv->package_name); ?>', '<?php echo e($inv->period); ?>', '<?php echo e($inv->daily_interest); ?>', '<?php echo e($inv->min); ?>', '<?php echo e($inv->max); ?>','<?php echo e($inv->mindol); ?>', '<?php echo e($inv->maxdol); ?>',  '<?php echo e($user->wallet); ?>')">
                                            <?php echo e(__('Invertir')); ?>

                                        </a>
                                        <br><br>
                                </div>

                            </div>
                        </div>

                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php endif; ?>
            <?php else: ?>
                <div class="alert alert-warning">
                    <a><?php echo e(__('Por favor, actualice los datos principales del cliente para poder invertir.')); ?></a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php /**PATH /Applications/MAMP/htdocs/invermix/resources/views/user/inc/packages.blade.php ENDPATH**/ ?>