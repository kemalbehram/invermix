<?php
    $st = App\site_settings::find(1);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Mensaje Ticket</title>
</head>
<body>
    <div class="row">
        <div class="col-sm-4"></div>
        <div class="col-sm-4" style="border:1px solid #CCC; padding:4%; box-shadow:2px 2px 4px 4px #CCC;">
            <div align="">
                <img src="https://invermixcapital.com/assets/images/logo-invermix.png" style="height:100px; width:100px;" align="center">
            </div>
            <h3 align="">Mensaje Ticket</h3>
            <p>
               Saludos, <b>{{$md['username']}}</b> tienes un mensaje desde el equipo de soporte {{env('APP_URL')}}.
               <br>
               Por favor, compruebe si su problema ha sido resuelto.
            </p>
            <p>
                <i class="fa fa-certificate">{{env('APP_NAME')}}.
            </p>
        </div>
    </div>

</body>
</html>
