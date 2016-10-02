<?php 
    include_once("includes.php"); 

	if (Permissions::checkSession("", false))
	{
		header("Location: master.php");
	}
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