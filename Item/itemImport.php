
<div id="managementForm">
    <h2>Artikelen Beheren</h2>
    <b>TODO: Zorg dat als er al artikelen aanwezig zijn dat hij dan alleen de prijs update als het itemId en/of EAN gelijk is.</b>
    <br />
    <br />
    <div class="form-group">
        Gistron XML Aanwezig: <?php if (file_exists(dirname(__FILE__) . '/../import/gistron.xml')) { echo 'Ja'; } else { echo 'Nee'; } ?>
        <br />
        <input type="button" class="btn btn-primary" id="importGistron" value="Gistron Importeren" />

        <script>
            $(document).ready(function () {
                $("#importGistron").click(function () {
                    $("#loaderAnimation").fadeIn();
                    $("#managementForm").fadeOut();
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
        <input type="button" class="btn btn-primary" id="importCopaco" value="Copaco Importeren" />

        <script>
            $(document).ready(function () {
                $("#importCopaco").click(function () {
                    $("#loaderAnimation").fadeIn();
                    $("#managementForm").fadeOut();
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
        <input type="button" class="btn btn-primary" id="importUSupplies" value="United Supplies Importeren" disabled/ />

        <script>
            $(document).ready(function () {
                $("#importUSupplies").click(function () {
                    $("#loaderAnimation").fadeIn();
                    $("#managementForm").fadeOut();
                    $("#PageContent").load("item/itemManage.php?import=unitedsupplies", function () {
                        $("#loaderAnimation").fadeOut();
                    });
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
        <br />(Dit kan enige tijd duren)
    </center>
</div>