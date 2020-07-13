<?php
    include_once("includes.php");

    Permissions::checkSession(basename($_SERVER['REQUEST_URI']));
?>
<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.2">

        <!-- Bootstrap and all it's dependencies -->
        <?php
        if ($_CFG['THEME'] == "")
            $_CFG['THEME'] = 'Default';
        ?>
        <link rel="stylesheet" href="http://<?php echo $_CFG['HOST_NAME']; ?>/CashRegister/src/themes/Yeti/bootstrap-switch.min.css" />
        <link rel="stylesheet" href="http://<?php echo $_CFG['HOST_NAME']; ?>/CashRegister/src/themes/Yeti/multiple-emails.css" />
        <link rel="stylesheet" href="http://<?php echo $_CFG['HOST_NAME']; ?>/CashRegister/src/themes/Yeti/bootstrap.css" />
        <link rel="stylesheet" href="http://<?php echo $_CFG['HOST_NAME']; ?>/CashRegister/src/themes/Yeti/stylesheet.css">
        <link rel="stylesheet" href="http://<?php echo $_CFG['HOST_NAME']; ?>/CashRegister/src/themes/Yeti/select2.min.css" />
        <link rel="stylesheet" href="http://<?php echo $_CFG['HOST_NAME']; ?>/CashRegister/src/themes/Yeti/bootstrap-combobox.css" />
        <link rel="stylesheet" href="http://<?php echo $_CFG['HOST_NAME']; ?>/CashRegister/src/themes/Yeti/font-awesome.css" />
        <link rel="stylesheet" href="http://<?php echo $_CFG['HOST_NAME']; ?>/CashRegister/src/themes/Yeti/multiple-emails.css" />


        <script src="http://<?php echo $_CFG['HOST_NAME']; ?>/CashRegister/src/js/jquery.js"></script>
        <script src="http://<?php echo $_CFG['HOST_NAME']; ?>/CashRegister/src/js/multiple-emails.js"></script>
        <script src="http://<?php echo $_CFG['HOST_NAME']; ?>/CashRegister/src/js/bootstrap.min.js"></script>
        <script src="http://<?php echo $_CFG['HOST_NAME']; ?>/CashRegister/src/js/bootstrap-notify.min.js"></script>
        <script src="http://<?php echo $_CFG['HOST_NAME']; ?>/CashRegister/src/js/select2.full.min.js"></script>
        <script src="http://<?php echo $_CFG['HOST_NAME']; ?>/CashRegister/src/js/jquery.jeditable.js"></script>
        <script src="http://<?php echo $_CFG['HOST_NAME']; ?>/CashRegister/src/js/bootstrap-combobox.js"></script>
        <script src="http://<?php echo $_CFG['HOST_NAME']; ?>/CashRegister/src/js/jquery.print.js"></script>
    </head>
    <body>
        <nav class="navbar navbar-default">
            <div class="container-fluid">
                <!-- Brand and toggle get grouped for better mobile display -->
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="#"><?php echo $_CFG['COMPANY_NAME'] . ' - ' . $_SESSION['login_ok']['nickName']; ?></a>
                </div>

                <!-- Collect the nav links, forms, and other content for toggling -->
                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                    <ul class="nav navbar-nav">
                        <li><a href="#" id="cashregOverview">Kassa overzicht</a></li>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Bonnen <span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <li><a href="#" id="searchReceipt"><i class="fa fa-search" aria-hidden="true"></i>&nbsp;Zoeken</a></li>
                                <?php
                                if ($_SESSION['receipt']['status'] == 'open')
                                {
                                    echo '<li><a href="#" id="newReceipt"><span class="glyphicon glyphicon-file"></span> Bon #' . str_pad($_SESSION['receipt']['id'], 4, '0', STR_PAD_LEFT) .'</a></li>';
                                }
                                else
                                {
                                    echo '<li><a href="#" id="newReceipt"><i class="fa fa-file-text" aria-hidden="true"></i>&nbsp;&nbsp; Nieuwe Bon</a></li>';
                                }
                                ?>
                            </ul>
                        </li>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Artikelen <span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <li><a href="#" id="searchItem"><i class="fa fa-search" aria-hidden="true"></i>&nbsp;Zoeken</a></li>
                                <li><a href="#" id="createNewItem"><i class="fa fa-file-text-o" aria-hidden="true"></i>&nbsp;Nieuw Artikel</a></li>
                                <li><a href="#" id="itemEntryUpdate"><i class="fa fa-barcode" aria-hidden="true"></i>&nbsp;Artikel Inboeken</a></li>
                            </ul>
                        </li>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Klanten <span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <li><a href="#" id="load_custm"><i class="fa fa-search" aria-hidden="true"></i>&nbsp;Zoeken</a></li>
                                <li><a href="#" id="load_ncustm"><i class="fa fa-address-card-o" aria-hidden="true"></i> Nieuwe Klant</a></li>
                            </ul>
                        </li>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Contracten <span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <li><a href="#" id="load_custm"><i class="fa fa-search" aria-hidden="true"></i>&nbsp;Zoeken</a></li>
                                <li><a href="#" id="load_ncustm"><i class="fa fa-file-text" aria-hidden="true"></i>&nbsp;Contracten overzicht</a></li>
                                <li><a href="#" id="load_ncustm"><i class="fa fa-file-text-o" aria-hidden="true"></i>&nbsp;Nieuw contract</a></li>
                            </ul>
                        </li>
                    </ul>
                    
                    <ul class="nav navbar-nav navbar-right">
                        <?php if (Permissions::isManager($_SESSION['login_ok']['userId'])) { ?><li><a href="#" id="managementBtn"><i class="fa fa-search" aria-hidden="true"></i>&nbsp;Systeem Beheer</a></li><?php } ?>
                        <li><a href="logout.php"><i class="fa fa-sign-out" aria-hidden="true"></i>&nbsp;Uitloggen</a></li>
                    </ul>
                </div><!-- /.navbar-collapse -->
            </div><!-- /.container-fluid -->
        </nav>
        <script>
            $(document).ready(function ()
            {
                var isShown = false;
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
                
                $("#searchItem").on("click", function ()
                {
                    $("#pageLoaderIndicator").fadeIn();
                    $("#PageContent").load("item.php", function() {
                        $("#pageLoaderIndicator").fadeOut();
                    });
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
                
                // function disableF5(e)
                // {
                //     if ((e.which || e.keyCode) == 116)
                //     {
                //         e.preventDefault();

                //         if (!isShown)
                //         {
                //             $.notify({
                //                 icon: 'glyphicon glyphicon-remove',
                //                 title: 'Actie geannuleert<br />',
                //                 message: 'Voor veiligheid is uw actie geannuleert, pagina verversen is uitgeschakeld met F5.<br /> (Klik <a href="master.php">hier</a> om de pagina als nog te verversen)'
                //             }, {
                //                 // settings
                //                 type: 'warning',
                //                 delay: 4000,
                //                 timer: 10,
                //                 placement: {
                //                     from: "bottom",
                //                     align: "right"
                //                 },
                //                 onClosed: function () {
                //                     isShown = false;
                //                 }
                //             });
                //             isShown = true;
                //         }
                //     }
                // };
                // $(document).on("keydown", disableF5);
            });
        </script>
        <div class="row">
            
            <span style="color: #a1a1a1;
                position: absolute;
                margin-left: auto;
                margin-right: auto;
                left: 50%;
                transform: translate(-50%, -50%);
                bottom: -2px;
                font-size: 10px;">
                <div class="loader mainLoader" id="pageLoaderIndicator" style="display: none;"></div>
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
            <div class="col-sm-1"></div>
            <div class="col-sm-10">
                <div id="PageContent">

                </div>
			</div>
            <div class="col-sm-1"></div>
        </div>
    </body>
</html>
