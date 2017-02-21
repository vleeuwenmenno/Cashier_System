<?php
include_once("includes.php");

if (isset($_GET['new']))
{
?>
		<div id="customerForm">
			<h2>Nieuwe klant</h2>
			<div class="form-group">
				<label for="initials">Voorletters: </label>
				<input type="text" class="form-control" id="initials" placeholder="A.">
			</div>
			<div class="form-group">
				<label for="familyname">Achternaam: </label>
				<input type="text" class="form-control" id="familyname" placeholder="Bakker">
			</div>
					<div class="form-group">
				<label for="companyname">Bedrijfsnaam: </label>
				<input type="text" class="form-control" id="companyname" placeholder="Com Today...">
			</div>
			<div class="form-group">
				<label for="street">Straat: </label>
				<input type="text" class="form-control" id="street" placeholder="Kerkstraat">
			</div>
			<div class="form-group">
				<label for="city">Woonplaats: </label>
				<input type="text" class="form-control" id="city" placeholder="Heemskerk">
			</div>
            <div class="form-group">
                <label for="postalCode">Postcode: </label>
                <input type="text" class="form-control" id="postalCode" placeholder="0123 AB" />
            </div>
			<div class="form-group">
				<label for="phonehome">Telefoon: </label>
				<input type="text" class="form-control" id="phonehome" placeholder="0251 200 627">
			</div>
			<div class="form-group">
				<label for="phonemobile">Mobiel: </label>
				<input type="text" class="form-control" id="phonemobile" placeholder="06 12 34 56 78">
			</div>
			<div class="form-group">
				<label for="email">Email: </label>
				<input type="text" class="form-control" id="email" placeholder="abakker@voorbeeld.nl">
			</div>
			<button type="button" id="applyBtn" class="btn btn-primary">Klant Aanmaken</button>
			<script>
				$(document).ready(function ()
				{
					$("#applyBtn").on("click", function ()
					{
					    $("#customerForm").fadeOut("fast", function () {
					        $("#loaderAnimation").fadeIn();
					    });

						$.get(
                            "customer/customerAdd.php",
                            {
                            	intials: $("#initials").val(),
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
								if (data.match("^OK "))
								{
									var arr = data.split(' ');
									$("#customerId").val(arr[1]);
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

					document.getElementById('phonemobile').addEventListener('input', function (e) {
						e.target.value = e.target.value.replace(/[^\dA-Z]/g, '').replace(/(.{2})/g, '$1 ').trim();
					});

					document.getElementById('phonehome').addEventListener('input', function (e) {
						e.target.value = e.target.value.replace(/(\d\d\d\d)(\d\d\d)(\d\d\d)/, "$1-$2-$3").trim();
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
            <input type="text" class="form-control" name="searchBar" id="searchBar" placeholder="Zoek term... (Id, voorletter, achternaam, enz.)" aria-describedby="basic-addon2">
            <span class="input-group-btn">
            <button class="btn btn-primary" type="submit" id="searchBtn" style="height: 34px;">
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
                <h3 class="panel-title">Klanten</h3>
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
                                <input type="text" class="form-control" placeholder="Id" disabled />
                            </a>
                        </th>
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
                if ($i >= 25)
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
echo 'Page created in '.$total_time.'ms';
?>
