            <table class="display table table-stripped table-hover">
                <thead>
                    <tr>
                        <th> {{ __('Acciones') }} </th>
                        <th> {{ __('Usuario') }} </th>
                        <th> {{ __('Monto') }} </th>
                        <th> {{ __('Monto pagable') }} </th>
                        <th> {{ __('Detalles bancario') }} </th>
                        <th> {{ __('Fecha') }} </th>
                        <th> {{ __('Status') }} </th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th> {{ __('Acciones') }} </th>
                        <th> {{ __('Usuario') }} </th>
                        <th> {{ __('Monto') }} </th>
                        <th> {{ __('Monto pagable') }} </th>
                        <th> {{ __('Detalles bancario') }} </th>
                        <th> {{ __('Fecha') }} </th>
                        <th> {{ __('Status') }} </th>
                    </tr>
                </tfoot>
                <tbody>

                    @if(count($wd) > 0 )
                        @foreach($wd as $dep)
                            <tr>
                                <td>
                                    <a title="Reject" href="/admin/reject/user/wd/{{$dep->id}}" >
                                        <span class=""><i class="fa fa-ban text-warning" ></i></span>
                                    </a>
                                    @if($adm->role == 3)
                                        <a title="Approve" href="/admin/approve/user/wd/{{$dep->id}}" >
                                            <span><i class="fa fa-check text-success"></i></span>
                                        </a>
                                        <a title="Delete" href="/admin/delete/user/wd/{{$dep->id}}" >
                                            <span class=""><i class="fa fa-times text-danger"></i></span>
                                        </a>
                                    @endif
                                </td>
                                <td>{{$dep->usn}}</td>
                                <td>{{$dep->currency}} {{$dep->amount}}</td>
                                <td><b>{{$dep->currency}} {{$dep->recieving}}</b></td>
                                <td>{{$dep->account}}</td>
                                <td>{{substr($dep->created_at, 0, 10)}}</td>
                                <td>{{$dep->status}}</td>
                            </tr>
                        @endforeach
                    @else

                    @endif
                </tbody>
            </table>
            {{$wd->links()}}

