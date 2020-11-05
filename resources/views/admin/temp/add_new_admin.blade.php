<div class="sparkline9-list shadow-reset mg-tb-30">
    <div class="sparkline9-graph dashone-comment">
        <div class="datatable-dashv1-list custom-datatable-overright dashtwo-project-list-data">
            <form action="/admin/addNew/admin" method="post">
              <input id="token" type="hidden" class="form-control" name="_token" value="{{csrf_token()}}">

              <div class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text " ><i class="fa fa-user"></i></span>
                </div>
                  <input type="text" class="form-control" name="Name" placeholder="Ingrese el nombre del personal" required>
              </div>
              <br>
              <div class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text "><i class="fa fa-envelope"></i></span>
                </div>
                  <input id="" type="email" class="form-control" name="email" placeholder="Ingrese la dirección de correo electrónico del personal" required>
              </div>
              <br>
              <div class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text "><i class="fa fa-key"></i></span>
                </div>
                  <input id="" type="password" class="form-control" name="pwd" placeholder="Introducir la contraseña" required>
              </div>
              <br>
              <div class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text "><i class="fa fa-user"></i></span>
                </div>

                  <select name="role" class="form-control" required>
                      <option selected disabled="">Seleccionar rol</option>
                      <option value="1">Soporte</option>
                      <option value="2">Mánager</option>
                      <option value="3">Admin</option>
                  </select>
              </div>
              <br>

              <div class="form-group">
                <br>
                  <button class="collb btn btn-info">{{ __('Añadir Admin') }}</button>
                  <br>
              </div>
            </form>
        </div>
    </div>
</div>
