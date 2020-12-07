
<div class="alert alert-info inv_alert_cont" >
    <div class="row inv_alert_top_row">
        <div class="col-xs-12 pad_top_5" align="center" >
            <h4 class="u_case"><?php echo e(__('Modalidad')); ?>: <?php echo e($in->package); ?></h4>

        </div>
    </div>
    <br>
    <div class="row color_blue_9">
        <div class="col-xs-6">
            <?php echo e(__('Capital:')); ?>

        </div>
        &nbsp;
        <div class="col-xs-6">
            <?php echo e(($in->currency)); ?> <?php echo e($in->capital); ?>

        </div>
    </div>
    <div class="row" style="">
        <div class="col-xs-6">
            <?php echo e(__('Retorno:')); ?>

        </div>
        &nbsp;
        <div class="col-xs-6">
            <?php echo e(($in->currency)); ?> <?php echo e($in->i_return); ?>

        </div>
    </div>
    <div class="row" style="">
        <div class="col-xs-6">
            <?php echo e(__('Desde:')); ?>

        </div>
        &nbsp;
        <div class="col-xs-6">
            <?php echo e($in->date_invested); ?>

        </div>
    </div>
    <div class="row" style="">
        <div class="col-xs-6">
            <?php echo e(__('Hasta:')); ?>

        </div>
        &nbsp;
        <div class="col-xs-6">
            <?php echo e($in->end_date); ?>

        </div>
    </div>
    <div class="row" style="">
        <div class="col-xs-6">
            <?php echo e(__('Días Transcurridos:')); ?>

        </div>
        &nbsp;
        <div class="col-xs-6">
            <?php echo e($totalDays); ?>

        </div>
    </div>
    <div class="row" style="">
        <div class="col-xs-6">
           <?php echo e(__('Retirados:')); ?>

        </div>
        &nbsp;
        <div class="col-xs-6">
            <?php echo e(($in->currency)); ?> <?php echo e($in->w_amt); ?>

        </div>
    </div>
    <div class="row" style="">
        <div class="col-xs-6">
            <?php echo e(__('Status:')); ?>

        </div>
        &nbsp;
        <div class="col-xs-6">
            <?php echo e($in->status); ?>

        </div>
        <br>
        <br>
    </div>
    <div class="row" style="" align="center">
        <br>
        <div class="col-xs-12" align="center">
            <a title="Withdraw" href="javascript:void(0)" class="btn btn-info" onclick="wd('pack', '<?php echo e($in->id); ?>', '<?php echo e($ern); ?>', '<?php echo e($withdrawable); ?>', '<?php echo e($Edays); ?>', '<?php echo e($ended); ?>')">
                <?php echo e($in->currency); ?> <?php echo e($ern); ?>

                <br>
                <?php echo e(__('Solicitar Retiro')); ?>

            </a>
            <a title="Inyectar" href="javascript:void(0)" class="btn btn-info" onclick="inyect('<?php echo e($in->package_id); ?>', '<?php echo e($in->id); ?>','<?php echo e($in->package); ?>', ' <?php echo e($in->capital); ?>', ' <?php echo e($in->curency); ?>', '<?php echo e($in->package_name); ?>',  '<?php echo e($in->period); ?>', '<?php echo e($in->daily_interest); ?>')">
            <br>
                <?php echo e(__('Inyectar')); ?>


            </a>
        </div>
        <!-- <?php echo e(__('Solicitar Retiro')); ?> -->
    </div>
</div>

<?php /**PATH C:\xampp\htdocs\invermix\invermix\resources\views/user/inc/mob_inv.blade.php ENDPATH**/ ?>