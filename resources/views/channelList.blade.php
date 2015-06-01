
@extends('layout')

@section('title', 'Channels List')

@section('content')

<h1>Channels</h1>
<table class="table table-striped table-bordered">
<tr>
    <th>Label</th>
    <th>Description</th>
</tr>
@foreach ($channels as $channel)
    <tr>
        <td><a href="{{ app('url')->route('ChannelGet', ['id'=>$channel->id]) }}">{{ $channel->label }}</a></td>
        <td>{{ $channel->description }}</a></td>
    </tr>
@endforeach
</table>

@stop

@section('javascript')
	@parent
	<script>
	"use strict" ;

	$(function() {
	});
	</script>

@stop
