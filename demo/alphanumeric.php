<!DOCTYPE html>
<!--
This file is a part of RealCaptcha human verification system.
Copyright (c) Ben New, Leftclick.com.au
See the LICENSE and README files in the main source directory for details.
-->
<html>
	<head>
		<title>Alphanumeric Captcha :: Demonstration :: RealCaptcha</title>
		<link rel="stylesheet" type="text/css" href="css/real-captcha-demo.css" />
	</head>
	<body>
		<div id="container">
			<h1>RealCaptcha: Alphanumeric Captcha Demonstration</h1>
			<p>
			The following is a simple demonstration form which is fixed to Alphanumeric mode.
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
						<div id="captcha-container">
							<img src="captcha.php?type=Alphanumeric" id="captcha-image" />
						</div>
						<div>
							<a href="" onclick="document.getElementById('captcha-image').setAttribute('src', 'captcha.php?type=Alphanumeric&amp;r=' + Math.floor(Math.random() * 10000)); return false;">Reload</a>
						</div>
						<input id="captcha" name="captcha" type="text" autocomplete="off" />
					</div>
					<div class="buttons">
						<input type="submit" value="Submit" />
						<input type="reset" value="Reset" />
					</div>
				</fieldset>
			</form>
			<p>
			<a href="index.php">Back to demonstration index</a>.
			</p>
		</div>
	</body>
</html>
