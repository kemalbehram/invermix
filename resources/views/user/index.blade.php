@include('user.inc.fetch')
@extends('layouts.atlantis.layout')
@Section('content')
		<div class="main-panel">
			<div class="content">
			    @php($breadcome = 'Dashboard')
				@include('user.atlantis.main_bar')
				<div class="page-inner mt--5">
					@include('user.atlantis.overview')
					<div id="prnt"></div>
					<div class="row">
						<div class="col-md-8">
							<div class="card">
								<div class="card-header">
									<div class="card-head-row">
										<div class="card-title">{{ __('Estadísticas de Inversión') }}</div>
										<div class="card-tools">
										</div>
									</div>
								</div>
								<div class="card-body">
									<div class="chart-container">
										<canvas id="statisticsChart2"></canvas>
									</div>
									<div id="myChartLegend2"></div>
								</div>
							</div>
						</div>
						<div class="col-md-4">
							<div class="card card-primary">
								<div class="card-header">
									<div class="card-title">{{ __('Estadísticas de Retiros en Inversión') }} </div>
									<div class="card-category">
                                    <?php
                                                    $activities = App\investment::where('user_id', $user->id)->orderby('id', 'desc')->paginate(1);
                                                ?>

                                    @foreach($activities as $activity)


                                        <h1>{{$activity->currency.' '. $activity->sum('w_amt')}}</h1>
                                        @endforeach
									</div>
								</div>
								<div class="card-body pb-0">
									<div class="pull-in">
										<canvas id="wd_stats"></canvas>
									</div>
								</div>
							</div>
							<!-- <div class="card">
								<div class="card-body pb-0">
									<div class="px-2 pb-2 pb-md-0 text-center">
										<div id="circles-logs"></div>
										<h6 class="fw-bold mt-3 mb-0">{{ __('My Total Actions') }}</h6>
										<br>
									</div>
								</div>
							</div> -->
                        </div>

					</div>

					<div class="row">
						<div class="col-md-12">
							<div class="card">
								<div class="card-header">
									<div class="card-title">{{ __('Planes de Inversión') }} </div>
								</div>
								<div class="card-body pb-0">
									@include('user.inc.packages')
								</div>
							</div>
						</div>
					</div>


				</div>
			</div>

			 @include('user.inc.confirm_inv')

@endSection
