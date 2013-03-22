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
		<div id="container">
			<h1>RealCaptcha: Dynamic Captcha Demonstration</h1>
			<p>
			The following is a simple demonstration form which allows dynamic switching between Alphanumeric and Mathematical modes, and utilises browser cookies to remember the user's selection between visits.
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
							<img src="" id="captcha-image" />
							<ul id="captcha-options" style="display:none;">
								<li>Reload...</li>
								<li><a href="" onclick="document.getElementById('captcha-image').setAttribute('src', 'captcha.php?type=Alphanumeric&amp;r=' + Math.floor(Math.random() * 10000)); document.cookie = 'captcha-type=Alphanumeric'; return false;">Alphanumeric</a></li>
								<li><a href="" onclick="document.getElementById('captcha-image').setAttribute('src', 'captcha.php?type=Mathematical&amp;r=' + Math.floor(Math.random() * 10000)); document.cookie = 'captcha-type=Mathematical'; return false;">Mathematical</a></li>
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
			<p>
			<a href="index.php">Back to demonstration index</a>.
			</p>
		</div>
		<address>
			RealCaptcha is an open source library provided by <a href="http://leftclick.com.au/">Leftclick.com.au</a>.
		</address>
		<script type="text/javascript">//<![CDATA
			window.onload = function() {
				var regex = new RegExp("(?:^|.*;\\s*)captcha-type\\s*\\=\\s*((?:[^;](?!;))*[^;]?).*"),
					type = document.cookie.match(regex) ? document.cookie.replace(regex, "$1") : null;
				document.getElementById('captcha-image').setAttribute('src', 'captcha.php?r=' + Math.floor(Math.random() * 10000) + (type ? '&type=' + type : ''));
			};
		//]]></script>
	</body>
</html>
