@include('user.inc.fetch')
@extends('layouts.atlantis.layout')
@Section('content')
        <div class="main-panel">
            <div class="content">
                @php($breadcome = 'Mis Inversiones')
                @include('user.atlantis.main_bar')
                <div class="page-inner mt--5">
                    @include('user.atlantis.overview')
                    <div id="prnt"></div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <div class="card-head-row">
                                        <div class="card-title">{{ __('Mis Inversiones') }}</div>
                                    </div>
                                </div>
                                <div class="card-body ">
                                    <div class="table-responsive web-table">
                                        <table id="basic-datatables" class="display table table-hover" >
                                            <thead>
                                                <tr>
                                                    <th>{{ __('Plan') }}</th>
                                                    <th>{{ __('Capital') }}</th>
                                                    <th>{{ __('Fecha de inversión') }}</th>
                                                    <th>{{ __('Hasta') }}</th>
                                                    <th>{{ __('Días transcurridos') }}</th>
                                                    <th>{{ __('Status') }}</th>
                                                    <th>{{ __('Ganancias') }}</th>
                                                    <th>{{ __('Propósito') }}</th>
                                                    <th>{{ __('Retirar') }}</th>
                                                    <th>{{ __('Inyectar') }}</th>
                                                </tr>
                                            </thead>
                                            <tbody class="web-table">
                                                @if(count($actInv) > 0 )
                                                    @foreach($actInv as $in)
                                                        <?php

                                                            $totalElapse = getDays(date('Y-m-d'), $in->end_date);
                                                            if($totalElapse == 0)
                                                            {
                                                                $lastWD = $in->last_wd;
                                                                $enddate = ($in->end_date);
                                                                $Edays = getDays($lastWD, $enddate);
                                                                $ern  = $Edays*$in->interest*$in->capital;
                                                                $withdrawable = $ern;
                                                                $totalDays = getDays($in->date_invested, $in->end_date);
                                                                $ended = "yes";
                                                            }
                                                            else
                                                            {
                                                                $lastWD = $in->last_wd;
                                                                $enddate = (date('Y-m-d'));
                                                                $Edays = getDays($lastWD, $enddate);
                                                                $ern  = $Edays*$in->interest*$in->capital;
                                                                $withdrawable = 0;
                                                                if ($Edays >= $in->days_interval)
                                                                {
                                                                    $withdrawable = $in->days_interval*$in->interest*$in->capital;
                                                                }

                                                                $totalDays = getDays($in->date_invested, date('Y-m-d'));
                                                                $ended = "no";
                                                            }
                                                        ?>
                                                        <tr class="">
                                                            <td>{{$in->package}}</td>
                                                            <td>{{($in->currency)}} {{number_format($in->capital),2}}</td>
                                                            <td>{{($in->currency)}} {{number_format($in->i_return),2}}</td>
                                                            <td>{{date('d/m/Y', strtotime($in->date_invested))}}</td>
                                                            <td>{{date('d/m/Y', strtotime($in->end_date))}}</td>
                                                            <td>{{$totalDays}}</td>
                                                            <td>{{$in->status}}</td>
                                                            <td>{{$in->currency}} {{number_format($ern),2}} </td>
                                                            <td>



                                                                @if($in->description == Null)
                                                            <a href="javascript:void(0)" onclick="description('{{$in->id}}')">Agregar propósito</a>
                                                               @else
                                                            <a  class="des-after" href="javascript:void(0)" onclick="description('{{$in->id}}')">{{$in->description}}</a>
                                                            @endif
                                                            </td>
                                                            <td>
                                                                <a title="Retirar" href="javascript:void(0)" class="btn btn-info" onclick="wd('pack', '{{$in->id}}', '{{$ern}}', '{{ $withdrawable }}', '{{$Edays}}', '{{$ended}}')">
                                                                    <i class="fas fa-arrow-down"></i>
                                                                </a>
                                                            </td>

                                                            <td>
                                                                <a title="Inyectar"  href="javascript:void(0)" class="btn btn-info" onclick="inyect('{{$in->package_id}}', '{{$in->id}}','{{$in->package}}', ' {{$in->capital}}', ' {{$in->curency}}', '{{$in->package_name}}',  '{{$in->period}}', '{{$in->daily_interest}}')">
                                                                    <i class="fas fa-arrow-up"></i>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @else

                                                @endif
                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="mobile_table container messages-scrollbar" >

                                        @if(count($actInv) > 0 )
                                            @foreach($actInv as $in)
                                                <?php

                                                    $totalElapse = getDays(date('y-m-d'), $in->end_date);
                                                    if($totalElapse == 0)
                                                    {
                                                        $lastWD = $in->last_wd;
                                                        $enddate = ($in->end_date);
                                                        $Edays = getDays($lastWD, $enddate);
                                                        $ern  = $Edays*$in->interest*$in->capital;
                                                        $withdrawable = $ern;

                                                        $totalDays = getDays($in->date_invested, $in->end_date);
                                                        $ended = "yes";

                                                    }
                                                    else
                                                    {
                                                        $lastWD = $in->last_wd;
                                                        $enddate = (date('Y-m-d'));
                                                        $Edays = getDays($lastWD, $enddate);
                                                        $ern  = $Edays*$in->interest*$in->capital;
                                                        $withdrawable = 0;
                                                        if ($Edays >= $in->days_interval)
                                                        {
                                                            $withdrawable = $in->days_interval*$in->interest*$in->capital;
                                                        }

                                                        $totalDays = getDays($in->date_invested, date('Y-m-d'));
                                                        $ended = "no";
                                                    }

                                                ?>

                                                @include('user.inc.mob_inv')

                                            @endforeach
                                        @else

                                        @endif
                                    </div>

                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <div class="card-title" id="invertir"> Planes Disponibles</div>
                                </div>
                                <div class="card-body pb-0">
                                    @include('user.inc.packages')
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <div id="popDescription" class="container pop_invest_cont" >
  <div class="row wd_row_pad" >
    <div class="col-md-4">&emps;</div>
    <div class="col-md-4 card pop_invest_col" align="center">
      <div class="card-header" style="">

      </div>
      <div class="pop_msg_contnt">
        <p align="center" class="color_blue_b">
            {{ __('Agrega un propósito para identificar tu inversión.') }} <b>
        </p>
        <form  action="/user/invest/description" method="post">
            <div class="form-group" align="left">
              <br>
              <input type="hidden" class="form-control" name="_token" value="{{csrf_token()}}">
              <input id="inv_id" type="hidden" class="form-control" name="id" value="">
              <input type="text" class="form-control" name="description" placeholder="Agregar propósito, no más de 25 carácteres" required>
            </div>

            <div class="form-group">
                <button class="collb btn btn-info">{{ __('Agregar') }}</button>
                <span style="">
                  <a id="popDescription_close" href="javascript:void(0)" class="btn btn-danger">{{ __('Cancelar') }}</a>
                </span>
                <br><br>
            </div>
        </form>

      </div>
      <!-- close btn -->
      <script type="text/javascript">
        $('#popDescription_close').click( function(){
          $('#popDescription').hide();
        });
</script>
      <!-- end close btn -->
    </div>
  </div>

  <script type="text/javascript">
function description(id)
{
    // alert(id);
    $('#inv_id').val(id);
    $('#popDescription').show();
}
</script>

</div>

    @include('user.inc.confirm_inv')
    @include('user.inc.withdrawal')
    @include('user.inc.inyect')
@endSection

