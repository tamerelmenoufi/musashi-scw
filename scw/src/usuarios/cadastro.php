<?php
  include("../../includes/includes.php");

  if($_POST['acao'] == 'salvar'){

    for ($i = 0; $i < count($_POST['campo']); $i++) {
      $campos[] = $_POST['campo'][$i] . " = '".utf8_decode($_POST['valor'][$i])."'";
    }

    if($campos){
      $query = "update login set ".implode(", ",$campos)." where codigo = '".$_SESSION['scw_usuario_logado']."'";
    }
    mysql_query($query);
    exit();
  }


    $query = "select * from login where codigo = '".$_SESSION['scw_usuario_logado']."'";
    $result = mysql_query($query);
    $d = mysql_fetch_object($result);

?>

<style type="text/css">
  .oculto{
    color: #fff;
  }
</style>

<div voltar class="text-left" style="cursor: pointer;"> <i class="fa fa-angle-left" style="margin-right: 10px;"></i> voltar </div>

<h2>Dados Cadastrais</small></h2>
<form id="formCadastros">


  <div class="form-group">
    <label for="nome">Nome</label>
    <input form type="text" class="form-control" id="nome" aria-describedby="Nome" value="<?=utf8_encode($d->nome)?>">
  </div>

  <div class="form-group">
    <label for="login">Login</label>
    <input <?=(($d->login == 'admin')?false:'form')?> type="text" class="form-control" id="login" aria-describedby="Login" value="<?=utf8_encode($d->login)?>" <?=(($d->login == 'admin')?'disabled':false)?>>
  </div>


  <div class="form-group">
    <label for="senha">Senha</label>
    <div class="input-group mb-2">
      <input form type="text" class="form-control oculto" id="senha" placeholder="senha" value="<?=utf8_encode($d->senha)?>" autocomplete="off">
      <div class="input-group-prepend">
        <div class="input-group-text"><i ver_senha opc="o" class="fa fa-eye-slash" aria-hidden="true"></i></div>
      </div>
    </div>
  </div>


  <div class="form-group">
    <butron SalvarCadastro codigo="<?=$d->codigo?>" tipo="<?=$_SESSION['opc_tipo']?>" class="btn btn-primary">Salvar</butron>
  </div>


</form>

<script type="text/javascript">
  $(function(){


    $("i[ver_senha]").click(function(){
      opc = $(this).attr("opc");
      if(opc == 'o'){
        $(this).attr("opc",'v');
        $(this).removeClass("fa-eye-slash");
        $(this).addClass("fa-eye");
        $("#senha").removeClass("oculto");
      }else{
        $(this).attr("opc",'o');
        $(this).removeClass("fa-eye");
        $(this).addClass("fa-eye-slash");
        $("#senha").addClass("oculto");
      }
    });



    $("div[voltar]").click(function(){
      Carregando();
      $.ajax({
        url:"src/scripts/home.php",
        type:"GET",
        data:{
          tipo:"<?=$_SESSION['tipo']?>",
        },
        success:function(dados){
          $("#app").html(dados);
          Carregando('none');
        }
      });
    });

    $("butron[SalvarCadastro]").click(function(){
        Carregando();
        obj = $("#formCadastros");
        codigo = $(this).attr('codigo');
        GetForm(obj);
        $.ajax({
          url:"src/usuarios/form.php",
          type:"POST",
          data:{
            campo:campo,
            valor:valor,
            codigo:codigo,
            tipo:"<?=$_SESSION['tipo']?>",
            acao:'salvar',
          },
          success:function(dados){
            //$.alert({ content:dados });
            $.ajax({
              url:"src/scripts/home.php",
              success:function(dados){
                Carregando('none');
                $("#app").html(dados);
                JanelaAlerta();
              }
            });
            
          }
        });

    });

  })
</script>