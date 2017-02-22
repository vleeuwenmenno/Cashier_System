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

	$sql = "SELECT * FROM items WHERE nativeId='" . $_GET['id'] . "' OR EAN='" . $_GET['id'] . "';";

	if(!$result = $db->query($sql))
	{
		die('There was an error running the query [' . $db->error . ']');
	}

	while($row = $result->fetch_assoc())
	{
?>
<div id="customerForm">
    <h2>Artikel</h2>
    <div class="form-group">
        <label for="initials">Artikel Nummer: </label>
        <input type="text" class="form-control" id="itemId" placeholder="00000" value="<?php echo $row["itemId"]; ?>" readonly/ />
    </div>
    <div class="form-group">
        <label for="familyname">EAN: </label>
        <input type="text" class="form-control" id="EAN" placeholder="0884962825884" value="<?php echo $row["EAN"]; ?>" readonly/ />
    </div>
    <div class="form-group">
        <label for="companyname">Leverancier: </label>
        <input type="text" class="form-control" id="supplier" placeholder="Com Today" value="<?php echo $row["supplier"]; ?>" readonly/ />
    </div>
    <div class="form-group">
        <label for="street">Fabrieks artikel nummer: </label>
        <input type="text" class="form-control" id="factoryId" placeholder="C6615DE" value="<?php echo $row["factoryId"]; ?>" readonly/ />
    </div>
    <div class="form-group">
        <label for="city">Artikel naam: </label>
        <input type="text" class="form-control" id="itemName" placeholder="HP No. 15 Zwart 25ml (Origineel)" value="<?php echo urldecode($row["itemName"]); ?>" readonly/ />
    </div>
    <div class="form-group">
        <label for="postalCode">Artikel categorie: </label>
        <input type="text" class="form-control" id="itemCategory" placeholder="Inkt origineel" value="<?php echo $row["itemCategory"]; ?>" readonly/ />
    </div>
	<label for="priceModifier">Voorraad: </label>
    <div class="input-group">
		<?php
		$stock = $row["itemStock"];
		if ($row["itemStock"] == 2147483647)
			$stock = "&infin;";
		?>
        <input type="text" class="form-control" id="itemStock" placeholder="0-1000000 of &infin;" value="<?php echo $stock; ?>" readonly/ />
		<span class="input-group-addon" id="makeInfinite">
			<input type="checkbox" id="stockInfiniteCheck" <?php if ($row["itemStock"] == 2147483647) echo "checked"; ?> disabled readonly /><span style="display: inline; font-size: 20px;">&nbsp;&infin; </span>
		</span>
    </div>
	<br />
	<div class="form-group">
		<label for="priceExclVat">Inkoop prijs: </label>
		<input type="text" class="form-control" id="priceExclVat" placeholder="26,66" value="<?php echo round($row["priceExclVat"], 2); ?>" readonly />
	</div>
	<label for="priceModifier">Prijs berekening: </label>
	<div class="input-group">
		<span class="input-group-addon" id="priceExclVatLabel" style="min-width: 96px; border-bottom-left-radius: 0px !important;">
			Inkoop<br />
			&euro;&nbsp;
		</span>
		<span class="input-group-addon" id="priceVatOnly" style="border-bottom-right-radius: 0px !important;">
			Btw<br />
			&nbsp;&euro;&nbsp;
		</span>
		<span class="input-group-addon" id="priceMarginOnly" style="border-bottom-right-radius: 0px !important;">
			Marge<br />
			&nbsp;&euro;&nbsp;
		</span>
		<span class="input-group-addon" id="priceResell" style="border-bottom-right-radius: 0px !important;">
			Verkoop<br />
			&nbsp;&euro;&nbsp;
		</span>
	</div>
	<div class="input-group">
		<span class="input-group-addon" id="" style="border-top-left-radius: 0px !important;">
			($INKOOP * $BTW)<br />
		</span>
		<input type="text" style="height: 42px; border-top-right-radius: 0px !important;" class="form-control" id="priceModifier" aria-describedby="priceModifierLabel" placeholder=" * 1.375" value="<?php echo str_replace(".", ",", $row["priceModifier"]); ?>" readonly />
	</div>
	<br />

    <input type="hidden" name="customerIdInput" id="customerIdInput" value="<?php echo $_GET['id'];?>" />

    <button type="button" id="applyBtn" class="btn btn-primary" style="display: none;">Wijzigingen Opslaan</button>
    <button type="button" id="cancelEditBtn" class="btn btn-secondary" style="display: none;">Wijzigingen Annuleren</button>
    <button type="button" id="deleteBtn" class="btn btn-danger">Artikel verwijderen</button>
    <button type="button" id="changeBtn" class="btn btn-secondary">Wijzig Gegevens</button>
    <script>
				$(document).ready(function ()
				{
					//~~~~~~~~~~~~~~~ LOAD EVERYTHING ONCE FOR THE PAGE ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
					var vat = "<?php echo $_CFG['VAT']; ?>";

					//Set price excl vat label
					$("#priceExclVatLabel").html("Inkoop<br />&euro;&nbsp;" + $('#priceExclVat').val().replace(".", ","));

					//Set vat price
					$.get(
						"item/calcString.php",
						{
							sum: encodeURIComponent($('#priceExclVat').val().replace(",", ".") + " * " + vat)
						},
						function (data)
						{
							$('#priceVatOnly').html("Btw<br />&nbsp;&euro;&nbsp;" + parseFloat(data.replace(",", ".") - $('#priceExclVat').val().replace(",", ".")).toFixed(2).replace(".", ","));
						}
					);

					//Set resell price and margin only price
					$.get(
						"item/calcString.php",
						{
							sum: encodeURIComponent("(" +  $('#priceExclVat').val().replace(",", ".") + " * " + vat  + ") " + $("#priceModifier").val())
						},
						function (data)
						{
							$("#priceResell").html("Verkoop<br />&nbsp;&euro;&nbsp;" + data);

							$.get(
								"item/calcString.php",
								{
									sum: encodeURIComponent(data + " - " + "(" +  $('#priceExclVat').val().replace(",", ".") + " * " + vat  + ")")
								},
								function (dataTwo)
								{
									$("#priceMarginOnly").html("Marge<br />&nbsp;&euro;&nbsp;" + dataTwo);
								}
							);
						}
					);
					//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ END OF LOAD

					$('#priceModifier').on('input', function ()
					{
						var vat = "<?php echo $_CFG['VAT']; ?>";

						$.get(
							"item/calcString.php",
							{
								sum: encodeURIComponent("(" +  $('#priceExclVat').val().replace(",", ".") + " * " + vat  + ") " + $("#priceModifier").val())
							},
							function (data)
							{
								$("#priceResell").html("Verkoop<br />&nbsp;&euro;&nbsp;" + data);

								$.get(
									"item/calcString.php",
									{
										sum: encodeURIComponent(data + " - " + "(" +  $('#priceExclVat').val().replace(",", ".") + " * " + vat  + ")")
									},
									function (dataTwo)
									{
										$("#priceMarginOnly").html("Marge<br />&nbsp;&euro;&nbsp;" + dataTwo);
									}
								);
							}
						);
					});

					$('#priceExclVat').on('input', function ()
					{
						var vat = "<?php echo $_CFG['VAT']; ?>";

						//Set price excl vat label
						$("#priceExclVatLabel").html("Inkoop<br />&euro;&nbsp;" + $('#priceExclVat').val().replace(".", ","));

						//Set vat price
						$.get(
							"item/calcString.php",
							{
								sum: encodeURIComponent($('#priceExclVat').val().replace(",", ".") + " * " + vat)
							},
							function (data)
							{
								$('#priceVatOnly').html("Btw<br />&nbsp;&euro;&nbsp;" + parseFloat(data.replace(",", ".") - $('#priceExclVat').val().replace(",", ".")).toFixed(2).replace(".", ","));
							}
						);

						//Set resell price and margin only price
						$.get(
							"item/calcString.php",
							{
								sum: encodeURIComponent("(" +  $('#priceExclVat').val().replace(",", ".") + " * " + vat  + ") " + $("#priceModifier").val())
							},
							function (data)
							{
								$("#priceResell").html("Verkoop<br />&nbsp;&euro;&nbsp;" + data);

								$.get(
									"item/calcString.php",
									{
										sum: encodeURIComponent(data + " - " + "(" +  $('#priceExclVat').val().replace(",", ".") + " * " + vat  + ")")
									},
									function (dataTwo)
									{
										$("#priceMarginOnly").html("Marge<br />&nbsp;&euro;&nbsp;" + dataTwo);
									}
								);
							}
						);
					});

					$("#itemStock").on("input", function() {
						if ($("#itemStock").val() != "∞")
							$("#stockInfiniteCheck").prop("checked", false);
						else
							$("#stockInfiniteCheck").prop("checked", true);
					});

					$("#stockInfiniteCheck").change(function() {
						if($(this).is(":checked"))
						{
							$("#itemStock").val("∞");
				        }
						else
						{
							$("#itemStock").val("0");
						}
					});

				    $("#cancelEditBtn").on("click", function ()
				    {
				        $("#PageContent").load('item/viewItem.php?id=<?php echo $_GET['id']; ?>');
				    });

				    $("#deleteBtn").on("click", function () {
				        $("#deleteConfirm").modal("show");
				    });

				    $("#changeBtn").on("click", function ()
				    {
				        $("#changeBtn").fadeOut('normal', function () {
				            $("#applyBtn").fadeIn();
				            $("#cancelEditBtn").fadeIn();
				        });

						$("#stockInfiniteCheck").attr("readonly", false);
						$("#stockInfiniteCheck").attr("disabled", false);
				        $("#itemId").attr("readonly", false);
				        $("#EAN").attr("readonly", false);
				        $("#supplier").attr("readonly", false);
				        $("#factoryId").attr("readonly", false);
				        $("#itemName").attr("readonly", false);
				        $("#itemCategory").attr("readonly", false);
				        $("#itemStock").attr("readonly", false);
				        $("#priceExclVat").attr("readonly", false);
				        $("#priceModifier").attr("readonly", false);
				    });

					$("#applyBtn").on("click", function ()
					{
					    $("#customerForm").fadeOut("fast", function () {
					        $("#loaderAnimation").fadeIn();
					    });

						if ($("#itemStock").val() == "∞")
							$("#itemStock").val("2147483647");

						$.get(
                            "item/itemUpdate.php",
                            {
                                itemId: "<?php if ($row['EAN'] != "") echo $row['EAN']; else echo $row['itemId']; ?>",
                                EAN: $("#EAN").val(),
                                supplier: $("#supplier").val(),
                                factoryId: $("#factoryId").val(),
                                itemName: $("#itemName").val(),
                                itemCategory: $("#itemCategory").val(),
                                itemStock: $("#itemStock").val(),
                                priceExclVat: $("#priceExclVat").val().replace(",", "."),
                                priceModifier: $("#priceModifier").val(),
                            },
							function(data)
							{
							    if (data.includes("OK "))
							    {
							        var arr = data.split(' ');
							        $("#customerId").val(arr[1]);
							        $("#okMessage").modal("show");

							        $("#applyBtn").fadeOut();
							        $("#cancelEditBtn").fadeOut("normal", function ()
							        {
							            $("#changeBtn").fadeIn();
							        });

									$("#stockInfiniteCheck").attr("readonly", true);
									$("#stockInfiniteCheck").attr("disabled", true);
							        $("#itemId").attr("readonly", true);
							        $("#EAN").attr("readonly", true);
							        $("#supplier").attr("readonly", true);
							        $("#factoryId").attr("readonly", true);
							        $("#itemName").attr("readonly", true);
							        $("#itemCategory").attr("readonly", true);
							        $("#itemStock").attr("readonly", true);
							        $("#priceExclVat").attr("readonly", true);
							        $("#priceModifier").attr("readonly", true);

									if ($("#itemStock").val() == "2147483647")
										$("#itemStock").val("∞");
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

<div class="modal fade" id="deleteConfirm">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">Artikel Verwijderen</h4>
            </div>
            <div class="modal-body">
                <p>
                    Weet u zeker dat u dit artikel wilt verwijderen?
                    <br />
                    <b>Dit process kan niet worden omgedraaid.</b>
                    Om dit artikel te verwijderen typ de volledige artikel nummer in de balk hieronder en druk dan op 'Verwijderen'.
                    <br />
                    <br />
                    <font style="background-color: darkkhaki; color: darkred;">
                        <?php echo '  ' . $row['itemId'] . '  ';?>
                    </font>
                </p>
                <input type="text" class="form-control" id="confirmBox" placeholder="<?php echo $row['itemId'];?>" />
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" id="applyDeletionBtn">Verwijderen</button>
                <button type="button" class="btn btn-primary" id="cancelDeletionBtn" data-dismiss="modal">Sluiten</button>
                <script>
							var confirmDeleted = false;
					        $(document).ready(function ()
					        {
								$("#deleteConfirm").on("hidden.bs.modal", function () {

									if (confirmDeleted)
									{
										$("#pageLoaderIndicator").fadeIn();
									    $("#PageContent").load("item.php", function() {
									        $("#pageLoaderIndicator").fadeOut();
									    });
									}
								});

					            $("#applyDeletionBtn").on("click", function ()
					            {
					                if ($("#confirmBox").val() == "<?php echo $row['itemId'];?>")
                                    {
                            	        $.get(
                                            "item/itemDelete.php",
                                            {
                                                id: "<?php echo $_GET['id']; ?>"
                                            },
							                function(data)
							                {
												if (data.replace(/(\r\n|\n|\r)/gm,"") == "OK 0")
							                    {
													confirmDeleted = true;
							                        $("#deleteConfirm").modal('hide');
							                    }
							                    else
							                    {
							                        $("#errorMessageContent").text(data);
							                        $("#errorMessage").modal("show");
							                    }
							                }
                                        );
					                }
					                else
					                {
					                    $("#confirmBox").css("border-color", "#FF0000");
					                    $("#confirmBox").css("box-shadow", "inset 0 1px 1px rgba(0, 0, 0, 0.075), 0 0 8px rgba(255, 0, 0, 0.6)");

					                    $('#confirmBox').on('input', function (e) {
					                        $("#confirmBox").css("border-color", "#428BCA");
					                        $("#confirmBox").css("box-shadow", "inset 0 1px 1px rgba(0, 0, 0, 0.075), 0 0 8px rgba(66,139,202, 0.6)");

					                    });

					                    $("#confirmBox").val("");
                                    }
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
                <h4 class="modal-title">Artikel wijziging</h4>
            </div>
            <div class="modal-body">
                <p>Het artikel is succesvol gewijzigd</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" id="closeOkBtn" data-dismiss="modal">Sluiten</button>
                <script>
					$(document).ready(function ()
					{
						$("#closeOkBtn").on("click", function () {
							$("#customerForm").fadeIn();
							$("#loaderAnimation").fadeOut();
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
    <center>Artikel wijziging wordt verwerkt in het systeem...</center>
</div>
<?php

	}
}

$time = microtime();
$time = explode(' ', $time);
$time = $time[1] + $time[0];
$finish = $time;
$total_time = round(($finish - $start), 4);
echo 'FUUUCK Page generated in '.$total_time.' seconds.';

?>
