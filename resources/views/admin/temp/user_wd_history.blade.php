
         
    <table id="" class=" table table-stripped table-hover">
        <thead>
            <tr>                           
                <th> {{ __('Capital') }} </th>
                <th> {{ __('Monto pagable') }} </th>
                <th> {{ __('Fecha') }} </th>
                <th> {{ __('Status') }} </th>                                                   
            </tr>
        </thead>
        <tbody>
            <?php
                $activities =  App\investment::where('user_id', $user->id)->where('status', 'Solicitado')->Orwhere('status', 'Retirado')->Orwhere('status', 'Depositado')
                ->orderby('created_at', 'desc')->get();
            
            ?>
            @if(count($activities) > 0 )
                @foreach($activities as $dep)
                    <tr> 
                                <td>{{$dep->currency}} {{$dep->capital}}</td>
                                <td><b>{{$dep->currency}} {{$dep->i_return}}</b></td>
                                <td>{{substr($dep->created_at, 0, 10)}}</td>
                                <td>{{$dep->wd_status}}</td>                   
                    </tr>
                @endforeach
            @else
                
            @endif
        </tbody>
    </table>
