<?php 
    $home = true; 
    include('includes/includes.php');

    $query = "select 
                    a.*,
                    s.nome as setor_nome,
                    t.nome as time_nome,
                    u.nome as utm_nome,
                    (select count(*) from chamados where status = 'c' and utm = a.utm and data_abertura >= NOW() - INTERVAL 30 DAY) as concluidos,
                    (select count(*) from chamados where status = 'p' and utm = a.utm) as pendentes,
                    (select count(*) from chamados where status = 'n' and utm = a.utm) as novos
                from chamados a 
                    left join setores s on a.setor = s.codigo
                    left join time t on a.time = t.codigo
                    left join utm u on a.utm = u.codigo
                where a.status != 'c' group by s.utm";
    $result = mysql_query($query);
    $i = 1;
?>
<table>
    <thead>
        <tr>
            <th colspan="4">UTM</th>
        </tr>
        <tr>
            <th>Nome</th>
            <th>Novos</th>
            <th>Pendentes</th>
            <th>Concluídos</th>
        </tr>
    </thead>
    <tbody>
<?php
    while($d = mysql_fetch_object($result)){
?>
        <tr>
            <td><?=(utf8_encode($d->utm_nome)?:('NÃO IDENTIFICADO'))?></td>
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