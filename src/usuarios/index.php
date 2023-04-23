<?php
  include("../../includes/includes.php");

  if($_GET['tipo']) $_SESSION['tipo'] = $_GET['tipo'];

  if($_POST['acao'] == 'excluir'){
    mysql_query("delete from login where codigo = '".$_POST['codigo']."'");
    exit();
  }

  if($_POST['acao'] == 'situacao'){
    mysql_query("update login set situacao = '".$_POST['situacao']."' where codigo = '".$_POST['codigo']."'");
    exit();
  }


  $NomeTipo = array('opr' => 'Operador' , 'tec' => 'Técnico');
  $NomePerfil = array('usr' => 'Operador' , 'adm' => 'Administrador');

?>

<style>
  td{
    white-space: nowrap;
  }
  .maiusculo{
      text-transform: uppercase;
  }
</style>

<h2>Cadastros <small><?=$NomeTipo[$_SESSION['tipo']]?></small></h2>
<?php
if($_SESSION['scw_usuario_perfil'] == 'adm'){
?>
<div class="text-right">
  <button novoCadastro class="btn btn-primary"><i class="fa fa-file-o"></i> Novo Cadastro</button>
</div>
<?php
}
?>
<div class="table-responsive" style="margin-top: 20px;">
  <table class="table table-hover">

      <?php
        $query = "select a.*, (SELECT count(*) FROM chamados where funcionario = a.codigo or tecnico = a.codigo) as vinc from login a where a.tipo = '".$_SESSION['tipo']."' order by a.nome";
        $result = mysql_query($query);
        if(mysql_num_rows($result)){
      ?>

    <thead>
      <tr>
        <!--<th scope="col-1">#</th>-->
        <th scope="col">Nome</th>
        <!--<th scope="col">Tipo</th>-->
        <th scope="col">Login</th>
        <th scope="col">Perfil</th>
        <?php
        if($_SESSION['scw_usuario_perfil'] == 'adm'){
        ?>        
        <th scope="col" width="30"></th>
        <th scope="col-1" width="110" class="text-right"></th>
        <?php
        }
        ?>
      </tr>
    </thead>
    <tbody>
      <?php
        }
        while($d = mysql_fetch_object($result)){
      ?>
      <tr cadastro<?=$d->codigo?>>
        <!--<th scope="row"><?=$d->codigo?></th>-->
        <td class="maiusculo"><?=utf8_encode($d->nome)?></td>
        <!--<td><?=($NomeTipo[$d->tipo])?></td>-->
        <td><?=utf8_encode($d->login)?></td>
        <td><?=(($d->perfil == 'adm')?'Administrador':$NomeTipo[$d->tipo])?></td>
        <!--<td><?=($NomePerfil[$d->perfil])?></td>-->
        <?php
        if($_SESSION['scw_usuario_perfil'] == 'adm'){
        ?>
        <td>
          <div class="custom-control custom-switch">
            <input <?=(($d->login == 'admin')?false:'situacao')?> type="checkbox" class="custom-control-input" <?=(($d->situacao)?'checked':false)?> id="<?=$d->codigo?>" <?=(($d->login == 'admin')?'disabled':false)?>>
            <label class="custom-control-label" for="<?=$d->codigo?>">Situação</label>
          </div>
        </td>
        <td class="text-right">
          <button EditarCadastro title="Editar Cadastro" codigo="<?=$d->codigo?>" nome="<?=utf8_encode($d->nome)?>" class="btn btn-info"><i class="fa fa-edit"></i></button>
          <button <?=(($d->login == 'admin' or $d->vinc)?false:'DeletarCadastro')?> title="Excluir Cadastro" codigo="<?=$d->codigo?>" nome="<?=utf8_encode($d->nome)?>" class="btn btn-warning" <?=(($d->login == 'admin' or $d->vinc)?'disabled':false)?>><i class="fa fa-trash-o"></i></button>
        </td>
        <?php
        }
        ?>        
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
      $("main").load("src/usuarios/form.php?tipo=<?=$_SESSION['tipo']?>");
    });

    $("button[EditarCadastro]").click(function(){
      Carregando();
      codigo = $(this).attr("codigo");
      $.ajax({
        url:"src/usuarios/form.php",
        type:"GET",
        data:{
          codigo:codigo,
          tipo:"<?=$_SESSION['tipo']?>",
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
              url:"src/usuarios/index.php",
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

    $("input[situacao]").change(function(){
      codigo = $(this).attr("id");
      if($(this).prop("checked") == true){
        situacao = '1';
      }else{
        situacao = '0';
      }
      $.ajax({
        url:"src/usuarios/index.php",
        type:"POST",
        data:{
          codigo:codigo,
          situacao:situacao,
          acao:'situacao',
        },
        success:function(dados){
          JanelaAlerta();
        }
      });
    });


  })
</script>