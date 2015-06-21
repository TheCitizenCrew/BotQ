/**
 * htmlCliWorker.js
 */

var botQPullFreq = 1000;
var botQPullTimer = null;

self.addEventListener('message', function(e) {
	var data = e.data;
	switch (data.cmd) {
	case 'say':
		self.postMessage(data.msg);
		break;
	case 'start':
		if (botQPullTimer == null) {
			botQPullTimer = setTimeout(pulse, botQPullFreq);
		}
		break;
	case 'stop':
		if(!botQPullTimer == null) {
			clearTimeout(botQPullTimer);
			botQPullTimer = null ;
		}
	}
	;
}, false);

function pulse()
{
	botQPullTimer = setTimeout(pulse, botQPullFreq);
}
