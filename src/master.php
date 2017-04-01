<?php
    include_once("includes.php");

    Permissions::checkSession(basename($_SERVER['REQUEST_URI']));
?>
<html>
    <head>
        <!-- Bootstrap and all it's dependencies -->
        <?php
        if ($_CFG['THEME'] == "")
            $_CFG['THEME'] = 'Default';
        ?>
        <link rel="stylesheet" href="themes/<?php echo $_SESSION['login_ok']['userTheme']; ?>/bootstrap.css" />
        <link rel="stylesheet" href="themes/<?php echo $_SESSION['login_ok']['userTheme']; ?>/stylesheet.css">
        <link rel="stylesheet" href="themes/<?php echo $_SESSION['login_ok']['userTheme']; ?>/select2.min.css" />
        <link rel="stylesheet" href="themes/<?php echo $_SESSION['login_ok']['userTheme']; ?>/bootstrap-combobox.css" />
        <link rel="stylesheet" href="themes/<?php echo $_SESSION['login_ok']['userTheme']; ?>/font-awesome.css" />
        <link rel="stylesheet" href="themes/<?php echo $_SESSION['login_ok']['userTheme']; ?>/bootstrap-switch.min.css" />
        <link rel="stylesheet" href="themes/<?php echo $_SESSION['login_ok']['userTheme']; ?>/multiple-emails.css" />


        <script src="js/jquery.js"></script>
        <script src="js/multiple-emails.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <script src="js/bootstrap-notify.min.js"></script>
        <script src="js/select2.full.min.js"></script>
        <script src="js/jquery.jeditable.js"></script>
        <script src="js/bootstrap-combobox.js"></script>
        <script src="js/jquery.printElement.js"></script>
        <script src="js/bootstrap-switch.min.js"></script>
    </head>
    <body>
        <div class="row">
            <!-- Menu -->
            <div class="side-menu" id="sideBarMenu">
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
                </div>

            </div>

            <!-- Main Menu -->
            <div class="side-menu-container">
                <ul class="nav navbar-nav">
                    <!-- Dropdown-->
                    <li class="panel panel-default" id="dropdown">
                        <a data-toggle="collapse" href="#dropdown-lvl1">
                            <i class="fa fa-money fa-2x" aria-hidden="true"></i>&nbsp;&nbsp; Kassa</span>
                        </a>

                        <!-- Dropdown level 1 -->
                        <div id="dropdown-lvl1" class="panel-collapse">
                            <div class="panel-body">
                                <ul class="nav navbar-nav">
                                    <!-- Dropdown level 2 -->
                                    <li class="panel panel-default" id="dropdown">
                                        <a data-toggle="collapse" href="#" id="cashregOverview">
                                            <i class="fa fa-television fa-2x" aria-hidden="true"></i>&nbsp;&nbsp;Kassa overzicht</span>
                                        </a>
                                        <a data-toggle="collapse" <?php if (Misc::crIsActive()) { ?> href="#dropdown-lvl2"<?php } ?>>
                                            <i class="fa fa-paperclip fa-2x" aria-hidden="true"></i>&nbsp;&nbsp;Bonnen</span>
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
                                                        echo '<li><a href="#" id="newReceipt"><i class="fa fa-file-text fa-2x" aria-hidden="true"></i>&nbsp;&nbsp; Nieuwe Bon</a></li>';
                                                    }
                                                    ?>
                                                    <li><a href="#" id="searchReceipt"><i class="fa fa-search fa-2x" aria-hidden="true"></i>&nbsp;&nbsp; Zoeken</a></li>
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
                                            <i class="fa fa-barcode fa-2x" aria-hidden="true"></i>&nbsp;&nbsp; Artikelen</span>
                                        </a>
                                        <div id="dropdown-lvl3" class="panel-collapse collapse">
                                            <div class="panel-body">
                                                <ul class="nav navbar-nav">
                                                    <li><a href="#" id="searchItem"><i class="fa fa-search fa-2x" aria-hidden="true"></i>&nbsp;&nbsp; Zoeken</a></li>
                                                    <li><a href="#" id="createNewItem"><i class="fa fa-file-text-o fa-2x" aria-hidden="true"></i>&nbsp;&nbsp; Nieuw Artikel</a></li>
                                                    <li><a href="#" id="itemEntryUpdate"><i class="fa fa-barcode fa-2x" aria-hidden="true"></i>&nbsp;&nbsp; Artikel Inboeken</a></li>
                                                    <script>
														$(document).ready(function ()
														{
														    $("#searchItem").on("click", function ()
															{
                                                                <?php if (!isset($_SESSION['receipt']['status']) || $_SESSION['receipt']['status'] != "open")
                                                                {?>
                                                                    $("#pageLoaderIndicator").fadeIn();
                                                                    $("#PageContent").load("receipt.php?new", function () {
        															    $("#PageContent").load("item.php", function() {
        															        $("#pageLoaderIndicator").fadeOut();
        															    });
                                                                    });
                                                                <?php }
                                                                else
                                                                {?>
                                                                    $("#pageLoaderIndicator").fadeIn();
    															    $("#PageContent").load("item.php", function() {
    															        $("#pageLoaderIndicator").fadeOut();
    															    });
                                                                <?php }?>
														    });

														    $("#managementBtn").on("click", function () {
														        $("#pageLoaderIndicator").fadeIn();
														        $("#PageContent").load("management.php", function () {
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
                                            <i class="fa fa-address-book-o fa-2x" aria-hidden="true"></i>&nbsp;&nbsp; Klanten</span>
                                        </a>
                                        <div id="dropdown-lvl4" class="panel-collapse collapse">
                                            <div class="panel-body">
                                                <ul class="nav navbar-nav">
                                                    <li><a href="#" id="load_custm"><i class="fa fa-search fa-2x" aria-hidden="true"></i>&nbsp;&nbsp; Zoeken</a></li>
                                                    <li><a href="#" id="load_ncustm"><i class="fa fa-address-card-o fa-2x" aria-hidden="true"></i> Nieuwe Klant</a></li>
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
                                    <?php if (Permissions::isManager($_SESSION['login_ok']['userId'])) { ?><li><a href="#" id="managementBtn"><i class="fa fa-sliders fa-2x" aria-hidden="true"></i>&nbsp;&nbsp; Systeem Beheer</a></li><?php } ?>
                                </ul>
                            </div>
                        </div>
                    </li>

                    <li><a href="logout.php"><i class="fa fa-sign-out fa-2x" aria-hidden="true"></i>&nbsp;&nbsp; Uitloggen</a></li>
                    <div class="loader mainLoader" id="pageLoaderIndicator" style="display: none;"></div>
                </ul>
            </div>
            </nav>
            <span style="color: #f1f1f1;
                position: absolute;
                margin-left: auto;
                margin-right: auto;
                left: 50%;
                transform: translate(-50%, -50%);
                bottom: -2px;
                font-size: 10px;">
                Ontwikkelt door <span id="mennoName">M.C. van Leeuwen</span>
            </span>
            <script>
                var clickCount = 0;
                $("#mennoName").on("click", function () {
                    if (clickCount < 7)
                        clickCount++;
                    else
                    {
                        alert("Well, I'd suppose you like my name so much that you started mashing on it?\n\nCooldown dude ;)\n\nDeze software is gemaakt door...\n\nMenno van Leeuwen\nmenno.vanleeuwen@stardebris.net");
                        clickCount = 0;
                    }
                });
            </script>
        </div>
        <div class="row">
            <div class="col-sm-3">

            </div>
            <div class="col-sm-8">
                <div id="PageContent" style="position: relative; left: -62px;">

                </div>
			</div>
        </div>
    </body>
</html>
