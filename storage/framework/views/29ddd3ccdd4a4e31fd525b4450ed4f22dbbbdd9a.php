<?php echo $__env->make('user.inc.fetch', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<?php $__env->startSection('content'); ?>
        <div class="main-panel">
            <div class="content">
                <?php ($breadcome = 'Mis Inversiones'); ?>
                <?php echo $__env->make('user.atlantis.main_bar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                <div class="page-inner mt--5">
                    <?php echo $__env->make('user.atlantis.overview', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                    <div id="prnt"></div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <div class="card-head-row">
                                        <div class="card-title"><?php echo e(__('Mis Inversiones')); ?></div>
                                    </div>
                                </div>
                                <div class="card-body ">
                                    <div class="table-responsive web-table">
                                        <table id="basic-datatables" class="display table table-hover" >
                                            <thead>
                                                <tr>
                                                    <th><?php echo e(__('Plan')); ?></th>
                                                    <th><?php echo e(__('Capital')); ?></th>
                                                    <th><?php echo e(__('Fecha de inversión')); ?></th>
                                                    <th><?php echo e(__('Hasta')); ?></th>
                                                    <th><?php echo e(__('Días transcurridos')); ?></th>
                                                    <th><?php echo e(__('Status')); ?></th>
                                                    <th><?php echo e(__('Ganancias')); ?></th>
                                                    <th><?php echo e(__('Propósito')); ?></th>
                                                    <th><?php echo e(__('Retirar')); ?></th>
                                                    <th><?php echo e(__('Inyectar')); ?></th>
                                                </tr>
                                            </thead>
                                            <tbody class="web-table">
                                                <?php if(count($actInv) > 0 ): ?>
                                                    <?php $__currentLoopData = $actInv; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $in): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <?php

                                                            $totalElapse = getDays(date('Y-m-d'), $in->end_date);
                                                            if($totalElapse == 0)
                                                            {
                                                                $lastWD = $in->last_wd;
                                                                $enddate = ($in->end_date);
                                                                $Edays = getDays($lastWD, $enddate);
                                                                $ern  = $Edays*$in->interest*$in->capital;
                                                                $withdrawable = $ern;
                                                                $totalDays = getDays($in->date_invested, $in->end_date);
                                                                $ended = "yes";
                                                            }
                                                            else
                                                            {
                                                                $lastWD = $in->last_wd;
                                                                $enddate = (date('Y-m-d'));
                                                                $Edays = getDays($lastWD, $enddate);
                                                                $ern  = $Edays*$in->interest*$in->capital;
                                                                $withdrawable = 0;
                                                                if ($Edays >= $in->days_interval)
                                                                {
                                                                    $withdrawable = $in->days_interval*$in->interest*$in->capital;
                                                                }

                                                                $totalDays = getDays($in->date_invested, date('Y-m-d'));
                                                                $ended = "no";
                                                            }
                                                        ?>
                                                        <tr class="">
                                                            <td><?php echo e($in->package); ?></td>
                                                            <td><?php echo e(($in->currency)); ?> <?php echo e($in->capital); ?></td>
                                                            <td><?php echo e($in->date_invested); ?></td>
                                                            <td><?php echo e($in->end_date); ?></td>
                                                            <td><?php echo e($totalDays); ?></td>
                                                            <td><?php echo e($in->status); ?></td>
                                                            <td><?php echo e($in->currency); ?> <?php echo e($ern); ?> </td>
                                                            <td>

                                                      

                                                                <?php if($in->description == Null): ?>
                                                            <a href="javascript:void(0)" onclick="description('<?php echo e($in->id); ?>')">Agregar propósito</a>
                                                               <?php else: ?>
                                                            <a  class="des-after" href="javascript:void(0)" onclick="description('<?php echo e($in->id); ?>')"><?php echo e($in->description); ?></a>
                                                            <?php endif; ?>
                                                            </td>
                                                            <td>
                                                                <a title="Retirar" href="javascript:void(0)" class="btn btn-info" onclick="wd('pack', '<?php echo e($in->id); ?>', '<?php echo e($ern); ?>', '<?php echo e($withdrawable); ?>', '<?php echo e($Edays); ?>', '<?php echo e($ended); ?>')">
                                                                    <i class="fas fa-arrow-down"></i>
                                                                </a>
                                                            </td>

                                                            <td>
                                                                <a title="Inyectar"  href="javascript:void(0)" class="btn btn-info" onclick="inyect('<?php echo e($in->package_id); ?>', '<?php echo e($in->id); ?>','<?php echo e($in->package); ?>', ' <?php echo e($in->capital); ?>', ' <?php echo e($in->curency); ?>', '<?php echo e($in->package_name); ?>',  '<?php echo e($in->period); ?>', '<?php echo e($in->daily_interest); ?>')">
                                                                    <i class="fas fa-arrow-up"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                <?php else: ?>

                                                <?php endif; ?>
                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="mobile_table container messages-scrollbar" >

                                        <?php if(count($actInv) > 0 ): ?>
                                            <?php $__currentLoopData = $actInv; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $in): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <?php

                                                    $totalElapse = getDays(date('y-m-d'), $in->end_date);
                                                    if($totalElapse == 0)
                                                    {
                                                        $lastWD = $in->last_wd;
                                                        $enddate = ($in->end_date);
                                                        $Edays = getDays($lastWD, $enddate);
                                                        $ern  = $Edays*$in->interest*$in->capital;
                                                        $withdrawable = $ern;

                                                        $totalDays = getDays($in->date_invested, $in->end_date);
                                                        $ended = "yes";

                                                    }
                                                    else
                                                    {
                                                        $lastWD = $in->last_wd;
                                                        $enddate = (date('Y-m-d'));
                                                        $Edays = getDays($lastWD, $enddate);
                                                        $ern  = $Edays*$in->interest*$in->capital;
                                                        $withdrawable = 0;
                                                        if ($Edays >= $in->days_interval)
                                                        {
                                                            $withdrawable = $in->days_interval*$in->interest*$in->capital;
                                                        }

                                                        $totalDays = getDays($in->date_invested, date('Y-m-d'));
                                                        $ended = "no";
                                                    }

                                                ?>

                                                <?php echo $__env->make('user.inc.mob_inv', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <?php else: ?>

                                        <?php endif; ?>
                                    </div>

                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <div class="card-title" id="invertir"> Planes Disponibles</div>
                                </div>
                                <div class="card-body pb-0">
                                    <?php echo $__env->make('user.inc.packages', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        
            </div>

            <div id="popDescription" class="container pop_invest_cont" >
  <div class="row wd_row_pad" >
    <div class="col-md-4">&emps;</div>
    <div class="col-md-4 card pop_invest_col" align="center">
      <div class="card-header" style="">
   
      </div>
      <div class="pop_msg_contnt">
        <p align="center" class="color_blue_b">
            <?php echo e(__('Agrega un propósito para identificar tu inversión.')); ?> <b>
        </p>
        <form  action="/user/invest/description" method="post">
            <div class="form-group" align="left">
              <br>
              <input type="hidden" class="form-control" name="_token" value="<?php echo e(csrf_token()); ?>">
              <input id="inv_id" type="hidden" class="form-control" name="id" value="">
              <input type="text" class="form-control" name="description" placeholder="Agregar propósito, no más de 25 carácteres" required>
            </div>

            <div class="form-group">
                <button class="collb btn btn-info"><?php echo e(__('Agregar')); ?></button>
                <span style="">
                  <a id="popDescription_close" href="javascript:void(0)" class="btn btn-danger"><?php echo e(__('Cancelar')); ?></a>
                </span>
                <br><br>
            </div>
        </form>

      </div>
      <!-- close btn -->
      <script type="text/javascript">
        $('#popDescription_close').click( function(){
          $('#popDescription').hide();
        });
</script>
      <!-- end close btn -->
    </div>
  </div>

  <script type="text/javascript">
function description(id)
{
    // alert(id);
    $('#inv_id').val(id);
    $('#popDescription').show();
}
</script>

</div>

    <?php echo $__env->make('user.inc.confirm_inv', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php echo $__env->make('user.inc.withdrawal', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php echo $__env->make('user.inc.inyect', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.atlantis.layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Applications/MAMP/htdocs/invermix/resources/views/user/my_investment.blade.php ENDPATH**/ ?>