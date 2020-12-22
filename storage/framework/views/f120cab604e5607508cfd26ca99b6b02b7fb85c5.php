
         
    <table id="" class=" table table-stripped table-hover">
        <thead>
            <tr>                           
                <th> <?php echo e(__('Capital')); ?> </th>
                <th> <?php echo e(__('Monto pagable')); ?> </th>
                <th> <?php echo e(__('Fecha')); ?> </th>
                <th> <?php echo e(__('Status')); ?> </th>                                                   
            </tr>
        </thead>
        <tbody>
            <?php
                $activities =  App\investment::where('user_id', $user->id)->where('status', 'Solicitado')->Orwhere('status', 'Retirado')->Orwhere('status', 'Depositado')
                ->orderby('created_at', 'desc')->get();
            
            ?>
            <?php if(count($activities) > 0 ): ?>
                <?php $__currentLoopData = $activities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dep): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr> 
                                <td><?php echo e($dep->currency); ?> <?php echo e($dep->capital); ?></td>
                                <td><b><?php echo e($dep->currency); ?> <?php echo e($dep->i_return); ?></b></td>
                                <td><?php echo e(substr($dep->created_at, 0, 10)); ?></td>
                                <td><?php echo e($dep->wd_status); ?></td>                   
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php else: ?>
                
            <?php endif; ?>
        </tbody>
    </table>
<?php /**PATH /Applications/MAMP/htdocs/invermix/resources/views/admin/temp/user_wd_history.blade.php ENDPATH**/ ?>