<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;
// use Illuminate\Support\Facades\Auth;
// use Illuminate\Support\Facades\File;

use DotenvEditor;

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
use App\msg;
use App\admin;
use App\deposits;
use App\withdrawal;
use App\adminLog;
use App\xpack_inv;
use Validator;
use App\site_settings;
use App\kyc;
use App\ref_set;
use App\ticket;
use App\comments;
use App\companies;
use App\currencies;
use App\inyects;


class adminController extends Controller
{
  private $data_files = [];
  private $settings;

  public function __construct()
  {
      // parent::__construct();
      $this->settings = site_settings::find(1);
  }

  public function load_data(){
    $adm = Session::get('adm');
    $inv = investment::orderby('id', 'desc')->get();
    $deposits = deposits::orderby('id', 'desc')->get();
    $users = User::orderby('id', 'desc')->get();
    $wd = withdrawal::orderby('id', 'desc')->get();
    $today_wd = withdrawal::where('created_at','like','%'.date('Y-m-d').'%')->orderby('id', 'desc')->get();
    $today_dep = deposits::where('created_at','like','%'.date('Y-m-d').'%')->orderby('id', 'desc')->get();
    $today_inv = investment::where('date_invested', date('Y-m-d'))->orderby('id', 'desc')->get();
    $logs =  adminLog::orderby('id', 'desc')->get();
    $settings = site_settings::find(1);



    $this->data_files = [$adm, $inv, $deposits, $users, $wd, $today_wd, $today_dep, $today_inv, $logs, $settings];
    return $this->data_files;
  }
  public function index()
  {
      //return view('user.');
  }
  public function backLogin()
  {
    return view('admin.login', ['settings' => $this->settings]);
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

  public function adm_login(Request $req)
  {
    $adm = admin::where('email', $req->input('email'))->get();
    if(count($adm) > 0)
    {
      if($adm[0]->status == 0)
      {
        Session::put('err2', "??Cuenta no activada!");
        return back();
      }
      if(Hash::check($req->input('password'), $adm[0]->pwd))
      {
        $adm = admin::find($adm[0]->id);
        Session::put('adm', $adm);
        Auth::logout();
        $act = new adminLog;
        $act->admin = $adm->email;
        $act->action = "Logged in to the system";
        $act->save();

        return redirect()->route('adm_dash');
        // return 's';
      }
      else
      {
        Session::put('err2', "??La contrase??a de inicio de sesi??n no es correcta!");
        return back();
      }

    }
    else
    {
      Session::put('err2', "??El nombre de usuario de inicio de sesi??n no es correcto!");
      return back();
    }
  }


  public function getMonthlyIvCart()
  {
    $cap = 0;
    $nm;
    $sm = array();
    for ($i = 1; $i <= 12; $i++)
    {
      $cap = 0;
      if(strlen($i) == 1)
      {
        $nm = '0'.$i;
      }
      else
      {
        $nm = $i;
      }
      $mIvs = investment::where('date_invested', 'like', '%'.date('Y').'-'.$nm.'%')->get();
      if(count($mIvs) > 0)
      {
        foreach($mIvs as $m)
        {
          $cap = $cap + intval($m->capital);
        }
      }
      else
      {
        $cap = 0;
      }

      array_push($sm, intval($cap));
    }

    return json_encode($sm);

  }

  public function updateUserProfile(Request $req)
  {
        if(Session::has('adm') && !empty(Session::get('adm')))
        {

          $adm = Session::get('adm');

          try
          {
            $validate = $req->validate([
                 'phone' => 'required|digits_between:7,15',
              ]);

            //$country = country::find($req->input('country'))
              $usr = User::find($req->input('uid'));
              $usr->country = $req->input('country');
              $usr->state = $req->input('state');
              $usr->address = $req->input('adr');
              $usr->phone = $req->input('cCode').$req->input('phone');
              $usr->currency = $this->settings->currency;


              $usr->save();

              $act = new adminLog;
              $act->admin = $adm->email;
              $act->action = "Updated User profile. User_id: ".$req->input('uid');
              $act->save();

              Session::put('status', "Exitoso");
              Session::put('msgType', "suc");
              return back();

          }
          catch(\Exception $e)
          {
            Session::put('status', "??Error guardando tu informaci??n! Por favor colocar un n??mero v??lido.");
            Session::put('msgType', "err");
            return back();
          }

        }
        else
        {
          return redirect('/');
        }

  }





  public function changeUserPwd(Request $req)
  {

    // dd($req);
    // die();

    if(Session::has('adm') && !empty(Session::get('adm')))
    {

      if($req->input('newpwd') != $req->input('cpwd'))
      {
        Session::put('status', "Contrasenas no coinciden.");
        Session::put('msgType', "err");
        return back();
      }

      try
      {
        $usr = User::find($req->input('uid'));

        $usr->pwd = Hash::make($req->input('newpwd'));
        $usr->save();

        $adm = Session::get('adm');
        $act = new adminLog;
        $act->admin = $adm->email;
        $act->action = "Contrasena cambiada. User_id: ".$req->input('uid');
        $act->save();

        Session::put('status', "Satisfactoriamente.");
        Session::put('msgType', "suc");
        return back();

      }
      catch(\Exception $e)
      {
        Session::put('status', "Error actualizando contrasena. Intenta otra vez.");
        Session::put('msgType', "err");
        return back();
      }


    }
    else
    {
      return redirect('/');
    }

  }


  public function blockUser($id)
  {
    if(Session::has('adm') && !empty(Session::get('adm')))
    {

      try
      {
        $usr = User::find($id);
        $usr->status = 2;
        $usr->save();

        $adm = Session::get('adm');
        $act = new adminLog;
        $act->admin = $adm->email;
        $act->action = "Blocked User Account. User_id: ".$id;
        $act->save();

        Session::put('status', "Exitoso");
        Session::put('msgType', "suc");
        return back();

      }
      catch(\Exception $e)
      {
        Session::put('status', "??Error al actualizar el registro! Int??ntalo de nuevo");
        Session::put('msgType', "err");
        return back();
      }


    }
    else
    {
      return redirect('/');
    }

  }

  public function activateUser($id)
  {
    if(Session::has('adm') && !empty(Session::get('adm')))
    {

      try
      {
        $usr = User::find($id);
        $usr->status = 1;
        $usr->save();

        $adm = Session::get('adm');
        $act = new adminLog;
        $act->admin = $adm->email;
        $act->action = "Activate User account. User_id: ".$id;
        $act->save();

        Session::put('status', "Successful");
        Session::put('msgType', "suc");
        return back();

      }
      catch(\Exception $e)
      {
        Session::put('status', "??Error al actualizar el registro! Int??ntalo de nuevo");
        Session::put('msgType', "err");
        return back();
      }


    }
    else
    {
      return redirect('/');
    }

  }

  public function deleteUser($id)
  {
    if(Session::has('adm') && !empty(Session::get('adm')))
    {

      try
      {
        $usr = User::where('id',$id)->delete();

        $adm = Session::get('adm');
        $act = new adminLog;
        $act->admin = $adm->email;
        $act->action = "Delete User account. User_id: ".$id;
        $act->save();

        Session::put('status', "Exitoso");
        Session::put('msgType', "suc");
        return redirect('/admin/manage/users'); //

      }
      catch(\Exception $e)
      {
        Session::put('status', "??Error al actualizar el registro! Int??ntalo de nuevo");
        Session::put('msgType', "err");
        return back();
      }


    }
    else
    {
      return redirect('/');
    }

  }


  public function searchInv(Request $req)
  {
    if(Session::has('adm') && !empty(Session::get('adm')))
    {
      Session::put('val', $req->input('search_val'));
      return back();
    }
    else
    {
      return redirect('/');
    }

  }

  public function searchXInv(Request $req)
  {
    if(Session::has('adm') && !empty(Session::get('adm')))
    {
      Session::put('val', $req->input('search_val'));
      return back();
    }
    else
    {
      return redirect('/');
    }

  }


  public function pauseInv($id)
  {
    if(Session::has('adm') && !empty(Session::get('adm')))
    {

      try
      {
        $usr = investment::find($id);
        $usr->status = 'Pausada';
        $usr->save();

        $adm = Session::get('adm');
        $act = new adminLog;
        $act->admin = $adm->email;
        $act->action = "Paused User Investment. Investment id: ".$id;
        $act->save();

        Session::put('status', "Successful");
        Session::put('msgType', "suc");
        return back(); //

      }
      catch(\Exception $e)
      {
        Session::put('status', "??Error al actualizar el registro! Int??ntalo de nuevo");
        Session::put('msgType', "err");
        return back();
      }


    }
    else
    {
      return redirect('/');
    }

  }

//   public function activateInv($id)
//   {
//     if(Session::has('adm') && !empty(Session::get('adm')))
//     {

//       try
//       {
//         $usr = investment::find($id);
//         $usr->status = 'Activa';
//         $usr->save();

//         $adm = Session::get('adm');
//         $act = new adminLog;
//         $act->admin = $adm->email;
//         $act->action = "Inversi??n de usuario activada. Inversi??n id: ".$id;
//         $act->save();

//         Session::put('status', "Successful");
//         Session::put('msgType', "suc");
//         return back(); //

//       }
//       catch(\Exception $e)
//       {
//         Session::put('status', "??Error al actualizar el registro! Int??ntalo de nuevo");
//         Session::put('msgType', "err");
//         return back();
//       }


//     }
//     else
//     {
//       return redirect('/');
//     }

//   }



public function activateInv(Request $request)
  {

    if(Session::has('adm') && !empty(Session::get('adm')))
    {

      try
      {
         $company = companies::find($request->company);

         if($company->status  ==  1 && $company->currency  ==  $request->currency){

             $capitalsubs = str_replace(',', '', $request->capital);
             $divide = str_replace(',', '', $request->capital) / $company->bonus_cost;
             $company->decrement('a_capital', $capitalsubs);
             $company->decrement('a_bonus', $divide);
             $company->increment('sold_bonus', $divide);
             $company->save();

             $usr = investment::find($request->id);
             $usr->status = 'Activa';
             $usr->save();

             $adm = Session::get('adm');
             $act = new adminLog;
             $act->admin = $adm->email;
             $act->action = "Inversi??n de usuario activada. Inversi??n id: ".$request->id;
             $act->save();

             Session::put('status', "Exitoso");
             Session::put('msgType', "suc");
             return back(); //

             }else{

            Session::put('status', "??Error! Verificar si moneda de compa????a es igual a la inversi??n que desea activar.");
            Session::put('msgType', "err");
            return back();

          }

      }
      catch(\Exception $e)
      {
        Session::put('status', "??Error al actualizar el registro! Int??ntalo de nuevo");
        Session::put('msgType', "err");
        return back();
      }


    }
    else
    {
      return redirect('/');
    }

  }


  public function deleteInv($id)
  {
    if(Session::has('adm') && !empty(Session::get('adm')))
    {

      try
      {
        $inv = investment::find($id);

        $inv_user = User::find($inv->user_id);
        $amt = $inv->capital;

        if($inv->w_amt == 0)
        {
            $inv_user->wallet += $amt;
            $inv_user->save();
        }

        investment::where('id',$id)->update(array('deleted_at' => date('Y-m-d H:i:s')));

        $adm = Session::get('adm');
        $act = new adminLog;
        $act->admin = $adm->email;
        $act->action = "Inversi??n de usuario eliminado. Inversi??n id: ".$id;
        $act->save();

        Session::put('status', "Exitoso");
        Session::put('msgType', "suc");
        return back();

      }
      catch(\Exception $e)
      {
        Session::put('status', "??Error actualizando registro! Try again");
        Session::put('msgType', "err");
        return back();
      }


    }
    else
    {
      return redirect('/');
    }

  }

  public function xpauseInv($id)
  {
    if(Session::has('adm') && !empty(Session::get('adm')))
    {

      try
      {
        $usr = xpack_inv::find($id);
        $usr->status = 'Paused';
        $usr->save();

        $adm = Session::get('adm');
        $act = new adminLog;
        $act->admin = $adm->email;
        $act->action = "Paused User Investment. Investment id: ".$id;
        $act->save();

        Session::put('status', "Exitoso");
        Session::put('msgType', "suc");
        return back(); //

      }
      catch(\Exception $e)
      {
        Session::put('status', "??Error al actualizar el registro! Int??ntalo de nuevo");
        Session::put('msgType', "err");
        return back();
      }


    }
    else
    {
      return redirect('/');
    }

  }

  public function xactivateInv($id)
  {
    if(Session::has('adm') && !empty(Session::get('adm')))
    {

      try
      {
        $usr = xpack_inv::find($id);
        $usr->status = 'Active';
        $usr->save();

        $adm = Session::get('adm');
        $act = new adminLog;
        $act->admin = $adm->email;
        $act->action = "Activated User Investment. Investment id: ".$id;
        $act->save();

        Session::put('status', "Exitoso");
        Session::put('msgType', "suc");
        return back(); //

      }
      catch(\Exception $e)
      {
        Session::put('status', "??Error al actualizar el registro! Int??ntalo de nuevo");
        Session::put('msgType', "err");
        return back();
      }


    }
    else
    {
      return redirect('/');
    }

  }

  public function xdeleteInv($id)
  {
    if(Session::has('adm') && !empty(Session::get('adm')))
    {

      try
      {
        $inv = xpack_inv::find($id);

        $inv_user = User::find($inv->user_id);
        $amt = $inv->capital;

        if($inv->w_amt == 0)
        {
            $inv_user->wallet += $amt;
            $inv_user->save();
        }

        xpack_inv::where('id',$id)->delete();

        $adm = Session::get('adm');
        $act = new adminLog;
        $act->admin = $adm->email;
        $act->action = "Deleted User Investment. Investment id: ".$id;
        $act->save();

        Session::put('status', "Exitioso");
        Session::put('msgType', "suc");
        return back();

      }
      catch(\Exception $e)
      {
        Session::put('status', "??Error al actualizar el registro! Int??ntalo de nuevo");
        Session::put('msgType', "err");
        return back();
      }


    }
    else
    {
      return redirect('/');
    }

  }

  public function searchDep(Request $req)
  {
    if(Session::has('adm') && !empty(Session::get('adm')))
    {
      Session::put('val', $req->input('search_val'));
      return back();
    }
    else
    {
      return redirect('/');
    }

  }

  public function searchWD(Request $req)
  {
    if(Session::has('adm') && !empty(Session::get('adm')))
    {
      Session::put('val', $req->input('search_val'));
      return back();
    }
    else
    {
      return redirect('/');
    }

  }


  public function searchadminUser(Request $req)
  {
    if(Session::has('adm') && !empty(Session::get('adm')))
    {
      Session::put('val', $req->input('search_val'));
      return back();
    }
    else
    {
      return redirect('/');
    }

  }



  public function admSearch(Request $req)
  {
    if(Session::has('adm') && !empty(Session::get('adm')))
    {
      Session::put('val', $req->input('search_val'));
      return back();
    }
    else
    {
      return redirect('/');
    }

  }


  public function rejectDep($id)
  {
    if(Session::has('adm') && !empty(Session::get('adm')))
    {

      try
      {
        $usr = deposits::find($id);

        $dep_user = User::find($usr->user_id);
        $amt = $usr->amount;

        if($usr->on_apr == 1)
        {
            $dep_user->wallet -= $amt;
            $dep_user->save();
        }

        $usr->on_apr = 0;
        $usr->status = 2;
        $usr->save();

        $adm = Session::get('adm');
        $act = new adminLog;
        $act->admin = $adm->email;
        $act->action = "Rejected user deposit. Deposit id: ".$id;
        $act->save();

        Session::put('status', "Exitoso");
        Session::put('msgType', "suc");
        return back(); //

      }
      catch(\Exception $e)
      {
        Session::put('status', "??Error al actualizar el registro! Int??ntalo de nuevo");
        Session::put('msgType', "err");
        return back();
      }


    }
    else
    {
      return redirect('/');
    }

  }

  public function approveDep($id)
  {
    if(Session::has('adm') && !empty(Session::get('adm')))
    {

      try
      {
        $usr = deposits::find($id);
        if($usr->status == 1)
        {
          return back()->with([
            'toast_msg' => 'Deposit already approved!',
            'toast_type' => 'err'
          ]);
        }

        $dep_user = User::find($usr->user_id);
        $amt = $usr->amount;

        if($usr->on_apr == 0)
        {
            $dep_user->wallet += $amt;
            $dep_user->save();
        }
        $usr->status = 1;
        $usr->on_apr = 1;
        $usr->save();

        $adm = Session::get('adm');
        $act = new adminLog;
        $act->admin = $adm->email;
        $act->action = "Approved user deposit. Deposit id: ".$id;
        $act->save();

        return back()->with([
          'toast_msg' => 'Deposit approved successfully!',
          'toast_type' => 'suc'
        ]);

      }
      catch(\Exception $e)
      {
        return back()->with([
          'toast_msg' => "Error updating record! Try again",
          'toast_type' => 'err'
        ]);
        return back();
      }

    }
    else
    {
      return redirect('/');
    }

  }

  public function deleteDep($id)
  {
    if(Session::has('adm') && !empty(Session::get('adm')))
    {

      try
      {

        $usr = deposits::find($id);

        $dep_user = User::find($usr->user_id);
        $amt = $usr->amount;

        if($usr->on_apr == 1)
        {
            $dep_user->wallet -= $amt;
            $dep_user->save();
        }

        deposits::where('id',$id)->delete();


        $adm = Session::get('adm');
        $act = new adminLog;
        $act->admin = $adm->email;
        $act->action = "Deleted ".$dep_user->username." deposit. Amount: ".$amt;
        $act->save();

        Session::put('status', "Exitoso");
        Session::put('msgType', "suc");
        return back();

      }
      catch(\Exception $e)
      {
        Session::put('status', "??Error al borrando el registro! Int??ntalo de nuevo");
        Session::put('msgType', "err");
        return back();
      }


    }
    else
    {
      return redirect('/');
    }

  }

  public function rejectWD($id)
  {
    if(Session::has('adm') && !empty(Session::get('adm')))
    {

      try
      {
        $usr = investment::find($id);

        if($usr->wd_status == 'Rechazado')
        {
          return back()->with([
            'toast_msg' => '??Retiro ya rechazado!',
            'toast_type' => 'err'
          ]);
        }

        $usr->wd_status = 'Rechazado';

        $user = User::find($usr->user_id);
        $user->wallet += $usr->amount;
        $user->save();

        $usr->save();

        $adm = Session::get('adm');
        $act = new adminLog;
        $act->admin = $adm->email;
        $act->action = "Retiro de usuario rechazado. id de retiro: ".$id;
        $act->save();

        return back()->with([
            'toast_msg' => '??El retiro se rechaz?? con ??xito!',
            'toast_type' => 'suc'
        ]);

      }
      catch(\Exception $e)
      {
        return back()->with([
          'toast_msg' => '??Error al actualizar el registro! ??Int??ntalo de nuevo!',
          'toast_type' => 'err'
        ]);
      }


    }
    else
    {
      return redirect('/');
    }

  }

  public function approveWD($id)
  {
    if(Session::has('adm') && !empty(Session::get('adm')))
    {

      try
      {
        $usr = investment::find($id);
        if($usr->wd_status == 'Depositado')
        {
          return back()->with([
            'toast_msg' => '??Retiro ya depositado!',
            'toast_type' => 'err'
          ]);
        }

        if($usr->wd_status == 'Rechazado')
        {
          return back()->with([
            'toast_msg' => 'Retiro ya rechazado. El usuario debe crear una nueva solicitud de retiro.',
            'toast_type' => 'err'
          ]);
        }

        // $userID = $usr->user_id;
        $usr->wd_status = 'Depositado';
        $usr->status = 'Depositado';
        $usr->save();

        $adm = Session::get('adm');
        $act = new adminLog;
        $act->admin = $adm->email;
        $act->action = "Retiro de usuario aprobado. Id de retiro: ".$id;
        $act->save();

        return back()->with([
                'toast_msg' => 'Aprobado con ??xito!',
                'toast_type' => 'suc'
              ]);

    //     $user_act = User::find($userID);

    //     $maildata = ['email' => $user_act->email ];
    //     Mail::send('mail.admin_approve_wd', ['md' => $maildata], function($msg) use ($maildata){
    //         $msg->from(env('MAIL_USERNAME'), env('APP_NAME'));
    //         $msg->to($maildata['email']);
    //         $msg->subject('Aprobaci??n de retiro');
    //     });
    //     return back()->with([
    //     'toast_msg' => 'Aprobado con ??xito!',
    //     'toast_type' => 'suc'
    //   ]);

      }
      catch(\Exception $e)
      {
        return back()->with([
          'toast_msg' => '??Error al actualizar el registro! ??Int??ntalo de nuevo!',
          'toast_type' => 'err'
        ]);
      }

    }
    else
    {
      return redirect('/');
    }

  }

//   public function deleteWD($id)
//   {
//     if(Session::has('adm') && !empty(Session::get('adm')))
//     {

//       try
//       {
//         investment::where('id',$id)->delete();

//         $adm = Session::get('adm');
//         $act = new adminLog;
//         $act->admin = $adm->email;
//         $act->action = "Deleted user withdrawal. withdrawal id: ".$id;
//         $act->save();

//         return back()->with([
//           'toast_msg' => 'Successful',
//           'toast_type' => 'suc'
//         ]);

//       }
//       catch(\Exception $e)
//       {
//         return back()->with([
//           'toast_msg' => 'Error Deleting record! Try again',
//           'toast_type' => 'err'
//         ]);
//       }


//     }
//     else
//     {
//       return redirect('/');
//     }

//   }

public function rejectWD_INJ($id)
  {
    if(Session::has('adm') && !empty(Session::get('adm')))
    {

      try
      {
        $usr = inyects::find($id);

        if($usr->wd_status == 'Rechazado')
        {
          return back()->with([
            'toast_msg' => '??Retiro ya rechazado!',
            'toast_type' => 'err'
          ]);
        }

        $usr->wd_status = 'Rechazado';

        $user = User::find($usr->user_id);
        $user->wallet += $usr->amount;
        $user->save();

        $usr->save();

        $adm = Session::get('adm');
        $act = new adminLog;
        $act->admin = $adm->email;
        $act->action = "Retiro de usuario rechazado. id de retiro: ".$id;
        $act->save();

        return back()->with([
            'toast_msg' => '??El retiro se rechaz?? con ??xito!',
            'toast_type' => 'suc'
        ]);

      }
      catch(\Exception $e)
      {
        return back()->with([
          'toast_msg' => '??Error al actualizar el registro! ??Int??ntalo de nuevo!',
          'toast_type' => 'err'
        ]);
      }


    }
    else
    {
      return redirect('/');
    }

  }

  public function approveWD_INJ($id)
  {
    if(Session::has('adm') && !empty(Session::get('adm')))
    {

      try
      {
        $usr = inyects::find($id);
        if($usr->wd_status == 'Depositado')
        {
          return back()->with([
            'toast_msg' => '??Retiro ya depositado!',
            'toast_type' => 'err'
          ]);
        }

        if($usr->wd_status == 'Rechazado')
        {
          return back()->with([
            'toast_msg' => 'Retiro ya rechazado. El usuario debe crear una nueva solicitud de retiro.',
            'toast_type' => 'err'
          ]);
        }

        // $userID = $usr->user_id;
        $usr->wd_status = 'Depositado';
        $usr->status = 'Depositado';
        $usr->save();

        $adm = Session::get('adm');
        $act = new adminLog;
        $act->admin = $adm->email;
        $act->action = "Retiro de usuario aprobado. Id de retiro: ".$id;
        $act->save();

        return back()->with([
                'toast_msg' => 'Aprobado con ??xito!',
                'toast_type' => 'suc'
              ]);

    //     $user_act = User::find($userID);

    //     $maildata = ['email' => $user_act->email ];
    //     Mail::send('mail.admin_approve_wd', ['md' => $maildata], function($msg) use ($maildata){
    //         $msg->from(env('MAIL_USERNAME'), env('APP_NAME'));
    //         $msg->to($maildata['email']);
    //         $msg->subject('Aprobaci??n de retiro');
    //     });
    //     return back()->with([
    //     'toast_msg' => 'Aprobado con ??xito!',
    //     'toast_type' => 'suc'
    //   ]);

      }
      catch(\Exception $e)
      {
        return back()->with([
          'toast_msg' => '??Error al actualizar el registro! ??Int??ntalo de nuevo!',
          'toast_type' => 'err'
        ]);
      }

    }
    else
    {
      return redirect('/');
    }

  }

  ///////////////////////////////////////////  pack edit//////////////////////////////////////////////////

  public function editPack(Request $req)
  {
    if(Session::has('adm') && !empty(Session::get('adm')))
    {
      try
      {
        $pack = packages::find($req->input('p_id'));
        $pack->min = $req->input('min');
        $pack->currency = $this->settings->currency;
        $pack->max = $req->input('max');
        $pack->mindol = $req->input('mindol');
        $pack->maxdol = $req->input('maxdol');
        $pack->daily_interest = ($req->input('interest') / 100)/$pack->period;
        // $pack->withdrwal_fee = ($req->input('fee'))/100;
        $pack->save();

        $adm = Session::get('adm');
        $act = new adminLog;
        $act->admin = $adm->email;
        $act->action = "Edited investment package. Package id: ".$req->input('p_id');
        $act->save();

        return back()->with([
          'toast_msg' => '??Actualizado!',
          'toast_type' => 'suc'
        ]);
      }
      catch(\Exception $e)
      {
        return back()->with([
          'toast_msg' => 'Error saving record! Try again!',
          'toast_type' => 'err'
        ]);
      }

    }
    else
    {
      return redirect('/');
    }

  }


  public function admin_ban_user($id)
  {
    if(Session::has('adm') && !empty(Session::get('adm')))
    {

      $adm = Session::get('adm');
      try
      {
        $usr = admin::find($id);
         if($usr->id == $adm->id)
        {
          Session::put('status', "??No puede realizar esta acci??n en usted mismo! Int??ntalo de nuevo");
          Session::put('msgType', "err");
          return back();
        }

        if($usr->role >= $adm->role && $adm->id != 1)
        {
          Session::put('status', "??Error al actualizar el registro! Int??ntalo de nuevo");
          Session::put('msgType', "err");
          return back();
        }
        else
        {
          $usr->status = '0';
          $usr->save();
        }


        $act = new adminLog;
        $act->admin = $adm->email;
        $act->action = "Blocked admin user. user id: ".$id;
        $act->save();

        Session::put('status', "Successful");
        Session::put('msgType', "suc");
        return back(); //

      }
      catch(\Exception $e)
      {
        Session::put('status', "??Error al actualizar el registro! Int??ntalo de nuevo");
        Session::put('msgType', "err");
        return back();
      }


    }
    else
    {
      return redirect('/');
    }

  }

  public function admin_activate_user($id)
  {
    if(Session::has('adm') && !empty(Session::get('adm')))
    {
      $adm = Session::get('adm');
      try
      {
        $usr = admin::find($id);
        if($usr->id == $adm->id)
        {
          Session::put('status', "??No puede realizar esta acci??n en usted mismo! Int??ntalo de nuevo");
          Session::put('msgType', "err");
          // return $usr->id.' '.$adm->id;
          return back();
        }

        if($usr->role >= $adm->role && $adm->id != 1)
        {
          Session::put('status', "??Error al actualizar el registro! Int??ntalo de nuevo");
          Session::put('msgType', "err");
          return back();
        }
        else
        {
          $usr->status = '1';
          $usr->save();
        }


        $act = new adminLog;
        $act->admin = $adm->email;
        $act->action = "Activated admin user. user id: ".$id;
        $act->save();

        Session::put('status', "Successful");
        Session::put('msgType', "suc");
        return back(); //

      }
      catch(\Exception $e)
      {
        Session::put('status', "??Error al actualizar el registro! Int??ntalo de nuevo");
        Session::put('msgType', "err");
        return back();
      }


    }
    else
    {
      return redirect('/');
    }

  }

  public function dadmin_delete_user($id)
  {
    if(Session::has('adm') && !empty(Session::get('adm')))
    {

      $adm = Session::get('adm');
      try
      {
        $usr = admin::find($id);
        if($usr->id == $adm->id)
        {
          Session::put('status', "??No puedes borrarte a ti mismo! Int??ntalo de nuevo");
          Session::put('msgType', "err");
          return back();
        }

        if($usr->role >= $adm->role && $adm->author != 0 && $usr->author != $adm->id)
        {
          Session::put('status', "??Error al actualizar el registro! Int??ntalo de nuevo");
          Session::put('msgType', "err");
          return back();
        }
        else
        {
          admin::where('id',$id)->delete();

          $act = new adminLog;
          $act->admin = $adm->email;
          $act->action = "Deleted admin user. user id: ".$id;
          $act->save();

          Session::put('status', "Successful");
          Session::put('msgType', "suc");
          return back(); //
        }


      }
      catch(\Exception $e)
      {
        Session::put('status', "??Error al actualizar el registro! Int??ntalo de nuevo");
        Session::put('msgType', "err");
        return back();
      }


    }
    else
    {
      return redirect('/');
    }

  }


public function admAddnew(Request $req)
  {
    if(Session::has('adm') && !empty(Session::get('adm')))
    {
      try
      {
        $adm = Session::get('adm');
        // $usr = admin::find($id);
        if($adm->role < $req->input('role') )
        {
          Session::put('status', "??No puede realizar esta operaci??n! Int??ntalo de nuevo");
          Session::put('msgType', "err");
          return back();
        }

        if($adm->role == 1)
        {
          Session::put('status', "??No puede realizar esta operaci??n! Int??ntalo de nuevo");
          Session::put('msgType', "err");
          return back();
        }


        $pack = new admin;
        $pack->name = $req->input('Name');
        $pack->email = $req->input('email');
        $pack->pwd = Hash::make($req->input('pwd'));
        $pack->role = $req->input('role');
        $pack->author = $adm->id;
        $pack->status = 1;
        $pack->save();

        $act = new adminLog;
        $act->admin = $adm->email;
        $act->action = "Created admin user. username: ".$req->input('email');
        $act->save();

        Session::put('status', "Exitoso");
        Session::put('msgType', "suc");
        return back();
      }
      catch(\Exception $e)
      {
        Session::put('status', "??Error al actualizar el registro! Int??ntalo de nuevo");
        Session::put('msgType', "err");
        return back();
      }

    }
    else
    {
      return redirect('/');
    }

  }

  function editMsg($id){
    if(Session::has('adm') && !empty(Session::get('adm')))
    {
      $msg = msg::find($id);
      return json_encode($msg);
    }
    else
    {
      return redirect('/');
    }
  }

  public function admSendMsg(Request $req)
  {
    if(Session::has('adm') && !empty(Session::get('adm')))
    {
      $adm = Session::get('adm');
      $validator = Validator::make($req->all(), [
        'subject' => 'required|min:5|max:50|string',
        'msg' => 'required|string'
      ]);

      if($validator->fails())
      {
        return back()->With([
          'toast_msg' => 'Message not sent! Error '.$validator->errors()->first(),
          'toast_type' => 'err'
        ]);
      }

      try
      {
        if(empty($req->input('msg_state')))
        {
          $msg = new msg;
          $msg->message = $req->input('msg');
          $msg->subject = $req->input('subject');
          if(!empty($req->input('msg_users')))
          {
            $msg->users = $req->input('msg_users').';';
          }

          $msg->save();

          $act = new adminLog;
          $act->admin = $adm->email;
          $act->action = "Admin sent notification to users.";
          $act->save();
        }
        else
        {
          $msg = msg::find($req->input('msg_state'));
          $msg->message = $req->input('msg');
          $msg->subject = $req->input('subject');
          $msg->readers = '';
          if(!empty($req->input('msg_users')))
          {
            $msg->users = $req->input('msg_users');
          }
          else
          {
            $msg->users = NULL;
          }
          $msg->save();

          $act = new adminLog;
          $act->admin = $adm->email;
          $act->action = "Admin updated users notification.";
          $act->save();
        }
        return back()->With([
          'toast_msg' => 'Enviado correctamente.',
          'toast_type' => 'suc'
        ]);
      }
      catch(\Exception $e)
      {
        return back()->With([
          'toast_msg' => 'Error saving message! Try again '.$e->getMessage(),
          'toast_type' => 'err'
        ]);
      }

    }
    else
    {
      return redirect('/login');
    }

  }

  public function admChangePwd(Request $req)
  {
    if(Session::has('adm') && !empty(Session::get('adm')))
    {
      try
      {
        $adm = Session::get('adm');

        if($req->input('newpwd') != $req->input('cpwd'))
        {
          Session::put('status', "??La nueva contrase??a no coincide! Int??ntalo de nuevo");
          Session::put('msgType', "err");
          return back();
        }

        if(Hash::check($req->input('oldpwd'),  $adm->pwd))
        {
          $ad = admin::find($adm->id);
          $ad->pwd = Hash::make($req->input('newpwd'));
          $ad->save();

          $act = new adminLog;
          $act->admin = $adm->email;
          $act->action = "Admin cambi?? contrase??a.";
          $act->save();


                  $maildata = ['email' => $ad->email, 'username' => $ad->name];
                  Mail::send('mail.change_psw', ['md' => $maildata], function($msg) use ($maildata){
                  $msg->from(env('MAIL_USERNAME'), env('APP_NAME'));
                  $msg->to($maildata['email']);
                  $msg->subject('Cambio de Contrase??a');
              });

          Session::put('status', "Exitoso");
          Session::put('msgType', "suc");
          return back();
        }

      }
      catch(\Exception $e)
      {
        Session::put('status', "??Error al actualizar contrase??a! Int??ntalo de nuevo");
        Session::put('msgType', "err");
        return back();
      }

    }
    else
    {
      return redirect('/');
    }

  }


  public function admSearchByMonth(Request $req)
  {
    if(Session::has('adm') && !empty(Session::get('adm')))
    {
      $val = $req->input('search_val');
      $musers = User::where('created_at', 'like', '%'.$val.'%')->where('status', 1)->orderby('created_at', 'asc')->get();
      $mInv = investment::where('date_invested', 'like', '%'.$val.'%')->where('status', 'active')->orderby('date_invested', 'asc')->get();
      $mDep = deposits::where('created_at', 'like', '%'.$val.'%')->where('status', 1)->orderby('created_at', 'asc')->get();
      $mWd = Withdrawal::where('w_date', 'like', '%'.$val.'%')->orderby('w_date', 'asc')->get();


      $musersDate = $mInvDate = $mDepDate = $mWdDate = [];
      $musersVal = $mInvVal = $mDepVal = $mWdVal = [];
      $iCount = $dCount = $wCount = 0;
      $pt = "";
      $cnt = 0;
      $sum_cap = 0;

      foreach ($musers as $in) {
          if($pt != date('Y-m-d', strtotime($in->created_at)))
          {           $sum_cap = 0;
              $pt = date('Y-m-d', strtotime($in->created_at));
              $musersDate[$cnt] = date('d/m/y', strtotime($in->created_at));
              $m_count = withdrawal::where('created_at', 'like','%'.$pt.'%')->get();
              // foreach ($m_count as $n)
              // {
              //     $sum_cap += $n->amount;
              // }
              $musersVal[$cnt] = count($m_count);
              $sum_cap = 0;
              $cnt += 1;
          }

      }
      $pt = "";
      $cnt = 0;
      $sum_cap = 0;
      foreach ($mInv as $in) {
          if($pt != date('Y-m-d', strtotime($in->created_at)))
          {
              $pt = date('Y-m-d', strtotime($in->created_at));
              $mInvDate[$cnt] = date('d/m/y', strtotime($in->created_at));
              $m_count = withdrawal::where('created_at', 'like','%'.$pt.'%')->get();
              foreach ($m_count as $n)
              {
                  $sum_cap += $n->amount;
              }
              $mInvVal[$cnt] = $sum_cap;
              $sum_cap = 0;
              $cnt += 1;
          }
          $iCount += $in->capital;
      }
      $pt = "";
      $cnt = 0;
      $sum_cap = 0;
      foreach ($mDep as $in) {
          if($pt != date('Y-m-d', strtotime($in->created_at)))
          {
              $pt = date('Y-m-d', strtotime($in->created_at));
              $mDepDate[$cnt] = date('d/m/y', strtotime($in->created_at));
              $m_count = withdrawal::where('created_at', 'like','%'.$pt.'%')->get();
              foreach ($m_count as $n)
              {
                  $sum_cap += $n->amount;
              }
              $mDepVal[$cnt] = $sum_cap;
              $cnt += 1;
              $sum_cap = 0;
          }
          $dCount += $in->amount;
      }
      $pt = "";
      $cnt = 0;
      $sum_cap = 0;
      foreach ($mWd as $in) {
          if($pt != date('Y-m-d', strtotime($in->created_at)))
          {
              $pt = date('Y-m-d', strtotime($in->created_at));
              $mWdDate[$cnt] = date('d/m/y', strtotime($in->created_at));
              $m_count = withdrawal::where('created_at', 'like','%'.$pt.'%')->get();
              foreach ($m_count as $n)
              {
                  $sum_cap += $n->amount;
              }
              $mWdVal[$cnt] = $sum_cap;
              $cnt += 1;
              $sum_cap = 0;
          }
          $wCount += $in->amount;
      }
      $search_mt = date("M-Y", strtotime(trim($req->input('search_val'))));
      $rst = [$musersDate, $mInvDate, $mDepDate, $mWdDate, $musersVal, $mInvVal, $mDepVal, $mWdVal, count($musers), $iCount, $dCount, $wCount, $search_mt];
      return json_encode($rst);
    }
    else
    {
      return redirect('/');
    }

  }

  public function switch_pack($id)
  {
    if(Session::has('adm') && !empty(Session::get('adm')))
    {
        $adm = Session::get('adm');
        if($adm->role == 3 || $adm->role == 2)
        {
            $pack = packages::find($id);
            if(!empty($pack))
            {
              if($pack->status == 0)
              {
                  $pack->status = 1;
              }
              else
              {
                  $pack->status = 0;
              }
              $pack->save();
              return 's';
            }
        }
        else
        {
            return('El usuario no puede actualizar esta funci??n.');
        }

    }
    else
    {
      return redirect('/');
    }

  }

  function editMsgDel($id){
    if(Session::has('adm') && !empty(Session::get('adm')))
    {
      $msg = msg::where('id', $id)->delete();
      return json_encode('["rst" => "Satisfactorio"]');
    }
    else
    {
      return redirect('/');
    }
  }

  function site_settings(){
    if(Session::has('adm') && !empty(Session::get('adm')))
    {
      $currencies = currencies::orderby('id')->get();
      $data = $this->load_data();
      return view('admin.settings', [
        'settings' => $data[9], 'adm' => $data[0], 'logs' => $data[8], 'users' => $data[3], 'inv' => $data[1],
         'deposits' => $data[2], 'currencies' => $currencies
      ]);
    }
    else
    {
      return redirect('/');
    }
  }



  function adminViewProfileSettings()
  {
    if(Session::has('adm') && !empty(Session::get('adm')))
    {
      $data = $this->load_data();
      return view('admin.profile', [
        'settings' => $data[9], 'adm' => $data[0], 'logs' => $data[8], 'users' => $data[3], 'inv' => $data[1],
         'deposits' => $data[2],
      ]);
    }
    else
    {
      return redirect('/');
    }
  }

  function adminUpdatSettings(Request $req)
  {
    if(Session::has('adm') && !empty(Session::get('adm')))
    {
      ref_set::truncate();

      if($req->has('1'))
      {
        for($i = 1; $i <= $req['referal_levels']; $i++)
        {
          $ref_set = new ref_set();
          $ref_set->name = $i;
          $ref_set->val = $req[$i]/100;

          $ref_set->save();
        }
      }

      $val = validator::make($req->all(), [
        'siteTitle' => 'required|max:20',
        'siteDescr' => 'required|max:100',
        'hcolor' => 'required|min:7|max:7',
        'fcolor' => 'required|min:7|max:7',
        'cur_rd' => 'required|string',
        'cur_conv_rd' => 'required|numeric',
        'cur_us' => 'required|string',
        'cur_conv_us' => 'required|numeric',
      ]);
      if($val->fails())
      {
        $toast_msg = ['msg' => $val->errors()->first(), 'type' => 'err'];
        return json_encode($toast_msg);
      }
      try
      {
        $settings = site_settings::find(1);
        $settings->site_title = $req->input('siteTitle');
        $settings->site_descr = $req->input('siteDescr');
        $settings->header_color = $req->input('hcolor');
        $settings->footer_color = $req->input('fcolor');
        $settings->deposit = is_null($req->input('wallet')) ? 0 : $req->input('wallet') ;
        $settings->withdrawal = is_null($req->input('wd')) ? 0 : $req->input('wd');
        $settings->investment = is_null($req->input('inv')) ? 0 : $req->input('inv');
        $settings->user_reg = is_null($req->input('reg')) ? 0 : $req->input('reg');
        $settings->paypal_ID =  $req->input('paypal_ID');
        $settings->paypal_secret = $req->input('paypal_secret');
        $settings->paypal_mode = $req->input('paypal_mode');
        $settings->stripe_key = $req->input('stripe_key');
        $settings->stripe_secret = $req->input('stripe_secret');
        // $settings->stripe_mode = $req->input('stripe_mode');
        // $settings->currency_rd  = $settings->currency_rd;
        // $settings->currency_conversion = $req->input('cur_conv');

        if($req->hasFile('siteLogo'))
        {
          $val = validator::make($req->all(), [
            'siteLogo' => 'image|mimes:png|max:500',
          ]);
          if($val->fails())
          {
            $toast_msg = ['msg' => $val->errors()->first(), 'type' => 'err'];
            return json_encode($toast_msg);
          }
          $file = $req->file('siteLogo');
          $path = "logo.png"; //$req->file('u_file')->store('public/post_img');
          $file->move('img/', $path);
          $settings->site_logo = $path;
        }

        if ($req->input('cur_rd') != NULL){
            // dd($settings);
            // dd();

            $currency = currencies::find(1);
            $currency->currency_rd = $req->input('cur_rd');
            $currency->currency_conversion_rd = $req->input('cur_conv_rd');
            $currency->currency_us = $req->input('cur_us');
            $currency->currency_conversion_us = $req->input('cur_conv_us');
            $currency->save();
        }






        ///// Environment ////////////////////////////////////////////////////////////////


        $file = DotenvEditor::setKeys([
          //// paypal ///////////////////////////////////////////////////////////////
          [
              'key'     => 'PAYPAL_CLIENT_ID',
              'value'   => $req->input('paypal_ID')
          ],
          [
              'key'     => 'PAYPAL_SECRET',
              'value'   => $req->input('paypal_secret')
          ],
          [
              'key'     => 'PAYPAL_MODE',
              'value'   => $req->input('paypal_mode')
          ],
          [
              'key'     => 'SWITCH_PAYPAL',
              'value'   => is_null($req->input('switch_paypal')) ? 0 : $req->input('switch_paypal')
          ],

          //// Stripe ///////////////////////////////////////////////////////////////
          [
              'key'     => 'STRIPE_KEY',
              'value'   => $req->input('stripe_key')
          ],
          [
              'key'     => 'STRIPE_SECRET',
              'value'   => $req->input('stripe_secret')
          ],
          [
              'key'     => 'SWITCH_STRIPE',
              'value'   => is_null($req->input('switch_stripe')) ? 0 : $req->input('switch_stripe')
          ],

          //// Coinpayment BTC ///////////////////////////////////////////////////////////////
          [
              'key'     => 'COINPAYMENTS_DB_PREFIX',
              'value'   => 'cp_'
          ],
          [
              'key'     => 'COINPAYMENTS_MERCHANT_ID',
              'value'   => $req->input('cp_m_id')
          ],
          [
              'key'     => 'COINPAYMENTS_PUBLIC_KEY',
              'value'   => $req->input('cp_p_key')
          ],
          [
              'key'     => 'COINPAYMENTS_PRIVATE_KEY',
              'value'   => $req->input('cp_pr_key')
          ],
          [
              'key'     => 'COINPAYMENTS_IPN_SECRET',
              'value'   => $req->input('cp_ipn_secret')
          ],
          [
              'key'     => 'COINPAYMENTS_IPN_URL',
              'value'   => $req->input('cp_ipn_url')
          ],
          [
              'key'     => 'SWITCH_BTC',
              'value'   => is_null($req->input('switch_BTC')) ? 0 : $req->input('switch_BTC')
          ],
          [
              'key'     => 'SWITCH_ETH',
              'value'   => is_null($req->input('switch_ETH')) ? 0 : $req->input('switch_ETH')
          ],

          //// Bank deposit switch ////////////////////////////////////////////////////////////
          [
              'key'     => 'BANK_NAME',
              'value'   => $req->input('bank_name')
          ],
          [
              'key'     => 'ACCOUNT_NUMBER',
              'value'   => $req->input('act_no')
          ],
          [
              'key'     => 'ACCOUNT_NAME',
              'value'   => $req->input('act_name')
          ],
          [
              'key'     => 'BANK_DEPOSIT_EMAIL',
              'value'   => $req->input('dep_email')
          ],
          [
              'key'     => 'BANK_DEPOSIT_SWITCH',
              'value'   => is_null($req->input('switch_bank_deposit')) ? 0 : $req->input('switch_bank_deposit')
          ],

          //// Min deposit ////////////////////////////////////////////////////////////
          [
              'key'     => 'MIN_DEPOSIT',
              'value'   => $req->input('min_dep')
          ],
          [
              'key'     => 'MAX_DEPOSIT',
              'value'   => $req->input('max_dep')
          ],

          //// Referal bonus ////////////////////////////////////////////////////////////

          [
              'key'     => 'REF_TYPE',
              'value'   => $req->input('referal_type')
          ],
          [
              'key'     => 'REF_SYSTEM',
              'value'   => $req->input('referal_system')
          ],
          [
              'key'     => 'REF_LEVEL_CNT',
              'value'   => intval($req->input('referal_levels'))
          ],


          //// Mail Settings ////////////////////////////////////////////////////////////
          [
              'key'     => 'MAIL_DRIVER',
              'value'   => 'smtp'
          ],
          [
              'key'     => 'MAIL_HOST',
              'value'   => $req->input('m_host')
          ],
          [
              'key'     => 'MAIL_PORT',
              'value'   => $req->input('m_port')
          ],
          [
              'key'     => 'MAIL_SENDER',
              'value'   => $req->input('m_sender')
          ],
          [
              'key'     => 'MAIL_USERNAME',
              'value'   => $req->input('m_user')
          ],
          [
              'key'     => 'MAIL_PASSWORD',
              'value'   => $req->input('m_pwd')
          ],
                    [
              'key'     => 'SUPPORT_EMAIL',
              'value'   => $req->input('supEmail')
          ],
          [
              'key'     => 'MAIL_ENCRYPTION',
              'value'   => $req->input('m_enc')
          ],
          [
              'key'     => 'WD_LIMIT',
              'value'   => $req->input('wd_limit')
          ],
          [
              'key'     => 'WD_FEE',
              'value'   => $req->input('wd_fee')/100
          ],
          [
              'key'     => 'WITHDRAWAL',
              'value'   =>  is_null($req->input('wd')) ? 0 : $req->input('wd')
          ],
          [
              'key'     => 'MIN_WD',
              'value'   => $req->input('min_wd')
          ],
          [
              'key'     => 'CURRENCY',
              'value'   => $req->input('cur')
          ],
          [
              'key'     => 'CONVERSION',
              'value'   => $req->input('cur_conv')
          ],
          ///////// Paystack ////////////////////////////////////////////////////////////////////
          [
              'key'     => 'PAYSTACK_PUBLIC_KEY',
              'value'   => $req->input('paystack_pub_key')
          ],
          [
              'key'     => 'PAYSTACK_SECRET_KEY',
              'value'   => $req->input('paystack_secret')
          ],
          [
              'key'     => 'MERCHANT_EMAIL',
              'value'   => $req->input('paystack_email')
          ],
          [
              'key'     => 'PAYSTACK_SWITCH',
              'value'   => is_null($req->input('paystack_switch')) ? 0 : $req->input('paystack_switch')
          ],

          /////////////// PM ////////////////////////////////////////////////////////////////////
          [
              'key'     => 'PM_ACCOUNT',
              'value'   => $req->input('pm_id')
          ],
          [
              'key'     => 'PM_COMPANY',
              'value'   => $req->input('pm_name')
          ],
          [
              'key'     => 'PM_SWITCH',
              'value'   => is_null($req->input('pm_switch')) ? 0 : $req->input('pm_switch')
          ],

          /////////////// Payeer ////////////////////////////////////////////////////////////////////
          [
              'key'     => 'PAYEER_MID',
              'value'   => $req->input('payeer_id')
          ],
          [
              'key'     => 'PAYEER_KEY',
              'value'   => $req->input('payeer_key')
          ],
          [
              'key'     => 'PAYEER_SWITCH',
              'value'   => is_null($req->input('payeer_switch')) ? 0 : $req->input('payeer_switch')
          ],

          /////////////// Coinbase ////////////////////////////////////////////////////////////////////
          [
              'key'     => 'COINBASE_SWITCH',
              'value'   => is_null($req->input('coinbase_switch')) ? 0 : $req->input('coinbase_switch')
          ],
          [
              'key'     => 'COINBASE_API_KEY',
              'value'   => $req->input('coinbase_key')
          ],
          [
              'key'     => 'COINBASE_WEBHOOK_SECRETE',
              'value'   => $req->input('coinbase_seceret')
          ],

        ]);

        $file = DotenvEditor::save();
        $settings->save();
        $toast_msg = ['msg' => 'La configuraci??n se guard?? correctamente. <br> Recargar para ver los cambios', 'type' => 'suc'];
        return json_encode($toast_msg);

      }
      catch(\Exception $e)
      {
        $toast_msg = ['msg' => $e->getMessage(), 'type' => 'err'];
        return json_encode($toast_msg);
      }
    }
    else
    {
      return redirect('/');
    }
  }

  public function create_package()
  {
    if(Session::has('adm') && !empty(Session::get('adm')))
    {
        return view('admin.add_package');
    }
    else
    {
        return redirect('/');
    }
  }

  public function create_package_post(Request $req)
  {

    if(Session::has('adm') && !empty(Session::get('adm')))
    {
        $val = Validator::make($req->all(),[
            'package_name' => 'required|string|max:15',
            'min' => 'required|numeric',
            'max' => 'required|numeric',
            'mindol' => 'required|numeric',
            'maxdol' => 'required|numeric',
            'interest' => 'required|numeric',
            'period' => 'required|numeric',
            'interval' => 'required|numeric',
        ]);

        if($val->fails())
        {
            $toast_msg = ['msg' => $val->errors()->first(), 'type' => 'err'];
            return json_encode($toast_msg);
        }
        if((INT)$req->input('period') % (INT)$req->input('interval') != 0)
        {
            $toast_msg = ['msg' => "Period must be completely divisible by interval", 'type' => 'err'];
            return json_encode($toast_msg);
        }
        try
        {
            $interest_calc = ($req->input('interest')/100)/$req->input('period');
            $pack = new packages;
            $pack->package_name = $req->input('package_name');
            $pack->currency = $this->settings->currency;
            $pack->min = $req->input('min');
            $pack->max = $req->input('max');
            $pack->mindol = $req->input('mindol');
            $pack->maxdol = $req->input('maxdol');
            $pack->daily_interest = $interest_calc;
            $pack->withdrwal_fee = env('WD_FEE');
            $pack->period = $req->input('period');
            $pack->days_interval = $req->input('interval');
            $pack->ref_bonus = 0;
            $pack->status = 1;
            $pack->save();
        }
        catch(\Exception $e)
        {
            $toast_msg = ['msg' => $e->getMessage(), 'type' => 'err'];
            return json_encode($toast_msg);
        }

        $toast_msg = ['msg' => '??Paquete a??adido satisfactoriamente!', 'type' => 'suc'];
        return json_encode($toast_msg);
    }
    else
    {
        return redirect('/');
    }
  }

  public function adminDeletePack($id){
    if(Session::has('adm') && !empty(Session::get('adm')))
    {
      try{
        packages::where('id', $id)->update(array('deleted_at' => date('Y-m-d H:i:s')));
        return json_encode('["rst" => "suc"]');
      }
      catch (\Exception $ex){
        return json_encode('["rst" => "err"]');
      }
    }
    else
    {
        return redirect('/');
    }
  }

  public function view_tickets()
  {
    if(Session::has('adm') && !empty(Session::get('adm')))
    {
      $tickets = ticket::orderby('status', 'desc')->orderby('updated_at', 'desc')->paginate(30);
      return view('admin.ticket_view', ['tickets' => $tickets]);
    }
    else
    {
      return redirect('/login');
    }
  }

  public function delete_ticket($id)
  {
    if(Session::has('adm') && !empty(Session::get('adm')))
    {
      ticket::with('comments')->where('id', $id)->delete(10);
      return back()->with([
        'toast_msg' => '??Ticket se ha borrado satisfactoriamente!',
        'toast_type' => 'suc'
      ]);
    }
    else
    {
      return redirect('/login');
    }
  }

  public function open_ticket($id)
  {
    if(Session::has('adm') && !empty(Session::get('adm')))
    {

      $ticket_view = ticket::With('comments')->find($id);
      $comments = comments::where('ticket_id', $id)->orderby('id', 'asc')->get();
      $user = User::find($ticket_view->user_id);
      $ticket_view->state = 0;
      $ticket_view->save();
      comments::where('ticket_id', $id)->where('sender', 'user')->update(['state' => 0]);
      return view('admin.ticket_chat', ['ticket_view' => $ticket_view, 'user' => $user, 'comments' => $comments]);
    }
    else
    {
      return redirect('/login');
    }
  }
  public function ticket_chat($id)
  {
    if(Session::has('adm') && !empty(Session::get('adm')))
    {
      $comments = comments::with('user')->where('ticket_id', $id)->where('sender', 'user')->where('state', 1)->orderby('id', 'asc')->get();
      // $user = User::find($ticket_view->user_id);
      comments::where('ticket_id', $id)->where('sender', 'user')->update(['state' => 0]);
      return json_encode($comments);
    }
    else
    {
      return redirect('/login');
    }
  }
  public function close_ticket($id)
  {
    if(Session::has('adm') && !empty(Session::get('adm')))
    {
      try
      {
        ticket::where('id', $id)->update(['status' => 0]);
        return back()->with([
          'toast_msg' => '??Ticket cerrado con ??xito!',
          'toast_type' => 'suc'
        ]);
      }
      catch (\Exception $e)
      {
        return back()->with([
          'toast_msg' => '??Ocurri?? un error!',
          'toast_type' => 'err'
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
    if(Session::has('adm') && !empty(Session::get('adm')))
    {
      $close_check = ticket::find($req->input('ticket_id'));
      $usr = User::find($close_check->user_id);
      if(empty($close_check) || $close_check->status == 0)
      {
        return json_encode([
          'toast_msg' => 'Ticket closed',
          'toast_type' => 'err'
        ]);
      }
      $user = Session::get('adm');
      $validator = Validator::make($req->all(), [
        'ticket_id' => 'required|string',
        'msg' => 'required|string'
      ]);

      if($validator->fails())
      {
        return json_encode([
          'toast_msg' => 'Message not sent! Error'.$validator->errors()->first(),
          'toast_type' => 'err'
        ]);
      }

      try
      {
        $comment = new comments([
          'ticket_id' =>$req->input('ticket_id'),
          'sender' => 'support',
          'sender_id' => $user->id,
          'message' => $req->input('msg'),
        ]);
        $comment->save();

        $maildata = ['email' => $usr->email, 'username' => $usr->username];

        Mail::send('mail.admin_tickect_msg', ['md' => $maildata], function($msg) use ($maildata){
            $msg->from(env('MAIL_USERNAME'), env('APP_NAME'));
            $msg->to($maildata['email']);
            $msg->subject('Ticket Message');
        });

        return json_encode([
          'toast_msg' => '??Enviado! ',
          'toast_type' => 'suc',
          'comment_msg' => $req->input('msg'),
          'comment_time' => date('Y-m-d H:i:s'),
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

  public function kyc()
  {
    if(Session::has('adm') && !empty(Session::get('adm')))
    {
      return view('admin.kyc');
    }
    else
    {
      return redirect('/login');
    }
  }

  public function approve_kyc(Request $req)
  {
    if(Session::has('adm') && !empty(Session::get('adm')))
    {
      try
      {
        $kyc = kyc::find($req['id']);
        $kyc->status = 1;
        $kyc->save();
        return back()->with([
          'toast_msg' => '??Aprobaci??n exitosa!',
          'toast_type' => 'suc'
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
    else
    {
      return redirect('/login');
    }
  }

  public function reject_kyc(Request $req)
  {
    if(Session::has('adm') && !empty(Session::get('adm')))
    {
      try
      {
        $kyc = kyc::find($req['id']);
        $files = array(env('APP_URL').'/img/kyc/'.$kyc->front_card, env('APP_URL').'/img/kyc/'.$kyc->back_card, env('APP_URL').'/img/kyc/'.$kyc->address_proof);
        File::delete($files);
        $kyc->delete();
        return back()->with([
            'toast_msg' => 'Borrado satisfactoriamente!!',
            'toast_type' => 'suc'
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
    else
    {
      return redirect('/login');
    }
  }




  //Companies Controllers


  public function create_company()
  {
    if(Session::has('adm') && !empty(Session::get('adm')))
    {
        return view('admin.companies.form');
    }
    else
    {
        return redirect('/');
    }
  }

  public function create_company_post(Request $req)
  {
    if(Session::has('adm') && !empty(Session::get('adm')))
    {
        $val = Validator::make($req->all(),[
            'name_comp' => 'required|string|max:60',
            'rnc' => 'required|numeric',
            'email' => 'required|email|unique:companies',
            'o_capital' => 'required|numeric',
            'a_capital' => 'required|numeric',
            'sold_bonus' => 'required|numeric',
            'a_bonus' => 'required|numeric',
            'bonus_cost' => 'required|numeric',
            'currency' => 'required|string|max:3',
            'status' => 'numeric',
        ]);

        if($val->fails())
        {
            $toast_msg = ['msg' => $val->errors()->first(), 'type' => 'err'];
            return json_encode($toast_msg);
        }

        try
        {
            // $interest_calc = ($req->input('interest')/100)/$req->input('period');
            $comp = new companies;
            $comp ->name_comp = $req->input('name_comp');
            $comp ->rnc = $req->input('rnc');
            $comp ->email = $req->input('email');
            $comp ->o_capital = $req->input('o_capital');
            $comp ->a_capital = $req->input('a_capital');
            $comp ->bonus_open = $req->input('bonus_open');
            $comp ->sold_bonus = $req->input('sold_bonus');
            $comp ->a_bonus = $req->input('a_bonus');
            $comp ->bonus_cost = $req->input('bonus_cost');
            $comp ->currency = $req->input('currency');
            $comp ->status = 1;
            $comp ->save();
        }
        catch(\Exception $e)
        {
            $toast_msg = ['msg' => $e->getMessage(), 'type' => 'err'];
            return json_encode($toast_msg);
        }

        $toast_msg = ['msg' => '??Compa????a a??adida satisfactoriamente!', 'type' => 'suc'];
        return json_encode($toast_msg);
    }
    else
    {
        return redirect('/');
    }
  }

  public function switch_company($id)
  {
    if(Session::has('adm') && !empty(Session::get('adm')))
    {
        $adm = Session::get('adm');
        if($adm->role == 3 || $adm->role == 2)
        {
            $comp = companies::find($id);
            if(!empty($comp))
            {
              if($comp->status == 0)
              {
                  $comp->status = 1;
              }
              else
              {
                  $comp->status = 0;
              }
              $comp->save();
              return 's';
            }
        }
        else
        {
            return('No puedes realizar esta acci??n');
        }

    }
    else
    {
      return redirect('/');
    }

  }

  public function adminDeleteCompany($id){
    if(Session::has('adm') && !empty(Session::get('adm')))
    {
      try{
        companies::where('id', $id)->update(array('deleted_at' => date('Y-m-d H:i:s')));
        return json_encode('["rst" => "suc"]');
      }
      catch (\Exception $ex){
        return json_encode('["rst" => "err"]');
      }
    }
    else
    {
        return redirect('/');
    }
  }





    ///////////////////////////////////////////  Pack Company//////////////////////////////////////////////////

    public function editCompany(Request $req)
    {
        // dd($req);
        // die();
      if(Session::has('adm') && !empty(Session::get('adm')))
      {
        try
        {
          $comp = companies::find($req->input('id'));
          $comp ->name_comp = $req->input('name_comp');
          $comp ->rnc = $req->input('rnc');
          $comp ->email = $req->input('email');
          $comp ->o_capital = $req->input('o_capital');
          $comp ->a_capital = $req->input('a_capital');
          $comp ->bonus_open = $req->input('bonus_open');
          $comp ->sold_bonus = $req->input('sold_bonus');
          $comp ->a_bonus = $req->input('a_bonus');
          $comp ->bonus_cost = $req->input('bonus_cost');
          $comp ->currency = $req->input('currency');
          // $pack->withdrwal_fee = ($req->input('fee'))/100;
          $comp->save();

          $adm = Session::get('adm');
          $act = new adminLog;
          $act->admin = $adm->email;
          $act->action = "Edit?? compa????a. Compa????a id: ".$req->input('id');
          $act->save();

          return back()->with([
            'toast_msg' => '??Actualizaci??n Exitosa!',
            'toast_type' => 'suc'
          ]);
        }
        catch(\Exception $e)
        {
          return back()->with([
            'toast_msg' => '??Error guardando r??cord! ??Intente otra vez!',
            'toast_type' => 'err'
          ]);
        }

      }
      else
      {
        return redirect('/');
      }

    }



///////////////////////////////////////////  Register Customers//////////////////////////////////////////////////

public function RegCustomers(Request $req){

if(Session::has('adm') && !empty(Session::get('adm')))
{
    $this->token = mt_rand(0000, 9999).strtotime(date("Y-m-d H:i:s"));
    //Session::forget('ref');
    $this->email = $req['email'];
    $this->usr = $req['username'];

    $val = Validator::make($req->all(),[
        'fname' => 'required|string|max:50',
        'lname' => 'required|string|max:50',
        'email' => 'required|email|unique:users',
        'username' => 'required|string|max:12|unique:users',
        'password' => 'required|string'
    ]);

    if($val->fails())
    {
        $toast_msg = ['msg' => $val->errors()->first(), 'type' => 'err'];
        return json_encode($toast_msg);
    }

    try
    {

        $customers = new User;
        $customers ->firstname = $req->input('fname');
        $customers ->lastname= $req->input('lname');
        $customers ->email = $req->input('email');
        $customers ->username = $req->input('username');
        $customers ->pwd = Hash::make($req->input('password'));
        $customers ->status = 1;
        $customers ->reg_date = date('d-m-Y');
        $customers ->save();
    }
    catch(\Exception $e)
    {
        $toast_msg = ['msg' => $e->getMessage(), 'type' => 'err'];
        return json_encode($toast_msg);
    }

    $toast_msg = ['msg' => '??Cliente a??adido satisfactoriamente!', 'type' => 'suc'];
    return json_encode($toast_msg);
}
else
{
    return redirect('/');
        }
    }



  /////////////////////////////FIRST INVEST//////////////////////////////

    public function first_invest(Request $req)
  {

      if(!empty($req))
      {

        try
        {
            if($req->currency == 'RD$'){

          $capital = $req->input('capital');
          $pack = packages::find($req->input('p_id'));


          if($capital > $pack->max)
          {
            Session::put('status', 'El capital de entrada es mayor que la inversi??n m??xima.');
            Session::put('msgType', "err");
            return back();
          }

          if($capital < $pack->min)
          {
            Session::put('status', 'El capital de entrada es menos que la inversi??n m??nima.');
            Session::put('msgType', "err");
            return back();
          }

          if($capital >= $pack->min && $capital <= $pack->max)
          {
            $inv = new investment;
            $inv->capital = $capital;
            $inv->user_id = $req->uid;
            $inv->usn = $req->username;
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

            // $user->wallet -= $capital;
            // $user->save();

            $inv->save();

            Session::put('status', "Inversi??n aplicada al cliente exitosamente.");
            Session::put('msgType', "suc");
            return back() ;
          }

          }elseif($req->currency == 'US$'){

            $capital = $req->input('capital');
            $pack = packages::find($req->input('p_id'));


            if($capital > $pack->maxdol)
            {
              Session::put('status', 'El capital de entrada es mayor que la inversi??n m??xima.');
              Session::put('msgType', "err");
              return back();
            }

            if($capital < $pack->mindol)
            {
              Session::put('status', 'El capital de entrada es menos que la inversi??n m??nima.');
              Session::put('msgType', "err");
              return back();
            }

            if($capital >= $pack->mindol && $capital <= $pack->maxdol)
            {
            $inv = new investment;
            $inv->capital = $capital;
            $inv->user_id = $req->uid;
            $inv->usn = $req->username;
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

            // $user->wallet -= $capital;
            // $user->save();

            $inv->save();

            Session::put('status', "Inversi??n aplicada al cliente exitosamente.");
            Session::put('msgType', "suc");
            return back() ;
          }

            }
          else
          {
            Session::put('status', "??Monto invalido! Intenta nuevamente.");
            Session::put('msgType', "err");
            return back();
          }

        }
        catch(\Exception $e)
        {
            Session::put('status', "??Error creando inversi??n! Por favor intentar nuevamente.".$e->getMessage());
            Session::put('msgType', "err");
            return back();
        }

      }
      else
      {
        return redirect('/');
      }

  }



  /////////////////////////////BANK//////////////////////////////
  public function addbank(Request $req)
  {

       $user = $req->uid;

     	if(!empty($user))
     	{
      	try
      	{
          $bank = new banks;
        	$bank->user_id = $req->uid;
        	$bank->Account_name = $req->input('act_name');
        	$bank->Account_number = $req->input('actNo');
        	$bank->Bank_Name = $req->input('bname');

          $bank->save();



          Session::put('status', "Cuenta de banco guardada satisfactoriamente.");
          Session::put('msgType', "suc");

          return back();

      	}
      	catch(\Exception $e)
      	{
      		  Session::put('status', "Error guardando. Cuenta puede que exista. Intente nuevamente.");
            Session::put('msgType', "err");
          	return back();
      	}

     	}
     	else
     	{
     		return redirect('/');
     	}

  }



  /////////////////////////////DELETE BANK ACCOUNT//////////////////////////////


  public function deleteBankAccount($id)
  {

     	if(!empty($id))
     	{

      	try
      	{
              $bank = banks::where('id', $id)->delete();


              Session::put('status', "Cuenta de banco borrado satisfactoriamente.");
              Session::put('msgType', "suc");
              return back();

      	}
      	catch(\Exception $e)
      	{
          Session::put('status', 'Error guardando los datos. Intente nuevamente.');
          Session::put('msgType', "err");
          return back();
      	}

     	}
     	else
     	{
     		return redirect('/');
     	}

  }


  /////////////////////////////KYC//////////////////////////////


  public function upload_kyc_doc(Request $req)
  {

    // dd($req);
    // die();

    $user = $req->uid;
    try
    {
      if($req['cardtype'] == 'cedula' || $req['cardtype'] == 'licencia' )
      {
        if($req->hasFile('id_front') && $req->hasFile('id_back'))
        {
          // $file = $req->file('selfie');
          // $file->move(base_path().'/../img/kyc/', $user->username."_selfie.jpg");
          $file = $req->file('id_front');
          $file->move(base_path().'/public/img/kyc/', $req->username."_id_front.jpg");
          $file = $req->file('id_back');
          $file->move(base_path().'/public/img/kyc/', $req->username."_id_back.jpg");
          $file = $req->file('utility_doc');
          $file->move(base_path().'/public/img/kyc/', $req->username."_utility_doc.pdf");

          $kyc = new kyc;
          $kyc->user_id = $req->uid;
          $kyc->username = $req->username;
          $kyc->card_type = $req['cardtype'];
          // $kyc->selfie = $req->username."_selfie.jpg";
          $kyc->front_card = $req->username."_id_front.jpg";
          $kyc->back_card = $req->username."_id_back.jpg";
          $kyc->address_proof = $req->username."_utility_doc.pdf";

          $kyc->save();


          return redirect()->back()->with([
            'toast_msg' => 'Archivo subido satisfactoriamente.',
            'toast_type' => 'suc'
          ]);
        }
        else
        {
          return redirect()->back()->with([
            'toast_msg' => 'Archivo subido satisfactoriamente',
            'toast_type' => 'err'
          ]);
        }
      }
      elseif ($req['cardtype'] == 'pasaporte')
      {
        if($req->hasFile('pas_id_front'))
        {
          // $file = $req->file('selfie');
          // $file->move(base_path().'/../img/kyc/', $req->username."_selfie.jpg");
          $file = $req->file('pas_id_front');
          $file->move(base_path().'/public/img/kyc/', $req->username."_pas_id_front.jpg");
          $file = $req->file('utility_doc');
          $file->move(base_path().'/public/img/kyc/', $req->username."_utility_doc.pdf");

          $kyc = new kyc;
          $kyc->user_id = $req->uid;
          $kyc->username = $req->username;
          // $kyc->selfie = $req->username."_selfie.jpg";
          $kyc->card_type = $req['cardtype'];
          $kyc->front_card = $req->username."_pas_id_front.jpg";
          $kyc->address_proof = $req->username."_utility_doc.pdf";

          $kyc->save();


          return redirect()->back()->with([
            'toast_msg' => 'Archivo subido satisfactoriamente.',
            'toast_type' => 'suc'
          ]);
        }
        else
        {
          return redirect()->back()->with([
            'toast_msg' => 'Archivo subido satisfactoriamente.',
            'toast_type' => 'err'
          ]);
        }
      }
      else
      {
        return redirect()->back()->with([
            'toast_msg' => 'Por favor, seleccionar tipo de documento a subir.',
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


/////////////////////////////INJECT//////////////////////////////

  public function pauseInj($id)
  {
    if(Session::has('adm') && !empty(Session::get('adm')))
    {

      try
      {
        $usr = inyects::find($id);
        $usr->status = 'Pausada';
        $usr->save();

        $adm = Session::get('adm');
        $act = new adminLog;
        $act->admin = $adm->email;
        $act->action = "Inyecci??n de usuario en pausa. Investment id: ".$id;
        $act->save();

        Session::put('status', "Exitoso");
        Session::put('msgType', "suc");
        return back(); //

      }
      catch(\Exception $e)
      {
        Session::put('status', "??Error al actualizar el registro! Intente nuevamente.");
        Session::put('msgType', "err");
        return back();
      }


    }
    else
    {
      return redirect('/');
    }

  }

//   public function activateInj($id)
//   {
//     if(Session::has('adm') && !empty(Session::get('adm')))
//     {

//       try
//       {
//         $usr = inyects::find($id);
//         $usr->status = 'Activa';
//         $usr->save();

//         $adm = Session::get('adm');
//         $act = new adminLog;
//         $act->admin = $adm->email;
//         $act->action = "Inyecci??n de usuario activada. Investment id: ".$id;
//         $act->save();

//         Session::put('status', "Exitosa");
//         Session::put('msgType', "suc");
//         return back(); //

//       }
//       catch(\Exception $e)
//       {
//         Session::put('status', "??Error al actualizar el registro! Intente nuevamente.");
//         Session::put('msgType', "err");
//         return back();
//       }


//     }
//     else
//     {
//       return redirect('/');
//     }

//   }


public function activateInj(Request $request)
  {

    if(Session::has('adm') && !empty(Session::get('adm')))
    {
      try
      {
         $company = companies::find($request->company);

         if($company->status  ==  1 && $company->currency  ==  $request->currency){

             $capitalsubs = str_replace(',', '', $request->capital);
             $divide = str_replace(',', '', $request->capital) / $company->bonus_cost;
             $company->decrement('a_capital', $capitalsubs);
             $company->decrement('a_bonus', $divide);
             $company->increment('sold_bonus', $divide);
             $company->save();

             $usr = inyects::find($request->id);
             $usr->status = 'Activa';
             $usr->save();

             $adm = Session::get('adm');
             $act = new adminLog;
             $act->admin = $adm->email;
             $act->action = "Inyecci??n de usuario activada. Inyecci??n id: ".$request->id;
             $act->save();

             Session::put('status', "Exitoso");
             Session::put('msgType', "suc");
             return back(); //

             }else{

            Session::put('status', "??Error! Verificar si moneda de compa????a es igual a la de la Inyecci??n que desea activar.");
            Session::put('msgType', "err");
            return back();

          }

      }
      catch(\Exception $e)
      {
        Session::put('status', "??Error al actualizar el registro! Int??ntalo de nuevo");
        Session::put('msgType', "err");
        return back();
      }

    }
    else
    {
      return redirect('/');
    }

  }


  public function deleteInj($id)
  {
    if(Session::has('adm') && !empty(Session::get('adm')))
    {

      try
      {
        $inv = inyects::find($id);

        $inv_user = User::find($inv->user_id);
        $amt = $inv->capital;

        if($inv->w_amt == 0)
        {
            $inv_user->wallet += $amt;
            $inv_user->save();
        }

        inyects::where('id',$id)->update(array('deleted_at' => date('Y-m-d H:i:s')));

        $adm = Session::get('adm');
        $act = new adminLog;
        $act->admin = $adm->email;
        $act->action = "Inyecci??n de usuario eliminado. Inversi??n id: ".$id;
        $act->save();

        Session::put('status', "Exitoso");
        Session::put('msgType', "suc");
        return back();

      }
      catch(\Exception $e)
      {
        Session::put('status', "??Error actualizando registro! Intente nuevamente.");
        Session::put('msgType', "err");
        return back();
      }


    }
    else
    {
      return redirect('/');
    }

  }

}
