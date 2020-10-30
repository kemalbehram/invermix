<table class="display table table-stripped table-hover">
    <thead>
        <tr>
           <th> {{ __('Nombre') }} </th>
           <th> {{ __('RNC') }} </th>
           <th> {{ __('Capital Apertura') }} </th>
           <th> {{ __('Capital Disponible') }} </th>
           <th> {{ __('Bonos Vendido') }} </th>
           <th> {{ __('Bonos Disponible') }} </th>
           <th> {{ __('Costo  Bono') }} </th>
           <th> {{ __('Moneda') }} </th>
        </tr>
    </thead>
    <tbody>

        @if(count($comps) > 0 )
            @foreach($comps as $dep)
                <tr>
                    <td>{{$dep->name_comp}}</td>
                    <td>{{$dep->rnc}}</td>
                    <td>{{$dep->o_capital}}</td>
                    <td>{{$dep->a_capital}}</td>
                    <td>{{$dep->sold_bonus}}</td>
                    <td>{{$dep->a_bonus}}</td>
                    <td>{{$dep->bonus_cost}}</td>
                    <td>{{$dep->currency}}</td>
                    <td>
                      <label class="switch" >
                        <input type="checkbox" @if($dep->status == 1){{'checked'}}@endif>
                        <span id="switch_pack{{$dep->id}}" class="slider round" onclick="act_deact_pack('{{$dep->id}}')"></span>
                      </label>
                    </td>

                    <td>
                        @if($adm->role == 3 || $adm->role == 2)
                            <a id="{{$dep->id}}" title="Editar Compañía" href="javascript:void(0)" onclick="edit_pack(this.id, '{{$dep->min}}', '{{$dep->max}}', '{{$dep->daily_interest*$dep->period*100}}', '{{$dep->withdrwal_fee}}', '{{csrf_token()}}', '{{$dep->currency}}')">
                                <span><i class="fa fa-edit btn btn-warning"></i></span>
                            </a>
                            <a id="{{$dep->id}}" title="Borrar Compañía" href="javascript:void(0)" onclick="load_get_ajax('/admin/delete/company/{{$dep->id}}', this.id, 'admDeleteMsg') ">
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
