<?php 
include_once("includes.php"); 

Permissions::checkSession(basename($_SERVER['REQUEST_URI']));
?>
<html>
    <head>
        <link rel="stylesheet" type="text/css" href="css/stylesheet.css">
        <script src="js/sidebar.js"></script>

        <!-- Bootstrap and all it's dependencies -->
        <link rel="stylesheet" href="css/bootstrap.min.css" />
        <link rel="stylesheet" href="css/select2.min.css" />

        <script src="js/jquery.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <script src="js/select2.full.min.js"></script>
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
                    <li class="panel panel-default" id="dropdown">
                        <a data-toggle="collapse" href="#dropdown-lvl1">
                            <span class="glyphicon glyphicon-eur"></span> Kassa</span>
                        </a>

                        <!-- Dropdown level 1 -->
                        <div id="dropdown-lvl1" class="panel-collapse">
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
                                    <li class="panel panel-default" id="dropdown">
                                        <a data-toggle="collapse" href="#dropdown-lvl3">
                                            <span class="glyphicon glyphicon-expand"></span> Klanten</span>
                                        </a>
                                        <div id="dropdown-lvl3" class="panel-collapse collapse">
                                            <div class="panel-body">
                                                <ul class="nav navbar-nav">
                                                    <li><a href="#" id="load_custm"><span class="glyphicon glyphicon-search"></span> Zoeken</a></li>
                                                    <li><a href="#" id="load_ncustm"><span class="glyphicon glyphicon-file"></span> Nieuwe Klant</a></li>
													<script>
														$(document).ready(function ()
														{
															$("#load_custm").on("click", function ()
															{
															    $("#pageLoaderIndicator").fadeIn();
															    $("#PageContent").load("customer.php", function() {
															        $("#pageLoaderIndicator").fadeOut();
															    });
															});

															$("#load_ncustm").on("click", function ()
															{
															    $("#pageLoaderIndicator").fadeIn();
															    $("#PageContent").load("customer.php?new", function () {
															        $("#pageLoaderIndicator").fadeOut();
															    });
															});
														});
													</script>
                                                </ul>
                                            </div>
                                        </div>
                                    </li>
                                    <li><a href="#"><span class="glyphicon glyphicon-cog"></span> Systemen</a></li>
                                </ul>
                            </div>
                        </div>
                    </li>

                    <li><a href="#"><span class="glyphicon glyphicon-user"></span> Beheer Login</a></li>
                    <li><a href="logout.php"><span class="glyphicon glyphicon-user"></span> Uitloggen</a></li>
                    <div class="loader mainLoader" id="pageLoaderIndicator" style="display: none;"></div>
                </ul>
            </div>
            </nav>
        </div>
        <div class="row">
            <div class="col-sm-3">
            
            </div>
            <div class="col-sm-8">
                <div id="PageContent">

                </div>
			</div>
        </div>
    </body>
</html>