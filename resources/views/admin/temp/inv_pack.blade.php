
            <table class="display table table-stripped table-hover">
                <thead>
                    <tr>
                       <th> {{ __('Nombre') }} </th>
                       <th> {{ __('Min. RD$') }} </th>
                       <th> {{ __('Max. RD$') }} </th>
                       <th> {{ __('Min. US$') }} </th>
                       <th> {{ __('Max. US$') }} </th>
                       <th> {{ __('Interés (%)') }} </th>
                       <th> {{ __('Duración') }} </th>
                       <th> {{ __('Intervalo de Retiro') }} </th>
                       <th> {{ __('On/Off') }} </th>
                       <th> {{ __('Manejar') }} </th>
                    </tr>
                </thead>
                <tbody>

                    @if(count($packs) > 0 )
                        @foreach($packs as $dep)
                            <tr>
                                <td>{{$dep->package_name}}</td>
                                <td>{{number_format($dep->min)}}</td>
                                <td>{{number_format($dep->max)}}</td>
                                <td>{{number_format($dep->mindol)}}</td>
                                <td>{{number_format($dep->maxdol)}}</td>
                                <td>{{$dep->daily_interest*$dep->period*100}}</td>
                                <td>{{$dep->period}}</td>
                                <td>{{$dep->days_interval}}</td>
                                <td>
                                  <label class="switch" >
                                    <input type="checkbox" @if($dep->status == 1){{'checked'}}@endif>
                                    <span id="switch_pack{{$dep->id}}" class="slider round" onclick="act_deact_pack('{{$dep->id}}')"></span>
                                  </label>
                                </td>

                                <td>
                                    @if($adm->role == 3 || $adm->role == 2)
                                        <a id="{{$dep->id}}" title="Edit Package" href="javascript:void(0)" onclick="edit_pack(this.id, '{{$dep->min}}', '{{$dep->max}}', '{{$dep->daily_interest*$dep->period*100}}', '{{$dep->withdrwal_fee}}', '{{csrf_token()}}', '{{$dep->currency}}')">
                                            <span><i class="fa fa-edit btn btn-warning"></i></span>
                                        </a>
                                        <a id="{{$dep->id}}" title="Delete Package" href="javascript:void(0)" onclick="load_get_ajax('/admin/delete/pack/{{$dep->id}}', this.id, 'admDeleteMsg') ">
                                            <span><i class="fa fa-times btn btn-danger"></i></span>
                                        </a>

                                    @endif
                                </td>

                            </tr>
                        @endforeach
                    @else

                    @endif
                </tbody>
            </table>
