<?php

$home = true;
include("../../includes/includes.php");


function SendMail($dados){


        $url = "http://email.mohatron.com/send.php";
        // Make a POST request
        $options = stream_context_create(['http' => [
                'method'  => 'POST',
                'header' => 'Content-Type: application/x-www-form-urlencoded',
                'content' => http_build_query($dados)
            ]
        ]);

        // Send a request
        $result = file_get_contents($url, false, $options);
        $result = json_decode($result);

        // echo "<pre>";
        // print_r($result);
        // echo "</pre>";

        return $result->status;

        // foreach($result as $i => $d){
        //     if($i != 'status'){
        //         if($result->status == 'error'){
        //             foreach($d as $fild => $msg_error){
        //                 echo "Posição: ".$i;
        //                 echo "<br>";
        //                 echo "Campo: ".$fild;
        //                 echo "<br>";
        //                 echo "Erro: ".$msg_error;
        //                 echo "<br><hr>";
        //             }
        //         }else if($result->status == 'success'){

        //             echo "ID: ".$d->id;
        //             echo "<br>";
        //             echo "MESSAGE: ".$d->message;
        //             echo "<br><hr>";

        //         }

        //     }
        // }
    }



    $postdata = http_build_query(
        array(
            'acao' => 'resumo', // Receivers phonei
            'codigo' => $_POST['codigo'],
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
    $result = file_get_contents('http://scw.mohatron.com/src/alertas/modelo.php', false, $context);


    // echo $result;

    //$contatos = sendContatos(0);


    foreach($Notificacao['email'][$_POST['time']] as $ind => $mail){
        $to[] =  ['to_name' => $Notificacao['nome'][$_POST['time']][$ind], 'to_email' => $mail];
    }

     $contatos = [
         'to' => $to
     ];

    $_SESSION['MailFotosInline'] = [];
    $_SESSION['MailFotosInline'][] = 'http://musashi.mohatron.com/img/musashi.png';

    $dados = [
        'from_name' => 'MUSASHI - SCW',
        'from_email' => 'mailgun@moh1.com.br',
        'subject' => 'Resumo da Situação - S.C.W.',
        'html' => $result,
        // 'attachment' => [
        //     './img_bk.png',
        //     './cliente-mohatron.xls',
        //     './formulario_prato_cheio.pdf',
        //     'https://os.bkmanaus.com.br/img/logo.png',
        // ],
        'inline' => $_SESSION['MailFotosInline'],
        // [
        //     // './img_bk.png',
        //     'https://os.bkmanaus.com.br/img/logo.png',
        // ],
        'to' => $contatos['to']
    ];
    // print_r($dados);
    print_r(SendMail($dados));
    ///////////////////////////////////////////////////////