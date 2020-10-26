<?php echo $__env->make('user.inc.fetch', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<?php $__env->startSection('content'); ?>
        <div class="main-panel">
            <div class="content">
                <?php ($breadcome = 'My Downlines'); ?>
                <?php echo $__env->make('user.atlantis.main_bar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                <div class="page-inner mt--5">
                    <?php echo $__env->make('user.atlantis.overview', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                    <div id="prnt"></div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <div class="card-head-row">
                                        <div class="card-title">                                            
                                            <?php echo e(__('Referral link:')); ?>

                                            <a href="/register/<?php echo e($user->username); ?>" class="text-danger" id="reflnk" >
                                                <small><?php echo e(env('APP_URL').__('/register/').$user->username); ?></small>
                                            </a>                                            
                                        </div>                                       
                                    </div>
                                </div>                                
                            </div>
                        </div>
                        
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <div class="card-title"><?php echo e(__('My Downlines')); ?></div>
                                </div>
                                <div class="card-body pb-0">
                                    <div class="table-responsive">                                        
                                        <table id="basic-datatables" class="display table table-striped table-hover" >
                                            <thead>
                                                <tr>
                                                    <!-- <th data-field="state" data-checkbox="true"></th> -->
                                                    <th><?php echo e(__('Name')); ?></th>
                                                    <th><?php echo e(__('Username')); ?></th>
                                                    <th><?php echo e(__('Investment')); ?></th>
                                                    <th><?php echo e(__('Date Registered')); ?></th>   
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                    $activities = App\User::where('referal', $user->username)->orderby('id', 'desc')->get();
                                                ?>
                                                <?php if(count($activities) > 0 ): ?>
                                                    <?php $__currentLoopData = $activities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $activity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <tr>                                                            
                                                            <td><?php echo e($activity->firstname); ?> <?php echo e($activity->lastname); ?></td>
                                                            <td><?php echo e($activity->username); ?></td>
                                                            <td>
                                                                <?php  
                                                                    $ref_inv = App\investment::where('user_id', $activity->id)->get();
                                                                    echo count($ref_inv);
                                                                ?>
                                                            </td>                                                            
                                                            <td><?php echo e($activity->created_at); ?></td>                     
                                                        </tr>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                <?php else: ?>
                                                    
                                                <?php endif; ?>
                                            </tbody>
                                        </table>
                                        <br><br>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <div class="card-title"> <?php echo e(__('Earning from downlines')); ?> </div>
                                </div>
                                <div class="card-body pb-0">
                                    <div class="table-responsive">                                        
                                        <table id="" class="table table-hover" >
                                            <thead>
                                                <tr>                                                    
                                                    <th><?php echo e(__('Date')); ?></th>
                                                    <th><?php echo e(__('Username')); ?></th>
                                                    <th><?php echo e(__('Name')); ?></th>
                                                    <th><?php echo e(__('Amount')); ?></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                    $activities = App\ref::where('username', $user->username)->orderby('id', 'desc')->get();
                                                ?>
                                                <?php if(count($activities) > 0 ): ?>
                                                    <?php $__currentLoopData = $activities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $activity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <?php
                                                            $usr = App\User::find($activity->user_id);
                                                        ?>
                                                        <tr>  
                                                            <td><?php echo e($activity->created_at); ?></td>
                                                            <td><?php echo e($usr->username); ?></td>
                                                            <td>
                                                                <?php echo e($usr->firstname .' '. $usr->lastname); ?>

                                                            </td>
                                                            <td><?php echo e($activity->currency); ?> <?php echo e($activity->amount); ?></td>
                                                        </tr>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                <?php else: ?>
                                                   
                                                <?php endif; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    
                </div>
            </div>

            <?php echo $__env->make('user.inc.confirm_inv', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<?php $__env->stopSection(); ?>
            
<?php echo $__env->make('layouts.atlantis.layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\invermix\invermix\resources\views/user/downlines.blade.php ENDPATH**/ ?>