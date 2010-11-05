<?php
require 'function.php';

function print_tweet() {
    global $type;
    $cache_file = ($type != 'html') ? 'cache.' . $type : 'cache';

    if (!file_exists($cache_file)) {
        echo '还没有任何缓存文件，请先进行设置';
    }
    else {
        $check = file_get_contents($cache_file);

        if (empty($check)) {
            echo '缓存文件被清空了';
            exit;
        }

        if ($type == 'json') {
            echo str_replace('\\', '/', dirname(__FILE__)) . '/' . $cache_file;
        }
        elseif ($type == 'rss') {
            echo str_replace('\\', '/', dirname(__FILE__)) . '/' . $cache_file;
        }
        else {
            $data= unserialize($check);
            echo '<div class="tapp_tweet"><ul>';
            foreach ($data as $key) {
                echo '<li>' . urlparse($key['text']) . ' ' . $key['date'] . '</li>';
            }
            echo '</ul></div>';
        }
    }
}

print_tweet();
?>
