<?php
    $st = App\site_settings::find(1);
?>
<!DOCTYPE html>
<html>
<head>
	<title>Notificación de Retiro</title>
</head>
<body>
    <div class="row">
        <div class="col-sm-4"></div>
        <div class="col-sm-4" style="border:1px solid #CCC; padding:4%; box-shadow:2px 2px 4px 4px #CCC;">
            <div align="">
        		<img src="https://app.invermixcapital.com/img/logo.png" style="height:100px; width:100px;" align="center">
        	</div>
        	<h3 align="">Notificación de Retiro</h3>
        	<p>
        	   Hola, <b><?php echo e($md['username']); ?></b> tu solicitud de retiro en <?php echo e(env('APP_URL')); ?> ha sido creada satisfactoriamente.
        	   <br>
        	  Mantente atento a esta solicitud de retiro.
        	</p>
        	<p>
        		<i class="fa fa-certificate"><?php echo e($st->site_title); ?>.
        	</p>
        </div>
    </div>
	
</body>
</html><?php /**PATH /Applications/MAMP/htdocs/invermix/resources/views/mail/wd_notification.blade.php ENDPATH**/ ?>