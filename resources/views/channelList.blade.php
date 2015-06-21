
@extends('layout')

@section('title', 'Channels List')

@section('content')

<h1>Channels</h1>
<table class="table table-striped table-bordered">
<tr>
    <th>Label</th>
    <th>Description</th>
    <th>Msgs count</th>
</tr>
@foreach ($channels as $channel)
    <tr>
        <td><a href="{{ app('url')->route('ChannelGet', ['id'=>$channel->id]) }}">{{ $channel->label }}</a></td>
        <td>{{ $channel->description }}</td>
        <td>{{ $channel->messagesCount }}</td>
    </tr>
@endforeach
</table>

<a href="{{ app('url')->route('ChannelNew') }}">Nouveau</a>

@stop

@section('javascript')
	@parent
	<script>
	"use strict" ;

	</script>

@stop
