<?php
session_start();

if (!$_SESSION) {
    header('Location: login.php');
    exit;
}

$user_dir = "users/{$_SESSION['USERNAME']}/";
$cache_name = $user_dir . 'cache';

include_once 'function.php';
include_once $user_dir . 'config.php';

// Cache 设置
// new cache file 因为 $type 会更改的关系放在下方
$old_cache_file = ($type != 'html') ? ($cache_name . '.' . $type) : $cache_name;
$cache_url = 'http://' . $_SERVER['SERVER_NAME'] . dirname($_SERVER['PHP_SELF']) . '/' . $user_dir . $cache_file;

if ($_POST['submit']) {
    $twitter_username = $_SESSION['USERNAME'];
 
    // Twitter API
    $timeline_api = 'http://api.twitter.com/statuses/user_timeline/' . $twitter_username. '.' . 'json';

    if (!empty($_POST['amount'])) {
        $amount = $_POST['amount'];
        if (file_exists($old_cache_file)) unlink($old_cache_file);
    }

    $types = array('html', 'rss', 'json');
    if (!empty($_POST['output'])) {
        $_POST['output'] = strtolower($_POST['output']);

        if (in_array($_POST['output'], $types)) {
            $type = $_POST['output'];
            if (file_exists($old_cache_file)) unlink($old_cache_file);
        }
    }

    $replies = array('yes', 'no');
    if (!empty($_POST['reply'])) {
        if (in_array($_POST['reply'], $replies)) {
            $reply = $_POST['reply'];
            if (file_exists($old_cache_file)) unlink($old_cache_file);
        }
    }

    if (!empty($_POST['cache_time'])) {
        $cache_time = $_POST['cache_time'];
        if (file_exists($old_cache_file)) unlink($old_cache_file);
    }

    // fixme: 这样做很不安全，不过是个很有趣的尝试
    // 更好的选择是使用数据库
    ignore_user_abort(true);
    $config = '<?php' . "\n" .
              '$twitter_username = \'' . $twitter_username . "';\n" .
              '$cache_time = \'' . $cache_time . "';\n" .
              '$amount = \'' . $amount . "';\n" .
              '$type = \'' . $type . "';\n" . 
              '$reply = \'' . $reply . "';\n" .
              '?>';
    file_put_contents($user_dir . 'config.php', $config);

    // new cache file
    $cache_file = ($type != 'html') ? ($cache_name . '.' . $type) : $cache_name;
    if (!file_exists($cache_file)) {
        touch($cache_file);
    }

    // 获取原始 Twitter JSON data
    $ret = process($timeline_api);
    $primitive = json_decode($ret, true);

    // 最新的一条 tweet
    $text = urlparse($primitive[0]['text']);
    file_put_contents($user_dir . 'latest.tweet', $text);

    // 按 amount 截取条目
    $data = array();
    for ($i = 0; $i < $amount; $i++) {
        $data[] = $primitive[$i];
    }

    // 按 amount 截取条目
    if ($reply == 'no') {
        $k = 0;
        foreach ($data as $key) {
            if ($key['in_reply_to_user_id'] != '') {
                unset($data[$k]);
            }
            else {
                $tem_arr[] = $data[$k];
            }
            $k++;
        }
        $data = $tem_arr;
    }

    // 输出 cache
    catchOutput($type, $data);

    header('Location: ./');
    exit;
}
else {
    require('template.php');
}
?>
