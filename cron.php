<?php
include_once 'function.php';
include_once 'users.php';

foreach ($users as $key) {
    list($user, $pw) = explode(':', $key);
    $user_dir = "./users/{$user}/";

    if (!file_exists($user_dir)) continue;

    include_once $user_dir . 'config.php';

    // Cache 设置
    $cache_name = $user_dir . 'cache';
    $cache_file = ($type != 'html') ? $cache_name . '.' . $type : $cache_name;

    // Twitter API
    $timeline_api = 'http://api.twitter.com/statuses/user_timeline/' . $user. '.' . 'json';

    if (time() - filemtime($cache_file) > $cache_time) {
        $primitive = json_decode(process($timeline_api), true);
        $text = urlparse($primitive[0]['text']);
        $time = date('D M j H:i Y', strtotime($primitive[0]['created_at']));

        // 最新的一条 tweet
        file_put_contents($user_dir . 'latest.tweet', $text);

        $data = array();
        for ($i = 0; $i < $amount; $i++) {
            $data[] = $primitive[$i];
        }

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

        // 生成图片
        include_once 'show.php';
    }
}
?>
