<?php
  
  include("../../includes/includes.php");
 
  ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////

  list($novos) = mysql_fetch_row(mysql_query("select count(*) from chamados where status = 'n' group by status"));
  list($atendidos) = mysql_fetch_row(mysql_query("select count(*) from chamados where status = 'c' group by status"));
  list($producao) = mysql_fetch_row(mysql_query("select count(*) from chamados where status = 'p' group by status"));

  list($operadores) = mysql_fetch_row(mysql_query("select count(*) from login where tipo = 'opr' group by tipo"));
  list($tecnicos) = mysql_fetch_row(mysql_query("select count(*) from login where tipo = 'tec' group by tipo"));
  

  $query = "select a.*, count(*) as qt, b.nome as motivos from chamados a left join motivos b on a.motivo = b.codigo where b.nome != '' group by a.motivo order by qt desc limit 10";
  $result = mysql_query($query);
  while($d = mysql_fetch_object($result)){
    $problema_rotulo[] = "'".utf8_encode($d->motivos)."'";
    $problema_qt[] = utf8_encode($d->qt);
  }



  for($i=1;$i<=12;$i++){

    list($m) = mysql_fetch_row(mysql_query("select count(*) from chamados where month(data_abertura) = '".$i."' and year(data_abertura) = '".date("Y")."' group by month(data_abertura)"));

    $meses[] = $m;

  }

?>
  <div class="row" style="margin-top: 20px;">

    <div class="col-md-4">
      <div class="alert alert-danger w3-center" role="alert" style="text-align: center;">
        <h5>NOVOS CHAMADOS</h5>
        <p><i class="fa fa-bullhorn fa-5x"></i></p>
        <hr>
        <h4 class="alert-heading"><?=($novos*1)?></h4>
      </div>        
    </div>

    <div class="col-md-4">
      <div class="alert alert-warning w3-center" role="alert" style="text-align: center;">
        <h5>CHAMADOS EM PRODUÇÃO</h5>
        <p><i class="fa fa-bullhorn fa-5x"></i></p>
        <hr>
        <h4 class="alert-heading"><?=($producao*1)?></h4>
      </div>
    </div>

    <div class="col-md-4">
      <div class="alert alert-success w3-center" role="alert" style="text-align: center;">
        <h5>CHAMADOS ATENDIDOS</h5>
        <p><i class="fa fa-bullhorn fa-5x"></i></p>
        <hr>
        <h4 class="alert-heading"><?=($atendidos*1)?></h4>
      </div>
    </div>
  </div>


  <div class="row">
    <div class="col-md-6">
        <canvas id="chamados"></canvas>
    </div>
    <div class="col-md-6">
        <canvas id="problemas"></canvas>
    </div>
  </div>








<?php
 
  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

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
  
  $_SESSION['relatorio_utm'] = $_POST['utm'];
  $_SESSION['relatorio_setor'] = $_POST['setor'];
  $_SESSION['relatorio_filtro_data1'] = $_POST['relatorio_filtro_data1'];
  $_SESSION['relatorio_filtro_data2'] = $_POST['relatorio_filtro_data2'];
  
}

if($_SESSION['relatorio_utm']){
  $where = " and b.utm = '{$_SESSION['relatorio_utm']}' ";
}
if($_SESSION['relatorio_setor']){
  $where .= " and a.setor = '{$_SESSION['relatorio_setor']}' ";
}
if($_SESSION['relatorio_filtro_data1']){
  $where .= " and a.data_abertura between '".dataMysql($_SESSION['relatorio_filtro_data1'])." 00:00:00' and '".(($_SESSION['relatorio_filtro_data2'])?dataMysql($_SESSION['relatorio_filtro_data2']):dataMysql($_SESSION['relatorio_filtro_data1']))." 23:59:59' ";
}



  $query = "select 
                  a.*,
                  b.utm,
                  c.nome as utm_nome,
                  b.nome as setor_nome
              from chamados a 
              left join setores b on a.setor = b.codigo 
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

  }


  foreach($relatorio['setor'] as $i => $v){
    $grafico_setor['legenda'][] = strtoupper(substr($i,0,2)).str_pad($j, 2, "0", STR_PAD_LEFT);
    $grafico_setor['nome'][] = $v['nome']*1;
    $grafico_setor['pendente'][] = $v['pendente']*1;
    $grafico_setor['concluido'][] = $v['concluido']*1;
    $grafico_setor['parada'][] = $v['parada']*1;
    $grafico_setor['producao'][] = $v['producao']*1;
  }

  foreach($relatorio['utm'] as $i => $v){
    $grafico_utm['legenda'][] = strtoupper(substr($i,0,2)).str_pad($j, 2, "0", STR_PAD_LEFT);
    $grafico_utm['nome'][] = $v['nome']*1;
    $grafico_utm['pendente'][] = $v['pendente']*1;
    $grafico_utm['concluido'][] = $v['concluido']*1;
    $grafico_utm['parada'][] = $v['parada']*1;
    $grafico_utm['producao'][] = $v['producao']*1;
  }
?>
<?php
/*
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
      
      if($i){
      $grafico_utm['legenda'][] = strtoupper(substr($i,0,2)).str_pad($j, 2, "0", STR_PAD_LEFT);
      $grafico_utm['nome'][] = $v['nome']*1;
      $grafico_utm['pendente'][] = $v['pendente']*1;
      $grafico_utm['concluido'][] = $v['concluido']*1;
      $grafico_utm['parada'][] = $v['parada']*1;
      $grafico_utm['producao'][] = $v['producao']*1;


?>
      <tr>
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
  }
?>
  </tbody>
</table>
<?php
//*/
?>
<canvas id="grafico_utm" style="margin-top:30px;"></canvas>
<?php
/*
?>
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
<?php
//*/
?>
<canvas id="grafico_setor" style="margin-top:30px;"></canvas>



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

<?php
///////////////////////////////////////////////////////////////////////////////////////////////

?>




  <div class="row" style="margin-top: 20px;">

    <div class="col-md-6">
      <div class="alert alert-primary w3-center" role="alert" style="text-align: center;">
        <p class="mb-0">TÉCNICOS</p>
        <p><i class="fa fa-users fa-5x"></i></p>
        <hr>
        <h4 class="alert-heading"><?=$tecnicos?></h4>
      </div>
    </div>

    <div class="col-md-6">
      <div class="alert alert-secondary w3-center" role="alert" style="text-align: center;">
        <p class="mb-0">OPERADORES</p>
        <p><i class="fa fa-users fa-5x"></i></p>
        <hr>
        <h4 class="alert-heading"><?=$operadores?></h4>
      </div>
    </div>

  </div>



<script type="text/javascript">

///////////////////////// Chamados ////////////////////////////////////////////////////////////
  var ctx = document.getElementById('chamados').getContext('2d');
  var data = [<?=implode(',',$meses)?>];
  var chart = new Chart(ctx, {
      // The type of chart we want to create
      type: 'bar',
  
      // The data for our dataset
      data: {
          labels: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul','Ago','Set','Out','Nov','Dez'],
          datasets: [{
              label: 'CHAMADOS REGISTRADOS',
              backgroundColor: 'blue',
              borderColor: 'blue',
              data: data
          }]
      },
  
      // Configuration options go here
    options: {
        scales: {
            yAxes: [{
                ticks: {
                    beginAtZero: true
                }
            }]
        }
    }
  });

  ///////////////////////// Problemas ////////////////////////////////////////////////////////////
  var ctx = document.getElementById('problemas').getContext('2d');
  var data = [<?=implode(',',$problema_qt)?>];
  var chart = new Chart(ctx, {
      // The type of chart we want to create
      type: 'bar',
  
      // The data for our dataset
      data: {
          labels: [<?=implode(',',$problema_rotulo)?>],
          datasets: [{
              label: 'PROBLEMAS',
              backgroundColor: 'red',
              borderColor: 'red',
              data: data
          }]
      },
  
      // Configuration options go here
    options: {
        scales: {
            yAxes: [{
                ticks: {
                    beginAtZero: true
                }
            }],
            xAxes: [{
              ticks: {
                    display: false
                }
            }]
        }
    }
  });


</script>