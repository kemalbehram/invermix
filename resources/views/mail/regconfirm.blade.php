<?php
    $st = App\site_settings::find(1);
?>
<!DOCTYPE html>
<html>
<head>
	<title>Registration Confirmation</title>
</head>
<body>
    <div class="row">
        <div class="col-sm-4"></div>
        <div class="col-sm-4" style="border:1px solid #CCC; padding:5%; ">
            <div align="">
        		<img src="http://{{env('MAIL_SENDER')}}/img/{{$st->site_logo}}" style="height:100px; width:100px;" align="center">
        	</div>
        	<h3 align="">Hi {{$md['usr']}}, </h3>
        	<p>
        	    You've taken a bold step for registering with us at <a href="https://{{env('MAIL_SENDER')}}"><b>{{env('MAIL_SENDER')}}</b></a><br>
        		To complete your registration and begin investing with us, please click the link below to activate your account.<br>
        		<br>
        		<a class="btn btn-info" href="http://{{env('MAIL_SENDER')}}/registration/confirm/{{$md['usr']}}/{{$md['token']}}"><b>Confirm Registration</b></a>
        	</p>
        	<p>
        		<i class="fa fa-certificate"></i>Thanks for registering at {{env('MAIL_SENDER')}}.
        	</p>
        </div>
    </div>
	
</body>
</html>