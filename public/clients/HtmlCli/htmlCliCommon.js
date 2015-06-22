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
"use strict" ;

/**
 * Exception
 */

var Exception = function(message) {
	this.message = message;
}

Exception.prototype.toString = function(){
	return this.message ;
}

var UnknowMessageTypeException = function( msgType, messageId )
{
	Exception.call(this, 'unknow message type "'+msgType+'" (messageId:'+(messageId?messageId:'unknow')+')');
	this.messageId = messageId;
}
UnknowMessageTypeException.prototype = Object.create(Exception.prototype);

/**
 * Message
 */

var Message = function(id, priority, loop, mime) {
	this.id = id ;
	this.loop = loop ;
	this.priority = priority ;
	this.mime = mime ;
}

var VideoMessage = function(id, priority, loop, url, mime) {
	Message.call(this, id, priority, loop, mime);
	this.url = url ;
}
VideoMessage.prototype = Object.create(Message.prototype);
