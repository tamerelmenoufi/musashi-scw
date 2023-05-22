<?php

/*
function EnviarWappNovo($n, $m){
	$postdata = http_build_query(
		array(
			'numero' => $n, // Receivers phonei
			'mensagem' => $m,
		  )
		);
	$opts = array('http' =>
		array(
			'method' => 'POST',
			'header' => 'Content-type: application/x-www-form-urlencoded',
			'content' => $postdata
		)
	);
	$context = stream_context_create($opts);
	$result = file_get_contents('http://wapp.mohatron.com/', false, $context);
}
//*/

function EnviarWappNovo($n, $m){
	$postdata = array(
			'numero' => $n, // Receivers phonei
			'mensagem' => $m,
		  );

	file_put_contents("../../cron/wapp/".md5(date("YmdHis").$n).".txt", json_encode($postdata));

}

function EnviaEmailNovo($codigo, $time){

	$postdata = array(
		'codigo' => $codigo, // Receivers phonei
		'time' => $time,
	  );

	file_put_contents("../../cron/email/".md5(date("YmdHis").$n).".txt", json_encode($postdata));

}