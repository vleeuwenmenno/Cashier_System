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

	$sql = "SELECT * FROM customers WHERE customerId='" . $_GET['id'] . "'";

	if(!$result = $db->query($sql))
	{
		die('There was an error running the query [' . $db->error . ']');
	}

	while($row = $result->fetch_assoc())
	{
?>
		<div id="customerForm">
			<h2>Klant gegevens</h2>
			<div class="form-group">
				<label for="initials">Voorletters: </label>
				<input type="text" class="form-control" id="initials" placeholder="A." value="<?php echo $row["initials"]; ?>" readonly>
			</div>
			<div class="form-group">
				<label for="familyname">Achternaam: </label>
				<input type="text" class="form-control" id="familyname" placeholder="Bakker" value="<?php echo $row["familyName"]; ?>" readonly>
			</div>
					<div class="form-group">
				<label for="companyname">Bedrijfsnaam: </label>
				<input type="text" class="form-control" id="companyname" placeholder="Com Today..." value="<?php echo $row["companyName"]; ?>" readonly>
			</div>
			<div class="form-group">
				<label for="street">Straat: </label>
				<input type="text" class="form-control" id="street" placeholder="Kerkstraat" value="<?php echo $row["streetName"]; ?>" readonly>
			</div>
			<div class="form-group">
				<label for="city">Woonplaats: </label>
				<input type="text" class="form-control" id="city" placeholder="Heemskerk" value="<?php echo $row["city"]; ?>" readonly>
			</div>
            <div class="form-group">
                <label for="postalCode">Postcode: </label>
                <input type="text" class="form-control" id="postalCode" placeholder="0123 AB" value="<?php echo $row["postalCode"]; ?>" readonly />
            </div>
			<div class="form-group">
				<label for="phonehome">Telefoon: </label>
				<input type="text" class="form-control" id="phonehome" placeholder="0251 200 627" value="<?php echo $row["phoneNumber"]; ?>" readonly>
			</div>
			<div class="form-group">
				<label for="phonemobile">Mobiel: </label>
				<input type="text" class="form-control" id="phonemobile" placeholder="06 12 34 56 78" value="<?php echo $row["mobileNumber"]; ?>" readonly>
			</div>
			<div class="form-group">
				<label for="email">Email: </label>
				<input type="text" class="form-control" id="email" placeholder="abakker@voorbeeld.nl" value="<?php echo $row["email"]; ?>" readonly>
			</div>

            <input type="hidden" name="customerIdInput" id="customerIdInput" value="<?php echo $_GET['id'];?>" />

            <button type="button" id="applyBtn" class="btn btn-primary" style="display: none;">Wijzigingen Opslaan</button>
            <button type="button" id="cancelEditBtn" class="btn btn-secondary" style="display: none;">Wijzigingen Annuleren</button>
            <button type="button" id="deleteBtn" class="btn btn-danger">Klant verwijderen</button>
			<button type="button" id="changeBtn" class="btn btn-secondary">Wijzig Gegevens</button>
			<script>
				$(document).ready(function ()
				{
				    $("#cancelEditBtn").on("click", function ()
				    {
				        $("#PageContent").load('customer/viewCustomer.php?id=' + $("#customerIdInput").val());
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

				        $("#initials").attr("readonly", false);
				        $("#familyname").attr("readonly", false);
				        $("#companyname").attr("readonly", false);
				        $("#street").attr("readonly", false);
				        $("#city").attr("readonly", false);
				        $("#phonehome").attr("readonly", false);
				        $("#phonemobile").attr("readonly", false);
				        $("#email").attr("readonly", false);
				        $("#postalCode").attr("readonly", false);
				    });

					$("#applyBtn").on("click", function ()
					{
					    $("#customerForm").fadeOut("fast", function () {
					        $("#loaderAnimation").fadeIn();
					    });

                		$.get(
                            "customer/customerUpdate.php",
                            {
                                id: "<?php echo $_GET['id']; ?>",
                                initials: $("#initials").val(),
                            	famName: $("#familyname").val(),
                            	comName: $("#companyname").val(),
                            	street: $("#street").val(),
                            	city: $("#city").val(),
                            	pHome: $("#phonehome").val(),
                            	pMobile: $("#phonemobile").val(),
                            	email: $("#email").val(),
                            	postalCode: $("#postalCode").val()
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

							        $("#initials").attr("readonly", true);
							        $("#familyname").attr("readonly", true);
							        $("#companyname").attr("readonly", true);
							        $("#street").attr("readonly", true);
							        $("#city").attr("readonly", true);
							        $("#phonehome").attr("readonly", true);
							        $("#phonemobile").attr("readonly", true);
							        $("#email").attr("readonly", true);
							        $("#postalCode").attr("readonly", true);
							    }
							    else
							    {
							        $("#errorMessageContent").text(data);
							        $("#errorMessage").modal("show");
							    }
							}
                        );
					});

					document.getElementById('phonemobile').addEventListener('input', function (e) {
						e.target.value = e.target.value.replace(/[^\dA-Z]/g, '').replace(/(.{2})/g, '$1 ').trim();
					});

					document.getElementById('phonehome').addEventListener('input', function (e) {
						e.target.value = e.target.value.replace(/(\d\d\d\d)(\d\d\d)(\d\d\d)/, "$1-$2-$3").trim();
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
                        <h4 class="modal-title">Klant Verwijderen</h4>
                    </div>
                    <div class="modal-body">
                        <p>
                            Weet u zeker dat u deze klant wilt verwijderen?<br />
                            <b>Dit process kan niet worden omgedraaid.</b>
                            Om deze klant te verwijderen typ de volledige naam in de balk hieronder en druk dan op 'Verwijderen'.
                            <br /><br />
                            <font style="background-color: darkkhaki; color: darkred;">
                                <?php echo '  ' . $row['initials'] . ' ' . $row['familyName'] . '  ';?>
                            </font>
                        </p>
                        <input type="text" class="form-control" id="confirmBox" placeholder="<?php echo $row['initials'] . ' ' . $row['familyName'];?>" />
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" id="applyDeletionBtn">Verwijderen</button>
                        <button type="button" class="btn btn-primary" id="cancelDeletionBtn" data-dismiss="modal">Sluiten</button>
                        <script>
					        $(document).ready(function ()
					        {
					            $("#applyDeletionBtn").on("click", function ()
					            {
					                if ($("#confirmBox").val() == "<?php echo $row['initials'] . ' ' . $row['familyName'];?>")
                                    {
                            	        $.get(
                                            "customer/customerDelete.php",
                                            {
                                                id: "<?php echo $_GET['id']; ?>"
                                            },
							                function(data)
							                {
							                    if (data.indexOf('\nOK ') == 0)
							                    {
							                        $("#deleteConfirm").modal('hide');

							                        $("#pageLoaderIndicator").fadeIn();
							                        $("#PageContent").load("customer.php", function () {
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
				<h4 class="modal-title">Klant wijziging</h4>
			  </div>
			  <div class="modal-body">
				<p>De klant gegevens zijn succesvol gewijzigd</p>
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
			<center>Klant wijziging wordt verwerkt in het systeem...</center>
		</div>
	<?php
	}
}
?>
