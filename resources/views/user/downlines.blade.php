@include('user.inc.fetch')
@extends('layouts.atlantis.layout')
@Section('content')
        <div class="main-panel">
            <div class="content">
                @php($breadcome = 'My Downlines')
                @include('user.atlantis.main_bar')
                <div class="page-inner mt--5">
                    @include('user.atlantis.overview')
                    <div id="prnt"></div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <div class="card-head-row">
                                        <div class="card-title">                                            
                                            {{ __('Referral link:') }}
                                            <a href="/register/{{$user->username}}" class="text-danger" id="reflnk" >
                                                <small>{{env('APP_URL').__('/register/').$user->username}}</small>
                                            </a>                                            
                                        </div>                                       
                                    </div>
                                </div>                                
                            </div>
                        </div>
                        
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <div class="card-title">{{ __('My Downlines') }}</div>
                                </div>
                                <div class="card-body pb-0">
                                    <div class="table-responsive">                                        
                                        <table id="basic-datatables" class="display table table-striped table-hover" >
                                            <thead>
                                                <tr>
                                                    <!-- <th data-field="state" data-checkbox="true"></th> -->
                                                    <th>{{ __('Name') }}</th>
                                                    <th>{{ __('Username') }}</th>
                                                    <th>{{ __('Investment') }}</th>
                                                    <th>{{ __('Date Registered') }}</th>   
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                    $activities = App\User::where('referal', $user->username)->orderby('id', 'desc')->get();
                                                ?>
                                                @if(count($activities) > 0 )
                                                    @foreach($activities as $activity)
                                                        <tr>                                                            
                                                            <td>{{$activity->firstname}} {{$activity->lastname}}</td>
                                                            <td>{{$activity->username}}</td>
                                                            <td>
                                                                <?php  
                                                                    $ref_inv = App\investment::where('user_id', $activity->id)->get();
                                                                    echo count($ref_inv);
                                                                ?>
                                                            </td>                                                            
                                                            <td>{{$activity->created_at}}</td>                     
                                                        </tr>
                                                    @endforeach
                                                @else
                                                    
                                                @endif
                                            </tbody>
                                        </table>
                                        <br><br>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <div class="card-title"> {{ __('Earning from downlines') }} </div>
                                </div>
                                <div class="card-body pb-0">
                                    <div class="table-responsive">                                        
                                        <table id="" class="table table-hover" >
                                            <thead>
                                                <tr>                                                    
                                                    <th>{{ __('Date') }}</th>
                                                    <th>{{ __('Username') }}</th>
                                                    <th>{{ __('Name') }}</th>
                                                    <th>{{ __('Amount') }}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                    $activities = App\ref::where('username', $user->username)->orderby('id', 'desc')->get();
                                                ?>
                                                @if(count($activities) > 0 )
                                                    @foreach($activities as $activity)
                                                        <?php
                                                            $usr = App\User::find($activity->user_id);
                                                        ?>
                                                        <tr>  
                                                            <td>{{$activity->created_at}}</td>
                                                            <td>{{$usr->username}}</td>
                                                            <td>
                                                                {{$usr->firstname .' '. $usr->lastname}}
                                                            </td>
                                                            <td>{{$activity->currency}} {{$activity->amount}}</td>
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
            