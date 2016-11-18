<?php
include_once("includes.php");

if (isset($_GET['new']))
{
    if (!isset($_SESSION['receipt']['status']) || $_SESSION['receipt']['status'] != 'open')
    {
        $db = new mysqli($config['SQL_HOST'], $config['SQL_USER'], $config['SQL_PASS'], $config['SQL_DB']);

        if($db->connect_errno > 0)
        {
            die('Unable to connect to database [' . $db->connect_error . ']');
        }

        $sql = "INSERT INTO receipt (creator, items, paymentMethod) VALUES ('1', '', 'PIN')";

        if(!$result = $db->query($sql))
        {
            die('There was an error running the query [' . $db->error . ']');
        }

        $_SESSION['receipt']['status'] = 'open';
        $_SESSION['receipt']['id'] = mysqli_insert_id($db);
    }
?>
<div id="cartForm">
        <span id="receiptNo"><h2>Bon #<?php echo str_pad($_SESSION['receipt']['id'], 4, '0', STR_PAD_LEFT); ?></h2></span>
        <div class="panel panel filterable">
            <div class="panel-heading">
                <?php
                if (isset($_SESSION['receipt']['customer']))
                {
                    echo Misc::sqlGet("initials", "customers", "customerId", $_SESSION['receipt']['customer'])['initials'] . ' ' . Misc::sqlGet("familyName", "customers", "customerId", $_SESSION['receipt']['customer'])['familyName'] . '<br />';
                    echo Misc::sqlGet("companyName", "customers", "customerId", $_SESSION['receipt']['customer'])['companyName'] . '<br />';
                    echo Misc::sqlGet("streetName", "customers", "customerId", $_SESSION['receipt']['customer'])['streetName'] . '<br />';
                    echo Misc::sqlGet("postalCode", "customers", "customerId", $_SESSION['receipt']['customer'])['postalCode'] . ' ';
                    echo Misc::sqlGet("city", "customers", "customerId", $_SESSION['receipt']['customer'])['city'] . '<br />';

                }
                ?>
            </div>
            <table class="table">
                <thead>
                    <tr class="filters">
                        <th width="54px">
                            <a href="#" class="mustFocus">
                                <input type="text" class="form-control" placeholder="" disabled />
                            </a>
                        </th>
                        <th width="64px">
                            <a href="#" class="mustFocus">
                                <input type="text" class="form-control" placeholder="Aantal" disabled />
                            </a>
                        </th>
                        <th>
                            <a href="#" class="mustFocus">
                                <input type="text" class="form-control" placeholder="Item" disabled />
                            </a>
                        </th>
                        <th width="128px">
                            <a href="#" class="mustFocus">
                                <input type="text" class="form-control" placeholder="Verkoop prijs" disabled />
                            </a>
                        </th>
                    </tr>
                </thead>

                <tbody id="listContents">
                    <?php
                        foreach ($_SESSION['receipt']['items'] as $key => $val)
                        {
                            echo '<tr>';
                            echo '<th><button id="trash' .  $key . '" type="button" class="btn btn-danger"><span class="glyphicon glyphicon-trash" style="font-size: 12px;"></span></button></th>';
                            echo '<th>' . $val . '</th>';
                            echo '<th>' . urldecode(Items::getField("itemName", $key)) . '</th>';
                            echo '<th><span class="priceClickable" id="' . $key . '" data-toggle="popover" title="Prijs berekening" data-content="'. Items::getField("priceExclVat", $key) . '&nbsp;excl. ' . Items::getField("priceModifier", $key) . ' = ' . str_replace(".", ",", round(Misc::calculate(Items::getField("priceExclVat", $key) . ' ' . str_replace(",", ".", Items::getField("priceModifier", $key))), 2)) . '&nbsp;&euro;">' . str_replace(".", ",", round(Misc::calculate(Items::getField("priceExclVat", $key) . ' ' . str_replace(",", ".", Items::getField("priceModifier", $key))), 2)) . ' &euro; </span></th>';
                            echo '</tr>';

                            echo '<script>
                            $(document).ready(function() {
                                $( "#' . $key . '" ).hover(function() {
                                    $(\'#' . $key . '\').popover(\'show\');
                                });

                                $( "#' . $key . '" ).mouseout(function() {
                                    $(\'#' . $key . '\').popover(\'hide\');
                                });

                                $("#trash' . $key . '").on("click", function() {
                                  $.get(
                                      "receipt/removeItem.php",
                                      {
                                          itemId: \'' . $key . '\',
                                          itemCount: \'1\'
                                      },
                                      function (data)
                                      {
                                        $.notify({
                                            icon: \'glyphicon glyphicon-trash\',
                                            title: \'' . urldecode(Items::getField("itemName", $key)) . '\',
                                            message: \'<br />Verwijderd van bon (<a href="#" id="undo' . $key . '">Ongedaan maken</a>)\'
                                        }, {
                                            // settings
                                            type: \'danger\',
                                            delay: 5000,
                                            timer: 10,
                                            placement: {
                                                from: "bottom",
                                                align: "right"
                                            },
                                            onClosed: function () {
                                                //TODO: Send delete to SQL
                                            }
                                        });

                                        $("#pageLoaderIndicator").fadeIn();
      															    $("#PageContent").load("receipt.php?new", function () {
      															        $("#pageLoaderIndicator").fadeOut();
      															    });
                                      }
                                  );
                                });

                                $("#undo' . $key . '").on("click", function() {
                                  $.get(
                                      "receipt/addItem.php",
                                      {
                                          itemId: \'' . $key . '\',
                                          itemCount: \'1\'
                                      },
                                      function (data)
                                      {
                                        $.notify({
                                            icon: \'glyphicon glyphicon-trash\',
                                            title: \'' . urldecode(Items::getField("itemName", $key)) . '\',
                                            message: \'<br />Toegevoegt aan bon.\'
                                        }, {
                                            // settings
                                            type: \'success\',
                                            delay: 5000,
                                            timer: 10,
                                            placement: {
                                                from: "bottom",
                                                align: "right"
                                            },
                                            onClosed: function () {
                                                //TODO: Send delete to SQL
                                            }
                                        });
                                      }
                                  );

                                  $("#pageLoaderIndicator").fadeIn();
                                  $("#PageContent").load("receipt.php?new", function () {
                                      $("#pageLoaderIndicator").fadeOut();
                                  });
                                });

                            });
                            </script>';
                        }
                    ?>
                </tbody>
            </table>
        </div>
    <button type="button" id="closeReceipt" class="btn btn-default">Bon Sluiten</button>
    <?php if (!isset($_SESSION['receipt']['customer'])) { ?><button type="button" id="selectCustomer" class="btn btn-info">Selecteer klant</button> <?php } ?>
    <?php if (isset($_SESSION['receipt']['customer'])) { ?><button type="button" id="deselectCustomer" class="btn btn-danger">Verwijder klant van bon</button> <?php } ?>
    <button type="button" id="payBtn" class="btn btn-primary pull-right" data-toggle="modal" data-target="#printAmount">Betalen</button>

    <div class="form-group pull-right" style="width: 256px; padding-right: 32px;">
        <select class="combobox form-control" id="paymentMethod">
            <option value="" selected="selected">Selecteer betaal methode</option>
            <option value="CASH">Kontant</option>
            <option value="PIN">Pin</option>
            <option value="PC">Pin & Kontant</option>
            <option value="BANK">Op rekening</option>
        </select>
    </div>

    <div class="pull-right" id="statusText"></div>

    <br /><br />

    <div class="form-group pull-right" id="pinValDiv" style="display: none; padding-left: 32px;">
        <label for="pinVal">Pin bedrag:</label>
        <input class="form-control" id="pinVal">
    </div>

    <div class="form-group pull-right" id="cashValDiv" style="display: none;">
        <label for="cashVal">Kontant bedrag:</label>
        <input class="form-control" id="cashVal">
    </div>

    <br /><br /><br /><br />
    <div class="pull-right">
        <?php
            $total = 0;
            foreach ($_SESSION['receipt']['items'] as $key => $val)
            {
                $price = Misc::calculate(Items::getField("priceExclVat", $key) . ' ' . str_replace(",", ".", Items::getField("priceModifier", $key)));
                $price *= $val;
                $total += $price;
            }
        ?>
        <h3>Totaal: &euro; <?php echo str_replace(".", ",", number_format ($total, 2)); ?></h3>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="printAmount" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Bon printen?</h4>
                </div>
                <div class="modal-body">
                    <p>Wil de klant een bon?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" id="printReceipt" data-dismiss="modal">Bon printen</button>
                    <button type="button" class="btn btn-warning" id="printNoReceipt" data-dismiss="modal">Geen bon printen</button>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        function checkTotalValue()
        {
            var totalPrice = "<?php echo number_format ($total, 2) ?>";

            //TODO: MAKE PINVAL OR CASHVAL THE TOTAL VAL WHEN paymentMethod IS PIN OR CASH
            $('#pinVal').val(totalPrice);


            if (Number( $("#pinVal").val().replace(",", ".")) > totalPrice || Number( $("#cashVal").val().replace(",", ".")) > totalPrice || $("#pinVal").val() == "" || $("#cashVal").val() == "")
            {
                $("#payBtn").prop("disabled", true);

                $("#cashVal").css("border-color", "red");
                $("#cashVal").css("box-shadow", "0 1px 1px rgba(0, 0, 0, 0.075) inset, 0 0 8px rgba(126, 239, 104, 0.6)");
                $("#cashVal").css("outline", "0 none");

                $("#pinVal").css("border-color", "red");
                $("#pinVal").css("box-shadow", "0 1px 1px rgba(0, 0, 0, 0.075) inset, 0 0 8px rgba(126, 239, 104, 0.6)");
                $("#pinVal").css("outline", "0 none");
            }
            else
            {
                $("#payBtn").prop("disabled", false);

                $("#cashVal").css("border-color", "");
                $("#cashVal").css("box-shadow", "");
                $("#cashVal").css("outline", "");

                $("#pinVal").css("border-color", "");
                $("#pinVal").css("box-shadow", "");
                $("#pinVal").css("outline", "");
            }
        }

        $(document).ready(function(){
            var totalPrice = "<?php echo number_format ($total, 2) ?>";
            $('#cashVal').keyup(function() {
                if (this.value != "")
                {
                    var half = totalPrice - Number( $("#cashVal").val().replace(",", "."));
                    $('#pinVal').prop("readonly", true);
                    $('#pinVal').val(half.toFixed(2).replace(".", ","));
                }
                else
                    $('#pinVal').prop("readonly", false);

                checkTotalValue();
            });

            $('#pinVal').keyup(function() {
                if (this.value != "")
                {
                    var half = totalPrice - Number( $("#pinVal").val().replace(",", "."));
                    $('#cashVal').prop("readonly", true);
                    $('#cashVal').val(half.toFixed(2).replace(".", ","));
                }
                else
                    $('#cashVal').prop("readonly", false);

                checkTotalValue();
            });

            var printAmount = 0;
            var isButtonClick = false;

            $('#paymentMethod').on('change', function() {
                if (this.value != "PC")
                {
                    $('#cashValDiv').css("display", "none");
                    $('#pinValDiv').css("display", "none");
                }
                else
                {
                    $('#cashValDiv').css("display", "inline");
                    $('#pinValDiv').css("display", "inline");
                }

                $('#statusText').html('');

                checkTotalValue();
            });

            $('#printAmount').on('hidden.bs.modal', function () {
                if (isButtonClick)
                {
                    isButtonClick = false;

                    if ($( "#paymentMethod" ).val() != "")
                    {
                        if ($('#paymentMethod').val() == "PC")
                        {
                            var pinVal = $('#pinVal').val();
                            var cashVal = $('#cashVal').val();

                            $("#pageLoaderIndicator").fadeIn();
                            $("#PageContent").load("receipt/processReceipt.php?receiptId=<?php echo $_SESSION['receipt']['id']; ?>&print=" + printAmount + "&paymentMethod=" + $( "#paymentMethod" ).val() + "&pin=" + pinVal + "&cash=" + cashVal, function () {
                                $("#pageLoaderIndicator").fadeOut();
                            });
                       }
                       else
                       {
                           $("#pageLoaderIndicator").fadeIn();
                           $("#PageContent").load("receipt/processReceipt.php?receiptId=<?php echo $_SESSION['receipt']['id']; ?>&print=" + printAmount + "&paymentMethod=" + $( "#paymentMethod" ).val(), function () {
                               $("#pageLoaderIndicator").fadeOut();
                           });
                       }
                    }
                    else
                    {
                        $( "#statusText" ).html("<p style=\"color: orange !important;\">Selecteer een betaal optie >> &nbsp;&nbsp;</p>");
                    }
                }
            });

            $('#printReceipt').click(function() {
                printAmount = 1;
                isButtonClick = true;
                $('#printAmount').modal('hide');
            });

            $('#printNoReceipt').click(function() {
                printAmount = 0;
                isButtonClick = true;
                $('#printAmount').modal('hide');
            });

            $('.combobox').combobox();


            $('#deselectCustomer').click(function () {
                 $.get(
                    "receipt/deselectCustomer.php",
                    {
                        receiptId: '<?php echo $_SESSION['receipt']['id']; ?>'
                    },
                    function (data)
                    {
                        $.notify({
                            icon: 'glyphicon glyphicon-trash',
                            title: '',
                            message: 'Klant verwijderd van bon'
                        }, {
                            // settings
                            type: 'info',
                            delay: 2000,
                            timer: 10,
                            placement: {
                                from: "bottom",
                                align: "right"
                            }
                        });
                        $("#pageLoaderIndicator").fadeIn();
                        $("#PageContent").load("receipt.php?new", function () {
                            $("#pageLoaderIndicator").fadeOut();
                        });
                    }
                );
            });

            $('#selectCustomer').click(function () {
                $("#pageLoaderIndicator").fadeIn();
                $("#PageContent").load("customer.php", function () {
                    $("#pageLoaderIndicator").fadeOut();
                });
            });

            $('#closeReceipt').click(function () {
                $("#newReceipt").html("<span class=\"glyphicon glyphicon-file\"></span> Nieuwe Bon");
                $("#pageLoaderIndicator").fadeIn();
                $("#PageContent").load("receipt.php", function () {
                    $("#pageLoaderIndicator").fadeOut();
                });

                $.get(
                    "receipt/empty.php",
                    {
                        receiptId: '<?php echo $_SESSION['receipt']['id']; ?>'
                    },
                    function (data)
                    {
                        $.notify({
                            icon: 'glyphicon glyphicon-trash',
                            title: 'Bon verwijderd',
                            message: 'Bon is verwijderd (<a href="#">Ongedaan maken</a>)'
                        }, {
                            // settings
                            type: 'warning',
                            delay: 2000,
                            timer: 10,
                            placement: {
                                from: "bottom",
                                align: "right"
                            }
                        });
                    }
                );
            });
        });
    </script>
    </div>

<div class="modal fade" id="errorMessage">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">Fout</h4>
            </div>
            <div class="modal-body">
                <p>
                    <div id="errorMessageContent"></div>
                    <br />Blijft het probleem bestaan? Neem dan contact op met de systeems administrator.
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="closeErrorBtn" data-dismiss="modal">Sluiten</button>
                <script>
					$(document).ready(function ()
					{
					    $("#newReceipt").html("<span class=\"glyphicon glyphicon-file\"></span> " + $('#receiptNo').html().replace('<h2>', '').replace('</h2>', ''));
						$("#closeErrorBtn").on("click", function () {
							$("#customerForm").fadeIn();
							$("#loaderAnimation").fadeOut();
						});
					});
                </script>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="okMessage">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">Nieuw Artikel</h4>
            </div>
            <div class="modal-body">
                <p>Het artikel is verwerkt in het systeem.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="openCustBtn" data-dismiss="modal">Weergeef Artikel</button>
                <button type="button" class="btn btn-secondary" id="closeOkBtn" data-dismiss="modal">Sluiten</button>
                <script>
					$(document).ready(function ()
					{
						$("#openCustBtn").on("click", function () {
						    $("#okMessage").modal("hide");
						});

						$('#okMessage').on('hidden.bs.modal', function () {
						    $("#PageContent").load('item/viewItem.php?id=' + $('#itemId').val());
						});

						$("#closeOkBtn").on("click", function () {
							$("#okMessage").modal("hide");
						});
					});
                </script>
            </div>
        </div>
    </div>
</div>

<div id="loaderAnimation" style="display: none;">
    <svg xmlns="http://www.w3.org/2000/svg" version="1.1">
        <defs>
            <filter id="gooey">
                <fegaussianblur in="SourceGraphic" stddeviation="10" result="blur"></fegaussianblur>
                <fecolormatrix in="blur" mode="matrix" values="1 0 0 0 0  0 1 0 0 0  0 0 1 0 0  0 0 0 18 -7" result="goo"></fecolormatrix>
                <feblend in="SourceGraphic" in2="goo"></feblend>
            </filter>
        </defs>
    </svg>
    <div class="blob blob-0"></div>
    <div class="blob blob-1"></div>
    <div class="blob blob-2"></div>
    <div class="blob blob-3"></div>
    <div class="blob blob-4"></div>
    <div class="blob blob-5"></div>
    <center>Artikel wordt verwerkt in het systeem...</center>
</div>
<?php
}
else
{
?>
<br />
<script type="text/javascript">
        $(".js-example-basic-multiple").select2();
</script>

<div class="row">
    <div class="col-lg-offset-3 col-lg-6">
        <div class="input-group">
            <input type="text" class="form-control" name="searchBar" id="searchBar" placeholder="Zoek term... (Bon nummer, klant, bedrijf, etc.)" aria-describedby="basic-addon2" />
            <span class="input-group-btn">
                <button class="btn btn-primary" type="submit" id="searchBtn" style="height: 34px;">
                    <span class="glyphicon glyphicon-search"></span>
                </button>
            </span>
        </div>
    </div>
</div>
<script>
        $(document).ready(function ()
        {
            $('textarea').keyup(function (e) {
                if (e.keyCode == 13) {
                    $(this).trigger("enterKey");
                }
            });

            $("#searchBtn").on("click", function () {
                startLocation = 0;

                $("#loadMore").fadeOut("fast", function () {
                    $("#moreLoaderIndicator").fadeIn();
                });

                $("#listContents").html("");

                $.get(
                    "item/itemLoad.php",
                    {
                        start: 0,
                        count: 25,
                        sTerm: $("#searchBar").val()
                    },
                    function (data)
                    {
                        if (data != "")
                        {
                            $("#loadMore").fadeIn();
                        }
                        else
                        {
                            $("#listContents").html('<tr><td>Uw zoekopdracht - ' + $("#searchBar").val() + ' - heeft geen resultaat opgeleverd.</td></tr>');
                        }

                        $("#moreLoaderIndicator").fadeOut();
                        $("#listContents").append(data);
                        startLocation += 25;
                    }
                );
            });

            $('#searchBar').keypress(function (e)
            {
                var key = e.which;
                if (key == 13)  // the enter key code
                {
                    $('#searchBtn').click();
                    return false;
                }
            });
        });
</script>

<div class="row">
    <div class="panel panel-primary filterable">
        <div class="panel-heading">
            <h3 class="panel-title">Bonnen</h3>
            <div class="pull-right">
                <button class="btn btn-default btn-xs btn-filter">
                    <span class="glyphicon glyphicon-filter"></span>&nbsp;Filteren
                </button>
            </div>
        </div>
        <table class="table">
            <thead>
                <tr class="filters">
                    <th>
                        <a href="#" class="mustFocus">
                            <input type="text" class="form-control" placeholder="Bon Nummer" disabled />
                        </a>
                    </th>
                    <th>
                        <a href="#" class="mustFocus">
                            <input type="text" class="form-control" placeholder="Tijd/Datum" disabled />
                        </a>
                    </th>
                    <th>
                        <a href="#" class="mustFocus">
                            <input type="text" class="form-control" placeholder="Klant/Bedrijf" disabled />
                        </a>
                    </th>
                    <th>
                        <a href="#" class="mustFocus">
                            <input type="text" class="form-control" placeholder="Bon totaal" disabled />
                        </a>
                    </th>
                </tr>
            </thead>
            <script>
                    $(document).ready(function ()
                    {
                        var filterEnabled = false;
                        $(".mustFocus").click(function (obj)
                        {
                            var $panel = $(this).parents('.filterable'),
                            $filters = $panel.find('.filters input'),
                            $tbody = $panel.find('.table tbody');

                            if ($filters.prop('disabled') == true)
                            {
                                $filters.prop('disabled', false);
                                obj.focus();
                            }
                            filterEnabled = true;
                        });

                        $(".mustFocus").focusout(function ()
                        {
                            if (filterEnabled)
                            {
                                var $panel = $(this).parents('.filterable'),
                                $filters = $panel.find('.filters input'),
                                $tbody = $panel.find('.table tbody');

                                if ($filters.prop('disabled') == false)
                                {
                                    $filters.prop('disabled', true);

                                }
                                filterEnabled = false;
                            }
                        });
                    });

            </script>

            <tbody id="listContents">
                <?php
    $db = new mysqli($config['SQL_HOST'], $config['SQL_USER'], $config['SQL_PASS'], $config['SQL_DB']);

    if($db->connect_errno > 0)
    {
        die('Unable to connect to database [' . $db->connect_error . ']');
    }

    $sql = "SELECT * FROM receipt WHERE 1;";

    if(!$result = $db->query($sql))
    {
        die('There was an error running the query [' . $db->error . ']');
    }

    $i = 0;
    while($row = $result->fetch_assoc())
    {
        $i++;
        if ($i < 25)
        {

        }
    }
                ?>
            </tbody>
        </table>
        <?php
    if ($i >= 25)
    {
        ?>
        <button type="button" class="btn btn-info center-block" id="loadMore">Laad Meer</button>
        <br />
        <div class="loader mainLoader" id="moreLoaderIndicator" style="display: none;"></div>
        <script>
                        $(document).ready(function ()
                        {
                            var startLocation = 26;
                            $("#loadMore").on("click", function () {

                                $("#loadMore").fadeOut("fast", function () {
                                    $("#moreLoaderIndicator").fadeIn();
                                    $("html, body").animate({ scrollTop: $(document).height() }, "normal");
                                });

                                $.get(
                                    "item/itemLoad.php",
                                    {
                                        start: startLocation,
                                        count: 25,
                                        sTerm: $("#searchBar").val()
                                    },
                                    function (data)
                                    {
                                        if (data != "")
                                        {
                                            $("#loadMore").fadeIn();
                                        }

                                        $("#moreLoaderIndicator").fadeOut();
                                        $("#listContents").append(data);
                                        startLocation += 25;
                                    }
                                );
                            });
                        });
        </script>
        <?php
    }
        ?>
    </div>
    <br />
    <br />
    <br />
    <br />
    <div id="loaderAnimation" style="display: none;">
        <svg xmlns="http://www.w3.org/2000/svg" version="1.1">
            <defs>
                <filter id="gooey">
                    <fegaussianblur in="SourceGraphic" stddeviation="10" result="blur"></fegaussianblur>
                    <fecolormatrix in="blur" mode="matrix" values="1 0 0 0 0  0 1 0 0 0  0 0 1 0 0  0 0 0 18 -7" result="goo"></fecolormatrix>
                    <feblend in="SourceGraphic" in2="goo"></feblend>
                </filter>
            </defs>
        </svg>
        <div class="blob blob-0"></div>
        <div class="blob blob-1"></div>
        <div class="blob blob-2"></div>
        <div class="blob blob-3"></div>
        <div class="blob blob-4"></div>
        <div class="blob blob-5"></div>
    </div>
</div>
<script>
        $(document).ready(function () {

            $("#searchBar").focus();

            $('.search-panel .dropdown-menu').find('a').click(function (e) {
                e.preventDefault();
                var param = $(this).attr("href").replace("#", "");
                var concept = $(this).text();
                $('.search-panel span#search_concept').text(concept);
                $('.input-group #search_param').val(param);
            });

            $('.filterable .btn-filter').click(function () {
                var $panel = $(this).parents('.filterable'),
                $filters = $panel.find('.filters input'),
                $tbody = $panel.find('.table tbody');
                if ($filters.prop('disabled') == true) {
                    $filters.prop('disabled', false);
                    $filters.first().focus();
                } else {
                    $filters.val('').prop('disabled', true);
                    $tbody.find('.no-result').remove();
                    $tbody.find('tr').show();
                }
            });

            $('.filterable .filters input').keyup(function (e) {
                /* Ignore tab key */
                var code = e.keyCode || e.which;
                if (code == '9') return;
                /* Useful DOM data and selectors */
                var $input = $(this),
                inputContent = $input.val().toLowerCase(),
                $panel = $input.parents('.filterable'),
                column = $panel.find('.filters th').index($input.parents('th')),
                $table = $panel.find('.table'),
                $rows = $table.find('tbody tr');
                /* Dirtiest filter function ever ;) */
                var $filteredRows = $rows.filter(function () {
                    var value = $(this).find('td').eq(column).text().toLowerCase();
                    return value.indexOf(inputContent) === -1;
                });
                /* Clean previous no-result if exist */
                $table.find('tbody .no-result').remove();
                /* Show all rows, hide filtered ones (never do that outside of a demo ! xD) */
                $rows.show();
                $filteredRows.hide();
                /* Prepend no-result row if all rows are filtered */
                if ($filteredRows.length === $rows.length) {
                    $table.find('tbody').prepend($('<tr class="no-result text-center"><td colspan="' + $table.find('.filters th').length + '">Uw filters hebben geen resultaten opgeleverd.</td></tr>'));
                }
            });
        });
</script>
<?php
}
?>
