<?php
  include("../../includes/includes.php");

  $r = array(
      'tecnicos' => 'Técnicos',
      'maquinas' => 'Máquinas',
      'tipos_manutencao' => 'Tipos de Manutenção',
      'setores' => 'Setores',
      'funcionarios' => 'Funcionários',
  );

  $tabela = $_GET['tabela'];
  $rotulo = $r[$tabela];


  if($_GET['acao'] == 'excluir'){
    mysql_query("delete from ".$tabela." where codigo = '".$_GET['codigo']."'");
    exit();
  }

  if($_POST['acao'] == 'editar'){

    if($_POST['codigo']){
      $query = "update ".$_POST['tabela']." set nome = '".utf8_decode($_POST['nome'])."' where codigo = '".$_POST['codigo']."'";
    }else{
      $query = "insert into ".$_POST['tabela']." set nome = '".utf8_decode($_POST['nome'])."'";
    }
    mysql_query($query);

    $tabela = $_POST['tabela'];

  }


?>
<h5><?=$rotulo?></h5>

<div id="<?=$tabela?>" class="card" style="margin-bottom: 10px;">
  <div class="card-header">
    Cadastro / Edição - <?=$rotulo?>
  </div>
  <div class="card-body">

    <div class="input-group mb-3">
      <div class="input-group-prepend">
          <span class="input-group-text"><?=$rotulo?></span>
      </div>
      <input type="text" id="nome<?=$tabela?>" value="" class="form-control" placeholder="Digote a descrição" aria-label="Digite a descrição">
      <input type="hidden" id="codigo<?=$tabela?>" value="" />
      <div class="input-group-append">
        <button salvar<?=$tabela?> class="btn btn-primary"><i class="fa fa-save"></i> Salvar</button>
      </div>
    </div>

  </div>
</div>

<table class="table table-hover">

    <?php
      $query = "select * from ".$tabela." order by nome";
      $result = mysql_query($query);
      if(mysql_num_rows($result)){
    ?>

  <thead>
    <tr>
      <th scope="col-1">#</th>
      <th scope="col">Nome</th>
      <th scope="col-1" class="text-right"></th>
    </tr>
  </thead>
  <tbody>
    <?php
      }
      while($d = mysql_fetch_object($result)){
    ?>
    <tr <?=$tabela.$d->codigo?>>
      <th scope="row"><?=$d->codigo?></th>
      <td><?=utf8_encode($d->nome)?></td>
      <td class="text-right">
        <button Editar<?=$tabela?> codigo="<?=$d->codigo?>" nome="<?=utf8_encode($d->nome)?>" class="btn btn-info"><i class="fa fa-edit"></i> Editar</button>
        <button Deletar<?=$tabela?> codigo="<?=$d->codigo?>" nome="<?=utf8_encode($d->nome)?>" class="btn btn-danger"><i class="fa fa-delete"></i> Excluir</button>
      </td>
    </tr>
    <?php
      }
    ?>
  </tbody>
</table>

<script type="text/javascript">
  $(function(){

    $("button[salvar<?=$tabela?>]").click(function(){
      nome = $("#nome<?=$tabela?>").val();
      codigo = $("#codigo<?=$tabela?>").val();
      if(nome){
        Carregando();
        $.ajax({
          url:"src/auxiliares/form.php",
          type:"POST",
          data:{
            codigo:codigo,
            nome:nome,
            tabela:'<?=$tabela?>',
            acao:'editar',
          },
          success:function(dados){
            $("div[<?=$tabela?>]").html(dados)
            JanelaAlerta();
            Carregando('none');
          }
        });
      }else{
        $.alert({
          content:"Favor preencha os campos solicitados.",
          title:false,
        });
      }
    });

    $("button[Editar<?=$tabela?>]").click(function(){
      codigo = $(this).attr("codigo");
      nome = $(this).attr("nome");
      $("#nome<?=$tabela?>").val(nome);
      $("#codigo<?=$tabela?>").val(codigo);
      $("#nome<?=$tabela?>").focus();
    });


    $("button[Deletar<?=$tabela?>]").click(function(){

      codigo = $(this).attr("codigo");
      nome = $(this).attr("nome");
      
      $.confirm({
        content:"Confirma a excluisão de <b>"+nome+"</b>?",
        title:false,
        buttons:{
          "SIM":function(){
            $("tr[<?=$tabela?>"+codigo+"]").remove();
            $.ajax({
              url:"src/auxiliares/form.php",
              type:"GET",
              data:{
                codigo:codigo,
                tabela:'<?=$tabela?>',
                acao:'excluir',
              },
              success:function(dados){
                JanelaAlerta();
              }
            });
          },
          "NÃO":function(){

          }
        }
      });

    });

  })
</script>