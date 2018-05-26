<?php
include_once("includes.php");
Permissions::checkSession(basename($_SERVER['REQUEST_URI']));

if (isset($_GET['recover']))
{
    $_SESSION['receipt'] = $_SESSION['receipt']['old'];

    ?>
    <span id="receiptNo"><h2>Bon #<?php echo str_pad($_SESSION['receipt']['id'], 4, '0', STR_PAD_LEFT); ?></h2></span>

    <script>
        $(document).ready(function ()
        {
            $("#newReceipt").html("<span class=\"glyphicon glyphicon-file\"></span> " + $('#receiptNo').html().replace('<h2>', '').replace('</h2>', ''));
            $("#closeErrorBtn").on("click", function () {
                $("#customerForm").fadeIn();
                $("#loaderAnimation").fadeOut();
            });

            $("#pageLoaderIndicator").fadeIn();
            $("#PageContent").load("receipt.php?new", function () {
                $("#pageLoaderIndicator").fadeOut();
            });

            $.notify({
                icon: 'glyphicon glyphicon-trash',
                title: '',
                message: 'Bon is successvol herstelt'
            }, {
                // settings
                type: 'success',
                delay: 2000,
                timer: 10,
                placement: {
                    from: "bottom",
                    align: "right"
                }
            });
        });
    </script>
    <?php
}
else if (isset($_GET['new']))
{
    if (!isset($_SESSION['receipt']['status']) || $_SESSION['receipt']['status'] != 'open')
    {
        $thisIp = $_SERVER['REMOTE_ADDR'];
        $db = new mysqli($config['SQL_HOST'], $config['SQL_USER'], $config['SQL_PASS'], $config['SQL_DB']);

        if($db->connect_errno > 0)
        {
            die('Unable to connect to database [' . $db->connect_error . ']');
        }

        //Create the recept (ALTER TABLE receipt AUTO_INCREMENT = 20170000)
        $sql = "INSERT INTO receipt (creator, items, createDt, parentSession) VALUES ('" . $_SESSION['login_ok']['userId'] . "', '', '" .  date("H:i:s d-m-Y") . "', '" . Misc::sqlGet("currentSession", "cash_registers", "crStaticIP", $thisIp)['currentSession'] . "')";

        if(!$result = $db->query($sql))
        {
            die('There was an error running the query [' . $db->error . ']');
        }

        $_SESSION['receipt']['status'] = 'open';
        $_SESSION['receipt']['saved'] = false;
        $_SESSION['receipt']['id'] = mysqli_insert_id($db);
    }
?>
<div id="cartForm">
        <span id="receiptNo"><h2>Bon #<?php echo $_SESSION['receipt']['id']; ?></h2></span>

        <div class="panel panel filterable">
            <div class="panel-heading">
                <?php
                if (isset($_SESSION['receipt']['customer']))
                {
                    echo Misc::sqlGet("initials", "customers", "customerId", $_SESSION['receipt']['customer'])['initials'] . ' ' . Misc::sqlGet("familyName", "customers", "customerId", $_SESSION['receipt']['customer'])['familyName'] . '<br />';

                    if (Misc::sqlGet("companyName", "customers", "customerId", $_SESSION['receipt']['customer'])['companyName'] != "")
                        echo Misc::sqlGet("companyName", "customers", "customerId", $_SESSION['receipt']['customer'])['companyName'] . '<br />';

                    echo Misc::sqlGet("streetName", "customers", "customerId", $_SESSION['receipt']['customer'])['streetName'] . '<br />';
                    echo Misc::sqlGet("postalCode", "customers", "customerId", $_SESSION['receipt']['customer'])['postalCode'] . ' ';
                    echo Misc::sqlGet("city", "customers", "customerId", $_SESSION['receipt']['customer'])['city'] . '<br />';

                }
                ?>
            </div>

            Omschrijving: <input type="text" class="form-control" id="receiptDescription"></input>
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
                        <th width="160px">
                            <a href="#" class="mustFocus">
                                <input type="text" class="form-control" placeholder="Verkoop prijs" disabled />
                            </a>
                        </th>
                    </tr>
                </thead>

                <tbody id="listContents">
                    <?php
                        if (!empty($_SESSION['receipt']['items']))
                        while ($val = current($_SESSION['receipt']['items']))
                        {
                            $total = Misc::calculate(round($_SESSION['receipt']['items'][key($_SESSION['receipt']['items'])]['priceAPiece']['priceExclVat'] * $_CFG['VAT'], 2) . " " . $_SESSION['receipt']['items'][key($_SESSION['receipt']['items'])]['priceAPiece']['priceModifier']);
                            $purchase = $_SESSION['receipt']['items'][key($_SESSION['receipt']['items'])]['priceAPiece']['priceExclVat'];
                            $vatOnly = (($_SESSION['receipt']['items'][key($_SESSION['receipt']['items'])]['priceAPiece']['priceExclVat'] * $_CFG['VAT']) - $_SESSION['receipt']['items'][key($_SESSION['receipt']['items'])]['priceAPiece']['priceExclVat']);

                            echo '<tr>';
                            echo '    <th><button id="trash' .  key($_SESSION['receipt']['items']) . '" type="button" class="btn btn-danger"><span class="glyphicon glyphicon-trash" style="font-size: 12px;"></span></button></th>';
                            echo '    <th><input class="form-control" style="width: 156px; display: none;" id="editable' . key($_SESSION['receipt']['items']) . '" value="' . $val['count'] . '" type="text" name="type"/><a style="float: left;" href="javascript:void(0);" id="editAmount' . key($_SESSION['receipt']['items']) . '">' . $val['count'] . '</a></th>';
                            
                            echo '    <th>';

                            if ($_SESSION['receipt']['items'][key($_SESSION['receipt']['items'])]['itemDesc'] == "")
                                echo '<a href="#" style="color: black;" id="itemDesc' . key($_SESSION['receipt']['items']) . '">Tijdelijk Artikel (' . key($_SESSION['receipt']['items']) . ')</a>';
                            else
                                echo '<a href="#" style="color: black;" id="itemDesc' . key($_SESSION['receipt']['items']) . '">' . $_SESSION['receipt']['items'][key($_SESSION['receipt']['items'])]['itemDesc'] . '</a>';
                            
                            echo '    
                                          <input class="form-control" type="input" id="newItemDesc' . key($_SESSION['receipt']['items']) . '" style="float: left; display: none; width: 50%;" value="' . $_SESSION['receipt']['items'][key($_SESSION['receipt']['items'])]['itemDesc'] . '">
                                          <input type="button" class="btn btn-primary" style="float: left; display: none; width: 15%;" id="changeCDBtn' . key($_SESSION['receipt']['items']) . '" value="Wijzigen" />
                                          <input type="button" class="btn btn-default" style="display: none; float: left; width: 15%;" id="cancelCDBtn' . key($_SESSION['receipt']['items']) . '" value="Annuleren" />
                                          <script>
                                            $(document).ready(function() {
                                                $("#itemDesc' . key($_SESSION['receipt']['items']) . '").on("click", function () {
                                                    $("#changeCDBtn' . key($_SESSION['receipt']['items']) . '").css("display", "");
                                                    $("#newItemDesc' . key($_SESSION['receipt']['items']) . '").css("display", "");
                                                    $("#cancelCDBtn' . key($_SESSION['receipt']['items']) . '").css("display", "");

                                                    $("#itemDesc' . key($_SESSION['receipt']['items']) . '").css("display", "none");
                                                });

                                                $("#cancelCDBtn' . key($_SESSION['receipt']['items']) . '").on("click", function () {
                                                    $("#changeCDBtn' . key($_SESSION['receipt']['items']) . '").css("display", "none");
                                                    $("#newItemDesc' . key($_SESSION['receipt']['items']) . '").css("display", "none");
                                                    $("#cancelCDBtn' . key($_SESSION['receipt']['items']) . '").css("display", "none");

                                                    $("#itemDesc' . key($_SESSION['receipt']['items']) . '").css("display", "");
                                                });

                                                $("#changeCDBtn' . key($_SESSION['receipt']['items']) . '").on("click", function () {
                                                    $.get(
                                                        "receipt/changeItemDesc.php",
                                                        {
                                                            itemId: "' . key($_SESSION['receipt']['items']) . '",
                                                            newDesc: encodeURIComponent($("#newItemDesc' . key($_SESSION['receipt']['items']) . '").val())
                                                        },
                                                        function (data)
                                                        {
                                                            if (data == "\nOK")
                                                            {
                                                                $("#itemDesc' . key($_SESSION['receipt']['items']) . '").html($("#newItemDesc' . key($_SESSION['receipt']['items']) . '").val());

                                                                $("#changeCDBtn' . key($_SESSION['receipt']['items']) . '").css("display", "none");
                                                                $("#newItemDesc' . key($_SESSION['receipt']['items']) . '").css("display", "none");
                                                                $("#cancelCDBtn' . key($_SESSION['receipt']['items']) . '").css("display", "none");

                                                                $("#itemDesc' . key($_SESSION['receipt']['items']) . '").css("display", "");

                                                                $.notify({
                                                                    icon: \'fa fa-check fa-2x\',
                                                                    title: \'Artikel naam veranderd<br />\',
                                                                    message: \'Artikel naam is voor deze bon veranderd.\'
                                                                }, {
                                                                    // settings
                                                                    type: \'success\',
                                                                    delay: 5000,
                                                                    timer: 10,
                                                                    placement: {
                                                                        from: "bottom",
                                                                        align: "right"
                                                                    }
                                                                });
                                                            }
                                                            else
                                                            {
                                                                $.notify({
                                                                    icon: \'fa fa-exclamation-triangle fa-2x\',
                                                                    title: \'Error<br />\',
                                                                    message: data
                                                                }, {
                                                                    // settings
                                                                    type: \'danger\',
                                                                    delay: 5000,
                                                                    timer: 10,
                                                                    placement: {
                                                                        from: "bottom",
                                                                        align: "right"
                                                                    }
                                                                });
                                                            }
                                                        }
                                                    );
                                                });
                                            });
                                          </script>
                                      </th>';
                            echo '    <th><span class="priceClickable" id="' . key($_SESSION['receipt']['items']) . '" data-placement="bottom" data-trigger="hover">';
                            echo '        <a href="javascript:void(0);" id="editPrice' . key($_SESSION['receipt']['items']) . '">';
                            echo '            &euro;&nbsp;' . number_format(round(round($total, 2) * $_SESSION['receipt']['items'][key($_SESSION['receipt']['items'])]['count'], 2), 2, ",", ".") . '</a>';
                            echo '        </span>';
                            echo '        <div id="popover-title' . key($_SESSION['receipt']['items']) . '" class="hidden">';
                            echo '            <b>Prijs berekening</b>';
                            echo '        </div>';
                            echo '        <div id="popover-content' . key($_SESSION['receipt']['items']) . '" class="hidden">';
                            echo '            <div>';
                            echo '            Inkoop: &euro;&nbsp;' . number_format(round($purchase, 2), 2, ",", ".") . '<br/>
                                              Btw. : &nbsp;&nbsp;&nbsp;&euro;&nbsp;' . number_format(round($vatOnly, 2), 2, ",", ".") . '<br />
                                              Marge: &euro;&nbsp;' . number_format(round($total - (round($purchase, 2) + round($vatOnly, 2)), 2), 2, ",", ".") . '<br />
                                              P.S: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&euro;&nbsp; ' . number_format(round($total, 2), 2, ",", ".") . '<br />
                                              Totaal:&nbsp; &euro;&nbsp;' . number_format(round(round($total, 2) * $_SESSION['receipt']['items'][key($_SESSION['receipt']['items'])]['count'], 2), 2, ",", ".") . '<br />';
                            echo '            </div>';
                            echo '        </div>';
                            echo '    </th>';
                            echo '</tr>';

                            echo '
                            <!-- Modal -->
                            <div class="modal fade" id="priceChange' .  key($_SESSION['receipt']['items']) . '" role="dialog">
                                <div class="modal-dialog">

                                    <!-- Modal content-->
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                            <h4 class="modal-title">Prijs aanpasssen</h4>
                                        </div>
                                        <div class="modal-body">
                                            <div id="absolutePriceDiv' . key($_SESSION['receipt']['items']) . '">
                                                <div class="form-group">
                                                    <label for="priceExclVat' .  key($_SESSION['receipt']['items']) . '">Inkoop prijs: </label>
                                                    <input type="text" class="form-control" id="absolutePriceExclVat' . key($_SESSION['receipt']['items']) . '" placeholder="26,66" value="' . round($_SESSION['receipt']['items'][key($_SESSION['receipt']['items'])]['priceAPiece']['priceExclVat'], 2) . '" />
                                                </div>
                                                <div class="form-group">
                                                    <label for="priceExclVat' .  key($_SESSION['receipt']['items']) . '">Absolute prijs: </label>
                                                    <input type="text" class="form-control" id="absolutePriceVal' . key($_SESSION['receipt']['items']) . '" placeholder="26,66" value="" />
                                                </div>
                                            </div>
                                            <div id="nonAbsoluteDiv' . key($_SESSION['receipt']['items']) . '"" hidden>
                                                <div class="form-group">
                                                    <label for="priceExclVat' .  key($_SESSION['receipt']['items']) . '">Inkoop prijs: </label>
                                                    <input type="text" class="form-control" id="priceExclVat' . key($_SESSION['receipt']['items']) . '" placeholder="26,66" value="' . round($_SESSION['receipt']['items'][key($_SESSION['receipt']['items'])]['priceAPiece']['priceExclVat'], 2) . '" />
                                                </div>
                                                <label for="priceModifier' .  key($_SESSION['receipt']['items']) . '">Prijs berekening: </label>
                                                <div class="input-group">
                                                    <span class="input-group-addon" id="priceExclVatLabel' .  key($_SESSION['receipt']['items']) . '" style="min-width: 96px; border-bottom-left-radius: 0px !important;">
                                                        Inkoop<br />
                                                        &euro;&nbsp;
                                                    </span>
                                                    <span class="input-group-addon" id="priceVatOnly' .  key($_SESSION['receipt']['items']) . '" style="border-bottom-right-radius: 0px !important;">
                                                        Btw<br />
                                                        &nbsp;&euro;&nbsp;
                                                    </span>
                                                    <span class="input-group-addon" id="priceMarginOnly' .  key($_SESSION['receipt']['items']) . '" style="border-bottom-right-radius: 0px !important;">
                                                        Marge<br />
                                                        &nbsp;&euro;&nbsp;
                                                    </span>
                                                    <span class="input-group-addon" id="priceResell' .  key($_SESSION['receipt']['items']) . '" style="border-bottom-right-radius: 0px !important;">
                                                        Verkoop<br />
                                                        &nbsp;&euro;&nbsp;
                                                    </span>
                                                </div>
                                                <div class="input-group">
                                                    <span class="input-group-addon" id="" style="border-top-left-radius: 0px !important;">
                                                        ($INKOOP * $BTW)<br />
                                                    </span>
                                                    <input type="text" style="height: 42px; border-top-right-radius: 0px !important;" class="form-control" id="priceModifier' .  key($_SESSION['receipt']['items']) . '" aria-describedby="priceModifierLabel" placeholder=" * 1.375" value="' . str_replace('.',',', $_SESSION['receipt']['items'][key($_SESSION['receipt']['items'])]['priceAPiece']['priceModifier']) . '" />
                                                </div>
                                            </div>

                                            <div class="checkbox">
                                              <label><input type="checkbox" value="" id="absolutePrice' . key($_SESSION['receipt']['items']) . '" checked>Absolute prijs berekening</label>
                                            </div>
                                            <div class="checkbox">
                                              <label><input type="checkbox" value="" id="global' . key($_SESSION['receipt']['items']) . '" checked>Artikel prijs aanpassen voor alleen deze bon.</label>
                                            </div>
                                        </div>
                                        <div class="modal-footer" id="stockWarningFooter">
                                            <button id="update' .  key($_SESSION['receipt']['items']) . '" type="button" class="btn btn-primary" data-dismiss="modal">Opslaan</button>
                                            <button type="button" data-dismiss="modal" class="btn btn-default">Annuleren</button>
                                        </div>
                                    </div>
                                </div>
                            </div>';

                            echo '<script>
                            $(document).ready(function() {
                                // Disable function
                                jQuery.fn.extend({
                                    disable: function(state) {
                                        return this.each(function() {
                                            this.disabled = state;
                                        });
                                    }
                                });

                                $("#absolutePrice' . key($_SESSION['receipt']['items']) . '").change(function() {
                                    if(this.checked)
                                    {
                                        $("#nonAbsoluteDiv' . key($_SESSION['receipt']['items']) . '").hide();
                                        $("#absolutePriceDiv' . key($_SESSION['receipt']['items']) . '").show();
                                    }
                                    else
                                    {
                                        $("#nonAbsoluteDiv' . key($_SESSION['receipt']['items']) . '").show();
                                        $("#absolutePriceDiv' . key($_SESSION['receipt']['items']) . '").hide();
                                    }
                                });

                                $("#' . key($_SESSION['receipt']['items']) . '").popover({
                                    html : true,
                                    content: function() {
                                      return $("#popover-content' . key($_SESSION['receipt']['items']) . '").html();
                                    },
                                    title: function() {
                                      return $("#popover-title' . key($_SESSION['receipt']['items']) . '").html();
                                    }
                                });

                                //SET THE VALUES ONCE!!!!!!
                                var vat = "' . $_CFG['VAT'] . '";

                                //Set price excl vat label
                                $("#priceExclVatLabel' . key($_SESSION['receipt']['items']) . '").html("Inkoop<br />&euro;&nbsp;" + $(\'#priceExclVat' . key($_SESSION['receipt']['items']) . '\').val().replace(".", ","));

                                //Set vat price
                                $.get(
                                    "item/calcString.php",
                                    {
                                        sum: encodeURIComponent($(\'#priceExclVat' . key($_SESSION['receipt']['items']) . '\').val().replace(",", ".") + " * " + vat)
                                    },
                                    function (data)
                                    {
                                        $(\'#priceVatOnly' . key($_SESSION['receipt']['items']) . '\').html("Btw<br />&nbsp;&euro;&nbsp;" + parseFloat(data.replace(",", ".") - $(\'#priceExclVat' . key($_SESSION['receipt']['items']) . '\').val().replace(",", ".")).toFixed(2).replace(".", ","));
                                    }
                                );

                                //Set resell price and margin only price
                                $.get(
                                    "item/calcString.php",
                                    {
                                        sum: encodeURIComponent("(" +  $(\'#priceExclVat' . key($_SESSION['receipt']['items']) . '\').val().replace(",", ".") + " * " + vat  + ") " + $("#priceModifier' . key($_SESSION['receipt']['items']) . '").val())
                                    },
                                    function (data)
                                    {
                                        $("#absolutePriceVal' . key($_SESSION['receipt']['items']) . '").val(data);
                                    }
                                );

                                //Set resell price and margin only price
                                $.get(
                                    "item/calcString.php",
                                    {
                                        sum: encodeURIComponent("(" +  $(\'#priceExclVat' . key($_SESSION['receipt']['items']) . '\').val().replace(",", ".") + " * " + vat  + ") " + $("#priceModifier' . key($_SESSION['receipt']['items']) . '").val())
                                    },
                                    function (data)
                                    {
                                        $("#priceResell' . key($_SESSION['receipt']['items']) . '").html("Verkoop<br />&nbsp;&euro;&nbsp;" + data);

                                        $.get(
                                            "item/calcString.php",
                                            {
                                                sum: encodeURIComponent(data + " - " + "(" +  $(\'#priceExclVat' . key($_SESSION['receipt']['items']) . '\').val().replace(",", ".") + " * " + vat  + ")")
                                            },
                                            function (dataTwo)
                                            {
                                                $("#priceMarginOnly' . key($_SESSION['receipt']['items']) . '").html("Marge<br />&nbsp;&euro;&nbsp;" + dataTwo);
                                            }
                                        );
                                    }
                                );
                                //END!!!!!!!
                                var shouldReload;

                                $("#update' .  key($_SESSION['receipt']['items']) . '").click(function() {
                                    shouldReload = true;
                                });

                                $("#priceChange' .  key($_SESSION['receipt']['items']) . '").on(\'hidden.bs.modal\', function () {
                                    if (shouldReload)
                                    {
                                        shouldReload = false;

                                        if ($("#absolutePrice' . key($_SESSION['receipt']['items']) . '").is(\':checked\'))
                                        {
                                            $.get(
                                                "receipt/updateModifier.php",
                                                {
                                                    //Update values here damn it!
                                                    modifier: " + " + ($("#absolutePriceVal' . key($_SESSION['receipt']['items']) . '").val().replace(",", ".") - ($("#absolutePriceExclVat' . key($_SESSION['receipt']['items']) . '").val().replace(",", ".") * vat)).toFixed(2),
                                                    global: $("#global' . key($_SESSION['receipt']['items']) . '").is(\':checked\'),
                                                    nativeId: "' . key($_SESSION['receipt']['items']) . '",
                                                    priceExclVat: $("#absolutePriceExclVat' . key($_SESSION['receipt']['items']) . '").val().replace(",", ".")
                                                },
                                                function (data)
                                                {
                                                    $("#pageLoaderIndicator").fadeIn();
                                                    $("#PageContent").load("receipt.php?new", function () {
                                                        $("#pageLoaderIndicator").fadeOut();
                                                    });
                                                }
                                            );
                                        }
                                        else
                                        {
                                            $.get(
                                                "receipt/updateModifier.php",
                                                {
                                                    modifier: $("#priceModifier' . key($_SESSION['receipt']['items']) . '").val(),
                                                    global: $("#global' . key($_SESSION['receipt']['items']) . '").is(\':checked\'),
                                                    nativeId: "' . key($_SESSION['receipt']['items']) . '",
                                                    priceExclVat: $("#priceExclVat' . key($_SESSION['receipt']['items']) . '").val().replace(",", ".")
                                                },
                                                function (data)
                                                {
                                                    $("#pageLoaderIndicator").fadeIn();
                                                    $("#PageContent").load("receipt.php?new", function () {
                                                        $("#pageLoaderIndicator").fadeOut();
                                                    });
                                                }
                                            );
                                        }
                                    }
                                });

                                $("#editAmount' . key($_SESSION['receipt']['items']) . '").click(function() {
                                    var $this = $(this);
                                    var text = $this.text();

                                    if(text == "Aanpassen")
                                    {
                                        if ($("#editable' . key($_SESSION['receipt']['items']) . '").val() != "0")
                                        {
                                            $("#editable' . key($_SESSION['receipt']['items']) . '").css("display", "none");
                                            $this.text($("#editable' . key($_SESSION['receipt']['items']) . '").val());

                                            $.get(
                                                "receipt/updateAmount.php",
                                                {
                                                    amount: $("#editable' . key($_SESSION['receipt']['items']) . '").val(),
                                                    nativeId: "' . key($_SESSION['receipt']['items']) . '"
                                                },
                                                function (data)
                                                {
                                                    $("#pageLoaderIndicator").fadeIn();
                                                    $("#PageContent").load("receipt.php?new", function () {
                                                        $("#pageLoaderIndicator").fadeOut();
                                                    });
                                                }
                                            );

                                            var priceOne = ' . Misc::calculate($_SESSION['receipt']['items'][key($_SESSION['receipt']['items'])]['priceAPiece']['priceExclVat'] . ' ' . $_SESSION['receipt']['items'][key($_SESSION['receipt']['items'])]['priceAPiece']['priceModifier']) . ';
                                            var total = priceOne * parseInt($("#editable' . key($_SESSION['receipt']['items']) . '").val());
                                            $("#editPrice' . key($_SESSION['receipt']['items']) . '").val(total);
                                        }
                                        else
                                        {
                                            $.get(
                                                "receipt/updateAmount.php",
                                                {
                                                    amount: 1,
                                                    nativeId: "' . key($_SESSION['receipt']['items']) . '"
                                                },
                                                function (data)
                                                {
                                                    $("#trash' .  key($_SESSION['receipt']['items']) . '").click();
                                                }
                                            );
                                        }
                                    }
                                    else
                                    {
                                         $this.text("Aanpassen");
                                         $("#editable' . key($_SESSION['receipt']['items']) . '").toggle();
                                    }
                                });

                                $(\'#priceModifier' . key($_SESSION['receipt']['items']) . '\').on(\'input\', function ()
            				    {
                                    var vat = "' . $_CFG['VAT'] . '";

            				        $.get(
                                        "item/calcString.php",
                                        {
                                            sum: encodeURIComponent("(" +  $(\'#priceExclVat' . key($_SESSION['receipt']['items']) . '\').val().replace(",", ".") + " * " + vat  + ") " + $("#priceModifier' . key($_SESSION['receipt']['items']) . '").val())
                                        },
                                        function (data)
                                        {
                                            $("#priceResell' . key($_SESSION['receipt']['items']) . '").html("Verkoop<br />&nbsp;&euro;&nbsp;" + data);

                                            $.get(
                                                "item/calcString.php",
                                                {
                                                    sum: encodeURIComponent(data + " - " + "(" +  $(\'#priceExclVat' . key($_SESSION['receipt']['items']) . '\').val().replace(",", ".") + " * " + vat  + ")")
                                                },
                                                function (dataTwo)
                                                {
                                                    $("#priceMarginOnly' . key($_SESSION['receipt']['items']) . '").html("Marge<br />&nbsp;&euro;&nbsp;" + dataTwo);
                                                }
                                            );
                                        }
                                    );
            				    });

            				    $(\'#priceExclVat' . key($_SESSION['receipt']['items']) . '\').on(\'input\', function ()
            				    {
                                    var vat = "' . $_CFG['VAT'] . '";

                                    //Set price excl vat label
                                    $("#priceExclVatLabel' . key($_SESSION['receipt']['items']) . '").html("Inkoop<br />&euro;&nbsp;" + $(\'#priceExclVat' . key($_SESSION['receipt']['items']) . '\').val().replace(".", ","));

                                    //Set vat price
                					$.get(
                						"item/calcString.php",
                						{
                							sum: encodeURIComponent($(\'#priceExclVat' . key($_SESSION['receipt']['items']) . '\').val().replace(",", ".") + " * " + vat)
                						},
                						function (data)
                						{
                							$(\'#priceVatOnly' . key($_SESSION['receipt']['items']) . '\').html("Btw<br />&nbsp;&euro;&nbsp;" + parseFloat(data.replace(",", ".") - $(\'#priceExclVat' . key($_SESSION['receipt']['items']) . '\').val().replace(",", ".")).toFixed(2).replace(".", ","));
                						}
                					);

                                    //Set resell price and margin only price
                                    $.get(
                                        "item/calcString.php",
                                        {
                                            sum: encodeURIComponent("(" +  $(\'#priceExclVat' . key($_SESSION['receipt']['items']) . '\').val().replace(",", ".") + " * " + vat  + ") " + $("#priceModifier' . key($_SESSION['receipt']['items']) . '").val())
                                        },
                                        function (data)
                                        {
                                            $("#priceResell' . key($_SESSION['receipt']['items']) . '").html("Verkoop<br />&nbsp;&euro;&nbsp;" + data);

                                            $.get(
                                                "item/calcString.php",
                                                {
                                                    sum: encodeURIComponent(data + " - " + "(" +  $(\'#priceExclVat' . key($_SESSION['receipt']['items']) . '\').val().replace(",", ".") + " * " + vat  + ")")
                                                },
                                                function (dataTwo)
                                                {
                                                    $("#priceMarginOnly' . key($_SESSION['receipt']['items']) . '").html("Marge<br />&nbsp;&euro;&nbsp;" + dataTwo);
                                                }
                                            );
                                        }
                                    );
            				    });

                                $("#' . key($_SESSION['receipt']['items']) . '").on("click", function() {
                                    $(\'#priceChange' . key($_SESSION['receipt']['items']) . '\').modal(\'show\');
                                });

                                $("#trash' . key($_SESSION['receipt']['items']) . '").on("click", function() {
                                  $.get(
                                      "receipt/removeItem.php",
                                      {
                                          itemId: \'' . key($_SESSION['receipt']['items']) . '\',
                                          itemCount: \'1\'
                                      },
                                      function (data)
                                      {
                                        $.notify({
                                            icon: \'glyphicon glyphicon-trash\',
                                            title: \'' . urldecode(Items::getField("itemName", key($_SESSION['receipt']['items']))) . '\',
                                            message: \'<br />Verwijderd van bon <a style="color: white;" href="#" id="undo' . key($_SESSION['receipt']['items']) . '">(Ongedaan maken)</a>\'
                                        }, {
                                            // settings
                                            type: \'danger\',
                                            delay: 5000,
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

                                        $("#undo' . key($_SESSION['receipt']['items']) . '").on("click", function() {
                                            $("#undo' . key($_SESSION['receipt']['items']) . '").css("display", "none");

                                            $.get(
                                              "receipt/addItem.php",
                                              {
                                                  itemId: \'' . key($_SESSION['receipt']['items']) . '\',
                                                  itemCount: \'1\'
                                              },
                                              function (data)
                                              {
                                                $.notify({
                                                    icon: \'glyphicon glyphicon-trash\',
                                                    title: \'' . urldecode(Items::getField("itemName", key($_SESSION['receipt']['items']))) . '\',
                                                    message: \'<br />Toegevoegt aan bon.\'
                                                }, {
                                                    // settings
                                                    type: \'success\',
                                                    delay: 5000,
                                                    timer: 10,
                                                    placement: {
                                                        from: "bottom",
                                                        align: "right"
                                                    }
                                                });
                                              }
                                          );

                                          $("#pageLoaderIndicator").fadeIn();
                                          $("#PageContent").load("receipt.php?new", function () {
                                              $("#pageLoaderIndicator").fadeOut();
                                          });
                                      }
                                  );
                                });

                                  $("#pageLoaderIndicator").fadeIn();
                                  $("#PageContent").load("receipt.php?new", function () {
                                      $("#pageLoaderIndicator").fadeOut();
                                  });
                                });

                            });
                            </script>';
                            next($_SESSION['receipt']['items']);
                        }
                    ?>
                </tbody>
            </table>
        </div>
    <button type="button" id="closeReceipt" class="btn btn-default">Bon Sluiten</button>
    <button type="button" id="saveReceipt" class="btn btn-primary">Bon Opslaan</button>
    <?php if (!isset($_SESSION['receipt']['customer'])) { ?><button type="button" id="selectCustomer" class="btn btn-info">Selecteer klant</button> <?php } ?>
    <?php if (isset($_SESSION['receipt']['customer'])) { ?><button type="button" id="deselectCustomer" class="btn btn-danger">Verwijder klant van bon</button> <?php } ?>
    <button type="button" id="payBtn" class="btn btn-primary pull-right" data-toggle="modal" data-target="#printAmount">Betalen</button>


    <div class="form-group pull-right" style="width: 320px; padding-right: 32px;">
        <select class="combobox form-control" id="paymentMethod">
            <option value="" selected="selected">Selecteer betaal methode</option>
            <option value="CASH">Kontant</option>
            <option value="PIN">Pin</option>
            <!--<option value="PC">Pin & Kontant</option>-->
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

    <h2 style="float: right; position: relative; left: -32px;">Totaal: &euro;<?php echo number_format(round(Calculate::getReceiptTotal($_SESSION['receipt']['id'], true)['total'], 2), 2, ",", "."); ?></h2>
    <div class="panel panel-default" id="debugPanel" style="display: none;">
        <div class="panel-heading">
            <button type="button" class="btn btn-default btn-xs spoiler-trigger" data-toggle="collapse">Debug Info</button>
        </div>
        <div class="panel-collapse collapse out">
            <div class="panel-body">
                <pre>
                    <?php print_r ($_SESSION); ?>
                </pre>
            </div>
        </div>
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
                    <?php if (isset($_SESSION['receipt']['customer'])) { ?>
                    <div id="mailingSegment">
                        <span style="top: -8px; position: relative;">
                            Bon emailen naar klant <input type="checkbox" name="emailToCustomer" id="emailToCustomer" 	data-size="small" data-on-text="Ja" data-off-text="Nee" checked></input><br />
                        </span>
                        <div class="row" id="emailList">
                            <div class="column">
                                <?php 
                                    if (Misc::sqlGet("email", "customers", "customerId", $_SESSION['receipt']['customer'])['email'] == "")
                                    {
                                        ?>
                                            <input type='text' id='example_email' name='example_emailSUI' class='form-control' value='["<?php echo "facturen@comforttoday.nl"; ?>"]'>
                                        <?php
                                    }
                                    else
                                    {
                                        ?>
                                            <input type='text' id='example_email' name='example_emailSUI' class='form-control' value='["<?php echo Misc::sqlGet("email", "customers", "customerId", $_SESSION['receipt']['customer'])['email']; ?>"]'>
                                        <?php
                                    }
                                ?>
                            </div>
                        </div>
                        <script type="text/javascript">
                        $(function() {
                            $('#example_email').multiple_emails( { position: "top" });
                        });
                            $(document).ready(function() {
                                $('#emailToCustomer').change(function() {
                                    if($("#emailToCustomer").is(":checked"))
                                    {
                                        $("#emailList").children().prop('disabled',false);
                                        $("#emailList").fadeTo(500, 1);
                                        $("#emailList").css("pointer-events", "");
                                    }
                                    else
                                    {
                                        $("#emailList").children().prop('disabled', true);
                                        $("#emailList").fadeTo(500, 0.2);
                                        $("#emailList").css("pointer-events", "none");
                                    }
                                });

                                $('#example_email').change( function(){
                                    $('#current_emails').text($(this).val());
                                });
                            });
                        </script>
                    </div>
                    <br />
                    <?php } else if (!isset($_SESSION['receipt']['customer'])) { ?>
                    <div id="mailingSegment">
                        <span style="top: -8px; position: relative;">
                            Bon emailen naar administratie <input type="checkbox" name="emailToCustomer" id="emailToCustomer" 	data-size="small" data-on-text="Ja" data-off-text="Nee"></input><br />
                        </span>
                        <div class="row" id="emailList">
                            <div class="column">
                                <input type='text' id='example_email' name='example_emailSUI' class='form-control' value='["<?php echo "facturen@comforttoday.nl"; ?>"]'>
                            </div>
                        </div>
                        <script type="text/javascript">
                        $(function() {
                            $('#example_email').multiple_emails( { position: "top" });
                        });
                            $(document).ready(function() {
                                $('#emailToCustomer').change(function() {
                                    if($("#emailToCustomer").is(":checked"))
                                    {
                                        $("#emailList").children().prop('disabled',false);
                                        $("#emailList").fadeTo(500, 1);
                                        $("#emailList").css("pointer-events", "");
                                    }
                                    else
                                    {
                                        $("#emailList").children().prop('disabled', true);
                                        $("#emailList").fadeTo(500, 0.2);
                                        $("#emailList").css("pointer-events", "none");
                                    }
                                });

                                $('#example_email').change( function(){
                                    $('#current_emails').text($(this).val());
                                });
                            });
                        </script>
                    </div>
                    <br />
                    <?php } ?>
                    <div style="float: left;"><input id="letterPaperInput" type="checkbox" name="letterPaperInput"> Briefpapier</input></div>
                    <button type="button" class="btn btn-success" id="printReceipt" data-dismiss="modal">Bon printen</button>
                    <button type="button" class="btn btn-warning" id="printNoReceipt" data-dismiss="modal">Geen bon printen</button>
                </div>
            </div>
        </div>
    </div>
    <?php
        $total = 0;
        if (isset($_SESSION['receipt']['items']))
        {
            foreach ($_SESSION['receipt']['items'] as $key => $val)
            {
                $price = Misc::calculate($_SESSION['receipt']['items'][$key]['priceAPiece']['priceExclVat'] . ' ' . $_SESSION['receipt']['items'][$key]['priceAPiece']['priceModifier']);
                $price *= $val['count'];
                $total += $price;
            }
        }
    ?>
    <script type="text/javascript">
        <?php if (!isset($_SESSION['receipt']['customer'])) { ?>
            $("#payBtn").click(function() {
                if ($('#paymentMethod').val() == "BANK")
                {
                    $("#emailToCustomer").prop('checked', true);
                    $("#mailingSegment").show();
                    $("example_email").prop("disabled", "");
                }
                else
                {
                    $("#mailingSegment").hide();
                    $("#emailToCustomer").prop('checked', false);
                }
            });
        <?php } ?>

        function showDebug()
        {
            $("#debugPanel").css("display", "");
        }

        function checkTotalValue()
        {
            var totalPrice = "<?php echo round(Calculate::getReceiptTotal($_SESSION['receipt']['id'], true)['total'], 2); ?>";
            if ($('#paymentMethod').val() != "PC")
            {
                if ($('#paymentMethod').val() != "PIN")
                {
                    $('#pinVal').val(totalPrice);
                    $('#cashVal').val("0,00");
                }
                else if ($('#paymentMethod').val() != "CASH")
                {
                    $('#cashVal').val(totalPrice);
                    $('#pinVal').val("0,00");
                }
                else if ($('#paymentMethod').val() != "BANK")
                {
                    $('#pinVal').val(0);
                    $('#cashVal').val(0);
                }
            }

            if (parseInt( $("#pinVal").val()) > totalPrice || parseInt( $("#cashVal").val()) > totalPrice || $("#pinVal").val() == "" || $("#cashVal").val() == "")
            {
                //$("#payBtn").prop("disabled", true); //Not needed

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

        $(document).ready(function() {
            $("#saveReceipt").click(function () {
                var rows = document.getElementById("listContents").getElementsByTagName("tr").length;
                if (rows > 0)
                {
                    $.get(
                        "receipt/saveReceipt.php",
                        { },
                        function (data)
                        {
                            if (data.replace(/(\r\n|\n|\r)/gm,"") == "OK")
                            {
                                $.notify({
                                    icon: 'glyphicon glyphicon-floppy-saved',
                                    title: 'Opgeslagen',
                                    message: '<br />Bon is successvol opgeslagen.'
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
                            }
                            else
                            {
                                $.notify({
                                    icon: 'glyphicon glyphicon-warning-sign',
                                    title: 'Fout',
                                    message: '<br />Bon is niet opgeslagen :(<br />' + data
                                }, {
                                    // settings
                                    type: 'danger',
                                    delay: 2000,
                                    timer: 10,
                                    placement: {
                                        from: "bottom",
                                        align: "right"
                                    }
                                });
                            }
                        }
                    );
                }
                else
                {
                    $.notify({
                        icon: 'glyphicon glyphicon-warning-sign',
                        title: 'Bon is niet opgeslagen',
                        message: '<br />Voeg eerst artikelen toe om de bon op te slaan.'
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
            });

            $(".spoiler-trigger").click(function() {
            	$(this).parent().next().collapse('toggle');
            });

            var totalPrice = "<?php echo round(Calculate::getReceiptTotal($_SESSION['receipt']['id'], true)['total'], 2); ?>";
            $('#cashVal').keyup(function() {
                if (this.value != "")
                {
                    var half = parseFloat(totalPrice.replace(",", ".")) - parseFloat( $("#cashVal").val().replace(",", "."));
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
                    var half = parseFloat(totalPrice.replace(",", ".")) - parseFloat( $("#pinVal").val().replace(",", "."));
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

                if (this.value == "BANK")
                    $("#emailToCustomer").prop('checked', true);
                else
                    $("#emailToCustomer").prop('checked', false);
                $('#statusText').html('');

                checkTotalValue();
            });

            $('#printAmount').on('hidden.bs.modal', function () {
                if (isButtonClick)
                {
                    isButtonClick = false;

                    console.log($('#example_email').val());

                    if ($( "#paymentMethod" ).val() != "")
                    {
                        if ($('#paymentMethod').val() == "PC")
                        {
                            var pinVal = $('#pinVal').val();
                            var cashVal = $('#cashVal').val();

                            $("#pageLoaderIndicator").fadeIn();
                            $("#sideBarMenu").addClass("disabledbutton");
                            $("#PageContent").load("receipt/processReceipt.php?receiptId=<?php echo $_SESSION['receipt']['id']; ?>&printLetterPaper=" + $("#letterPaperInput").is(':checked') + "&mail=" + $("#emailToCustomer").is(":checked") + "&printAmount=" + printAmount + "&paymentMethod=" + $( "#paymentMethod" ).val() +"&receiptDesc=" + encodeURIComponent($('#receiptDescription').val()) + "&mailList=" + encodeURIComponent($('#example_email').val()) + "&pin=" + pinVal + "&cash=" + cashVal, function () { });
                       }
                       else
                       {
                           $("#pageLoaderIndicator").fadeIn();
                           $("#sideBarMenu").addClass("disabledbutton");
                           $("#PageContent").load("receipt/processReceipt.php?receiptId=<?php echo $_SESSION['receipt']['id']; ?>&printLetterPaper=" + $("#letterPaperInput").is(':checked') + "&mail=" + $("#emailToCustomer").is(":checked") + "&printAmount=" + printAmount + "&receiptDesc=" + encodeURIComponent($('#receiptDescription').val()) + "&mailList=" + encodeURIComponent($('#example_email').val()) + "&paymentMethod=" + $( "#paymentMethod" ).val(), function () { });
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
                $("#newReceipt").html("<i class=\"fa fa-file-text fa-2x\" aria-hidden=\"true\"></i>&nbsp;&nbsp; Nieuwe Bon");

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
                            title: 'Bon venster gesloten',
                            message: '<a href="#" id="undoCloseReceipt" style="color: white;">(Ongedaan maken)</a>'
                        }, {
                            // settings
                            type: 'warning',
                            delay: 5000,
                            timer: 10,
                            placement: {
                                from: "bottom",
                                align: "right"
                            }
                        });

                        $('#undoCloseReceipt').click(function () {
                            $("#undoCloseReceipt").css("display", "none");

                            $("#pageLoaderIndicator").fadeIn();
                            $("#PageContent").load("receipt.php?recover", function () {
                                $("#pageLoaderIndicator").fadeOut();
                            });
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
                <button class="btn btn-primary" type="submit" id="searchBtn" style="height: 38px;">
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
                    "receipt/receiptLoad.php",
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

            $('#searchBtn').click();

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
                    <th>
                        <a href="#" class="mustFocus">
                            <input type="text" class="form-control" placeholder="Betaal Methode" disabled />
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

            </tbody>
        </table>
        <?php
    if (0 >= 25)
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
                                    "receipt/receiptLoad.php",
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

$time = microtime();
$time = explode(' ', $time);
$time = $time[1] + $time[0];
$finish = $time;
$total_time = str_replace("0.", "", number_format(($finish - $start), 4));
echo '<script> $(document).ready(function () { console.log("Page created in '.$total_time.'ms"); });';

?>
