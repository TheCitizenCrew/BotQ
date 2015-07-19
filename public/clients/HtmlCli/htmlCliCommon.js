/**
 * htmlCliCommon.js
 * 
 * Documentation:
 * 
 * Javascript OOP
 * https://developer.mozilla.org/en-US/docs/Web/JavaScript/Introduction_to_Object-Oriented_JavaScript
 * 
 * Javascript Exception
 * https://developer.mozilla.org/en-US/docs/Web/JavaScript/Reference/Statements/throw
 * 
 */
"use strict";

/**
 * Exception
 */

/*
var Exception = function(message) {
	this.message = message;
}

Exception.prototype.toString = function() {
	return this.message;
}

var UnknowMessageTypeException = function(msgType, messageId) {
	Exception
			.call(this, 'unknow message type "' + msgType + '" (messageId:' + (messageId ? messageId : 'unknow') + ')');
	this.messageId = messageId;
}
UnknowMessageTypeException.prototype = Object.create(Exception.prototype);
*/

/**
 * Message
 */

/*
var Message = function(id, priority, loop, mime) {
	this.id = id;
	this.loop = loop;
	this.priority = priority;
	this.mime = mime;
}
Message.createMessageFromBotQMessage = function(json) {

	if( json.content_type.indexOf('video/') == 0) {
		return new VideoMessage(json.id, json.priority, json.play_loop, json.content, json.content_type);
	} else {
		throw new UnknowMessageTypeException(json.content_type, json.id);
	}
}

var VideoMessage = function(id, priority, loop, url, mime) {
	Message.call(this, id, priority, loop, mime);
	this.url = url;
}
VideoMessage.prototype = Object.create(Message.prototype);
*/

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
