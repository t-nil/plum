<?php
$error = -1;
	if (isset($_GET['do'])) {
		if (!isset($_POST['user']) || !isset($_POST['pw']) || !isset($_POST['email']) || !isset($_POST['email_confirm']) || !isset($_POST['pw_confirm'])) die("Invalid call!");
		if ($_POST['pw'] != $_POST['pw_confirm'] || $_POST['email'] != $_POST['email_confirm']) $error = 1;
		else {
			require_once("api.class.php");
			$result = json_decode(API::register($_POST['user'], $_POST['email'], $_POST['pw']), true);
			if ($result['error'] == "malformed_input") $error = 2; else if ($result['error'] == "malformed_request") $error = 3; else if ($result['error'] == "already_in_use") $error = 4;
			else $error = 0;
		}
	}
?>	
<html>
<head>
		<meta charset="utf-8">
		
		<title>plum_</title>
		<link rel="shortcut icon" href="/media/plum32x32v2.ico">
		<link rel="stylesheet" href="http://yui.yahooapis.com/pure/0.5.0/pure-min.css">
		<link href="css/plum.css" type="text/css" rel="stylesheet" media="all">
		<link href="css/buttons.css" type="text/css" rel="stylesheet" media="all">
		<link href="css/info_box.css" type="text/css" rel="stylesheet" media="all">
		<link href="css/plum_clouds.css" type="text/css" rel="stylesheet" media="all">
</head>
<body>
	<div id="logo">
		<span class="logo">plum<span class="underscore">_</span></span>
	</div><br><br>
<?php if ($error == 0) { ?>
<div class="errorMsg fontapply">Thank you for your registration!</div>
<?php } else if ($error == 1) { ?>
<div class="errorMsg fontapply">Email or password do not match!</div>
<?php } else if ($error == 2) { ?>
<div class="errorMsg fontapply">Your input contains invalid letters!</div>
<?php } else if ($error == 3) { ?>
<div class="errorMsg fontapply">Internal server error!</div>
<?php } else if ($error == 4) { ?>
<div class="errorMsg fontapply">Email or password already in use!</div>
<?php } ?><br />
<div id="register">
			<form id="registerForm" method="POST" action="register.php?do">
				<table>
					<tr class="usernameRow">
					<td>
						<label>username:</label>
					</td>
					<td>
						<input name="user" type="text" /></td>
					</td>
					</tr>
					<tr class="emptyRow"></tr>
					<tr class="emailRow1">
					<td>
						<label>email:</label>
					</td>
					<td>
						<input name="email" type="text" /></td>
					</td>
					</tr>
					<tr class="emailRow2">
					<td>
						<label>repeat:</label>
					</td>
					<td>
						<input name="email_confirm" type="text" /></td>
					</td>
					<tr class="emptyRow"></tr>
					
					<tr class="passRow1">
					<td>
						
							<label>password:</label>
						
					</td>
					<td>
						<input name="pw" type="password" />
					</td>
					</tr>
					<tr class="passRow2">
					<td>
						
							<label>repeat:</label>
						
					</td>
					<td>
						<input name="pw_confirm" type="password" />
					</td>
					</tr>	
					<tr>
					<td rowspan="2">
							<button class="button-xsmall pure-button pure-button-primary btn" type="submit" value="register">register</button>
							<a class="button-xsmall pure-button btn no_underline"  href="login.php">back</a>
					</td>
					</tr>
				</table>
			</form>
		</div>

</body>
</html>
