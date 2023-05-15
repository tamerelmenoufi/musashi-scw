<?php
    //$home = true;
    include('includes/includes.php');


    header("Content-type: application/vnd.ms-excel");
    header("Content-type: application/force-download");
    header("Content-Disposition: attachment; filename=chamados.xls");
    header("Pragma: no-cache");


    function Funcao_utf8($dado){
        return $dado;
    }

    function status($st){

        if($st == 'n'){
            return utf8_decode('Novo');
        }elseif($st == 'p'){
            return utf8_decode('Em Produção');
        }elseif($st == 'c'){
            return utf8_decode('Concluído');
        }


    }

    $query = "SELECT
            		a.*,
                    b.nome as funcionario,
                    c.nome as setor,
                    d.nome as tipo_manutencao,
                    e.nome as maquina,
                    f.nome as tecnico


            FROM  chamados a

            left join login b on a.funcionario = b.codigo
            left join setores c on a.setor = c.codigo
            left join tipos_manutencao d on a.tipo_manutencao = d.codigo
            left join maquinas e on a.maquina = e.codigo
            left join login f on a.tecnico = f.codigo order by data_abertura desc limit 1000";


    $query = "SELECT
                    a.*,
                    a.codigos as codigo_nome,

                    tm.nome as time_nome,
                    mt.nome as motivo_nome,
                    s.nome as setor,
                    m.nome as maquina,

                    p.nome as peca_nome,
                    md.nome as modelo_nome,
                    /*cd.nome as codigo_nome,*/

                    t.nome as tipo_manutencao,
                    a.problema,
                    f.nome as funcionario,
                    tc.nome as tecnico
            FROM chamados a
            left join setores s on a.setor = s.codigo

            left join pecas p on a.peca = p.codigo
            left join modelos md on a.modelo = md.codigo
            /*left join codigos cd on a.codigos = cd.codigo*/

            left join tipos_manutencao t on a.tipo_manutencao = t.codigo
            left join maquinas m on a.maquina = m.codigo
            left join time tm on a.time = tm.codigo
            left join motivos mt on a.motivo = mt.codigo
            left join login tc on a.tecnico = tc.codigo
            left join login f on a.funcionario = f.codigo
            order by data_abertura desc limit 1000";


    $result = mysql_query($query);
?>
<table>
    <thead>
        <tr>
            <th><?=utf8_decode('FUNCIONÁRIO')?></th>
            <th><?=utf8_decode('DATA DA ABERTURA')?></th>
            <th><?=utf8_decode('DATA DE RECEBIMENTO')?></th>
            <th><?=utf8_decode('DATA DE FECHAMENTO')?></th>
            <th><?=utf8_decode('SETOR')?></th>

            <th><?=utf8_decode('TIME DE ATUAÇÃO')?></th>
            <th><?=utf8_decode('OCORRÊNCIA')?></th>

            <th><?=utf8_decode('MÁQUINA')?></th>
            <th><?=utf8_decode('PEÇA')?></th>
            <th><?=utf8_decode('MODELO')?></th>
            <th><?=utf8_decode('CÓDIGO')?></th>


            <th><?=utf8_decode('TÉCNICO')?></th>
            <th><?=utf8_decode('PROBLEMA')?></th>
            <th><?=utf8_decode('SITUAÇÃO')?></th>
            <th><?=utf8_decode('OBSERVAÇÕES TÉCNICAS')?></th>
        </tr>
    </thead>
    <tbody>
<?php
    while($d = mysql_fetch_object($result)){

        $q = "SELECT b.nome, a.data, a.observacao FROM chamados_observacoes a left join login b on a.tecnico = b.codigo where chamado = '".$d->codigo."'";
        $r = mysql_query($q);
        $obs_tecnicos = array();
        while($d1 = mysql_fetch_object($r)){
            $obs_tecnicos[] = dataBr($d1->nome).' - '.Funcao_utf8($d1->nome).": ".Funcao_utf8($d1->observacao);
        }

        $obs_tecnicos = @implode('; ',$obs_tecnicos);


?>
        <tr>
            <td><?=Funcao_utf8($d->funcionario)?></td>
            <td><?=dataBr($d->data_abertura)?></td>
            <td><?=dataBr($d->data_recebimento)?></td>
            <td><?=dataBr($d->data_fechamento)?></td>
            <td><?=Funcao_utf8($d->setor)?></td>

            <td><?=Funcao_utf8($d->time_nome)?></td>
            <td><?=Funcao_utf8($d->motivo_nome)?></td>

            <td><?=Funcao_utf8($d->maquina)?></td>
            <td><?=Funcao_utf8($d->peca_nome)?></td>
            <td><?=Funcao_utf8($d->modelo_nome)?></td>
            <td><?=Funcao_utf8($d->codigo_nome)?></td>


            <td><?=Funcao_utf8($d->tecnico)?></td>
            <td><?=Funcao_utf8($d->problema)?></td>
            <td><?=status($d->status)?></td>
            <td><?=$obs_tecnicos?></td>
        </tr>
<?php
    }
?>
    </tbody>
</table>
