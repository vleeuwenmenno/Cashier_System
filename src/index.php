<?php
    include_once("includes.php");

    if (Permissions::checkSession("", false))
    {
        header("Location: master.php");
    }
?>
<html>
    <head>
        <!-- Bootstrap and all it's dependencies -->
        <link rel="stylesheet" href="themes/<?php echo $_CFG['THEME']; ?>/bootstrap.css" />
        <link rel="stylesheet" href="themes/<?php echo $_CFG['THEME']; ?>/stylesheet.css">
        <link rel="stylesheet" href="themes/<?php echo $_CFG['THEME']; ?>/select2.min.css" />
        <link rel="stylesheet" href="themes/<?php echo $_CFG['THEME']; ?>/bootstrap-combobox.css" />
        <script src="js/jquery.js"></script>
        <script src="js/bootstrap.min.js"></script>
    </head>
    <body>
        <div class="row">
            <div class="col-sm-3">

            </div>
            <div class="col-sm-6">
                <form class="ui form panel" style="text-align: left !important;" action="index.php?login<?php if (isset($_GET['r'])) { echo '&r=' . $_GET['r']; }?>" method="POST" enctype="multipart/form-data">
                    <h2>Kassa</h2>

                    <div class="form-group">
                        <label for="username">Medewerker: </label>
                        <input type="text" class="form-control" name="username" id="username" readonly onfocus="this.removeAttribute('readonly');"/>
                    </div>
                    <div class="form-group">
                        <label for="passwrd">Wachtwoord: </label>
                        <input type="password" class="form-control" name="passwrd" id="passwrd" readonly onfocus="this.removeAttribute('readonly');"/>
                    </div>
                    <center><button type="submit" class="btn btn-primary" style="width: 196px;">Login</button></center>
                    <?php

                    if (isset($_POST['username']) || isset($_POST['passwrd']))
                    {
                        $_SESSION['login'] = array(
                            'user' => trim($_POST['username']),
                            'pass' => $_POST['passwrd']);
                    ?>
                    <script>
                        (function()
                        {
                            window.location.replace("login.php<?php if (isset($_GET['r'])) { echo '?r=' . $_GET['r']; } else { echo '?r=index.php'; } ?>");
                        })();
                    </script>
                    <?php
                    }

                    if (isset($_SESSION['prob']))
                    {
                        echo $_SESSION['prob'];
                    }
                    ?>
	             </form>
            </div>
            <div class="col-sm-2"></div>
        </div>
    </body>
</html>
