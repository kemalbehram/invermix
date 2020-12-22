<?php ($user_data = user_details_data($id)); ?>
<?php ($user = $user_data['user']); ?>
<?php ($dt = $user_data['dt']); ?>


<div id="popInvest" class="container pop_invest_cont" >
  <div class="row wd_row_pad" >
    <div class="col-md-4">&emps;</div>
    <div class="col-md-4 card pop_invest_col" align="center">
      <div class="card-header" style="">
        <h3><b><?php echo e(__('')); ?></b></h3>
        
        <hr>
      </div>
      <div class="pop_msg_contnt">
        <p align="center" class="color_blue_b">
            <?php echo e(__('Estás a punto de invertir en este plan ')); ?> <b><span id="pack_inv"></span></b><?php echo e(__(', cual tiene un período de')); ?>  <b><span id="period"></span></b><?php echo e(__(' días laborables y tiene un interés total de ')); ?>  <b><span id="intr"></span></b>%.
        </p>
        <form id="userpackinv" action="<?php echo e(route('first_inv')); ?>" method="post">
            <div class="form-group" align="left">
              <div class="pop_form_min_max" align="center">
                <b><?php echo e(__('Inversión Min.:')); ?> <?php echo e('RD$'); ?> <span id="min"></span></b> |
                <b><?php echo e(__('Inversión Máx.:')); ?> <?php echo e('RD$'); ?> <span id="max"></span></b>
              </div>
              <br>
              <div class="pop_form_min_max" align="center">
                <b><?php echo e(__('Inversión Min.:')); ?> <?php echo e('US$'); ?> <span id="mindol"></span></b> |
                <b><?php echo e(__('Inversión Máx.:')); ?> <?php echo e('US$'); ?> <span id="maxdol"></span></b>
              </div>
              <br>
              <label><?php echo e(__('Colocar Monto a Invertir')); ?></label>
              <input type="hidden" class="form-control" name="_token" value="<?php echo e(csrf_token()); ?>">
              <input id="p_id" type="hidden" class="form-control" name="p_id" value="">
              <input type="hidden" name="uid" value="<?php echo e($user->id); ?>">
              <input type="hidden" name="username" value="<?php echo e($user->username); ?>">
              <input type="text" class="form-control" name="capital" placeholder="Digitar capital a invertir" required>
            </div>
            <div class="input-group">
                <div class="input-group-prepend " >
                  <span class="input-group-text "><i class=""></i>Moneda</span>
                </div>
                <select class="form-control" id="currency" name="currency">
                                        <option value="RD$" name="currency">RD$</option>
                                        <option value="US$" name="currency">US$</option>
                                        </select>
                <div class="input-group-append " >
                  <span class="input-group-text " ><i class=""></i></span>
                </div>
              </div>
            <div class="form-group">
                <button class="collb btn btn-info"><?php echo e(__('Proceder')); ?></button>
                <span style="">
                  <a id="popMsg_close_user" href="javascript:void(0)" class="btn btn-danger"><?php echo e(__('Cancelar')); ?></a>
                </span>
                <br><br>
            </div>
        </form>

      </div>
      <!-- close btn -->
      <script type="text/javascript">
        $('#popMsg_close_user').click( function(){
          $('#popInvest').hide();
        });
      </script>
      <!-- end close btn -->
    </div>
  </div>
</div>
<?php /**PATH /Applications/MAMP/htdocs/invermix/resources/views/admin/inc/first_inv.blade.php ENDPATH**/ ?>