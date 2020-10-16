<?php echo $__env->make('user.inc.fetch', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<?php $__env->startSection('content'); ?>
    
            <?php ($breadcome = 'Perfect Money'); ?>           
            <div class="container mt-5">                   
                <div id="prnt"></div>
                <div class="row">
                    <div class="col-md-3">
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <div class="card-head-row" align="center">
                                    <div class="card-title" align="center">
                                        <img align="center" src="http://www.deminetsolution.biz/slides/slide4.jpg" class="img-responsive" style="height: 100px; width: 70%;">
                                    </div>
                                                                       
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">                                      
                                    <div class="col-md-12">
                                        <div class="panel-body">

                                            <form class="form-horizontal" action="<?php echo e(route('pm.post_amt')); ?>" method="POST" role="form">
                                                <?php echo csrf_field(); ?>
                                                <div class="form-group<?php echo e($errors->has('amount') ? ' has-error' : ''); ?>">
                                                    <label for="amount" class="col-md-4 control-label"><?php echo e(__('Enter Amount')); ?></label>
                                                    <div class="col-md-12">
                                                        <div class="input-group">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text"><b><?php echo e(__('USD')); ?></b></span>
                                                            </div>
                                                            
                                                            <input id="amount" type="number" class="form-control" name="amount" required autofocus>                    
                                                        </div>
                                                        <?php if(Session::has('err')): ?>
                                                            <span class="help-block text-danger">
                                                                <strong><?php echo e(Session::get('err')); ?></strong>
                                                            </span>
                                                        <?php endif; ?>
                                                    </div>

                                                </div>
                                                <div class="form-group">
                                                    <div class="col-md-12">
                                                        <button type="submit" class="btn btn-primary">
                                                            <?php echo e(__('Pay Now')); ?>

                                                        </button>
                                                    </div>
                                                </div>                                                

                                            </form>


                                        </div>
                                    </div>
                                   
                                </div>
                                
                                
                            </div>
                        </div>
                    </div>                        
                </div>
            </div>
       

<?php $__env->stopSection(); ?>
            
<?php echo $__env->make('layouts.atlantis.no_header_layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\dh\core\resources\views/user/pay_pm_amt.blade.php ENDPATH**/ ?>