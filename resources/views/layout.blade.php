<!DOCTYPE html>
<html lang="fr">
	<head>
		<meta charset="UTF-8" /> 
		<meta http-equiv="X-UA-Compatible" content="IE=edge" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<title>BotQ - @yield('title', 'Home')</title>
		<meta name="description" content="BotQ manage an actions pool for some robots" />
		<meta name="author" content="The Citizen Crew & Co, Cyrille Giquello" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />

		@section('css')

		<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/font-awesome/4.3.0/css/font-awesome.min.css" />
		<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.4/css/bootstrap.min.css" />
		<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.4/css/bootstrap-theme.min.css" />
		<link rel="stylesheet" href="/style.css" />

		@show

	</head>
    <body role="document">

		<nav class="navbar navbar-inverse navbar-fixed-top">
			<div class="container-fluid">

				<div class="navbar-header">					
		      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-items-collapse">
		        <span class="sr-only">Toggle navigation</span>
		        <span class="icon-bar"></span>
		        <span class="icon-bar"></span>
		        <span class="icon-bar"></span>
		      </button>
		      <a class="navbar-brand " href="/">BotQ</a>
				</div>

		    <!-- Collect the nav links, forms, and other content for toggling -->
		    <div class="collapse navbar-collapse" id="navbar-items-collapse" >
		      <ul class="nav navbar-nav">
            <li><a href="/channel/all">Channels</a></li>
            <li><a href="/about">À propos</a></li>
		      </ul>
				</div>

			</div>
		</nav>

		<div class="container-fluid" role="main">

			@yield('content')

		</div>

		<br/>
		<div class="container-fuild" role="footer">
			<blockquote>
				<footer>
					&copy; 2015 <a href="https://github.com/TheCitizenCrew">The Citizen Crew &amp; Co</a><br/>
					Powered with Php &amp; Javascript (Lumen, Bootstrap, JQuery, CdnJs ...).
				</footer>
			</blockquote>
		</div>

		@section('javascript')

			<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
			<script src="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.4/js/bootstrap.min.js"></script>
		@show

	</body>
</html>
