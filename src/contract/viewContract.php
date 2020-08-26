<?php
include_once("../includes.php");
Permissions::checkSession(basename($_SERVER['REQUEST_URI']));

if (isset($_GET['id']))
{
	$db = new mysqli($config['SQL_HOST'], $config['SQL_USER'], $config['SQL_PASS'], $config['SQL_DB']);

	if($db->connect_errno > 0)
	{
		die('Unable to connect to database [' . $db->connect_error . ']');
	}

	$sql = "SELECT * FROM contract WHERE contractId='" . $_GET['id'] . "'";

	if(!$result = $db->query($sql))
	{
		die('There was an error running the query [' . $db->error . ']');
	}

	while($row = $result->fetch_assoc())
	{
		if (isset($_GET['loadFromReceipt']))
		{
			$json = json_decode(urldecode($row['items']), true);
			
			$_SESSION['receipt'] = array();

			$_SESSION['receipt']['order'] = 1;
			$_SESSION['receipt']['status'] = "open";
			$_SESSION['receipt']['saved'] = 0;
			$_SESSION['receipt']['id'] = rand(0, 999999999);
			$_SESSION['receipt']['items'] = $json;
			$_SESSION['receipt']['customer'] = $row['customerId'];
		}
	?>
	<div id="customerForm">
			<h2>Contract bijwerken</h2>
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
                            Ieder jaar wordt gefactureerd op de maand van de start datum en op de aangegeven dag. <br />(Bijvoorbeeld: start datum 17-02-2018 en aangegeven dag 5, dan wordt de eerstvolgende data 05-03-2018 en dan 05-03-2019, 05-03-2020 enz.)<br /><br />
                            
                            <h3>Kwartaal</h3>
                            Ieder kwartaal wordt gefactureerd op de eerste maand van dat kwartaal op de aangegeven dag.<br />(Bijvoorbeeld: start datum 17-02-2018 en aangegeven dag 5, dan wordt de eerstvolgende data 05-03-2018 en dan 05-04-2018, 05-07-2018, 05-10-2018 enz.)<br /><br />
                            
                            <h3>Maand</h3>
                            Iedere maand wordt gefactureerd op de op de aangegeven dag van de maand.<br />(Bijvoorbeeld: start datum 17-02-2018 en aangegeven dag 5, dan wordt de eerstvolgende data 05-03-2018 en dan 05-04-2019, 05-05-2020, enz.)<br /><br />
                            
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
                    <option value="year" <?php if ($row['planningPeriod'] =="year") echo "selected"; ?>>jaar</option>
                    <option value="quarter" <?php if ($row['planningPeriod'] =="quarter") echo "selected"; ?>>kwartaal</option>
                    <option value="month" <?php if ($row['planningPeriod'] =="month") echo "selected=\"selected\""; ?>>maand</option>
                </select>
                <span class="input-group-addon" type="button" id="selectCustomer" id="basic-addon2">op de</span>
                <select class="combobox form-control" id="paymentDate">
                    <option value="1" <?php if ($row['planningDay'] =="1") echo "selected=\"selected\""; ?>>1ste</option>
                    <option value="2" <?php if ($row['planningDay'] =="2") echo "selected=\"selected\""; ?>>2de</option>
                    <option value="3" <?php if ($row['planningDay'] =="3") echo "selected=\"selected\""; ?>>3de</option>
                    <option value="4" <?php if ($row['planningDay'] =="4") echo "selected=\"selected\""; ?>>4de</option>
                    <option value="5" <?php if ($row['planningDay'] =="5") echo "selected=\"selected\""; ?>>5de</option>
                    <option value="6" <?php if ($row['planningDay'] =="6") echo "selected=\"selected\""; ?>>6de</option>
                    <option value="7" <?php if ($row['planningDay'] =="7") echo "selected=\"selected\""; ?>>7de</option>
                    <option value="8" <?php if ($row['planningDay'] =="8") echo "selected=\"selected\""; ?>>8ste</option>
                    <option value="9" <?php if ($row['planningDay'] =="9") echo "selected=\"selected\""; ?>>9de</option>
                    <option value="10" <?php if ($row['planningDay'] =="10") echo "selected=\"selected\""; ?>>10de</option>
                    <option value="11" <?php if ($row['planningDay'] =="11") echo "selected=\"selected\""; ?>>11de</option>
                    <option value="12" <?php if ($row['planningDay'] =="12") echo "selected=\"selected\""; ?>>12de</option>
                    <option value="13" <?php if ($row['planningDay'] =="13") echo "selected=\"selected\""; ?>>13de</option>
                    <option value="14" <?php if ($row['planningDay'] =="14") echo "selected=\"selected\""; ?>>14de</option>
                    <option value="15" <?php if ($row['planningDay'] =="15") echo "selected=\"selected\""; ?>>15de</option>
                    <option value="16" <?php if ($row['planningDay'] =="16") echo "selected=\"selected\""; ?>>16de</option>
                    <option value="17" <?php if ($row['planningDay'] =="17") echo "selected=\"selected\""; ?>>17de</option>
                    <option value="18" <?php if ($row['planningDay'] =="18") echo "selected=\"selected\""; ?>>18de</option>
                    <option value="19" <?php if ($row['planningDay'] =="19") echo "selected=\"selected\""; ?>>19de</option>
                    <option value="20" <?php if ($row['planningDay'] =="20") echo "selected=\"selected\""; ?>>20ste</option>
                    <option value="21" <?php if ($row['planningDay'] =="21") echo "selected=\"selected\""; ?>>21ste</option>
                    <option value="22" <?php if ($row['planningDay'] =="22") echo "selected=\"selected\""; ?>>22ste</option>
                    <option value="23" <?php if ($row['planningDay'] =="23") echo "selected=\"selected\""; ?>>23ste</option>
                    <option value="24" <?php if ($row['planningDay'] =="24") echo "selected=\"selected\""; ?>>24ste</option>
                    <option value="25" <?php if ($row['planningDay'] =="25") echo "selected=\"selected\""; ?>>25ste</option>
                    <option value="26" <?php if ($row['planningDay'] =="26") echo "selected=\"selected\""; ?>>26ste</option>
                    <option value="27" <?php if ($row['planningDay'] =="27") echo "selected=\"selected\""; ?>>27ste</option>
                    <option value="28" <?php if ($row['planningDay'] =="28") echo "selected=\"selected\""; ?>>28ste</option>
                </select>
            </div><br />
            
            <label for="email">Start datum: </label>
			<div class="form-group">
                <div class='input-group date' id='datetimepicker2'>
                    <input type='text' class="form-control" id="startDate" placeholder="07-2020" value="<?=substr(explode (' ', $row['startDate'])[0], 3)?>"/>
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                </div>
			</div>
            
            <label for="email">Klant: </label>
            <div class="input-group">
                <input type="text" class="form-control" placeholder="A. Bakker | Com Today | abakker@voorbeeld.nl" value="<?php

                if (isset($row['customerId']))
                {
					$id = $row['customerId'];

					if (isset($_GET['loadFromItems']))
						$id = $_SESSION['receipt']['customer'];

                    echo Misc::sqlGet("initials", "customers", "customerId", $id)['initials'] . ' ' . Misc::sqlGet("familyName", "customers", "customerId", $id)['familyName'].' | ';
                    
                    if (Misc::sqlGet("companyName", "customers", "customerId", $id)['companyName'] != "")
                        echo Misc::sqlGet("companyName", "customers", "customerId", $id)['companyName'] . ' | ';
                    
                    if (Misc::sqlGet("email", "customers", "customerId", $id)['email'] == "")
                        echo "Geen email, voeg email adres toe!";
                    else
                        echo Misc::sqlGet("email", "customers", "customerId", $id)['email'];
                }
                ?>" aria-describedby="basic-addon2" readonly>
				<a href="#" class="input-group-addon" type="button" id="selectCust">Selecteer klant</a>
            </div><br />
            
            <div class="checkbox">
                <label><input type="checkbox" id="directDebit" value="" <?php if (Misc::sqlGet("directDebit", "contract", "contractId", $_GET['id'])['directDebit'] == 1) { echo 'checked'; }?>>Automatisch incasso</label>
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

								$.get(
									"contract/viewContract.php",
									{
										loadFromReceipt: 1,
										id: <?=$_GET['id']?>
									},
									function(data)
									{ 
                						$("#newReceipt").html("<i class=\"glyphicon glyphicon-file\" aria-hidden=\"true\"></i>&nbsp;&nbsp; Factuur specificatie");
										$("#newReceipt").show();
										$("#pageLoaderIndicator").fadeIn();
										$("#PageContent").load("customer.php?returnViewContract=1&loadFromItems=1&rvcid=<?=$_GET['id']?>", function () {
											$("#pageLoaderIndicator").fadeOut();
										});
									}
								);
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
			<div class="text-center panel panel-info">
                <div class="panel-heading" style="position: relative; top: -1;">
                    <h3 class="panel-title">Factuur historie</h3>
                </div>
                <div class="panel-body">
					<table class="table">
                        <thead>
                            <tr>
                                <th scope="col">Factuur nr.&nbsp;</th>
                                <th scope="col">Tijd & Datum</th>
                                <th scope="col">Ontvanger</th>
                                <th scope="col">Factuur totaal</th>
                                <th scope="col">Succesvol verstuurd</th>
                                <th scope="col">Melding</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $sqls = "SELECT * FROM log WHERE contractId=".$_GET['id'].";";                        
                                $dbs = new mysqli($config['SQL_HOST'], $config['SQL_USER'], $config['SQL_PASS'], $config['SQL_DB']);
                        
                                if($dbs->connect_errno > 0)
                                {
                                    die('Unable to connect to database [' . $dbs->connect_error . ']');
                                }
                        
                                if(!$results = $dbs->query($sqls))
                                {
                                    die('Er was een fout tijdens het uitvoeren van deze query (' . $dbs->error . ') (' . $sqls . ')');
                                }

                                $list = array();
                                $i = 0;
                                while($rows = $results->fetch_assoc())
                                {
                                    $list[$i] = $rows;
                                    $i++;
                                }

                                usort($list, function($a, $b) {
                                    return new DateTime($a['dateTime']) <=> new DateTime($b['dateTime']);
                                });
                                $list= array_reverse($list);

                                foreach ($list as $key => $rowss)
                                {
                                    ?>
                                    <tr>
                                        <th><a href="#" id="contractInvoice<?=$rowss['logId']?>Btn">#<?=str_pad($rowss['logId'], 8, '0', STR_PAD_LEFT)?></a></th>
                                        <th><?=$rowss['dateTime']?></th>
                                        <td><?=$rowss['receiverEmail']?></td>
                                        <td><?=$_CFG['CURRENCY']?>&nbsp;<?php echo number_format(round($rowss['total'], 2), 2, ",", "."); ?></td>
                                        <td><?=$rowss['success'] ? "Ja": "Nee"?></td>
                                        <td><?=$rowss['notes']?></td>
                                    </tr>
                                    <script>
                                    	$(document).ready(function ()
                                        {
                                            $("#contractInvoice<?=$rowss['logId']?>Btn").on("click", function () {
                                                $("#pageLoaderIndicator").fadeIn();
                                                $("#PageContent").load("pdf/pdf.php?cid=<?=$_GET['id']?>&lid=<?=$rowss['logId']?>&exvat", function () {
                                                    $("#pageLoaderIndicator").fadeOut();
                                                });
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
			
            <div class="text-center panel panel-info">
                <div class="panel-heading" style="position: relative; top: -1;">
                    <h3 class="panel-title">Factuur specificatie</h3>
                </div>
                <div class="panel-body">
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
								if (isset($_GET['loadFromItems']))
									$json = $_SESSION['receipt']['items'];
								else
									$json = json_decode(urldecode($row['items']), true);
								
								if (!empty($json))
									while ($val = current($json))
									{
										$total = Misc::calculate(round($json[key($json)]['priceAPiece']['priceExclVat'] * $_CFG['VAT'], 2) . " " . $json[key($json)]['priceAPiece']['priceModifier']);
										$purchase = $json[key($json)]['priceAPiece']['priceExclVat'];
										$vatOnly = (($json[key($json)]['priceAPiece']['priceExclVat'] * $_CFG['VAT']) - $json[key($json)]['priceAPiece']['priceExclVat']);

										echo '<tr>';
										echo '    <th><input class="form-control" style="width: 156px; display: none;" id="editable' . key($json) . '" value="' . $val['count'] . '" type="text" name="type"/><a style="float: left;" href="javascript:void(0);" id="editAmount' . key($json) . '">' . $val['count'] . '</a></th>';
										
										echo '    <th>';

										if ($json[key($json)]['itemDesc'] == "")
											echo '<a href="#" style="color: black;" id="itemDesc' . key($json) . '">Tijdelijk Artikel (' . key($json) . ')</a>';
										else
											echo '<a href="#" style="color: black;" id="itemDesc' . key($json) . '">' . $json[key($json)]['itemDesc'] . '</a>';
										
										echo '    </th>';
										echo '    <th><span class="priceClickable" id="' . key($json) . '" data-placement="bottom" data-trigger="hover">';
										echo '        <span id="editPrice' . key($json) . '">';
										echo '            '.$_CFG['CURRENCY'].'&nbsp;' . number_format(round(round($total, 2) * $json[key($json)]['count'], 2), 2, ",", ".") . '</span>';
										echo '        </span>';
										echo '    </th>';
										echo '</tr>';
										
										next($json);
									}
							?>
						</tbody>
					</table>
                    <h2 class="pull-right" style="margin: 0 !important; padding: 0 !important;">Totaal: <?=$_CFG['CURRENCY']?>&nbsp;<?php echo number_format(round(Calculate::getContractTotal($json, true)['total'], 2), 2, ",", "."); ?></h2>
                </div>
            </div>
			
            <button type="button" style="float: left; margin-left: 8px; margin-right: 8px;" id="updateContractBtn" class="btn btn-success">Wijzigingen opslaan</button>
			<div class="dropdown" style="float: left; margin-left: 8px; margin-right: 8px;">
				<button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">Opties
				<span class="caret"></span></button>
				<ul class="dropdown-menu">
					<li><button type="button" id="loadReceiptFromItems" style="width: 100%;" class="btn btn-info">Factuur specificatie laden</button></li>
					<li><button type="button" id="loadItemsFromReceipt" style="width: 100%;" class="btn btn-info">Contract naar factuur laden</button></li>
					<li><button type="button" id="sendNewOrder" style="width: 100%;" class="btn btn-warning">Factuur vandaag inplannen</button></li>
					<li><button type="button" id="deleteContract" style="width: 100%;" class="btn btn-danger">Contract verwijderen</button></li>
				</ul>
			</div>
            
            <script>

                function populate(date)
                {
                    const options = { year: 'numeric', month: 'long', day: 'numeric' };

                    if ($("#paymentPeroid").children("option:selected").val() == "quarter")
                        $("#nextOrderTime").html($("#nextOrderTime").html() + "<li class=\"list-group-item\">" + date.toLocaleDateString('nl-NL', options) + " kwartaal " + getQuarter(date) + "</li>");
                    else
                        $("#nextOrderTime").html($("#nextOrderTime").html() + "<li class=\"list-group-item\">" + date.toLocaleDateString('nl-NL', options) + "</li>");
                }

                function updateMonths()
                {
                    $("#nextOrderTime").html("Eerste factuur wordt verzonden op de volgende data: <br />");

                    calculateNext(0, function (date)
                    {
                        populate(date);

                        calculateNext(1, function (date)
                        {
                            populate(date);

                            calculateNext(2, function (date)
                            {
                                populate(date);

                                calculateNext(3, function (date)
                                {
                                    populate(date);

                                    calculateNext(4, function (date)
                                    {
                                        populate(date);

                                        calculateNext(5, function (date)
                                        {
                                            populate(date);
                                        });
                                    });
                                });
                            });
                        });
                    });
                }
        
                $( document ).ready(function() {
					updateMonths();

					$("#deleteContract").on("click", function () {
						$("#deleteConfirm").modal("show");
					});

                    $("#paymentDate").change(function(){
                        updateMonths();
                    });
                    
                    $("#paymentPeroid").change(function(){
                        updateMonths();
                    });
                    
                    $("#startDate").change(function(){
                        updateMonths();
                    });
					
                    $("#loadItemsFromReceipt").click(function () {
						$.get(
                            "contract/viewContract.php",
                            {
                                loadFromReceipt: 1,
								id: <?=$_GET['id']?>
                            },
							function(data)
							{ 
								$("#newReceipt").html("<i class=\"glyphicon glyphicon-file\" aria-hidden=\"true\"></i>&nbsp;&nbsp; Factuur specificatie");
								$("#newReceipt").show();
								$("#pageLoaderIndicator").fadeIn();
                                $("#PageContent").load("receipt.php?new", function () {
                                    $("#pageLoaderIndicator").fadeOut();
                                });
							}
						);
					});
					
                    $("#loadReceiptFromItems").click(function () {
						$("#pageLoaderIndicator").fadeIn();
						$("#PageContent").load("contract/viewContract.php?id=<?=$_GET['id']?>&loadFromItems", function () {
							$("#pageLoaderIndicator").fadeOut();
						});
					});
					
                    $("#sendNewOrder").click(function () {
                        $("#pageLoaderIndicator").fadeIn();
						$("#PageContent").load("contract/contractForceSend.php?id=<?=$_GET['id']?>", function () {
							$("#pageLoaderIndicator").fadeOut();
						});
					});

                    $("#updateContractBtn").click(function () {
                        const options = { year: 'numeric', month: 'numeric', day: 'numeric' };

                        $.get(
                            "contract/viewContract.php",
                            {
								id: <?=$_GET['id']?>
                            },
							function(data2)
							{ 
                                $.get(
                                    "contract/contractUpdate.php",
                                    {
                                        id: <?=$_GET['id']?>,
                                        startDate: "01-" + $("#startDate").val() + " 00:00:00",
                                        planningPeriod: $("#paymentPeroid").children("option:selected").val(),
                                        planningDay: parseInt($("#paymentDate").children("option:selected").val()),
                                        directDebit: $('#directDebit').is(":checked")
                                    },
                                    function(data)
                                    {
                                        if (data.replace(/(\r\n|\n|\r)/gm,"").startsWith("OK"))
                                        {
                                            $.get(
                                                "receipt/empty.php",
                                                {
                                                    receiptId: '<?php echo $_SESSION['receipt']['id']; ?>',
                                                    destroy: 'true'
                                                },
                                                function (data3)
                                                {
                                                    $("#pageLoaderIndicator").fadeIn();
                                                    $("#PageContent").load("contract.php", function () {
                                                        $("#pageLoaderIndicator").fadeOut();
                                                    });

                                                    $.notify({
                                                        icon: 'glyphicon glyphicon-floppy-disk',
                                                        title: '<b>Contract bijgewerkt</b><br />',
                                                        message: 'De contract gegevens zijn bijgewerkt'
                                                    }, {
                                                        // settings
                                                        type: 'success',
                                                        delay: 5000,
                                                        timer: 10,
                                                        placement: {
                                                            from: "bottom",
                                                            align: "right"
                                                        }
                                                    });
                                                }
                                            );
                                        }
                                        else
                                        {
                                            $("#errorMessageContent").text(data);
                                            $("#errorMessage").modal("show");
                                        }
                                    }
                                );
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

                function calculateNext(nextNext = 0, funct)
                {
                    var start = new Date($("#startDate").val().split("-")[1] + "-" + $("#startDate").val().split("-")[0] + "-01");
                    var period = $("#paymentPeroid").children("option:selected").val();
                    var day = parseInt($("#paymentDate").children("option:selected").val());
                    var sendNow = $('#sendOrderImmediatly').is(":checked");

                    $.get(
                        "contract/contractGetNext.php",
                        { 
                            period: period,
                            day: day,
                            start: start.getFullYear()+"-"+("0"+(start.getMonth()+1)).slice(-2)+"-"+("0"+(start.getDate())).slice(-2),
                            nextTime: nextNext,
                            sendNow: sendNow ? 1 : 0
                        },
                        function (data)
                        {
                            funct(new Date(data.split('-')[0].replace("\n", ""), data.split('-')[1], data.split('-')[2]));
                        }
                    );
                }
            </script>
        </div>

		
        <div class="modal fade" id="deleteConfirm">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title">Contract Verwijderen</h4>
                    </div>
                    <div class="modal-body">
                        <p>
                            Weet u zeker dat u dit contract wilt verwijderen?<br />
                            <b>Dit process kan niet worden omgedraaid.</b>
                        </p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" id="applyDeletionBtn">Verwijderen</button>
                        <button type="button" class="btn btn-primary" id="cancelDeletionBtn" data-dismiss="modal">Sluiten</button>
                        <script>
					        $(document).ready(function ()
					        {
					            $("#applyDeletionBtn").on("click", function ()
					            {
									$.get(
										"contract/contractDelete.php",
										{
											id: "<?php echo $_GET['id']; ?>"
										},
										function(data)
										{
											if (data == 'OK')
											{
												$("#deleteConfirm").modal('hide');

												$("#pageLoaderIndicator").fadeIn();
												$("#PageContent").load("contract.php", function () {
													$("#pageLoaderIndicator").fadeOut();
												});

												$(".in.fade.modal-backdrop").remove();
												$("body").css("overflow", "auto");

												$.notify({
													icon: 'glyphicon glyphicon-trash',
													title: '<b>Klant verwijderd uit het systeem</b><br / >',
													message: ''
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
												$("#errorMessageContent").text(data);
												$("#errorMessage").modal("show");
											}
										}
									);
						        });
					        });
                        </script>
                    </div>
                </div>
            </div>
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
				<h4 class="modal-title">Contract bijgewerkt</h4>
			  </div>
			  <div class="modal-body">
				<p>Contract gegevens zijn bijgewerkt in het systeem.</p>
			  </div>
			  <div class="modal-footer">
				<button type="button" class="btn btn-secondary" id="closeOkBtn" data-dismiss="modal">Sluiten</button>
				<input type="hidden" id="contractId" name="contractId" value="">
				<script>
					$(document).ready(function ()
					{
						$("#closeOkBtn").on("click", function () {
						    $("#PageContent").load('contract/viewContract.php?id=<?=$_GET['id']?>');
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
}
?>
