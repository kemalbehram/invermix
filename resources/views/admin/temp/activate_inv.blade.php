<div id="ActivateINV" class="container edit_pack_cont" >
  <div class="row wd_row_pad">
    <div class="col-md-4">&emps;</div>
    <div class="col-md-4" align="Center">
      <div class="card mt50">
        <div class="card-header card_header_bg_blue">
          <h2 class="text-white">Activar Inversión</h2>
        </div>
        <div id="" class="edit_pack_pad_n30_5 card-body">
          <form action="/admin/activate/user_inv" method="post">
              <input id="token" type="hidden" class="form-control" name="_token" value="">
              <div class="form-group">
                  <input id="id" type="hidden" class="form-control" name="id" value="">
              </div>

              <?php
              $companies = App\companies::where('status', '=', 1)->where('a_capital', '>', '0')->where('a_bonus', '>', '0')->orderBy('created_at', 'ASC')->get();
              ?>

              <div class="input-group">
                <div class="input-group-prepend " >
                  <span class="input-group-text "><i class=""></i>Compañía</span>
                </div>

                <select class="form-control" id="company" name="company">
                       @foreach($companies as $company)
                        <option value="{{ $company->id }}">{{ $company->name_comp }}</option>
                        @endforeach
                </select>

                <div class="input-group-append " >
                  <span class="input-group-text " ><i class=""></i></span>
                </div>
              </div>

              <br>

              <div class="input-group">
                <div class="input-group-prepend " >
                  <span class="input-group-text " ><i class=""></i>Usuario</span>
                </div>
                <input id="usn" type="text" class="form-control" name="usn" placeholder="Nombre de usuario" required readonly>
                <div class="input-group-append " >
                  <span class="input-group-text pack_edit_cur" ><i class=""></i></span>
                </div>
              </div>
              <br>
              <div class="input-group">
                <div class="input-group-prepend " >
                  <span class="input-group-text " ><i class=""></i>Modalidad</span>
                </div>
                <input id="package" type="text" name="package" class="form-control" name="package" placeholder="Capital de apertura" required readonly>
                <div class="input-group-append " >
                  <span class="input-group-text pack_edit_cur" ><i class=""></i></span>
                </div>
              </div>
              <br>
              <div class="input-group">
                <div class="input-group-prepend " >
                  <span class="input-group-text " ><i class=""></i>Capital</span>
                </div>
                <input id="capital" type="text" class="form-control" name="capital" placeholder="Capital Invertido" readonly>
                <div class="input-group-append " >
                  <span class="input-group-text pack_edit_cur" ><i class=""></i></span>
                </div>
              </div>
              <br>
              <div class="input-group">
                <div class="input-group-prepend " >
                  <span class="input-group-text "><i class=""></i>Status</span>
                </div>
                  <input id="status" type="text" class="form-control" name="status" placeholder="Status" required readonly>
                <div class="input-group-append " >
                  <span class="input-group-text " ><i class=""></i></span>
                </div>
              </div>
              <br>
              <div class="input-group">
                <div class="input-group-prepend " >
                  <span class="input-group-text "><i class=""></i>Moneda</span>
                </div>
                  <input id="currency" type="text" class="form-control" name="currency" placeholder="Moneda" required readonly>
                <div class="input-group-append " >
                  <span class="input-group-text " ><i class=""></i></span>
                </div>
              </div>
              <br>
              <div class="form-group">
                <br>
                  <button class="collb btn btn-info">Activar</button>
                  <span style="">
                    <a id="ActivateINV_close" href="javascript:void(0)" class="btn btn-danger">Cancelar</a>
                  </span>
                  <br>
              </div>
          </form>
        </div>
      </div>
      <!-- close btn -->
      <script type="text/javascript">
        $('#ActivateINV_close').click( function(){
          $('#ActivateINV').hide();
        });
      </script>
      <!-- end close btn -->

    </div>

  </div>

  <script type="text/javascript">
  function activate_inv(id, usn, package, capital, status, currency, token)
{
	$('#id').val(id);
	$('#usn').val(usn);
    $('#package').val(package);
	$('#capital').val(capital);
    $('#status').val(status);
    $('#currency').val(currency);
	$('#token').val(token);
	$('#ActivateINV').show();

}
</script>
</div>
