<?php
include_once("../includes.php");

 ?>
<br />
<div id="exTab2" class="container">
	<ul class="nav nav-tabs">
			<li class="active">
        		<a  href="#1" data-toggle="tab">Gebruikers Beheer</a>
			</li>
			<li>
				<a href="#2" data-toggle="tab">Balans Beheer</a>
			</li>
			<li>
				<a href="#3" data-toggle="tab">Database Beheer</a>
			</li>
		</ul>

			<div class="tab-content ">
				<div class="tab-pane active" id="1">
					<div id="userManagement">
						<h2>Gebruikers Beheer</h2>
					    <br />
					    <div class="form-group">
							<table class="table">
					            <thead>
					                <tr class="filters">
										<th id="displayNameBox" width="20%">
					                        <a href="#" class="mustFocus">
					                            Weergavenaam
					                        </a>
					                    </th>
					                    <th id="userNameBox" width="50%">
					                        <a href="#" class="mustFocus">
					                            Gebruikersnaam
					                        </a>
					                    </th>
					                    <th id="changePwBox" width="10%">
					                        <a href="#" class="mustFocus">
					                            Wachtwoord
					                        </a>
					                    </th>
					                    <th width="20%">
					                        <a href="#" class="mustFocus">
					                            Opties
					                        </a>
					                    </th>
					                </tr>
					            </thead>

					            <tbody id="userListContents">
									<?php
									$sql = "SELECT nickName, username, userTheme, managementUser FROM `users` WHERE 1;";

									$db = new mysqli($config['SQL_HOST'], $config['SQL_USER'], $config['SQL_PASS'], $config['SQL_DB']);

									if($db->connect_errno > 0)
									{
										die('Unable to connect to database [' . $db->connect_error . ']');
									}

									if(!$result = $db->query($sql))
									{
										die('Er was een fout tijdens het uitvoeren van deze query (' . $db->error . ') (' . $sql . ')');
									}

									$i = 0;
									while($row = $result->fetch_assoc())
									{
									?>
									<tr>
										<td>
											<a href="#" style="color: black;" id="displayNameLabel<?php echo $i; ?>"><?php echo $row['nickName']; ?></a>
											<input class="form-control" id="displayNameText<?php echo $i; ?>" style="float: left; display: none; width: 60%;" value="<?php echo $row['nickName']; ?>">
											<input type="button" class="btn btn-primary" style="float: left; display: none; width: 20%;" id="applyDisplayName<?php echo $i; ?>" value="Wijzigen" />
											<input type="button" class="btn btn-default" style="display: none; float: left; width: 20%" id="changeDNCancel<?php echo $i; ?>" value="Annuleren" />
										</td>
										<td>
											<a href="#" style="color: black;" id="userNameLabel<?php echo $i; ?>"><?php echo $row['username']; ?></a>
											<input class="form-control" id="userNameText<?php echo $i; ?>" style="float: left; display: none; width: 60%;" value="<?php echo $row['username']; ?>">
											<input type="button" class="btn btn-primary" style="float: left; display: none; width: 20%;" id="applyUserName<?php echo $i; ?>" value="Wijzigen" />
											<input type="button" class="btn btn-default" style="display: none; float: left; width: 20%" id="changeUNCancel<?php echo $i; ?>" value="Annuleren" />
										</td>
										<td>
											<input class="form-control" type="password" id="changePwTextbox<?php echo $i; ?>" style="float: left; display: none; width: 65%;">
											<input type="button" class="btn btn-primary" style="float: left;" id="changePw<?php echo $i; ?>" value="Wijzigen" />
											<input type="button" class="btn btn-default" style="display: none; float: left; width: 20%" id="changePwCancel<?php echo $i; ?>" value="Annuleren" />
										</td>
										<td>
    				                        <div style="float: left;">
                                                Is Beheerder: <input id="isAdmin<?php echo $i; ?>" type="checkbox" <?php if ($row['managementUser'] == 1) echo 'checked'; ?>>
    											<br />
    											Thema:
    											<select class="combobox">
    												<?php
    												$folders = scandir("../themes");

    												foreach ($folders as $key => $val)
    												{
    													if ($val != "." && $val != ".." && $val != "fonts")
    													{
    														$s = "";

    														if ($val == $row['userTheme'])
    															$s = "selected";

    														if ($val == "Default")
    														{
    															echo '<option value="' . $val . '"'. $s . '>Bootstrap</option>';
    														}
    														else
                                                                echo '<option value="' . $val . '"' . $s . '>' . $val . '</option>';
    													}
    												}
    												?>
    											</select>
                                             </div>
                                             <div style="float: left; padding-left: 12px;">
                                                 <?php if ($i > 0) { ?>
                                                 <button id="deleteUser<?php echo $i; ?>" type="button" class="btn btn-warning"><span class="glyphicon glyphicon-trash"></span></button>
                                                 <?php } ?>
                                             </div>
										</td>
									</td>

									<script>
										$(document).ready(function() {
											$("#changePw<?php echo $i; ?>").click(function() {
												$("#userNameBox").prop("width", "10%");
												$("#changePwBox").prop("width", "50%");

												$("#changePw<?php echo $i; ?>").css("width", "15%");
												$("#changePwTextbox<?php echo $i; ?>").css("display", "");
												$("#changePwCancel<?php echo $i; ?>").css("display", "");
											});

											$("#changePwCancel<?php echo $i; ?>").click(function() {
												$("#userNameBox").prop("width", "50%");
												$("#changePwBox").prop("width", "10%");

												$("#changePw<?php echo $i; ?>").css("width", "");
												$("#changePwTextbox<?php echo $i; ?>").css("display", "none");
												$("#changePwCancel<?php echo $i; ?>").css("display", "none");
											});

											$("#displayNameLabel<?php echo $i; ?>").click(function() {
												$("#userNameBox").prop("width", "20%");
												$("#displayNameBox").prop("width", "40%");

												$("#displayNameLabel<?php echo $i; ?>").css("display", "none");
												$("#displayNameText<?php echo $i; ?>").css("display", "");
												$("#applyDisplayName<?php echo $i; ?>").css("display", "");
												$("#changeDNCancel<?php echo $i; ?>").css("display", "");
											});

											$("#userNameLabel<?php echo $i; ?>").click(function() {
												$("#userNameLabel<?php echo $i; ?>").css("display", "none");
												$("#userNameText<?php echo $i; ?>").css("display", "");
												$("#applyUserName<?php echo $i; ?>").css("display", "");
												$("#changeUNCancel<?php echo $i; ?>").css("display", "");
											});

											$("#changeDNCancel<?php echo $i; ?>").click(function() {
												$("#userNameBox").prop("width", "50%");
												$("#displayNameBox").prop("width", "20%");

												$("#displayNameLabel<?php echo $i; ?>").css("display", "");
												$("#displayNameText<?php echo $i; ?>").css("display", "none");
												$("#applyDisplayName<?php echo $i; ?>").css("display", "none");
												$("#changeDNCancel<?php echo $i; ?>").css("display", "none");
											});

											$("#changeUNCancel<?php echo $i; ?>").click(function() {
												$("#userNameLabel<?php echo $i; ?>").css("display", "");
												$("#userNameText<?php echo $i; ?>").css("display", "none");
												$("#applyUserName<?php echo $i; ?>").css("display", "none");
												$("#changeUNCancel<?php echo $i; ?>").css("display", "none");
											});
										});
									</script>
									<?php
									$i++;
									}
									?>
					            </tbody>
					        </table>

							<table>
								<tbody id="newUserBox" style="display: none;">
									<tr>
										<td>
											<input class="form-control" id="displayNameText" style="float: left;" placeholder="Weergavenaam...">
										</td>
										<td>
											<input class="form-control" id="userNameText" style="float: left;" placeholder="Gebruikersnaam...">
										</td>
										<td>
											<input class="form-control" type="password" id="passwordText" style="float: left;" placeholder="Wachtwoord...">
										</td>
										<td>
											Is Beheerder: <input id="isAdmin" type="checkbox">
											<br />
											Thema:
											<select id="userThemeCombo" class="combobox">
												<?php
												$folders = scandir("../themes");

												foreach ($folders as $key => $val)
												{
													if ($val != "." && $val != ".." && $val != "fonts")
													{
														if ($val == "Default")
														{
															echo '<option value="' . $val . '">Bootstrap</option>';
														}
                                                        else if ($val == "Yeti")
                                                            echo '<option value="' . $val . '"' . $s . ' selected>' . $val . '</option>';
														else
															echo '<option value="' . $val . '">' . $val . '</option>';
													}
												}
												?>
											</select>
										</td>
									</td>
								</tbody>
							</table>
							<div class="form-group">
								<input type="button" class="btn btn-default" id="addUser" value="Nieuwe gebruiker" />
								<input type="button" class="btn btn-Primary" style="display: none;" id="addUserApply" value="Gebruiker toevoegen" />
								<input type="button" class="btn btn-default" style="display: none;" id="addUserCancel" value="Annuleren" />

								<script>
									$(document).ready(function() {
										$("#addUser").click(function() {
											$("#userListContents").html($("#userListContents").html() + $("#newUserBox").html())

											$("#addUser").css("display", "none");

											$("#addUserCancel").css("display", "");
											$("#addUserApply").css("display", "");
										});

										$("#addUserCancel").click(function() {
											deleterow("userListContents");

											$("#addUser").css("display", "");

											$("#addUserCancel").css("display", "none");
											$("#addUserApply").css("display", "none");
										});

										$("#addUserApply").click(function() {
                                            $.get(
                                                "management/addUser.php",
                                                {
                                                    nickname: $("#displayNameText").val(),
                                                    username: $("#userNameText").val(),
                                                    pass: $("#passwordText").val(),
                                                    managementUser: $("#isAdmin").is(":checked"),
                                                    userTheme: $('#userThemeCombo option:selected').val()
                                                },
                                                function (data)
                                                {
                                                    if (data.replace(/(\r\n|\n|\r)/gm,"") == "OK")
                                                    {
                                                        $.notify({
                                                            icon: 'glyphicon glyphicon-ok',
                                                            title: 'Gebruiker toegevoegt',
                                                            message: '<br / >De gebruiker is succesvol toegevoegt'
                                                        },{
                                                            // settings
                                                            type: 'success',
                                                            placement: {
                                                                from: "bottom",
                                                                align: "right"
                                                            }
                                                        });

                                                        $("#pageLoaderIndicator").fadeIn();
                                                        $("#PageContent").load("item/itemImport.php", function () {
                                                            $("#pageLoaderIndicator").fadeOut();
                                                        });
                                                    }
                                                    else
                                                    {
                                                        $.notify({
                                                            icon: 'glyphicon glyphicon-warning-sign',
                                                            title: 'Fout',
                                                            message: '<br / >' + data
                                                        },{
                                                            // settings
                                                            type: 'danger',
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

									function deleterow(tableID) {
									    var table = document.getElementById(tableID);
									    var rowCount = table.rows.length;

									    table.deleteRow(rowCount -1);

									}
								</script>
							</div>
						</div>
					</div>
				</div>
				<div class="tab-pane" id="2">
					<div id="balanceManagement">
						<h2>Balans Beheer</h2>
					    <br />
					    <div class="form-group">
							Balans rapport printen:<br />
							<input type="button" class="btn btn-default" id="printReport" value="Print minimaal" />
							<input type="button" class="btn btn-primary" id="printFullReport" value="Print volledig rapport" />
						</div>
					</div>
				</div>
				<div class="tab-pane" id="3">
					<div id="managementForm">
					    <h2>Database Beheer</h2>
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
