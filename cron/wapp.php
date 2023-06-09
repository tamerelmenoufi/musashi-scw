<?php

    function EnviarWappNovo($n, $m){
        $postdata = http_build_query(
            array(
                'numero' => $n, // Receivers phonei
                'mensagem' => $m,
                'cnf' => [
                    'template' => 'mohatron_musashi_scw',
                    'namespace' => 'd897e193_d48e_4831_8739_470062b6e4db',
                    'language' => 'pt_BR',
                ]
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


    $path = "./wapp/";
    if(!is_dir($path)) mkdir($path);
    $dir = dir($path);

    while($file = $dir -> read()){
        if(is_file($path.$file)){
            $json = file_get_contents($path.$file);
            $send = json_decode($json);
            $data = print_r($send, true);
            // file_put_contents('result.txt', $path.$file." || ".$data. " || ".date("d/m/Y H:i:s")."\n\n\n\n", FILE_APPEND | LOCK_EX);
            unlink($path.$file);
            EnviarWappNovo($send->numero, $send->mensagem);
            // echo $file."\n";

        }
    }
    $dir -> close();