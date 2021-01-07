@extends('admin.atlantis.layout')
@Section('content')
        <div class="main-panel">
            <div class="content">
                @include('admin.atlantis.main_bar')
                <div class="page-inner mt--5">
                    @include('admin.atlantis.overview')
                    <div id="prnt"></div>
                    <div class="row">
                        <div class="col-md-8">
                            <div class="card">
                                <div class="card-header">
                                    <div class="card-head-row">
                                        <div class="card-title"> {{ __('Estadísticas generales') }} </div>
                                        <div class="card-tools">
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="chart-container @if($adm->role < 2) {{blur_cnt}}@endif" >
                                        <canvas id="adminStatisticsChart2"></canvas>
                                    </div>
                                    <div id="adminMyChartLegend2"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card card-primary @if($adm->role < 2) {{blur_cnt}}@endif">
                                <div class="card-header">
                                    <div class="card-title"> {{ __('Estadísticas de Retiros') }} </div>
                                </div>
                                <div class="card-body pb-0">
                                    <div class="pull-in">
                                        <canvas id="wd_stats"></canvas>
                                    </div>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-body pb-0">
                                    <div class="px-2 pb-2 pb-md-0 text-center">
                                        <div id="circles-logs"></div>
                                        <h6 class="fw-bold mt-3 mb-0"> {{ __('Actividades') }} </h6>
                                        <br>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <div class="card-title"> {{ __('Desglose Mensual') }} </div>
                                </div>
                                <div class="card-body pb-0">
                                    @include('admin.temp.monthly')
                                </div>
                            </div>
                        </div>
                    </div>

                    @php($role = Session::get('adm'))
                    @if($role->role == 3)
                    <div class="row row-card-no-pd">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <div class="card-head-row card-tools-still-right">
                                        <h4 class="card-title"> {{ __('Actividades Usuarios') }} </h4>
                                    </div>
                                    <p class="card-category">
                                        {{ __('Todas las acciones realizadas por los usuarios.') }}
                                    </p>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="card">
                                                <div class="card-body">
                                                    <div class="table-responsive">
                                                        <table id="basic-datatables" class="display table table-stripped
                                                        table-hover" >
                                                            <thead>
                                                                <tr>
                                                                    <th> {{ __('Admin') }} </th>
                                                                    <th> {{ __('Acciones') }} </th>
                                                                    <th> {{ __('Fecha') }} </th>
                                                                </tr>
                                                            </thead>
                                                            <tfoot>
                                                                <tr>
                                                                    <th> {{ __('Admin') }} </th>
                                                                    <th> {{ __('Acciones') }} </th>
                                                                    <th> {{ __('Fecha') }} </th>
                                                                </tr>
                                                            </tfoot>
                                                            <tbody>
                                                                @foreach($logs as $log)
                                                                    <tr>
                                                                        <td>
                                                                            {{ $log->admin }}
                                                                        </td>
                                                                        <td>
                                                                            {{ $log->action }}
                                                                        </td>
                                                                        <td>{{ $log->created_at }}</td>
                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                </div>
            </div>

@endSection
