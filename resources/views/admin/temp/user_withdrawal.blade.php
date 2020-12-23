            <table class="display table table-stripped table-hover">
                <thead>
                    <tr>
                        <th> {{ __('Acciones') }} </th>
                        <th> {{ __('Usuario') }} </th>
                        <th> {{ __('Capital') }} </th>
                        <th> {{ __('Monto pagable') }} </th>
                        <th> {{ __('Fecha') }} </th>
                        <th> {{ __('Status') }} </th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th> {{ __('Acciones') }} </th>
                        <th> {{ __('Usuario') }} </th>
                        <th> {{ __('Capital') }} </th>
                        <th> {{ __('Monto pagable') }} </th>
                        <th> {{ __('Fecha') }} </th>
                        <th> {{ __('Status') }} </th>
                    </tr>
                </tfoot>
                <tbody>

                                                 <?php
                                                    $inv = App\investment::where('wd_status', '!=',NULL)
                                                    ->orderby('created_at', 'desc')->get();
                                                ?>

                    @if(count($inv) > 0 )
                        @foreach($inv as $dep)
                            <tr>
                                <td>
                                    <a title="Rechazar" href="/admin/reject/user/wd/{{$dep->id}}" >
                                        <span class=""><i class="fa fa-ban text-warning" ></i></span>
                                    </a>
                                    &nbsp;
                                    &nbsp;
                                    @if($adm->role == 3 |$adm->role == 2 )
                                        <a title="Aprobar" href="/admin/approve/user/wd/{{$dep->id}}" >
                                            <span><i class="fa fa-check text-success"></i></span>
                                        </a>
                                        <br>

                                        <!-- <a title="Borrar" href="/admin/delete/user/wd/{{$dep->id}}" >
                                            <span class=""><i class="fa fa-times text-danger"></i></span>
                                        </a> -->
                                        <br>
                                    @endif
                                </td>

                                <td>{{$dep->usn}}</td>
                                <td>{{$dep->currency}} {{number_format($dep->capital), 2}}</td>
                                <td><b>{{$dep->currency}} {{number_format($dep->i_return), 2}}</b></td>
                                <td>{{date('d/m/Y', strtotime($dep->created_at))}}</td>
                                <td>{{$dep->wd_status}}</td>
                            </tr>
                        @endforeach
                    @else

                    @endif
                </tbody>
            </table>
            {{$wd->links()}}

            <br>
            <br>

            <h3>Retiro Clientes Inyecciones</h3>
            <table class="display table table-stripped table-hover">
                <thead>
                    <tr>
                        <th> {{ __('Acciones') }} </th>
                        <th> {{ __('Usuario') }} </th>
                        <th> {{ __('Capital') }} </th>
                        <th> {{ __('Monto pagable') }} </th>
                        <th> {{ __('Fecha') }} </th>
                        <th> {{ __('Status') }} </th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th> {{ __('Acciones') }} </th>
                        <th> {{ __('Usuario') }} </th>
                        <th> {{ __('Capital') }} </th>
                        <th> {{ __('Monto pagable') }} </th>
                        <th> {{ __('Fecha') }} </th>
                        <th> {{ __('Status') }} </th>
                    </tr>
                </tfoot>
                <tbody>

                                                 <?php
                                                    $inv = App\inyects::where('wd_status', '!=',NULL)
                                                    ->orderby('created_at', 'desc')->get();
                                                ?>

                    @if(count($inv) > 0 )
                        @foreach($inv as $dep)
                            <tr>
                                <td>
                                    <a title="Rechazar" href="/admin/reject/user/wd/inj/{{$dep->id}}" >
                                        <span class=""><i class="fa fa-ban text-warning" ></i></span>
                                    </a>
                                    &nbsp;
                                    &nbsp;
                                    @if($adm->role == 3 |$adm->role == 2 )
                                        <a title="Aprobar" href="/admin/approve/user/wd/inj/{{$dep->id}}" >
                                            <span><i class="fa fa-check text-success"></i></span>
                                        </a>
                                        <br>

                                        <!-- <a title="Borrar" href="/admin/delete/user/wd/{{$dep->id}}" >
                                            <span class=""><i class="fa fa-times text-danger"></i></span>
                                        </a> -->
                                        <br>
                                    @endif
                                </td>

                                <td>{{$dep->usn}}</td>
                                <td>{{$dep->currency}} {{number_format($dep->capital), 2}}</td>
                                <td><b>{{$dep->currency}} {{number_format($dep->i_return), 2}}</b></td>
                                <td>{{substr($dep->created_at, 0, 10)}}</td>
                                <td>{{$dep->wd_status}}</td>
                            </tr>
                        @endforeach
                    @else

                    @endif
                </tbody>
            </table>
            {{$wd->links()}}
