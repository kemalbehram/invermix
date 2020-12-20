<div id="packEdit" class="container edit_pack_cont" >
  <div class="row wd_row_pad">
    <div class="col-md-4">&emps;</div>
    <div class="col-md-4" align="Center">
      <div class="card mt50">
        <div class="card-header card_header_bg_blue">
          <h2 class="text-white">Actualizar Plan</h2>
        </div>
        <div id="" class="edit_pack_pad_n30_5 card-body">
          <form action="/admin/edit/packages" method="post">
              <input id="token" type="hidden" class="form-control" name="_token" value="">
              <div class="form-group">
                  <input id="p_id" type="hidden" class="form-control" name="p_id" value="">
              </div>
              <div class="input-group">
                <div class="input-group-prepend " >
                  <span class="input-group-text " ><i class=""></i>Min. RD$</span>
                </div>
                <input id="min" type="text" class="form-control" name="min" placeholder="Coloca monto mínimo para invertir en pesos dominicanos" required>
              </div>
              <br>
              <div class="input-group">
                <div class="input-group-prepend " >
                  <span class="input-group-text "><i class=""></i>Max. RD$</span>
                </div>
                <input id="max" type="text" class="form-control" name="max" placeholder="Coloca monto máximo para invertir en pesos dominicanos" required>
  
              </div>
              <br>
              <div class="input-group">
                <div class="input-group-prepend " >
                  <span class="input-group-text " ><i class=""></i>Min. US$</span>
                </div>
                <input id="mindol" type="text" class="form-control" name="mindol" placeholder="Coloca monto mínimo para invertir en dólares estadounidentes" required>
              </div>
              <br>
              <div class="input-group">
                <div class="input-group-prepend " >
                  <span class="input-group-text "><i class=""></i>Max. US$</span>
                </div>
                <input id="maxdol" type="text" class="form-control" name="maxdol" placeholder="Coloca monto máximo para invertir en dólares estadounidentes" required>
      
              </div>
              <br>
              <div class="input-group">
                <div class="input-group-prepend " >
                  <span class="input-group-text "><i class=""></i>Interés</span>
                </div>
                  <input id="interest" type="text" class="form-control" name="interest" placeholder="Intereses para la inversión" required>
                <div class="input-group-append " >
                  <span class="input-group-text " ><i class="fa fa-percent"></i></span>
                </div>
              </div>
              <br>
              <div class="form-group">
                <br>
                  <button class="collb btn btn-info">Actualizar</button>
                  <span style="">
                    <a id="pack_edit_close" href="javascript:void(0)" class="btn btn-danger">Cancelar</a>
                  </span>
                  <br>
              </div>
          </form>
        </div>
      </div>
      <!-- close btn -->
      <script type="text/javascript">
        $('#pack_edit_close').click( function(){
          $('#packEdit').hide();
        });
      </script>
      <!-- end close btn -->

    </div>

  </div>

</div>
