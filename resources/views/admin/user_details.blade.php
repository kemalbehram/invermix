@php($user_data = user_details_data($id))
@php($user = $user_data['user'])
@php($dt = $user_data['dt'])
@extends('admin.atlantis.layout')
@Section('content')

        <div class="main-panel">
            <div class="content">
                @include('admin.atlantis.main_bar')
                <div class="page-inner mt--5">
                    @include('admin.atlantis.overview')
                    <div id="prnt"></div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header card_header_bg_blue" >
                                    <div class="card-head-row card-tools-still-right">
                                        <h4 class="card-title text-white"> {{ __('Detalles Cliente') }} </h4>
                                        <div class="card-tools">
                                            <a href="/admin/block/user/{{$user->id}}" >
                                                <span class=""><i class="fa fa-ban btn btn-warning" ></i></span>
                                            </a>
                                            <a href="/admin/activate/user/{{$user->id}}" >
                                                <span><i class="fa fa-check btn btn-success"></i></span>
                                            </a>
                                            @if($adm->role != 1)
                                                <a href="/admin/delete/user/{{$user->id}}" >
                                                    <span class=""><i class="fa fa-times btn btn-danger"></i></span>
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row pad_top_20">
                                        <div class="col-lg-6">
                                            <div class="form-group" align="center">
                                                @if($user->img == "")
                                                    <img class="img-responsive" src="/img/any.png" width="200px" height="200px">
                                                @else
                                                    <img class="img-responsive" src="/img/profile/{{ $user->img }}" width="200px" height="200px">
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="card full-height">
                                                <div class="card-body">
                                                    <div class="card-title">
                                                        <h2 class="text-success"> {{ __('Resumen de la cuenta') }} </h2>
                                                    </div>
                                                    <hr>
                                                    <div class="row py-3 @if($adm->role < 2) {{blur_cnt}}@endif position_relative">
                                                        <div class="col-md-6 d-flex flex-column justify-content-around">
                                                            <!-- <div class="border_btm_1">
                                                                <h4 class="fw-bold  text-info op-8"> {{ __('Wallet Balance') }} </h4>
                                                                <h3 class="fw-bold">{{$settings->currency}} {{ round($user->wallet,2) }}</h3>
                                                                <div class="colhd margin_top_n10 font_10">&emsp;</div>
                                                            </div> -->
                                                          <div class="clearfix"><br></div>
                                                            <!-- <div>
                                                                <h4 class="fw-bold text-info op-8"> {{ __('Referral Bonus') }} </h4>
                                                                <h3 class="fw-bold">{{$settings->currency}} {{ round ($user->ref_bal, 2)}}</h3>
                                                                <div class="colhd margin_top_n10 font_10 ">&emsp;</div>
                                                            </div> -->
                                                        </div>

                                                        <div class="col-md-6">
                                                            <div class="border_btm_1" >
                                                                <h4 class="fw-bold text-info op-8"> {{ __('Fecha de creacion') }} </h4>
                                                                {{$dt->format('Y-m-d')}}
                                                                <div class="colhd margin_top_n10 font_10">&emsp;</div>
                                                                <br>
                                                            </div>
                                                            <div class="clearfix"><br></div>
                                                            <div>
                                                                <h4 class="fw-bold text-info op-8"> {{ __('Status') }} </h4>
                                                                <span class="fa fa-circle" style="color: green;"></span>
                                                                <span class="">
                                                                @if($user->status == 1 || $user->status == 'Active')
                                                                    Activo
                                                                @elseif($user->status == 2 || $user->status == 'Blocked')
                                                                    Bloqueado
                                                                @elseif($user->status == 0 || $user->status == 'Inactive')
                                                                    Inactivo
                                                                @endif
                                                                </span>

                                                                <div class="colhd margin_top_n10 font_10" >&emsp;</div>
                                                                <br>
                                                            </div>

                                                        </div>

                                                    </div>
                                                </div>
                                            </div>

                                        </div>

                                    </div>

                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label> {{ __('Nombres') }} </label>
                                                <input id="adr" type="text" value="{{ucfirst($user->firstname)}}" class="form-control" name="fname" readonly>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label> {{ __('Apellidos') }} </label>
                                                <input id="adr" type="text" value="{{ucfirst($user->lastname)}}" class="form-control" name="lname" readonly>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label> {{ __('Correo Electrónico') }} </label>
                                                <div class="input-group">
                                                    <input id="email" type="email" value="{{$user->email}}" class="form-control" name="email">
                                                </div>

                                            </div>
                                        </div>

                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label> {{ __('Usuario') }} </label>
                                                <div class="input-group">
                                                    <input id="usn" type="text" value="{{$user->username}}" class="form-control" name="usn" readonly>
                                                </div>

                                            </div>
                                        </div>

                                    </div>

                                    <form class="" method="post" action="/admin/update/user/profile">

                                        <div class="row">
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <input type="hidden" name="_token" value="{{csrf_token()}}">
                                                    <input type="hidden" name="uid" value="{{$user->id}}">
                                                    <label> {{ __('País') }} </label>
                                                    <select id="country" class="form-control" name="country" >
                                                        <?php
                                                            $country = App\country::orderby('name', 'asc')->get();
                                                        ?>
                                                        @php($phn_code = '')
                                                        @foreach($country as $c)
                                                            @if($c->id == $user->country)
                                                                @php($cs = $c->id)
                                                                @php($phn_code = $c->phonecode)
                                                                {{'selected'}}
                                                                <option selected  value="{{$c->id}}">{{$c->name}}</option>

                                                            @else
                                                                <option value="{{$c->id}}">{{$c->name}}</option>
                                                            @endif
                                                        @endforeach
                                                        @if(!isset($cs))
                                                                <option selected disabled>Seleccionar País</option>
                                                        @endif
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                 <div class="form-group">
                                                    <label> {{ __('Estado/Provincia') }} </label>
                                                    <select  id="states" class="form-control" name="state" required>
                                                        @if(isset($cs))
                                                            <?php
                                                                $st = App\states::where('id', $user->state)->get();
                                                            ?>
                                                            @if(count($st) > 0)
                                                                <option selected value="{{$st[0]->id}}">{{$st[0]->name}}</option>
                                                            @else
                                                                <option selected disabled>Seleccionar provincia</option>
                                                            @endif

                                                        @else
                                                           <option selected disabled>Seleccionar provincia</option>
                                                        @endif
                                                    </select>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label> {{ __('Dirección') }} </label>
                                                    <input id="adr" type="text" class="form-control" value="{{$user->address}}" name="adr" required>
                                                </div>
                                            </div>

                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label> {{ __('Teléfono') }} </label>
                                                    <div class="input-group">
                                                        <div class="input-group-prepend">
                                                            <span id="countryCode" class="input-group-text">
                                                                @if(isset($phn_code))
                                                                    {{'+'.$phn_code}}
                                                                @else
                                                                    +1
                                                                @endif
                                                            </span>
                                                        </div>
                                                        <input id="cCode" type="hidden" class="form-control" name="cCode" required>
                                                        <input id="phone" type="text" class="form-control" value="{{str_replace('+'.$phn_code,'',$user->phone)}}" name="phone" required>
                                                    </div>

                                                </div>
                                            </div>

                                        </div>
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="form-group">
                                                       <button class="collb btn btn-info"> {{ __('Guardar') }} </button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>

                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <div class="card-title"> {{ __('Restablecer Contraseña Cliente') }} </div>
                                </div>
                                <div class="card-body pb-0">
                                    <form class="" method="post" action="/admin/change/user/pwd">
                                        <input type="hidden" name="_token" value="{{csrf_token()}}">
                                        <input type="hidden" name="uid" value="{{ $user->id }}">
                                        <div class="form-group">
                                            <label> {{ __('Nueva Contraseña') }} </label>
                                            <input type="password" class="form-control" name="newpwd" placeholder="Nueva Contraseña" required>
                                        </div>
                                        <div class="form-group">
                                            <label> {{ __('Confirmar Contraseña') }} </label>
                                            <input type="password" class="form-control" name="cpwd" placeholder="Confirmar Contraseña" required>
                                        </div>
                                            <div class="form-group" align="left">
                                               <button class="collb btn btn-info"> {{ __('Guardar') }} </button>
                                            </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <div class="card-title"> {{ __('Primera Inversión Cliente') }} </div>
                                </div>
                                <?php
                                                 $myinv = App\investment::where('user_id', $user->id)->get();
                                            ?>
                                @if(count($myinv) == 0)
                                <div class="card-body pb-0">
                                    @include('user.inc.packages')
                                </div>
                                @else
                                <div class="card-body pb-0">
                                    <h3><em>Ya el cliente tiene inversiones</em></h3>
                                </div>

                                @endif
                            </div>
                        </div>
                    </div>
                    @include('admin.inc.first_inv')
                     @include('user.inc.withdrawal')


                    <!-- KYC -->
                    <div class="row">
                                <form id="id_verify" class="" method="post" action="{{ route('kyc_upload_admin') }}" enctype="multipart/form-data">
                                  <div class="row form-group">
                                    <div class="col-sm-6">
                                      <div class="card">
                                        <div class="card-header">
                                            <div class="card-title">{{ __('Conoce tu Cliente') }}</div>
                                        </div>
                                        <div class="card-body">

                                          <div class="row">
                                            <div class="col-lg-12">
                                                <div id="selfie" class="">
                                                  <div class="form-group" align="center">

                                                  </div>
                                                </div>

                                                <div class="form-group">
                                                  <h3>{{ __('Documento de Identidad') }}</h3>
                                                  <p>
                                                    {{ __('Documentos válidos son: Cédula, Pasaporte y Licencia de Conducir') }}
                                                  </p>
                                                </div>
                                                <div class="form-group mt-4">
                                                  <label>Tipo de Identificación</label>
                                                  <select id="card_select" name="cardtype" class="form-control" required="required">
                                                    <option selected disabled >Seleccionar</option>
                                                    <option value="idcard_op">Cédula</option>
                                                    <option value="passport_op">Pasaporte</option>
                                                    <option value="driver_op">Licencia de Conducir</option>
                                                  </select>
                                                </div>
                                                <hr>
                                                <div id="card_cont" class="cont_display_none">
                                                  <div class="form-group mt-3">
                                                    <label>Card Front</label>
                                                    <br>
                                                    <img src="/img/id_temp_front.png" class="img_card_temp" width="100%">
                                                    <input type="file" class="form-control upload_inp mt-2" name="id_front" >
                                                    <input type="hidden" name="uid" value="{{$user->id}}">
                                                    <input type="hidden" name="username" value="{{$user->username}}">
                                                  </div>

                                                  <hr>
                                                  <div class="form-group mt-3">
                                                    <label>Card Back</label>
                                                    <br>
                                                    <img src="/img/id_tem_bac.png" class="img_card_temp" width="100%">
                                                    <input type="file" class="form-control mt-2" name="id_back" >
                                                  </div>
                                                </div>

                                                <div id="pass_cont" class="cont_display_none">
                                                  <div class="form-group">
                                                    <label>Passport Front</label>
                                                    <br>
                                                    <img src="/img/id_temp_front.png" class="img_card_temp" width="100%">
                                                    <input type="file" class="form-control upload_inp mt-2" name="pas_id_front" >
                                                  </div>
                                                </div>

                                            </div>
                                          </div>

                                        </div>
                                      </div>
                                    </div>

                                    <div class="col-sm-6">
                                      <div class="card">
                                        <div class="card-header">
                                          <div class="card-title">{{ __('Formulario Conoce tu Cliente') }}</div>
                                        </div>
                                        <div class="card-body">
                                          <div class="row">
                                            <div class="row">
                                              <div class="col-lg-12">

                                                  <div class="form-group">
                                                    <h3></h3>
                                                    <p>
                                                      {{ __('Para clientes cuya inversión sea superior a RD$500,000 o equivalente en dolares.') }}
                                                    </p>
                                                    <input type="file" class="form-control" name="utility_doc" required >
                                                  </div>

                                              </div>
                                            </div>
                                          </div>

                                        </div>
                                      </div>

                                    </div>

                                    <div class="col-sm-12 mt-5">
                                      <div class="form-group">
                                        <button class="collcc btn btn-info float-right">{{ __('Guardar') }}</button>
                                      </div>
                                    </div>

                                  </div>
                                </form>

                            </div>
                            <!-- End of KYC -->

                            <div class="row">
                        <div class="col-md-12">
                        <div class="card">
                                    <div class="card-header">
                                        <div class="card-title">{{ __('Cuenta de Banco') }}</div>
                                    </div>
                                    <div class="card-body">
                                        <form class="" method="post" action="{{route('add_bank')}}">
                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <div class="form-group">
                                                        <label>{{ __('Nombre Banco') }}</label>
                                                        <input type="hidden" name="_token" value="{{csrf_token()}}">
                                                        <input type="hidden" name="uid" value="{{$user->id}}">
                                                        <input type="text" class="form-control" name="bname" required placeholder="Nombre Banco">
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="form-group">
                                                        <label>{{ __('Número de Cuenta') }}</label>
                                                        <input type="text" class="form-control" name="actNo"  required placeholder="Número de Cuenta">
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <div class="form-group">
                                                        <label>{{ __('Nombre Cuenta') }}</label>
                                                        <input type="text" class="form-control" name="act_name" required placeholder="Nombre Cuenta">
                                                    </div>
                                                </div>

                                            </div>

                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <div class="form-group">
                                                        <button class="collcc btn btn-info">{{ __('Guardar') }}</button>
                                                    </div>
                                                </div>

                                            </div>
                                        </form>
                                    </div>
                                  </div>

                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <div class="card-title"> {{ __('Cuentas de Bancos del Cliente') }} </div>
                                </div>
                                <div class="card-body pb-0 table-responsive">
                                    <table  id="" class="display table table-stripped table-hover">
                                        <thead>
                                            <tr>
                                                <th> {{ __('Nombre Banco') }} </th>
                                                <th> {{ __('Número de Cuenta') }} </th>
                                                <th> {{ __('Nombre de Cuenta') }} </th>
                                                <th data-field="company" >Acciones</th>

                                            </tr>
                                        </thead>
                                        <tfoot>
                                            <tr>
                                                <th> {{ __('Nombre Banco') }} </th>
                                                <th> {{ __('Número de Cuenta') }} </th>
                                                <th> {{ __('Nombre de Cuenta') }} </th>
                                                <th data-field="company" >Acciones</th>

                                            </tr>
                                        </tfoot>
                                        <tbody>
                                            <?php
                                                 $mybanks = App\banks::where('user_id', $user->id)->get();
                                            ?>
                                            @if(count($mybanks) > 0)
                                                @foreach($mybanks as $bank)
                                                    <tr>
                                                        <td>{{$bank->Bank_Name}}</td>
                                                        <td>{{$bank->Account_name}}</td>
                                                        <td>{{$bank->Account_number}}</td>
                                                        <td>
                                                                <a class="btn btn-danger" href="/view/userdetails/remove/bankaccount/{{$bank->id}}" title="Remove">
                                                                    <i class="fa fa-times"></i>
                                                                </a>
                                                        </td>

                                                    </tr>
                                                @endforeach
                                            @endif
                                        </tbody>
                                    </table>
                                    <br><br>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <div class="card-title"> {{ __('Inversiones Usuario') }} </div>
                                </div>
                                <div class="card-body pb-0">
                                    @include('admin.temp.user_inv')
                                    <br><br>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <div class="card-title"> {{ __('Historial de Retiros') }} </div>
                                </div>
                                <div class="card-body pb-0 table-responsive">
                                    @include('admin.temp.user_wd_history')
                                    <br><br>
                                </div>
                            </div>
                        </div>
                        <!-- <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <div class="card-title"> {{ __('Referrals') }} </div>
                                </div>
                                <div class="card-body pb-0 table-responsive">
                                    @include('admin.temp.user_ref')
                                    <br><br>
                                </div>
                            </div>
                        </div> -->
                    </div>
                </div>
            </div>
@endSection
