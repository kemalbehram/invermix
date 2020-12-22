<div class="panel-header " style="background-color: <?php echo e($settings->header_color); ?>">
	<div class="page-inner py-5" style="background-color: <?php echo e($settings->header_color); ?>">
		<div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
			<div>
				<h2 class="text-white pb-2 fw-bold">
					<?php echo e($breadcome); ?>

				</h2>
			</div>
			<div class="ml-md-auto py-2 py-md-0">
				<a href="/<?php echo e($user->username); ?>/investments#invertir" class="btn btn-white btn-border btn-round mr-2">Invertir</a>
				<a href="/<?php echo e($user->username); ?>/investments" class="btn btn-secondary btn-round">Inyectar</a>
			</div>
		</div>
	</div>
</div>
<?php /**PATH /Applications/MAMP/htdocs/invermix/resources/views/user/atlantis/main_bar.blade.php ENDPATH**/ ?>