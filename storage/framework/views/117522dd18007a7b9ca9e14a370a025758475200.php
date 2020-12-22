<div id="popInyect" class="container pop_invest_cont" >
  <div class="row wd_row_pad" >
    <div class="col-md-4">&emps;</div>
    <div class="col-md-4 card pop_invest_col" align="center">
      <div class="card-header" style="">
        <h3><b><?php echo e(__('Inyección a Capital')); ?></b></h3>
        <hr>
      </div>
      <div class="pop_msg_contnt">
        <p align="center" class="color_blue_b">
            <?php echo e(__('Estás a punto de solicitar inyectar a una inversión existente ')); ?> <b><span id="pack_inv"></span></b><?php echo e(__(', la cual tiene un período de duración e interés total igual a la inversión inicial.')); ?>

        </p>
        <form id="userpackinv" action="/user/inyect" method="post">
            <div class="form-group" align="left">
              <!-- <div class="pop_form_min_max" align="center">

              </div> -->
              <br>
              <label><?php echo e(__('Colocar Monto a Invertir')); ?></label>
              <input type="hidden" class="form-control" name="_token" value="<?php echo e(csrf_token()); ?>">
              <input  type="hidden" class="form-control" name="packa_id" id="packa_id" value="">
              <input type="text" class="form-control" name="capital" placeholder="Digitar capital a inyectar" required>
              <input type="hidden" id="invest_id" class="form-control"  name="invest_id" value="">
            </div>
            <div class="form-group">
                <button class="collb btn btn-info"><?php echo e(__('Proceder')); ?></button>
                <span style="">
                  <a id="popInyect_close" href="javascript:void(0)" class="btn btn-danger"><?php echo e(__('Cancelar')); ?></a>
                </span>
                <br><br>
            </div>
        </form>

      </div>
      <!-- close btn -->
      <script type="text/javascript">
        $('#popInyect_close').click( function(){
          $('#popInyect').hide();
        });
      </script>
      <!-- end close btn -->
    </div>
  </div>
</div>
<?php /**PATH /Applications/MAMP/htdocs/invermix/resources/views/user/inc/inyect.blade.php ENDPATH**/ ?>