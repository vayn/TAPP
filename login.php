<?php
session_start();
include_once 'users.php';

if ($_POST['submit']) {
    foreach ($users as $key) {
        list($user, $pw) = explode(':', $key);
        if ($_POST['user'] == $user && $_POST['pw'] == $pw) {
            $_SESSION['USERNAME'] = $user;
            $user_dir = './users/' . $user . '/';

            if (!file_exists($user_dir)) {
                if (mkdir($user_dir, 0777)) {
                    copy('./users/config.sample.php', $user_dir . 'config.php');
                    copy('./users/include.sample.php', $user_dir . 'include.php');
                }
                else {
                    echo '创建目录失败，请检查权限设置';
                }
            }
            header('Location: index.php');
            exit;
        }
    }
    header('Location: ' . $_SERVER['SCRIPT_NAME'] . '?error=1');
    exit;
}
else {
?>
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
</head> 
<body class="login"> 
    <header> 
        <h1>TAPP</h1> 
    </header> 
    <hr /> 
    <section> 
        <form action="<?=$_SERVER['SCRIPT_NAME']?>" method="post"> 
            <?php
                if ($_GET['error'] == 1) {
                    echo '<p class="error">Invalid password, try again.</p>';
                }
            ?>
            <fieldset> 
                <p> 
                    <label for="user">User:</label> 
                    <input id="user" name="user" value="" type="text" tabindex="1" /> 
                </p> 
                <p> 
                    <label for="userid">Password:</label> 
                    <input id="userid" class="long" name="pw" value="" type="password" tabindex="2" /> 
                </p> 
            </fieldset> 
            <fieldset> 
                    <input type="submit" name="submit" class="clean-gray" tabindex="3" value="Login" id="save_submit"> 
            </fieldset> 
        </form> 
    </section>
	<footer>
		<p><a href="http://www.zdxia.com/projects/tapp">Tapp</a> <small>1.0.0</small> &copy; <a href="http://www.zdxia.com/">disinfeqt</a> and <a href="http://elnode.com/">vayn</a></p>
	</footer>
</body> 
</html>
<? } ?>
