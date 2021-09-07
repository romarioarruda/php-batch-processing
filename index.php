<?php
require_once 'vendor/autoload.php';

use App\Services\Coupon;
use App\Services\FileSystem;

$configEnv = parse_ini_file('env.ini');

$coupon = new Coupon($configEnv, new FileSystem);
$coupon->searchCoupon();