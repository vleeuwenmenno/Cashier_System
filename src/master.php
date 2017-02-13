<?php
    include_once("includes.php");

    Permissions::checkSession(basename($_SERVER['REQUEST_URI']));
?>
<html>
    <head>
        <script src="js/sidebar.js"></script>

        <!-- Bootstrap and all it's dependencies -->
        <?php
        if ($_CFG['THEME'] == "")
            $_CFG['THEME'] = 'Default';
        ?>
        <link rel="stylesheet" href="themes/<?php echo $_SESSION['login_ok']['userTheme']; ?>/bootstrap.css" />
        <link rel="stylesheet" href="themes/<?php echo $_SESSION['login_ok']['userTheme']; ?>/stylesheet.css">
        <link rel="stylesheet" href="themes/<?php echo $_SESSION['login_ok']['userTheme']; ?>/select2.min.css" />
        <link rel="stylesheet" href="themes/<?php echo $_SESSION['login_ok']['userTheme']; ?>/bootstrap-combobox.css" />

        <script src="js/jquery.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <script src="js/bootstrap-notify.min.js"></script>
        <script src="js/select2.full.min.js"></script>
        <script src="js/jqeury.jeditable.js"></script>
        <script src="js/bootstrap-combobox.js"></script>
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
                            <?php echo $_CFG['COMPANY_NAME'] . ' - ' . $_SESSION['login_ok']['nickName']; ?>
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
                                        <a data-toggle="collapse" href="#" id="cashregOverview">
                                            <span class="glyphicon glyphicon-eur"></span> Kassa overzicht</span>
                                        </a>
                                        <a data-toggle="collapse" <?php if (Misc::crIsActive()) { ?> href="#dropdown-lvl2"<?php } ?>>
                                            <span class="glyphicon glyphicon-eur"></span> Bonnen</span>
                                        </a>
                                        <script>
                                            $(document).ready(function ()
                                            {
                                                $("#cashregOverview").on("click", function ()
                                                {
                                                    $("#pageLoaderIndicator").fadeIn();
                                                    $("#PageContent").load("cashregOverview.php", function () {
                                                        $("#pageLoaderIndicator").fadeOut();
                                                    });
                                                });

                                                $("#pageLoaderIndicator").fadeIn();
                                                $("#PageContent").load("cashregOverview.php", function () {
                                                    $("#pageLoaderIndicator").fadeOut();
                                                });
                                            });
                                        </script>

                                        <div id="dropdown-lvl2" class="panel-collapse collapse">
                                            <div class="panel-body">
                                                <ul class="nav navbar-nav">
                                                    <?php
                                                    if ($_SESSION['receipt']['status'] == 'open')
                                                    {
                                                        echo '<li><a href="#" id="newReceipt"><span class="glyphicon glyphicon-file"></span> Bon #' . str_pad($_SESSION['receipt']['id'], 4, '0', STR_PAD_LEFT) .'</a></li>';
                                                    }
                                                    else
                                                    {
                                                        echo '<li><a href="#" id="newReceipt"><span class="glyphicon glyphicon-file"></span> Nieuwe Bon</a></li>';
                                                    }
                                                    ?>
                                                    <li><a href="#" id="searchReceipt"><span class="glyphicon glyphicon-search"></span> Zoeken</a></li>
                                                    <script>
														$(document).ready(function ()
														{
														    $("#newReceipt").on("click", function ()
															{
															    $("#pageLoaderIndicator").fadeIn();
															    $("#PageContent").load("receipt.php?new", function () {
															        $("#pageLoaderIndicator").fadeOut();
															    });
														    });

														    $("#searchReceipt").on("click", function () {
														        $("#pageLoaderIndicator").fadeIn();
														        $("#PageContent").load("receipt.php", function () {
														            $("#pageLoaderIndicator").fadeOut();
														        });
														    });
														});
                                                    </script>
                                                </ul>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="panel panel-default" id="dropdown">
                                        <a data-toggle="collapse" <?php if (Misc::crIsActive()) { ?> href="#dropdown-lvl3"<?php } ?>>
                                            <span class="glyphicon glyphicon-barcode"></span> Artikelen</span>
                                        </a>
                                        <div id="dropdown-lvl3" class="panel-collapse collapse">
                                            <div class="panel-body">
                                                <ul class="nav navbar-nav">
                                                    <li><a href="#" id="searchItem"><span class="glyphicon glyphicon-search"></span> Zoeken</a></li>
                                                    <li><a href="#" id="createNewItem"><span class="glyphicon glyphicon-file"></span> Nieuw Artikel</a></li>
                                                    <li><a href="#" id="itemEntryUpdate"><span class="glyphicon glyphicon-barcode"></span> Artikel Inboeken</a></li>
                                                    <script>
														$(document).ready(function ()
														{
														    $("#searchItem").on("click", function ()
															{
															    $("#pageLoaderIndicator").fadeIn();
															    $("#PageContent").load("item.php", function() {
															        $("#pageLoaderIndicator").fadeOut();
															    });
														    });

														    $("#manageItems").on("click", function () {
														        $("#pageLoaderIndicator").fadeIn();
														        $("#PageContent").load("item/itemImport.php", function () {
														            $("#pageLoaderIndicator").fadeOut();
														        });
														    });

														    $("#createNewItem").on("click", function ()
															{
															    $("#pageLoaderIndicator").fadeIn();
															    $("#PageContent").load("item.php?new", function () {
															        $("#pageLoaderIndicator").fadeOut();
															    });
														    });

														    $("#itemEntryUpdate").on("click", function () {
														        $("#pageLoaderIndicator").fadeIn();
														        $("#PageContent").load("item/itemManage.php?update", function () {
														            $("#pageLoaderIndicator").fadeOut();
														        });
														    });
														});
                                                    </script>
                                                </ul>
                                            </div>
                                        </div>
                                    </li>

                                    <li class="panel panel-default" id="dropdown">
                                        <a data-toggle="collapse" <?php if (Misc::crIsActive()) { ?> href="#dropdown-lvl4"<?php } ?>>
                                            <span class="glyphicon glyphicon-expand"></span> Klanten</span>
                                        </a>
                                        <div id="dropdown-lvl4" class="panel-collapse collapse">
                                            <div class="panel-body">
                                                <ul class="nav navbar-nav">
                                                    <li><a href="#" id="load_custm"><span class="glyphicon glyphicon-search"></span> Zoeken</a></li>
                                                    <li><a href="#" id="load_ncustm"><span class="glyphicon glyphicon-file"></span> Nieuwe Klant</a></li>
													<script>
														$(document).ready(function ()
														{
														    var isShown = false;
														    function disableF5(e)
														    {
														        if ((e.which || e.keyCode) == 116)
														        {
														            e.preventDefault();

														            if (!isShown)
														            {
														                $.notify({
														                    icon: 'glyphicon glyphicon-remove',
														                    title: 'Actie geannuleert<br />',
														                    message: 'Voor veiligheid is uw actie geannuleert, pagina verversen is uitgeschakeld met F5.<br /> (Klik <a href="master.php">hier</a> om de pagina als nog te verversen)'
														                }, {
														                    // settings
														                    type: 'warning',
														                    delay: 4000,
														                    timer: 10,
														                    placement: {
														                        from: "bottom",
														                        align: "right"
														                    },
														                    onClosed: function () {
														                        isShown = false;
														                    }
														                });
														                isShown = true;
														            }
														        }
														    };
														    $(document).on("keydown", disableF5);

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
                                    <?php if (Permissions::isManager($_SESSION['login_ok']['userId'])) { ?><li><a href="#" id="manageItems"><span class="glyphicon glyphicon-cog"></span> Systeem Beheer</a></li><?php } ?>
                                </ul>
                            </div>
                        </div>
                    </li>

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
