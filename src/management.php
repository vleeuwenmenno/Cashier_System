<?php
include_once("includes.php");
Permissions::checkSession(basename($_SERVER['REQUEST_URI']));

if (isset($_GET['applyOptions']))
{
	Misc::sqlUpdate("options", "companyName", "'".$_GET['companyName']."'", "id", 1);
	Misc::sqlUpdate("options", "vat", $_GET['vat'], "id", 1);
	Misc::sqlUpdate("options", "currency", "'".$_GET['currency']."'", "id", 1);

	Misc::sqlUpdate("options", "smtpHost", "'".$_GET['smtpHost']."'", "id", 1);
	Misc::sqlUpdate("options", "smtpPort", "'".$_GET['smtpPort']."'", "id", 1);
	Misc::sqlUpdate("options", "smtpName", "'".$_GET['smtpName']."'", "id", 1);
	Misc::sqlUpdate("options", "smtpUser", "'".$_GET['smtpUser']."'", "id", 1);
	Misc::sqlUpdate("options", "smtpPass", "'".$_GET['smtpPass']."'", "id", 1);
	Misc::sqlUpdate("options", "smtpSecure", "'".$_GET['smtpSecure']."'", "id", 1);
	die();
}
if (isset($_GET['content'])) {?>
<script>
	$(document).ready(function() {
		$.notify({
			icon: 'glyphicon glyphicon-cog',
			title: 'Instellingen zijn gewijzigt',
			message: '<br / >'
		},{
			// settings
			type: 'success',
			placement: {
				from: "bottom",
				align: "right"
			}
		});
	});
</script>
<?php } ?>
<br />
<div id="exTab2">
	<ul class="nav nav-tabs">
			<li <?php if (!isset($_GET['content'])) { echo 'class="active"'; } ?>>
        		<a  href="#1" data-toggle="tab">Gebruikers Beheer</a>
			</li>
			<li <?php if (isset($_GET['content'])) { echo 'class="active"'; } ?>>
        		<a  href="#2" data-toggle="tab">Content Beheer</a>
			</li>
			<li>
				<a href="#3" data-toggle="tab">Database Beheer</a>
			</li>
		</ul>

			<div class="tab-content ">
				<div class="tab-pane <?php if (!isset($_GET['content'])) { echo 'active'; } ?>" id="1">
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
					                    <th id="userNameBox" width="45%">
					                        <a href="#" class="mustFocus">
					                            Gebruikersnaam
					                        </a>
					                    </th>
					                    <th id="changePwBox" width="15%">
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
									$sql = "SELECT userId, nickName, username, managementUser FROM `users` WHERE 1;";

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
											<script>
												$(document).ready(function() {
													$("#applyDisplayName<?php echo $i; ?>").click(function() {
														$.get(
															"management/updateUser.php",
															{
																userId: "<?php echo $row['userId']; ?>",
																nickName: encodeURI($('#displayNameText<?php echo $i; ?>').val())
															},
															function (data)
															{
																if (data.replace(/(\r\n|\n|\r)/gm,"") == "OK")
																{
																	$.notify({
																		icon: 'glyphicon glyphicon-warning-sign',
																		title: 'Weergavenaam is gewijzigt',
																		message: '<br / >'
																	},{
																		// settings
																		type: 'success',
																		placement: {
																			from: "bottom",
																			align: "right"
																		}
																	});

																	$("#pageLoaderIndicator").fadeIn();
																	$("#PageContent").load("management.php", function () {
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
											</script>
										</td>
										<td>
											<a href="#" style="color: black;" id="userNameLabel<?php echo $i; ?>"><?php echo $row['username']; ?></a>
											<input class="form-control" id="userNameText<?php echo $i; ?>" style="float: left; display: none; width: 60%;" value="<?php echo $row['username']; ?>">
											<input type="button" class="btn btn-primary" style="float: left; display: none; width: 20%;" id="applyUserName<?php echo $i; ?>" value="Wijzigen" />
											<input type="button" class="btn btn-default" style="display: none; float: left; width: 20%" id="changeUNCancel<?php echo $i; ?>" value="Annuleren" />
											<script>
												$(document).ready(function() {
													$("#applyUserName<?php echo $i; ?>").click(function() {
														$.get(
															"management/updateUser.php",
															{
																userId: "<?php echo $row['userId']; ?>",
																username: $('#userNameText<?php echo $i; ?>').val()
															},
															function (data)
															{
																if (data.replace(/(\r\n|\n|\r)/gm,"") == "OK")
																{
																	$.notify({
																		icon: 'glyphicon glyphicon-warning-sign',
																		title: 'Gebruikersnaam is gewijzigt',
																		message: '<br / >'
																	},{
																		// settings
																		type: 'success',
																		placement: {
																			from: "bottom",
																			align: "right"
																		}
																	});

																	$("#pageLoaderIndicator").fadeIn();
																	$("#PageContent").load("management.php", function () {
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
											</script>
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
												<script>
													$(document).ready(function() {
														$("#isAdmin<?php echo $i; ?>").change(function() {
															$.get(
																"management/updateUser.php",
																{
																	userId: "<?php echo $row['userId']; ?>",
																	managementUser: this.checked
																},
																function (data)
																{
																	if (data.replace(/(\r\n|\n|\r)/gm,"") == "OK")
																	{
																		$("#pageLoaderIndicator").fadeIn();
																		$("#PageContent").load("management.php", function () {
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
												 </script>
                                             </div>
                                             <div style="float: left; padding-left: 12px;">
                                                 <?php if ($i > 0) { ?>
                                                 <button id="deleteUser<?php echo $i; ?>" type="button" class="btn btn-warning"><span class="glyphicon glyphicon-trash"></span></button>
												 <script>
													$(document).ready(function() {
														$("#deleteUser<?php echo $i; ?>").click(function() {
															$.get(
																"management/deleteUser.php",
																{
																	userId: "<?php echo $row['userId']; ?>",
																},
																function (data)
																{
																	if (data.replace(/(\r\n|\n|\r)/gm,"") == "OK")
																	{
																		$.notify({
																			icon: 'glyphicon glyphicon-ok',
																			title: 'Gebruiker is verwijderd',
																			message: '<br / >De gebruiker is succesvol verwijderd uit het systeem'
																		},{
																			// settings
																			type: 'success',
																			placement: {
																				from: "bottom",
																				align: "right"
																			}
																		});

																		$("#pageLoaderIndicator").fadeIn();
																		$("#PageContent").load("management.php", function () {
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
												 </script>
                                                 <?php } ?>
                                             </div>
										</td>
									</td>

									<script>
										$(document).ready(function() {
											$("#changePw<?php echo $i; ?>").click(function() {
												if ($("#changePwTextbox<?php echo $i; ?>").is(":visible"))
												{
													$.get(
														"management/updateUser.php",
														{
															userId: "<?php echo $row['userId']; ?>",
															pass: encodeURI($('#changePwTextbox<?php echo $i; ?>').val())
														},
														function (data)
														{
															if (data.replace(/(\r\n|\n|\r)/gm,"") == "OK")
															{
																$.notify({
																	icon: 'glyphicon glyphicon-warning-sign',
																	title: 'Wachtwoord is gewijzigt',
																	message: '<br / >'
																},{
																	// settings
																	type: 'success',
																	placement: {
																		from: "bottom",
																		align: "right"
																	}
																});

																$("#pageLoaderIndicator").fadeIn();
																$("#PageContent").load("management.php", function () {
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
												}
												
												$("#userNameBox").prop("width", "15%");
												$("#changePwBox").prop("width", "45%");

												$("#changePw<?php echo $i; ?>").css("width", "15%");
												$("#changePwTextbox<?php echo $i; ?>").css("display", "");
												$("#changePwCancel<?php echo $i; ?>").css("display", "");
											});

											$("#changePwCancel<?php echo $i; ?>").click(function() {
												$("#userNameBox").prop("width", "45%");
												$("#changePwBox").prop("width", "15%");

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
										</td>
										<script>
											$(document).ready(function() {
												$( "#userNameText" ).focusin(function() {
													$("#changePwBox").prop("width", "15%");
													$("#userNameBox").prop("width", "45%");
												});

												$( "#passwordText" ).focusin(function() {
													$("#changePwBox").prop("width", "45%");
													$("#userNameBox").prop("width", "15%");
												});

												$( "#passwordText" ).focusout(function() {
													$("#changePwBox").prop("width", "15%");
													$("#userNameBox").prop("width", "45%");
												});
											});
										</script>
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
                                                    managementUser: $("#isAdmin").is(":checked")
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
                                                        $("#PageContent").load("management.php", function () {
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
				<div class="tab-pane <?php if (isset($_GET['content'])) { echo 'active'; } ?>" id="2">
					<div id="userManagement">
					<h2>Content Beheer</h2>
					    <br />
					    <!-- <div class="form-group">
							<h3>Briefpapier header</h3>
							<p>De afbeelding voor het briefpapier moet een PNG-bestandsextensie zijn en moet <u>precies</u> 1656x368 pixels groot zijn.</p>
							<input id="input-b1" name="input-b1" type="file" class="file" data-browse-on-zone-click="true">
							<img src="/CashRegister/src/images/A4-Template.png" class="img-fluid" alt="Responsive image" style="max-width: 95%">
						</div> -->
						<div class="form-group">
							<label for="familyname">Bedrijf naam: </label>
							<input type="text" class="form-control" id="companyName" placeholder="<?=$_CFG['COMPANY_NAME']?>">
						</div>
								<div class="form-group">
							<label for="taxAmount">BTW: </label>
							<input type="text" class="form-control" id="taxAmount" placeholder="<?=number_format($_CFG['VAT'] * 100 - 100, 2)?>%">
						</div>
						<div class="form-group">
							<label for="street">Valuta: </label>
							<input type="text" class="form-control" id="currency" placeholder="<?=$_CFG['CURRENCY']?>">
						</div>
						<h2>SMTP Beheer</h2>
					    <br />
						
						<div class="form-group">
							<label for="familyname">SMTP Server: </label>
							<input type="text" class="form-control" id="smtpHost" placeholder="<?=$_CFG['smtpHost']?>">
						</div>
						<div class="form-group">
							<label for="taxAmount">SMTP Server poort: </label>
							<input type="text" class="form-control" id="smtpPort" placeholder="<?=$_CFG['smtpPort']?>">
						</div>
						<div class="form-group">
							<label for="taxAmount">Weergaven email: </label>
							<input type="text" class="form-control" id="smtpName" placeholder="<?=$_CFG['smtpName']?>">
						</div>
						<div class="form-group">
							<label for="taxAmount">Email: </label>
							<input type="text" class="form-control" id="smtpUser" placeholder="<?=$_CFG['smtpUser']?>">
						</div>
						<div class="form-group">
							<label for="street">Wachtwoord: </label>
							<input type="password" class="form-control" id="smtpPass" value="<?=$_CFG['smtpPass']?>">
						</div>
						<div class="form-group">
							<label for="street">SMTP Security: </label>
							<input type="text" class="form-control" id="smtpSecure" placeholder="<?=$_CFG['smtpSecure']?>">
						</div>
						<input type="button" class="btn btn-primary btn-xl" id="updateVarsOptions" value="Instellingen opslaan" />
						<script>
							$(document).ready(function () {
								$("#updateVarsOptions").click(function () {
									$.get(
										"management.php",
										{
											applyOptions: 1,
											companyName: $("#companyName").val() !== null && $("#companyName").val() !== '' ? $("#companyName").val() : "<?=$_CFG['COMPANY_NAME']?>",
											vat: $("#taxAmount").val() !== null && $("#taxAmount").val() !== '' ? $("#taxAmount").val() / 100 + 1 : "<?=$_CFG['VAT']?>",
											currency: $("#currency").val() !== null && $("#currency").val() !== '' ? $("#currency").val() : "<?=$_CFG['CURRENCY']?>",
											smtpHost: $("#smtpHost").val() !== null && $("#smtpHost").val() !== '' ? $("#smtpHost").val() : "<?=$_CFG['smtpHost']?>",
											smtpPort: $("#smtpPort").val() !== null && $("#smtpPort").val() !== '' ? $("#smtpPort").val() : "<?=$_CFG['smtpPort']?>",
											smtpName: $("#smtpName").val() !== null && $("#smtpName").val() !== '' ? $("#smtpName").val() : "<?=$_CFG['smtpName']?>",
											smtpUser: $("#smtpUser").val() !== null && $("#smtpUser").val() !== '' ? $("#smtpUser").val() : "<?=$_CFG['smtpUser']?>",
											smtpPass: $("#smtpPass").val() !== null && $("#smtpPass").val() !== '' ? $("#smtpPass").val() : "<?=$_CFG['smtpPass']?>",
											smtpSecure: $("#smtpSecure").val() !== null && $("#smtpSecure").val() !== '' ? $("#smtpSecure").val() : "<?=$_CFG['smtpSecure']?>"
										},
										function (data)
										{
											$("#loaderAnimation").fadeIn();
											$("#managementForm").fadeOut();
											$("#balanceManagement").fadeOut();
											$("#PageContent").load("management.php?content", function () {
												$("#loaderAnimation").fadeOut();
											});
										}
									);
								});
							});
						</script>
					</div>
				</div>
				<div class="tab-pane" id="3">
					<div id="managementForm">
					    <h2>Database Beheer</h2>
					    <br />
						<div class="container">
							<div class="panel panel-default">
								<div class="panel-heading">
									<button type="button" class="btn btn-default btn-xs spoiler-trigger" data-toggle="collapse">Artikelen Importeren</button>
								</div>
								<div class="panel-collapse collapse out">
									<div class="panel-body">
										<div class="form-group">
											Gistron XML Aanwezig: <?php if (file_exists(dirname(__FILE__) . '/import/gistron.xml')) { echo 'Ja'; } else { echo 'Nee'; } ?>
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
											Copaco XML Aanwezig: <?php if (file_exists(dirname(__FILE__) . '/import/copaco.xml')) { echo 'Ja'; } else { echo 'Nee'; } ?>
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
											United Supplies XML Aanwezig: <?php if (file_exists(dirname(__FILE__) . '/import/unitedsupplies.xml')) { echo 'Ja'; } else { echo 'Nee'; } ?>
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
									</div>
								</div>
							</div>
							<div class="panel panel-default">
								<div class="panel-heading">
								<button type="button" class="btn btn-default btn-xs spoiler-trigger" data-toggle="collapse" disabled>Artikelen Updaten</button>
								</div>
								<div class="panel-collapse collapse out">
									<div class="panel-body">
										<div class="form-group">
											Gistron XML Aanwezig: <?php if (file_exists(dirname(__FILE__) . '/import/gistron.xml')) { echo 'Ja'; } else { echo 'Nee'; } ?>
											<br />
											<input type="button" class="btn btn-success" id="importGistronU" value="Gistron Updaten" />

											<script>
												var startTime = Math.floor(Date.now() / 1000);
												var intervalObject;

												$(document).ready(function () {
													$("#importGistronU").click(function () {
														startTime = Math.floor(Date.now() / 1000);
														intervalObject = setInterval(updateText, 1000);

														$("#loaderAnimationU").fadeIn();
														$("#managementForm").fadeOut();
														$("#balanceManagement").fadeOut();
														$("#PageContent").load("item/itemManage.php?update=gistron", function () {
															$("#loaderAnimationU").fadeOut();
														});
													});
												});
											</script>
										</div>
										<div class="form-group">
											Copaco XML Aanwezig: <?php if (file_exists(dirname(__FILE__) . '/import/copaco.xml')) { echo 'Ja'; } else { echo 'Nee'; } ?>
											<br />
											<input type="button" class="btn btn-success" id="importCopacoU" value="Copaco Updaten" />

											<script>
												$(document).ready(function () {
													$("#importCopacoU").click(function () {
														startTime = Math.floor(Date.now() / 1000);
														intervalObject = setInterval(updateText, 1000);

														$("#loaderAnimationU").fadeIn();
														$("#managementForm").fadeOut();
														$("#balanceManagement").fadeOut();
														$("#PageContent").load("item/itemManage.php?update=copaco", function () {
															$("#loaderAnimationU").fadeOut();
														});
													});
												});
											</script>
										</div>
										<div class="form-group">
											United Supplies XML Aanwezig: <?php if (file_exists(dirname(__FILE__) . '/import/unitedsupplies.xml')) { echo 'Ja'; } else { echo 'Nee'; } ?>
											<br />
											<input type="button" class="btn btn-success" id="importUSuppliesU" value="United Supplies Updaten" disabled/ />

											<script>
												$(document).ready(function () {
													$("#importUSuppliesU").click(function () {
														startTime = Math.floor(Date.now() / 1000);
														intervalObject = setInterval(updateText, 1000);

														$("#loaderAnimationU").fadeIn();
														$("#managementForm").fadeOut();
														$("#balanceManagement").fadeOut();
														$("#PageContent").load("item/itemManage.php?update=unitedsupplies", function () {
															$("#loaderAnimationU").fadeOut();
														});
													});
												});
											</script>
										</div>
										<br />
										Waarschuwing, het bijwerken van artikelen in het database kan lang duren.
									</div>
								</div>
							</div>
						</div>
						<script>
							$(".spoiler-trigger").click(function() {
								$(this).parent().next().collapse('toggle');
							});
						</script>

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
													<label><input type="checkbox" id="deleteAll" value="">Alle artikelen uit database verwijderen. (DELETE FROM `items` WHERE manuallyInserted=0)</label>
												</div>
												<div class="checkbox">
													<label style="padding-left: 5em;"><input type="checkbox" id="deleteAllInclManual" value="" disabled>Ook handmatig toegevoegde artikelen verwijderen (DELETE FROM `items` WHERE manuallyInserted=1)</label>
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
														$('#deleteAllInclManual').prop("checked", false);
														$('#deleteAllInclManual').prop("disabled", false);
													}
													else
													{
														$("#resetAll").prop("disabled", false);
														$('#deleteAllInclManual').prop("checked", false);
														$('#deleteAllInclManual').prop("disabled", true);
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
															deleteAllInclManual: $("#deleteAllInclManual").is(":checked"),
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

											                if ($("#deleteAllInclManual").is(":checked"))
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

<div id="loaderAnimationU" style="display: none;">
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
        Bezig met updaten van producten...
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

<?php
include("debug.php"); ?>
