<?php
	$home = true;
	include('../includes/includes.php');

	$q = "SELECT
        a.codigo,
        a.data_abertura,
        a.status,
        a.time,
        a.motivo,
        a.parada,
        a.setor,
        a.tipo_manutencao,
        a.maquina,
        tm.nome as time_nome,
        mt.nome as motivo_nome,
        s.nome as setor_nome,
        m.nome as maquina_nome,
        t.nome as tipo_manutencao_nome,
        a.problema,
        f.nome as funcionario,
        tc.nome as tecnico
            FROM chamados a
            left join setores s on a.setor = s.codigo
            left join tipos_manutencao t on a.tipo_manutencao = t.codigo
            left join maquinas m on a.maquina = m.codigo
            left join time tm on a.time = tm.codigo
            left join motivos mt on a.motivo = mt.codigo
            left join login tc on a.tecnico = tc.codigo
            left join login f on a.funcionario = f.codigo
        where (a.status != 'c') or (a.status = 'c' and a.data_abertura >= NOW() - INTERVAL 1 DAY)
            order by a.codigo desc limit 10";
	$r = mysql_query($q);
    $n = mysql_num_rows($r);

    $codigo = rand(0,1);

    $json = [
        'status' => (($codigo == 1)?true:false),
    ];

    echo json_encode($json);