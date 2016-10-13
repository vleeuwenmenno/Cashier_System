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

        $ok = 0;
        $fail = 0;

        echo '<br /><pre>';
        foreach ($array['product'] as $product)
        {
            if (is_array($product['ean']))
                $sql = "INSERT INTO items (itemId, EAN, supplier, factoryId, itemName, itemCategory, priceExclVat) VALUES ('" . $product['artnr'] . "', '', 'Gistron', '" . $product['sku'] . "', '" . urlencode(htmlspecialchars($product['omschrijving'])) . "', '" . $product['productgroep'] . "', '" . $product['prijs_ex'] . "');";
            else
                $sql = "INSERT INTO items (itemId, EAN, supplier, factoryId, itemName, itemCategory, priceExclVat) VALUES ('" . $product['artnr'] . "', '" . $product['ean'] . "', 'Gistron', '" . $product['sku'] . "', '" . urlencode(htmlspecialchars($product['omschrijving'])) . "', '" . $product['productgroep'] . "', '" . $product['prijs_ex'] . "');";

            if(!$result = $db->query($sql))
            {
                echo  $product['artnr'] . ' failed (' . $db->error . ')<br />';
                $fail++;
            }
            else
            {
                $ok++;

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
                    //echo  $product['artnr'] . ' ok, confirmed.<br />';
                }
                else
                {
                    echo  $product['artnr'] . ' error, not found.<br />';
                }
            }
        }
        echo 'Successfully imported ' . $ok . ' items. (Fail count: ' . $fail . ')';
        die('</pre>');
    }
    else if ($_GET['import'] == "copaco")
    {
        ini_set('max_input_vars', 10000);
        ini_set('memory_limit', '-1');

        $xml = simplexml_load_string(file_get_contents(dirname(__FILE__) . '/../import/copaco.xml'));
        $json = json_encode($xml);
        $array = json_decode($json,TRUE);

        $db = new mysqli($config['SQL_HOST'], $config['SQL_USER'], $config['SQL_PASS'], $config['SQL_DB']);

        if($db->connect_errno > 0)
        {
            die('Unable to connect to database [' . $db->connect_error . ']');
        }

        $ok = 0;
        $fail = 0;

        echo '<br /><pre>';
        foreach ($array['item'] as $product)
        {
            if (is_array($product['EAN_code']))
                $sql = "INSERT INTO items (itemId, EAN, supplier, factoryId, itemName, itemCategory, priceExclVat) VALUES ('" . $product['item_id'] . "', '', 'Copaco', '" . $product['vendor_id'] . "', '" . urlencode(htmlspecialchars($product['long_desc'])) . "', '" . $product['item_group'] . "', '" . $product['price'] . "');";
            else
                $sql = "INSERT INTO items (itemId, EAN, supplier, factoryId, itemName, itemCategory, priceExclVat) VALUES ('" . $product['item_id'] . "', '" . $product['EAN_code'] . "', 'Copaco', '" . $product['vendor_id'] . "', '" . urlencode(htmlspecialchars($product['long_desc'])) . "', '" . $product['item_group'] . "', '" . $product['price'] . "');";

            if(!$result = $db->query($sql))
            {
                echo  $product['artnr'] . ' failed (' . $db->error . ')<br />';
                $fail++;
            }
            else
            {
                $ok++;

                $query = "SELECT itemId FROM items WHERE itemId='" . $product['item_id'] . "'";
                if(!$results = $db->query($query))
                {
                    echo  $product['item_id'] . ' failed (' . $db->error . ')<br />';
                }

                if(mysqli_num_rows($results) > 1)
                {
                    echo  $product['item_id'] . ' ok, but duplicate EAN!<br />';
                }
                else if(mysqli_num_rows($results) > 0)
                {
                    //echo  $product['item_id'] . ' ok, confirmed.<br />';
                }
                else
                {
                    echo  $product['item_id'] . ' error, not found.<br />';
                }
            }
        }
        echo 'Successfully imported ' . $ok . ' items. (Fail count: ' . $fail . ')';
        die('</pre>');
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
                                if (data.match("^OK "))
							    {
                                    $.notify({
	                                    icon: 'glyphicon glyphicon-ok',
	                                    title: 'Inboeken succesvol verwerkt',
	                                    message: 'Voorraad voor ' + data.replace('OK', '') + ' is succesvol geupdate naar '
                                    },{
	                                    // settings
	                                    type: 'success',
                                        placement: {
		                                    from: "bottom",
		                                    align: "right"
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
		                                    align: "right"
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
	                                    message: 'Voorraad voor ' + decodeURIComponent(data.replace('OK', '')) + ' is succesvol geupdate.'
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
    ?>