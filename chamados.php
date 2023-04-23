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
            <th><?=utf8_decode('TIPO DA MANUTENÇÃO')?></th>
            <th><?=utf8_decode('MÁQUINA')?></th>
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
            <td><?=Funcao_utf8($d->tipo_manutencao)?></td>
            <td><?=Funcao_utf8($d->maquina)?></td>
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
