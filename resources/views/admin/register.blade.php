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
                                            <i class="fas fa-plus"></i>{{ __(' Registrar Nuevo Cliente') }}
                                        </h4>
                                    </div>
                                </div>
                                <div class="card-body pb-0 table-responsive">
                                    <form method="POST" action="{{ route('register') }}">
                                        <input id="csrf" type="hidden"  name="_token" value="{{ csrf_token() }}" >
                                        <div class="form-group row">
                                            <div class="col-sm-6">
                                                <label for="Fname" class=" col-form-label text-md-right">{{ __('Nombres') }}</label>
                                                <input id="Fname" type="text" class="form-control @error('Fname') is-invalid @enderror regTxtBox" name="Fname" value="{{ old('Fname') }}" required autocomplete="Fname" autofocus placeholder="Juan">

                                                @error('Fname')
                                                    <span class="invalid-feedback" role="alert alert-danger">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                             <div class="col-sm-6">
                                                <label for="Lname" class=" col-form-label text-md-right">{{ __('Apellidos') }}</label>
                                                <input id="Lname" type="text" class="form-control @error('Lname') is-invalid @enderror regTxtBox" name="Lname" value="{{ old('Lname') }}" required autocomplete="Lname" autofocus placeholder="Pérez">

                                                @error('Lname')
                                                    <span class="invalid-feedback" role="alert alert-danger">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="form-group row">

                                            <div class="col-sm-12">
                                                <label for="email" class=" col-form-label text-md-right">{{ __('E-Mail Address') }}</label>
                                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror regTxtBox" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="juanperez@outlook.com">

                                                @error('email')
                                                    <span class="invalid-feedback" role="alert alert-danger">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="form-group row">

                                            <div class="col-sm-12">
                                                <label for="username" class=" col-form-label text-md-right">{{ __('Nombre de usuario') }}</label>
                                                <input id="username" type="username" class="form-control @error('username') is-invalid @enderror regTxtBox" name="username" value="{{ old('username') }}" required autocomplete="username" placeholder="Ej.: Juanp">

                                                @error('username')
                                                    <span class="invalid-feedback" role="alert alert-danger" >
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <div class="col-sm-6">
                                                <label for="password" class=" col-form-label text-md-right">{{ __('Password') }}</label>
                                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror regTxtBox" name="password" required autocomplete="new-password" placeholder="Contraseña">

                                                @error('password')
                                                    <span class="invalid-feedback" role="alert alert-danger" >
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                            <div class="col-sm-6">
                                                <label for="password-confirm" class=" col-form-label text-md-right">{{ __('Confirm Password') }}</label>
                                                <input id="password-confirm" type="password" class="form-control regTxtBox" name="password_confirmation" required autocomplete="new-password" placeholder="Confirmar contraseña" >
                                            </div>

                                        </div>

                                        <?php
                                                    $usn = App\User::where('username', Session::get('ref'))->get();
                                                ?>

                                                <div class="row">
                                                    <div class="">
                                                        <input id="ref" type="hidden" class="form-control" name="ref" value="@if(count($usn) > 0){{Session::get('ref')}}@endif" >
                                                    </div>
                                                </div>

                                                <div class="">
                                                    <div class="" align="center">
                                                        <br><br>

                                                            <button type="submit" class="collc btn btn-primary">
                                                                {{ __('Registrar') }}
                                                            </button>
                                                        <br><br>
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
