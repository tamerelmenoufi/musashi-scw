<?php 
    $home = true; 
    include('includes/includes.php');
?>
<style>
    table{
        width:100%;
    }
    td{
        text-align:center;
        font-size:20px;
        font-family:verdana;
        color:#333;
        padding:10px;
    }
    .bg1{
        background-color:#fff;
    }
    .bg2{
        background-color:#ccc;
    }

</style>
<?php
    ////////////////////////////////////////////////// UTMs ///////////////////////////////////////////////////
    $query = "select 
                    a.*,
                    s.nome as setor_nome,
                    t.nome as time_nome,
                    u.nome as utm_nome,
                    (select count(*) from chamados where status = 'c' and utm = a.utm and data_abertura >= NOW() - INTERVAL 30 DAY) as concluidos,
                    (select count(*) from chamados where status = 'p' and utm = a.utm) as pendentes,
                    (select count(*) from chamados where status = 'n' and utm = a.utm) as novos,
                    ((select count(*) from chamados where status = 'p' and utm = a.utm) + (select count(*) from chamados where status = 'n' and utm = a.utm)) as ordem
                from chamados a 
                    left join setores s on a.setor = s.codigo
                    left join time t on a.time = t.codigo
                    left join utm u on a.utm = u.codigo
                where a.status != 'c' group by a.utm order by ordem desc";
    $result = mysql_query($query);
?>
<table cellspacing="0" cellpadding="0">
    <thead>
        <tr>
            <th colspan="4">UTM</th>
        </tr>
        <tr>
            <th style="width:60%; text-align:left;">Nome</th>
            <th>Novos</th>
            <th>Pendentes</th>
            <th>Concluídos (30 Dias)</th>
        </tr>
    </thead>
    <tbody>
<?php
    $i = 0;
    while($d = mysql_fetch_object($result)){
        if($i%2 == 0){
            $bg = 'bg1';
        }else{
            $bg = 'bg2';
        }
?>
        <tr class="<?=$bg?>">
            <td style="text-align:left;"><?=(utf8_encode($d->utm_nome)?:('NÃO IDENTIFICADO'))?></td>
            <td><?=$d->novos?></td>
            <td><?=$d->pendentes?></td>
            <td><?=$d->concluidos?></td>
        </tr>
<?php
    $i++;
    }
?>
    </tbody>
</table>



<?php
    ////////////////////////////////////////////////// SETORES ///////////////////////////////////////////////////
    $query = "select 
                    a.*,
                    s.nome as setor_nome,
                    t.nome as time_nome,
                    u.nome as utm_nome,
                    (select count(*) from chamados where status = 'c' and setor = a.setor and data_abertura >= NOW() - INTERVAL 30 DAY) as concluidos,
                    (select count(*) from chamados where status = 'p' and setor = a.setor) as pendentes,
                    (select count(*) from chamados where status = 'n' and setor = a.setor) as novos,
                    ((select count(*) from chamados where status = 'p' and setor = a.setor) + (select count(*) from chamados where status = 'n' and setor = a.setor)) as ordem
                from chamados a 
                    left join setores s on a.setor = s.codigo
                    left join time t on a.time = t.codigo
                    left join utm u on a.utm = u.codigo
                where a.status != 'c' group by a.setor order by ordem desc";
    $result = mysql_query($query);
?>
<table >
    <thead>
        <tr>
            <th colspan="4">SETORES</th>
        </tr>
        <tr>
            <th style="width:60%; text-align:left;">Nome</th>
            <th>Novos</th>
            <th>Pendentes</th>
            <th>Concluídos (30 Dias)</th>
        </tr>
    </thead>
    <tbody>
<?php
    $i = 0;
    while($d = mysql_fetch_object($result)){
        if($i%2 == 0){
            $bg = 'bg1';
        }else{
            $bg = 'bg2';
        }
?>
        <tr class="<?=$bg?>">
            <td style="text-align:left;"><?=(utf8_encode($d->setor_nome)?:('NÃO IDENTIFICADO'))?></td>
            <td><?=$d->novos?></td>
            <td><?=$d->pendentes?></td>
            <td><?=$d->concluidos?></td>
        </tr>
<?php
    $i++;
    }
?>
    </tbody>
</table>


<?php
    ////////////////////////////////////////////////// TIMES ///////////////////////////////////////////////////
    $query = "select 
                    a.*,
                    s.nome as setor_nome,
                    t.nome as time_nome,
                    u.nome as utm_nome,
                    (select count(*) from chamados where status = 'c' and time = a.time and data_abertura >= NOW() - INTERVAL 30 DAY) as concluidos,
                    (select count(*) from chamados where status = 'p' and time = a.time) as pendentes,
                    (select count(*) from chamados where status = 'n' and time = a.time) as novos,
                    ((select count(*) from chamados where status = 'p' and time = a.time) + (select count(*) from chamados where status = 'n' and time = a.time)) as ordem
                from chamados a 
                    left join setores s on a.setor = s.codigo
                    left join time t on a.time = t.codigo
                    left join utm u on a.utm = u.codigo
                where a.status != 'c' group by a.time order by ordem desc";
    $result = mysql_query($query);
    $i = 1;
?>
<table>
    <thead>
        <tr>
            <th colspan="4">TIMES DE ATUAÇÃO</th>
        </tr>
        <tr>
            <th style="width:60%; text-align:left;">Nome</th>
            <th>Novos</th>
            <th>Pendentes</th>
            <th>Concluídos (30 Dias)</th>
        </tr>
    </thead>
    <tbody>
<?php
    $i = 0;
    while($d = mysql_fetch_object($result)){
        if($i%2 == 0){
            $bg = 'bg1';
        }else{
            $bg = 'bg2';
        }
?>
        <tr class="<?=$bg?>">
            <td style="text-align:left;"><?=(utf8_encode($d->setor_nome)?:('NÃO IDENTIFICADO'))?></td>
            <td><?=$d->novos?></td>
            <td><?=$d->pendentes?></td>
            <td><?=$d->concluidos?></td>
        </tr>
<?php
    $i++;
    }
?>
    </tbody>
</table>