<?php

function EnviaEmailNovo($codigo, $time){

    ////////////////EMAIL//////////////////////////////////
    $postdata = http_build_query(
        array(
            'codigo' => $codigo,
            'time' => $time,
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
    $result = file_get_contents('http://scw.mohatron.com/src/alertas/email.php', false, $context);
    ////////////////////////////////////////////////////////

}


    $path = "./email/";
    if(!is_dir($path)) mkdir($path);
    $dir = dir($path);

    while($file = $dir -> read()){
        if(is_file($path.$file)){
            $json = file_get_contents($path.$file);
            $send = json_decode($json);
            $data = print_r($send, true);
            // file_put_contents('result.txt', $path.$file." || ".$data. " || ".date("d/m/Y H:i:s")."\n\n\n\n", FILE_APPEND | LOCK_EX);
            unlink($path.$file);
            EnviaEmailNovo($send->codigo, $send->time);
            // echo $file."\n";

        }
    }
    $dir -> close();