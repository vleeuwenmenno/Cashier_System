
<?php
class Items
{
    public static function getField($field, $itemId)
    {
        global $config;

        $db = new mysqli($config['SQL_HOST'], $config['SQL_USER'], $config['SQL_PASS'], $config['SQL_DB']);

        if($db->connect_errno > 0)
        {
            die('Unable to connect to database [' . $db->connect_error . ']');
        }

        $sql = "SELECT * FROM items WHERE nativeId='$itemId';";

        if(!$result = $db->query($sql))
        {
            die('Er was een fout tijdens het verwerken van de klant gegevens. (' . $db->error . ')' . $sql);
        }

        while($row = $result->fetch_assoc())
        {
            return $row[$field];
        }
    }
}
?>
