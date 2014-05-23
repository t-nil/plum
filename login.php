<?php
	$error = false;
	if (isset($_POST['name'])) {
		require_once("api.class.php");
		$sid = json_decode(API::login($_POST['name'], $_POST['pw']), true);
		
		if ($sid['error'] != "") {
			$error = true;
		} else {		
			setcookie("sid", $sid['result']['sid']);
			header("Location: index.php");
		}
	}
?>
<html>
	<head>
		<title>plum_</title>
		<link rel="shortcut icon" href="/media/plum32x32v2.ico">
		<link href="css/plum.css" type="text/css" rel="stylesheet" media="all">
		<link href="css/buttons.css" type="text/css" rel="stylesheet" media="all">
		<link href="css/animate.min.css" type="text/css" rel="stylesheet" media="all">
		<link href="css/info_add.css" type="text/css" rel="stylesheet" media="all">
		
		
		<!-- Piwik -->
		<script type="text/javascript">
		  var _paq = _paq || [];
		  _paq.push(['trackPageView']);
		  _paq.push(['enableLinkTracking']);
		  (function() {
		    var u=(("https:" == document.location.protocol) ? "https" : "http") + "://faui2k13.de/analytics/";
		    _paq.push(['setTrackerUrl', u+'piwik.php']);
		    _paq.push(['setSiteId', 4]);
		    var d=document, g=d.createElement('script'), s=d.getElementsByTagName('script')[0]; g.type='text/javascript';
		    g.defer=true; g.async=true; g.src=u+'piwik.js'; s.parentNode.insertBefore(g,s);
		  })();
		</script>
		<noscript><p><img src="http://faui2k13.de/analytics/piwik.php?idsite=4" style="border:0;" alt="" /></p></noscript>
		<!-- End Piwik Code -->

	</head>
	<body>
		<div id="logo">
			<img src="media/plumanimated2.gif" />
		</div>
		
		<div id="login">
			<form id="loginForm" method="POST" action="login.php">
				<table>
					<tr class="userRow">
					<td>
						<div class="userDiv">
							<label>user:</label>
						</div>
					</td>
					<td>
						<input name="name" type="text" /></td>
					</td>
					</tr>
					<tr class="passRow">
					<td>
						<div class="passDiv">
							<label>pass:</label>
						</div>
					</td>
					<td>
						<input name="pw" type="password" />
					</td>
					</tr>
					<tr>
					<td>
						<div class="loginDiv">
							<input class="btnLogin" type="submit" value="Login" />
						</div>
					</td>
					<td>
						<div class="registerDiv">
							<input class="btnRegister" type="button" value="Register" onClick="window.location.href='register.php'"/>
						</div>
					</td>
					</tr>
				</table>
			</form>
			<?php if ($error) { ?>
		<p>
			Error while logging in! Please try again.
		</p>
		<?php } ?>
		</div>
		<div class="info_box">
	        	<ul class="tabs">
			         <li class="tab">
			          <input type="radio" checked name="tabs" id="tab1">
			          <label for="tab1">changelog</label>
			          <div id="tab-content1" class="tab-content">
			            <div class="animated  fadeInRight">
							  23.05.14: added info-box to login-page<br>
			              19.05.14: plum went online!
			            </div>
			          </div>
			        </li>
			        <li class="tab">
			          <input type="radio" name="tabs" id="tab2">
			          <label for="tab2">about</label>
			          <div id="tab-content2" class="tab-content">
			            <div class="animated  fadeInRight">
			              plum is a free and open coding-project by ra1n and nakami.<br>with plum stundents of technische fakultät can shout-out their location and what they're doing.<br><br>visit our github-entry for upcoming features: <a href="https://github.com/floMeise/plum/issues">https://github.com/floMeise/plum/issues</a>
			            </div>
			          </div>
			        </li>
					<!-- license
			         <li class="tab">
			          <input type="radio" name="tabs" id="tab3">
			          <label for="tab3">license</label>
			          <div id="tab-content3" class="tab-content">
			            <div class="animated  fadeInRight ">
			              license
			            </div>
			          </div>
			        </li> -->
			    </ul>
				</div>
	</body>
</html>
