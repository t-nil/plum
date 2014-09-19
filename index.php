<html>
	<head>
		<meta charset="utf-8">
		
		<script src="jquery.js" type="text/javascript"></script>
		<script src="jquery.cookie.js" type="text/javascript"></script>
		<script src="plum.js" type="text/javascript"></script>
		
		<title>plum_</title>
		<link rel="shortcut icon" href="/media/plum32x32v2.ico">
		<link rel="stylesheet" href="http://yui.yahooapis.com/pure/0.5.0/pure-min.css">
		<link href="css/plum.css" type="text/css" rel="stylesheet" media="all">
		<link href="css/buttons.css" type="text/css" rel="stylesheet" media="all">
		<link href="css/info_box.css" type="text/css" rel="stylesheet" media="all">
		<link href="css/plum_clouds.css" type="text/css" rel="stylesheet" media="all">
		
		<!-- nightmode -->
		<link href="css/css_toggle.css" type="text/css" rel="stylesheet" media="all">
		<link id="nightmode" rel="stylesheet" type="text/css" href="">
		<script type="text/javascript">
			function updateStylesheet() {
				if ($.cookie("switch-input") == "true")
					document.getElementById('nightmode').setAttribute('href', 'css/plum_night.css');
				else
					document.getElementById('nightmode').setAttribute('href', '');
			}
			updateStylesheet();
		</script>
		<script src="nightmode_cookie.js" type="text/javascript"></script>
		<!-- /night mode -->
	</head>
	<body>
	</body>
</html>
