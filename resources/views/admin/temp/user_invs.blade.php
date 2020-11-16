<?php

    if(Session::has('val'))
    {
        $v = Session::get('val');
        $actInv = App\investment::where('user_id', $v)->orwhere('usn', 'like', '%'.$v.'%')->orwhere('capital', $v)->orwhere('status', $v)->orwhere('date_invested', 'like', '%'.$v.'%')->orderby('id', 'desc')->paginate(50);
        Session::forget('val');
    }
    else
    {
        $actInv = App\investment::orderby('id', 'desc')->paginate(50);
    }

?>

<table class="display table table-stripped table-hover">
    <thead>
        <tr>
            <th> {{ __('Acción') }} </th>
            <th> {{ __('Nombre de usuario') }} </th>
            <th> {{ __('Modalidad') }} </th>
            <th> {{ __('Capital') }} </th>
            <th> {{ __('Retorno') }} </th>
            <th> {{ __('Fecha inversión') }} </th>
            <th> {{ __('Hasta') }} </th>
            <th> {{ __('Días transcurridos') }} </th>
            <th> {{ __('Retorno') }} </th>
            <th> {{ __('Status') }} </th>
            <th> {{ __('Ganancias') }} </th>
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
                        $ern  = intval($Edays)*floatval($in->interest)*intval($in->capital);
                        $withdrawable = $ern;

                        $totalDays = getWorkingDays($in->date_invested, $in->end_date);
                        $ended = "yes";

                    }
                    else
                    {
                        $lastWD = $in->last_wd;
                        $enddate = (date('Y-m-d'));
                        $Edays = getWorkingDays($lastWD, $enddate);
                        $ern  = intval($Edays)*floatval($in->interest)*intval($in->capital);
                        $withdrawable = 0;
                        if ($Edays >= $in->days_interval)
                        {
                            $withdrawable = intval($in->days_interval)*intval($in->interest)*intval($in->capital);
                        }

                        $totalDays = getWorkingDays($in->date_invested, date('Y-m-d'));
                        $ended = "no";
                    }

                ?>
                <tr class="">
                    <td>
                        <a title="Pause Investment" href="/admin/pause/user_inv/{{$in->id}}" >
                            <span class=""><i class="fa fa-pause text-warning" ></i></span>
                        </a>
                        @if($adm->role == 3 |$adm->role == 2 )
                            <a title="Activate Investment" href="/admin/activate/user_inv/{{$in->id}}" >
                                <span><i class="fa fa-check text-success"></i></span>
                            </a>
                            <a title="Delete Investment" href="/admin/delete/user_inv/{{$in->id}}" >
                                <span class=""><i class="fa fa-times text-danger"></i></span>
                            </a>
                        @endif
                    </td>
                    <td>{{$in->usn}}</td>
                    <td>{{$in->package}}</td>
                    <td>{{$in->currency}}{{$in->capital}}</td>
                    <td>{{$in->currency}}{{round ($in->i_return, 2)}}</td>
                    <td>{{$in->created_at->format('d-m-Y')}}</td>
                    <td>{{$in->end_date}}</td>
                    <td>
                        @if($in->status != 'Expired')
                            {{$totalDays}}
                        @else
                            0
                        @endif
                    </td>
                    <td>{{$in->w_amt}}</td>
                    <td>{{$in->status}}</td>
                    <td>
                        @if($in->currency == "" && $in->package != 'International')
                            N {{round ($ern, 2)}}
                        @elseif($in->currency == "" && $in->package = 'International')
                            $ {{round ($ern, 2)}}
                        @else
                            {{$in->currency}} {{round ($ern, 2)}}
                        @endif
                    </td>
                </tr>
            @endforeach

        @else

        @endif
    </tbody>

</table>
<div class="" align="">
   <span> {{$actInv->links()}}</span>
</div>
