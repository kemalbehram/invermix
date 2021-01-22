<?php

    $actInv = App\inyects::where('user_id', $user->id)->where('deleted_at', NULL)->orderby('id', 'desc')->get();

    $refs = App\ref::where('username', $user->username)->orderby('id', 'desc')->get();
    $ref_amt = 0;
    foreach($refs as $ref)
    {
       $ref_amt += $ref->amount;
    }
    $ref_bal = $ref_amt - $user->ref_bal;

    $totalEarning = 0;
    $currentEarning = 0;
    $workingDays = 0;


    foreach($actInv as $inv)
    {
        $totalElapse = getWorkingDays(date('y-m-d'), $inv->end_date);
        if($totalElapse == 0)
        {
            $lastWD = $inv->last_wd;
            $enddate = ($inv->end_date);
            $workingDays = getWorkingDays($lastWD, $enddate);
            $currentEarning += $workingDays*$inv->interest*$inv->capital;
        }
        else
        {
            $sd = $inv->last_wd;
            $ed = date('Y-m-d');
            $workingDays = getWorkingDays($sd, $ed);
            $currentEarning += $workingDays*$inv->interest*$inv->capital;
        }
    }
?>


<div class="table-responsive">
    <table id="basic-datatables" class="display table table-striped table-hover">
        <thead class="web-table">
            <tr>
               <th> {{ __('Plan') }} </th>
               <th> {{ __('Capital') }} </th>
               <th> {{ __('Retorno') }} </th>
               <th> {{ __('Fecha de Inversión') }} </th>
               <th> {{ __('Hasta') }} </th>
               <th> {{ __('Días transcurridos') }} </th>
               <th> {{ __('Retirado') }} </th>
               <th> {{ __('Status') }} </th>
               <th> {{ __('Acumulado') }} </th>
            </tr>
        </thead>

        <tbody class="web-table">

            @if(count($actInv) > 0 )
                @foreach($actInv as $in)
                    <?php

                        $totalElapse = getWorkingDays(date('y-m-d'), $in->end_date);
                        if($totalElapse == 0)
                        {
                            $lastWD = $in->last_wd;
                            $enddate = ($in->end_date);
                            $Edays = getWorkingDays($lastWD, $enddate);
                            $ern  = $Edays*$in->interest*$in->capital;
                            $withdrawable = $ern;

                            $totalDays = getWorkingDays($in->date_invested, $in->end_date);
                            $ended = "yes";

                        }
                        else
                        {
                            $lastWD = $in->last_wd;
                            $enddate = (date('Y-m-d'));
                            $Edays = getWorkingDays($lastWD, $enddate);
                            $ern  = $Edays*$in->interest*$in->capital;
                            $withdrawable = 0;
                            if ($Edays >= $in->days_interval)
                            {
                                $withdrawable = $in->days_interval*$in->interest*$in->capital;
                            }

                            $totalDays = getWorkingDays($in->date_invested, date('Y-m-d'));
                            $ended = "no";
                        }

                    ?>
                    <tr class="">
                        <td>{{$in->package}}</td>
                        <td>{{number_format($in->capital) ,2}}</td>
                        <td>{{number_format($in->i_return) ,2}}</td>
                        <td>{{$in->date_invested}}</td>
                        <td>{{$in->end_date}}</td>
                        <td>
                            @if($in->status != 'Retirado')
                                {{$totalDays}}
                            @else
                                0
                            @endif
                        </td>
                        <td>{{number_format($in->w_amt) ,2}}</td>
                        <td>{{$in->status}}</td>
                        <td>
                            <a title="Withdraw" href="javascript:void(0)" onclick="wdnone('{{$in->id}}', '{{$ern}}', '{{$withdrawable}}', '{{$Edays}}', '{{$ended}}')">
                            {{ number_format($ern), 2}}
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

                        $totalElapse = getWorkingDays(date('y-m-d'), $inv->end_date);
                        if($totalElapse == 0)
                        {
                            $lastWD = $in->last_wd;
                            $enddate = ($in->end_date);
                            $Edays = getWorkingDays($lastWD, $enddate);
                            $ern  = $Edays*$in->interest*$in->capital;
                            $withdrawable = $ern;

                            $totalDays = getWorkingDays($in->date_invested, $in->end_date);
                            $ended = "yes";

                        }
                        else
                        {
                            $lastWD = $in->last_wd;
                            $enddate = (date('Y-m-d'));
                            $Edays = getWorkingDays($lastWD, $enddate);
                            $ern  = $Edays*$in->interest*$in->capital;
                            $withdrawable = 0;
                            if ($Edays >= $in->days_interval)
                            {
                                $withdrawable = $in->days_interval*$in->interest*$in->capital;
                            }

                            $totalDays = getWorkingDays($in->date_invested, date('Y-m-d'));
                            $ended = "no";
                        }

                    ?>

                    <div class="alert alert-info margin_top_10 pad_top_0 font_14" >
                        <div class="row admin_usr_inv_row" >
                            <div class="col-xs-12 pad_top_5" align="center" >
                                <h4 class="u_case"> {{ __('Pakage:') }}  {{$in->package}}</h4>

                            </div>
                        </div>
                        <div class="row color_blue_9">
                            <div class="col-xs-6">
                                {{ __('Capital:') }}
                            </div>
                            <div class="col-xs-6">
                                {{$in->capital}}
                            </div>
                        </div>
                        <div class="row" style="">
                            <div class="col-xs-6">
                                {{ __('Return:') }}
                            </div>
                            <div class="col-xs-6">
                                {{$in->i_return}}
                            </div>
                        </div>
                        <div class="row" style="">
                            <div class="col-xs-6">
                                {{ __('Started:') }}
                            </div>
                            <div class="col-xs-6">
                                {{$in->date_invested}}
                            </div>
                        </div>
                        <div class="row" style="">
                            <div class="col-xs-6">
                                {{ __('Ending:') }}
                            </div>
                            <div class="col-xs-6">
                                {{$in->end_date}}
                            </div>
                        </div>
                        <div class="row" style="">
                            <div class="col-xs-6">
                                {{ __('Days:') }}
                            </div>
                            <div class="col-xs-6">
                                {{$totalDays}}
                            </div>
                        </div>
                        <div class="row" style="">
                            <div class="col-xs-6">
                                {{ __('Withdrawn:') }}
                            </div>
                            <div class="col-xs-6">
                                {{$in->w_amt}}
                            </div>
                        </div>
                        <div class="row" style="">
                            <div class="col-xs-6">
                                {{ __('Status:') }}
                            </div>
                            <div class="col-xs-6">
                                {{$in->status}}
                            </div>
                        </div>
                        <div class="row" style="">
                            <br>
                            <div class="col-xs-12" align="center">
                                <a title="" href="javascript:void(0)" class="btn btn-info" onclick="wd('{{$in->id}}', '{{$ern}}', '{{$withdrawable}}', '{{$Edays}}', '{{$ended}}')">
                                {{ number_format($ern), 2}}
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else

            @endif
</div>
