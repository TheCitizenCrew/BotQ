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
var botQPullFreq = 1000 * 5;
var botQPullTimer = null;

var messageCurrent = null;
var messageNext = null;

/**
 * Listen to client's messages
 */
self.addEventListener('message', function(e) {

	var msgStatusString = null ;

	var data = e.data;
	console.log('htmlCliWorker.js received cmd "' + data.cmd + '"');
	switch( data.cmd ){

	case 'say':
		self.postMessage(data.msg);
		break;

	case 'messageDone':
		
		msgStatusString = 'done';
		
	case 'messageError':
		
		if( msgStatusString == null )
			msgStatusString = 'aborted';

		//
		// messageDone & messageError trigger same actions, only messsage status change
		//

		// check ...
		if( messageCurrent.id != data.id ){
			// Argh!! TODO : manage error
		}

		// Le client a fait son travail, qui est maintenant terminé (done)
		tinyxhr('http://botq.localhost/api/messageStatus/' + botQChannel + '/' + messageCurrent.id + '/' + msgStatusString,
				onXhrResponseMessageStatus, 'GET', null, 'application/javascript');
		messageCurrent = null ;
		
		if( messageNext != null) {
			// A message was ready, consume it
			messageCurrent = messageNext;
			messageNext = null;
			// consume message queue
			tinyxhr('http://botq.localhost/api/messageStatus/' + botQChannel + '/' + messageCurrent.id + '/got',
					onXhrResponseMessageStatus, 'GET', null, 'application/javascript');
			// give new work to client
			self.postMessage(messageCurrent);
		}

		break;

	case 'messageAborted':
		
		// messageAborted is not an Error, it's just a ack to update message status on server side

		// check ...
		if( messageCurrent.id != data.id ){
			// Argh!! TODO : manage error
		}

		// Le client a abandonné son travail parcequ'il a reçu un autre message
		tinyxhr('http://botq.localhost/api/messageStatus/' + botQChannel + '/' + messageCurrent.id + '/aborted',
				onXhrResponseMessageStatus, 'GET', null, 'application/javascript');
		break;

	case 'start':
		if( botQPullTimer == null) {
			botQPullTimer = setTimeout(pulse, 100);
		}
		break;

	case 'stop':
		if( !botQPullTimer == null) {
			clearTimeout(botQPullTimer);
			botQPullTimer = null;
		}
	}
	;
}, false);

function pulse() {

	tinyxhr('http://botq.localhost/api/messagesSet/' + botQChannel, onXhrResponse, 'GET', null, 'application/javascript');

	botQPullTimer = setTimeout(pulse, botQPullFreq);
}

function onXhrResponse(err, data, xhr) {

	console.log('onXhrResponse() messageCurrent ' + (messageCurrent ? messageCurrent.id : 'null'));
	console.log('onXhrResponse() messageNext ' + (messageNext ? messageNext.id : 'null'));

	if( err) {
		console.log("goterr ", err, 'status=' + xhr.status);
		return;
	}

	// console.log(data);
	var json = JSON.parse(data);
	console.log(json);

	if( json.length == 0) {
		messageNext = null;
		return;
	}

	try {

		if( messageCurrent == null) {
			// Le client n'a plus rien a manger...

			messageCurrent = json[0];

			if( json.length == 2)
				messageNext = json[1];

			// Send now to htmlClient
			tinyxhr('http://botq.localhost/api/messageStatus/' + botQChannel + '/' + messageCurrent.id + '/got',
					onXhrResponseMessageStatus, 'GET', null, 'application/javascript');
			self.postMessage(messageCurrent);

		} else if( json[0].id == messageCurrent.id) {

			if( json.length == 2) {
				if( messageNext == null) {
					messageNext = json[1];
				} else if( json[1].id != messageNext.id) {
					messageNext = json[1];
				}
			}

		} else if(
				json[0].priority > messageCurrent.priority
				|| json[0].play_at_time!=null
				) {
console.log('ARGH! '+json[0].play_at_time);

			// new message with higher priority
			// or that it's time to play
			messageCurrent = json[0];

			tinyxhr('http://botq.localhost/api/messageStatus/' + botQChannel + '/' + messageCurrent.id + '/got',
					onXhrResponseMessageStatus, 'GET', null, 'application/javascript');
			self.postMessage(messageCurrent);

		} else {

			if( messageNext == null) {
				messageNext = json[0];
			} else if( json[0].id != messageNext.id) {
				messageNext = json[0];
			}
		}

	} catch( e ) {
		if( e instanceof UnknowMessageTypeException) {
			console.log('UnknowMessageTypeException: ' + e.toString());

			if( e.messageId) {
				// remove bad message from Q
				tinyxhr('http://botq.localhost/api/messageStatus/' + botQChannel + '/' + e.messageId + '/got',
						onXhrResponseMessageStatus, 'GET', null, 'application/javascript');
			}

		} else {
			console.log('Uknow Exception: ');
			console.log(e);
		}
	}

}

function onXhrResponseMessageStatus(err, data, xhr) {
}

/**
 * Simple Ajax method
 * 
 * @param url
 * @param cb
 * @param method
 * @param post
 * @param contenttype
 * @returns
 */
function tinyxhr(url, cb, method, post, contenttype) {
	var requestTimeout, xhr;
	try {
		xhr = new XMLHttpRequest();
	} catch( e ) {
		try {
			xhr = new ActiveXObject("Msxml2.XMLHTTP");
		} catch( e ) {
			if( console)
				console.log("tinyxhr: XMLHttpRequest not supported");
			return null;
		}
	}
	requestTimeout = setTimeout(function() {
		xhr.abort();
		cb(new Error("tinyxhr: aborted by a timeout"), "", xhr);
	}, 5000);
	xhr.onreadystatechange = function() {
		if( xhr.readyState != 4)
			return;
		clearTimeout(requestTimeout);
		cb(xhr.status != 200 ? new Error("tinyxhr: server respnse status is " + xhr.status) : false, xhr.responseText, xhr);
	}
	xhr.open(method ? method.toUpperCase() : "GET", url, true);

	// xhr.withCredentials = true;

	xhr.setRequestHeader('Content-type', contenttype ? contenttype : 'application/x-www-form-urlencoded');
	if( !post)
		xhr.send();
	else
		xhr.send(post)
}
