<?
//
// 抓取函数
//
function process($url) {
    // HTTPHEADER
    // http://is.gd/g0h5v
    $headers = array('X-Twitter-Client: TAPP', 'X-Twitter-Client-Version: 2.0', 'X-Twitter-Client-URL:http://elnode.com');
    $responseInfo = array();

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_NOBODY, 0); // 读取页面内容
    curl_setopt($ch, CURLOPT_HEADER, 0); // 返回内容中不包含 HTTP 头
    curl_setopt($ch, CURLOPT_USERAGENT, 'TAPP'); // 设置 UserAgent
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); // 支持 Location 重定向
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // 保存输出
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers); // 设置 HTTPHEADER

    $ret = curl_exec($ch);
    $responseInfo = curl_getinfo($ch);
    curl_close($ch);

    if (intval($responseInfo['http_code']) != 200) {
        return False
    }
    else {
        return $ret;
    }
}
//
//
//


//
// RSS 生成函数
//
function generateRss($data) {
    global $cache_url;

    $rsshead =<<<XML
<?xml version="1.0" encoding="UTF-8"?>
<rss xmlns:atom="http://www.w3.org/2005/Atom" version="2.0" xmlns:georss="http://www.georss.org/georss" xmlns:twitter="http://api.twitter.com">
  <channel>
   <title>TAPP</title>
   <link>$cache_url</link>
   <atom:link href="$cache_url" rel="self" type="application/rss+xml" />
   <description>TAPP updates from Twitter</description>
   <language>en-us</language>
   <ttl>40</ttl>
XML;
    if (empty($data)) {
        die('Powered by <a href="">TAPP</a>');
    }

    foreach ($data as $tweet) {
        $rss_title = $tweet['user']['screen_name'] . ': ' . $tweet['text'];
        $rss_date = $tweet['created_at'];
        $rss_guid = 'http://twitter.com/' . $tweet['user']['screen_name'] . '/statuses/' . $tweet['id'];

        $rssbody .= <<<XML
<item>
  <title>$rss_title</title>
  <description>$rss_title</description>
  <pubDate>$rss_date</pubDate>
  <guid>$rss_guid</guid>
  <link>$rss_guid</link>
</item>
XML;
    }

    $rssfooter = <<<XML
 </channel>
</rss>
XML;
    return $rsshead . $rssbody . $rssfooter;
}
//
//
//


//
// Metainfo 识别 
//
function urlparse($text) {
    // 识别 url
    $text = preg_replace("#\b(http://(\S+))#i",
                 "<a href=\"$1\" target=\"_blank\">$2</a>",
                 $text);

    // 识别 @username
    $text = preg_replace("#@([a-z0-9_]+)\b#i",
                 "<a href=\"http://twitter.com/$1\">@$1</a>",
                 $text);

    // 识别 hashtag
    $text = preg_replace("/#([a-z0-9]+)\b/i",
                 "<a href=\"http://twitter.com/search?q=%23$1\">#$1</a>",
                 $text);

    return $text;
}
//
//
//


//
// Cache output 函数
//
function catchOutput($type, $data) {
    global $user_dir;

    if ($type == 'json') {
        $data = json_encode($data);
        $data = 'twitterCallback2(' . $data . ');';
        file_put_contents($user_dir . 'cache.json', $data);
    }
    elseif ($type == 'rss') {
        $data = generateRss($data);
        file_put_contents($user_dir . 'cache.rss', $data);
    }
    else {
        $i = 0;
        foreach ($data as $key) {
            $tem_arr[$i]['text'] = $key['text'];
            $tem_arr[$i]['date'] = '<a href="http://twitter.com/' . $key['user']['screen_name'] . '/status/' . $key['id'] . '">' . relativeTime($key['created_at']) . '</a>';
            $i++;
        }

        $data = serialize($tem_arr);
        file_put_contents($user_dir . 'cache', $data);
    }
}
//
//
//


//
// Relative time 函数
//
function relativeTime($time) {
    $now = time(); // 现在时间
    $timestamp = strtotime($time); // 传入的时间
    $differ = $now - $timestamp; // 时间差

    if ($differ < 60) {
        return 'less than a minute ago';
    } elseif($differ < 120) {
        return 'about a minute ago';
    } elseif($differ < (60*60)) {
        return ceil($differ / 60) . ' minutes ago';
    } elseif($differ < (120*60)) {
        return 'about an hour ago';
    } elseif($differ < (24*60*60)) {
        return 'about ' . ceil($differ / 3600) . ' hours ago';
    } elseif($differ < (48*60*60)) {
        return '1 day ago';
    } else {
        return ceil($differ / 86400) . ' days ago';
    }
}
//
//
//


//
// 图片生成函数
//
function nowIMG($user, $time, $format, $style = 0) {
    /* S:特别感谢 Sunyanzi @V2EX */
    /* V:特别感谢 Sai @V2EX */
    global $user_dir;

    $before = file_get_contents($user_dir . 'latest.tweet');
    $clean = array("\n","\r","\n\r","</a>");
    $before = str_replace($clean, '', $before);
    $before = preg_replace("/<a[^>]+>/i", '', $before);

    if ($style == '0') {
        $step = '42';
    } else {
        $step = '24';
    }
    for ($i=0;$i<mb_strlen($before);$i=$i+$step) {
        $after .= mb_substr($before,$i,$step,'utf-8')."\n\r";
    }

    $now_content = $after;
    $now_author = $user . ' says: ';
    $now_date = $time .' via twitter';
    $now_icon ='';

    // header("Content-type: image/png");
    $avatar = $user_dir . 'avatar.' . $format;
    $date_font = './show/04b03.ttf';
    $font = './show/wqy.ttc';
    $image_tem = imagecreatetruecolor(32, 32);

    if ($style == '0') {
        $fn = './show/wbg/wbg2.jpg';
        switch ($format) {
            case 'png':
                $image[0] = imagecreatefrompng($avatar);

                imagesavealpha($image_tem, true);
                imagealphablending($image_tem, false);
                break;

            case 'gif':
                $image[0] = imagecreatefromgif($avatar);
                break;

            case 'jpg':
                $image[0] = imagecreatefromjpeg($avatar);
                break;
        }

        imagecopyresampled($image_tem, $image[0], 0, 0, 0, 0, 32, 32, 48, 48);
        $image[0] = $image_tem;

        $image[1] = imagecreatefromjpeg($fn);
        // $now = imagecopymerge($image[1],$image[0],12,16,0,0,32,32,100);
        $now = imagecopy($image[1],$image[0],12,16,0,0,32,32);
        $fg = imagecolorallocate($image[1], 240, 240, 230);
        $bg = imagecolorallocate($image[1], 120, 140, 190);
        $bbg = imagecolorallocate($image[1], 20, 40, 40);
        $fs = 10;
        imagettftext($image[1], $fs, 0, 58, 47, $fg, $font, $now_content);
        imagettftext($image[1], 10, 0, 55, 29, $bbg, $font, $now_author);
        imagettftext($image[1], 10, 0, 55, 28, $fg, $font, $now_author);
        imagettftext($image[1], 6, 0, 257, 115, $fg, $date_font, $now_date);
        imagettftext($image[1], 8, 0, 10, 115, $fg, $font, $now_icon);
    } else {
        $fn = './show/wbg/wbg_bubble.gif';
        $image[0] = imagecreatefromjpeg($avatar);
        $image[1] = imagecreatefromgif($fn);
        $now = imagecopymerge($image[1],$image[0],12,16,0,0,32,32,100);
        $fs = 10;
        imagettftext($image[1], $fs, 0, 58, 47, 0, $font, $now_content);
        imagettftext($image[1], 10, 0, 58, 29, 0, $font, $now_author);
        imagettftext($image[1], 6, 0, 270, 107, 0, $date_font, $now_date);
        imagettftext($image[1], 8, 0, 305, 29, 0, $font, $now_icon);
    }
    // imagepng($image[1]);
    imagepng($image[1], $user_dir . 'show.png');
}
//
//
//
?>
