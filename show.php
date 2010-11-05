<?php
session_start();
include_once 'function.php';

$user = $_SESSION['USERNAME'] ? $_SESSION['USERNAME'] : $user;

$api = 'http://api.twitter.com/1/users/profile_image/' . $user. '.' . 'json?size=normal';
$ret = process($api);

// cron.php 调用的话会取 tweet 时间
$time = empty($time) ? date('D M j T Y', time()) : $time;
$user_dir = './users/' . $user . '/';

if (strpos($ret, 'PNG')) {
    file_put_contents($user_dir . 'avatar.png', $ret);
    $format = 'png';
}
elseif (strpos($ret, 'GIF89')) {
    file_put_contents($user_dir . 'avatar.gif', $ret);
    $format = 'gif';
}
else {
    file_put_contents($user_dir . 'avatar.jpg', $ret);
    $format = 'jpg';
}

nowIMG($user, $time, $format);

$url = 'http://' . $_SERVER['SERVER_NAME'] .
    substr($_SERVER['PHP_SELF'], 0, strpos($_SERVER['PHP_SELF'], basename($_SERVER['SCRIPT_NAME']))) .
    'users/' . $user . '/';
echo "图片地址：<a href=\"{$url}show.png\" target=\"_blank\">" . $url . 'show.png</a>';
?>
