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
                                            <i class="fas fa-plus"></i>{{ __('Añadir Compañía') }}
                                        </h4>
                                    </div>
                                </div>
                                <div class="card-body pb-0 table-responsive">
                                   <form id="add_new_pack" action="/admin/create/package" method="post" >
                                       @csrf()
                                        <div class="form-group row">
                                            <div class="col-sm-12">
                                                <label for="package_name">{{ __('Nombre Compañía') }}</label>
                                                <input id="package_name" type="text" class="regTxtBox" name="package_name" value="" required autocomplete="package_name" autofocus placeholder="Nombre de compañía">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-sm-6">
                                                <label for="min">{{ __('RNC') }}</label>
                                                <input id="min" type="number" class="regTxtBox" name="min" value="" required autocomplete="min" autofocus placeholder="RNC">
                                            </div>
                                            <div class="col-sm-6">
                                                <label for="max" class="">{{ __('Correo Electrónico') }}</label>
                                                <input id="email" type="email" class="regTxtBox" name="email" value="" required autocomplete="email" autofocus placeholder="Correo electrónico">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-sm-6">
                                                <label for="min">{{ __('Capital Apertura') }}</label>
                                                <input id="min" type="number" class="regTxtBox" name="min" value="" required autocomplete="min" autofocus placeholder="Capital de Apertura">
                                            </div>

                                                <div class="col-sm-6">
                                                    <label for="min">{{ __('Capital Disponible') }}</label>
                                                    <input id="min" type="number" class="regTxtBox" name="min" value="" required autocomplete="min" autofocus placeholder="Capital de Apertura">
                                                </div>
                                        </div>

                                        <div class="form-group row">
                                            <div class="col-sm-6">
                                                <label for="min">{{ __('Bonos Aperturados') }}</label>
                                                <input id="min" type="number" class="regTxtBox" name="min" value="" required autocomplete="min" autofocus placeholder="Bonos Aperturados">
                                            </div>

                                                <div class="col-sm-6">
                                                    <label for="min">{{ __('Bonos Vendidos') }}</label>
                                                    <input id="min" type="number" class="regTxtBox" name="min" value="" required autocomplete="min" autofocus placeholder="Bonos Vendidos">
                                                </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-sm-6">
                                                <label for="min">{{ __('Bonos Disponible') }}</label>
                                                <input id="min" type="number" class="regTxtBox" name="min" value="" required autocomplete="min" autofocus placeholder="Bonos Disponible">
                                            </div>

                                                <div class="col-sm-6">
                                                    <label for="min">{{ __('Bonos Costo') }}</label>
                                                    <input id="min" type="number" class="regTxtBox" name="min" value="" required autocomplete="min" autofocus placeholder="Bonos Costo">
                                                </div>
                                        </div>

                                        <div class="form-group row">
                                             <div class="col-sm-6">
                                                <label for="interval" class="">{{ __('Moneda') }}</label>
                                                <input id="currency" type="currency" class="regTxtBox" name="currency" value="" required autocomplete="currency" autofocus placeholder="Moneda de la compañía">
                                            </div>
                                        </div>
                                   </form>
                                   <div class="form-group row">
                                        <div class="col-sm-12 text-center">
                                            <br><br>
                                            <button class="btn btn-info btn_form" onclick="load_post_ajax('/admin/create/package', 'add_new_pack', 'add_pack')"><i class="fa fa-plus"></i> {{ __('Añadir') }} </button>
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
