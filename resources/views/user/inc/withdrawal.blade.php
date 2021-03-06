<div id="div_withdrawal" class="container popmsgContainer" >
    <div class="row wd_row_pad" >
      <div class="col-md-4">&emps;</div>
      <div class="col-md-4 card popmsg-mobile pop_invest_col" align="Center">
        <div class="card-header" style="">
          <h3> {{ __('Retiro') }} </h3>
          <h5>{{ __('Ganancia Total:') }} $ <span id="earned"></span></h5>
          <small>Días: <span id="days" class="text-danger" ></span></small>
        </div>
        <div class="card-body pt-0" >

          <form id="wd_formssss" action="/user/wdraw/earning" method="post">
              <div class="form-group" align="left">
                  <input type="hidden" class="form-control" name="_token" value="{{csrf_token()}}">
                  <input id="id" type="hidden" class="form-control" name="p_id" value="">
                  <input id="ended" type="hidden" class="form-control" name="ended" value="">
              </div>
              <div align="left">
                <label>{{ __('Monto a retirar') }} </label>
              </div>
              <div class="input-group">
                <div class="input-group-prepend " >
                  <span class="input-group-text "  > $</span>
                </div>
                <input id="withdrawable_amt" type="text" value="" readonly class="bg_white form-control" name="amt"  required >
              </div>
              <div class="form-group">
                <br><br>
                  <button class="btn btn-info"> {{ __('Retirar') }} </button>
                  <span style="">
                    <a id="div_withdrawal_close" href="javascript:void(0)" class="btn btn-danger"> {{ __('Cancelar') }} </a>
                  </span>
                  <br>
              </div>
          </form>
        </div>
        <!-- close btn -->
        <script type="text/javascript">
          $('#div_withdrawal_close').click( function(){
            $('#div_withdrawal').hide();
          });
        </script>
        <!-- end close btn -->
      </div>

    </div>
  </div>
