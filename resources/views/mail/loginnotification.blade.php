<?php
    $st = App\site_settings::find(1);
?>
<!DOCTYPE html>
<html>
<head>
	<title>Login Notificación</title>
	<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	<link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
	<!-- <script type="text/javascript" src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script> -->
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</head>
<body>
	<div align="">
		<img src="https://invermixcapital.com/assets/images/logo-invermix.png" style="height:100px; width:100px;" align="center">
	</div>
	<h3 align=""> Hola, {{$md['username']}}</h3>
	<p>
		Esto es para notificarle que hubo un inicio de sesión en su cuenta hace un momento.<br>
		Si no fue usted el indicado, comuníquese con el soporte ahora para una acción rápida.<br>
		<br>
		Póngase en contacto con el soporte ahora: support{{'@'.env('MAIL_SENDER')}}
	</p>
	<p>
		<i class="fa fa-certificate"></i> Gracias por usar {{$st->site_title}}.
	</p>
</body>
</html>