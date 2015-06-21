/**
 * a WebWorker
 * 
 * Documentation:
 * 
 * The Basics of Web Workers
 * http://www.html5rocks.com/en/tutorials/workers/basics/
 */

var timer = null;

self.addEventListener('message', function(e) {
	var data = e.data;
	switch (data.cmd) {
	
	case 'say':
		self.postMessage(data.msg);
		break;
		
	case 'timerToggle':
		if (timer != null) {
			clearTimeout( timer );
			timer = null ;
		} else {
			timer = setTimeout(pulse, 1000);
		}
	}
	;
}, false);

function pulse() {
	//console.log('Pulsing!');
	self.postMessage('Pulsing!');
	timer = setTimeout(pulse, 1000);
}
