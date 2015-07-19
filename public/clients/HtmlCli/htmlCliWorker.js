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

var botQurl = location.protocol+'//'+location.host ;
//var botQurl = 'http://botq.local.comptoir.net' ;
log('botq server url : '+botQurl );

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
	log('htmlCliWorker.js received cmd "' + data.cmd + '"');
	log(data);

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
		tinyxhr(botQurl+'/api/messageStatus/' + botQChannel + '/' + data.message.id + '/' + msgStatusString + (data.comment?'/'+encodeURI(data.comment):''),
				onXhrResponseMessageStatus, 'GET', null, 'application/javascript');
		messageCurrent = null ;

		if( messageNext != null) {
			// A message was ready, consume it
			messageCurrent = messageNext;
			messageNext = null;
			// consume message queue
			tinyxhr(botQurl+'/api/messageStatus/' + botQChannel + '/' + messageCurrent.id + '/got',
					onXhrResponseMessageStatus, 'GET', null, 'application/javascript');
			// give new work to client
			self.postMessage(messageCurrent);
		}

		break;

	case 'messageAborted':

		// messageAborted is not an Error, it's just a ack to update message status on server side

		tinyxhr(botQurl+'/api/messageStatus/' + botQChannel + '/' + data.message.id + '/aborted'+ (data.comment?'/'+encodeURI(data.comment):''),
				onXhrResponseMessageStatus, 'GET', null, 'application/javascript');
		break;

		// Le client a abandonné son travail parcequ'il a reçu un autre message
		// Si ce n'est pas le cas ... Passons lui le message suivant
		if( messageCurrent.id == data.message.id )
		{
			log('################################');
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

	tinyxhr(botQurl+'/api/messagesSet/' + botQChannel, onXhrMessagesSet, 'GET', null, 'application/javascript');

	//botQPullTimer = setTimeout(pulse, botQPullFreq);
}

function onXhrMessagesSet(err, data, xhr) {

	//log('onXhrResponse() messageCurrent ' + (messageCurrent ? messageCurrent.id : 'null'));
	//log('onXhrResponse() messageNext ' + (messageNext ? messageNext.id : 'null'));

	if( err) {
		log('ERROR XHR: '+ err + ', status=' + xhr.status);
		botQPullTimer = setTimeout(pulse, botQPullFreq);
		return;
	}

	// log(data);
	var json = JSON.parse(data);

	if( json.length == 0) {
		//log('JSON empty');
		// TODO: bug? messageNext = null;
		botQPullTimer = setTimeout(pulse, botQPullFreq);
		return;
	}
	log(json);

	try {
		processMessagesSet( json );
	}
	catch( ex )
	{
		log('ERROR htmlCliWorker: '+ex.name+', '+ex.message );
		log(ex);
	}

	botQPullTimer = setTimeout(pulse, botQPullFreq);
}

function processMessagesSet( json ) {

		if( messageCurrent == null) {

			// Le client n'a plus rien a manger...
			log('processMessagesSet() case #1');

			messageCurrent = json[0];

			if( json.length == 2)
				messageNext = json[1];

			// Set message has "got" on server side
			tinyxhr(botQurl+'/api/messageStatus/' + botQChannel + '/' + messageCurrent.id + '/got',
					onXhrResponseMessageStatus, 'GET', null, 'application/javascript');

			// Send now to htmlClient
			self.postMessage(messageCurrent);

		} else if( json[0].id == messageCurrent.id) {

			// c'est le meme message que le message courant
			log('processMessagesSet() case #2');

			if( json.length == 2) {
				if( messageNext == null) {
					messageNext = json[1];
				} else if( json[1].id != messageNext.id) {
					messageNext = json[1];
				}
			}

		} else if(
				json[0].priority > messageCurrent.priority
				|| json[0].play_at_time != null
				/*|| messageCurrent.play_loop == '1'*/
				) {

			// new message with higher priority
			// or that it's time to play
			log('processMessagesSet() case #3');
			
			messageCurrent = json[0];

			// Set message has "got" on server side
			tinyxhr(botQurl+'/api/messageStatus/' + botQChannel + '/' + messageCurrent.id + '/got',
					onXhrResponseMessageStatus, 'GET', null, 'application/javascript');

			// Send now to htmlClient
			self.postMessage(messageCurrent);

		} else {

			log('processMessagesSet() case #4');

			if( messageNext == null) {
				messageNext = json[0];
			}
			// TODO: bug ?
			//else if( json[0].id != messageNext.id) {
			//	messageNext = json[0];
			//}
		}


}

/**
 * TODO: unused ?
 * @param err
 * @param data
 * @param xhr
 */
function onXhrResponseMessageStatus(err, data, xhr) {
	//log('onXhrResponseMessageStatus() err: '+err+', data: '+data);
}

