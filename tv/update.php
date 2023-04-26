<?php
    $codigo = rand(0,10);

    $json = [
        'codigo' => $codigo,
        'conteudo' => "Alteração no registro de código {$codigo}"
    ];

    echo json_encode($json);