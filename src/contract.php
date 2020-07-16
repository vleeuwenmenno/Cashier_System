<?php
include_once("includes.php");
Permissions::checkSession(basename($_SERVER['REQUEST_URI']));

if (isset($_GET['new']))
{
?>
		<div id="customerForm">
			<h2>Nieuw contract</h2>
            <label for="email">Factuur datum: </label>
            <!-- Button trigger modal -->
            <a href="#" data-toggle="modal" data-target="#exampleModal">
                factuur planning uitleg
            </a>

            <!-- Modal -->
            <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h2 class="modal-title" id="exampleModalLabel">Factuur datum uitleg</h2>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p style="font-size: 9pt;">
                            <h3>Jaar</h3>
                            Ieder jaar wordt gefactureerd op de maand van de start datum en op de aangegeven dag. <br />(Bijvoorbeeld: start datum 02-2018 en aangegeven dag 5, dan wordt de eerstvolgende data 05-03-2018 en dan 05-03-2019, 05-03-2020 enz.)<br /><br />
                            
                            <h3>Kwartaal</h3>
                            Ieder kwartaal wordt gefactureerd op de eerste maand van dat kwartaal op de aangegeven dag.<br />(Bijvoorbeeld: start datum 02-2018 en aangegeven dag 5, dan wordt de eerstvolgende data 05-03-2018 en dan 05-04-2018, 05-07-2018, 05-10-2018 enz.)<br /><br />
                            
                            <h3>Maand</h3>
                            Iedere maand wordt gefactureerd op de op de aangegeven dag van de maand.<br />(Bijvoorbeeld: start datum 02-2018 en aangegeven dag 5, dan wordt de eerstvolgende data 05-03-2018 en dan 05-04-2019, 05-05-2020, enz.)<br /><br />
                            
                            <h3>Overige</h3>
                            Om direct een factuur te sturen na het aanmaken vink dan "Factuur direct sturen" aan.<br /> (Dit veranderd de planning de daarna komende facturen niet)<br /><br />
                        </p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Sluiten</button>
                    </div>
                    </div>
                </div>
            </div>
            <div class="input-group">
                <span class="input-group-addon" type="button" id="selectCustomer" id="basic-addon2">Ieder(e)</span>
                <select class="combobox form-control" id="paymentPeroid">
                    <option value="year">jaar</option>
                    <option value="quarter">kwartaal</option>
                    <option value="month">maand</option>
                </select>
                <span class="input-group-addon" type="button" id="selectCustomer" id="basic-addon2">op de</span>
                <select class="combobox form-control" id="paymentDate">
                    <option value="1">1ste</option>
                    <option value="2">2de</option>
                    <option value="3">3de</option>
                    <option value="4">4de</option>
                    <option value="5">5de</option>
                    <option value="6">6de</option>
                    <option value="7">7de</option>
                    <option value="8">8ste</option>
                    <option value="9">9de</option>
                    <option value="10">10de</option>
                    <option value="11">11de</option>
                    <option value="12">12de</option>
                    <option value="13">13de</option>
                    <option value="14">14de</option>
                    <option value="15">15de</option>
                    <option value="16">16de</option>
                    <option value="17">17de</option>
                    <option value="18">18de</option>
                    <option value="19">19de</option>
                    <option value="20">20ste</option>
                    <option value="21">21ste</option>
                    <option value="22">22ste</option>
                    <option value="23">23ste</option>
                    <option value="24">24ste</option>
                    <option value="25">25ste</option>
                    <option value="26">26ste</option>
                    <option value="27">27ste</option>
                    <option value="28">28ste</option>
                </select>
            </div><br />
            
            <label for="email">Start datum: </label>
			<div class="form-group">
                <div class='input-group date' id='datetimepicker2'>
                    <input type='text' class="form-control" id="startDate" placeholder="07-2020" />
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                </div>
			</div>
            
            <div class="checkbox">
                <label><input type="checkbox" id="sendOrderImmediatly" value="">Factuur direct sturen (Met factuur datum: <span id="today"></span>)</label>
            </div>
            
            <label for="email">Klant: </label>
            <div class="input-group">
                <input type="text" class="form-control" placeholder="A. Bakker | Com Today | abakker@voorbeeld.nl" value="<?php

                if (isset($_SESSION['receipt']['customer']))
                {
                    echo Misc::sqlGet("initials", "customers", "customerId", $_SESSION['receipt']['customer'])['initials'] . ' ' . Misc::sqlGet("familyName", "customers", "customerId", $_SESSION['receipt']['customer'])['familyName'].' | ';
                    
                    if (Misc::sqlGet("companyName", "customers", "customerId", $_SESSION['receipt']['customer'])['companyName'] != "")
                        echo Misc::sqlGet("companyName", "customers", "customerId", $_SESSION['receipt']['customer'])['companyName'] . ' | ';
                    
                    if (Misc::sqlGet("email", "customers", "customerId", $_SESSION['receipt']['customer'])['email'] == "")
                        echo "Geen email, voeg email adres toe!";
                    else
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
                            
                            echo '    </th>';
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

                            $("#startDate").attr("placeholder", event.toLocaleDateString('nl-NL', options).substring(3));
                            $("#today").html(event.toLocaleDateString('nl-NL', options));

                            $('#datetimepicker2').datepicker({
                                format: "mm-yyyy",
                                calendarWeeks: true,
                                orientation: "bottom auto",    
                                viewMode: "months", 
                                minViewMode: "months",
                                startDate: "today"
                            });

                            $("#loadNewReceipt").click(function() {
                                $("#pageLoaderIndicator").fadeIn();
                                $("#PageContent").load("receipt.php?new", function () {
                                    $("#pageLoaderIndicator").fadeOut();
                                });
                            });

                            $('#selectCust').click(function () {
                                $("#pageLoaderIndicator").fadeIn();
                                $("#PageContent").load("customer.php?returnContract=1", function () {
                                    $("#pageLoaderIndicator").fadeOut();
                                });
                            });
                        });
                    </script>
                </div>
            </div>
			<div class="text-center panel panel-info">
                <div class="panel-heading" style="position: relative; top: -1;">
                    <h3 class="panel-title">Factuur planning</h3>
                </div>
                <div class="panel-body">
					<ul class="list-group" id="nextOrderTime">
					</ul>
				</div>
			</div>
            <button type="button" id="addContractBtn" class="btn btn-primary" <?php if(!isset($_SESSION['receipt']['customer']) || !isset($_SESSION['receipt']['items']) || Misc::sqlGet("email", "customers", "customerId", $_SESSION['receipt']['customer'])['email'] == "") {?>disabled<?php } ?>>Contract toevoegen</button>
            <script>
                function updateMonths()
                {
                    const options = { year: 'numeric', month: 'long', day: 'numeric' };

                    $("#nextOrderTime").html("Eerste factuur wordt verzonden op de volgende data: <br />");

                    for (var i = 0; i < 6; i++)
                    {
                        var date = calculateNext(i);

                        if ($("#paymentPeroid").children("option:selected").val() == "quarter")
                            $("#nextOrderTime").html($("#nextOrderTime").html() + "<li class=\"list-group-item\">" + date.toLocaleDateString('nl-NL', options) + " kwartaal " + getQuarter(date) + "</li>");
                        else
                            $("#nextOrderTime").html($("#nextOrderTime").html() + "<li class=\"list-group-item\">" + date.toLocaleDateString('nl-NL', options) + "</li>");
                    }
                }
        
                $( document ).ready(function() {
                    
                    $("#paymentDate").change(function(){
                        updateMonths();
                    });
                    
                    $("#paymentPeroid").change(function(){
                        updateMonths();
                    });
                    
                    $("#startDate").change(function(){
                        updateMonths();
                    });

                    $("#addContractBtn").click(function () {
                        const options = { year: 'numeric', month: 'numeric', day: 'numeric' };

                        $.get(
                            "contract/contractAdd.php",
                            {
                                startDate: "01-" + $("#startDate").val() + " 00:00:00",
                                planningPeriod: $("#paymentPeroid").children("option:selected").val(),
                                planningDay: parseInt($("#paymentDate").children("option:selected").val()),
                                sendNow: $('#sendOrderImmediatly').is(":checked"),
                            },
							function(data)
							{
								if (data.replace(/(\r\n|\n|\r)/gm,"").startsWith("OK"))
								{        
                                    $("#newReceipt").html("<i class=\"fa fa-file-text\" aria-hidden=\"true\"></i>&nbsp;&nbsp; Nieuwe Bon");
                                    $("#newReceipt").hide();

									var arr = data.replace(/(\r\n|\n|\r)/gm,"").split(' ');
									$("#contractId").val(arr[1]);
									$("#okMessage").modal("show");
								}
								else
								{
									$("#errorMessageContent").text(data);
									$("#errorMessage").modal("show");
								}
							}
                        );
                    });
                });

                // safety check to see if the prototype name is already defined
                Function.prototype.method = function (name, func) {
                    if (!this.prototype[name]) {
                        this.prototype[name] = func;
                        return this;
                    }
                };

                Date.method('inPast', function () {
                    return this < new Date($.now());// the $.now() requires jQuery
                });

                // including this prototype as using in example
                Date.method('addDays', function (days) {
                    var date = new Date(this);
                    date.setDate(date.getDate() + (days));    
                    return date;
                });

                // including this prototype as using in example
                Date.method('addMonths', function (months) {
                    var date = new Date(this);
                    var d = date.getDate();
                    date.setMonth(date.getMonth() + +months);
                    if (date.getDate() != d) {
                        date.setDate(0);
                    }
                    return date;
                });

                function monthDiff(d1, d2) 
                {
                    var months;
                    months = (d2.getFullYear() - d1.getFullYear()) * 12;
                    months -= d1.getMonth();
                    months += d2.getMonth();
                    return months <= 0 ? 0 : months;
                }

                function leapYear(year)
                {
                    return ((year % 4 == 0) && (year % 100 != 0)) || (year % 400 == 0);
                }

                function getQuarter(d) 
                {
                    d = d || new Date(); // If no date supplied, use today
                    var q = [1,2,3,4];
                    return q[Math.floor(d.getMonth() / 3)];
                }

                function getDaysLeftinQuarter(d)
                {
                    var today = d;
                    var quarter = Math.floor((today.getMonth() + 3) / 3);
                    var nextq;
                    if (quarter == 4) {
                        nextq = new Date (today.getFullYear() + 1, 1, 1);
                    } else {
                        nextq = new Date (today.getFullYear(), quarter * 3, 1);
                    }
                    var millis1 = today.getTime();
                    var millis2 = nextq.getTime();
                    return (millis2 - millis1) / 1000 / 60 / 60 / 24;
                }

                function calculateNext(nextNext = 0)
                {
                    var start = new Date($("#startDate").val().split("-")[1] + "-" + $("#startDate").val().split("-")[0] + "-01");
                    var period = $("#paymentPeroid").children("option:selected").val();
                    var day = parseInt($("#paymentDate").children("option:selected").val());
                    var sendNow = $('#sendOrderImmediatly').is(":checked");
                    var now = new Date();
                    var next = new Date();

                    if (period == "year")
                    {
                        if (day < now.getDate()) /// Check if we have to skip to the next month
                        {
                            if ((next.getMonth() + 2) == 13) /// Make sure we don't try to say month 13
                                next = new Date(now.getFullYear()+1 + "-01-" + day); // If it is month 13, we change it to 1 and increase the year by 1
                            else
                                next = new Date(now.getFullYear() + "-" + (next.getMonth() + 1) + "-" + day); // Continue normally
                        }
                        else /// This month
                        {
                            next = new Date(now.getFullYear() + "-" + (next.getMonth() + 1) + "-" + day);
                        }

                        if (sendNow && nextNext == 0) /// Check if we have to send it now
                            return now;

                        next.setFullYear(next.getFullYear() + nextNext);

                        if (next.inPast())
                            next = new Date();
                    }
                    else if (period == "quarter")
                    {
                        next = new Date().addDays(Math.ceil(getDaysLeftinQuarter(new Date())));
                        next = next.addMonths(monthDiff(next, start))
                        next = next.addDays(day-1)


                        if (nextNext > 0 && sendNow)
                        {
                            next = next.addDays(92*(nextNext-1));
                            next = new Date(next.getFullYear() + "-" + (next.getMonth()+1) + "-" + day);
                        }
                        else if (nextNext > 0)
                        {
                            next = next.addDays(92*nextNext);
                            next = new Date(next.getFullYear() + "-" + (next.getMonth()+1) + "-" + day);
                        }
                        else if (nextNext == 0 && sendNow)
                        {
                            next = new Date();
                        }
                    }
                    else // month
                    {
                        if (day < now.getDate()) /// Check if we have to skip to the next month
                        {
                            if ((next.getMonth() + 2) == 13) /// Make sure we don't try to say month 13
                                next = new Date((now.getFullYear()+1) + "-01-" + day); // If it is month 13, we change it to 1 and increase the year by 1
                            else
                                next = new Date(now.getFullYear() + "-" + (next.getMonth() + 2) + "-" + day); // Continue normally
                        }
                        else /// This month
                        {
                            next = new Date(now.getFullYear() + "-" + (next.getMonth() + 1) + "-" + day);
                        }
                        
                        /// Check if we have to send it now and this is the first
                        if (sendNow && nextNext == 0)
                            next = new Date();
                        else if (sendNow) /// Check if we send one today
                            next.setMonth(next.getMonth() + nextNext-1);
                        else // Continue normally
                            next.setMonth(next.getMonth() + nextNext);
                    }

                    /// Check if the start date is later than today except for yearly 
                    if (start.getTime() > now.getTime() && period == "year")
                    {
                        start.setFullYear(start.getFullYear() + nextNext);
                        start.setDate(day);
                            
                        return start; // The start date is later than today so we will set the next date to be the start date.
                    }
                    else if (start.getTime() > now.getTime() && period == "month")
                    {
                        start.setMonth(start.getMonth() + nextNext);
                        start.setDate(day);
                            
                        return start; // The start date is later than today so we will set the next date to be the start date.
                    }
                    else
                        return next; // The start date has already passed so the next date will be as planned.
                }
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
				<h4 class="modal-title">Nieuw contract</h4>
			  </div>
			  <div class="modal-body">
				<p>Contract gegevens zijn verwerkt in het systeem.</p>
			  </div>
			  <div class="modal-footer">
				<button type="button" class="btn btn-primary" id="openCustBtn" data-dismiss="modal">Weergeef contract</button>
				<button type="button" class="btn btn-secondary" id="closeOkBtn" data-dismiss="modal">Sluiten</button>
				<input type="hidden" id="contractId" name="contractId" value="">
				<script>
					$(document).ready(function ()
					{
						$("#openCustBtn").on("click", function () {
						    $("#okMessage").modal("hide");
						});

						$('#okMessage').on('hidden.bs.modal', function () {
						    $("#PageContent").load('contract/viewContract.php?id=' + $('#contractId').val());
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
			<center>Contract wordt verwerkt in het systeem...</center>
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
                        <div><span style="margin-left: 2.5em; font-size: 12px;">Totaal aantal contracten:</b><i style="float: right;">&nbsp;&nbsp;<?=Misc::sqlGetAll("COUNT(contractId)", "contract")['COUNT(contractId)']?></i></div><br />
                        <div style="position: relative; top: -16px;"><span style="margin-left: 2.5em; font-size: 12px;">Foutmeldingen:</span><i style="float: right; font-size: 12px;">&nbsp;&nbsp;<?=Misc::sqlGet("COUNT(success)", "log", "success", "0")['COUNT(success)']?></i></div><br />

                    
                        <b>Mail agenda: WIP</b><br />
                        <div class="panel panel-info">
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
                                        <th>2020-07-18 23:55:00</th>
                                        <th><a href="#">#2020001</a></th>
                                        <td>menno@vleeuwen.me</td>
                                    </tr>
                                    <tr>
                                        <th>2020-07-15 23:55:00</th>
                                        <th><a href="#">#2020001</a></th>
                                        <td>rob@comtoday.nl</td>
                                    </tr>
                                    <tr>
                                        <th>2020-07-17 23:55:00</th>
                                        <th><a href="#">#2020001</a></th>
                                        <td>harryhakman@hotmail.com</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <b>Factuur historie:</b> <?php echo $row['openDate']; ?><br />
                <div class="panel panel-info">
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">Tijd & Datum</th>
                                <th scope="col">Factuur #</th>
                                <th scope="col">Contract</th>
                                <th scope="col">Ontvanger</th>
                                <th scope="col">Succesvol verstuurd</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                
                                $sql = "SELECT * FROM log;";

                                global $config;
                        
                                $db = new mysqli($config['SQL_HOST'], $config['SQL_USER'], $config['SQL_PASS'], $config['SQL_DB']);
                        
                                if($db->connect_errno > 0)
                                {
                                    die('Unable to connect to database [' . $db->connect_error . ']');
                                }
                        
                                if(!$result = $db->query($sql))
                                {
                                    die('Er was een fout tijdens het uitvoeren van deze query (' . $db->error . ') (' . $sql . ')');
                                }

                                $list = array();
                                $i = 0;
                                while($row = $result->fetch_assoc())
                                {
                                    $list[$i] = $row;
                                    $i++;
                                }

                                usort($list, function($a, $b) {
                                    return new DateTime($a['dateTime']) <=> new DateTime($b['dateTime']);
                                });
                                $list= array_reverse($list);

                                foreach ($list as $key => $row)
                                {
                                    ?>
                                    <tr>
                                        <th><?=$row['dateTime']?></th>
                                        <th>#<?=str_pad($row['logId'], 8, '0', STR_PAD_LEFT)?></th>
                                        <th><a href="#" id="contract<?=$row['contractId']?>Btn">#<?=str_pad($row['contractId'], 8, '0', STR_PAD_LEFT)?></a></th>
                                        <td><?=$row['receiverEmail']?></td>
                                        <td><?=$row['success'] ? "Ja": "Nee"?></td>
                                    </tr>
                                    <script>
                                    	$(document).ready(function ()
                                        {
                                            $("#contract<?=$row['contractId']?>Btn").on("click", function () {
                                                $("#loaderAnimation").fadeIn();
                                                $("#PageContent").load("contract/viewContract.php?id=<?=$row['contractId']?>");
                                            });
                                        });
                
                                    </script>
                                <?php
                                }
                            ?>
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

    <div class="row" style="display: none;">
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
                    "contract/contractLoad.php",
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
                                <input type="text" class="form-control" placeholder="Contract" disabled />
                            </a>
                        </th>
                        <th>
                            <a href="#" class="mustFocus">
                                <input type="text" class="form-control" placeholder="Klant" disabled />
                            </a>
                        </th>
                        <th>
                            <a href="#" class="mustFocus">
                                <input type="text" class="form-control" placeholder="Periode" disabled />
                            </a>
                        </th>
                        <th>
                            <a href="#" class="mustFocus">
                                <input type="text" class="form-control" placeholder="Volgende factuur" disabled />
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
                                    "contract/contractLoad.php",
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
include("debug.php"); ?>