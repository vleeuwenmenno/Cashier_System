<?php 

include_once('functions.php');
?>
<h2>Year tests</h2>
<h4>day 22 | 08-2020</h4>
<pre>
<?php
    for ($i = 0; $i < 10; $i++)
        var_dump(Calculate::calculateNextOrder("year", "22", new DateTime("01-08-2020"), $i)->format("Y-m-d"));
?>
</pre>
<h4>day 22 | 08-2020 | sendNow</h4>
<pre>
<?php
    for ($i = 0; $i < 10; $i++)
        var_dump(Calculate::calculateNextOrder("year", "22", new DateTime("01-08-2020"), $i, true)->format("Y-m-d"));
?>
</pre>
<h4>day 22 | 08-2030</h4>
<pre>
<?php
    for ($i = 0; $i < 10; $i++)
        var_dump(Calculate::calculateNextOrder("year", "22", new DateTime("01-08-2030"), $i)->format("Y-m-d"));
?>
</pre>
<h2>Quarters tests</h2>
<h4>day 11 | 07-2020</h4>
<pre>
<?php
    for ($i = 0; $i < 10; $i++)
    {
        $time = Calculate::calculateNextOrder("quarter", "11", new DateTime("01-07-2020"), $i);
        echo $time->format("Y-m-d");
        echo ' Q';
        echo Quarters::getQuarter($time);
        echo '<br/>';
    }
?>
</pre>
<h4>day 5 | 08-2020</h4>
<pre>
<?php
    for ($i = 0; $i < 10; $i++)
    {
        $time = Calculate::calculateNextOrder("quarter", "5", new DateTime("01-08-2020"), $i);
        echo $time->format("Y-m-d");
        echo ' Q';
        echo Quarters::getQuarter($time);
        echo '<br/>';
    }
?>
</pre>
<h4>day 5 | 09-2020</h4>
<pre>
<?php
    for ($i = 0; $i < 10; $i++)
    {
        $time = Calculate::calculateNextOrder("quarter", "5", new DateTime("01-09-2020"), $i);
        echo $time->format("Y-m-d");
        echo ' Q';
        echo Quarters::getQuarter($time);
        echo '<br/>';
    }
?>
</pre>
<h4>day 19 | 10-2020 | sendNow</h4>
<pre>
<?php
    for ($i = 0; $i < 10; $i++)
    {
        $time = Calculate::calculateNextOrder("quarter", "19", new DateTime("01-10-2020"), $i, true);
        echo $time->format("Y-m-d");
        echo ' Q';
        echo Quarters::getQuarter($time);
        echo '<br/>';
    }
?>
</pre>
<h4>day 11 | 07-2020 | sendNow</h4>
<pre>
<?php
    for ($i = 0; $i < 10; $i++)
    {
        $time = Calculate::calculateNextOrder("quarter", "11", new DateTime("01-07-2020"), $i, true);
        echo $time->format("Y-m-d");
        echo ' Q';
        echo Quarters::getQuarter($time);
        echo '<br/>';
    }
?>
</pre>
<h4>day 11 | 10-2020</h4>
<pre>
<?php
    for ($i = 0; $i < 10; $i++)
    {
        $time = Calculate::calculateNextOrder("quarter", "11", new DateTime("01-10-2020"), $i);
        echo $time->format("Y-m-d");
        echo ' Q';
        echo Quarters::getQuarter($time);
        echo '<br/>';
    }
?>
</pre>
<h4>day 11 | 10-2024</h4>
<pre>
<?php
    for ($i = 0; $i < 10; $i++)
    {
        $time = Calculate::calculateNextOrder("quarter", "11", new DateTime("01-10-2024"), $i);
        echo $time->format("Y-m-d");
        echo ' Q';
        echo Quarters::getQuarter($time);
        echo '<br/>';
    }
?>
</pre>

<h2>Months tests</h2>
<h4>day 18 | 07-2020 | sendNow</h4>
<pre>
<?php
    for ($i = 0; $i < 10; $i++)
        var_dump(Calculate::calculateNextOrder("month", "18", new DateTime("01-07-2020"), $i, true)->format("Y-m-d"));
?>
</pre>
<h4>day 18 | 07-2020 (Before today)</h4>
<pre>
<?php
    for ($i = 0; $i < 10; $i++)
        var_dump(Calculate::calculateNextOrder("month", "18", new DateTime("01-07-2020"), $i)->format("Y-m-d"));
?>
</pre>
<h4>day 05 | 07-2021</h4>
<pre>
<?php
    for ($i = 0; $i < 10; $i++)
        var_dump(Calculate::calculateNextOrder("month", "5", new DateTime("01-07-2021"), $i)->format("Y-m-d"));
?>
</pre>