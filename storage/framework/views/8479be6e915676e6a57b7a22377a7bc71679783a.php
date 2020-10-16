

    <div class="panel-body">

        <form class="form-horizontal" method="POST" id="" role="form" action="<?php echo URL::route('addmoney.paypal'); ?>" >
            <?php echo e(csrf_field()); ?>


            <div class="form-group<?php echo e($errors->has('amount') ? ' has-error' : ''); ?>">
                <label for="amount" class="col-md-4 control-label"><?php echo e(__('Enter Amount')); ?></label>
                <div class="col-md-12">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><b><?php echo e($settings->currency); ?></b></span>
                        </div>
                        <input id="amount" type="number" class="form-control" name="amount" value="<?php echo e(old('amount')); ?>" required autofocus>
                    </div>
                    <?php if($errors->has('amount')): ?>
                        <span class="help-block">
                            <strong><?php echo e($errors->first('amount')); ?></strong>
                        </span>
                    <?php endif; ?>
                </div>

            </div>
            <div class="form-group">
                <div class="col-md-6 col-md-offset-4">
                    <button type="submit" class="btn btn-primary">
                        <?php echo e(__('Pay Now')); ?>

                    </button>
                </div>
            </div>

        </form>

    </div>
<?php /**PATH /home/superloc/is.me/maxprofiamis.me/core/resources/views/user/inc/paypal_form.blade.php ENDPATH**