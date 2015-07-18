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
var botQPullFreq = 5 * 1000 ;
var botQPullTimer = null;

var messageCurrent = null;
var messageNext = null;

/**
 * Listen to the worker's client messages
 */
self.addEventListener('message', function(e) {

	var msgStatusString = null ;

	var data = e.data;
	console.log('htmlCliWorker.js received cmd "' + data.cmd + '"');
	console.log(data);

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

		// Le client a fait son travail, qui est maintenant terminé (done)
		tinyxhr('http://botq.localhost/api/messageStatus/' + botQChannel + '/' + data.message.id + '/' + msgStatusString,
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

		tinyxhr('http://botq.localhost/api/messageStatus/' + botQChannel + '/' + data.message.id + '/aborted',
				onXhrResponseMessageStatus, 'GET', null, 'application/javascript');
		break;

		// Le client a abandonné son travail parcequ'il a reçu un autre message
		// Si ce n'est pas le cas ... Passons lui le message suivant
		if( messageCurrent.id == data.message.id )
		{
			console.log('################################');
		}

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
		break;

	};

}, false);

/**
 * Periodically retreive the channel's messagesSet onto the BotQ server.
 */
function pulse() {

	tinyxhr('http://botq.localhost/api/messagesSet/' + botQChannel, onXhrResponse, 'GET', null, 'application/javascript');

	botQPullTimer = setTimeout(pulse, botQPullFreq);
}

function onXhrResponse(err, data, xhr) {

	//console.log('onXhrResponse() messageCurrent ' + (messageCurrent ? messageCurrent.id : 'null'));
	//console.log('onXhrResponse() messageNext ' + (messageNext ? messageNext.id : 'null'));

	if( err) {
		console.log("goterr ", err, 'status=' + xhr.status);
		return;
	}

	// console.log(data);
	var json = JSON.parse(data);

	if( json.length == 0) {
		//console.log('JSON empty');
		// TODO: bug? messageNext = null;
		return;
	}
	console.log(json);

	try {

		if( messageCurrent == null) {

			// Le client n'a plus rien a manger...
			console.log('onXhrResponse() case #1');

			messageCurrent = json[0];

			if( json.length == 2)
				messageNext = json[1];

			// Set message has "got" on server side
			tinyxhr('http://botq.localhost/api/messageStatus/' + botQChannel + '/' + messageCurrent.id + '/got',
					onXhrResponseMessageStatus, 'GET', null, 'application/javascript');
			// Send now to htmlClient
			self.postMessage(messageCurrent);

		} else if( json[0].id == messageCurrent.id) {

			// c'est le meme message que le message courant
			console.log('onXhrResponse() case #2');

			if( json.length == 2) {
				if( messageNext == null) {
					messageNext = json[1];
				} else if( json[1].id != messageNext.id) {
					messageNext = json[1];
				}
			}

		} else if(
				json[0].priority > messageCurrent.priority
				|| json[0].play_at_time != ''
				/*|| messageCurrent.play_loop == '1'*/
				) {

			// new message with higher priority
			// or that it's time to play
			// or the current message is waiting in an infinite loop
			console.log('onXhrResponse() case #3');
			
			messageCurrent = json[0];

			tinyxhr('http://botq.localhost/api/messageStatus/' + botQChannel + '/' + messageCurrent.id + '/got',
					onXhrResponseMessageStatus, 'GET', null, 'application/javascript');
			self.postMessage(messageCurrent);

		} else {

			console.log('onXhrResponse() case #4');

			if( messageNext == null) {
				messageNext = json[0];
			}
			// TODO: bug ?
			//else if( json[0].id != messageNext.id) {
			//	messageNext = json[0];
			//}
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

/**
 * TODO: unused ?
 * @param err
 * @param data
 * @param xhr
 */
function onXhrResponseMessageStatus(err, data, xhr) {
	//console.log('onXhrResponseMessageStatus() err: '+err+', data: '+data);
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
