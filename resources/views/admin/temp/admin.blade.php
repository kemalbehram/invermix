<table class="display table table-stripped table-hover">
    <thead>
        <tr>
            <th> {{ __('Nombre') }} </th>
            <th> {{ __('Correo Electrónico') }} </th>
            <th> {{ __('Rol') }} </th>
            <th> {{ __('Status') }} </th>
            <th> {{ __('Manejar') }} </th>
        </tr>
    </thead>
    <tbody>
        @if(count($adm_users) > 0 )
            @foreach($adm_users as $dep)
                <tr>
                    <td>{{$dep->name}}</td>
                    <td>{{$dep->email}}</td>
                    <td>
                        @if($dep->role == 1)
                        {{$dep->role = "Support"}}
                        @elseif($dep->role == 2)
                        {{$dep->role = "Manager"}}
                         @else
                         {{$dep->role = "Admin"}}
                        @endif
                    </td>
                    <td>{{$dep->status}}</td>
                    <td>
                        <a title="Bloquear Usuario" href="/admin/ban/admuser/{{$dep->id}}" >
                            <span class=""><i class="fa fa-ban text-warnig" ></i></span>
                        </a>
                        <a title="Activar Usuario" href="/admin/activate/admuser/{{$dep->id}}" >
                            <span><i class="fa fa-check text-success"></i></span>
                        </a>
                        @if($adm->role == 3)
                            <a title="Borrar Usuario" href="/admin/delete/admuser/{{$dep->id}}" >
                                <span class=""><i class="fa fa-times text-danger"></i></span>
                            </a>
                        @endif
                    </td>

                </tr>
            @endforeach
        @else
            <tr>
                <td>No data</td>
            </tr>
        @endif
    </tbody>
</table>
