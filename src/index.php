<?php
    include_once("includes.php");

    if (Permissions::checkSession("", false))
    {
        header("Location: master.php");
    }
?>
<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.2">

        <link rel="stylesheet" href="http://<?php echo $_CFG['HOST_NAME']; ?>/css/bootstrap-switch.min.css" />
        <link rel="stylesheet" href="http://<?php echo $_CFG['HOST_NAME']; ?>/css/multiple-emails.css" />
        <link rel="stylesheet" href="http://<?php echo $_CFG['HOST_NAME']; ?>/css/bootstrap.css" />
        <link rel="stylesheet" href="http://<?php echo $_CFG['HOST_NAME']; ?>/css/stylesheet.css">
        <link rel="stylesheet" href="http://<?php echo $_CFG['HOST_NAME']; ?>/css/select2.min.css" />
        <link rel="stylesheet" href="http://<?php echo $_CFG['HOST_NAME']; ?>/css/bootstrap-combobox.css" />
        <link rel="stylesheet" href="http://<?php echo $_CFG['HOST_NAME']; ?>/css/font-awesome.css" />
        <link rel="stylesheet" href="http://<?php echo $_CFG['HOST_NAME']; ?>/css/multiple-emails.css" />

        <script src="http://<?php echo $_CFG['HOST_NAME']; ?>/js/jquery.min.js"></script>
        <script src="http://<?php echo $_CFG['HOST_NAME']; ?>/js/multiple-emails.js"></script>
        <script src="http://<?php echo $_CFG['HOST_NAME']; ?>/js/bootstrap.min.js"></script>
        <script src="http://<?php echo $_CFG['HOST_NAME']; ?>/js/bootstrap-notify.min.js"></script>
        <script src="http://<?php echo $_CFG['HOST_NAME']; ?>/js/select2.full.min.js"></script>
        <script src="http://<?php echo $_CFG['HOST_NAME']; ?>/js/jquery.jeditable.js"></script>
        <script src="http://<?php echo $_CFG['HOST_NAME']; ?>/js/bootstrap-combobox.js"></script>
        <script src="http://<?php echo $_CFG['HOST_NAME']; ?>/js/jquery.print.js"></script>
    </head>
    <body>
        <div class="row">
            <div class="col-sm-4"></div>
            <div class="col-sm-4">
                <form id="loginDiv" name="loginDiv" style="text-align: left !important;" action="index.php?login<?php if (isset($_GET['r'])) { echo '&r=' . $_GET['r']; }?>" method="POST" enctype="multipart/form-data">
                    <h2>Kassa</h2>

                    <div class="form-group" id="usDiv">
                        <label for="username">Medewerker: </label>
                        <input type="text" class="form-control" name="username" id="username" readonly onfocus="this.removeAttribute('readonly');"/>
                    </div>
                    <div class="form-group" id="pwDiv">
                        <label for="passwrd">Wachtwoord: </label>
                        <input type="password" class="form-control" name="passwrd" id="passwrd" readonly onfocus="this.removeAttribute('readonly');"/>
                    </div>
                    <center><button id="submitBtn" class="btn btn-primary" style="width: 196px;">Login</button></center>
                    <center>
                        <div id="loaderAnimation" style="display: none;">
                            <svg xmlns="http://www.w3.org/2000/svg" version="1.1">
                            <defs>
                                <filter id="gooey">
                                <feGaussianBlur in="SourceGraphic" stdDeviation="10" result="blur"></feGaussianBlur>
                                <feColorMatrix in="blur" mode="matrix" values="1 0 0 0 0  0 1 0 0 0  0 0 1 0 0  0 0 0 18 -7" result="goo"></feColorMatrix>
                                <feBlend in="SourceGraphic" in2="goo"></feBlend>
                                </filter>
                            </defs>
                            </svg>
                            <div class="blob blob-0"></div>
                            <div class="blob blob-1"></div>
                            <div class="blob blob-2"></div>
                            <div class="blob blob-3"></div>
                            <div class="blob blob-4"></div>
                            <div class="blob blob-5"></div>
                            <center>Bezig met inloggen...</center>
                        </div>
                    </center>
                    <script>
                            $(document).ready(function() {
                                $("#submitBtn").click(function () {
                                    $("#submitBtn").hide();
                                    $("#usDiv").hide();
                                    $("#pwDiv").hide();
                                    
                                    $("#loaderAnimation").show();
                                    $("#loginDiv").submit();
                                });
                            });
                        </script>
                    <?php

                    if (isset($_POST['username']) || isset($_POST['passwrd']))
                    {
                        ?>
                        <script>
                            $(document).ready(function() {
                                $("#loginDiv").hide();
                            });
                        </script>
                        <div id="loaderAnimation">
                            <svg xmlns="http://www.w3.org/2000/svg" version="1.1">
                            <defs>
                                <filter id="gooey">
                                <feGaussianBlur in="SourceGraphic" stdDeviation="10" result="blur"></feGaussianBlur>
                                <feColorMatrix in="blur" mode="matrix" values="1 0 0 0 0  0 1 0 0 0  0 0 1 0 0  0 0 0 18 -7" result="goo"></feColorMatrix>
                                <feBlend in="SourceGraphic" in2="goo"></feBlend>
                                </filter>
                            </defs>
                            </svg>
                            <div class="blob blob-0"></div>
                            <div class="blob blob-1"></div>
                            <div class="blob blob-2"></div>
                            <div class="blob blob-3"></div>
                            <div class="blob blob-4"></div>
                            <div class="blob blob-5"></div>
                            <center>Bezig met inloggen...</center>
                        </div>
                        <?php

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
                        $_SESSION['prob'] = "";
                    }
                    ?>
	             </form>
            </div>
            <div class="col-sm-2"></div>
        </div>
    </body>
</html>
