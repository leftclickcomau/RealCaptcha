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
$captcha = new \RealCaptcha\RealCaptcha($_GET);
$captcha->writeImage();
