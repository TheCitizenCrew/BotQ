
@extends('layout')

@section('title', 'Channel view')

@section('content')

<h1>Channel</h1>
<ul>
<li> {{ $channel->label }} </li>
<li> {{ $channel->description }} </li>
</ul>

@stop

@section('javascript')
	@parent
	<script>
	"use strict" ;

	$(function() {
	});
	</script>

@stop
