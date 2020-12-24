<?php
    $st = App\site_settings::find(1);
?>
<!DOCTYPE html>
<html>
<head>
	<title>Notificación de Inversión</title>
</head>
<body>
    <div class="row">
        <div class="col-sm-4"></div>
        <div class="col-sm-4" style="border:1px solid #CCC; padding:4%; box-shadow:2px 2px 4px 4px #CCC;">
            <div align="">
        		<img src="https://invermixcapital.com/assets/images/logo-invermix.png" style="height:100px; width:100px;" align="center">
        	</div>
        	<h3 align="">Notificación de Inversión</h3>
        	<p>
     		   Hola, <b> <?php echo e($md ['username']); ?> </b> su inversión en <?php echo e(env('APP_URL')); ?> fue exitosa.
       		   <br>
               Puede verificar el crecimiento de su inversión en Mis inversiones en su dashboard.
        
        		</p>
        	<p>
        		<i class="fa fa-certificate"><?php echo e(env('APP_NAME')); ?>.
        	</p>
        </div>
    </div>
	
</body>
</html><?php /**PATH /Applications/MAMP/htdocs/invermix/resources/views/mail/user_inv_notification.blade.php ENDPATH**/ ?>