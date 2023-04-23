<?php

    //include("{$_SERVER['DOCUMENT_ROOT']}/bkos/lib/includes.php");


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
    $result = file_get_contents('http://moh1.com.br/musashi/scw/src/alertas/modelo.php', false, $context);


    // echo $result;

    //$contatos = sendContatos(0);

     $to = [
            ['to_name' => 'Tamer Mohamed', 'to_email' => 'tamer.menoufi@gmail.com'], 
            ['to_name' => 'Támer Elmenoufi', 'to_email' => 'tamer@mohatron.com.br'],
            //*
            ['to_name' => 'Ronaldo Bosco', 'to_email' => 'ronaldo.bosco@musashi.com.br'],
            ['to_name' => 'Nize Edwards', 'to_email' => 'nize.salvia@musashi.com.br'],
            ['to_name' => 'Hirata', 'to_email' => 'h.hirata@musashi.com.br'],
            ['to_name' => 'José Ribamar', 'to_email' => 'ribamar@musashi.com.br'],
            ['to_name' => 'Gesimiel Cavalvante', 'to_email' => 'gesimielcavalcante@musashi.com.br'],
            ['to_name' => 'Messias Rodrigues', 'to_email' => 'messiaslins@musashi.com.br'],
            ['to_name' => 'Luiz Eduardo Giovanetti', 'to_email' => 'luiz.andrade@musashi.com.br'],
            ['to_name' => 'Regis Santos', 'to_email' => 'regiscarvalho@musashi.com.br'],
            ['to_name' => 'Gisele Freitas', 'to_email' => 'gisele.oliveira@musashi.com.br'],
            ['to_name' => 'Pedro Amorim', 'to_email' => 'pedroamorim@musashi.com.br'],
            ['to_name' => 'Kethlen Costa', 'to_email' => 'kethlen.costa@musashi.com.br'],
            ['to_name' => 'Luciane Sá', 'to_email' => 'luciane.oliveira@musashi.com.br'],
            ['to_name' => 'Carpgiane Souza', 'to_email' => 'carpgiane@musashi.com.br'],
            ['to_name' => 'Rodrigo Teixeira', 'to_email' => 'rodrigosantos@musashi.com.br'],
            ['to_name' => 'Ruy Reis', 'to_email' => 'ruyreis@musashi.com.br'],
            ['to_name' => 'Murilo Costa', 'to_email' => 'murilo.costa@musashi.com.br'],
            ['to_name' => 'Sebastião da Silva', 'to_email' => 'sebastiao@musashi.com.br'],
            ['to_name' => 'Dean Simões', 'to_email' => 'deansimoes@musashi.com.br'],
            ['to_name' => 'José Oliveira', 'to_email' => 'oliveira@musashi.com.br'],
            ['to_name' => 'Israel Feio', 'to_email' => 'israel@musashi.com.br'],
            ['to_name' => 'Adriano Imbiriba', 'to_email' => 'adriano.imbiriba@musashi.com.br'],
            ['to_name' => 'Wagner da Cruz', 'to_email' => 'wagner.souza@musashi.com.br'],
            ['to_name' => 'Valdineia Vasconcelos', 'to_email' => 'valdineia.vasconcelos@musashi.com.br'],
            ['to_name' => 'Ruldson Coelho', 'to_email' => 'ruldson.gomes@musashi.com.br'],
            ['to_name' => 'Paulo Henrique', 'to_email' => 'paulo.sena@musashi.com.br'],
            ['to_name' => 'Robson Rodrigues', 'to_email' => 'robson.rodrigues@musashi.com.br'],
            ['to_name' => 'Arnalda de Jesus', 'to_email' => 'arnalda.ribeiro@musashi.com.br'],
            ['to_name' => 'Afranio Moraes', 'to_email' => 'jose.oliveira@musashi.com.br'],
            ['to_name' => 'Wander Prado', 'to_email' => 'wandergleisson.prado@musashi.com.br'],
            ['to_name' => 'Edmar Martins', 'to_email' => 'edmar.amorim@musashi.com.br'],
            ['to_name' => 'Rodrigo Lima', 'to_email' => 'rodrigo.lima@musashi.com.br'],
            ['to_name' => 'Leandro Godinho', 'to_email' => 'leandro.godinho@musashi.com.br'],
            ['to_name' => 'David Costa Freire', 'to_email' => 'david.freire@musashi.com.br'],
            ['to_name' => 'Jerri Adriani', 'to_email' => 'jerri.santos@musashi.com.br'],
            ['to_name' => 'Mota Simões', 'to_email' => 'mota@musashi.com.br'],
            ['to_name' => 'Gilsomar dos Santos', 'to_email' => 'gilsomar@musashi.com.br'],
            ['to_name' => 'Gesimiel Cavalvante', 'to_email' => 'gesimielcavalcante@musashi.com.br'],
            ['to_name' => 'Carlos Antônio', 'to_email' => 'carlosantonio@musashi.com.br'],
            ['to_name' => 'João Marques', 'to_email' => 'manutencao.mda@musashi.com.br'],
            ['to_name' => 'Airão Cavalcante', 'to_email' => 'airao@musashi.com.br'],
            ['to_name' => 'Diego Bichara', 'to_email' => 'diego.bichara@musashi.com.br'],
            ['to_name' => 'Odinei Silva', 'to_email' => 'odinei@musashi.com.br'],
            ['to_name' => 'Joelso Mazzarolo', 'to_email' => 'joelso.mazzarolo@musashi.com.br'],
            ['to_name' => 'Akira Takeno', 'to_email' => 'akiratakeno@musashi.com.br'],
            //*/
            ['to_name' => 'Taicir Elmenoufi', 'to_email' => 'taicir@mohatron.com.br'],
            
            
        
        ];

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