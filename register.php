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
		<title>register at plum_</title>
		<link rel="shortcut icon" href="/media/plum32x32v2.ico">
		<link href="css/plum.css" type="text/css" rel="stylesheet" media="all">
		<link href="css/buttons.css" type="text/css" rel="stylesheet" media="all">
</head>
<body>
	<div id="logo">
		<span class="logo">plum<span class="underscore">_</span></span>
	</div><br><br>
<?php if ($error == 0) { ?>
Thank you for your registration!
<?php } else if ($error == 1) { ?>
Email or password do not match!
<?php } else if ($error == 2) { ?>
Your input contains invalid letters!
<?php } else if ($error == 3) { ?>
Internal server error!
<?php } else if ($error == 4) { ?>
Email or password already in use!
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
					<td>
							<input class="btnReg" type="submit" value="register" />
					</td>
					<td>
							<input class="btnDef" type="button" onclick="window.location.href='index.php'" value="back" />
					</td>
					
					</tr>
				</table>
			</form>
		</div>

</body>
</html>
