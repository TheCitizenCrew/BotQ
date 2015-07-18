 @extends('layout') @section('title', 'Channel view')

@section('content')

<h1>Channel <button class="btn btn-default btn-xs" onclick="window.location = '{{ app('url')->route('ChannelEdit', ['id'=>$channel->id]) }}';">edit</button></h1>
<ul>
	<li>{{ $channel->label }}</li>
	<li>{{ $channel->description }}</li>
</ul>

<div>
Urgent message:
	<input type="text" id="urgentMessageText" size="32" placeholder="urgent message text" value="Ceci est un message urgent !" />
	<button onclick="sendUrgentMessage({{$channel->id}})">send</button>
</div>

<h2>Messages
	<button class="btn btn-default btn-xs" onclick="window.location.reload();">refresh</button>
	<button class="btn btn-default btn-xs" onclick="window.location='{{ app('url')->route('MessageNew', ['channelId'=>$channel->id]) }}';">new</button>
</h2>
<table class="table table-striped table-bordered">
	<tr>
		<th>Id</th>
		<th>Label</th>
		<td>priority</td>
		<td>priority action</td>
		<td>play loop</td>
		<td>play at time</td>
		<td>play duration (ms)</td>
		<td>content_type</td>
		<td>content</td>
		<td>status got</td>
		<td>status done</td>
		<td>status aborted</td>
		<td>actions</td>
	</tr>
	@foreach ($channel->messages->reverse() as $message)
	<tr>
 		<td>{{ $message->id }}</td>
		<td>{{ $message->label }}</td>
		<td>
			@if( $message->priority > 0 )
			<span class="label label-warning">{{ $message->priority }}</span>
			@else
			{{ $message->priority }}
			@endif
		</td>
		<td>{{ $message->priority_action }}</td>
		<td>
			@if( $message->play_loop == '1')
				@if( $message->priority > 0 )
					<span class="label label-danger">On</span>
				@else
					<span class="label label-warning">On</span>
				@endif
			@else
				Off
			@endif
		</td>
		<td>
    		@if( $message->play_at_time != '')
    			<span class="label label-warning">{{ $message->play_at_time }}</span>
    		@else
    			{{ $message->play_at_time }}
    		@endif
		</td>
		<td>
			@if( $message->content_type=='application/url' && $message->play_duration == '')
				<span class="label label-danger"> ? </span>
			@else
				{{ number_format($message->play_duration,0,'',' ') }}
			@endif
		</td>
		<td>{{ $message->content_type }}</td>
		<td>{{ $message->content }}</td>
		<td>{{ $message->status_got }}</td>
		<td>{{ $message->status_done }}</td>
		<td>{{ $message->status_aborted }}</td>
		<td><a
            href="{{ app('url')->route('MessageEdit', ['id'=>$message->id]) }}">edit</a>
            <br/>
            <button class="btn btn-default btn-xs" onclick="resetStatus({{$channel->id}},{{$message->id}})">clear status</button>
		</td>
	</tr>
	@endforeach
</table>

@stop

@section('javascript')
@parent
<!--script src="/js/jquery/jquery.min.js"></script-->
<script src="/js/require.js"></script>
<script>
require(['jquery'], function($) {
    //$('body').css('background-color', 'black');
});

function resetStatus(channelId, messageId)
{
	var url = '/api/messageStatus/'+channelId+'/'+messageId+'/reset' ;
	$.getJSON( url, function( data ) {
		window.location.reload();
	});
}

function sendUrgentMessage(channelId)
{
	var t = $('#urgentMessageText').val();
	var url = '/api/urgentTextMessage/'+channelId+'/'+encodeURI(t) ;
	$.getJSON( url, function( data ) {
		window.location.reload();
	});
}

</script>
@stop
