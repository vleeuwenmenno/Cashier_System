<?php

if (isset($_GET['cid']) && isset($_GET['lid']))
{
    ?>
    <iframe src = "http://cashier.local/pdf/?cid=<?=$_GET['cid']?>&lid=<?=$_GET['lid']?>&exvat" style="width: 100%; height: 100%;"></iframe>
    <?php
    die();
}

if (isset($_GET['rid']))
{
    ?>
    <!-- Modal -->
    <div class="modal fade" id="sendMailDialog" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Bon mailen</h4>
                </div>

                <div class="modal-footer">
                    <?php if (isset($_SESSION['receipt']['customer'])) { ?>
                    <div id="mailingSegment">
                        <span style="top: -8px; position: relative;">
                            Bon emailen<br />
                        </span>
                        <div class="row" id="emailList">
                            <div class="column">
                                <?php 
                                    if (Misc::sqlGet("email", "customers", "customerId", $_SESSION['receipt']['customer'])['email'] == "")
                                    {
                                        ?>
                                            <input type='text' id='example_email' name='example_emailSUI' class='form-control' value='["<?php echo $_CFG['smtpName']; ?>"]'>
                                        <?php
                                    }
                                    else
                                    {
                                        ?>
                                            <input type='text' id='example_email' name='example_emailSUI' class='form-control' value='["<?php echo Misc::sqlGet("email", "customers", "customerId", $_SESSION['receipt']['customer'])['email']; ?>"]'>
                                        <?php
                                    }
                                ?>
                            </div>
                        </div>
                        <script type="text/javascript">
                        $(function() {
                            $('#example_email').multiple_emails( { position: "top" });
                        });
                            $(document).ready(function() {
                                $('#emailToCustomer').change(function() {
                                    if($("#emailToCustomer").is(":checked"))
                                    {
                                        $("#emailList").children().prop('disabled',false);
                                        $("#emailList").fadeTo(500, 1);
                                        $("#emailList").css("pointer-events", "");
                                    }
                                    else
                                    {
                                        $("#emailList").children().prop('disabled', true);
                                        $("#emailList").fadeTo(500, 0.2);
                                        $("#emailList").css("pointer-events", "none");
                                    }
                                });

                                $('#example_email').change( function(){
                                    $('#current_emails').text($(this).val());
                                });
                            });
                        </script>
                    </div>
                    <br />
                    <?php } else if (!isset($_SESSION['receipt']['customer'])) { ?>
                    <div id="mailingSegment">
                        <div class="row" id="emailList">
                            <div class="column">
                                <input type='text' id='example_email' name='example_emailSUI' class='form-control' value='["<?php echo $_CFG['smtpName']; ?>"]'>
                            </div>
                        </div>
                        <script type="text/javascript">
                        $(function() {
                            $('#example_email').multiple_emails( { position: "top" });
                        });
                            $(document).ready(function() {
                                $('#emailToCustomer').change(function() {
                                    if($("#emailToCustomer").is(":checked"))
                                    {
                                        $("#emailList").children().prop('disabled',false);
                                        $("#emailList").fadeTo(500, 1);
                                        $("#emailList").css("pointer-events", "");
                                    }
                                    else
                                    {
                                        $("#emailList").children().prop('disabled', true);
                                        $("#emailList").fadeTo(500, 0.2);
                                        $("#emailList").css("pointer-events", "none");
                                    }
                                });

                                $('#example_email').change( function(){
                                    $('#current_emails').text($(this).val());
                                });
                            });
                        </script>
                    </div>
                    <br />
                    <?php } ?>
                    <button type="button" class="btn btn-success" id="sendMailBtn" data-dismiss="modal">Bon mailen</button>
                </div>
            </div>
        </div>
    </div>
    <button id="mailBtn" class="btn btn-secondary">Bon mailen</button><br /><br />
    <script>
        $(document).ready(function () {
            $("#mailBtn").click(function(){
                $("#sendMailDialog").modal('show');
            });

            $("#sendMailBtn").click(function(){
                $("#pageLoaderIndicator").fadeIn();
                $("#PageContent").load("mail.php?receipt=<?=$_GET['rid']?>&mail=true&nobcc=1&mailList=" + encodeURIComponent($('#example_email').val()), function() {
                    $("#pageLoaderIndicator").fadeOut();
                });
            });
        });
    </script>
    <iframe src = "pdf/?rid=<?=$_GET['rid']?>" style="width: 100%; height: 100%;"></iframe>
    <?php
    die();
}