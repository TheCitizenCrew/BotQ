# BotQ

**! Work in progress !**

![Work in progress][workInProgressImage]

[![Code Climate](https://codeclimate.com/github/TheCitizenCrew/BotQ/badges/gpa.svg)](https://codeclimate.com/github/TheCitizenCrew/BotQ)
[![Test Coverage](https://codeclimate.com/github/TheCitizenCrew/BotQ/badges/coverage.svg)](https://codeclimate.com/github/TheCitizenCrew/BotQ)

"BotQ" aka "Bot Queue" permit to manage input and output of robots and pilot them.

## RoadMap

- [ ] Design architecture
  - [ ] Data
  - [ ] Client(s)
  - [ ] Server

## Server Installation

### Dependencies

[Php](http://php.net), [Lumen](http://lumen.laravel.com), [CDNJs](https://cdnjs.com/), [Bootstrap](http://getbootstrap.com), [JQuery](http://jquery.com), [Leaflet](http://leafletjs.com/)
 
Use "[composer](https://getcomposer.org/)".

### Web server

#### nginx

	...
	location / {
		try_files $uri $uri/ /index.php?$query_string ;
	}
	...

## Android Client

TODO

## Php Client

TODO

[workInProgressImage]: http://upload.wikimedia.org/wikipedia/commons/thumb/2/26/Work_in_progress_%283709389075%29.jpg/320px-Work_in_progress_%283709389075%29.jpg?raw=true
 