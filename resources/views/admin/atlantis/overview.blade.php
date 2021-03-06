<div class="row mt--2">
	<div class="col-md-6">
		<div class="card full-height">
			<div class="card-body">
				<div class="card-title">Resumen estadístico</div>
				<div class="card-category">Estadísticas del sistema</div>
				<div class="d-flex flex-wrap justify-content-around pb-2 pt-4">
					<div class="px-2 pb-2 pb-md-0 text-center">
						<div id="circles-1"></div>
						<h6 class="fw-bold mt-3 mb-0">Clientes</h6>
            <span>Inactivo: {{count($users->where('status', '!=', '1'))}}</span>
					</div>
					<div class="px-2 pb-2 pb-md-0 text-center">
            <?php
                $inv = App\investment::orderby('id', 'desc')->get();
                $cap = 0;
                $cap2 = 0;
            ?>


						<div id="circles-2"></div>
						<h6 class="fw-bold mt-3 mb-0">Inversiones</h6>
            <span>Inactiva: {{count($inv->where('status', '=', 'Pendiente'))}}</span>
					</div>
					<div class="px-2 pb-2 pb-md-0 text-center">
            <?php
                $deposits = App\inyects::orderby('id', 'desc')->get();
                $dep = 0;
                $dep2 = 0;
            ?>
						<div id="circles-3"></div>
						<h6 class="fw-bold mt-3 mb-0">Inyecciones</h6>
            			<span>Inactiva: {{count($deposits->where('status', '=', 'Pendiente'))}}</span>
					</div>
				</div>
			</div>
		</div>
	</div>

  @foreach($inv as $in)
    @php($cap = $cap + intval($in->capital) )
  @endforeach

 <?php
 $deposits = App\inyects::orderby('id', 'desc')->get();
 ?>
  @foreach($deposits as $in)
    @php($dep += $in->capital)
  @endforeach

 <?php
 $wd = App\investment::where('wd_status', 'Depositado')-> orderby('id', 'desc')->get();
 ?>
 
  @foreach($wd as $in)
    @php($wd_bal += $in->w_amt )
  @endforeach

	<div class="col-md-6">
		<div class="card full-height">
			<div class="card-body">
				<div class="card-title"><h2>Resumen de saldo</h2></div>
				<div class="row py-3 @if($adm->role < 2) {{blur_cnt}}@endif" style="position: relative;">
					<div class="col-md-6 d-flex flex-column justify-content-around">
						<div style="border-bottom: 1px solid #CCC;">

						<?php
               			 $invrd = DB::table("invest")
							->where('currency', '=', 'RD$')
							->value(DB::raw("SUM(capital)"))
            			?>
							<h4 class="fw-bold text-uppercase text-success op-8">Inversiones RD$</h4>
							<h3 class="fw-bold">${{ number_format($invrd),2}}</h3>
							<div class="colhd" style="font-size: 10px; margin-top: -10px;">&emsp;</div>
							<br>
						</div>

					  <div class="clearfix"><br></div>

					  <div style="border-bottom: 1px solid #CCC;">
						<?php
							$deprd = DB::table("inyects")
							->where('currency', '=', 'RD$')
							->value(DB::raw("SUM(capital)"))
         			    ?>
							<h4 class="fw-bold text-uppercase text-success op-8">Inyecciones RD$</h4>
							<h3 class="fw-bold">${{ number_format($deprd),2}}</h3>
							<div class="colhd" style="font-size: 10px; margin-top: -10px;">&emsp;</div>
							<br>
						</div>

			
						<br>
						<!-- <div>
							<h4 class="fw-bold text-uppercase text-success op-8">Retiros RD$</h4>
							<h3 class="fw-bold">$ {{number_format($wd_bal)}}</h3>
							<div class="colhd" style="font-size: 10px; margin-top: -10px;">&emsp;</div>
							<br>
						</div> -->
					</div>

					<div class="col-md-6">
					<?php
               			 $invs = DB::table("invest")
							->where('currency', '=', 'US$')
							->value(DB::raw("SUM(capital)"))
          		      ?>
						<div style="border-bottom: 1px solid #CCC;">
						
							<h4 class="fw-bold text-uppercase text-success op-8">Inversiones USD$</h4>
							<h3 class="fw-bold">${{ number_format($invs),2}}</h3>
						
							<div class="colhd" style="font-size: 10px; margin-top: -10px;">&emsp;</div>
							<br>
						</div>
						<br>
						<div style="border-bottom: 1px solid #CCC;">
						<?php
							$depus = DB::table("inyects")
							->where('currency', '=', 'US$')
							->value(DB::raw("SUM(capital)"))
         			    ?>
							<h4 class="fw-bold text-uppercase text-success op-8">Inyecciones USD$</h4>
							<h3 class="fw-bold">${{ number_format($depus),2}}</h3>
							<div class="colhd" style="font-size: 10px; margin-top: -10px;">&emsp;</div>
							<br>
						</div>

						<br>
						<!-- <div>
							<h4 class="fw-bold text-uppercase text-success op-8">Retiros USD$</h4>
							<h3 class="fw-bold">$ {{number_format($wd_bal)}}</h3>
							<div class="colhd" style="font-size: 10px; margin-top: -10px;">&emsp;</div>
							<br>
						</div> -->
					</div>
					
				</div>
			</div>
		</div>
	</div>
</div>
