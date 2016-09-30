<?php 
    include_once("includes.php"); 
?>
<html>
    <head>
        <link rel="stylesheet" type="text/css" href="css/stylesheet.css">
        <script src="js/sidebar.js"></script>

        <!-- Bootstrap and all it's dependencies -->
        <link rel="stylesheet" href="css/bootstrap.min.css">
        <script src="js/jquery.js"></script>
        <script src="js/bootstrap.min.js"></script>
    </head>
    <body>
        <div class="row">
            <!-- Menu -->
            <div class="side-menu">
            <nav class="navbar navbar-default" role="navigation">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <div class="brand-wrapper">

                    <!-- Brand -->
                    <div class="brand-name-wrapper">
                        <a class="navbar-brand" href="#">
                            <?php echo $_CFG['COMPANY_NAME']; ?>
                        </a>
                    </div>

                    <!-- Search -->
                    <a data-toggle="collapse" href="#search" class="btn btn-default" id="search-trigger">
                        <span class="glyphicon glyphicon-search"></span>
                    </a>

                    <!-- Search body -->
                    <div id="search" class="panel-collapse collapse">
                        <div class="panel-body">
                            <form class="navbar-form" role="search">
                                <div class="form-group">
                                    <input type="text" class="form-control" placeholder="Snel-zoeken...">
                                </div>
                                <button type="submit" class="btn btn-default "><span class="glyphicon glyphicon-ok"></span></button>
                            </form>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Main Menu -->
            <div class="side-menu-container">
                <ul class="nav navbar-nav">
                    <!-- Dropdown-->
                    <li class="panel panel-default disabled" id="dropdown">
                        <a data-toggle="collapse" href="#dropdown-lvl0">
                            <span class="glyphicon glyphicon-eur"></span> Kassa</span>
                        </a>

                        <!-- Dropdown level 1 -->
                        <div id="dropdown-lvl1" class="panel-collapse collapse">
                            <div class="panel-body">
                                <ul class="nav navbar-nav">
                                    <!-- Dropdown level 2 -->
                                    <li class="panel panel-default" id="dropdown">
                                        <a data-toggle="collapse" href="#dropdown-lvl2">
                                            <span class="glyphicon glyphicon-eur"></span> Bonnen</span>
                                        </a>
                                        <div id="dropdown-lvl2" class="panel-collapse collapse">
                                            <div class="panel-body">
                                                <ul class="nav navbar-nav">
                                                    <li><a href="#"><span class="glyphicon glyphicon-search"></span> Zoeken</a></li>
                                                    <li><a href="#"><span class="glyphicon glyphicon-file"></span> Nieuwe Bon</a></li>
                                                    <li><a href="#"><span class="glyphicon glyphicon-cog"></span> Beheren</a></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </li>
                                    <li><a href="#"><span class="glyphicon glyphicon-barcode"></span> Artikelen</a></li>
                                    <li><a href="#"><span class="glyphicon glyphicon-expand"></span> Klanten</a></li>
                                    <li><a href="#"><span class="glyphicon glyphicon-cog"></span> Systemen</a></li>
                                </ul>
                            </div>
                        </div>
                    </li>

                    <li><a href="#"><span class="glyphicon glyphicon-user"></span> Beheer Login</a></li>
                    <li><a href="#"><span class="glyphicon glyphicon-user"></span> Login</a></li>
                </ul>
            </div>
            </nav>
        </div>
        <div class="row">
            <div class="col-sm-3">
            
            </div>
            <div class="col-sm-6">
                <h2>Kassa</h2>
                <div class="form-group">
                    <label for="employee">Medewerker: </label>
                    <input type="text" class="form-control" id="employee">
                </div>
                <div class="form-group">
                    <label for="passwrd">Wachtwoord: </label>
                    <input type="password" class="form-control" id="passwrd">
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
                                window.location.replace("login/login.php<?php if (isset($_GET['r'])) { echo '?r=' . $_GET['r']; } else { echo '?r=index.php'; } ?>");
                            })();
                        </script>
                        <?php
                    }

                    if (isset($_GET['notice']))
                    {
                        echo $_GET['notice'];
                    }
            ?>
            </div>
            <div class="col-sm-2"></div>
        </div>
    </body>
</html>