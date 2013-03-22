<?php
/*!
 * This file is a part of RealCaptcha human verification system.
 * Copyright (c) Ben New, Leftclick.com.au
 * See the LICENSE and README files in the main source directory for details.
 */

/**
 * RealCaptcha bootstrap demonstration.
 */
include '../vendor/autoload.php';
$type = isset($_GET['type']) ? $_GET['type'] : \RealCaptcha\RealCaptcha::TYPE_ALPHANUMERIC;
$options = $_GET;
unset($options['type']);
$captcha = new \RealCaptcha\RealCaptcha($type, $options);
$captcha->writeImage();
