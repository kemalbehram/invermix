<?php
    $st = App\site_settings::find(1);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Ticket Mensajes</title>
</head>
<body>
    <div class="row">
        <div class="col-sm-4"></div>
        <div class="col-sm-4" style="border:1px solid #CCC; padding:4%; box-shadow:2px 2px 4px 4px #CCC;">
            <div align="">
                <img src="https://app.invermixcapital.com/img/logo.png" style="height:100px; width:100px;" align="center">
            </div>
            <h3 align="">Ticket Mensajes</h3>
            <p>
        	   Saludos, Admin, <b>{{$md['username']}}</b> ha dejado un comentario en soporte de {{env('APP_URL')}}.
        	   <br>
               Mantente atento a esta conversación.

        	</p>
            <p>
                <i class="fa fa-certificate">{{env('APP_NAME')}}.
            </p>
        </div>
    </div>

</body>
</html>
