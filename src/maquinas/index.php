<?php
  include("../../includes/includes.php");

  if($_POST['acao'] == 'excluir'){
    mysql_query("delete from maquinas where codigo = '".$_POST['codigo']."'");
    exit();
  }

?>

<style>
  td{
    white-space: nowrap;
  }
</style>

<h2>Máquinas </h2>
<div class="text-right">
  <button novoCadastro class="btn btn-primary"><i class="fa fa-file-o"></i> Novo Cadastro</button>
</div>
<div class="table-responsive" style="margin-top: 20px;">
  <table class="table table-hover">

      <?php
        $query = "select * from maquinas order by nome";
        $result = mysql_query($query);
        if(mysql_num_rows($result)){
      ?>

    <thead>
      <tr>
        <!--<th scope="col-1">#</th>-->
        <th scope="col">CC</th>
        <th scope="col">Máquina</th>
        <th scope="col">Tipo</th>
        <th scope="col-1" width="110" class="text-right"></th>
      </tr>
    </thead>
    <tbody>
      <?php
        }
        while($d = mysql_fetch_object($result)){
      ?>
      <tr cadastro<?=$d->codigo?>>
        <!--<th scope="row"><?=$d->codigo?></th>-->
        <td><?=utf8_encode($d->cc)?></td>
        <td><?=utf8_encode($d->nome)?></td>
        <td><?=utf8_encode($d->tipo)?></td>
        <td class="text-right">
          <button EditarCadastro title="Editar Registro" codigo="<?=$d->codigo?>" nome="<?=utf8_encode($d->nome)?>" class="btn btn-info"><i class="fa fa-edit"></i></button>
          <button DeletarCadastro title="Excluir Registro" codigo="<?=$d->codigo?>" nome="<?=utf8_encode($d->nome)?>" class="btn btn-warning" ><i class="fa fa-trash-o"></i></button>
        </td>
      </tr>
      <?php
        }
      ?>
    </tbody>
  </table>
</div>
<script type="text/javascript">
  $(function(){

    $("button[novoCadastro]").click(function(){
      $("main").load("src/maquinas/form.php");
    });

    $("button[EditarCadastro]").click(function(){
      Carregando();
      codigo = $(this).attr("codigo");
      $.ajax({
        url:"src/maquinas/form.php",
        type:"GET",
        data:{
          codigo:codigo,
        },
        success:function(dados){
          $("main").html(dados);
          Carregando('none');
        }
      });
    });

    $("button[DeletarCadastro]").click(function(){
      codigo = $(this).attr("codigo");
      nome = $(this).attr("nome");
      $.confirm({
        content:"Confirma a exclusão de <b>"+nome+"</b>?",
        title:false,
        buttons:{
          "SIM":function(){
            $("tr[cadastro"+codigo+"]").remove();
            $.ajax({
              url:"src/maquinas/index.php",
              type:"POST",
              data:{
                codigo:codigo,
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