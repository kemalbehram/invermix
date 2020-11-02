<div id="packEdit" class="container edit_pack_cont" >
  <div class="row wd_row_pad">
    <div class="col-md-4">&emps;</div>
    <div class="col-md-4" align="Center">
      <div class="card mt50">
        <div class="card-header card_header_bg_blue">
          <h2 class="text-white">Actualizar Compañía</h2>
        </div>
        <div id="" class="edit_pack_pad_n30_5 card-body">
          <form action="/admin/edit/company" method="post">
              <input id="token" type="hidden" class="form-control" name="_token" value="">
              <div class="form-group">
                  <input id="id" type="hidden" class="form-control" name="id" value="">
              </div>
              <div class="input-group">
                <div class="input-group-prepend " >
                  <span class="input-group-text " ><i class=""></i>Nombre</span>
                </div>
                <input id="name_comp" type="text" class="form-control" name="name_comp" placeholder="Nombre compañía" required>
                <div class="input-group-append " >
                  <span class="input-group-text pack_edit_cur" ><i class=""></i></span>
                </div>
              </div>
              <br>
              <div class="input-group">
                <div class="input-group-prepend " >
                  <span class="input-group-text " ><i class=""></i>RNC</span>
                </div>
                <input id="rnc" type="text" class="form-control" name="rnc" placeholder="RNC compañía" required>
                <div class="input-group-append " >
                  <span class="input-group-text pack_edit_cur" ><i class=""></i></span>
                </div>
              </div>
              <br>

              <div class="input-group">
                <div class="input-group-prepend " >
                  <span class="input-group-text " ><i class=""></i>Correo</span>
                </div>
                <input id="email" type="text" class="form-control" name="email" placeholder="Correo electrónico" required>
                <div class="input-group-append " >
                  <span class="input-group-text pack_edit_cur" ><i class=""></i></span>
                </div>
              </div>
              <br>
              <div class="input-group">
                <div class="input-group-prepend " >
                  <span class="input-group-text " ><i class=""></i>Capital Apertura</span>
                </div>
                <input id="o_capital" type="text" class="form-control" name="o_capital" placeholder="Capital de apertura" required readonly>
                <div class="input-group-append " >
                  <span class="input-group-text pack_edit_cur" ><i class=""></i></span>
                </div>
              </div>
              <br>
              <div class="input-group">
                <div class="input-group-prepend " >
                  <span class="input-group-text " ><i class=""></i>Capital Disponible</span>
                </div>
                <input id="a_capital" type="text" class="form-control" name="a_capital" placeholder="Capital disponible" required readonly>
                <div class="input-group-append " >
                  <span class="input-group-text pack_edit_cur" ><i class=""></i></span>
                </div>
              </div>
              <br>
              <div class="input-group">
                <div class="input-group-prepend " >
                  <span class="input-group-text " ><i class=""></i>Bonos Aperturados</span>
                </div>
                <input id="bonus_open" type="text" class="form-control" name="bonus_open" placeholder="Bonos Aperturados" readonly>
                <div class="input-group-append " >
                  <span class="input-group-text pack_edit_cur" ><i class=""></i></span>
                </div>

              </div>
              <br>
              <div class="input-group">
                <div class="input-group-prepend " >
                  <span class="input-group-text " ><i class=""></i>Bonos Vendidos</span>
                </div>
                <input id="sold_bonus" type="text" class="form-control" name="sold_bonus" placeholder="Bonos vendidos" readonly>
                <div class="input-group-append " >
                  <span class="input-group-text pack_edit_cur" ><i class=""></i></span>
                </div>
              </div>
              <br>
              <div class="input-group">
                <div class="input-group-prepend " >
                  <span class="input-group-text "><i class=""></i>Bonos Disponibles</span>
                </div>
                <input id="a_bonus" type="text" class="form-control" name="a_bonus" placeholder="Bonos disponibles" required readonly>
                <div class="input-group-append " >
                  <span class="input-group-text pack_edit_cur" ><i class=""></i></span>
                </div>
              </div>
              <br>
              <div class="input-group">
                <div class="input-group-prepend " >
                  <span class="input-group-text "><i class=""></i>Costo Bono</span>
                </div>
                  <input id="bonus_cost" type="text" class="form-control" name="bonus_cost" placeholder="Costo del bono de la compañía" required>
                <div class="input-group-append " >
                  <span class="input-group-text " ><i class=""></i></span>
                </div>
              </div>
              <br>
              <div class="input-group">
                <div class="input-group-prepend " >
                  <span class="input-group-text "><i class=""></i>Moneda</span>
                </div>
                <select class="form-control" id="currency" name="currency">
                                        <option value="RD$" name="currency">RD$</option>
                                        <option value="US$" name="currency">US$</option>
                                        </select>
                <div class="input-group-append " >
                  <span class="input-group-text " ><i class=""></i></span>
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
