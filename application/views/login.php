<!DOCTYPE HTML> 
<html lang="en-US"> 
<head> 
    <meta charset="UTF-8"> 
    <title>TAPP</title>   
    <link rel="stylesheet" type="text/css" href="static/css/style.css" media="all" />
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js" type="text/javascript"></script> 
    <script type="text/javascript" src="static/js/plugins.js"></script>
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

        <?php echo form_open('login/submit'); ?>

            <?php if (!empty($error)): ?>
            <p class="error">Username and/or password is not correct, please try again.</p>
            <?php endif; ?>

            <?php echo form_fieldset(); ?>
                <p> 
                    <label for="user">User:</label> 
                    <?php echo form_input($user); ?>
                </p> 
                <p> 
                    <label for="userid">Password:</label> 
                    <?php echo form_password($password); ?>
                </p> 
            <?php echo form_fieldset_close(); ?>

            <?php echo form_fieldset(); ?>
                <?php echo form_submit($submit); ?> 
            <?php echo form_fieldset_close(); ?>

        <?php echo form_close(); ?>

    </section>
    <footer>
        <p>
            <a href="http://www.zdxia.com/projects/tapp">Tapp</a>
            <small>2.0.0</small>
            &copy; <a href="http://www.zdxia.com/">disinfeqt</a> and <a href="http://elnode.com/">vayn</a>
        </p>
    </footer>
</body> 
</html>
