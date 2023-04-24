<?php
  include("../../includes/includes.php");

  if($_POST['acao'] == 'salvar'){

    for ($i = 0; $i < count($_POST['campo']); $i++) {
      $campos[] = $_POST['campo'][$i] . " = '".utf8_decode($_POST['valor'][$i])."'";
      if($_POST['campo'][$i] == 'nome') $nome = utf8_decode($_POST['valor'][$i]);
    }

    if($_POST['codigo'] and $campos){
      $query = "update motivos set ".implode(", ",$campos)." where codigo = '".$_POST['codigo']."'";
    }else if($campos){

      $n = mysql_num_rows(mysql_query("select * from motivos where nome = '".$nome."'"));

      $query = "insert into motivos set ".implode(", ",$campos);
    }


    if($n){
        echo "erro"; exit();
    }else{
      mysql_query($query);
    }

    exit();
  }


  if($_GET['codigo']){
    $query = "select * from motivos where codigo = '".$_GET['codigo']."'";
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

<h2>Motivos (Chamados)</small></h2>
<form id="formCadastros">

  <div class="form-group">
    <label for="nome">Motivo</label>
    <input form type="text" class="form-control" id="nome" aria-describedby="Nome" value="<?=utf8_encode($d->nome)?>">
  </div>

  <div class="form-group">
    <label for="competencia">Competência (Time)</label>
    <select form id="competencia" class="form-control">
      <option value="">:: Selecione o time ::</option>
      <?php
        $q = "select * from time order by nome";
        $r = mysql_query($q);
        while($s = mysql_fetch_object($r)){
      ?>
      <option value="<?=$s->codigo?>" <?=(($s->codigo == $d->competencia)?'selected':false)?>><?=utf8_encode($s->nome)?></option>
      <?php
        }
      ?>
    </select>
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
        url:"src/motivos/index.php",
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
              url:"src/motivos/form.php",
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
                      url:"src/motivos/index.php",
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