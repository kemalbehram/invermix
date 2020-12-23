<?php
    $st = App\site_settings::find(1);
?>
<!DOCTYPE html>
<html>
<head>
	<title>Aprobación de retiro</title>
	<link rel="stylesheet" href="{{env('APP_URL')}}/css/bootstrap.min.css">
	<link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" >
</head>
<body>
    <div class="row">
        <div class="col-sm-4"></div>
        <div class="col-sm-4" style="border:1px solid #CCC; padding:4%; box-shadow:2px 2px 4px 4px #CCC;">
            <div align="">
        		<img src="https://invermixcapital.com/assets/images/logo-invermix.png" style="height:100px; width:100px;" align="center">
        	</div>
        	<h3 align="">Aprobación de retiro</h3>
        	<p>
			Hola, esto es para notificarle que su solicitud de retiro con ID: <b>{{$md['wd_id']}} en {{env('APP_URL')}} </b> ha sido aprobado. <br> 
			Espere pacientemente el depósito en su cuenta.<br>
        	   <b>Cuenta: {{$md['act']}}</b><br>
        	   <b>Monto: {{$md['currency']}}{{$md['amt']}}</b>
        	</p>
        	<p>
        		<i class="fa fa-certificate">{{$st->site_title}}.
        	</p>
        </div>
    </div>
	
</body>
</html>