@include('user.inc.fetch')
@extends('layouts.atlantis.layout')
@Section('content')
        <div class="main-panel">
            <div class="content">
                @php($breadcome = 'Inyecciones')
                @include('user.atlantis.main_bar')
                <div class="page-inner mt--5">
                    @include('user.atlantis.overview')
                    <div id="prnt"></div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <div class="card-head-row">
                                        <div class="card-title">{{ __('Mis Inyecciones') }}</div>
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
                                                </tr>
                                            </thead>
                                            <tbody class="web-table">
                                                @if(count($actIny) > 0 )
                                                    @foreach($actIny as $in)
                                                        <?php

                                                            $totalElapse = getDays(date('Y-m-d'), $in->end_date);
                                                            if($totalElapse == 0)
                                                            {
                                                                $lastWD = $in->last_wd;
                                                                $enddate = ($in->end_date);
                                                                $Edays = getDays($lastWD, $enddate);
                                                                $ern  = $Edays*$in->interest*$in->capital;
                                                                $withdrawable = $ern;
                                                                $totalDays = getDays($in->date_inyected, $in->end_date);
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

                                                                $totalDays = getDays($in->date_inyected, date('Y-m-d'));
                                                                $ended = "no";
                                                            }
                                                        ?>
                                                        <tr class="">
                                                            <td>{{$in->package}}</td>
                                                            <td>{{($in->currency)}} {{number_format($in->capital),2}}</td>
                                                            <td>{{($in->currency)}} {{number_format($in->i_return),2}}</td>
                                                            <td>{{date('d/m/Y', strtotime($in->date_inyected))}}</td>
                                                            <td>{{date('d/m/Y', strtotime($in->end_date))}}</td>
                                                            <td>{{$totalDays}}</td>
                                                            <td>{{$in->status}}</td>
                                                            <td>{{$in->currency}} {{number_format($ern),2}} </td>
                                                            <td>



                                                                @if($in->description == Null)
                                                            <a href="javascript:void(0)" onclick="descriptioninj('{{$in->id}}')">Agregar propósito</a>
                                                               @else
                                                            <a  class="des-after" href="javascript:void(0)" onclick="descriptioninj('{{$in->id}}')">{{$in->description}}</a>
                                                            @endif
                                                            </td>

                                                            <td>
                                                                <a title="Retirar" href="javascript:void(0)" class="btn btn-info" onclick="inywd('pack', '{{$in->id}}', '{{$ern}}', '{{ $withdrawable }}', '{{$Edays}}', '{{$ended}}')">
                                                                    <i class="fas fa-arrow-down"></i>
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

                                        @if(count($actIny) > 0 )
                                            @foreach($actIny as $in)
                                                <?php

                                                    $totalElapse = getDays(date('y-m-d'), $in->end_date);
                                                    if($totalElapse == 0)
                                                    {
                                                        $lastWD = $in->last_wd;
                                                        $enddate = ($in->end_date);
                                                        $Edays = getDays($lastWD, $enddate);
                                                        $ern  = $Edays*$in->interest*$in->capital;
                                                        $withdrawable = $ern;

                                                        $totalDays = getDays($in->date_inyected, $in->end_date);
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

                                                        $totalDays = getDays($in->date_inyected, date('Y-m-d'));
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
                                    <div class="card-title"> Planes Disponibles</div>
                                </div>
                                <div class="card-body pb-0">
                                    @include('user.inc.packages')
                                </div>
                            </div>
                        </div>
                    </div>



                </div>
            </div>

            <div id="div_inyect" class="container popmsgContainer" >
    <div class="row wd_row_pad" >
      <div class="col-md-4">&emps;</div>
      <div class="col-md-4 card popmsg-mobile pop_invest_col" align="Center">
        <div class="card-header" style="">
          <h3> {{ __('Retiro') }} </h3>
          <h5>{{ __('Ganancia Total:') }} $ <span id="earned"></span></h5>
          <small>Días: <span id="days" class="text-danger" ></span></small>
        </div>
        <div class="card-body pt-0" >

          <form id="wd_formssss" action="/user/wdraw/earning/inyect" method="post">
              <div class="form-group" align="left">
                  <input type="hidden" class="form-control" name="_token" value="{{csrf_token()}}">
                  <input id="inv_id" type="hidden" class="form-control" name="p_id" value="">
                  <input id="ended" type="hidden" class="form-control" name="ended" value="">
              </div>
              <div align="left">
                <label>{{ __('Monto a retirar') }} </label>
              </div>
              <div class="input-group">
                <div class="input-group-prepend " >
                  <span class="input-group-text " >$ </span>
                </div>
                <input id="withdrawable_amt" type="text" value="" readonly class="bg_white form-control" name="amt"  required >
              </div>
              <div class="form-group">
                <br><br>
                  <button class="btn btn-info"> {{ __('Retirar') }} </button>
                  <span style="">
                    <a id="div_inyect_close" href="javascript:void(0)" class="btn btn-danger"> {{ __('Cancelar') }} </a>
                  </span>
                  <br>
              </div>
          </form>
        </div>
        <!-- close btn -->
        <script type="text/javascript">
          $('#div_inyect_close').click( function(){
            $('#div_inyect').hide();
          });
        </script>

<script type="text/javascript">

function inywd(p,id, earn, w_able, days, ended)
{
// alert(id);
$('#pack_type').val(p);
$('#earned').text(earn);
$('#days').text(days);
$('#inv_id').val(id);
$('#ended').val(ended);
$('#withdrawable_amt').val(w_able);
$('#div_inyect').show();

}

</script>

        <!-- end close btn -->
      </div>

    </div>

    </div>



    <div id="popDescriptionInj" class="container pop_invest_cont" >
  <div class="row wd_row_pad" >
    <div class="col-md-4">&emps;</div>
    <div class="col-md-4 card pop_invest_col" align="center">
      <div class="card-header" style="">

      </div>
      <div class="pop_msg_contnt">
        <p align="center" class="color_blue_b">
            {{ __('Agrega un propósito para identificar tu inyección.') }} <b>
        </p>
        <form  action="/user/inject/description" method="post">
            <div class="form-group" align="left">
              <br>
              <input type="hidden" class="form-control" name="_token" value="{{csrf_token()}}">
              <input id="inj_id" type="hidden" class="form-control" name="id" value="">
              <input type="text" class="form-control" name="description" placeholder="Agregar propósito, no más de 25 carácteres" required>
            </div>

            <div class="form-group">
                <button class="collb btn btn-info">{{ __('Agregar') }}</button>
                <span style="">
                  <a id="popDescriptionInj_close" href="javascript:void(0)" class="btn btn-danger">{{ __('Cancelar') }}</a>
                </span>
                <br><br>
            </div>
        </form>

      </div>
      <!-- close btn -->
      <script type="text/javascript">
        $('#popDescriptionInj_close').click( function(){
          $('#popDescriptionInj').hide();
        });
</script>
      <!-- end close btn -->
    </div>
  </div>

  <script type="text/javascript">
function descriptioninj(id)
{
    $('#inj_id').val(id);
    $('#popDescriptionInj').show();
}
</script>

</div>




    @include('user.inc.confirm_inv')
    @include('user.inc.withdrawal')

@endSection
