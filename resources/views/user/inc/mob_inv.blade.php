
<div class="alert alert-info inv_alert_cont" >
    <div class="row inv_alert_top_row">
        <div class="col-xs-12 pad_top_5" align="center" >
            <h4 class="u_case">{{ __('Plan') }}: {{$in->package}}</h4>

        </div>
    </div>
    <br>
    <div class="row color_blue_9">
        <div class="col-xs-6">
            {{ __('Capital:') }}
        </div>
        &nbsp;
        <div class="col-xs-6">
            {{($in->currency)}} {{$in->capital}}
        </div>
    </div>
    <div class="row" style="">
        <div class="col-xs-6">
            {{ __('Retorno:') }}
        </div>
        &nbsp;
        <div class="col-xs-6">
            {{($in->currency)}} {{$in->i_return}}
        </div>
    </div>
    <div class="row" style="">
        <div class="col-xs-6">
            {{ __('Desde:') }}
        </div>
        &nbsp;
        <div class="col-xs-6">
            {{$in->date_invested}}
        </div>
    </div>
    <div class="row" style="">
        <div class="col-xs-6">
            {{ __('Hasta:') }}
        </div>
        &nbsp;
        <div class="col-xs-6">
            {{$in->end_date}}
        </div>
    </div>
    <div class="row" style="">
        <div class="col-xs-6">
            {{ __('Días Transcurridos:') }}
        </div>
        &nbsp;
        <div class="col-xs-6">
            {{$totalDays}}
        </div>
    </div>
    <div class="row" style="">
        <div class="col-xs-6">
           {{ __('Retirados:') }}
        </div>
        &nbsp;
        <div class="col-xs-6">
            {{($in->currency)}} {{$in->w_amt}}
        </div>
    </div>
    <div class="row" style="">
        <div class="col-xs-6">
            {{ __('Status:') }}
        </div>
        &nbsp;
        <div class="col-xs-6">
            {{$in->status}}
        </div>
        <br>
        <br>
    </div>
    <div class="row" style="" align="center">
        <br>
        <div class="col-xs-12" align="center">
            <a title="Withdraw" href="javascript:void(0)" class="btn btn-info" onclick="wd('pack', '{{$in->id}}', '{{$ern}}', '{{$withdrawable}}', '{{$Edays}}', '{{$ended}}')">
                {{$in->currency}} {{$ern}}
                <br>
                {{ __('Solicitar Retiro') }}
            </a>
            <a title="Inyectar" href="javascript:void(0)" class="btn btn-info" onclick="inyect('{{$in->package_id}}', '{{$in->id}}','{{$in->package}}', ' {{$in->capital}}', ' {{$in->curency}}', '{{$in->package_name}}',  '{{$in->period}}', '{{$in->daily_interest}}')">
            <br>
                {{ __('Inyectar') }}

            </a>
        </div>
        <!-- {{ __('Solicitar Retiro') }} -->
    </div>
</div>

