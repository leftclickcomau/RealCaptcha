<?php
/*!
 * This file is a part of RealCaptcha human verification system.
 * Copyright (c) Ben New, Leftclick.com.au
 * See the LICENSE and README files in the main source directory for details.
 */

/**
 * RealCaptcha code check demonstration.  This is just a simple script demonstrating usage that can be integrated into
 * any form generation and/or processing mechanism, or by custom processing scripts.
 */
include '../vendor/autoload.php';
$captcha = new \RealCaptcha\RealCaptcha($_GET);
?>
<h1>Form Results</h1>
<p>
The following values were submitted:
</p>
<dl>
<?php
foreach ($_POST as $name => $value) {
?>
	<dt><?php echo $name; ?></dt>
	<dd><?php echo $value; ?></dd>
<?php
}
?>
</dl>
<p>
The captcha submission was <strong><?php echo $captcha->checkCode($_POST['captcha']) ? 'valid' : 'invalid'; ?></strong>.
</p>
