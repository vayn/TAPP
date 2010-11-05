<!DOCTYPE HTML> 
<html lang="en-US"> 
<head> 
	<meta charset="UTF-8"> 
	<title>TAPP</title>   
	<link rel="stylesheet" type="text/css" href="css/style.css" media="all" />
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js" type="text/javascript"></script> 
	<script type="text/javascript" src="js/plugins.js"></script>
	<script type="text/javascript">$(function(){$("select,input:radio").uniform();});</script> 
	<!--[if lt IE 9]>
	<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]--> 
</head>
<body class="main" onload="sh_highlightDocument();"> 
    <header> 
        <h1>TAPP</h1>
    </header> 
    <hr /> 
    <section> 
        <form action="index.php" method="post"> 
            <fieldset> 
                <p> 
                    <label for="userid">Twitter username:</label> 
                    <input id="userid" class="text" name="username" value="<?=$_SESSION['USERNAME']?>" type="text" tabindex="1" disabled="disabled" /> 
                </p> 
                <span class="vr"></span> 
                <p> 
                    <label for="tweets">Amount:</label> 
                    <select name="amount"> 
                    <?php
                    for ($i = 1; $i < 11; $i++) {
                        if ($i == $amount) {
                            echo "<option value='$i' selected>$i</option>";
                        }
                        else {
                            echo "<option value='$i'>$i</option>";
                        }
                    }
                    ?>
                    </select> 
                </p> 
                <span class="vr"></span> 
                <p> 
                    <label for="tweets">@ Replies?</label> 
                    <input type="radio" name="reply" id="yes" value="yes" <?if($reply == 'yes') echo 'checked';?> /><label for="yes">Yes</label> 
                    <input type="radio" name="reply" id="no" value="no" <?if($reply == 'no') echo 'checked';?> /><label for="no">No</label> 
                </p> 
            </fieldset> 
            <hr /> 
            <fieldset> 
                <p> 
                    <label for="username">Cache time:</label> 
                    <select class="long" name="cache_time"> 
                        <option value='<?=$t=300?>' <?if($cache_time==$t) echo 'selected'?>>5 min</option>
                        <option value='<?=$t=600?>' <?if($cache_time==$t) echo 'selected'?>>10 min</option>
                        <option value='<?=$t=1200?>' <?if($cache_time==$t) echo 'selected'?>>20 min</option>
                        <option value='<?=$t=1800?>' <?if($cache_time==$t) echo 'selected'?>>30 min</option>
                        <option value='<?=$t=3600?>' <?if($cache_time==$t) echo 'selected'?>>1 hour</option>
                    </select> 
                </p> 
                <span class="vr"></span> 
                <p> 
                    <label for="tweets">Output:</label> 
                    <input type="radio" name="output" value="json" id="json" <?if($type == 'json') echo 'checked';?> /><label for="json">JSON</label> 
                    <input type="radio" name="output" value="html" id="html" <?if($type == 'html') echo 'checked';?> /><label for="html">HTML</label> 
                    <input type="radio" name="output" value="rss" id="rss" <?if($type == 'rss') echo 'checked';?> /><label for="php">RSS</label> 
                </p> 
            </fieldset> 
            <hr /> 
            <fieldset> 
                    <input type="submit" name="submit" class="clean-gray" tabindex="3" value="Save" id="save_submit"> 
            </fieldset> 
        </form> 
        <hr /> 
        <p> 
            <label>Your latest tweet:</label> 
        </p> 
        <div class="spch-bub-inside"> 
            <em> 
                <b><?=@file_get_contents(file_exists($user_dir.'latest.tweet')?$user_dir.'latest.tweet':'latest.tweet')?></a></b> 
            </em> 
        </div> 
        <hr /> 
        <div id="include">
            <p class="clearfix"> 
                <label for="url">Your include code:</label>
                <?php
                    $cache_file = ($type != 'html') ? ($cache_name . '.' . $type) : $cache_name;
                    $url = 'http://' . $_SERVER['SERVER_NAME'] .
                            substr($_SERVER['PHP_SELF'], 0,
                                strpos($_SERVER['PHP_SELF'], basename($_SERVER['SCRIPT_NAME'])));

                    if (file_exists($cache_file) && filesize($cache_file) != 0) {
                        switch ($type) {
                            case 'html':
                                echo "<pre class='sh_php'><code>&lt?php\n" .
                                    "require('" . str_replace('\\', '/', dirname(__FILE__)) . "/include.php');\n" .
                                    '?&gt;</code></pre>';
                                break;

                            case 'rss':
                                echo '<pre><code><a target="_blank" href="' . $cache_url . 'cache.rss">' . $cache_url. 'cache.rss' . '</a></code></pre>';
                                break;
                            
                            case 'json':
                                $op = <<<HTML
<pre class="sh_html"><code>&lt;!-- HTML --&gt;
&lt;div id="twitter_div"&gt;
&lt;h2 class="sidebar-title"&gt;Twitter Updates&lt;/h2&gt;
&lt;ul id="twitter_update_list"&gt;&lt;/ul&gt;
&lt;/div&gt;
&lt;!-- Javascript --&gt;
&lt;script type="text/javascript" src="{$url}js/twitter.js"&gt;&lt;/script&gt;
&lt;script type="text/javascript" src="{$cache_url}cache.json"&gt;&lt;/script&gt;
</code></pre>
HTML;
                                echo $op;
                                break;
                        }
                    }
                    else {
                        echo '<pre><code>还没有任何缓存文件</code></pre>';
                    }
                ?>
            </p> 
        </div> 
    </section> 
<?php require 'footer.php' ?>
