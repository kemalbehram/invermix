<div class="panel-header" style="background-color: {{$settings->header_color}}">
	<div class="page-inner py-5" style="background-color: {{$settings->header_color}}">
		<div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
			<div>
				<h2 class="text-white pb-2 fw-bold">
					¡Hola! Bienvenido, {{ucfirst($adm->name)}}
				</h2>
				<p class="text-white">{{str_replace('/', ' > ', ucfirst(Request::path())) }}</p>
			</div>
			<div class="ml-md-auto py-2 py-md-0">
				@php($role = Session::get('adm'))
                @if($role->role == 3 || $role->role == 2)
					<a href="/admin/manage/investments" class="btn btn-white btn-border btn-round mr-2">Inversiones</a>
					<a href="/admin/manage/injects" class="btn btn-secondary btn-round">Inyecciones</a>
				@endif
			</div>
		</div>
	</div>
</div>
