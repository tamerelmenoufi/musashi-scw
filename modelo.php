<?php 
    $home = true; 
    include('includes/includes.php');

    $query = "select 
                    a.*,
                    s.nome as setor_nome,
                    t.nome as time_nome,
                    count(*) as qt
                from chamados a 
                    left join setores s on a.setor = s.codigo
                    left join time t on a.time = t.codigo
                where a.status != 'c' group by a.setor, a.time";
    $result = mysql_query($query);
    $i = 1;
?>
<table>
    <thead>
        <tr>
            <th>Setor</th>
            <th>Time de Atuação</th>
            <th>Quantidade</th>
        </tr>
    </thead>
    <tbody>
<?php
    while($d = mysql_fetch_object($result)){
?>
        <tr>
            <td><?=$d->setor_nome?></td>
            <td><?=$d->time_nome?></td>
            <td><?=$d->qt?></td>
        </tr>
<?php
    $i++;
    }
?>
    </tbody>
</table>