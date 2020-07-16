<?php $rand = rand(0, 30000); ?>
<div class="panel panel-default" id="debugPanel<?=$rand?>" style="display: none;">
    <div class="panel-collapse">
        <div class="panel-body">
            <pre>
                <?php print_r ($_SESSION); ?>
            </pre>
        </div>
    </div>
</div>

<script type="text/javascript">
    function showDebug()
    {
        $("#debugPanel<?=$rand?>").css("display", "");
    }
</script>
<?php

$time = microtime();
$time = explode(' ', $time);
$time = $time[1] + $time[0];
$finish = $time;
$total_time = str_replace("0.", "", number_format(($finish - $start), 4));
echo '<script> $(document).ready(function () { console.log("Page created in '.$total_time.'ms"); });';