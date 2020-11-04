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
                                        <h4 class="card-title text-white">
                                            <i class="fas fa-plus"></i>{{ __('Añadir Cliente') }}
                                        </h4>
                                    </div>
                                </div>
                                <div class="card-body pb-0 table-responsive">
                                   <form id="add_new_pack" action="/admin/users/register'" method="post" >
                                       @csrf()
                                        <div class="form-group row">
                                            <div class="col-sm-6">
                                                <label for="package_name">{{ __('Nombres') }}</label>
                                                <input id="fname" type="text" class="regTxtBox" name="fname" value="" required autocomplete="fname" autofocus placeholder="Juan Antonio">
                                            </div>

                                            <div class="col-sm-6">
                                                <label for="lname">{{ __('Apellidos') }}</label>
                                                <input id="package_name" type="text" class="regTxtBox" name="lname" value="" required autocomplete="lname" autofocus placeholder="Pérez Moronta">
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <div class="col-sm-6">
                                                <label for="email">{{ __('Correo Electrónico') }}</label>
                                                <input id="email" type="email" class="regTxtBox" name="email" type="email" value="" required autocomplete="email" autofocus placeholder="Correo Electrónico">
                                            </div>

                                            <div class="col-sm-6">
                                                <label for="package_name">{{ __('Nombre de usuario') }}</label>
                                                <input id="username" type="text" class="regTxtBox" name="username" value="" required autocomplete="username" autofocus placeholder="juanp">
                                            </div>
                                        </div>


                                        <div class="form-group row">
                                        <div class="col-sm-6">
                                                        <label for="password" class=" col-form-label text-md-right">{{ __('Contraseña') }}</label>
                                                        <input id="password" type="password" class="regTxtBox" name="password" required autocomplete="new-password" placeholder="Password">

                                                    </div>

                                            <div class="col-sm-6">
                                            <label for="password" class=" col-form-label text-md-right">{{ __('Confirmar contraseña') }}</label>
                                                        <input id="password-confirm" type="password" class="form-control regTxtBox" name="password_confirmation" required autocomplete="new-password" placeholder="Confirm password" >
                                                    </div>

                                        </div>



                                   </form>
                                   <div class="form-group row">
                                        <div class="col-sm-12 text-center">
                                            <br><br>
                                            <button class="btn btn-info btn_form" onclick="load_post_ajax('/admin/users/register', 'add_new_pack', 'add_pack')"><i class="fa fa-plus"></i> {{ __('Añadir') }} </button>
                                        </div>
                                    </div>

                                   <br><br>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

@endSection
