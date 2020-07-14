<?php
include_once("includes.php");
Permissions::checkSession(basename($_SERVER['REQUEST_URI']));

if (isset($_GET['new']))
{
?>
		<div id="customerForm">
			<h2>Nieuw contract</h2>
            
            <label for="email">Factuur datum: </label>
            <div class="input-group">
                <span class="input-group-addon" type="button" id="selectCustomer" id="basic-addon2">Ieder(e)</span>
                <input type="text" class="form-control" placeholder="jaar, kwartaal of maand" aria-describedby="basic-addon2">
                <span class="input-group-addon" type="button" id="selectCustomer" id="basic-addon2">op</span>
                <input type="text" class="form-control" placeholder="20" aria-describedby="basic-addon2">
            </div><br />
            
            <label for="email">Start datum: </label>
			<div class="form-group">
                <div class='input-group date' id='datetimepicker2'>
                    <input type='text' class="form-control" id="startDate" placeholder="20-07-2020" />
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                </div>
			</div><br />
            
            <label for="email">Klant: </label>
            <div class="input-group">
                <input type="text" class="form-control" placeholder="A. Bakker | Com Today | abakker@voorbeeld.nl" value="<?php

                if (isset($_SESSION['receipt']['customer']))
                {
                    echo Misc::sqlGet("initials", "customers", "customerId", $_SESSION['receipt']['customer'])['initials'] . ' ' . Misc::sqlGet("familyName", "customers", "customerId", $_SESSION['receipt']['customer'])['familyName'].' | ';
                    
                    if (Misc::sqlGet("companyName", "customers", "customerId", $_SESSION['receipt']['customer'])['companyName'] != "")
                        echo Misc::sqlGet("companyName", "customers", "customerId", $_SESSION['receipt']['customer'])['companyName'] . ' | ';

                    echo Misc::sqlGet("email", "customers", "customerId", $_SESSION['receipt']['customer'])['email'];
                }
                ?>" aria-describedby="basic-addon2" readonly>
                <a href="#" class="input-group-addon" type="button" id="selectCust">Selecteer klant</a>
            </div><br />

            <div class="text-center panel panel-info">
                <div class="panel-heading" style="position: relative; top: -1;">
                    <h3 class="panel-title">Factuur specificatie</h3>
                </div>
                <div class="panel-body">
                    <?php if(!isset($_SESSION['receipt']['items'])) {?>
                    <?php if($_SESSION['receipt']['status'] != "open") {?><span class="pull-center">De factuur specificatie wordt opgebouwd vanuit de gegevens op een open staande bon, maak een nieuwe bon aan en vul deze aan. Kom daarna terug op deze pagina om je contract aan te maken.</span><br /><br /><?php } ?>
                    <?php if($_SESSION['receipt']['status'] == "open") {?><span class="pull-center">Er zijn geen artikelen toegevoegt op de open staande bon! De factuur specificatie wordt opgebouwd vanuit de gegevens op een open staande bon, voeg artikelen toe aan de bon. Kom daarna terug op deze pagina om je contract aan te maken.</span><br /><br /><?php } ?>
                    <?php if($_SESSION['receipt']['status'] != "open") {?><button type="button" id="loadNewReceipt" class="btn btn-secondary pull-center">Nieuwe bon maken</button><?php } ?>
                    <?php } else { ?>
                        <table class="table">
                <thead>
                    <tr class="filters">
                        <th width="64px">
                            Aantal
                        </th>
                        <th>
                            Item
                        </th>
                        <th width="160px">
                            Verkoop prijs
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
                            echo '    <th><input class="form-control" style="width: 156px; display: none;" id="editable' . key($_SESSION['receipt']['items']) . '" value="' . $val['count'] . '" type="text" name="type"/><a style="float: left;" href="javascript:void(0);" id="editAmount' . key($_SESSION['receipt']['items']) . '">' . $val['count'] . '</a></th>';
                            
                            echo '    <th>';

                            if ($_SESSION['receipt']['items'][key($_SESSION['receipt']['items'])]['itemDesc'] == "")
                                echo '<a href="#" style="color: black;" id="itemDesc' . key($_SESSION['receipt']['items']) . '">Tijdelijk Artikel (' . key($_SESSION['receipt']['items']) . ')</a>';
                            else
                                echo '<a href="#" style="color: black;" id="itemDesc' . key($_SESSION['receipt']['items']) . '">' . $_SESSION['receipt']['items'][key($_SESSION['receipt']['items'])]['itemDesc'] . '</a>';
                            
                            echo '    
                                          
                                      </th>';
                            echo '    <th><span class="priceClickable" id="' . key($_SESSION['receipt']['items']) . '" data-placement="bottom" data-trigger="hover">';
                            echo '        <span id="editPrice' . key($_SESSION['receipt']['items']) . '">';
                            echo '            '.$_CFG['CURRENCY'].'&nbsp;' . number_format(round(round($total, 2) * $_SESSION['receipt']['items'][key($_SESSION['receipt']['items'])]['count'], 2), 2, ",", ".") . '</span>';
                            echo '        </span>';
                            echo '        <div id="popover-title' . key($_SESSION['receipt']['items']) . '" class="hidden">';
                            echo '            <b>Prijs berekening</b>';
                            echo '        </div>';
                            echo '        <div id="popover-content' . key($_SESSION['receipt']['items']) . '" class="hidden">';
                            echo '            <div>';
                            echo '            Inkoop: '.$_CFG['CURRENCY'].'&nbsp;' . number_format(round($purchase, 2), 2, ",", ".") . '<br/>
                                              Btw. : &nbsp;&nbsp;&nbsp;'.$_CFG['CURRENCY'].'&nbsp;' . number_format(round($vatOnly, 2), 2, ",", ".") . '<br />
                                              Marge: '.$_CFG['CURRENCY'].'&nbsp;' . number_format(round($total - (round($purchase, 2) + round($vatOnly, 2)), 2), 2, ",", ".") . '<br />
                                              P.S: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$_CFG['CURRENCY'].'&nbsp; ' . number_format(round($total, 2), 2, ",", ".") . '<br />
                                              Totaal:&nbsp; '.$_CFG['CURRENCY'].'&nbsp;' . number_format(round(round($total, 2) * $_SESSION['receipt']['items'][key($_SESSION['receipt']['items'])]['count'], 2), 2, ",", ".") . '<br />';
                            echo '            </div>';
                            echo '        </div>';
                            echo '    </th>';
                            echo '</tr>';

                            ?>
                            <script>
                                $(document).ready(function () {
                                    $("#<?=key($_SESSION['receipt']['items'])?>").popover({
                                        html : true,
                                        content: function() {
                                            return $("#popover-content<?=key($_SESSION['receipt']['items'])?>").html();
                                        },
                                        title: function() {
                                            return $("#popover-title<?=key($_SESSION['receipt']['items'])?>").html();
                                        }
                                    });
                                });
                            </script>
                            <?php
                            
                            next($_SESSION['receipt']['items']);
                        }
                    ?>
                </tbody>
            </table>
                    <h2 class="pull-right" style="margin: 0 !important; padding: 0 !important;">Totaal: <?=$_CFG['CURRENCY']?><?php echo number_format(round(Calculate::getReceiptTotal($_SESSION['receipt']['id'], true)['total'], 2), 2, ",", "."); ?></h2>
                    <?php } ?>
                </div>
            </div>

            <div class="container">
                <div class="row">
                    <div class='col-sm-6'>
                        <div class="form-group">
                        </div>
                    </div>
                    <script type="text/javascript">
                        $(document).ready(function () {
                            const event = new Date();
                            const options = { year: 'numeric', month: 'numeric', day: 'numeric' };

                            $("#startDate").attr("placeholder", event.toLocaleDateString('nl-NL', options));

                            $('#datetimepicker2').datepicker({
                                language: 'nl',
                                calendarWeeks: true,
                                orientation: "bottom auto"
                            });

                            $("#loadNewReceipt").click(function() {
                                $("#pageLoaderIndicator").fadeIn();
                                $("#PageContent").load("receipt.php?new", function () {
                                    $("#pageLoaderIndicator").fadeOut();
                                });
                            });

                            $('#selectCust').click(function () {
                                $("#pageLoaderIndicator").fadeIn();
                                $("#PageContent").load("customer.php", function () {
                                    $("#pageLoaderIndicator").fadeOut();
                                });
                            });
                        });
                    </script>
                </div>
            </div>

            <button type="button" id="applyBtn" class="btn btn-primary" <?php if(!isset($_SESSION['receipt']['customer']) || !isset($_SESSION['receipt']['items'])) {?>disabled<?php } ?>>Contract toevoegen</button>
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
				<p><div id="errorMessageContent"></div><br />Blijft het probleem bestaan? Neem dan contact op met de systeems administrator.</p>
			  </div>
			  <div class="modal-footer">
				<button type="button" class="btn btn-primary" id="closeErrorBtn" data-dismiss="modal">Sluiten</button>
				<script>
					$(document).ready(function ()
					{
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
				<h4 class="modal-title">Nieuwe klant</h4>
			  </div>
			  <div class="modal-body">
				<p>De klant gegevens zijn verwerkt in het systeem.</p>
			  </div>
			  <div class="modal-footer">
				<button type="button" class="btn btn-primary" id="openCustBtn" data-dismiss="modal">Weergeef klant</button>
				<button type="button" class="btn btn-secondary" id="closeOkBtn" data-dismiss="modal">Sluiten</button>
				<input type="hidden" id="customerId" name="customerId" value="">
				<script>
					$(document).ready(function ()
					{
						$("#openCustBtn").on("click", function () {
						    $("#okMessage").modal("hide");
						});

						$('#okMessage').on('hidden.bs.modal', function () {
						    $("#PageContent").load('customer/viewCustomer.php?id=' + $('#customerId').val());
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
			<center>Nieuwe klant wordt verwerkt in het systeem...</center>
		</div>
	<?php
}
else if (isset($_GET['overview']))
{
?>
<div class="container container-table">
        <div class="row vertical-center-row">
            <div class="col-md-1"></div>
            <div class="col-md-8" style="margin-top: 32px;">
                <div class="text-center panel panel-info">
                    <div class="panel-heading">
                        <h3 class="panel-title">Contracten overzicht</h3>
                    </div>
                    <div class="panel-body" style="display: inline-block; text-align: left">
                        <b>Contracten:</b><br />
                        <div><span style="margin-left: 2.5em; font-size: 12px;">Active contracten:</span><i style="float: right;">&nbsp;&nbsp;10</i></div><br />
                        <div style="position: relative; top: -16px;"><span style="margin-left: 2.5em; font-size: 12px;">Geannuleerde contracten:</span><i style="float: right;">&nbsp;&nbsp;12</i></div><br />
                        <div style="position: relative; top: -32px;"><b style="margin-left: 2.5em; font-size: 12px;">Totaal aantal contracten:</b><i style="float: right;">&nbsp;&nbsp;22</i></div>
                        
                        <b>Active contracten:</b><br />
                        <div><span style="margin-left: 2.5em; font-size: 12px;">Gemiddelde:</span><i style="float: right;"><?=$_CFG['CURRENCY']?>&nbsp;34,95</i></div><br />
                        <div style="position: relative; top: -16px;"><b style="margin-left: 2.5em; font-size: 12px;">Totaal inkomend:</b><i style="float: right;"><?=$_CFG['CURRENCY']?>&nbsp;363.85</i></div>

                        <b>Uitgaande mails:</b><br />
                        <div><span style="margin-left: 2.5em; font-size: 12px;">Verstuurd:</span><i style="float: right;">&nbsp;&nbsp;5</i></div><br />
                        <div style="position: relative; top: -16px;"><span style="margin-left: 2.5em; font-size: 12px;">Nog te versturen:</span><i style="float: right;">&nbsp;&nbsp;7</i></div><br />
                        <div style="position: relative; top: -32px;"><b style="margin-left: 2.5em; font-size: 12px;">Uitgaande mailbox:</b><i style="float: right;">&nbsp;&nbsp;facturen@comtoday.nl</i></div>
                    </div>
                </div>

                <div class="panel panel-info">
                    <b>Mail agenda:</b> <?php echo $row['openDate']; ?><br />
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">Tijd & Datum</th>
                                <th scope="col">Contract</th>
                                <th scope="col">Ontvanger</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th>2020-07-18T23:55:00+0000</th>
                                <th><a href="#">#2020001</a></th>
                                <td>menno@vleeuwen.me</td>
                            </tr>
                            <tr>
                                <th>2020-07-15T23:55:00+0000</th>
                                <th><a href="#">#2020001</a></th>
                                <td>rob@comtoday.nl</td>
                            </tr>
                            <tr>
                                <th>2020-07-17T23:55:00+0000</th>
                                <th><a href="#">#2020001</a></th>
                                <td>harryhakman@hotmail.com</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="panel panel-info">
                    <b>Mail log:</b> <?php echo $row['openDate']; ?><br />
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">Tijd & Datum</th>
                                <th scope="col">Contract</th>
                                <th scope="col">Ontvanger</th>
                                <th scope="col">Succesvol verstuurd</th>
                                <th scope="col">Melding</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th>2020-07-14T11:52:40+0000</th>
                                <th><a href="#">#2020001</a></th>
                                <td>menno@vleeuwen.me</td>
                                <td>Ja</td>
                                <td>N.v.t.</td>
                            </tr>
                            <tr>
                                <th>2020-07-14T11:52:40+0000</th>
                                <th><a href="#">#2020001</a></th>
                                <td>rob@comtoday.nl</td>
                                <td>Ja</td>
                                <td>N.v.t.</td>
                            </tr>
                            <tr>
                                <th>2020-07-14T11:52:40+0000</th>
                                <th><a href="#">#2020001</a></th>
                                <td>harryhakman@hotmail.com</td>
                                <td>Nee</td>
                                <td>501 5.7.0 – Authentication failed.  Username or password is invalid.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
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
            <input type="text" class="form-control" name="searchBar" id="searchBar" placeholder="Zoek term... (Klant voorletter, Klant achternaam, Id)" aria-describedby="basic-addon2">
            <span class="input-group-btn">
            <button class="btn btn-primary" type="submit" id="searchBtn" style="height: 38px;">
                <i class="fa fa-search fa-1x" aria-hidden="true"></i>
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
                    "customer/customerLoad.php",
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
                <h3 class="panel-title">Contracten</h3>
                <div class="pull-right">
                    <button class="btn btn-default btn-xs btn-filter">
                        <i class="fa fa-filter fa-1x" aria-hidden="true"></i>&nbsp;&nbsp;&nbsp;Filteren
                    </button>
                </div>
            </div>
            <table class="table">
                <thead>
                    <tr class="filters">
                        <th>
                            <a href="#" class="mustFocus">
                                <input type="text" class="form-control" placeholder="Voorletters" disabled />
                            </a>
                        </th>
                        <th>
                            <a href="#" class="mustFocus">
                                <input type="text" class="form-control" placeholder="Achternaam" disabled />
                            </a>
                        </th>
                        <th>
                            <a href="#" class="mustFocus">
                                <input type="text" class="form-control" placeholder="Bedrijfsnaam" disabled />
                            </a>
                        </th>
                        <th>
                            <a href="#" class="mustFocus">
                                <input type="text" class="form-control" placeholder="Postcode" disabled />
                            </a>
                        </th>
                    </tr>
                </thead>
                <script>
                    $(document).ready(function ()
                    {
                        $("#searchBtn").trigger('click');

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
                    <button type="button" class="btn btn-info center-block" id="loadMore">Laad Meer</button><br />
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
                                    "customer/customerLoad.php",
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
        <br /><br /><br /><br />
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
