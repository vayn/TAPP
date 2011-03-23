<body class="main" onload="sh_highlightDocument();"> 
    <header> 
        <h1>TAPP</h1>
    </header> 
    <hr /> 
    <section> 
    <?php echo validation_errors('<p class="error">', '</p>'); ?> 

        <?php echo form_open('setting/index'); ?>

            <?php echo form_fieldset(); ?>
                <p> 
                    <label for="user">Twitter username:</label> 
                    <?php echo form_input($user, $setting->twitter); ?>
                </p> 
                <span class="vr"></span> 
                <p> 
                    <label for="amount">Amount:</label> 
                    <?php echo form_dropdown('amount', $amount['options'], $setting->amount); ?>
                </p> 
                <span class="vr"></span> 
                <p> 
                    <label for="reply">@ Replies?</label> 
                    <?php
                    foreach ($reply as $reply):
                        if ($reply['value'] == $setting->reply) {
                            echo form_radio($reply, '', 'True');
                        }
                        else {
                            echo form_radio($reply);
                        }
                        echo form_label($reply['value'], $reply['value']);
                    endforeach;
                    ?>
                </p> 
            <?php echo form_fieldset_close(); ?>

            <hr /> 

            <?php echo form_fieldset(); ?>
                <p> 
                    <label for="username">Cache time:</label> 
                    <?php echo form_dropdown('cache_time', $cache_time['options'], $setting->cache_time, 'class="long"') ?>
                </p> 
                <span class="vr"></span> 
                <p> 
                    <label for="type">Output:</label> 
                    <?php
                    foreach ($types as $type):
                        if ($type['value'] == $setting->type) {
                            echo form_radio($type, '', 'True');
                        }
                        else {
                            echo form_radio($type);
                        }
                        echo form_label($type['value'], $type['value']);
                    endforeach;
                    ?>
                </p> 
            <?php echo form_fieldset_close(); ?>

            <hr /> 

            <?php echo form_fieldset(); ?>
                <?php echo form_submit($submit); ?>
            <?php echo form_fieldset_close(); ?>

        <?php echo form_close(); ?>

        <hr /> 
        <p> 
            <label>Your latest tweet:</label> 
        </p> 
        <div class="spch-bub-inside"> 
            <em> 
                <b><?php
                if (!empty($latest)) {
                    echo $latest;
                }
                else {
                    echo file_get_contents($user_dir.'/../latest');
                }
                ?></b>
            </em> 
        </div> 
        <hr /> 
        <div id="include">
            <p class="clearfix"> 
                <label for="url">Your include code:</label>
                <?php
                switch ($setting->type):
                    case 'html':
                        echo "<pre class='sh_php'><code>&lt?php\n" .
                            "require('".$user_dir."include.php');\n" .
                            '?&gt;</code></pre>';
                    break;

                    case 'rss':
                        echo '<pre><code><a target="_blank" href="'.$cache.'cache.rss">'.$cache.'cache.rss'.'</a></code></pre>';
                    break;
                    
                    case 'json':
                ?>
<pre class="sh_html"><code>&lt;!-- HTML --&gt;
&lt;div id="twitter_div"&gt;
&lt;h2 class="sidebar-title"&gt;Twitter Updates&lt;/h2&gt;
&lt;ul id="twitter_update_list"&gt;&lt;/ul&gt;
&lt;/div&gt;
&lt;!-- Javascript --&gt;
&lt;script type="text/javascript" src="<?php echo $url; ?>static/js/twitter.js"&gt;&lt;/script&gt;
&lt;script type="text/javascript" src="<?php echo $cache; ?>cache.json"&gt;&lt;/script&gt;
</code></pre>
                <?php
                    break;
                endswitch;
                ?>
            </p> 
        </div> 
    </section> 
