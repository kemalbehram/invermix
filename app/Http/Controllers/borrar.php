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

            Session::put('status', "Inversión aplicada al cliente exitosamente.");
            Session::put('msgType', "suc");
            return back() ;
          }
            $maildata = ['email' => $user->email, 'username' => $user->username];
            Mail::send('mail.user_inv_notification', ['md' => $maildata], function($msg) use ($maildata){
                $msg->from(env('MAIL_USERNAME'), env('APP_NAME'));
                $msg->to($maildata['email']);
                $msg->subject('Inversión InverBanking');
            });

            $maildata = ['email' => $user->email, 'username' => $user->username];
            Mail::send('mail.admin_inv_notification', ['md' => $maildata], function($msg) use ($maildata){
                $msg->from(env('MAIL_USERNAME'), env('APP_NAME'));
                $msg->to(env('SUPPORT_EMAIL'));
                $msg->subject('Inversión de Cliente');
            });


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

            Session::put('status', "Inversión aplicada al cliente exitosamente.");
            Session::put('msgType', "suc");
            return back() ;
          }

          $maildata = ['email' => $user->email, 'username' => $user->username];
          Mail::send('mail.user_inv_notification', ['md' => $maildata], function($msg) use ($maildata){
              $msg->from(env('MAIL_USERNAME'), env('APP_NAME'));
              $msg->to($maildata['email']);
              $msg->subject('Inversión InverBanking');
          });

          $maildata = ['email' => $user->email, 'username' => $user->username];

          Mail::send('mail.admin_inv_notification', ['md' => $maildata], function($msg) use ($maildata){
              $msg->from(env('MAIL_USERNAME'), env('APP_NAME'));
              $msg->to(env('SUPPORT_EMAIL'));
              $msg->subject('Inversión de Cliente');
          });

            }
          else
          {
            Session::put('status', "¡Monto invalido! Intenta nuevamente.");
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
