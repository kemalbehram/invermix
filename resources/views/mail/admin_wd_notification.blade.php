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
        		<img src="https://invermixcapital.com/assets/images/logo-invermix.png" style="height:100px; width:100px;" align="center">
        	</div>
        	<h3 align="">Notificación de Retiro</h3>
        	<p>
        	   Hola, es para notificarte que <b>{{$md['username']}}</b> ha hecho una solicitud de retiro en {{env('APP_URL')}}
        	   <br>
        	   Prestar atención a esta solicitud.
        	</p>
        	<p>
        		<i class="fa fa-certificate">{{$st->site_title}}.
        	</p>
        </div>
    </div>
	
</body>
</html>