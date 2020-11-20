@include('user.inc.fetch')
@extends('layouts.atlantis.layout')
@Section('content')
        <div class="main-panel">
            <div class="content">
                @php($breadcome = 'Withdrawal')
                @include('user.atlantis.main_bar')
                <div class="page-inner mt--5">
                    @include('user.atlantis.overview')
                    <div id="prnt"></div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <div class="card-head-row">
                                        <div class="card-title">{{ __('Historial de Retiro de Inversiones') }}</div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table id="basic-datatables" class="display table table-striped table-hover" >
                                            <thead>
                                                <tr>
                                                    <th>{{ __('Fecha') }}</th>
                                                    <th>{{ __('Modalidad') }}</th>
                                                    <th>{{ __('Capital') }}</th>
                                                    <th>{{ __('Monto Retirado') }}</th>
                                                    <th>{{ __('Status') }}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            <?php
                                                    $activities = App\investment::where('user_id', $user->id)->where('wd_status', 'Solicitado')->Orwhere('wd_status', 'Depositado')
                                                    ->orderby('id', 'desc')->get();
                                                ?>
                                                @if(count($activities) > 0 )
                                                    @foreach($activities as $activity)
                                                        <tr>
                                                            <td>{{$activity->created_at}}</td>
                                                            <td>{{$activity->package}}</td>
                                                            <td>{{$activity->capital}}</td>
                                                            <td>{{$activity->currency.' '.$activity->w_amt}}</td>
                                                            <td>{{$activity->wd_status}}</td>

                                                        </tr>
                                                    @endforeach
                                                @else

                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="prnt"></div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <div class="card-head-row">
                                        <div class="card-title">{{ __('Historial de Retiro de Inyecciones') }}</div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table id="basic-datatables" class="display table table-striped table-hover" >
                                            <thead>
                                                <tr>
                                                    <th>{{ __('Fecha') }}</th>
                                                    <th>{{ __('Modalidad') }}</th>
                                                    <th>{{ __('Capital') }}</th>
                                                    <th>{{ __('Monto Retirado') }}</th>
                                                    <th>{{ __('Status') }}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            <?php
                                                    $activities = App\inyects::where('user_id', $user->id)->where('wd_status', 'Solicitado')
                                                    ->Orwhere('wd_status', 'Depositado')
                                                    ->orderby('id', 'desc')->get();
                                                ?>
                                                @if(count($activities) > 0 )
                                                    @foreach($activities as $activity)
                                                        <tr>
                                                            <td>{{$activity->created_at}}</td>
                                                            <td>{{$activity->package}}</td>
                                                            <td>{{$activity->capital}}</td>
                                                            <td>{{$activity->currency.' '.$activity->w_amt}}</td>
                                                            <td>{{$activity->wd_status}}</td>

                                                        </tr>
                                                    @endforeach
                                                @else

                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                </div>
            </div>

            @include('user.inc.confirm_inv')

@endSection
