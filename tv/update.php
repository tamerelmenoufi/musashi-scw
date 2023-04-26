<?php
    $codigo = rand(0,1);

    $json = [
        'status' => (($codigo == 1)?true:false),
    ];

    echo json_encode($json);