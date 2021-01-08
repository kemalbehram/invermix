<div class="sparkline8-graph dashone-comment  dashtwo-messages">
    <div class="comment-phara">
        <div class="row comment-adminpr">
            <?php
                $invs = App\packages::where('status', 1)->orderby('id', 'asc')->get();
            ?>
            @if($user->phone != '')
                @if(isset($invs) && count($invs) > 0)
                    @foreach($invs as $inv)
                        <div class="col-sm-4">
                            <div class="panel card pack-container" style="" align="center">
                                <div class="panel-head" style="">
                                    <h3 class="txt_transform">{{ __('Plan') }} {{$inv->package_name}} </h3>
                                </div>
                                <div class="" align="center" >
                                    <br>
                                        <h4 class="u_case" >
                                            <strong>{{ __('Período de Inversión') }}</strong>
                                        </h4>
                                        <div style="font-size: 40px;">
                                            <b>
                                                {{$inv->period}}
                                            </b>
                                        </div>
                                        <span class="pk_num">
                                                {{__('Días')}}
                                        </span>
                                </div>
                                <br>
                                <span align="center">..............................</span>
                                <div class="" align="center" style="">
                                        <h4 class="u_case" >
                                            <strong>{{ __('Inversión Mín.') }}</strong>
                                        </h4>
                                        <span class="pk_num">{{'RD$'}} {{number_format($inv->min),2}}</span>
                                        <br>
                                        <span class="note">{{'US$'}} {{number_format($inv->mindol),2}}</span>
                                        <br>
                                        <br>
                                        <h4 class="u_case">
                                            <strong>{{ __('Inversión Máx.') }}</strong>
                                        </h4>

                                        <span class="pk_num">{{'RD$'}} {{number_format($inv->max),2}}</span>
                                        <br>
                                        <span class="note">{{'US$'}} {{number_format($inv->maxdol),2}}</span>

                                </div>


                                <span align="center">..............................</span>
                                <div class="" align="center">
                                    <h4 class="u_case">
                                        <strong>Interés Total</strong>
                                    </h4>
                                     <span class="pk_num">{{$inv->daily_interest*$inv->period*100}}%</span>
                                </div>
                                <br>
                                 <div class="" align="center">
                                    <h4 class="u_case">
                                       <strong> Intervalo de Retiro</strong>
                                    </h4>
                                    <span class="pk_num">{{$inv->days_interval}} Días</span>
                                </div>
                                <br>
                                <div class="" align="center">
                                    <p>{{ __('Capital accesible una vez transcurrida el intervalo de retiro.') }}</p>
                                </div>
                                <div class="" align="center">
                                        <a id="{{$inv->id}}" href="javascript:void(0)" class="collcc btn btn-info" onclick="confirm_inv('{{$inv->id}}', '{{$inv->package_name}}', '{{$inv->period}}', '{{$inv->daily_interest}}', '{{$inv->min}}', '{{$inv->max}}', '{{$inv->mindol}}','{{$inv->maxdol}}','{{$user->wallet}}')">
                                            {{ __('Invertir') }}
                                        </a>
                                        <br><br>
                                </div>

                            </div>
                        </div>

                    @endforeach
                @endif
            @else
                <div class="alert alert-warning">
                    <a>{{ __('Por favor, actualice los datos principales del cliente para poder invertir.') }}</a>
                </div>
            @endif
        </div>
    </div>
</div>
