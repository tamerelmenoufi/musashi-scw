<?php
  
  include("../../includes/includes.php");
  
  


  list($novos) = mysql_fetch_row(mysql_query("select count(*) from chamados where status = 'n' group by status"));
  list($atendidos) = mysql_fetch_row(mysql_query("select count(*) from chamados where status = 'c' group by status"));
  list($producao) = mysql_fetch_row(mysql_query("select count(*) from chamados where status = 'p' group by status"));

  list($operadores) = mysql_fetch_row(mysql_query("select count(*) from login where tipo = 'opr' group by tipo"));
  list($tecnicos) = mysql_fetch_row(mysql_query("select count(*) from login where tipo = 'tec' group by tipo"));
  

  $query = "select a.*, count(*) as qt, b.nome as tipo_manutencao from chamados a left join tipos_manutencao b on a.tipo_manutencao = b.codigo group by a.tipo_manutencao order by qt desc limit 10";
  $result = mysql_query($query);
  while($d = mysql_fetch_object($result)){
    $problema_rotulo[] = "'".utf8_encode($d->tipo_manutencao)."'";
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
            }]
        }
    }
  });


</script>