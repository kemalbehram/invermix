<?php

    if(Session::has('val'))
    {
        $v = Session::get('val');
        $actIny = App\inyects::where('user_id', $v)->orwhere('usn', 'like', '%'.$v.'%')->orwhere('capital', $v)->orwhere('status', $v)->orwhere('created_at', 'like', '%'.$v.'%')->orderby('created_at', 'desc')->whereNull('deleted_at')->paginate(50);
        Session::forget('val');
    }
    else
    {
        $actIny = App\inyects::where('deleted_at', NULL)->orderby('created_at', 'desc')->paginate(50);
    }

?>

<table class="display table table-stripped table-hover">
    <thead>
        <tr>
            <th> {{ __('Acción') }} </th>
            <th> {{ __('Nombre de usuario') }} </th>
            <th> {{ __('Plan') }} </th>
            <th> {{ __('Capital') }} </th>
            <th> {{ __('Retorno') }} </th>
            <th> {{ __('Fecha inversión') }} </th>
            <th> {{ __('Hasta') }} </th>
            <th> {{ __('Días transcurridos') }} </th>
            <th> {{ __('Retirados') }} </th>
            <th> {{ __('Status') }} </th>
            <th> {{ __('Ganancias') }} </th>
        </tr>
    </thead>
    <tbody class="web-table">
        @if(count($actIny) > 0 )
            @foreach($actIny as $in)
                <?php

                    $totalElapse = getWorkingDays(date('y-m-d'), $in->end_date);
                    if($totalElapse == 0)
                    {
                        $lastWD = $in->last_wd;
                        $enddate = ($in->end_date);
                        $Edays = getWorkingDays($lastWD, $enddate);
                        $ern  = intval($Edays)*floatval($in->interest)*intval($in->capital);
                        $withdrawable = $ern;

                        $totalDays = getWorkingDays($in->date_inyected, $in->end_date);
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

                        $totalDays = getWorkingDays($in->date_inyected, date('Y-m-d'));
                        $ended = "no";
                    }

                ?>
                <tr class="">
                    <td>
                        <a title="Pausar Inyección" href="/admin/pause/user_inj/{{$in->id}}" >
                            <span class=""><i class="fa fa-pause text-warning" ></i></span>
                        </a>
                        @if($adm->role == 3 |$adm->role == 2 )
                        &nbsp;&nbsp;&nbsp;
                             <a title="Activar Inyección" href="javascript:void(0)" onclick="activate_inj('{{$in->id}}','{{$in->usn}}', '{{$in->package}}', '{{number_format($in->capital), 2}}', '{{$in->status}}', '{{$in->currency}}','{{csrf_token()}}')" >
                                <span><i class="fa fa-check text-success"></i></span>
                            </a>
                            <br>
                            <a title="Borrar Inyección" href="/admin/delete/user_inj/{{$in->id}}" >
                                <span class=""><i class="fa fa-times text-danger"></i></span>
                            </a>
                        @endif
                    </td>
                    <td>{{$in->usn}}</td>
                    <td>{{$in->package}}</td>
                    <td>{{$in->currency}}{{number_format($in->capital), 2}}</td>
                    <td>{{$in->currency}}{{number_format(round ($in->i_return, 2), 2 )}}</td>
                    <td>{{date('d/m/Y', strtotime($in->created_at))}}</td>
                    <td>{{date('d/m/Y', strtotime($in->end_date))}}</td>
                    <td>
                        @if($in->status != 'Retirado')
                            {{$totalDays}}
                        @else
                            0
                        @endif
                    </td>
                    <td>{{number_format($in->w_amt, 2)}}</td>
                    <td>{{$in->status}}</td>
                    <td>

                            {{$in->currency}} {{number_format(round ($ern, 2),2)}}

                    </td>
                </tr>
            @endforeach

        @else

        @endif
    </tbody>

</table>
<div class="" align="">
   <span> {{$actIny->links()}}</span>
</div>
@include('admin.temp.activate_inj')
