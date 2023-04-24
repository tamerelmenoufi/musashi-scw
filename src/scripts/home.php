<?php
  include("../../includes/includes.php");

  $query = "select * from login where codigo = '".$_SESSION['scw_usuario_logado']."'";
  $result = mysql_query($query);
  $d = mysql_fetch_object($result);
?>

<style type="text/css">
  img[logo]{
    position: fixed;
    left: 20px;
    top:5px;
    height: 60px;
  }
</style>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
  <a class="navbar-brand" href="#" style="padding-left: 20px;"><!--<img logo src="img/logo.png" >--> SCW</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse justify-content-end">

    <ul class="navbar-nav">
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">Bem-vindo(a): <?=utf8_encode($d->nome)?></a>
        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuOffset">
          <a dados_cadastrais class="dropdown-item" href="#one"><i class="fa fa-user"></i> Dados Cadastrais</a>
          <div role="separator" class="dropdown-divider"></div>
          <a class="dropdown-item text-danger" sair><i class="fa fa-close"></i> Sair</a>
        </div>
      </li>
    </ul>
  </div>
</nav>


<div class="container-fluid">
  <div class="row">
    <nav id="navbarText" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
      <div class="sidebar-sticky pt-3">
        <ul class="nav flex-column">
          <?php
            $menu = array(
                      array(
                        'icone'  => 'fa-home',
                        'local'  => 'src/scripts/home.php',
                        'destino'  => '#app',
                        'titulo' => 'Página Principal',
                      ),
                    );

            for($i=0;$i<count($menu);$i++){
          ?>
          <li class="nav-item">
            <a menu class="nav-link" local="<?=$menu[$i]['local']?>" destino="<?=$menu[$i]['destino']?>" >
              <i class="fa <?=$menu[$i]['icone']?>"></i>
              <?=$menu[$i]['titulo']?>
            </a>
          </li>
          <?php
            }
          ?>
        </ul>



        <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
          <span>MANUTENÇÃO</span>
          <a class="d-flex align-items-center text-muted" href="#" aria-label="Add a new report">
            <i class="fa fa-cogs" aria-hidden="true"></i>
          </a>
        </h6>

        <ul class="nav flex-column">
          <?php

            $menu = array(
                      array(
                        'icone'  => 'fa-cogs',
                        'local'  => 'src/usuarios/index.php?tipo=tec',
                        'destino'  => 'main',
                        'titulo' => 'Técnicos',
                        'permissao' => 'tecnicos',
                      ),
                      array(
                        'icone'  => 'fa-cogs',
                        'local'  => 'src/usuarios/index.php?tipo=opr',
                        'destino'  => 'main',
                        'titulo' => 'Operadores',
                        'permissao' => 'operadores',
                      ),
                      array(
                        'icone'  => 'fa-cogs',
                        'local'  => 'src/maquinas/index.php',
                        'destino'  => 'main',
                        'titulo' => 'Máquinas',
                        'permissao' => 'maquinas',
                      ),
                      array(
                        'icone'  => 'fa-cogs',
                        'local'  => 'src/setores/index.php',
                        'destino'  => 'main',
                        'titulo' => 'Setores',
                        'permissao' => 'auxiliares',
                      ),
                      array(
                        'icone'  => 'fa-cogs',
                        'local'  => 'src/problemas/index.php',
                        'destino'  => 'main',
                        'titulo' => 'Problemas',
                        'permissao' => 'auxiliares',
                      ),
                      array(
                        'icone'  => 'fa-cogs',
                        'local'  => 'src/time/index.php',
                        'destino'  => 'main',
                        'titulo' => 'Time de Atuação',
                        'permissao' => 'auxiliares',
                      ),
                      array(
                        'icone'  => 'fa-cogs',
                        'local'  => 'src/motivos/index.php',
                        'destino'  => 'main',
                        'titulo' => 'Motivos',
                        'permissao' => 'auxiliares',
                      ),
                    );

            for($i=0;$i<count($menu);$i++){

              if(in_array($menu[$i]['permissao'], $_SESSION['scw_usuario_permissoes']) and ($_SESSION['scw_usuario_perfil'] == 'adm' or $menu[$i]['permissao'] != 'auxiliares')){

          ?>
          <li class="nav-item">
            <a menu class="nav-link" local="<?=$menu[$i]['local']?>" destino="<?=$menu[$i]['destino']?>" >
              <i class="fa <?=$menu[$i]['icone']?>"></i>
              <?=$menu[$i]['titulo']?>
            </a>
          </li>
          <?php
              }
            }
          ?>
        </ul>


        <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
          <span>CHAMADOS</span>
          <a class="d-flex align-items-center text-muted" href="#" aria-label="Add a new report">
            <i class="fa fa-bullhorn" aria-hidden="true"></i>
          </a>
        </h6>

        <ul class="nav flex-column mb-2">

          <?php

            $menu = array(
                      array(
                        'icone'  => 'fa-bullhorn',
                        'local'  => 'src/chamados/index.php?status=t',
                        'destino'  => 'main',
                        'titulo' => 'Chamados',
                        'permissao' => 'todos',
                      ),
                      array(
                        'icone'  => 'fa-bullhorn',
                        'local'  => 'src/chamados/index.php?status=n',
                        'destino'  => 'main',
                        'titulo' => 'Novos',
                        'permissao' => 'novos',
                      ),
                      array(
                        'icone'  => 'fa-bullhorn',
                        'local'  => 'src/chamados/index.php?status=p',
                        'destino'  => 'main',
                        'titulo' => 'Em produção',
                        'permissao' => 'producao',
                      ),
                      array(
                        'icone'  => 'fa-bullhorn',
                        'local'  => 'src/chamados/index.php?status=c',
                        'destino'  => 'main',
                        'titulo' => 'Concluídos',
                        'permissao' => 'concluidos',
                      ),
                    );

            for($i=0;$i<count($menu);$i++){
              if(in_array($menu[$i]['permissao'], $_SESSION['scw_usuario_permissoes'])){
          ?>
          <li class="nav-item">
            <a menu class="nav-link" local="<?=$menu[$i]['local']?>" destino="<?=$menu[$i]['destino']?>" >
              <i class="fa <?=$menu[$i]['icone']?>"></i>
              <?=$menu[$i]['titulo']?>
            </a>
          </li>
          <?php
              }
            }
          ?>
        </ul>
      </div>
    </nav>
    <main class="col-md-9 ml-sm-auto col-lg-10 px-md-4"></main>
  </div>
</div>

<script type="text/javascript">
  var home = "src/scripts/";
  $(function(){

    $("main").load("src/scripts/dashboard.php");

    $("a[dados_cadastrais]").click(function(){
      Carregando();
      $.ajax({
        url:"src/usuarios/cadastro.php",
        success:function(dados){
          $("main").html(dados);
          Carregando('none')
        }
      });
    });

    $("a[sair]").click(function(){
      Carregando();
      $.ajax({
        url:"src/login/login.php?s=1",
        success:function(dados){
          $("#app").html(dados);
          Carregando('none')
        }
      });
    });

    $("a[menu]").click(function(){
      local = $(this).attr("local");
      destino = $(this).attr("destino");
      Carregando();
      $.ajax({
        url:local,
        success:function(dados){
          $(destino).html(dados);
          Carregando('none')
        }
      });
    });

  })
</script>