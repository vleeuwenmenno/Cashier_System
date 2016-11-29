<?php
include_once("includes.php");

if (isset($_GET['new']))
{
?>
<div id="customerForm">
    <h2>Nieuw Artikel</h2>
    <div class="form-group">
        <label for="initials">Artikel Nummer: </label>
        <input type="text" class="form-control" id="itemId" placeholder="00000" />
    </div>
    <div class="form-group">
        <label for="familyname">EAN: </label>
        <input type="text" class="form-control" id="EAN" placeholder="0884962825884" />
    </div>
    <div class="form-group">
        <label for="companyname">Leverancier: </label>
        <input type="text" class="form-control" id="supplier" placeholder="Com Today" />
    </div>
    <div class="form-group">
        <label for="street">Artikel nummer: </label>
        <input type="text" class="form-control" id="factoryId" placeholder="C6615DE" />
    </div>
    <div class="form-group">
        <label for="city">Artikel naam: </label>
        <input type="text" class="form-control" id="itemName" placeholder="HP No. 15 Zwart 25ml (Origineel)" />
    </div>
    <div class="form-group">
        <label for="postalCode">Artikel categorie: </label>
        <input type="text" class="form-control" id="itemCategory" placeholder="Inkt origineel" />
    </div>
    <div class="form-group">
        <label for="phonehome">Voorraad: </label>
        <input type="text" class="form-control" id="itemStock" placeholder="0-1000000" />
    </div>
    <div class="form-group">
        <label for="phonemobile">Prijs exclusief BTW: </label>
        <input type="text" class="form-control" id="priceExclVat" placeholder="26,66" />
    </div>

    <label for="priceModifier">Prijs berekening: </label>
    <div class="input-group">
        <span class="input-group-addon" id="priceModifierLabel">26,66</span>
        <input type="text" class="form-control" id="priceModifier" aria-describedby="priceModifierLabel" placeholder="* 1.575" />
        <span class="input-group-addon" id="priceModifierLabelOutCome">26,66 * 1.575 = &euro;</span>
    </div>
    <br />
    <button type="button" id="applyBtn" class="btn btn-primary">Artikel Toevoegen</button>
    <script>
				$(document).ready(function ()
				{
				    $('#priceModifier').on('input', function ()
				    {
				        var resultSum = "";
				        $.get(
                            "item/calcString.php",
                            {
                                sum: encodeURIComponent($('#priceExclVat').val() + " " + $("#priceModifier").val())
                            },
                            function (data)
                            {
                                if ($('#priceExclVat').val() == "")
                                    $("#priceModifierLabel").text("26,66");
                                else {
                                    $("#priceModifierLabel").text($('#priceExclVat').val());
                                    $("#priceModifierLabelOutCome").html($('#priceExclVat').val() + " " + $("#priceModifier").val() + " = " + data + " &euro;");
                                }
                            }
                        );
				    });

				    $('#priceExclVat').on('input', function ()
				    {
				        var resultSum = "";
				        $.get(
                            "item/calcString.php",
                            {
                                sum: encodeURIComponent($('#priceExclVat').val() + " " + $("#priceModifier").val())
                            },
                            function (data)
                            {
                                if ($('#priceExclVat').val() == "")
                                    $("#priceModifierLabel").text("26,66");
                                else {
                                    $("#priceModifierLabel").text($('#priceExclVat').val());
                                    $("#priceModifierLabelOutCome").html($('#priceExclVat').val() + " " + $("#priceModifier").val() + " = " + data + " &euro;");
                                }
                            }
                        );
				    });

					$("#applyBtn").on("click", function ()
					{
					    $("#customerForm").fadeOut("fast", function () {
					        $("#loaderAnimation").fadeIn();
					    });

						$.get(
                            "item/itemAdd.php",
                            {
                                itemId: $("#itemId").val(),
                                EAN: $("#EAN").val(),
                                supplier: $("#supplier").val(),
                                factoryId: $("#factoryId").val(),
                                itemName: $("#itemName").val(),
                                itemCategory: $("#itemCategory").val(),
                                itemStock: $("#itemStock").val(),
                                priceExclVat: $("#priceExclVat").val(),
                                priceModifier: $("#priceModifier").val()
                            },
							function(data)
							{
								if (data.match("^OK "))
								{
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

<!-- Modal -->
<div class="modal fade" id="stockWarning" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Waarschuwing</h4>
            </div>
            <div class="modal-body">
                <p>Weet je zeker dat je door wilt gaan?<br />De voorraad voor dit item is 0, doorgaan zal de voorraad -1 maken en dit het voorraad register .</p>
            </div>
            <div class="modal-footer" id="stockWarningFooter">
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-offset-3 col-lg-6">
        <div class="input-group">
            <input type="text" class="form-control" name="searchBar" id="searchBar" placeholder="Zoek term... (EAN, artikel naam, prijs, enz.)" aria-describedby="basic-addon2" />
            <span class="input-group-btn">
                <button class="btn btn-primary" type="submit" id="searchBtn" style="height: 34px;">
                    <span class="glyphicon glyphicon-search"></span>
                </button>
            </span>
        </div>
        <div class="checkbox">
            <label><input type="checkbox" id="isBarCodeMode" value="" disabled readonly>Barcode scanner modus</label>
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

                $("#loadMore").fadeOut("fast");

                $("#listContents").html("");

                $.get(
                    "item/itemLoad.php",
                    {
                        start: 0,
                        count: 50,
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

                        $("#listContents").append(data);
                        startLocation += 50;

                        if (barCodemode)
                        {
                            var row = $("#listContents").closest('table').find(' tbody tr:first').attr('id');

                            if(row === undefined)
                                $('#searchBar').prop('placeholder', 'Kon geen artikel vinden met deze EAN code :(');
                        }
                    }
                );
            });

            var barCodemode = false;

            $('#searchBar').on('keydown', function ( e )
            {
                if (e.which == 13)  // the enter key code
                {
                    if (!barCodemode)
                    {
                        $('#searchBtn').click();
                        $('#searchBar').prop('placeholder', "Zoek term... (EAN, artikel naam, prijs, enz.)");
                    }
                    else
                    {
                        barCodemode = false;
                        $("#isBarCodeMode").prop('checked', false);

                        var row = $("#listContents").closest('table').find(' tbody tr:first').attr('id');
                        alert(row);
                    }

                    return false;
                }
                else
                {
                    barCodemode = false;
                    $("#isBarCodeMode").prop('checked', false);

                    $('#searchBar').prop('placeholder', "Zoek term... (EAN, artikel naam, prijs, enz.)");
                }
            });

            document.onkeydown = function (e)
            {
                e = e || window.event;//Get event
                if (e.ctrlKey)
                {
                    var c = e.which || e.keyCode;//Get key code
                    switch (c) {
                        case 83://Block Ctrl+S
                        case 74://Block Ctrl+J
                        {
                            var row = $("#listContents").closest('table').find(' tbody tr:first').attr('id');

                            $('#searchBar').val('');
                            $("#isBarCodeMode").prop('checked', true);

                            $('#searchBar').prop('placeholder', 'Druk op enter om het artikel toe tevoegen aan de bon...');
                            barCodemode = true;

                            e.preventDefault();
                            e.stopPropagation();
                        }
                        break;
                    }
                }
            };
        });
</script>

<div class="row">
    <div class="panel panel-primary filterable">
        <div class="panel-heading">
            <h3 class="panel-title">Artikelen</h3>
            <div class="pull-right">
                <button class="btn btn-default btn-xs btn-filter">
                    <span class="glyphicon glyphicon-filter"></span>&nbsp;Filteren
                </button>
            </div>
        </div>
        <table class="table">
            <thead>
                <tr class="filters">
                    <th width="15%">
                        <a href="#" class="mustFocus">
                            <input type="text" class="form-control" placeholder="EAN" disabled />
                        </a>
                    </th>
                    <th width="45%">
                        <a href="#" class="mustFocus">
                            <input type="text" class="form-control" placeholder="Item" disabled />
                        </a>
                    </th>
                    <th width="20%">
                        <a href="#" class="mustFocus">
                            <input type="text" class="form-control" placeholder="Artikel Nummer" disabled />
                        </a>
                    </th>
                    <th width="10%">
                        <a href="#" class="mustFocus">
                            <input type="text" class="form-control" placeholder="Voorraad" disabled />
                        </a>
                    </th>
                    <th width="10%">
                        <a href="#" class="mustFocus">
                            <input type="text" class="form-control" placeholder="Verkoop prijs" disabled />
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
    if ($i >= 50)
    {
        ?>
        <button type="button" class="btn btn-info center-block" id="loadMore">Laad Meer</button>
        <script>
                        $(document).ready(function ()
                        {
                            var startLocation = 26;
                            $("#loadMore").on("click", function () {

                                $("#loadMore").fadeOut("fast", function () {
                                    $("html, body").animate({ scrollTop: $(document).height() }, "normal");
                                });

                                $.get(
                                    "item/itemLoad.php",
                                    {
                                        start: startLocation,
                                        count: 50,
                                        sTerm: $("#searchBar").val()
                                    },
                                    function (data)
                                    {
                                        if (data != "")
                                        {
                                            $("#loadMore").fadeIn();
                                        }

                                        $("#listContents").append(data);
                                        startLocation += 50;
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
?>
