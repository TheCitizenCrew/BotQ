 @extends('layout') @section('title', 'Channel edit')

@section('content')

	@if(empty($channel->id))
	<h1>Créer un channel</h1>
	<form class="form-horizontal" method="POST" action="/channel">
	@else
	<h1>Modifier un channel</h1>
	<form class="form-horizontal" method="POST"
		action="/channel/{{$channel->id}}">
		@endif <input type="hidden" name="_token" id="csrf-token"
			value="{{ Session::token() }}" />

		<div class="form-group @if($errors->first('label'))has-error @endif">
			<label for="label" class="col-sm-1 control-label">Label</label>
			<div class="col-sm-6">
				@if ($errors->first('label'))
				<p class="text-danger">error {{$errors->first('label')}}</p>
				@endif <input type="text" class="form-control" name="label"
					id="label" placeholder="Nom du channel"
					value="{{ $channel->label }}" />
			</div>
		</div>
		<div
			class="form-group @if($errors->first('description'))has-error @endif">
			<label for="description" class="col-sm-1 control-label">Description</label>
			<div class="col-sm-6">
				@if ($errors->first('description'))
				<p class="text-danger">error {{$errors->first('description')}}</p>
				@endif
				<textarea class="form-control" name="description" id="description"
					placeholder="Description">{{ $channel->description }}</textarea>
			</div>
		</div>

		<br /> @if(empty($channel->id)) <a
			href="{{ app('url')->route('Home') }}" class="btn btn-warning">Annuler</a>
		@else <a
			href="{{ app('url')->route('ChannelGet', ['id'=>$channel->id]) }}"
			class="btn btn-warning">Annuler</a> @endif
		<button type="submit" class="btn btn-success">Enregistrer</button>

	</form>

	@stop @section('javascript') @parent
	<script>
	"use strict" ;

	</script>

	@stop