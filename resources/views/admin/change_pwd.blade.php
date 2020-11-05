@extends('admin.atlantis.layout')
@Section('content')
        <div class="main-panel">
            <div class="content">
                @include('admin.atlantis.main_bar')
                <div class="page-inner mt--5">
                    @include('admin.atlantis.overview')
                    <div id="prnt"></div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <div class="card-title"><i class="fa fa-key"></i> {{ __('Cambiar Contraseña') }} </div>
                                </div>
                                <div class="card-body pb-0">
                                    <form action="/admin/change/pwd" method="post">
                                        <input id="token" type="hidden" class="form-control" name="_token" value="{{csrf_token()}}">

                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text " ><i class="fa fa-key"></i></span>
                                            </div>
                                              <input type="Password" class="form-control" name="oldpwd" placeholder="Antigua contraseña" required>
                                          </div>
                                          <br>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text "><i class="fa fa-key"></i></span>
                                            </div>
                                              <input id="" type="password" class="form-control" name="newpwd" placeholder="Nueva contraseña" required>
                                        </div>
                                          <br>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                              <span class="input-group-text "><i class="fa fa-key"></i></span>
                                            </div>
                                              <input id="" type="password" class="form-control" name="cpwd" placeholder="Confirmar contraseña" required>
                                        </div>
                                          <br>

                                          <div class="form-group">
                                            <br>
                                              <button class="collb btn btn-info"> {{ __('Actualizar Contraseña') }} </button>
                                              <br>
                                          </div>

                                    </form>
                                    <br><br>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

@endSection
