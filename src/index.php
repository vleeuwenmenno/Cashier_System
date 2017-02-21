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
                        <label for="employee">Medewerker: </label>
                        <input type="text" class="form-control" name="employee" id="employee">
                    </div>
                    <div class="form-group">
                        <label for="passwrd">Wachtwoord: </label>
                        <input type="password" class="form-control" name="passwrd" id="passwrd">
                    </div>
                    <button type="submit" class="btn btn-primary">Login</button>
                    <button type="button" class="btn btn-default">Annuleren</button>
                    <?php

                    if (isset($_POST['employee']) || isset($_POST['passwrd']))
                    {
                        $_SESSION['login'] = array(
                            'user' => trim($_POST['employee']),
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
                    if (isset($_GET['notice']))
                    {
                        echo $_GET['notice'];
                    }

                    ?>
	             </form>
            </div>
            <div class="col-sm-2"></div>
        </div>
    </body>
</html>
