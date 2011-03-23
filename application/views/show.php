<body class="main" onload="sh_highlightDocument();"> 
    <header> 
        <h1>TAPP</h1>
    </header> 
    <hr /> 
    <section> 
        <p>Your ShowPic URL:</p>
        <textarea rows="1" cols="25" onclick="select();"><?php echo $src; ?></textarea>
        <script type="text/javascript">
        function select() {
            var text = document.form1.type();
            text.focus();
            text.select();
        }
        </script>
        <hr />
        <img src="<?php echo $src ?>" alt="TAPP ShowPic" width="350" />
        <hr /> 
    </section> 
