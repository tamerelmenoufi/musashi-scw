<?php
  include("../../includes/includes.php");

  if($_POST['acao'] == 'salvar'){

    for ($i = 0; $i < count($_POST['campo']); $i++) {
      $campos[] = $_POST['campo'][$i] . " = '".utf8_decode($_POST['valor'][$i])."'";
    }

    if($_POST['codigo'] and $campos){
      $query = "update notificacoes set ".implode(", ",$campos)." where codigo = '".$_POST['codigo']."'";
    }else if($campos){
      $query = "insert into notificacoes set ".implode(", ",$campos);
    }


    mysql_query($query);

    exit();
  }


  if($_GET['codigo']){
    $query = "select * from notificacoes where codigo = '".$_GET['codigo']."'";
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

<h2>Notificações</small></h2>
<form id="formCadastros">

  <div class="form-group">
    <label for="nome">Nome</label>
    <input form type="text" class="form-control" id="nome" aria-describedby="Nome" value="<?=utf8_encode($d->nome)?>">
  </div>

  <div class="form-group">
    <label for="email">E-mail</label>
    <input form type="text" class="form-control" id="email" aria-describedby="E-mail" value="<?=utf8_encode($d->email)?>">
  </div>

  <div class="form-group">
    <label for="telefone">Telefone</label>
    <input form type="text" class="form-control" id="telefone" aria-describedby="Telefone" value="<?=utf8_encode($d->telefone)?>">
  </div>


  <!-- <div class="form-group">
    <label for="time">Time</label>
    <select form id="time" class="form-control">
      <option value="">:: Selecione o time ::</option>
      <?php
        $q = "select * from time order by nome";
        $r = mysql_query($q);
        while($s = mysql_fetch_object($r)){
      ?>
      <option value="<?=$s->codigo?>" <?=(($s->codigo == $d->time)?'selected':false)?>><?=utf8_encode($s->nome)?></option>
      <?php
        }
      ?>
    </select>
  </div> -->


    <?php
      $q = "select * from time order by nome";
      $r = mysql_query($q);
      while($s = mysql_fetch_object($r)){
    ?>
    <div class="accordion" id="accordionTime" style="margin-bottom:20px;">
      <div class="card">
        <div class="card-header" id="heading<?=$s->codigo?>">
            <div class="form-group form-check" XXdata-toggle="collapse" XXdata-target="#collapse<?=$s->codigo?>" XXaria-expanded="true" XXaria-controls="collapse<?=$s->codigo?>">
              <input type="checkbox" class="form-check-input" time="<?=$s->codigo?>" id="time<?=$s->codigo?>">
              <label class="form-check-label" for="time<?=$s->codigo?>"><?=utf8_encode($s->nome)?></label>
            </div>
        </div>

        <div XXid="collapse<?=$s->codigo?>" XXclass="collapse" XXaria-labelledby="heading<?=$s->codigo?>" XXdata-parent="#accordionTime">
          <div class="card-body">
            <ul>
            <?php
            $q1 = "select * from motivos where competencia = '{$s->codigo}' order by codigo";
            $r1 = mysql_query($q1);
            while($s1 = mysql_fetch_object($r1)){
            ?>
            <li><?=utf8_encode($s1->nome)?></li>
            <?php
            }
            ?>
            </ul>
          </div>
        </div>
      </div>
    </div>
    <?php
      }
    ?>




  <div class="form-group">
    <butron SalvarCadastro codigo="<?=$d->codigo?>" tipo="<?=$_SESSION['opc_tipo']?>" class="btn btn-primary">Salvar</butron>
  </div>


</form>

<script type="text/javascript">
  $(function(){

    $("div[voltar]").click(function(){
      Carregando();
      $.ajax({
        url:"src/notificacoes/index.php",
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

        obj.find("input[time]").each(function(){
          t = 0;
          time = [];
          if($(this).prop("checked") == true){
            time[t] = $(this).attr("time");
            t++;
          }
        });

        campo[i] = 'time';
		    valor[i] = time;

        valida = false;
        for(i=0;i<valor.length;i++){
          if(!valor[i]){
            valida = true;
          }
        }

        if(!valida){
            Carregando();
            $.ajax({
              url:"src/notificacoes/form.php",
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
                      url:"src/notificacoes/index.php",
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