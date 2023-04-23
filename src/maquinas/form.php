<?php
  include("../../includes/includes.php");

  if($_POST['acao'] == 'salvar'){

    for ($i = 0; $i < count($_POST['campo']); $i++) {
      $campos[] = $_POST['campo'][$i] . " = '".utf8_decode($_POST['valor'][$i])."'";
      if($_POST['campo'][$i] == 'nome') $nome = utf8_decode($_POST['valor'][$i]);
    }

    if($_POST['codigo'] and $campos){
      $query = "update maquinas set ".implode(", ",$campos)." where codigo = '".$_POST['codigo']."'";
    }else if($campos){
        
      $n = mysql_num_rows(mysql_query("select * from maquinas where nome = '".$nome."'"));
        
      $query = "insert into maquinas set ".implode(", ",$campos);
    }
    
    mysql_query($query);

    if($n){
        echo "erro"; exit();
    }

    exit();
  }


  if($_GET['codigo']){
    $query = "select * from maquinas where codigo = '".$_GET['codigo']."'";
    $result = mysql_query($query);
    $d = mysql_fetch_object($result);
  }

?>

<style type="text/css">
  .oculto{
    color: #fff;
  }
</style>

<div voltar class="text-left" style="cursor: pointer;"> <i class="fa fa-angle-left" style="margin-right: 10px;"></i> voltar </div>

<h2>Máquinas</small></h2>
<form id="formCadastros">

  <div class="form-group">
    <label for="cc">CC (Centro de Custo)</label>
    <input form type="text" class="form-control" id="cc" aria-describedby="CC" value="<?=utf8_encode($d->cc)?>">
  </div>

  <div class="form-group">
    <label for="nome">Máquina</label>
    <input form type="text" class="form-control" id="nome" aria-describedby="Nome" value="<?=utf8_encode($d->nome)?>">
  </div>

  <div class="form-group">
    <label for="login">Tipo</label>
    <input form type="text" class="form-control" id="tipo" aria-describedby="Tipo" value="<?=utf8_encode($d->tipo)?>" >
  </div>


  <div class="form-group">
    <butron SalvarCadastro codigo="<?=$d->codigo?>" tipo="<?=$_SESSION['opc_tipo']?>" class="btn btn-primary">Salvar</butron>
  </div>


</form>

<script type="text/javascript">
  $(function(){

    $("div[voltar]").click(function(){
      Carregando();
      $.ajax({
        url:"src/maquinas/index.php",
        success:function(dados){
          $("main").html(dados);
          Carregando('none');
        }
      });
    });

    $("butron[SalvarCadastro]").click(function(){

        obj = $("#formCadastros");
        codigo = $(this).attr('codigo');
        GetForm(obj);

        valida = false;
        for(i=0;i<valor.length;i++){
          if(!valor[i]){
            valida = true;
          }
        }

        if(!valida){
            Carregando();
            $.ajax({
              url:"src/maquinas/form.php",
              type:"POST",
              data:{
                campo:campo,
                valor:valor,
                codigo:codigo,
                acao:'salvar',
              },
              success:function(dados){
                //$.alert({ content:dados });
                if(dados == 'erro'){
                    
                    Carregando('none');
                    JanelaAlertaErro();
                    
                }else{
                
                    $.ajax({
                      url:"src/maquinas/index.php",
                      success:function(dados){
                        Carregando('none');
                        $("main").html(dados);
                        JanelaAlerta();
                      }
                    });
                }
              }
            });
         }else{

            $.alert({
              content:"Favor preencha os campos para realizar o cadastro!",
              title:false,
            })

        }
    });

  })
</script>