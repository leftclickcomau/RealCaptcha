<!DOCTYPE html>
<!--
This file is a part of RealCaptcha human verification system.
Copyright (c) Ben New, Leftclick.com.au
See the LICENSE and README files in the main source directory for details.
-->
<html>
	<head>
		<title>Demonstration :: RealCaptcha</title>
		<link rel="stylesheet" type="text/css" href="css/real-captcha-demo.css" />
	</head>
	<body>
		<h1>RealCaptcha Demonstration Page</h1>
		<p>
		The following is a simple demonstration form, it does not do anything except validation.
		</p>
		<form method="post" action="captcha-check.php">
			<fieldset>
				<legend>Create an Account</legend>
				<div class="field">
					<label for="name">Name:</label>
					<input id="name" name="name" type="text" />
				</div>
				<div class="field">
					<label for="email">Email Address:</label>
					<input id="email" name="email" type="text" />
				</div>
				<div class="field">
					<label for="password">Password:</label>
					<input id="password" name="password" type="password" />
				</div>
				<div class="field">
					<label for="captcha">Captcha:</label>
					<div id="captcha-container" onmouseover="document.getElementById('captcha-options').style.display = 'block';" onmouseout="document.getElementById('captcha-options').style.display = 'none';">
						<img src="captcha.php?r=<?php echo mt_rand(0, 10000); ?>" id="captcha-image" />
						<ul id="captcha-options" style="display:none;">
							<li>Reload...</li>
							<li><a href="" onclick="document.getElementById('captcha-image').setAttribute('src', 'captcha.php?r=' + Math.floor(Math.random() * 10000) + '&type=mathematical'); return false;">Mathematical</a></li>
							<li><a href="" onclick="document.getElementById('captcha-image').setAttribute('src', 'captcha.php?r=' + Math.floor(Math.random() * 10000) + '&type=alphanumeric'); return false;">Alphanumeric</a></li>
						</ul>
					</div>
					<input id="captcha" name="captcha" type="text" autocomplete="off" />
				</div>
				<div class="buttons">
					<input type="submit" value="Submit" />
					<input type="reset" value="Reset" />
				</div>
			</fieldset>
		</form>
	</body>
</html>
