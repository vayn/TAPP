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
            <a href="https://github.com/vayn/tapp">Tapp</a>
            <small>2.0.0</small>
            &copy; <a href="http://disinfeqt.com/">disinfeqt</a> and <a href="http://elnode.com/">vayn</a>
        </p>
    </footer>
</body> 
</html>
