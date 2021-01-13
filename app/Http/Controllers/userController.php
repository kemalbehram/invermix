<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;

use Validator;
use App\states;
use App\country;
use Session;
use Hash;
use File;
use Auth;
use App\User;
use App\banks;
use App\activities;
use App\packages;
use App\investment;
use App\inyects;
use App\msg;
use App\withdrawal;
use App\deposits;
use App\ref;
use App\fund_transfer;
use App\xpack_inv;
use App\xpack_packages;
use App\site_settings;
use App\ticket;
use App\comments;
use App\currencies;
use App\admin;
use App\kyc;
use App\ref_set;
use GuzzleHttp\Client as GuzzleClient;
use DotenvEditor;
use App\Mail\ContactMail;
use CoinbaseCommerce\ApiClient;
use CoinbaseCommerce\Resources\Checkout;
use CoinbaseCommerce\Resources\Charge;

use Google2FA;


class userController extends Controller
{
  private $st;

  public function __construct()
  {
    $user = Auth::User();
    $this->st = site_settings::find(1);
  }
  public function index()
  {
      //return view('user.');
  }

  public function states($id)
  {
      $state = states::where('country_id', $id)->get();
      return json_encode($state);
  }
  public function countryCode($id)
  {
      $code = country::where('id', $id)->get();
      return $code[0]->phonecode;
  }

  public function upload_u2s(Request $req)
  {
    $val = Validator::make($req->all(), [
        'email' => 'required|email',
        'password' => 'required|string|'
    ]);

    if($val->fails())
    {
        return back()->with([
            'toast_msg' => $val->errors()->first(),
            'toast_type' => 'err'
        ]);
    }

    try
    {
      if(Auth::attempt(['email' => $req['email'], 'password' => $req['password']]))
      {
        $user = Auth::User();
        if($user->status == 0 || $user->status == 'New' || $user->status == 'pending')
        {
            Session::flush();
            Session::put('err_msg', 'Cuenta no activada');
            return redirect()->bacK(); //->withErrors(['msg', 'Account not activated']);
        }
        if($user->status == 2 || $user->status == 'Blocked')
        {
            Session::flush();
            Session::put('err_msg', '¡Cuenta bloqueada! Póngase en contacto con el servicio de asistencia.');
            return redirect()->bacK(); //->withErrors(['msg', 'Account not activated']);
        }

        if($user->sec_2fa_status == 1)
        {
          Session::put('temp_login_email', $req['email']);
          Session::put('temp_login_pwd', $req['password']);
          Session::put('temp_2fa_key', $user->sec_2fa);

          Auth::logout();
          return view('user.enter_otp');
        }
        else
        {
          $act = new activities;
          $act->action = "User logged in to account";
          $act->user_id = $user->id;
          $act->save();
          return redirect('/'.$user->username.'/dashboard');
        }
      }
      return redirect()->route('login')->with([
        'toast_msg' => '¡Las credenciales de usuario no son correctas!',
        'toast_type' => 'err'
      ]);
    }
    catch(\Exception $e)
    {
      return back()->with([
        'toast_msg' => $e->getMessage(),
        'toast_type' => 'err'
      ]);
    }
  }

  public function verify_u2s(Request $req)
  {
    $val = Validator::make($req->all(), [
        'otp' => 'required|numeric'
    ]);

    if($val->fails())
    {
        return back()->with([
            'toast_msg' => $val->errors()->first(),
            'toast_type' => 'err'
        ]);
    }

    try
    {
      if(Google2FA::verifyGoogle2FA(Session::get('temp_2fa_key'), $req['otp']))
      {
        if(Auth::attempt(['email' => Session::get('temp_login_email'), 'password' => Session::get('temp_login_pwd')]))
        {
          $user = Auth::User();

          Session::forget('temp_login_email');
          Session::forget('temp_login_pwd');
          Session::forget('temp_2fa_key');

          $act = new activities;
          $act->action = "User logged in to account";
          $act->user_id = $user->id;
          $act->save();
          return redirect('/'.$user->username.'/dashboard');
        }
        return redirect()->route('login')->with([
          'toast_msg' => '¡Las credenciales de usuario no son correctas!',
          'toast_type' => 'err'
        ]);
      }
      else
      {
        return redirect()->back()->with([
          'toast_msg' => 'OTP not correct!',
          'toast_type' => 'err'
        ]);
      }
    }
    catch(\Exception $e)
    {
      return back()->with([
        'toast_msg' => $e->getMessage(),
        'toast_type' => 'err'
      ]);
    }
  }

  public function uploadProfilePic(Request $req)
  {
     	$user = Auth::User();
     	if(!empty($user))
     	{
      	try
      	{
      		$validate = $req->validate([
           'prPic' => 'required|image|mimes:jpeg,png,jpg|max:500',
          ]);

  		    $file = $req->file('prPic');
          $path = $user->username.".jpg"; //$req->file('u_file')->store('public/post_img');
          $file->move(base_path().'/public/img/profile/', $path);


          $usr = User::find($user->id);
          $usr->img = $path;
          $usr->save();

          $act = new activities;
          $act->action = "User updated profile picture";
          $act->user_id = $user->id;
          $act->save();

          Session::put('status', "Exitoso");
          Session::put('msgType', "suc");
          return back();
      	}
      	catch(\Exception $e)
      	{
    		  Session::put('status', "Error al cargar la imagen o el archivo de imagen no es válido");
          Session::put('msgType', "err");
        	return back();;
      	}

     	}
     	else
     	{
     		return redirect('/');
     	}
  }


  public function verify_reg($usn, $code)
  {

      	try
      	{

      	    $usr = User::where('username', $usn)->get();

      	    if(count($usr) > 0)
      	    {
      	        if($usr[0]->act_code == 0000000000)
      	        {
      	            Session::put('status', "Cuenta ya activada");
                      Session::put('msgType', "err");
      	        }
      	        elseif($usr[0]->act_code == $code)
      	        {
      	            $usr[0]->status = 1;
          	        $usr[0]->act_code = 0000000000;
                      $usr[0]->save();

                      Session::put('status', "Activación de cuenta exitosa");
                      Session::put('msgType', "suc");
      	        }
      	        else
      	        {
      	            Session::put('status', "¡Se pasó un código de activación no válido!");
                      Session::put('msgType', "err");
      	        }
      	    }
      	    else
      	    {

                  Session::put('status', "Error de activación de cuenta");
                  Session::put('msgType', "err");

      	    }

              return view('auth.act_verify');
      	}
      	catch(\Exception $e)
      	{
      		Session::put('status', $e->getMessage()."Error al actualizar su cuenta. Póngase en contacto con soporte");
              Session::put('msgType', "err");
          	return view('auth.act_verify');
      	}


  }

  public function changePwd(Request $req)
  {
     	$user = Auth::User();
     	if(!empty($user))
     	{
          if($req->input('newpwd') == $req->input('cpwd'))
          {
          	if($req->input('newpwd') == $req->input('oldpwd'))
          	{
                Session::put('status', "¡No puede usar la misma contraseña anterior! Utilice una contraseña diferente.");
                Session::put('msgType', "err");
                return back();
          	}
          	try
        	{
                $usr = User::find($user->id);
                if(Hash::check($req->input('oldpwd'), $user->pwd))
                {
                	$usr->pwd = Hash::make($req->input('newpwd'));
	                $usr->save();

                      $act = new activities;
                      $act->action = "Cliente cambió contraseña";
                      $act->user_id = $user->id;
                      $act->save();

                      $maildata = ['email' => $user->email, 'username' => $user->name];
                      Mail::send('mail.change_psw', ['md' => $maildata], function($msg) use ($maildata){
                      $msg->from(env('MAIL_USERNAME'), env('APP_NAME'));
                      $msg->to($maildata['email']);
                      $msg->subject('Cambio de Contraseña');
                  });

  		            Session::put('status', "Contraseña cambiada correctamente");
                      Session::put('msgType', "suc");
  		            return back();
                }
                else
                {
                	Session::put('status', "¡Contraseña anterior no válida! Inténtalo de nuevo");
                      Session::put('msgType', "err");
                      return back();
            		// return back();
                }
        	}
        	catch(\Exception $e)
        	{
        		Session::put('status', "¡Error al guardar la contraseña! Inténtalo de nuevo");
                  Session::put('msgType', "err");
  	            return back();
        	}
          }
          else
          {
          	Session::put('status', "La contraseña no coinciden.");
            Session::put('msgType', "err");
            return back();;
          }

     	}
     	else
     	{
     		return redirect('/');
     	}

  }

  public function updateProfile(Request $req)
  {
     	$user = Auth::User();
     	if(!empty($user))
     	{
      	try
      	{
      		$validate = $req->validate([
               'phone' => 'required|digits_between:8,15',
            ]);

      		  //$country = country::find($req->input('country'))
            $usr = User::find($user->id);
          	$usr->country = $req->input('country');
          	$usr->state = $req->input('state');
          	$usr->address = $req->input('adr');
          	$usr->phone = $req->input('cCode').$req->input('phone');

            $usr->save();

            $act = new activities;
            $act->action = "User Updated profile";
            $act->user_id = $user->id;
            $act->save();


            Session::put('status', "Actualización de perfil con éxito.");
            Session::put('msgType', "suc");
            return back();

      	}
      	catch(\Exception $e)
      	{
      		Session::put('status', "¡Error al guardar sus datos! Asegúrese de que su número sea válido.");
          Session::put('msgType', "err");
          return back();
      	}

     	}
     	else
     	{
     		return redirect('/');
     	}

  }

  public function addbank(Request $req)
  {

     	$user = Auth::User();
     	if(!empty($user))
     	{
      	try
      	{
          $bank = new banks;
        	$bank->user_id = $user->id;
        	$bank->Account_name = $req->input('act_name');
        	$bank->Account_number = $req->input('actNo');
        	$bank->Bank_Name = $req->input('bname');

          $bank->save();


            $act = new activities;
            $act->action = "User added bank details";
            $act->user_id = $user->id;
            $act->save();



          Session::put('status', "Su banco se ha agregado correctamente");
          Session::put('msgType', "suc");

          return back();

      	}
      	catch(\Exception $e)
      	{
      		  Session::put('status', "¡Error al guardar los detalles! La cuenta puede existir. Inténtalo de nuevo");
            Session::put('msgType', "err");
          	return back();
      	}

     	}
     	else
     	{
     		return redirect('/');
     	}

  }

  public function deleteBankAccount($id)
  {
     	$user = Auth::User();
     	if(!empty($user))
     	{

      	try
      	{
              $bank = banks::where('id', $id)->delete();

              $act = new activities;
              $act->action = "User deleted bank details";
              $act->user_id = $user->id;
              $act->save();

              Session::put('status', "Cuenta de banco eliminado correctamente");
              Session::put('msgType', "suc");
              return back();

      	}
      	catch(\Exception $e)
      	{
          Session::put('status', '¡Error al guardar los detalles! La cuenta puede existir. Inténtalo de nuevo');
          Session::put('msgType', "err");
          return back();
      	}

     	}
     	else
     	{
     		return redirect('/');
     	}

  }




  public function invest(Request $req)
  {

      $user = Auth::User();

      if($this->st->investment != 1 )
      {
        Session::put('msgType', "err");
        Session::put('status', 'Inversión no disponible! Serás notificado cuando esté disponible.');
        return back();
      }

      if($user->status == 'Blocked' || $user->status == 2 )
      {
        Session::put('msgType', "err");
        Session::put('status', '¡Cuenta bloqueada! Póngase en contacto con soporte.');
        return redirect('/login');
      }

      if($user->status == 'pending' || $user->status == 0 )
      {
        Session::put('msgType', "err");
        Session::put('status', '¡Cuenta no activada! Póngase en contacto con soporte.');
        return redirect('/login');
      }



      if(!empty($user))
      {

        try
        {
            if($req->currency == 'RD$'){

          $capital = $req->input('capital');
          $pack = packages::find($req->input('p_id'));


          if($capital > $pack->max)
          {
            Session::put('status', 'El capital de entrada es mayor que la inversión máxima.');
            Session::put('msgType', "err");
            return back();
          }

          if($capital < $pack->min)
          {
            Session::put('status', 'El capital de entrada es menos que la inversión mínima.');
            Session::put('msgType', "err");
            return back();
          }

          if($capital >= $pack->min && $capital <= $pack->max)
          {
            $inv = new investment;
            $inv->capital = $capital;
            $inv->user_id = $user->id;
            $inv->usn = $user->username;
            $inv->package = $pack->package_name;
            $inv->date_invested = date("d-m-Y");
            $inv->period = $pack->period;
            $inv->days_interval = $pack->days_interval;
            $inv->i_return = (round($capital*$pack->daily_interest*$pack->period,2));
            $inv->interest = $pack->daily_interest;

            $dt = strtotime(date('Y-m-d'));
            $days = $pack->period;

            while ($days > 0)
            {
                $dt    +=   86400   ;
                $actualDate = date('Y-m-d', $dt);
                $days--;
            }

            $inv->package_id = $pack->id;
            $inv->currency = $req->currency;
            $inv->end_date = $actualDate;
            $inv->last_wd = date("Y-m-d");
            $inv->status = 'Pendiente';

            $user->wallet -= $capital;
            $user->save();

            $inv->save();

            $act = new activities;
            $act->action = "User Invested ".$capital." in ".$pack->package_name." package";
            $act->user_id = $user->id;
            $act->save();


            $maildata = ['email' => $user->email, 'username' => $user->username];
            Mail::send('mail.user_inv_notification', ['md' => $maildata], function($msg) use ($maildata){
                $msg->from(env('MAIL_USERNAME'), env('APP_NAME'));
                $msg->to($maildata['email']);
                $msg->subject('Inversión InverBanking');
            });

            $maildata = ['email' => $user->email, 'username' => $user->username];
            Mail::send('mail.admin_inv_notification', ['md' => $maildata], function($msg) use ($maildata){
                $msg->from(env('MAIL_USERNAME'), env('APP_NAME'));
                $msg->to('gerencia@invermixcapital.com');
                $msg->to('servicios@invermixcapital.com');
                $msg->subject('Inversión de Cliente');
            });


            Session::put('status', "Inversión enviada para su aprobación.");
            Session::put('msgType', "suc");
            return back() ;
        }



          }elseif($req->currency == 'US$'){

            $capital = $req->input('capital');
            $pack = packages::find($req->input('p_id'));


            if($capital > $pack->maxdol)
            {
              Session::put('status', 'El capital de entrada es mayor que la inversión máxima.');
              Session::put('msgType', "err");
              return back();
            }

            if($capital < $pack->mindol)
            {
              Session::put('status', 'El capital de entrada es menos que la inversión mínima.');
              Session::put('msgType', "err");
              return back();
            }

            if($capital >= $pack->mindol && $capital <= $pack->max)
            {
              $inv = new investment;
              $inv->capital = $capital;
              $inv->user_id = $user->id;
              $inv->usn = $user->username;
              $inv->package = $pack->package_name;
              $inv->date_invested = date("d-m-Y");
              $inv->period = $pack->period;
              $inv->days_interval = $pack->days_interval;
              $inv->i_return = (round($capital*$pack->daily_interest*$pack->period,2));
              $inv->interest = $pack->daily_interest;

              $dt = strtotime(date('Y-m-d'));
              $days = $pack->period;

              while ($days > 0)
              {
                  $dt    +=   86400   ;
                  $actualDate = date('Y-m-d', $dt);
                  $days--;
              }

              $inv->package_id = $pack->id;
              $inv->currency = $req->currency;
              $inv->end_date = $actualDate;
              $inv->last_wd = date("Y-m-d");
              $inv->status = 'Pendiente';

              $user->wallet -= $capital;
              $user->save();

              $inv->save();

              $act = new activities;
              $act->action = "User Invested ".$capital." in ".$pack->package_name." package";
              $act->user_id = $user->id;
              $act->save();


          $maildata = ['email' => $user->email, 'username' => $user->username];
          Mail::send('mail.user_inv_notification', ['md' => $maildata], function($msg) use ($maildata){
              $msg->from(env('MAIL_USERNAME'), env('APP_NAME'));
              $msg->to($maildata['email']);
              $msg->subject('Inversión InverBanking');
          });

          $maildata = ['email' => $user->email, 'username' => $user->username];
          Mail::send('mail.admin_inv_notification', ['md' => $maildata], function($msg) use ($maildata){
              $msg->from(env('MAIL_USERNAME'), env('APP_NAME'));
              $msg->to('gerencia@invermixcapital.com');
              $msg->to('servicios@invermixcapital.com');
              $msg->subject('Inversión de Cliente');
          });

              Session::put('status', "Inversión enviada para su aprobación.");
              Session::put('msgType', "suc");
              return back() ;
          }


            }
          else
          {
            Session::put('status', "¡Monto inválido! Intenta nuevamente.");
            Session::put('msgType', "err");
            return back();
          }

        }
        catch(\Exception $e)
        {
            Session::put('status', "¡Error creando inversión! Por favor intentar nuevamente.".$e->getMessage());
            Session::put('msgType', "err");
            return back();
        }

      }
      else
      {
        return redirect('/');
      }

  }



  public function description(Request $req){

    $user = Auth::User();

    if(!empty($user))
    {
      try
      {
        $validator = Validator::make($req->all(), [
          'description' => 'required|string|max:25',

        ]);

        if($validator->fails()){
          Session::put('msgType', "err");
          Session::put('status', $validator->errors()->first());
          return back();

        }else{
          $descript = investment::where('id', $req->id)->update(['description' => $req->description]);
          Session::put('status', "Propósito agregado.");
          Session::put('msgType', "suc");
          return back() ;
        }

  }

  catch(\Exception $e)
  {
    Session::put('status', 'Error al agregar inversión');
    Session::put('msgType', "err");
    return back();
  }
    }

}

  public function wd_invest(Request $req)
  {



      $user = Auth::User();

      if($user->status == 'pending' || $user->status == 0 )
      {
        Session::put('msgType', "err");
        Session::put('status', '¡Cuenta no activada! Póngase en contacto con soporte.');
        return redirect('/login');
      }

      if($user->status == 'Blocked' || $user->status == 2 )
      {
        Session::put('msgType', "err");
        Session::put('status', '¡Cuenta bloqueada! Póngase en contacto con el servicio de asistencia.');
        return redirect('/login');
      }


      if(!empty($user))
      {

        try
        {

          $amt = $req->input('amt');

          if($req->input('pack_type') == 'xpack')
          {
              $pack = xpack_inv::find($req->input('p_id'));
          }
          else
          {
              $pack = investment::find($req->input('p_id'));

          }


          if($amt <= 0)
          {
            Session::put('msgType', "err");
            Session::put('status', 'Fecha de retiro no cumplida/ Monto inválido/ Inversión expirada');
            return back();
          }

          if($pack->status == 'Pendiente')
          {
            Session::put('msgType', "err");
            Session::put('status', 'Status de la inversión está pendiente, debe esperar ser aprobada para solictar el retiro de sus ganancias.');
            return back();
          }

          if($req->input('ended') == 'yes')
          {

            if($pack->wd_status != 'Solicitado')
            {
                $user->wallet += $pack->capital;
                $user->save();
            }

            $pack->last_wd = $pack->end_date;
            $pack->wd_status = 'Solicitado';
            $pack->status = 'Retiro Solicitado';

          }

          else
          {
            $dt = strtotime($pack->last_wd);
            $days = $pack->days_interval;

            while ($days > 0)
            {
              $dt    +=   86400   ;
              $actualDate = date('Y-m-d', $dt);
              // if (date('N', $dt) < 6)
              // {
                  $days--;
              //}
            }
            $pack->last_wd = $actualDate;
          }


          $pack->w_amt += $amt;
          $pack->save();



          $usr = User::find($user->id);
          $usr->wallet += $amt;
          $usr->save();

          $act = new activities;
          $act->action = "User withdrawn to wallet from ".$pack->package.'package. package id: '.$pack->id;
          $act->user_id = $user->id;
          $act->save();


          $maildata = ['email' => $user->email, 'username' => $user->username];
          Mail::send('mail.wd_notification', ['md' => $maildata], function($msg) use ($maildata){
              $msg->from(env('MAIL_USERNAME'), env('APP_NAME'));
              $msg->to($maildata['email']);
              $msg->subject('Notificación de Retiro');
          });

          $maildata = ['email' => $user->email, 'username' => $user->username];
          Mail::send('mail.admin_wd_notification', ['md' => $maildata], function($msg) use ($maildata){
              $msg->from(env('MAIL_USERNAME'), env('APP_NAME'));
              $msg->to('gerencia@invermixcapital.com');
              $msg->to('servicios@invermixcapital.com');
              $msg->subject('Notificación de Retiro');
          });


          Session::put('status', 'Retiro de inversión solicitada, la cantidad solicitada se depositará en su cuenta.');
          Session::put('msgType', "suc");
          return back();

        }
        catch(\Exception $e)
        {
          Session::put('status', 'Error enviando retiro.');
          Session::put('msgType', "err");
          return back();
        }

      }
      else
      {
        return redirect('/');
      }
  }



  public function user_wallet_wd(Request $req)
  {
      $user = Auth::User();

      if($this->st->withdrawal != 1 )
      {
        Session::put('msgType', "err");
        Session::put('status', '¡Retiro desactivado! Póngase en contacto con el servicio de asistencia.');
        return back();
      }

      if($user->status == 'Blocked' || $user->status == 2 )
      {
        Session::put('msgType', "err");
        Session::put('status', '¡Cuenta bloqueada! Póngase en contacto con el servicio de asistencia.');
        return back();
      }

      if($user->status == 'pending' || $user->status == 0 )
      {
        Session::put('msgType', "err");
        Session::put('status', '¡Cuenta no activada! Póngase en contacto con el servicio de asistencia.');
        return back();
      }

      if(intval($req->input('amt')) > intval($user->wallet) || intval($req->input('amt')) == 0 )
      {
        Session::put('msgType', "err");
        Session::put('status', 'Monto de retiro no válido. La cantidad debe ser mayor que cero (0) y no mayor que el saldo de la billetera');
        return back();
      }

      if(intval($req->input('amt')) > env('WD_LIMIT'))
      {
        Session::put('msgType', "err");
        Session::put('status', env('WD_LIMIT').' ¡Se superó el límite de retiro!');
        return back();
      }


      if(!empty($user))
      {
        try
        {

          $usr = User::find($user->id);
          $usr->wallet -= intval($req->input('amt'));
          $usr->save();

          $wd = new withdrawal;
          $wd->user_id = $user->id;
          $wd->usn = $user->username;
          $wd->package = 'wallet';
          $wd->invest_id = $user->id;
          $wd->amount = intval($req->input('amt'));
          $wd->account = $req->input('bank');
          $wd->w_date = date('Y-m-d');
          $wd->currency = $user->currency;
          $wd->charges = $charge = intval($req->input('amt'))*env('WD_FEE');
          $wd->recieving = intval($req->input('amt'))-$charge;
          $wd->save();

          $act = new activities;
          $act->action = "User requested withdrawal from wallet to bank ";
          $act->user_id = $user->id;
          $act->save();

          $maildata = ['email' => $user->email, 'username' => $user->username];
          Mail::send('mail.wd_notification', ['md' => $maildata], function($msg) use ($maildata){
              $msg->from(env('MAIL_USERNAME'), env('APP_NAME'));
              $msg->to($maildata['email']);
              $msg->subject('Withdrawal Notification');
          });

          $maildata = ['email' => $user->email, 'username' => $user->username];
          Mail::send('mail.admin_wd_notification', ['md' => $maildata], function($msg) use ($maildata){
              $msg->from(env('MAIL_USERNAME'), env('APP_NAME'));
              $msg->to(env('SUPPORT_EMAIL'));
              $msg->subject('Withdrawal Notification');
          });

          $wd_fee = env("WD_FEE")*100;
          Session::put('status', '¡Retiro de billetera exitoso!  '.$wd_fee.'% processing fee');
          Session::put('msgType', "suc");
          return back();
        }
        catch(\Exception $e)
        {
          Session::put('status', $e->getMessage());
          Session::put('msgType', "err");
          return back();
        }

      }
      else
      {
        return redirect('/');
      }
  }


  public function user_ref_wd(Request $req)
  {
      $user = Auth::User();

      if(env('WITHDRAWAL') != 1  )
      {
        Session::put('msgType', "err");
        Session::put('status', 'Withdrawal disabled! Please contact support.');
        return back();
      }

      if($user->status == 'Blocked' || $user->status == 2 )
      {
        Session::put('msgType', "err");
        Session::put('status', 'Account Blocked! Please contact support.');
        return back();
      }

      if($user->status == 'pending' || $user->status == 0 )
      {
        Session::put('msgType', "err");
        Session::put('status', 'Account not activated! Please contact support.');
        return back();
      }

      if(intval($req->input('amt')) < env('MIN_WD'))
      {
        Session::put('msgType', "err");
        Session::put('status', 'Amount is lower than minimum withdrawal of '.env('MIN_WD'));
        return back();
      }

      if(intval($req->input('amt')) > env('WD_LIMIT'))
      {
        Session::put('msgType', "err");
        Session::put('status', env('WD_LIMIT').' Withdrawal limit exceeded!');
        return back();
      }

      if(intval($req->input('amt')) > intval($user->ref_bal) || intval($req->input('amt')) == 0)
      {
        Session::put('msgType', "err");
        Session::put('status', 'Invalid withdrawal amount. Amount must be greater than zero(0) and not more than referral balance');
        return back();
      }


      if(!empty($user))
      {
        $iv = investment::where('user_id', $user->id)->get();
        if(count($iv) < 1)
        {
          Session::put('msgType', "err");
          Session::put('status', 'Sorry you have to invest at least once before you can withdraw your referral bonus.');
          return back();
        }

        try
        {

          $usr = User::find($user->id);
          $usr->ref_bal -= intval($req->input('amt'));
          $usr->save();

          $wd = new withdrawal;
          $wd->user_id = $user->id;
          $wd->usn = $user->username;
          $wd->package = 'ref_bonus';
          $wd->invest_id = $user->id;
          $wd->amount = intval($req->input('amt'));
          $wd->account = $req->input('bank');
          $wd->w_date = date('Y-m-d');
          $wd->currency = $user->currency;
          $wd->charges = $charge = intval($req->input('amt'))*env('WD_FEE');
          $wd->recieving = intval($req->input('amt'))-$charge;
          $wd->save();

          $act = new activities;
          $act->action = "User requested withdrawal from referral bonus to bank";
          $act->user_id = $user->id;
          $act->save();

          $maildata = ['email' => $user->email, 'username' => $user->username];
          Mail::send('mail.wd_notification', ['md' => $maildata], function($msg) use ($maildata){
              $msg->from(env('MAIL_USERNAME'), env('APP_NAME'));
              $msg->to($maildata['email']);
              $msg->subject('User Withdrawal Notification');
          });

          $maildata = ['email' => $user->email, 'username' => $user->username];
          Mail::send('mail.wd_notification', ['md' => $maildata], function($msg) use ($maildata){
              $msg->from(env('MAIL_USERNAME'), env('APP_NAME'));
              $msg->to(env('SUPPORT_EMAIL'));
              $msg->subject('User Withdrawal Notification');
          });

          Session::put('status', 'Referral Withdrawal Successful, Please Allow up to 10 Business Days for Payment Processing');
          Session::put('msgType', "suc");
          return back();

        }
        catch(\Exception $e)
        {
          Session::put('status', $e->getMessage().' Error al enviar su retiro');
          Session::put('msgType', "err");
          return back();
        }

      }
      else
      {
        return redirect('/');
      }
  }


  public function readmsg_up($id)
  {
      $user = Auth::User();
      if(!empty($user))
      {

        try
        {
          $msg = msg::find($id);
          $str = explode(';', $msg->readers);

          if(!in_array($user->id, $str))
          {
            if($msg->readers == "")
            {
              $msg->readers = $user->id.';';
            }
            else
            {
              $msg->readers = $msg->readers.$user->id.';';
            }
            $msg->save();
          }
          return "s";
        }
        catch(\Exception $e)
        {
          return 'err';
        }

      }
      else
      {
        return redirect('/');
      }

  }


  public function user_deposit_trans(Request $req)
  {
      $user = Auth::User();

      if($user->status == 'Blocked' || $user->status == 2 )
      {
        Session::put('msgType', "err");
        Session::put('status', '¡Cuenta bloqueada! Póngase en contacto con el servicio de asistencia.');
        return back();
      }

      if($user->status == 'pending' || $user->status == 0 )
      {
        Session::put('msgType', "err");
        Session::put('status', '¡Cuenta no activada! Póngase en contacto con el servicio de asistencia.');
        return back();
      }


      if(!empty($user))
      {

        try
        {

          $validator = Validator::make($req->all(), [
            'pop' => 'required|image|mimes:jpeg,png,jpg|max:500',
            'act_no' => 'required|numeric' ,
            'p_amt' => 'required|numeric' ,
          ]);

          if($validator->fails()){
            Session::put('msgType', "err");
            Session::put('status', $validator->errors()->first());
            return back();
          }

          $wd = new deposits;
          $wd->user_id = $user->id;
          $wd->usn = $user->username;
          // $wd->package = 'ref_bonus';
          // $wd->invest_id = $user->id;
          $wd->amount = intval($req->input('p_amt'));
          $wd->account_name = $req->input('act_name');
          $wd->account_no = $req->input('act_no');
          $wd->currency = $user->currency;
          $wd->bank = $req->input('y_bank');

          $file = $req->file('pop');
          $path =  $user->username.time().'.jpg';
          $file->move(public_path().'/pop/', $path);

          $wd->pop = $path;
          $wd->save();

          $act = new activities;
          $act->action = "User deposited ".$user->currency.intval($req->input('p_amt'))." through bank transfer.";
          $act->user_id = $user->id;
          $act->save();

          Session::put('status', 'Se han recibido los detalles de su depósito, el administrador confirmará y aprobará el pago');
          Session::put('msgType', "suc");
          return back();

        }
        catch(\Exception $e)
        {
          Session::put('status', $e->getMessage().' Error al enviar su retiro');
          Session::put('msgType', "err");
          return back();
        }

      }
      else
      {
        return redirect('/');
      }
  }


  public function payment_suc($amt, Request $req)
  {
      $user = Auth::User();
      if($req->input('event') == 'successful' && $req->input('txref') == Session::get('pay_ref'))
      {
          try
          {
            $dep = new deposits;
            $dep->user_id = $user->id;
            $dep->usn = $user->username;
            $dep->amount = $amt; //Session::get('payAmt');
            $dep->currency = $user->currency;
            $dep->account_name =  $req->input('flwref');
            // $dep->account_no = $_GET['flwref'];
            $dep->bank       = 'Ref:'.$req->input('txref');
            $dep->status = 1;
            $dep->on_apr = 1;
            $user->wallet += intval($amt);
            $user->save();

            $dep->save();

            Session::forget('pay_ref');

            Session::put('status', 'Pago exitoso');
            Session::put('msgType', "suc");
            Session::put('payment_complete', "yes");
            return redirect('/'.$user->username.'/dashboard');

              $act = new activities;
              $act->action = "User deposited ".$user->currency.intval($req->input('p_amt'))." through flutterwave.";
              $act->user_id = $user->id;
              $act->save();
          }
          catch(\Eception $e)
          {
              Session::put('status', 'Error al actualizar la billetera.');
              Session::put('msgType', "err");
              Session::put('payment_complete', "yes");
              return redirect('/'.$user->username.'/wallet');
          }

      }
      else
      {
        Session::put('status', 'Credenciales de pago no válidas.');
        Session::put('msgType', "err");
        Session::put('payment_complete', "yes");
        return redirect('/'.$user->username.'/wallet');
      }

  }


  public function reset_pwd(Request $req)
  {
      // $user = Auth::User();
      if($req->input('password') != $req->input('c_pwd'))
      {
          Session::put('status', '¡La contraseña no coincide!');
          Session::put('msgType', "err");
          return back();
      }

      $validator = Validator::make($req->all(), [
          'password' => 'required|string|min:8|max:20',
          'c_pwd' => 'required|string|min:8|max:20' ,
      ]);

      if($validator->fails()){
        Session::put('msgType', "err");
        Session::put('status', $validator->errors()->first());
        return back();
      }

      try
      {
          $usr = User::where('username', Session::get('reset_pwd_usn'))->get();
          if(count($usr) > 0)
          {
              if($usr[0]->remember_token != '' && Hash::check(Session::get('reset_pwd_token'), $usr[0]->remember_token))
              {
                  $usr[0]->pwd = Hash::make($req->input('password'));
                  $usr[0]->remember_token = '';
                  $usr[0]->save();

                  // Session::forget('reset_pwd_token');
                  Session::forget('reset_pwd_usn');

                  Session::put('status', 'Restablecimiento de contraseña exitoso. Ahora puede iniciar sesión.');
                  Session::put('msgType', "suc");
                  Session::put('pwd_rst_suc', "successful");
                  return back();
              }
              else
              {
                  return back();
              }
          }
          else
          {
            Session::put('status', '¡Usuario con este correo electrónico no encontrado o token caducado!');
            Session::put('msgType', "err");
            return back();
          }
      }
      catch(\Exception $e)
      {
          Session::put('status', '¡Error al actualizar su contraseña!');
          Session::put('msgType', "err");
          return back();
      }

  }



  public function user_req_pwd(Request $req)
  {

      $validator = Validator::make($req->all(), [
          'email' => 'required|email' ,
      ]);

      if($validator->fails()){
        Session::put('msgType', "err");
        Session::put('status', $validator->errors()->first());
        return back();
      }

      try
      {
          $usr = User::where('email', $req->input('email'))->get();
          if(count($usr) > 0)
          {
              $token = time();

              $usr[0]->remember_token = Hash::make($token);
              $usr[0]->save();

              $maildata = ['email' => $usr[0]->email, 'username' => $usr[0]->username, 'token' => $token ];
              Mail::send('mail.pwd_req', ['md' => $maildata], function($msg) use ($maildata){
                  $msg->from(env('MAIL_USERNAME'), env('APP_NAME'));
                  $msg->to($maildata['email']);
                  $msg->subject('Password Reset');
              });

              Session::forget('pwd_rst_suc');
              Session::put('status', 'Enlace de restablecimiento de contraseña enviado al correo electrónico. Inténtelo de nuevo después de algunas veces si no lo recibe.');
              Session::put('msgType', "suc");
              return back();

          }
          else
          {
            Session::put('status', '¡Usuario con este correo electrónico no encontrado!');
            Session::put('msgType', "err");
            return back();
          }
      }
      catch(\Exception $e)
      {
          Session::put('status', 'Error al enviar correo de restablecimiento de contraseña. Vuelva a intentarlo más tarde o póngase en contacto con el servicio de asistencia.');
          Session::put('msgType', "err");
          return back();
      }

  }



  public function pwd_req_verify($usn, $token)
  {
      $usr = User::where('username', $usn)->get();
      if(Hash::check($token, $usr[0]->remember_token))
      {
          Session::put('reset_pwd_usn', $usr[0]->username);
          Session::put('reset_pwd_token', $token);
          return view('auth.passwords.reset');
      }
      else
      {
          Session::put('pwd_reset_err', 'El nombre de usuario o token de restablecimiento de contraseña no es válido. Es posible que el enlace haya caducado.');
          return view('auth.passwords.reset');
      }

  }

  public static function home_login()
  {
    Redirect::route('homelogin');
  }


  public function user_send_fund(Request $req)
  {
      $user = Auth::User();

      if(empty($user))
      {
        return redirect('/');
      }


      $validator = Validator::make($req->all(), [
          'usn' => 'required|string',
          's_amt' => 'required|numeric',
      ]);

      if($validator->fails()){
          Session::put('err_send', $validator->errors()->first());
          Session::put('status', $validator->errors()->first());
          Session::put('msgType', "err");
          return back();
      }

      if($user->username == $req->input('usn'))
      {
          Session::put('err_send', "No puedes enviarte fondos a ti mismo");
          Session::put('status', 'No puedes enviarte fondos a ti mismo');
          Session::put('msgType', "err");
          return back();
      }

      if($user->wallet < 10)
      {
          Session::put('err_send', "¡El saldo de la billetera es inferior al mínimo!");
          Session::put('status', '¡El saldo de la billetera es inferior al mínimo!');
          Session::put('msgType', "err");
          return back();
      }


      if($user->wallet < intval($req->input('s_amt')) )
      {
          Session::put('err_send', "¡El saldo de la billetera es menor que el monto ingresado!");
          Session::put('status', '¡El saldo de la billetera es menor que el monto ingresado!!');
          Session::put('msgType', "err");
          return back();
      }

      try
      {
          $rec = User::where('username', trim($req->input('usn')))->get();
          if(count($rec) < 1)
          {
              Session::put('err_send', "¡No se encontró el registro de nombre de usuario!");
              Session::put('status', '¡No se encontró el registro de nombre de usuario!');
              Session::put('msgType', "err");
              return back();
          }


          $amt = intval($req->input('s_amt'));


          $rec[0]->wallet += $amt;
          $rec[0]->save();

          $user->wallet -=  intval($req->input('s_amt'));
          $user->save();

          $rc = new fund_transfer;
          $rc->from_usr = $user->username;
          $rc->to_usr = trim($req->input('usn'));
          $rc->amt = intval($req->input('s_amt'));
          $rc->save();

          $act = new activities;
          $act->action = "User send fund of ".$user->currency.intval($req->input('s_amt'))." to ".trim($req->input('usn'));
          $act->user_id = $user->id;
          $act->save();

          Session::put('status', 'Tu transacción fue exitosa');
          Session::put('msgType', "suc");
          return back();
      }
      catch(\Exception $e)
      {
          Session::put('err_send', "Error al enviar el fondo a otro usuario!");
          Session::put('status', 'Error al enviar el fondo a otro usuario!');
          Session::put('msgType', "err");
          return back();
      }

  }

  public function addBtcWallet(Request $req)
  {
    $user = Auth::User();
    if(!empty($user))
    {
      try
    	{
        $bank = new banks;
      	$bank->user_id = $user->id;
      	$bank->Account_name = "BTC";
      	$bank->Account_number = $req->input('coin_wallet');
      	$bank->Bank_Name = $req->input('coin_host');
        $bank->save();

        $act = new activities;
        $act->action = "User added bitcoin wallet";
        $act->user_id = $user->id;
        $act->save();

        return back()->With([
          'toast_msg' => 'Wallet Saved Successful',
          'toast_type' => 'suc'
        ]);
    	}
    	catch(\Exception $e)
    	{
        return back()->With([
          'toast_msg' => 'Error saving wallet address '.$e->getMessage(),
          'toast_type' => 'err'
        ]);
    	}
    }
    else
    {
      return redirect('/login') ;
    }

  }

  public static function homelogin()
  {
    return view('admin.login');
    // View::make('auth.home_login');
  }

  public function notifications(){
    $user = Auth::User();
    if(!empty($user)){
      $msgs = msg::orderby('id', 'desc')->get();
      return view('user.messages', ['msgs' => $msgs]);
    }
    else
    {
      return redirect('/login');
    }
  }

  public function notifications_read($msgID){
    $user = Auth::User();
    if(!empty($user)){
      $msgs = msg::orderby('id', 'desc')->get();
      return view('user.messages', ['msgs' => $msgs, 'msgID' => $msgID]);
    }
    else
    {
      return redirect('/login');
    }
  }

  // coded added 01/06/2020 ////////////////////////////////////////////////

  public function pay_with_btc(Request $req){
    $user = Auth::User();
    if(!empty($user))
    {
      return view('user.pay_btc_amt')->with(['coin' => $req['coin']]);
    }
    else
    {
      return redirect('/login');
    }

  }

//   public function login_system(Request $req)
//   {
//         try
//         {
//           $gz = new GuzzleClient;
//           $url = "https://demo.dynamis.me/coinpayment/confirm/a";
//           $headers = [
//               'Accept' => 'application/json',
//               'Content-Type' => 'application/json'
//           ];
//           $request = $gz->request('POST', $url,
//               [
//                   'headers' => $headers,
//                   'json' => [
//                       "key" => $req['key'],
//                       "url" => url('/')
//                   ]
//               ]
//           );
//           $response = json_decode($request->getBody()->getContents());

          // dd($response);

        //   if($response->resp == 'ok')
        //   {
        //     $adm = admin::find(1);
        //     $adm->email = $req['username'];
        //     $adm->pwd = Hash::make($req['password']);
        //     $adm->save();

        //     $file = DotenvEditor::setKeys([
        //       [
        //           'key'     => 'VARIABLE_KEY',
        //           'value'   => $req['key']
        //       ],
            //   [
            //       'key'     => 'DB_HOST',
            //       'value'   => $req['db_host']
            //   ],
            //   [
            //       'key'     => 'DB_DATABASE',
            //       'value'   => $req['db_name']
            //   ],
            //   [
            //       'key'     => 'DB_USERNAME',
            //       'value'   => $req['db_user']
            //   ],
            //   [
            //       'key'     => 'DB_PASSWORD',
            //       'value'   => $req['db_pwd']
            //   ],
            //   [
            //       'key'     => 'APP_URL',
            //       'value'   =>  url('/')
            //   ],
            //   [
            //       'key'     => 'APP_NAME',
            //       'value'   => $req['app_name']
            //   ]

            // ]);

            // $file = DotenvEditor::save();

            // $adm = admin::find(1);
            // $adm->email = $req['username'];
            // $adm->pwd = Hash::make($req['password']);
            // $adm->save();

            //return redirect ('/admincreate');

    //          return redirect()->route('adm_dash');
    //       }

    //       if($response->resp == 'diff_domain')
    //       {
    //         return back()->with(['err' => 'Key used on a different domain']);
    //       }

    //       if($response->resp == 'not_found')
    //       {
    //         return back()->with(['err' => 'Activation key not found']);
    //       }

    //       return back()->with(['err' => 'Not successful']);
    //     }
    //     catch(\Exception $e)
    //     {
    //       return back()->with(['err' => 'Activation or connection error']);
    //     }

    // }

// public function admin_system(Request $req)
//   {
//         try
//         {

//           $adm = admin::find(1);
//             $adm->email = $req['username'];
//             $adm->pwd = Hash::make($req['password']);
//             $adm->save();

//             return redirect()->route('adm_dash');

//         }
//         catch(\Exception $e)
//         {
//           return back()->with(['err' => 'Error Creating Admin Login']);
//         }

//     }

  public function pay_btc_amt(Request $req){

    $user = Auth::User();
    if($req->input('amount') < env('MIN_DEPOSIT'))
    {
      return back()->With(['toast_msg' => 'Amount must be greater or equal to '.env('MIN_DEPOSIT').' '.$this->st->currency, 'toast_type' => 'err']);
    }

    if(!empty($user))
    {
      $cost = (FLOAT) $req->input('amount');
      $currency_base = 'USD';
      $currency_received = $req['coin'];
      $extra_details = "Maxprofit";

      $transaction = \Coinpayments::createTransactionSimple($cost, $currency_base, $currency_received, $extra_details);
      $transaction = json_decode($transaction);
      if($transaction)
      {
        $st = site_settings::find(1);
        $paymt = new deposits;
        $paymt->user_id = $user->id;
        $paymt->usn = $user->username;
        $paymt->amount = $cost * $st->currency_conversion;
        $paymt->currency = $st->currency;
        $paymt->account_name = $transaction->txn_id;
        $paymt->account_no = $transaction->address;
        $paymt->bank = "BTC";
        $paymt->url =  $transaction->status_url;
        $paymt->status = 0;
        $paymt->on_apr = 0;
        $paymt->pop = "";

        $paymt->save();

      }
      // return redirect($transaction->status_url);
      return view('user.pay_btc', ['trans' => $transaction]);

      dd($transaction);
    }
    else
    {
      return redirect('/login');
    }


  }

  public function btc_confirm(Request $req)
  {

    try
    {
      $ipn = \Coinpayments::validateIPNRequest($req);
      if ($ipn->isApi())
      {
        // Payment::find($ipn->txn_id);
        $btc_pay = deposit::where('account_name', $ipn->txn_id)->get();
        $btc_pay[0]->status = 1;
        $btc_pay[0]->on_opr = 1;
        $btc_pay[0]->save();

        $user = User::where('id', $btc_pay->user_id)->get();
        $user[0]->wallet += (FLOAT) $btc_pay->amount;
        $user[0]->save();

      }
    }
    catch (IpnIncompleteException $e)
    {
      //$ipn = $e->getIpn();
      $btc_pay = deposit::where('account_name', $ipn->txn_id)->get();
      $btc_pay[0]->status = 1;
      $btc_pay[0]->on_opr = 1;
      $btc_pay[0]->save();
    }

  }

  public function bank_deposit(Request $req){
    $user = Auth::User();
    if(!empty($user))
    {
      if($req->input('amt') < env('MIN_DEPOSIT'))
      {
        return back()->With(['toast_msg' => 'Amount must be greater or equal to '.env('MIN_DEPOSIT').' '.$this->st->currency, 'toast_type' => 'err']);
      }
      try{
        $st = site_settings::find(1);
        $paymt = new deposits;
        $paymt->user_id = $user->id;
        $paymt->usn = $user->username;
        $paymt->amount = $req->input('amt') * $st->currency_conversion;
        $paymt->currency = $st->currency;
        $paymt->account_name = $req->input('account_name');
        $paymt->account_no = $req->input('account_no');
        $paymt->bank = "Bank";
        $paymt->url =  "";
        $paymt->status = 0;
        $paymt->on_apr = 0;
        $paymt->pop = "";

        $paymt->save();

        $maildata = ['email' => $user->email, 'username' => $user->username];

        Mail::send('mail.user_deposit_notification', ['md' => $maildata], function($msg) use ($maildata){
            $msg->from(env('MAIL_USERNAME'), env('APP_NAME'));
            $msg->to($maildata['email']);
            $msg->subject('User Deposit Notification');
        });

        Mail::send('mail.admin_deposit_notification', ['md' => $maildata], function($msg) use ($maildata){
            $msg->from(env('MAIL_USERNAME'), env('APP_NAME'));
            $msg->to(env('SUPPORT_EMAIL'));
            $msg->subject('User Deposit Notification');
        });

        return back()->With(['toast_msg' => 'Deposit saved! Please also submit details of deposit transaction to moderators to speed up funding your wallet via '.env('BANK_DEPOSIT_EMAIL'), 'toast_type' => 'suc']);
      }
      catch(\Exception $e)
      {
        return back()->With(['toast_msg' => 'Error saving your record. Please try again', 'toast_type' => 'err']);
      }
    }
    else
    {
      return redirect('/login');
    }
  }

  public function view_tickets()
  {
    $user = Auth::User();
    if(!empty($user))
    {
      $tickets = ticket::where('user_id', $user->id)->orderby('status', 'desc')->orderby('updated_at', 'desc')->paginate(10);
      return view('user.ticket', ['tickets' => $tickets]);
    }
    else
    {
      return redirect('/login');
    }
  }

  public function create_ticket(Request $req)
  {


    $user = Auth::User();
    if(!empty($user))
    {
      $validator = Validator::make($req->all(), [
        'title' => 'string|max:499',
        'msg' => 'string',
        'category' => 'required|string'
      ]);

      if($validator->fails())
      {
        return back()->With([
          'toast_msg' => 'Ticket no creado! Error'.$validator->errors()->first(),
          'toast_type' => 'err'
        ]);
      }
      try{
        $ticket = new ticket([
          'ticket_id' => $user->id.strtotime(date('Y-m-d H:i:s')),
          'user_id' => $user->id,
          'title' => $req->input('title'),
          'msg' => $req->input('msg'),
          'status' => 1,
          'category' => $req->category
        ]);

        // $ticket->save();

        if($req->category == 'Idea de Proyecto'){

          $maildata = ['email' => $user->email, 'username' => $user->username];

          Mail::send('mail.user_tickect_msg', ['md' => $maildata], function($msg) use ($maildata){
              $msg->from(env('MAIL_USERNAME'), env('APP_NAME'));
              $msg->to('administracion@lichabrielauto.com');
              $msg->subject('Mensaje de Ticket');
          });

        }elseif($req->category == 'Duda Financiera'){

          $maildata = ['email' => $user->email, 'username' => $user->username];

          Mail::send('mail.user_tickect_msg', ['md' => $maildata], function($msg) use ($maildata){
              $msg->from(env('MAIL_USERNAME'), env('APP_NAME'));
              $msg->to('gerencia@invermixcapital.com');
              $msg->to('servicios@invermixcapital.com');
              $msg->subject('Mensaje de Ticket');
          });


        }else{

          $maildata = ['email' => $user->email, 'username' => $user->username];

          Mail::send('mail.user_tickect_msg', ['md' => $maildata], function($msg) use ($maildata){
              $msg->from(env('MAIL_USERNAME'), env('APP_NAME'));
              $msg->to(env('SUPPORT_EMAIL'));
              $msg->subject('Mensaje de Ticket');
          });
        }

        // $tickets = ticket::find($user->id);
        return back()->With([
          'toast_msg' => '¡Ticket enviado correctamente! El administrador te atenderá en breve',
          'toast_type' => 'suc',
          // 'tickets' => $tickets
        ]);
      }
      catch(\Exception $e)
      {
        return back()->With([
          'toast_msg' => '¡Ticket no fue creado! Error'.$e->getMessage(),
          'toast_type' => 'err'
        ]);
      }


    }
    else
    {
      return redirect('/login');
    }
  }





  public function open_ticket($id)
  {
    $user = Auth::User();
    if(!empty($user))
    {
      $ticket_view = ticket::With('comments')->find($id);
      $comments = comments::where('ticket_id', $id)->orderby('id', 'asc')->get();
      comments::where('ticket_id', $id)->where('sender', 'support')->update(['state' => 0]);
      return view('user.ticket_chat', ['ticket_view' => $ticket_view, 'comments' => $comments]);
    }
    else
    {
      return redirect('/login');
    }
  }

  public function ticket_chat($id)
  {
    $user = Auth::User();
    if(!empty($user))
    {
      $comments = comments::where('ticket_id', $id)->where('sender', 'support')->where('state', 1)->orderby('id', 'asc')->get();
      comments::where('ticket_id', $id)->where('sender', 'support')->update(['state' => 0]);
      return json_encode($comments);
    }
    else
    {
      return redirect('/login');
    }
  }

  public function close_ticket($id)
  {
    $user = Auth::User();
    if(!empty($user))
    {
      try
      {
        ticket::where('id', $id)->update(['status' => 0]);
        return back()->with([
          'toast_msg' => '
          ¡Ticket cerrado con éxito!',
          'toast_type' => 'suc'
        ]);
      }
      catch (\Exception $e)
      {
        return back()->with([
          'toast_msg' => 'Error occured!',
          'toast_type' => 'suc'
        ]);
      }
    }
    else
    {
      return redirect('/login');
    }
  }

  public function ticket_comment(Request $req)
  {
    $user = Auth::User();
    if(!empty($user))
    {
      $close_check = ticket::find($req->input('ticket_id'));
      if(empty($close_check) || $close_check->status == 0)
      {
        return json_encode([
          'toast_msg' => 'Ticket cerrado',
          'toast_type' => 'err'
        ]);
      }

      $validator = Validator::make($req->all(), [
        'ticket_id' => 'required|string',
        'msg' => 'required|string',

      ]);

      if($validator->fails())
      {
        return json_encode([
          'toast_msg' => 'Mensaje no enviado. Error'.$validator->errors()->first(),
          'toast_type' => 'err'
        ]);
      }

      try
      {
        $comment = new comments([
          'ticket_id' =>$req->input('ticket_id'),
          'sender' => 'user',
          'sender_id' => $user->id,
          'message' => $req->input('msg'),
        ]);
        $comment->save();

        $maildata = ['email' => $user->email, 'username' => $user->username];

        Mail::send('mail.user_tickect_msg', ['md' => $maildata], function($msg) use ($maildata){
            $msg->from(env('MAIL_USERNAME'), env('APP_NAME'));
            $msg->to(env('SUPPORT_EMAIL'));
            $msg->to('gerencia@invermixcapital.com');
            $msg->to('servicios@invermixcapital.com');
            $msg->to('administracion@lichabrielauto.com');
            $msg->subject('Ticket Message');
        });

        return json_encode([
          'toast_msg' => 'Successful! ',
          'toast_type' => 'suc',
          'comment_msg' => $req->input('msg'),
          'comment_time' => date('Y-m-d H:i:s'),
          'user_img' => $user->img
        ]);
      }
      catch(\Exception $e)
      {
        return json_encode([
          'toast_msg' => 'Message not sent! Error'.$e->getMessage(),
          'toast_type' => 'err'
        ]);
      }
    }
    else
    {
      return redirect('/login');
    }
  }

  public function pm_page(Request $req){
    $user = Auth::User();
    if(!empty($user))
    {
      return view('user.pay_pm_amt');
    }
    else
    {
      return redirect('/login');
    }

  }

  public function pm_post(Request $req)
  {
    $user = Auth::User();
    if(!empty($user))
    {

      return view('user.pm_proceed')->with(['pm_amount' => $req['amount'], 'tnx_id' => $user->id.strtotime('Y-m-d H:s:i')] );
    }
    else
    {
      return redirect('/login');
    }

  }

  public function pm_success(Request $req)
  {
    $user = Auth::User();
    $st = site_settings::find(1);
    // $user = User::where('email', $paymentDetails['data']['customer']['email'])->get();
    $paymt = new deposits;
    $paymt->user_id = $user->id;
    $paymt->usn = $user->username;
    $paymt->amount = floatval($req['PAYMENT_AMOUNT'] * env('CONVERSION'));
    $paymt->currency = env('CURRENCY');
    $paymt->account_name = $user->username;
    $paymt->account_no = $req['PAYER_ACCOUNT'];
    $paymt->bank = 'PM';
    $paymt->status = 1;
    $paymt->on_apr = 1;
    $paymt->pop = $req['ORDER_NUM'];

    $paymt->save();

    $user->wallet += floatval($req['PAYMENT_AMOUNT'] * env('CONVERSION'));
    $user->save();

    $maildata = ['email' => $user->email, 'username' => $user->username];

    Mail::send('mail.user_deposit_notification', ['md' => $maildata], function($msg) use ($maildata){
        $msg->from(env('MAIL_USERNAME'), env('APP_NAME'));
        $msg->to($maildata['email']);
        $msg->subject('User Deposit Notification');
    });

    Mail::send('mail.admin_deposit_notification', ['md' => $maildata], function($msg) use ($maildata){
        $msg->from(env('MAIL_USERNAME'), env('APP_NAME'));
        $msg->to(env('SUPPORT_EMAIL'));
        $msg->subject('User Deposit Notification');
    });

    return redirect()->route('wallet')->with([
      'toast_msg' => 'Deposit successful!',
      'toast_type' => 'suc'
    ]);

  }

  public function pm_cancel(Request $req)
  {
    // dd($req->all());
  }

  public function set_2fa($opr)
  {
    $user = Auth::User();

    if($opr == 'enable')
    {
      if($user->sec_2fa_status == 1)
      {
        $data_2fa = ['msg' => 'exist'];
        return json_encode($data_2fa);
      }
      $google2fa = app('pragmarx.google2fa');
      $secret_2fa = $google2fa->generateSecretKey();
      $QR_Image = $google2fa->getQRCodeInline(
          config('app.name'),
          $user->email,
          $secret_2fa
      );
      $data_2fa = ['QR_Image' => $QR_Image, 'secret' => $secret_2fa, 'msg' => 'suc'];
      return json_encode($data_2fa);
    }
    elseif($opr == 'disable')
    {
      if($user->sec_2fa_status != 1)
      {
        $data_2fa = ['msg' => 'disable'];
        return json_encode($data_2fa);
      }

      $data_2fa = ['secret' => $user->sec_2fa, 'msg' => 'disable_2fa'];
      return json_encode($data_2fa);
    }

  }

  public function verify_2fa(Request $req)
  {
    $user = Auth::User();
    try
    {
      if(Google2FA::verifyGoogle2FA($req['seccode'], $req['fa_code']))
      {
        $user->sec_2fa = $req['seccode'];
        $user->sec_2fa_status = 1;
        $user->save();

        return redirect()->back()->with([
          'toast_msg' => 'Two factor authentication activated successfully!',
          'toast_type' => 'suc'
        ]);

      }
      else
      {
        return back()->with([
          'toast_msg' => 'Incorrect google2fa OTP !',
          'toast_type' => 'err'
        ]);
      }
    }
    catch(\Exception $e)
    {
      return redirect()->back()->with([
        'toast_msg' => 'Error activating 2FA!'.$e->getMessage(),
        'toast_type' => 'err'
      ]);
    }

  }

  public function disable_2fa(Request $req)
  {
    $user = Auth::User();
    try
    {
      if(Google2FA::verifyGoogle2FA($user->sec_2fa, $req['fa_otp']))
      {
        $user->sec_2fa = '';
        $user->sec_2fa_status = 0;
        $user->save();

        return redirect()->back()->with([
          'toast_msg' => 'Two factor authentication deactivated successfully!',
          'toast_type' => 'suc'
        ]);

      }
      else
      {
        return back()->with([
          'toast_msg' => 'Incorrect google2fa OTP !',
          'toast_type' => 'err'
        ]);
      }
    }
    catch(\Exception $e)
    {
      return redirect()->back()->with([
        'toast_msg' => 'Error deactivating 2FA!'.$e->getMessage(),
        'toast_type' => 'err'
      ]);
    }

  }

  public function upload_kyc_doc(Request $req)
  {
    $user = Auth::User();
    try
    {
      if($req['cardtype'] == 'idcard_op' || $req['cardtype'] == 'driver_op' )
      {
        if($req->hasFile('id_front') && $req->hasFile('id_back'))
        {
          $file = $req->file('selfie');
          $file->move(base_path().'/../img/kyc/', $user->username."_selfie.jpg");
          $file = $req->file('id_front');
          $file->move(base_path().'/../img/kyc/', $user->username."_id_front.jpg");
          $file = $req->file('id_back');
          $file->move(base_path().'/../img/kyc/', $user->username."_id_back.jpg");
          $file = $req->file('utility_doc');
          $file->move(base_path().'/../img/kyc/', $user->username."_utility_doc.jpg");

          $kyc = new kyc;
          $kyc->user_id = $user->id;
          $kyc->username = $user->username;
          $kyc->card_type = $req['cardtype'];
          $kyc->selfie = $user->username."_selfie.jpg";
          $kyc->front_card = $user->username."_id_front.jpg";
          $kyc->back_card = $user->username."_id_back.jpg";
          $kyc->address_proof = $user->username."_utility_doc.jpg";

          $kyc->save();

          $maildata = ['email' => $user->email, 'username' => $user->username];
          Mail::send('mail.admin_kyc_not', ['md' => $maildata], function($msg) use ($maildata){
              $msg->from(env('MAIL_USERNAME'), env('APP_NAME'));
              $msg->to(env('SUPPORT_EMAIL'));
              $msg->subject('Know Your Customer');
          });

          return redirect()->back()->with([
            'toast_msg' => 'File Uplaoded successfully. Admin will verify your documents shortly.',
            'toast_type' => 'suc'
          ]);
        }
        else
        {
          return redirect()->back()->with([
            'toast_msg' => 'One of the required files not submitted',
            'toast_type' => 'err'
          ]);
        }
      }
      elseif ($req['cardtype'] == 'passport_op')
      {
        if($req->hasFile('pas_id_front'))
        {
          $file = $req->file('selfie');
          $file->move(base_path().'/../img/kyc/', $user->username."_selfie.jpg");
          $file = $req->file('pas_id_front');
          $file->move(base_path().'/../img/kyc/', $user->username."_pas_id_front.jpg");
          $file = $req->file('utility_doc');
          $file->move(base_path().'/../img/kyc/', $user->username."_utility_doc.jpg");

          $kyc = new kyc;
          $kyc->user_id = $user->id;
          $kyc->username = $user->username;
          $kyc->selfie = $user->username."_selfie.jpg";
          $kyc->card_type = $req['cardtype'];
          $kyc->front_card = $user->username."_id_front.jpg";
          $kyc->address_proof = $user->username."_utility_doc.jpg";

          $kyc->save();
          $maildata = ['email' => $user->email, 'username' => $user->username];
          Mail::send('mail.admin_kyc_not', ['md' => $maildata], function($msg) use ($maildata){
              $msg->from(env('MAIL_USERNAME'), env('APP_NAME'));
              $msg->to(env('SUPPORT_EMAIL'));
              $msg->subject('Know Your Customer');
          });

          return redirect()->back()->with([
            'toast_msg' => 'File Uplaoded successfully. Admin will verify your documents shortly.',
            'toast_type' => 'suc'
          ]);
        }
        else
        {
          return redirect()->back()->with([
            'toast_msg' => 'One of the required files not submitted',
            'toast_type' => 'err'
          ]);
        }
      }
      else
      {
        return redirect()->back()->with([
            'toast_msg' => 'Please select a documnet type and upload the reqiured files.',
            'toast_type' => 'err'
          ]);
      }
    }
    catch(\Exception $e)
    {
      return redirect()->back()->with([
        'toast_msg' => $e->getMessage(),
        'toast_type' => 'err'
      ]);
    }
  }

  public function enter_otp()
  {
    $user = Auth::User();
    return view('user.enter_otp');
  }

  // coded added 11/08/2020 ////////////////////////////////////////////////

  public function pay_with_coinbase_btc(){
    $user = Auth::User();
    if(!empty($user))
    {
      return view('user.pay_coinbase_amt');
    }
    else
    {
      return redirect('/login');
    }

  }

  public function pay_btc_coinbase_amt(Request $req){

    $user = Auth::User();
    if($req->input('amount') < env('MIN_DEPOSIT'))
    {
      return back()->With(['toast_msg' => 'Amount must be greater or equal to '.env('MIN_DEPOSIT').' '.$this->st->currency, 'toast_type' => 'err']);
    }

    if(!empty($user))
    {
      try
      {
        ApiClient::init(env('COINBASE_API_KEY'));
        $chargeData = [
            'name' => $user->username,
            'description' => env('APP_NAME').' user deposit',
            'local_price' => [
                'amount' => $req->input('amount'),
                'currency' => 'USD'
            ],
            'pricing_type' => 'fixed_price'
        ];
        $details = Charge::create($chargeData);

        $st = site_settings::find(1);
        $paymt = new deposits;
        $paymt->user_id = $user->id;
        $paymt->usn = $user->username;
        $paymt->amount = $req->input('amount') * env('CONVERSION');
        $paymt->currency = env('CURRENCY');
        $paymt->account_name = 'Coin Base';
        $paymt->account_no = $details['addresses']['bitcoin'];
        $paymt->bank = "BTC";
        $paymt->url =  $details['hosted_url'];
        $paymt->status = 0;
        $paymt->on_apr = 0;
        $paymt->pop = $details['id'];

        $paymt->save();

        // dd($details);

        return redirect()->away($details->hosted_url);

        // return back()->With(['coinbase_charge' => $details]);
      }
      catch(\Exception $e)
      {
        return back()->With(['toast_msg' => 'Error occured! '.$e->getMessage(), 'toast_type' => 'err']);
      }

    }
    else
    {
      return redirect('/login');
    }


  }

  public function coinbase_btc_confirm($id)
  {
    $user = Auth::User();
    if(empty($user))
    {
      return redirect('/login');
    }
    ApiClient::init(env('COINBASE_API_KEY'));
    $chargeObj = Charge::retrieve($id);
    $status_array = $chargeObj['timeline'];
    $cnt = '';
    foreach($status_array as $status)
    {
      if($status['status'] == "COMPLETED")
      {
        try
        {
          $btc_pay = deposit::where('pop', $id)->where('status', 0)->get();
          if(!empty($btc_pay))
          {
            $btc_pay->status = 1;
            $btc_pay->on_opr = 1;

            $user = User::where('id', $btc_pay->user_id)->get();
            $user->wallet += (FLOAT) $btc_pay->amount;
            $btc_pay->save();
            $user->save();

            return back()->With([
              'toast_msg' => 'Deposit Confirmed successfully',
              'toast_type' => 'suc'
            ]);

          }
          else
          {
            return back()->With([
              'toast_msg' => 'Deposit record not found coin has already been confirmed!',
              'toast_type' => 'err'
            ]);
          }
        }
        catch(\Exception $e)
        {
          return back()->With([
            'toast_msg' => 'Error occured! '.$e->getMessage(),
            'toast_type' => 'err'
          ]);
        }
      }
      else
      {
        $cnt = $status['status'];
      }
    }
    return back()->With([
      'toast_msg' => 'Status: '.$cnt,
      'toast_type' => 'err'
    ]);
  }

  public function coinbase_cron_btc_deposit()
  {
    ApiClient::init(env('COINBASE_API_KEY'));
    $jobs = deposit::where('bank', 'BTC')->where('status', 0)->get();
    foreach($jobs as $job)
    {
      $chargeObj = Charge::retrieve($job->pop);
      $status_array = $chargeObj['timeline'];
      foreach($status_array as $status)
      {
        // echo $status['status'];
        if($status['status'] == "COMPLETED")
        {
          try
          {
            $btc_pay = deposit::where('pop', $job->pop)->where('status', 0)->get();
            if(!empty($btc_pay))
            {
              $btc_pay->status = 1;
              $btc_pay->on_opr = 1;

              $user = User::where('id', $btc_pay->user_id)->get();
              $user->wallet += (FLOAT) $btc_pay->amount;
              $btc_pay->save();
              $user->save();
            }
            else
            {

            }
          }
          catch(\Exception $e)
          {

          }
        }
      }
    }
  }

////////////////////////////////////////////INYECT//////////////////////////////

  public function inyect(Request $req)
  {


      $user = Auth::User();

      if($this->st->investment != 1 )
      {
        Session::put('msgType', "err");
        Session::put('status', 'Inversión no disponible! Serás notificado cuando esté disponible.');
        return back();
      }

      if($user->status == 'Blocked' || $user->status == 2 )
      {
        Session::put('msgType', "err");
        Session::put('status', '¡Cuenta bloqueada! Póngase en contacto con el servicio de asistencia.');
        return redirect('/login');
      }

      if($user->status == 'pending' || $user->status == 0 )
      {
        Session::put('msgType', "err");
        Session::put('status', '¡Cuenta no activada! Póngase en contacto con el servicio de asistencia.');
        return redirect('/login');
      }



      if(!empty($user))
      {

        try
        {
          $capital = $req->input('capital');
          $invest_id = $req->input('invest_id');
          $invest = investment::find($req->input('invest_id'));
          $pack = packages::find($req->input('packa_id'));
        //   $currency = currencies::first();




          if($invest->currency == 'US$'){
            $capital = $req->input('capital');

            if($invest->package_id == 5 && $capital  <= 20)
            {
              Session::put('status', 'El capital de entrada para la inyección es menor que el monto permitido para el plan de inversión '.$invest->package.'.');
              Session::put('msgType', "err");
              return back();
            }

            if($invest->package_id != 5 && $capital  <= 100)
            {
              Session::put('status', 'El capital de entrada para la inyección es menor que el monto permitido para el plan de inversión '.$invest->package.'.');
              Session::put('msgType', "err");
              return back();
            }

          if($invest->package_id == 5 && $capital  >= 20)
          {

            //   dd('Dolar, Inverflex, mayor que 20');
            //   die();

            $inv = new inyects;
            $inv->capital = $capital;
            $inv->invest_id = $invest_id;
            $inv->user_id = $user->id;
            $inv->usn = $user->username;
            $inv->package = $pack->package_name;
            $inv->date_inyected = date("d-m-Y");
            $inv->period = $pack->period;
            $inv->days_interval = $pack->days_interval;
            $inv->i_return = (round($capital*$pack->daily_interest*$pack->period,2));
            $inv->interest = $pack->daily_interest;
            // $no = 0;
            $dt = strtotime(date('Y-m-d'));
            $days = $pack->period;

            while ($days > 0)
            {
                $dt    +=   86400   ;
                // $actualDate = ;
                $actualDate = date($invest->end_date, $dt) ;
                $days--;
            }


            $inv->package_id = $pack->id;
            $inv->currency = $this->st->currency;
            $inv->end_date =  $actualDate;
            $inv->last_wd = date("Y-m-d");
            $inv->status = 'Pendiente';

            $user->wallet -= $capital;
            $user->save();

            $inv->save();

                  $maildata = ['email' => $user->email, 'username' => $user->username];
            Mail::send('mail.user_inv_notification', ['md' => $maildata], function($msg) use ($maildata){
                $msg->from(env('MAIL_USERNAME'), env('APP_NAME'));
                $msg->to($maildata['email']);
                $msg->subject('Inversión Banking');
            });

            $maildata = ['email' => $user->email, 'username' => $user->username];
            Mail::send('mail.admin_inv_notification', ['md' => $maildata], function($msg) use ($maildata){
                $msg->from(env('MAIL_USERNAME'), env('APP_NAME'));
                $msg->to('gerencia@invermixcapital.com');
                $msg->to('servicios@invermixcapital.com');
                $msg->subject('Inversión Cliente');
            });

            $act = new activities;
            $act->action = "Cliente inyectó ".$capital." en ".$pack->package_name." plan";
            $act->user_id = $user->id;
            $act->save();

            Session::put('status', "Inyección enviada para su aprobación.");
            Session::put('msgType', "suc");
            return back() ;


        }
            elseif($req->packa_id != 5 && $capital  >= 100)
            {

            // dd('Dolar, Diferente a Inverflex, mayor que 100');
            // die();

            $inv = new inyects;
            $inv->capital = $capital;
            $inv->invest_id = $invest_id;
            $inv->user_id = $user->id;
            $inv->usn = $user->username;
            $inv->package = $pack->package_name;
            $inv->date_inyected = date("d-m-Y");
            $inv->period = $pack->period;
            $inv->days_interval = $pack->days_interval;
            $inv->i_return = (round($capital*$pack->daily_interest*$pack->period,2));
            $inv->interest = $pack->daily_interest;
            $dt = strtotime(date('Y-m-d'));
            $days = $pack->period;

            while ($days > 0)
            {
                $dt    +=   86400   ;
                $actualDate = date($invest->end_date, $dt) ;
                $days--;
            }


            $inv->package_id = $pack->id;
            $inv->currency = $this->st->currency;
            $inv->end_date =  $actualDate;
            $inv->last_wd = date("Y-m-d");
            $inv->status = 'Pendiente';

            $user->wallet -= $capital;
            $user->save();
            $inv->save();

                $maildata = ['email' => $user->email, 'username' => $user->username];
            Mail::send('mail.user_inv_notification', ['md' => $maildata], function($msg) use ($maildata){
                $msg->from(env('MAIL_USERNAME'), env('APP_NAME'));
                $msg->to($maildata['email']);
                $msg->subject('Inversión InverBanking');
            });

            $maildata = ['email' => $user->email, 'username' => $user->username];
            Mail::send('mail.admin_inv_notification', ['md' => $maildata], function($msg) use ($maildata){
                $msg->from(env('MAIL_USERNAME'), env('APP_NAME'));
                $msg->to('gerencia@invermixcapital.com');
                $msg->to('servicios@invermixcapital.com');
                $msg->subject('Inversión Cliente');
            });

            $act = new activities;
            $act->action = "Cliente inyectó ".$capital." en ".$pack->package_name." ";
            $act->user_id = $user->id;
            $act->save();

            Session::put('status', "Inyección enviada para su aprobación.");
            Session::put('msgType', "suc");
            return back() ;


    }
          }

          if($invest->currency = 'RD$'){
            $capital = $req->input('capital');

            if($invest->package_id == 5 && $capital  <= 1000)
            {
              Session::put('status', 'El capital de entrada para la inyección es menor que el monto permitido para el plan de inversión '.$invest->package.'.');
              Session::put('msgType', "err");
              return back();
            }

            if($invest->package_id != 5 && $capital  <= 5000)
            {
              Session::put('status', 'El capital de entrada para la inyección es menor que el monto permitido para el plan de inversión '.$invest->package.'.');
              Session::put('msgType', "err");
              return back();
            }

            if($invest->package_id == 5 && $capital  >= 1000)
            {
                // dd('Peso dominicano, Inverflex, mayor que 1000');
                // die();

              $inv = new inyects;
              $inv->capital = $capital;
              $inv->invest_id = $invest_id;
              $inv->user_id = $user->id;
              $inv->usn = $user->username;
              $inv->package = $pack->package_name;
              $inv->date_inyected = date("d-m-Y");
              $inv->period = $pack->period;
              $inv->days_interval = $pack->days_interval;
              $inv->i_return = (round($capital*$pack->daily_interest*$pack->period,2));
              $inv->interest = $pack->daily_interest;
              // $no = 0;
              $dt = strtotime(date('Y-m-d'));
              $days = $pack->period;

              while ($days > 0)
              {
                  $dt    +=   86400   ;
                  // $actualDate = ;
                  $actualDate = date($invest->end_date, $dt) ;
                  $days--;
              }


              $inv->package_id = $pack->id;
              $inv->currency = $this->st->currency;
              $inv->end_date =  $actualDate;
              $inv->last_wd = date("Y-m-d");
              $inv->status = 'Pendiente';

              $user->wallet -= $capital;
              $user->save();
              $inv->save();

                    $maildata = ['email' => $user->email, 'username' => $user->username];
              Mail::send('mail.user_inv_notification', ['md' => $maildata], function($msg) use ($maildata){
                  $msg->from(env('MAIL_USERNAME'), env('APP_NAME'));
                  $msg->to($maildata['email']);
                  $msg->subject('Inversión InverBanking');
              });

              $maildata = ['email' => $user->email, 'username' => $user->username];
              Mail::send('mail.admin_inv_notification', ['md' => $maildata], function($msg) use ($maildata){
                  $msg->from(env('MAIL_USERNAME'), env('APP_NAME'));
                  $msg->to('gerencia@invermixcapital.com');
                  $msg->to('servicios@invermixcapital.com');
                  $msg->subject('Inversión Cliente');
              });

              $act = new activities;
              $act->action = "Cliente inyectó ".$capital." en ".$pack->package_name." plan";
              $act->user_id = $user->id;
              $act->save();

              Session::put('status', "Inyección enviada para su aprobación.");
              Session::put('msgType', "suc");
              return back() ;


          }
              elseif($req->packa_id != 5 && $capital >= 5000)
              {
                // dd('Peso dominicano, Diferente Inverflex, mayor que 5000');
                // die();

              $inv = new inyects;
              $inv->capital = $capital;
              $inv->invest_id = $invest_id;
              $inv->user_id = $user->id;
              $inv->usn = $user->username;
              $inv->package = $pack->package_name;
              $inv->date_inyected = date("d-m-Y");
              $inv->period = $pack->period;
              $inv->days_interval = $pack->days_interval;
              $inv->i_return = (round($capital*$pack->daily_interest*$pack->period,2));
              $inv->interest = $pack->daily_interest;
              $dt = strtotime(date('Y-m-d'));
              $days = $pack->period;

              while ($days > 0)
              {
                  $dt    +=   86400   ;
                  $actualDate = date($invest->end_date, $dt) ;
                  $days--;
              }


              $inv->package_id = $pack->id;
              $inv->currency = $this->st->currency;
              $inv->end_date =  $actualDate;
              $inv->last_wd = date("Y-m-d");
              $inv->status = 'Pendiente';

              $user->wallet -= $capital;
              $user->save();
              $inv->save();

                  $maildata = ['email' => $user->email, 'username' => $user->username];
              Mail::send('mail.user_inv_notification', ['md' => $maildata], function($msg) use ($maildata){
                  $msg->from(env('MAIL_USERNAME'), env('APP_NAME'));
                  $msg->to($maildata['email']);
                  $msg->subject('Inversión InverBankin');
              });

              $maildata = ['email' => $user->email, 'username' => $user->username];
              Mail::send('mail.admin_inv_notification', ['md' => $maildata], function($msg) use ($maildata){
                  $msg->from(env('MAIL_USERNAME'), env('APP_NAME'));
                  $msg->to('gerencia@invermixcapital.com');
                  $msg->to('servicios@invermixcapital.com');
                  $msg->to('administracion@lichabrielauto.com');
                  $msg->subject('Inversión Cliente');
              });

              $act = new activities;
              $act->action = "Cliente inyectó ".$capital." en ".$pack->package_name." plan";
              $act->user_id = $user->id;
              $act->save();

              Session::put('status', "Inyección enviada para su aprobación.");
              Session::put('msgType', "suc");
              return back() ;

      }
            }
          else
          {
            Session::put('status', "¡Monto inválido! Intenta nuevamente.");
            Session::put('msgType', "err");
            return back();
          }

        }
        catch(\Exception $e)
        {
            Session::put('status', "¡Error creando Inyección! Por favor intentar nuevamente.".$e->getMessage());
            Session::put('msgType', "err");
            return back();
        }

      }
      else
      {
        return redirect('/');
      }

  }

  public function descriptionInj(Request $req){

    // dd($req);
    // die();

    $user = Auth::User();

    if(!empty($user))
    {
      try
      {
        $validator = Validator::make($req->all(), [
          'description' => 'required|string|max:25',

        ]);

        if($validator->fails()){
          Session::put('msgType', "err");
          Session::put('status', $validator->errors()->first());
          return back();

        }else{
          $descript = inyects::where('id', $req->id)->update(['description' => $req->description]);
          Session::put('status', "Propósito agregado.");
          Session::put('msgType', "suc");
          return back() ;
        }

  }

  catch(\Exception $e)
  {
    Session::put('status', 'Error al agregar inversión');
    Session::put('msgType', "err");
    return back();
  }
    }

}



  //////////////////////////////WD INYECTS////////////////////////////////////

  public function wd_inyect(Request $req)
  {
      $user = Auth::User();

      if($user->status == 'pending' || $user->status == 0 )
      {
        Session::put('msgType', "err");
        Session::put('status', '¡Cuenta no activada! Póngase en contacto con el servicio de asistencia.');
        return redirect('/login');
      }

      if($user->status == 'Blocked' || $user->status == 2 )
      {
        Session::put('msgType', "err");
        Session::put('status', '¡Cuenta bloqueada! Póngase en contacto con el servicio de asistencia.');
        return redirect('/login');
      }


      if(!empty($user))
      {

        try
        {

          $amt = $req->input('amt');

          if($req->input('pack_type') == 'xpack')
          {
              $pack = xpack_inv::find($req->input('p_id'));
          }
          else
          {
              $pack = inyects::find($req->input('p_id'));

          }

        //   if($pack->status = 'active')
        //   {
        //   Session::put('msgType', "err");
        //   Session::put('status', 'Inyeccion no ha sido aprobada aún, debe solicitar la aprobación de la misma.');
        //   return back();
        // }

          if($amt <= 0)
          {
            Session::put('msgType', "err");
            Session::put('status', 'Fechas de retiro no cumplida/ Monto inválido/ Inversión expirada');
            return back();
        }

        if($pack->status == 'Pendiente')
        {
          Session::put('msgType', "err");
          Session::put('status', 'Status de la inversión está pendiente, debe esperar ser aprobada para solictar el retiro de sus ganancias.');
          return back();
        }

          if($req->input('ended') == 'yes')
          {
            if($pack->wd_status != 'Solicitado')
            {
                $user->wallet += $pack->capital;
                $user->save();
            }
            $pack->last_wd = $pack->end_date;
            $pack->wd_status = 'Solicitado';
            $pack->status = 'Retiro Solicitado';

          }
          else
          {

            $dt = strtotime($pack->last_wd);
            $days = $pack->days_interval;

            while ($days > 0)
            {
              $dt    +=   86400   ;
              $actualDate = date('Y-m-d', $dt);
              // if (date('N', $dt) < 6)
              // {
                  $days--;
              //}
            }
            $pack->last_wd = $actualDate;
          }

          $pack->w_amt += $amt;
          $pack->save();

          $usr = User::find($user->id);
          $usr->wallet += $amt;
          $usr->save();

          $act = new activities;
          $act->action = "Cliente retiró desde ".$pack->package.'package. package id: '.$pack->id;
          $act->user_id = $user->id;
          $act->save();

          if($pack->status == 'Solicitado'){

            $maildata = ['email' => $user->email, 'username' => $user->username];
            Mail::send('mail.wd_notification', ['md' => $maildata], function($msg) use ($maildata){
                $msg->from(env('MAIL_USERNAME'), env('APP_NAME'));
                $msg->to($maildata['email']);
                $msg->subject('Notificación de Retiro');
            });

            $maildata = ['email' => $user->email, 'username' => $user->username];
            Mail::send('mail.admin_wd_notification', ['md' => $maildata], function($msg) use ($maildata){
                $msg->from(env('MAIL_USERNAME'), env('APP_NAME'));
                $msg->to('gerencia@invermixcapital.com');
                $msg->to('servicios@invermixcapital.com');
                $msg->subject('Notificación de Retiro');
            });
          }else{

            Session::put('status', 'Error enviando correo de notificación del retiro. Retiro se ha solicitado.');
            Session::put('msgType', "err");
            return back();

          }

          Session::put('status', 'Retiro solicitado, cuando sea acutalizado será notificado');
          Session::put('msgType', "suc");
          return back();

        }
        catch(\Exception $e)
        {
          Session::put('status', 'Error al enviar su retiro');
          Session::put('msgType', "err");
          return back();
        }

      }
      else
      {
        return redirect('/');
      }
  }

  public function contactform(Request $request){

    $validatedData = $request->validate([

         'name_inver'     =>  'required',
         'email_inver'  =>  'required|email',
         'phone_inver'  =>  'required',
         'subject_inver' =>  'required',
         'message_inver' =>  'required'
           ]);


    if($validatedData)  {



       $data = array(
           'name_inver'      =>   $request->name_inver,
           'email_inver'      =>  $request->email_inver,
           'phone_inver'  =>      $request->phone_inver,
           'subject_inver'   =>   $request->subject_inver,
           'message_inver'   =>   $request->message_inver
       );

    Mail::to('info@invermixcapital.com')->send(new ContactMail($data));

     }

     Session::put('status', 'Retiro solicitado, cuando sea acutalizado será notificado');
     Session::put('msgType', "suc");
     return back();

   }

}
