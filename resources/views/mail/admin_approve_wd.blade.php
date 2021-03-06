<?php
    $st = App\site_settings::find(1);
?>
<!DOCTYPE html>
<html>
<head>
	<title>Aprobación de Retiro</title>
	<link rel="stylesheet" href="{{env('APP_URL')}}/css/bootstrap.min.css">
	<link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" >
</head>
<body>
    <div class="row">
        <div class="col-sm-4"></div>
        <div class="col-sm-4" style="border:1px solid #CCC; padding:4%; box-shadow:2px 2px 4px 4px #CCC;">
            <div align="">
        		<img src="https://app.invermixcapital.com/img/logo.png" style="height:100px; width:100px;" align="center">
        	</div>
        	<h3 align="">Aprobación de Retiro</h3>
        	<p>
        	   Hola, esto es para notificar que tu retiro con el ID: <b>{{$md['wd_id']}} en {{env('APP_URL')}} </b> ha sido aprobado. <br> 
        	   Espera paciente el depósito a tu cuenta.<br>

        	   <b>Monto: {{$md['currency']}}{{$md['amt']}}</b>
        	</p>
        	<p>
        		<i class="fa fa-certificate">{{$st->site_title}}.
        	</p>
        </div>
    </div>
	
</body>
</html>