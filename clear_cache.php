<?php
session_start();

$user_dir = "./users/{$_SESSION['USERNAME']}/";
require $user_dir . 'config.php';

if (!$_SESSION['USERNAME']) {
    header('Location: login.php');
    exit;
}

$cache_file = ($type != 'html') ? $user_dir . 'cache.' . $type : $user_dir . 'cache';
$reset = 'Welcome, and thanks for using <a href="http://www.zdxia.com/projects/tapp">Tapp</a> !';

// 清空 cache 文件
file_put_contents($cache_file, '');
file_put_contents($user_dir . 'latest.tweet', $reset);

// header('refresh: 3; url=index.php');
header('Location: index.php');

?>
