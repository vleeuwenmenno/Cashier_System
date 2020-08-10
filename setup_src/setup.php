<?php 
function copyfiles($source_folder, $target_folder, $move=false) {
    $source_folder=trim($source_folder, '/').'/';
    $target_folder=trim($target_folder, '/').'/';
    $files = scandir($source_folder);
    foreach($files as $file) {
        if($file != '.' && $file != '..') {
            if ($move) {
                rename($source_folder.$file, $target_folder.$file);
            } else {
                copy($source_folder.$file, $target_folder.$file);
            }
        }
    }   
}

function movefiles($source_folder, $target_folder) {
    copyfiles($source_folder, $target_folder, $move=true);
}

function rmrf($dir) {
    $files = glob($dir.'/{,.}*', GLOB_BRACE); // get all file names
    foreach($files as $file){ // iterate files
    if(is_file($file))
        unlink($file); // delete file
    }

    foreach (glob($dir) as $file) {
        if (is_dir($file)) { 
            rmrf("$file/*");
            rmdir($file);
        } else {
            unlink($file);
        }
    }
}

if (isset($_POST['mysqlHost']) && isset($_POST['mysqlPass']) && isset($_POST['mysqlUser']) && isset($_POST['mysqlDb']))
{?>
<!doctype html>
<html lang="en">
    <head>
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

        <title>Cashier installer</title>
    </head>
    <body>
        <!-- Begin page content -->
        <main role="main" class="flex-shrink-0">
            <div class="container">
                <h1 class="mt-5">Installatie voortgang:</h1>
                <p class="lead">
                    <?php
    echo 'Downloading archive ...<br/>';
    $f = file_put_contents("cashier.zip", fopen("https://github.com/vleeuwenmenno/Cashier_System/archive/v2.0.5-beta.zip", 'r'), LOCK_EX);
    if(FALSE === $f)
        die("Couldn't write to file.");

    echo 'Unzipping archive ...<br/>';
    $zip = new ZipArchive;
    $res = $zip->open('cashier.zip');

    if ($res === TRUE) 
    {
        $zip->extractTo('.');
        $zip->close();

        echo 'Importing database ...<br/>';
        $conn = new mysqli($_POST['mysqlHost'], $_POST['mysqlUser'], $_POST['mysqlPass']);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        
        // Create database
        $sql = "CREATE DATABASE ".$_POST['mysqlDb'];
        if ($conn->query($sql) === TRUE) {
        } else {
            die("Error creating database: " . $conn->error);
        }

        $conn->close();
        $conn = new mysqli($_POST['mysqlHost'], $_POST['mysqlUser'], $_POST['mysqlPass'] , $_POST['mysqlDb']);

        $query = '';
        $sqlScript = file('./Cashier_System-2.0.4-beta/db/export.sql');
        foreach ($sqlScript as $line)	
        {
            $startWith = substr(trim($line), 0 ,2);
            $endWith = substr(trim($line), -1 ,1);
            
            if (empty($line) || $startWith == '--' || $startWith == '/*' || $startWith == '//') {
                continue;
            }
                
            $query = $query . $line;
            if ($endWith == ';') {
                mysqli_query($conn,$query) or die('<div class="error-response sql-import-response">Problem in executing the SQL query <b>' . $query. '</b></div>');
                $query= '';		
            }
        }

        echo 'Writing config file for database ...<br/>';
        $myfile = fopen("vars.php", "w") or die("Unable to open file!");
        $txt = '
        <?php 
            $config = array(
                "SQL_PASS" => "'.$_POST['mysqlPass'].'",
                "SQL_USER" => "'.$_POST['mysqlUser'].'",
                "SQL_HOST" => "'.$_POST['mysqlHost'].'",
                "SQL_DB" => "'.$_POST['mysqlDb'].'",
                "timeout" => 480
            );
        ';
        fwrite($myfile, $txt);
        fclose($myfile);

        echo 'Running composer update ...<br/>';
        shell_exec ("composer update");

        echo 'Removing junk files ...<br/>';
        movefiles("./Cashier_System-2.0.4-beta/src", ".");
        unlink("./Cashier_System-2.0.4-beta/.gitignore");
        unlink("cashier.zip");
        unlink("setup.php");
        rmrf("./Cashier_System-2.0.4-beta");
        
        echo 'Done!!<br/><br/>The default login credentials are admin | admin<br />Please once logged in change the password to something more secure.<br/><a href="index.php">Click here to continue to login</a>';
        ?>
        </p>
            </div>
        </main>

        <!-- Optional JavaScript -->
        <!-- jQuery first, then Popper.js, then Bootstrap JS -->
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    </body>
</html>
<?php
    }
}
else
{
?>
<!doctype html>
<html lang="en">
    <head>
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

        <title>Cashier installer</title>
    </head>
    <body>
        <!-- Begin page content -->
        <main role="main" class="flex-shrink-0">
            <div class="container">
                <h1 class="mt-5">Database instellingen:</h1>
                <p class="lead">
                    <form action="setup.php" method="POST">
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="inputGroup-sizing-default">Host</span>
                            </div>
                            <input class="form-control" type="text" id="mysqlHost" name="mysqlHost" value="localhost" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default">
                        </div>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="inputGroup-sizing-default">Gebruiker</span>
                            </div>
                            <input class="form-control" type="text" id="mysqlUser" name="mysqlUser" value="root" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default">
                        </div>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="inputGroup-sizing-default">Wachtwoord</span>
                            </div>
                            <input class="form-control" type="password" id="mysqlPass" name="mysqlPass" value="" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default">
                        </div>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="inputGroup-sizing-default">Database naam</span>
                            </div>
                            <input class="form-control" type="text" id="mysqlDb" name="mysqlDb" value="cashier_server" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default">
                        </div>
                        <h3 style="display: none;" id="installLabel">Installatie bezig ...</h3>
                        <a href="#" id="installBtn" class="btn btn-primary">Installeer kassa server</a>
                    </form>
                </p>
            </div>
        </main>

        <!-- Optional JavaScript -->
        <!-- jQuery first, then Popper.js, then Bootstrap JS -->
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
        <script>
            $(document).ready(function()
            {
                $("#installBtn").click(function()
                {
                    $(".input-group").hide();
                    $("#installLabel").show();
                    $("#installBtn").hide();
                    $("form:first").submit();
                });
            });
        </script>
    </body>
</html>
<?php } ?>