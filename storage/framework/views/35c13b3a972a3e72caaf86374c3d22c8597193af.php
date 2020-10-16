<?php echo $__env->make('user.inc.fetch', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<?php $__env->startSection('content'); ?>
        <div class="main-panel">
            <div class="content">
                <?php ($breadcome = 'Perfect Money Payment'); ?>
                <?php echo $__env->make('user.atlantis.main_bar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                <div class="page-inner mt--5">                   
                    <div id="prnt"></div>
                    <div class="row">
                        <div class="col-md-8">
                            <div class="card">
                                <div class="card-header">
                                    <div class="card-head-row">
                                        <div class="card-title">
                                             <img align="center" src="http://www.deminetsolution.biz/slides/slide4.jpg" class="img-responsive" style="height: 70px"></div>
                                        <div class="card-tools">                                            
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row"> 
                                         
                                        <div class="col-md-7">
                                            <div class="panel-body">
                                            <form class="form-horizontal" action="https://perfectmoney.is/api/step1.asp" method="POST" role="form">
                                                <div class="form-group<?php echo e($errors->has('amount') ? ' has-error' : ''); ?>">
                                                    <div class="col-md-12" align="center">
                                                        <h3 align="center"><?php echo e(__('Confirm Amount')); ?></h3>
                                                        <p style="font-size: 30px;">
                                                           <strong><?php echo e(__('$').$pm_amount); ?></strong>
                                                        </p>                                                    
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <div class="col-md-12" align="center">
                                                        <button type="submit" class="btn btn-primary">
                                                            <?php echo e(__('Proceed')); ?>

                                                        </button>
                                                    </div>
                                                </div>
                                                <input type="hidden" name="PAYEE_ACCOUNT" value="<?php echo e(env('PM_ACCOUNT')); ?>">
                                                <input type="hidden" name="PAYEE_NAME" value="<?php echo e(env('PM_COMPANY')); ?>">
                                                <input type="hidden" name="PAYMENT_AMOUNT" value="<?php echo e($pm_amount); ?>">
                                                <input type="hidden" name="PAYMENT_UNITS" value="USD">
                                                <input type="hidden" name="STATUS_URL" value="<?php echo e(route('pm.pm_success')); ?>">
                                                <input type="hidden" name="PAYMENT_URL" value="<?php echo e(route('pm.pm_success')); ?>">
                                                <input type="hidden" name="NOPAYMENT_URL" value="<?php echo e(route('pm.pm_cancel')); ?>">
                                                <input type="hidden" name="BAGGAGE_FIELDS" value="<?php echo e($user->username); ?>">
                                                <input type="hidden" name="ORDER_NUM" value="<?php echo e($tnx_id); ?>">
                                                <input type="hidden" name="CUST_NUM" value="<?php echo e($user->id); ?>">
                                                <!-- <input type="submit" name="PAYMENT_METHOD" value="PerfectMoney account"> -->

                                            </form>


                                        </div>
                                        </div>
                                    </div>
                                    
                                    
                                </div>
                            </div>
                        </div>                        
                    </div>
                </div>
            </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.atlantis.layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/superloc/dynamis.me/maxprofit.dynamis.me/core/resources/views/user/pm_proceed.blade.php ENDPATH**/ ?>