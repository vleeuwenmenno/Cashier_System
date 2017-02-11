<div id="balanceManagement">
	<h2>Balans Beheer</h2>
    <br />
    <br />
    <div class="form-group">
		Balans rapport printen:<br />
		<input type="button" class="btn btn-default" id="printReport" value="Print minimaal" />
		<input type="button" class="btn btn-primary" id="printFullReport" value="Print volledig rapport" />
	</div>
</div>
<br />
<br />
<div id="managementForm">
    <h2>Database Beheer</h2>
    <b>Opgelet!</b> Het importeren van XML bestanden meer dan een keer kan duplicaten opleveren.
    <br />
    <br />
    <div class="form-group">
        Gistron XML Aanwezig: <?php if (file_exists(dirname(__FILE__) . '/../import/gistron.xml')) { echo 'Ja'; } else { echo 'Nee'; } ?>
        <br />
        <input type="button" class="btn btn-warning" id="importGistron" value="Gistron Importeren" />

        <script>
			var startTime = Math.floor(Date.now() / 1000);
			var intervalObject;

            $(document).ready(function () {
                $("#importGistron").click(function () {
					startTime = Math.floor(Date.now() / 1000);
					intervalObject = setInterval(updateText, 1000);

                    $("#loaderAnimation").fadeIn();
                    $("#managementForm").fadeOut();
					$("#balanceManagement").fadeOut();
                    $("#PageContent").load("item/itemManage.php?import=gistron", function () {
                        $("#loaderAnimation").fadeOut();
                    });
                });
            });
        </script>
    </div>
    <div class="form-group">
        Copaco XML Aanwezig: <?php if (file_exists(dirname(__FILE__) . '/../import/copaco.xml')) { echo 'Ja'; } else { echo 'Nee'; } ?>
        <br />
        <input type="button" class="btn btn-warning" id="importCopaco" value="Copaco Importeren" />

        <script>
            $(document).ready(function () {
                $("#importCopaco").click(function () {
					startTime = Math.floor(Date.now() / 1000);
					intervalObject = setInterval(updateText, 1000);

                    $("#loaderAnimation").fadeIn();
                    $("#managementForm").fadeOut();
					$("#balanceManagement").fadeOut();
                    $("#PageContent").load("item/itemManage.php?import=copaco", function () {
                        $("#loaderAnimation").fadeOut();
                    });
                });
            });
        </script>
    </div>
    <div class="form-group">
        United Supplies XML Aanwezig: <?php if (file_exists(dirname(__FILE__) . '/../import/unitedsupplies.xml')) { echo 'Ja'; } else { echo 'Nee'; } ?>
        <br />
        <input type="button" class="btn btn-warning" id="importUSupplies" value="United Supplies Importeren" disabled/ />

        <script>
            $(document).ready(function () {
                $("#importUSupplies").click(function () {
					startTime = Math.floor(Date.now() / 1000);
					intervalObject = setInterval(updateText, 1000);

                    $("#loaderAnimation").fadeIn();
                    $("#managementForm").fadeOut();
					$("#balanceManagement").fadeOut();
                    $("#PageContent").load("item/itemManage.php?import=unitedsupplies", function () {
                        $("#loaderAnimation").fadeOut();
                    });
                });
            });
        </script>
    </div>
	<div class="form-group">
        Database naar standaard waarde resetten.
        <br />
        <input type="button" data-toggle="modal" data-target=".bd-example-modal-lg" class="btn btn-danger" id="databaseWipe" value="Database Opties" />
		<div class="modal fade bd-example-modal-lg" id="databaseWipeModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<div class="row">
						<div class="col-md-12">
							<h2>Database reset opties</h2>
							<div class="checkbox">
								<label><input type="checkbox" id="deleteAll" value="">Alle artikelen uit database verwijderen. (DELETE FROM `items` WHERE 1)</label>
							</div>
							<div class="checkbox">
								<label><input type="checkbox" id="resetAll" value="">Voorraad naar NUL zetten voor alle artikelen. (UPDATE items SET itemStock=0;)</label>
							</div>
							<div class="checkbox">
								<label><input type="checkbox" id="deleteAllReceipts" value="">Alle opgeslagen/verwerkte bonnen verwijderen. (DELETE FROM `receipt` WHERE 1)</label>
							</div>
							<div class="checkbox">
								<label><input type="checkbox" id="deleteAllSessions" value="">Alle dag overzichten verwijderen. (DELETE FROM `cashsession` WHERE 1)</label>
							</div>
							<br />
							<div class="checkbox">
								<label><input type="checkbox" id="continueCheck" value="">Doorgaan?</label>
							</div>
							<input type="button" class="btn btn-danger btn-xl" id="executeBtw" value="Uitvoeren" disabled/>
							<br />	<br />
						</div>
					</div>
					<script>
						$( document ).ready(function() {
							$("#deleteAll").change(function()
							{
								if(this.checked)
								{
									$("#resetAll").prop("checked", true);
									$("#resetAll").prop("disabled", true);
								}
								else
								{
									$("#resetAll").prop("disabled", false);
								}
							});

							$("#continueCheck").change(function()
							{
								if(this.checked)
								{
									$("#executeBtw").prop("disabled", false);
								}
								else
								{
									$("#executeBtw").prop("disabled", true);
								}
							});

							$("#executeBtw").on("click", function()
							{
								$.get(
						            "item/dbWipe.php",
						            {
						                deleteAll: $("#deleteAll").is(":checked"),
										resetAll: $("#resetAll").is(":checked"),
										deleteAllReceipts: $("#deleteAllReceipts").is(":checked"),
										deleteAllSessions: $("#deleteAllSessions").is(":checked")
						            },
						            function (data)
						            {
										var test = "";
						                if ($("#deleteAll").is(":checked"))
											test += "1"
										else
											test += "0";

										if ($("#resetAll").is(":checked"))
											test += "1"
										else
											test += "0";

										if ($("#deleteAllReceipts").is(":checked"))
											test += "1"
										else
											test += "0";

										if ($("#deleteAllSessions").is(":checked"))
											test += "1"
										else
											test += "0";

										if (test == data.replace(/(\r\n|\n|\r)/gm,""))
										{
											$("#databaseWipeModal").modal("hide");
											$.notify({
			                                    icon: 'glyphicon glyphicon-ok',
			                                    title: 'Database wipe',
			                                    message: '<br / >Database veranderingen succesvol verwerkt.'
		                                    },{
			                                    // settings
			                                    type: 'success',
		                                        placement: {
				                                    from: "bottom",
				                                    align: "right"
			                                    }
		                                    });
										}
										else
										{
											alert("ERROR! Expected: " + test + " executed: " + data);
										}
						            }
						        );
							});
						});
					</script>
				</div>
			</div>
		</div>

        <script>
            $(document).ready(function () {
                $("#databaseWipe").click(function () {

                });
            });
        </script>
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
    <center>
        Bezig met importeren van producten...
        <br />(Dit kan enige tijd duren)<br />

		Verstreken tijd: <label id="minutes">00</label>:<label id="seconds">00</label>
	    <script type="text/javascript">
			function updateText()
			{
				var currentTime = Math.floor(Date.now() / 1000);
				var totalElapsedTimeSeconds = Math.abs(startTime - currentTime);
				var elapsedTimeSeconds = Math.floor(totalElapsedTimeSeconds%60);
				var elapsedTimeMinutes = Math.floor(totalElapsedTimeSeconds/60);

				$("#minutes").html(pad(elapsedTimeMinutes, 2));
				$("#seconds").html(pad(elapsedTimeSeconds, 2));
			}

			function pad(num, size) {
			    var s = num+"";
			    while (s.length < size) s = "0" + s;
			    return s;
			}
	    </script>
    </center>
</div>
