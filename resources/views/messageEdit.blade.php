
@extends('layout')

@section('title', 'Message edit')

@section('content')

@if(empty($message->id))
    <h2>Créer un message</h2>
    <form class="form-horizontal" method="POST" action="/channel">
@else
    <h2>Modifier un message</h2>
    <form class="form-horizontal" method="POST" action="/channel/{{$message->id}}">
@endif
    <input type="hidden" name="_token" id="csrf-token" value="{{ Session::token() }}" />
    <input type="hidden" name="channel_id" id="channel_id" value="{{ $message->channel_id }}" />

    <div class="form-group">
        <label for="label" class="col-sm-1 control-label">Channel</label>
        <div class="col-sm-6">
            <input class="form-control" type="text" placeholder="{{ $message->channel->label }}" readonly />
        </div>
    </div>

    <div class="form-group @if($errors->first('label'))has-error @endif">
    	<label for="label" class="col-sm-1 control-label">Label</label>
    	<div class="col-sm-6">
    		@if ($errors->first('label'))
    		<p class="text-danger">error {{$errors->first('label')}}</p>
    		@endif
    		<input type="text" class="form-control" name="label"
    			id="label" placeholder="Nom du message"
    			value="{{$message->label}}" />
    	</div>
    </div>

    <div class="form-group @if($errors->first('priority'))has-error @endif">
    	<label for="priority" class="col-sm-1 control-label">Priorité</label>
    	<div class="col-sm-2">
    		@if ($errors->first('priority'))
    		<p class="text-danger">error {{$errors->first('priority')}}</p>
    		@endif
    		<input type="number" class="form-control" name="priority"
    			id="priority" placeholder="Priorité du message"
    			value="{{$message->priority}}" />
    	</div>
    </div>

    <div class="form-group @if($errors->first('priority_action'))has-error @endif">
    	<label for="priority_action" class="col-sm-1 control-label">priority_action</label>
    	<div class="col-sm-6">
    		@if ($errors->first('priority_action'))
    		<p class="text-danger">error {{$errors->first('priority_action')}}</p>
    		@endif
    		<label class="radio-inline">
          <input type="radio" name="priority_action" id="priority_action1" value="pause" @if($message->priority_action=='pause') checked @endif /> pause
        </label>
        <label class="radio-inline">
          <input type="radio" name="priority_action" id="priority_action2" value="stop" @if($message->priority_action=='stop') checked @endif /> stop
        </label>
        <label class="radio-inline">
          <input type="radio" name="priority_action" id="priority_action3" value="simult" @if($message->priority_action=='simult') checked @endif /> simult
        </label>
    	</div>
    </div>

    <div class="form-group @if($errors->first('play_loop'))has-error @endif">
    	<label for="play_loop" class="col-sm-1 control-label">play_loop</label>
    	<div class="col-sm-6">
    		@if ($errors->first('play_loop'))
    		<p class="text-danger">error {{$errors->first('play_loop')}}</p>
    		@endif
    		<label class="radio-inline">
          <input type="radio" name="play_loop" id="play_loop1" value="1" @if($message->play_loop=='1') checked @endif /> On
        </label>
        <label class="radio-inline">
          <input type="radio" name="play_loop" id="play_loop2" value="0" @if($message->play_loop=='0') checked @endif /> Off
        </label>
    	</div>
    </div>

    <div class="form-group @if($errors->first('play_at_time'))has-error @endif">
    	<label for="play_at_time" class="col-sm-1 control-label">play_at_time</label>
    	<div class="col-sm-2">
    		@if ($errors->first('play_at_time'))<p class="text-danger">error {{$errors->first('play_at_time')}}</p>@endif
    		<input type="datetime" class="form-control" name="play_at_time" id="play_at_time" placeholder="play_at_time" value="{{$message->play_at_time}}" />
    	</div>
    </div>

    <div class="form-group @if($errors->first('content_type'))has-error @endif">
    	<label for="content_type" class="col-sm-1 control-label">content_type</label>
    	<div class="col-sm-2">
    		@if ($errors->first('content_type'))<p class="text-danger">error {{$errors->first('content_type')}}</p>@endif
    		<input type="text" class="form-control" name="content_type" id="content_type" placeholder="content_type" value="{{$message->content_type}}" />
    	</div>
    </div>

    <div class="form-group @if($errors->first('content'))has-error @endif">
			<label for="content" class="col-sm-1 control-label">content</label>
			<div class="col-sm-6">
				@if ($errors->first('content'))<p class="text-danger">error {{$errors->first('content')}}</p>@endif
				<textarea class="form-control" name="content" id="content" placeholder="content" >{{ $message->content }}</textarea>
			</div>
		</div>

    <div class="form-group">
        <label for="label" class="col-sm-1 control-label">status_got</label>
        <div class="col-sm-6">
            <input class="form-control" type="datetime" placeholder="{{ $message->status_got }}" readonly />
        </div>
    </div>
    <div class="form-group">
        <label for="status_done" class="col-sm-1 control-label">status_done</label>
        <div class="col-sm-6">
            <input class="form-control" type="datetime" placeholder="{{ $message->status_done }}" readonly />
        </div>
    </div>
    <div class="form-group">
        <label for="status_aborted" class="col-sm-1 control-label">status_aborted</label>
        <div class="col-sm-6">
            <input class="form-control" type="datetime" placeholder="{{ $message->status_aborted }}" readonly />
        </div>
    </div>

</form>

@stop

@section('javascript')
	@parent
	<script>
	"use strict" ;

	$(function() {
	});
	</script>

@stop
