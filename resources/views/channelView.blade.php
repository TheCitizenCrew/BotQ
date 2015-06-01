
@extends('layout')

@section('title', 'Channel view')

@section('content')

<h1>Channel</h1>
<ul>
<li> {{ $channel->label }} </li>
<li> {{ $channel->description }} </li>
</ul>

<a href="{{ app('url')->route('ChannelEdit', ['id'=>$channel->id]) }}">Edit</a>

<h2>Messages</h2>
<table class="table table-striped table-bordered">
<tr>
    <th>Label</th>
    <td>priority</td>
    <td>concurentAction</td>
    <td>playloop</td>
    <td>content_type</td>
    <td>content</td>
    <td>status_got</td>
    <td>status_done</td>
    <td>status_aborted</td>
</tr>
@foreach ($channel->messages as $message)
    <tr>
        <td>{{ $message->label }}</td>
        <td>{{ $message->priority }}</td>
        <td>{{ $message->concurentAction }}</td>
        <td>{{ $message->playloop }}</td>
        <td>{{ $message->content_type }}</td>
        <td>{{ $message->content }}</td>
        <td>{{ $message->status_got }}</td>
        <td>{{ $message->status_done }}</td>
        <td>{{ $message->status_aborted }}</td>
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
