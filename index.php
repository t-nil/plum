<html>
	<head>
		<meta charset="utf-8">
		
		<!-- social media meta-info -->
		<!-- for Google -->
		<meta name="description" content="with plum students of the Faculty of Engineering FAU Erlangen-Nuremberg can shout-out their current position and what they're doing." />
		<meta name="keywords" content="plum, faui2k13, fau, computer science" />
		<meta name="author" content="ra1n, nakami" />
		<meta name="copyright" content="all rights reserved" />
		<meta name="application-name" content="plum_" />
		<!-- for Facebook -->          
		<meta property="og:title" content="plum_" />
		<meta property="og:type" content="website" />
		<meta property="og:image" content="http://plum.faui2k13.de/media/plum_200x200.png" />
		<meta property="og:url" content="http://plum.faui2k13.de/login.php" />
		<meta property="og:description" content="with plum students of the Faculty of Engineering FAU Erlangen-Nuremberg can shout-out their current position and what they're doing." />
		<!-- for Twitter -->          
		<meta name="twitter:card" content="summary" />
		<meta name="twitter:title" content="plum_" />
		<meta name="twitter:description" content="with plum students of the Faculty of Engineering FAU Erlangen-Nuremberg can shout-out their current position and what they're doing." />
		<meta name="twitter:image" content="http://plum.faui2k13.de/media/plum_200x200.png" />
		
		<script src="jquery.js" type="text/javascript"></script>
		<script src="jquery.cookie.js" type="text/javascript"></script>
		<script src="plum.js" type="text/javascript"></script>
		
		<title>plum_</title>
		<link rel="shortcut icon" href="/media/plum32x32v2.ico">
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
		<!-- /night mode -->
	</head>
	<body>
		<!-- cookie night mode -->
		<script>
			$(function () {
				$("input.switch-input").each(function() {
					var mycookie = $.cookie($(this).attr('name'));
					if (mycookie && mycookie == "true") {
						$(this).prop('checked', mycookie);
					}
				});
				
				$("input.switch-input").change(function() {
					$.cookie($(this).attr("name"), $(this).prop('checked'), {
						path: '/',
						expires: 365
					});
					
					updateStylesheet();
				});
			});
		</script>
		<!-- /cookie night mode -->
	</body>
</html>
