<?php
	$home = true;
	include('../includes/includes.php');

	$q = "SELECT count(*) as qt, max(UNIX_TIMESTAMP(data_abertura)) as tempo FROM `chamados`";
	$r = mysql_query($q);
    $d = mysql_fetch_object($r);

    $json = [
        'status' => (($d->qt != $_POST['qt'] or $d->tempo != $_POST['tempo'])?true:false),
    ];

    echo json_encode($json);