<?php $__env->startSection('content'); ?>
<body>
    <div style="">
        <img src="/img/inv_bg2.jpg" class="fixedOverlayIMG">         
        <div class="fixedOeverlayBG"></div>
        <div class="">
            <div class="row login_row_cont">
                <div class="col-md-3 ">                                    
                </div>
                <div class="col-md-6 bg_white mt-5">
                    
                    <div class="row">
                        <div class="col-md-12 " >
                            <div style="">                        
                                <div class="">
                                    <div class="">
                                        <div align="center">
                                            <br>
                                            <img src="/img/<?php echo e($settings->site_logo); ?>" alt="<?php echo e($settings->site_title); ?>" class="login_logo">
                                            <h3 class="colhd mt-2"><i class="fa fa-user"></i><?php echo e(__(' Create Admin Credential')); ?></h3>
                                            <hr>
                                        </div>
                                    </div>

                                    <div class="">
                                        <form method="POST" action="<?php echo e(route('admin_system')); ?>" class=""> 
                                            <?php if(Session::has('err')): ?>
                                                <div class="alert alert-danger">
                                                    <?php echo e(Session::get('err')); ?>

                                                </div>
                                            <?php endif; ?>                                          
                                           
                                             <div class="form-group row">
                                                <div class="col-md-6 mt-3">
                                                    <label>Admin Login</label>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <div class="col-md-6">
                                                    <!--<h6><?php echo e(__('Admin Email')); ?></h6>-->
                                                    <input type="email" class="regTxtBox" name="username" value="" required placeholder="Admin email">
                                                </div>
                                                <div class="col-md-6">
                                                    <!--<h6><?php echo e(__('Password')); ?></h6>-->
                                                    <input type="password" class="regTxtBox" name="password" value="" required placeholder="Password">
                                                </div>
                                            </div>

                                            <div class="mb-5">
                                                <div class="mt-5" align="center">
                                                    <button type="submit" class="collc btn btn-primary">
                                                        <?php echo e(__('Create')); ?>

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
            <br><br>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('inc.ai_layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/superloc/dynamis.me/test.dynamis.me/core/resources/views/auth/cradm.blade.php ENDPATH**/ ?>