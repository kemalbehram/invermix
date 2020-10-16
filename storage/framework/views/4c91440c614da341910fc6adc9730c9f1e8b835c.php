<?php echo $__env->make('user.inc.fetch', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<?php $__env->startSection('content'); ?>
        <div class="main-panel">
            <div class="content">
                <?php ($breadcome = 'Stripe Payment'); ?>
                <?php echo $__env->make('user.atlantis.main_bar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                <div class="page-inner mt--5">
                    <div id="prnt"></div>
                    <div class="row">
                        <div class="col-md-8">
                            <div class="card">
                                <div class="card-header">
                                    <div class="card-head-row">
                                        <div class="card-title"><?php echo e(__('Deposit Using Stripe')); ?></div>
                                        <div class="card-tools">
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-7">
                                            <?php if(Session::has('success')): ?>
                                                <div class="alert alert-success text-center">
                                                    <a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
                                                    <i class="fas fa-check-circle text-success" ></i>
                                                    <p><?php echo e(Session::get('success')); ?></p>
                                                </div>
                                            <?php else: ?>
                                            <form class="form-horizontal" method="POST" role="form" action="<?php echo e(route('stripe.submitAmount')); ?>" >
                                                <?php echo e(csrf_field()); ?>


                                                <div class="form-group <?php echo e($errors->has('amount') ? ' has-error' : ''); ?>">
                                                    <label for="amount" class="control-label"><?php echo e(__('Enter Amount')); ?></label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text"><b><?php echo e($settings->currency); ?></b></span>
                                                        </div>
                                                        <input id="amount" type="number" class="form-control" name="amt" value="" required autofocus>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <button type="submit" class="btn btn-primary"><?php echo e(__('Proceed to Payment')); ?></button>
                                                </div>
                                            </form>
                                            <?php endif; ?>
                                        </div>
                                        <div class="col-md-5" align="center">
                                            <br>
                                            <i class="fab fa-cc-stripe fa-4x text-info">
                                            </i>
                                        </div>
                                    </div>


                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

             <?php echo $__env->make('user.inc.confirm_inv', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.atlantis.layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/superloc/is.me/maxprofiamis.me/core/resources/views/user/stripe_payment.blade.php ENDPATH**