<div id="PopInyect" class="container pop_invest_cont" >
  <div class="row wd_row_pad" >
    <div class="col-md-4">&emps;</div>
    <div class="col-md-4 card pop_invest_col" align="center">
      <div class="card-header" style="">
        <h3><b>{{ __('Inversión Inicial') }}</b></h3>
        {{-- <h5>{{ __('Wallet Balance:') }} <b>{{$settings->currency}} <span id="WalletBal"></span></b></h5> --}}
        <hr>
      </div>
      <div class="pop_msg_contnt">
        <p align="center" class="color_blue_b">
            {{ __('Estás a punto de invertir en la modalidad ') }} <b><span id="pack_inv"></span></b>{{ __(', cual tiene un período de') }}  <b><span id="period"></span></b>{{ __(' días laborables y tiene un interés total de ') }}  <b><span id="intr"></span></b>%.
        </p>
        <form id="userpackinv" action="/user/invest/packages" method="post">
            <div class="form-group" align="left">
              <div class="pop_form_min_max" align="center">
                <b>{{ __('Inversión Min.:') }} {{$settings->currency}} <span id="inv_id"></span></b>

              </div>
              <br>
              <label>{{ __('Colocar Monto a Invertir') }}</label>
              <input type="hidden" class="form-control" name="_token" value="{{csrf_token()}}">
              <input id="p_id" type="hidden" class="form-control" name="p_id" value="">
              <input type="text" class="form-control" name="capital" placeholder="Digitar capital a invertir" required>
            </div>
            <div class="form-group">
                <button class="collb btn btn-info">{{ __('Proceder') }}</button>
                <span style="">
                  <a id="PopInyect_close" href="javascript:void(0)" class="btn btn-danger">{{ __('Cancelar') }}</a>
                </span>
                <br><br>
            </div>
        </form>

      </div>
      <!-- close btn -->
      <script type="text/javascript">
        $('#PopInyect_close').click( function(){
          $('#PopInyect').hide();
        });
      </script>
      <!-- end close btn -->
    </div>
  </div>
</div>
