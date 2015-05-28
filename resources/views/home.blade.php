
@extends('layout')

@section('title', 'Welcome on BotQ')

@section('content')

<p class="bg-primary">La base de données contient <span id="messagesCount">...</span> messages pour <span id="channelsCount">...</span> channels.</p>

@stop

@section('javascript')
	@parent
	<script>
	"use strict" ;

	$(function() {
		$.getJSON( '/api/stats', function( data ) {
			$('#messagesCount').text( data.messagesCount );
			$('#channelsCount').text( data.channelsCount );
		});
	});
	</script>
@stop
