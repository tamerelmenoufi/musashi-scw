<?php
  include("../../includes/includes.php");

  $md5 = md5(date("YmdHis"));

  function Legenda($opc){

    $d = [
        'nome' => 'Quantidade',
        'pendente' => 'Pendente',
        'concluido' => 'Concluido',
        'parada' => 'Parada',
        'producao' => 'Em Produção',
    ];

    return $d[$opc];

  }

  if($_POST['limpar']){
    $_SESSION['relatorio_utm'] = [];
    $_SESSION['relatorio_setor'] = [];
    $_SESSION['relatorio_filtro_data1'] = [];
    $_SESSION['relatorio_filtro_data2'] = [];
  }


  if($_POST['acao'] == 'filtra_setor'){
    $q = "select * from setores where utm = '{$_POST['utm']}' order by nome";
    $r = mysql_query($q);
    echo "<option value=''>:: Geral ::</option>";
    while($s = mysql_fetch_object($r)){
        echo "<option value='{$s->codigo}'>".utf8_encode($s->nome)."</option>";
    }
    exit();
  }


  if($_POST['acao'] == 'filtro'){
    
    // $_SESSION['relatorio_utm'] = $_POST['utm'];
    // $_SESSION['relatorio_setor'] = $_POST['setor'];
    $_SESSION['relatorio_filtro_data1'] = $_POST['relatorio_filtro_data1'];
    $_SESSION['relatorio_filtro_data2'] = $_POST['relatorio_filtro_data2'];
    
  }

//   if($_SESSION['relatorio_utm']){
//     $where = " and b.utm = '{$_SESSION['relatorio_utm']}' ";
//   }
//   if($_SESSION['relatorio_setor']){
//     $where .= " and a.setor = '{$_SESSION['relatorio_setor']}' ";
//   }
  if($_SESSION['relatorio_filtro_data1']){
    $where .= " and a.data_abertura between '".dataMysql($_SESSION['relatorio_filtro_data1'])." 00:00:00' and '".(($_SESSION['relatorio_filtro_data2'])?dataMysql($_SESSION['relatorio_filtro_data2']):dataMysql($_SESSION['relatorio_filtro_data1']))." 23:59:59' ";
  }


?>

<h3>Relatório</h3>

<div class="input-group mb-3">

    <div class="input-group-prepend">
        <span class="input-group-text">Busca</span>
    </div>

    <!-- <div class="input-group-prepend">
        <span class="input-group-text">UTM</span>
    </div>
    <select class="form-control" id="utm">
        <option value="">:: Geral ::</option>
        <?php
        $q = "select * from utm order by nome";
        $r = mysql_query($q);
        while($s = mysql_fetch_object($r)){
        ?>
        <option value="<?=$s->codigo?>" <?=(($s->codigo == $_SESSION['relatorio_utm'])?'selected':false)?>><?=utf8_encode($s->nome)?></option>
        <?php
        }
        ?>
    </select>

    <div class="input-group-prepend">
        <span class="input-group-text">Setor</span>
    </div>
    <select class="form-control" id="setor">
        <option value="">:: Geral ::</option>
        <?php
        $q = "select * from setores where utm = '{$_SESSION['relatorio_utm']}' order by nome";
        $r = mysql_query($q);
        while($s = mysql_fetch_object($r)){
        ?>
        <option value="<?=$s->codigo?>" <?=(($s->codigo == $_SESSION['relatorio_setor'])?'selected':false)?>><?=utf8_encode($s->nome)?></option>
        <?php
        }
        ?>
    </select> -->

    <div class="input-group-prepend">
        <span class="input-group-text">Data Ini</span>
    </div>
    <input type="text" id="relatorio_filtro_data1" value="<?=$_SESSION['relatorio_filtro_data1']?>" class="form-control" placeholder="Data Inicial" aria-label="Data Inicial">

    <div class="input-group-prepend">
        <span class="input-group-text">Data Fim</span>
    </div>
    <input type="text" id="relatorio_filtro_data2" value="<?=$_SESSION['relatorio_filtro_data2']?>" class="form-control" placeholder="Data Final" aria-label="Data Final">


    <div class="input-group-append">
    <button buscar class="btn btn-success"><i class="fa fa-search"></i> Buscar</button>
    <button limpar_relatorio class="btn btn-danger"><i class="fa fa-eraser"></i> Limpar</button>
    </div>
</div>


<?php
    $query = "select 
                    a.*,
                    b.utm,
                    c.nome as utm_nome,
                    b.nome as setor_nome,
                    t.nome as time_nome,
                    m.nome as maquina_nome
                from chamados a 
                left join setores b on a.setor = b.codigo 
                left join time t on a.time = t.codigo 
                left join maquinas m on a.maquina = m.codigo 
                left join utm c on b.utm = c.codigo 
            where 1 = 1 {$where}
            order by a.data_abertura desc";
    $result = mysql_query($query);
    $relatorio = [];
    while($d = mysql_fetch_object($result)){


        $relatorio['utm'][utf8_encode($d->utm_nome)]['nome'] = ($relatorio['utm'][utf8_encode($d->utm_nome)]['nome'] + 1);
        if($d->status != 'c'){
        $relatorio['utm'][utf8_encode($d->utm_nome)]['pendente'] = ($relatorio['utm'][utf8_encode($d->utm_nome)]['pendente'] + 1);
        }else{
        $relatorio['utm'][utf8_encode($d->utm_nome)]['concluido'] = ($relatorio['utm'][utf8_encode($d->utm_nome)]['concluido'] + 1);
        }
        if($d->parada == 's'){
        $relatorio['utm'][utf8_encode($d->utm_nome)]['parada'] = ($relatorio['utm'][utf8_encode($d->utm_nome)]['parada'] + 1);
        }else{
        $relatorio['utm'][utf8_encode($d->utm_nome)]['producao'] = ($relatorio['utm'][utf8_encode($d->utm_nome)]['producao'] + 1);    
        }
        



        $relatorio['setor'][utf8_encode($d->setor_nome)]['nome'] = ($relatorio['setor'][utf8_encode($d->setor_nome)]['nome'] + 1);
        if($d->status != 'c'){
        $relatorio['setor'][utf8_encode($d->setor_nome)]['pendente'] = ($relatorio['setor'][utf8_encode($d->setor_nome)]['pendente'] + 1);
        }else{
        $relatorio['setor'][utf8_encode($d->setor_nome)]['concluido'] = ($relatorio['setor'][utf8_encode($d->setor_nome)]['concluido'] + 1);
        }
        if($d->parada == 's'){
        $relatorio['setor'][utf8_encode($d->setor_nome)]['parada'] = ($relatorio['setor'][utf8_encode($d->setor_nome)]['parada'] + 1);
        }else{
        $relatorio['setor'][utf8_encode($d->setor_nome)]['producao'] = ($relatorio['setor'][utf8_encode($d->setor_nome)]['producao'] + 1);    
        }


        $relatorio['time'][utf8_encode($d->time_nome)]['nome'] = ($relatorio['time'][utf8_encode($d->time_nome)]['nome'] + 1);
        if($d->status != 'c'){
        $relatorio['time'][utf8_encode($d->time_nome)]['pendente'] = ($relatorio['time'][utf8_encode($d->time_nome)]['pendente'] + 1);
        }else{
        $relatorio['time'][utf8_encode($d->time_nome)]['concluido'] = ($relatorio['time'][utf8_encode($d->time_nome)]['concluido'] + 1);
        }
        if($d->parada == 's'){
        $relatorio['time'][utf8_encode($d->time_nome)]['parada'] = ($relatorio['time'][utf8_encode($d->time_nome)]['parada'] + 1);
        }else{
        $relatorio['time'][utf8_encode($d->time_nome)]['producao'] = ($relatorio['time'][utf8_encode($d->time_nome)]['producao'] + 1);    
        }


        $relatorio['maquina'][utf8_encode($d->maquina_nome)]['nome'] = ($relatorio['maquina'][utf8_encode($d->maquina_nome)]['nome'] + 1);
        if($d->status != 'c'){
        $relatorio['maquina'][utf8_encode($d->maquina_nome)]['pendente'] = ($relatorio['maquina'][utf8_encode($d->maquina_nome)]['pendente'] + 1);
        }else{
        $relatorio['maquina'][utf8_encode($d->maquina_nome)]['concluido'] = ($relatorio['maquina'][utf8_encode($d->maquina_nome)]['concluido'] + 1);
        }
        if($d->parada == 's'){
        $relatorio['maquina'][utf8_encode($d->maquina_nome)]['parada'] = ($relatorio['maquina'][utf8_encode($d->maquina_nome)]['parada'] + 1);
        }else{
        $relatorio['maquina'][utf8_encode($d->maquina_nome)]['producao'] = ($relatorio['maquina'][utf8_encode($d->maquina_nome)]['producao'] + 1);    
        }




    }
?>
<h3 class="mt-3">Representação das UTM's</h3>
<table class="table">
    <thead>
        <tr>
            <th>UTM</th>
            <th>Quantidade</th>
            <th>Conclídos</th>
            <th>Pendentes</th>
            <th>Máquinas Paradas</th>
            <th>Máquinas Em Produção</th>
        </tr>
    </thead>
    <tbody>

<?php
    arsort($relatorio['utm']);
    $j = 1;
    foreach($relatorio['utm'] as $i => $v){
        
        // if($i){
        $grafico_utm['legenda'][] = strtoupper(substr($i,0,2)).str_pad($j, 2, "0", STR_PAD_LEFT);
        $grafico_utm['nome'][] = $v['nome']*1;
        $grafico_utm['pendente'][] = $v['pendente']*1;
        $grafico_utm['concluido'][] = $v['concluido']*1;
        $grafico_utm['parada'][] = $v['parada']*1;
        $grafico_utm['producao'][] = $v['producao']*1;


?>
        <tr>
            <td><?=(($i)?:"Não Identificado")?></td>
            <td><?=$v['nome']*1?></td>
            <td><?=$v['concluido']*1?></td>
            <td><?=$v['pendente']*1?></td>
            <td><?=$v['parada']*1?></td>
            <td><?=$v['producao']*1?></td>
        </tr>
        
<?php
    $j++;
        // }
    }
?>
    </tbody>
</table>

<!-- <canvas id="grafico_utm" style="margin-top:30px;"></canvas> -->

<h3 class="mt-3">Representação dos Setores</h3>
<table class="table">
    <thead>
        <tr>
            <th>Legenda</th>
            <th>Setor</th>
            <th>Geral</th>
            <th>Concluidos</th>
            <th>Pendentes</th>
            <th>Máquinas Paradas</th>
            <th>Máquinas Em Produção</th>
        </tr>
    </thead>
    <tbody>

<?php
    arsort($relatorio['setor']);
    $j = 1;
    foreach($relatorio['setor'] as $i => $v){
        $grafico_setor['legenda'][] = strtoupper(substr($i,0,2)).str_pad($j, 2, "0", STR_PAD_LEFT);
        $grafico_setor['nome'][] = $v['nome']*1;
        $grafico_setor['pendente'][] = $v['pendente']*1;
        $grafico_setor['concluido'][] = $v['concluido']*1;
        $grafico_setor['parada'][] = $v['parada']*1;
        $grafico_setor['producao'][] = $v['producao']*1;
?>
        <tr>
            <td><?=strtoupper(substr($i,0,2)).str_pad($j, 2, "0", STR_PAD_LEFT)?></td>
            <td><?=$i?></td>
            <td><?=$v['nome']*1?></td>
            <td><?=$v['concluido']*1?></td>
            <td><?=$v['pendente']*1?></td>
            <td><?=$v['parada']*1?></td>
            <td><?=$v['producao']*1?></td>
        </tr>
        
<?php
$j++;
    }
?>
    </tbody>
</table>

<!-- <canvas id="grafico_setor" style="margin-top:30px;"></canvas> -->




<h3 class="mt-3">Representação dos Times de Atuação</h3>
<table class="table">
    <thead>
        <tr>
            <th>Legenda</th>
            <th>Time</th>
            <th>Geral</th>
            <th>Concluidos</th>
            <th>Pendentes</th>
            <th>Máquinas Paradas</th>
            <th>Máquinas Em Produção</th>
        </tr>
    </thead>
    <tbody>

<?php
    arsort($relatorio['time']);
    $j = 1;
    foreach($relatorio['time'] as $i => $v){
        $grafico_time['legenda'][] = strtoupper(substr($i,0,2)).str_pad($j, 2, "0", STR_PAD_LEFT);
        $grafico_time['nome'][] = $v['nome']*1;
        $grafico_time['pendente'][] = $v['pendente']*1;
        $grafico_time['concluido'][] = $v['concluido']*1;
        $grafico_time['parada'][] = $v['parada']*1;
        $grafico_time['producao'][] = $v['producao']*1;
?>
        <tr>
            <td><?=strtoupper(substr($i,0,2)).str_pad($j, 2, "0", STR_PAD_LEFT)?></td>
            <td><?=$i?></td>
            <td><?=$v['nome']*1?></td>
            <td><?=$v['concluido']*1?></td>
            <td><?=$v['pendente']*1?></td>
            <td><?=$v['parada']*1?></td>
            <td><?=$v['producao']*1?></td>
        </tr>
        
<?php
$j++;
    }
?>
    </tbody>
</table>

<!-- <canvas id="grafico_time" style="margin-top:30px;"></canvas> -->




<h3 class="mt-3">Representação das Máquinas</h3>
<table class="table">
    <thead>
        <tr>
            <th>Legenda</th>
            <th>Máquina</th>
            <th>Geral</th>
            <th>Concluidos</th>
            <th>Pendentes</th>
            <th>Máquinas Paradas</th>
            <th>Máquinas Em Produção</th>
        </tr>
    </thead>
    <tbody>

<?php
    arsort($relatorio['maquina']);
    $j = 1;
    foreach($relatorio['maquina'] as $i => $v){
        $grafico_maquina['legenda'][] = strtoupper(substr($i,0,2)).str_pad($j, 2, "0", STR_PAD_LEFT);
        $grafico_maquina['nome'][] = $v['nome']*1;
        $grafico_maquina['pendente'][] = $v['pendente']*1;
        $grafico_maquina['concluido'][] = $v['concluido']*1;
        $grafico_maquina['parada'][] = $v['parada']*1;
        $grafico_maquina['producao'][] = $v['producao']*1;
?>
        <tr>
            <td><?=strtoupper(substr($i,0,2)).str_pad($j, 2, "0", STR_PAD_LEFT)?></td>
            <td><?=$i?></td>
            <td><?=$v['nome']*1?></td>
            <td><?=$v['concluido']*1?></td>
            <td><?=$v['pendente']*1?></td>
            <td><?=$v['parada']*1?></td>
            <td><?=$v['producao']*1?></td>
        </tr>
        
<?php
$j++;
    }
?>
    </tbody>
</table>

<!-- <canvas id="grafico_maquina" style="margin-top:30px;"></canvas> -->



<script>


    ///////////////////////// Grafico ////////////////////////////////////////////////////////////


    new Chart("grafico_setor", {
        type: "horizontalBar",
        data: {
            labels: ['<?=implode("', '", $grafico_setor['legenda'])?>'],
            datasets: [{
            label: 'Geral',
            data: [<?=implode(", ", $grafico_setor['nome'])?>],
            borderColor: "blue",
            backgroundColor:"rgb(2, 62, 198, 0.7)",
            fill: false
            },{
            label: 'Concluidos',
            data: [<?=implode(", ", $grafico_setor['concluido'])?>],
            borderColor: "green",
            backgroundColor:"rgb(1, 174, 50, 0.7)",
            fill: false
            },{
            label: 'Pendentes',
            data: [<?=implode(", ", $grafico_setor['pendente'])?>],
            borderColor: "gray",
            backgroundColor:"rgb(116, 116, 116, 0.7)",
            fill: false
            },{
            label: 'Máquinas Paradas',
            data: [<?=implode(", ", $grafico_setor['parada'])?>],
            borderColor: "red",
            backgroundColor:"rgb(200, 3, 54, 0.7)",
            fill: false
            },{
            label: 'Máquinas Em Produção',
            data: [<?=implode(", ", $grafico_setor['producao'])?>],
            borderColor: "orange",
            backgroundColor:"rgb(247, 152, 2, 0.7)",
            fill: false
            }]
        },
        options: {
            legend: {display: false},
            title: {
                display: true,
                text: "Gráfico de Representação dos Setores",
                fontSize: 16
            }
        }
    });
    


///////////////////////// Grafico ////////////////////////////////////////////////////////////


new Chart("grafico_utm", {
    type: "horizontalBar",
    data: {
        labels: ['<?=implode("', '", $grafico_utm['legenda'])?>'],
        datasets: [{
        label: 'Geral',
        data: [<?=implode(", ", $grafico_utm['nome'])?>],
        borderColor: "blue",
        backgroundColor:"rgb(2, 62, 198, 0.7)",
        fill: false
        },{
        label: 'Concluidos',
        data: [<?=implode(", ", $grafico_utm['concluido'])?>],
        borderColor: "green",
        backgroundColor:"rgb(1, 174, 50, 0.7)",
        fill: false
        },{
        label: 'Pendentes',
        data: [<?=implode(", ", $grafico_utm['pendente'])?>],
        borderColor: "gray",
        backgroundColor:"rgb(116, 116, 116, 0.7)",
        fill: false
        },{
        label: 'Máquinas Paradas',
        data: [<?=implode(", ", $grafico_utm['parada'])?>],
        borderColor: "red",
        backgroundColor:"rgb(200, 3, 54, 0.7)",
        fill: false
        },{
        label: 'Máquinas Em Produção',
        data: [<?=implode(", ", $grafico_utm['producao'])?>],
        borderColor: "orange",
        backgroundColor:"rgb(247, 152, 2, 0.7)",
        fill: false
        }]
    },
    options: {
        legend: {display: false},
        title: {
            display: true,
            text: "Gráfico de Representação das UTM's",
            fontSize: 16
        }
    }
});



    $(function(){

        $("#relatorio_filtro_data1, #relatorio_filtro_data2").mask("99/99/9999");

        $("#utm").change(function(){
            utm = $(this).val();
            $.ajax({
                url:"src/relatorios/index.php",
                type:"POST",
                data:{
                    utm,
                    acao:'filtra_setor',
                },
                success:function(dados){
                    $("#setor").html(dados);
                }
            });
        })

        $("button[buscar]").click(function(){
            utm = $("#utm").val();
            setor = $("#setor").val();
            relatorio_filtro_data1 = $("#relatorio_filtro_data1").val();
            relatorio_filtro_data2 = $("#relatorio_filtro_data2").val();
            Carregando();
            $.ajax({
                url:"src/relatorios/index.php",
                type:"POST",
                data:{
                    utm,
                    setor,
                    relatorio_filtro_data1,
                    relatorio_filtro_data2,           
                    acao:'filtro'         
                },
                success:function(dados){
                    $("main").html(dados);
                    Carregando('none');
                }
            });
        })

        $("button[limpar_relatorio]").click(function(){
            Carregando();
            $.ajax({
                url:"src/relatorios/index.php",
                type:"POST",
                data:{
                    limpar:1,
                },
                success:function(dados){
                    $("main").html(dados);
                    Carregando('none');
                }
            });
        })


    })
</script>