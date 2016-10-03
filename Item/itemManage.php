<?php
include_once("../includes.php");

?>
<div id="customerForm">



    <?php
if (isset($_GET['import']))
{
    if ($_GET['import'] == "gistron")
    {
        ini_set('max_input_vars', 10000);

        $xml = simplexml_load_string(file_get_contents(dirname(__FILE__) . '/../import/gistron.xml'));
        $json = json_encode($xml);
        $array = json_decode($json,TRUE);

        $db = new mysqli($config['SQL_HOST'], $config['SQL_USER'], $config['SQL_PASS'], $config['SQL_DB']);

        if($db->connect_errno > 0)
        {
            die('Unable to connect to database [' . $db->connect_error . ']');
        }

        echo '<pre>';
        foreach ($array['product'] as $product)
        {
            if (is_array($product['ean']))
                $sql = "INSERT INTO items (itemId, EAN, supplier, factoryId, itemName, itemCategory, priceExclVat) VALUES ('" . $product['artnr'] . "', '', 'Gistron', '" . $product['sku'] . "', '" . urlencode($product['omschrijving']) . "', '" . $product['productgroep'] . "', '" . $product['prijs_ex'] . "');";
            else
                $sql = "INSERT INTO items (itemId, EAN, supplier, factoryId, itemName, itemCategory, priceExclVat) VALUES ('" . $product['artnr'] . "', '" . $product['ean'] . "', 'Gistron', '" . $product['sku'] . "', '" . urlencode($product['omschrijving']) . "', '" . $product['productgroep'] . "', '" . $product['prijs_ex'] . "');";

            if(!$result = $db->query($sql))
            {
                echo  $product['artnr'] . ' failed (' . $db->error . ')<br />';
            }
            else
            {
                $query = "SELECT itemId FROM items WHERE itemId='" . $product['artnr'] . "'";
                if(!$results = $db->query($query))
                {
                    echo  $product['artnr'] . ' failed (' . $db->error . ')<br />';
                }

                if(mysqli_num_rows($results) > 1)
                {
                    echo  $product['artnr'] . ' ok, but duplicate EAN!<br />';
                }
                else if(mysqli_num_rows($results) > 0)
                {
                    echo  $product['artnr'] . ' ok, confirmed.<br />';
                }
                else
                {
                    echo  $product['artnr'] . ' error, not found.<br />';
                }
            }
        }
        echo '</pre>';
    }
}
else if (isset($_GET['update']))
{
    ?>
    <h2>Artikelen Inboeken</h2>
    <div class="form-group">
        <div class="form-group">
            <label for="EAN" id="EANLabel">EAN: </label>
            <input type="text" class="form-control" id="EAN" placeholder="0884962825884" />
        </div>
        <div class="form-group">
            <label for="amount">Aantal: </label>
            <input type="text" class="form-control" id="amount" placeholder="+10" />
        </div>
        <div class="checkbox">
          <label><input type="checkbox" value="" id="itemIdUse">Inboeken met artikel nummer</label>
        </div>
        <input type="button" class="btn btn-primary" id="updateItem" value="Inboeken" />

        <script>
            $(document).ready(function () 
            {
                $("#itemIdUse").change(function() 
                {
                    if(this.checked) 
                    {
                        $("#EANLabel").html("Artikel Nummer:");
                        $("#EAN").attr("placeholder", "20243");
                    }
                    else
                    {
                        $("#EANLabel").html("EAN:");
                        $("#EAN").attr("placeholder", "0884962825884");
                    }
                });
                $('#EAN').keypress(function (e)
                {
                    var key = e.which;
                    if (key == 13)  // the enter key code
                    {
                        $('#amount').focus();
                        return false;
                    }
                });

                $('#amount').keypress(function (e)
                {
                    var key = e.which;
                    if (key == 13)  // the enter key code
                    {
                        $('#updateItem').click();
                        return false;
                    }
                });

                $("#updateItem").click(function ()
                {
                    $("#loaderAnimation").fadeIn();

                    if ($("#itemIdUse").is(':checked'))
                    {
                        $.get(
                            "item/itemUpdateStock.php",
                            {
                                itemStock: $('#amount').val(),
                                itemId: $('#EAN').val()
                            },
                            function (data)
                            {
                                if (data.match("^OK"))
							    {
                                    $.notify({
	                                    icon: 'glyphicon glyphicon-ok',
	                                    title: 'Inboeken succesvol verwerkt',
                                        message: 'Uw aanvraag is met succes verwerkt in het systeem.'
                                    },{
	                                    // settings
	                                    type: 'success',
                                        placement: {
		                                    from: "bottom",
		                                    align: "center"
	                                    }
                                    });

							        $("#loaderAnimation").fadeOut();

                                    $('#EAN').val("");
                                    $('#amount').val("");
                                    $('#EAN').focus();
							    }
							    else
							    {
							        $.notify({
	                                    icon: 'glyphicon glyphicon-warning-sign',
	                                    title: 'Fout',
	                                    message: data 
                                    },{
	                                    // settings
	                                    type: 'danger',
                                        placement: {
		                                    from: "bottom",
		                                    align: "center"
	                                    }
                                    });

                                    $("#loaderAnimation").fadeOut();
                                    $('#EAN').focus();
							    }
                            }
                        );
                    }
                    else
                    {
                        $.get(
                            "item/itemUpdateStock.php",
                            {
                                itemStock: $('#amount').val(),
                                EAN: $('#EAN').val()
                            },
                            function (data)
                            {
                                if (data.match("^OK "))
							    {
                                    $.notify({
	                                    icon: 'glyphicon glyphicon-ok',
	                                    title: 'Inboeken succesvol verwerkt',
                                        message: 'Uw aanvraag is met succes verwerkt in het systeem.'
                                    },{
	                                    // settings
	                                    type: 'success',
                                        placement: {
		                                    from: "bottom",
		                                    align: "center"
	                                    }
                                    });

							        $("#loaderAnimation").fadeOut();

                                    $('#EAN').val("");
                                    $('#amount').val("");
                                    $('#EAN').focus();
							    }
							    else
							    {
							        $.notify({
	                                    icon: 'glyphicon glyphicon-warning-sign',
	                                    title: 'Fout',
	                                    message: data 
                                    },{
	                                    // settings
	                                    type: 'danger',
                                        placement: {
		                                    from: "bottom",
		                                    align: "center"
	                                    }
                                    });
                                    $("#loaderAnimation").fadeOut();
                                    $('#EAN').focus();
							    }
                            }
                        );
                    }
                });
            });
        </script>
    </div>
</div>
    <?php
}
else
{
    ?>
    <h2>Artikelen Beheren</h2>
    <b>TODO: Zorg dat als er al artikelen aanwezig zijn dat hij dan alleen de prijs update als het itemId en/of EAN gelijk is.</b><br /><br />
    <div class="form-group">
        Gistron XML Aanwezig: <?php if (file_exists(dirname(__FILE__) . '/../import/gistron.xml')) { echo 'Ja'; } else { echo 'Nee'; } ?>
        <br />
        <input type="button" class="btn btn-primary" id="importGistron" value="Gistron Importeren" />

        <script>
            $(document).ready(function () {
                $("#importGistron").click(function ()
                {
                    $("#loaderAnimation").fadeIn();
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
        <input type="button" class="btn btn-primary" id="importGistron" value="Copaco Importeren" disabled/ />

        <script>
            $(document).ready(function () {
                $("#importGistron").click(function ()
                {
                    $("#loaderAnimation").fadeIn();
                    $("#PageContent").load("item/itemManage.php?import=gistron", function () {
                        $("#loaderAnimation").fadeOut();
                    });
                });
            });
        </script>
    </div>
    <div class="form-group">
        United Supplies XML Aanwezig: <?php if (file_exists(dirname(__FILE__) . '/../import/unitedsupplies.xml')) { echo 'Ja'; } else { echo 'Nee'; } ?>
        <br />
        <input type="button" class="btn btn-primary" id="importGistron" value="United Supplies Importeren" disabled/ />

        <script>
            $(document).ready(function () {
                $("#importGistron").click(function ()
                {
                    $("#loaderAnimation").fadeIn();
                    $("#PageContent").load("item/itemManage.php?import=gistron", function () {
                        $("#loaderAnimation").fadeOut();
                    });
                });
            });
        </script>
    </div>
</div> 
    <?php
}
?>
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
        <br />(Dit kan enige tijd duren)
    </center>
</div>