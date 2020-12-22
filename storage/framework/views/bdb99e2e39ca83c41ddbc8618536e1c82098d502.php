<table class="display table table-stripped table-hover">
    <thead>
        <tr>
           <th> <?php echo e(__('Nombre')); ?> </th>
           <th> <?php echo e(__('RNC')); ?> </th>
           <th> <?php echo e(__('Correo')); ?> </th>
           <th> <?php echo e(__('Capital Apertura')); ?> </th>
           <th> <?php echo e(__('Capital Disponible')); ?> </th>
           <th> <?php echo e(__('Bonos Aperturado')); ?> </th>
           <th> <?php echo e(__('Bonos Vendido')); ?> </th>
           <th> <?php echo e(__('Bonos Disponible')); ?> </th>
           <th> <?php echo e(__('Costo  Bono')); ?> </th>
           <th> <?php echo e(__('Moneda')); ?> </th>
        </tr>
    </thead>
    <tbody>

        <?php if(count($comps) > 0 ): ?>
            <?php $__currentLoopData = $comps; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dep): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><?php echo e($dep->name_comp); ?></td>
                    <td><?php echo e($dep->rnc); ?></td>
                    <td><?php echo e($dep->email); ?></td>
                    <td><?php echo e(number_format($dep->o_capital), 2); ?></td>
                    <td><?php echo e(number_format($dep->a_capital), 2); ?></td>
                    <td><?php echo e(number_format($dep->bonus_open)); ?></td>
                    <td><?php echo e(number_format($dep->sold_bonus)); ?></td>
                    <td><?php echo e(number_format($dep->a_bonus)); ?></td>
                    <td><?php echo e(number_format($dep->bonus_cost)); ?></td>
                    <td><?php echo e($dep->currency); ?></td>
                    <td>
                      <label class="switch" >
                        <input type="checkbox" <?php if($dep->status == 1): ?><?php echo e('checked'); ?><?php endif; ?>>
                        <span id="switch_pack<?php echo e($dep->id); ?>" class="slider round" onclick="act_deact_comp('<?php echo e($dep->id); ?>')"></span>
                      </label>
                    </td>

                    <td>
                        <?php if($adm->role == 3 || $adm->role == 2): ?>
                            <a id="<?php echo e($dep->id); ?>" title="Editar Compañía" href="javascript:void(0)" onclick="edit_comp(this.id, '<?php echo e($dep->name_comp); ?>', '<?php echo e($dep->rnc); ?>', '<?php echo e($dep->email); ?>',  '<?php echo e($dep->o_capital); ?>', '<?php echo e($dep->a_capital); ?>', '<?php echo e($dep->bonus_open); ?>', '<?php echo e($dep->sold_bonus); ?>', '<?php echo e($dep->a_bonus); ?>', '<?php echo e($dep->bonus_cost); ?>', '<?php echo e($dep->currency); ?>', '<?php echo e(csrf_token()); ?>', '<?php echo e($dep->currency); ?>')">
                                <span><i class="fa fa-edit btn btn-warning"></i></span>
                            </a>
                            <a id="<?php echo e($dep->id); ?>" title="Borrar Compañía" href="javascript:void(0)" onclick="load_get_ajax('/admin/delete/company/<?php echo e($dep->id); ?>', this.id, 'admDeleteMsg') ">
                                <span><i class="fa fa-times btn btn-danger"></i></span>
                            </a>

                        <?php endif; ?>
                    </td>

                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php else: ?>

        <?php endif; ?>
    </tbody>
</table>
<?php /**PATH /Applications/MAMP/htdocs/invermix/resources/views/admin/temp/companie.blade.php ENDPATH**/ ?>