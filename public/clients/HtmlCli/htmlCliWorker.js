/**
 * htmlCliWorker.js
 * 
 * Documentation:
 * 
 * The Basics of Web Workers
 * http://www.html5rocks.com/en/tutorials/workers/basics/
 * 
 */
"use strict";

importScripts('htmlCliCommon.js');

var botQChannel = 1;
var botQPullFreq = 1000 * 4;
var botQPullTimer = null;

var messageCurrent = null;
var messageNext = null;

self.addEventListener('message', function(e) {
	var data = e.data;
	switch (data.cmd) {

	case 'say':
		self.postMessage(data.msg);
		break;

	case 'messageNext':
		
		messageCurrent = messageNext ;
		messageNext = null ;
		// Send now to htmlClient
		tinyxhr('http://botq.localhost/api/messageGot/' + botQChannel + '/'
				+ messageCurrent.id, onXhrResponseMessageGot, 'GET', null,
				'application/javascript');
		self.postMessage(messageCurrent);

		//data.message
		/*data.status
		tinyxhr('http://botq.localhost/api/messageDone/' + botQChannel + '/'
				+ messageCurrent.id, onXhrResponseMessageGot, 'GET', null,
				'application/javascript');*/
		break;
	case 'messageAborted':
		/*tinyxhr('http://botq.localhost/api/messageAborted/' + botQChannel + '/'
				+ messageCurrent.id, onXhrResponseMessageGot, 'GET', null,
				'application/javascript');*/
		break;

	case 'start':
		if (botQPullTimer == null) {
			botQPullTimer = setTimeout(pulse, 100);
		}
		break;

	case 'stop':
		if (!botQPullTimer == null) {
			clearTimeout(botQPullTimer);
			botQPullTimer = null;
		}
	}
	;
}, false);

function pulse() {

	tinyxhr('http://botq.localhost/api/messagesSet/' + botQChannel,
			onXhrResponse, 'GET', null, 'application/javascript');

	botQPullTimer = setTimeout(pulse, botQPullFreq);
}

function onXhrResponse(err, data, xhr) {

	console.log('messageCurrent '+(messageCurrent?messageCurrent.id:'null'));
	console.log('messageNext '+(messageNext?messageNext.id:'null'));

	if (err) {
		console.log("goterr ", err, 'status=' + xhr.status);
		return;
	}

	// console.log(data);
	var json = JSON.parse(data);
	console.log(json);

	if (json.length == 0) {
		messageNext = null;
		return;
	}

	try {

		if (messageCurrent == null) {

			messageCurrent = createMessageFromBotQMessage(json[0]);
if( messageCurrent instanceof VideoMessage ){
	console.log('messageCurrent is a VideoMessage' )
}else{
	console.log('messageCurrent is NOT a VideoMessage' )
}

			if (json.length == 2)
				messageNext = createMessageFromBotQMessage(json[1]);

			// Send now to htmlClient
			tinyxhr('http://botq.localhost/api/messageGot/' + botQChannel + '/'
					+ messageCurrent.id, onXhrResponseMessageGot, 'GET', null,
					'application/javascript');
			self.postMessage(messageCurrent);

		} else if (json[0].id == messageCurrent.id) {

			if (json.length == 2) {
				if (messageNext == null) {
					messageNext = createMessageFromBotQMessage(json[1]);
				} else if (json[1].id != messageNext.id) {
					messageNext = createMessageFromBotQMessage(json[1]);
				}
			}

		} else if (json[0].priority > messageCurrent.priority) {

			// new message with higher priority
			messageCurrent = createMessageFromBotQMessage(json[0]);

			// Send now to htmlClient
			tinyxhr('http://botq.localhost/api/messageGot/' + botQChannel + '/'
					+ messageCurrent.id, onXhrResponseMessageGot, 'GET', null,
					'application/javascript');
			self.postMessage(messageCurrent);

		} else {

			if (messageNext == null) {
				messageNext = createMessageFromBotQMessage(json[0]);
			} else if (json[0].id != messageNext.id) {
				messageNext = createMessageFromBotQMessage(json[0]);
			}
		}

	} catch (e) {
		if (e instanceof UnknowMessageTypeException) {
			console.log('UnknowMessageTypeException: ' + e.toString());

			if (e.messageId) {
				// remove bad message from Q
				tinyxhr('http://botq.localhost/api/messageGot/' + botQChannel
						+ '/' + e.messageId, onXhrResponseMessageGot, 'GET',
						null, 'application/javascript');
			}

		} else {
			console.log('Uknow Exception: ');
			console.log(e);
		}
	}

	/*
	 * [ { "id" : "1", "channel_id" : "1", "label" : "msg #1", "priority" :
	 * "100", "priority_action" : "stop", "play_loop" : "0", "play_at_time" :
	 * null, "content_type" : "application\/url", "content" :
	 * "http:\/\/sanibot.org", "status_got" : null, "status_done" : null,
	 * "status_aborted" : null, "created_at" : "21\/06\/2015 09:25:55",
	 * "updated_at" : "21\/06\/2015 09:25:55", "deleted_at" : null }, { "id" :
	 * "2", "channel_id" : "1", "label" : "msg #2", "priority" : "0",
	 * "priority_action" : "pause", "play_loop" : "0", "play_at_time" : null,
	 * "content_type" : "application\/url", "content" : "http:\/\/sanibot.org",
	 * "status_got" : null, "status_done" : null, "status_aborted" : null,
	 * "created_at" : "21\/06\/2015 09:25:55", "updated_at" : "21\/06\/2015
	 * 09:25:55", "deleted_at" : null } ];
	 */

}

function onXhrResponseMessageGot(err, data, xhr) {
}

function createMessageFromBotQMessage(json) {

	switch (json.content_type) {
	case 'video/mp4':
	case 'video/ogg':
	case 'video/webm':
		return new VideoMessage(json.id, json.priority, json.play_loop,
				json.content, json.content_type);
		break;
	default:
		throw new UnknowMessageTypeException(json.content_type, json.id);

	}
}

function tinyxhr(url, cb, method, post, contenttype) {
	var requestTimeout, xhr;
	try {
		xhr = new XMLHttpRequest();
	} catch (e) {
		try {
			xhr = new ActiveXObject("Msxml2.XMLHTTP");
		} catch (e) {
			if (console)
				console.log("tinyxhr: XMLHttpRequest not supported");
			return null;
		}
	}
	requestTimeout = setTimeout(function() {
		xhr.abort();
		cb(new Error("tinyxhr: aborted by a timeout"), "", xhr);
	}, 5000);
	xhr.onreadystatechange = function() {
		if (xhr.readyState != 4)
			return;
		clearTimeout(requestTimeout);
		cb(xhr.status != 200 ? new Error("tinyxhr: server respnse status is "
				+ xhr.status) : false, xhr.responseText, xhr);
	}
	xhr.open(method ? method.toUpperCase() : "GET", url, true);

	// xhr.withCredentials = true;

	xhr.setRequestHeader('Content-type', contenttype ? contenttype
			: 'application/x-www-form-urlencoded');
	if (!post)
		xhr.send();
	else
		xhr.send(post)
}
