<?php
  include("../../includes/includes.php");

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
    
    $_SESSION['relatorio_utm'] = $_POST['utm'];
    $_SESSION['relatorio_setor'] = $_POST['setor'];
    $_SESSION['relatorio_filtro_data1'] = $_POST['relatorio_filtro_data1'];
    $_SESSION['relatorio_filtro_data2'] = $_POST['relatorio_filtro_data2'];
    
  }



?>

<h3>Relatório</h3>

<div class="input-group mb-3">

    <div class="input-group-prepend">
        <span class="input-group-text">Busca</span>
    </div>

    <div class="input-group-prepend">
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
    </select>

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
    $query = "select a.*, b.utm, c.nome as utm_nome, b.nome as setor_nome from chamados a left join setores b on a.setor = b.codigo left join utm c on b.utm = c.codigo order by a.data_abertura desc limit 0,100";
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

    }
?>
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
    foreach($relatorio['utm'] as $i => $v){

?>
        <tr>
            <td><?=$i?></td>
            <td><?=$v['nome']*1?></td>
            <td><?=$v['pendente']*1?></td>
            <td><?=$v['concluido']*1?></td>
            <td><?=$v['parada']*1?></td>
            <td><?=$v['producao']*1?></td>
        </tr>
        
<?php
    }
?>
    </tbody>
</table>

<canvas id="grafico_utm"></canvas>

<table class="table mt-3">
    <thead>
        <tr>
            <th>Setor</th>
            <th>Quantidade</th>
            <th>Conclídos</th>
            <th>Pendentes</th>
            <th>Máquinas Paradas</th>
            <th>Máquinas Em Produção</th>
        </tr>
    </thead>
    <tbody>

<?php
    arsort($relatorio['setor']);
    foreach($relatorio['setor'] as $i => $v){
?>
        <tr>
            <td><?=$i?></td>
            <td><?=$v['nome']*1?></td>
            <td><?=$v['pendente']*1?></td>
            <td><?=$v['concluido']*1?></td>
            <td><?=$v['parada']*1?></td>
            <td><?=$v['producao']*1?></td>
        </tr>
        
<?php
    }
?>
    </tbody>
</table>



<script>


    ///////////////////////// Chamados ////////////////////////////////////////////////////////////
    var ctx = document.getElementById('grafico_utm').getContext('2d');
    
    var chart = new Chart(ctx, {
        // The type of chart we want to create
        type: 'bar',
    
        // The data for our dataset
        data: {
            labels: [
                <?php
                    foreach($relatorio['setor'] as $i => $v){
                ?>
                        '<?=$i?>',    
                <?php
                    }
                ?>
                ],
            datasets: [

                <?php
                    foreach($relatorio['setor'] as $i => $v){
                ?>
                {
                    label: '<?=$i?>',
                    backgroundColor: 'blue',
                    borderColor: 'blue',
                    data: [<?=Legenda($v['nome'])?>, <?=$v['pendente']?>, <?=$v['concluido']?>, <?=$v['parado']?>, <?=$v['producao']?>]
                },   
                <?php
                    }
                ?>

            ]
        },
    
        // Configuration options go here
        options: {
            indexAxis: 'y',
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true
                    }
                }]
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