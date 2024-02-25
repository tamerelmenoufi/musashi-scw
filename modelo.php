<?php 
    $home = true; 
    include('includes/includes.php');

    $query = "select * from chamados where status != 'c' order by data_abertura desc, status";
    $result = mysql_query($query);
    $i = 1;
    while($d = mysql_fetch_object($result)){
?>
<?=$d->problema?> - <?=$d->status?><br>
<?php
    $i++;
    }
?>