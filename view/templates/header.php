<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>The University of York -- Computer Science News</title>

<link
	href="<?php echo CSS_PATH ."/jquery-ui/jquery-ui-1.10.3.custom.css"?>"
	rel="stylesheet">
<script src="<?php echo JS_PATH ."/jquery-1.9.1.js" ?>"> </script>
<script src="<?php echo JS_PATH ."/jquery-ui-1.10.3.custom.js"?>"></script>
<script src="<?php echo JS_PATH ."/main.js" ?>"> </script>
<script>
/* $(function() {
	$( "a" )
	.button();
});  */

$(document).ready(function () {	
	$('nav li').hover(
		function () {
			$('ul', this).stop().slideDown(200);
			
		}, 
		function () {
			$('ul', this).stop().slideUp(200);			
		}
	);
});



</script>
<link rel="stylesheet" type="text/css"
	href="<?php echo CSS_PATH . "/main.css"?>">
<style type="text/css">
body {
	
}

#background {
	position: fixed;
	top: 45%;
	left: 0;
	right: 0;
	width: 42%;
	opacity: 0.07;
	filter: alpha(opacity =   7);
	pointer-events: none;
}

.overlay {
	position: absolute;
	left: 0px;
	background: url('<?php echo ROOT ?>/images/bg.png');
	bottom: 0px;
	padding: 4px;
}
</style>
</head>

<body>

	<div id="messages" class="center"></div>
	<div id="background"></div>

	<img src="<?php echo  IMAGE_PATH. "/shield.png"?>" id="background"
		class="center">
	<div class="center main">
		<div id="topbar" class="center">
<?php

if (! array_key_exists ( 'LoggedIn', $_SESSION )) {
	echo "<small><a href=\"" . ROOT . "/member/login\">Login</a></small>";
} else {
	echo "<span>Hello " . $_SESSION ['UserType'] . " <b>" . $_SESSION ['Username'] . "</b>!     </span>";
	echo "<a href=\"" . ROOT . "/member/logout\">Logout</a>";
	echo "<a href=\"" . ROOT . "/member\">Member area</a>";
	echo "<a href=\"" . ROOT . "/member/manual\">User manual</a>";
}
?>
			</div>
		<hr color="#5A8039" size="2px" />
		<header>
			<img id="logo" src="<?php echo  IMAGE_PATH. "/UoYLogo.png"?>">

			<h2>Bringing you the latest from the Department and Beyond</h2>
			<h1>Computer Science News</h1>
		</header>
		<hr color="#5A8039" size="2px" />
		<nav class="center">
			<ul>
				<li><a href="<?php  echo ROOT ?>">Home</a></li>
				<li><a href="<?php echo ROOT . "/latest/all" ?>">Latest</a></li>
				<li><a href="<?php  echo ROOT ?>">Columns</a><span
					class="ui-icon ui-icon-carat-1-s"></span>
					<ul>
						<li><a href="<?php echo ROOT . "/latest/column/tech" ?>">Technology</a></li>
						<li><a href="<?php echo ROOT . "/latest/column/cs_success" ?>"
							class="selected">DCS Success</a></li>
						<li><a href="#">Item 03</a></li>
					</ul></li>
				<li><a href="<?php echo ROOT . "/latest/reviews" ?>">Reviews</a></li>
				<li><a href="<?php echo ROOT . "/about" ?>">About</a></li>
			</ul>
		</nav>
		<hr color="#5A8039" size="1px" />