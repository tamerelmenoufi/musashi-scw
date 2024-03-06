<?php
  include("../../includes/includes.php");

  if($_GET['tipo']) $_SESSION['tipo'] = $_GET['tipo'];

  $NomeTipo = array('opr' => 'Operador' , 'tec' => 'Técnico');

  if($_POST['acao'] == 'salvar'){

      //$campos[] = "tipo = '".$_POST['tipo']."'";
    for ($i = 0; $i < count($_POST['campo']); $i++) {
      $campos[] = $_POST['campo'][$i] . " = '".utf8_decode($_POST['valor'][$i])."'";
      
      if($_POST['campo'][$i] == 'login'){
          $campos[] = "matricula = '".utf8_decode($_POST['valor'][$i])."'";
          $matricula = utf8_decode($_POST['valor'][$i]);
      }
      
    }

    if($_POST['codigo'] and $campos){
      $query = "update login set ".implode(", ",$campos)." where codigo = '".$_POST['codigo']."'";
    }else if($campos){
        
      $n = mysql_num_rows(mysql_query("select * from login where matricula = '".$matricula."'"));
        
      $query = "insert into login set ".implode(", ",$campos);
    }
    mysql_query($query);

    if($n){
        echo "erro"; exit();
    }

    exit();
  }


  if($_GET['codigo']){
    $query = "select * from login where codigo = '".$_GET['codigo']."'";
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

<h2>Cadastros - <small><?=$NomeTipo[$_SESSION['tipo']]?></small></h2>
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
    <label for="perfil">Perfil</label>
    <select <?=(($d->login == 'admin')?false:'form')?> class="form-control" id="perfil" <?=(($d->login == 'admin')?'disabled':false)?>>
      <option value="usr" <?=(($d->perfil == 'usr')?'selected':false)?>><?=$NomeTipo[$_SESSION['tipo']]?></option>
      <option value="adm" <?=(($d->perfil == 'adm')?'selected':false)?>>Administrador</option>
    </select>
  </div>


  <div class="form-group">
    <label for="permissoes">Permissões</label>
      <?php
      $permissoes_ativos = explode(",",$d->permissoes);
      $permissoes = array(
                      'auxiliares' => 'Tabelas Auxiliares',
                      'operadores' => 'Cadastro de Operadores',
                      'tecnicos' => 'Cadastros de Técnicos',
                      'maquinas' => 'Cadastros das Máquinas',
                      'todos' => 'Todos os chamados',
                      'novos' => 'Novos Chamados',
                      'producao' => 'Chamados em produção (atendimento)',
                      'concluidos' => 'Chamados concluídos',
                      'chamados' => 'Fechar qualquer Chamado',
                    );
        $usu = array('todos','novos','producao','concluidos');
      foreach ($permissoes as $chave => $valor) {
        # code...
      ?>
      <div class="custom-control custom-checkbox">
        <input <?=((in_array($chave,$usu))?'usu':false)?> permissoes type="checkbox" id="<?=$chave?>" class="custom-control-input" <?=((in_array($chave, $permissoes_ativos))?'checked':false)?> <?=(($d->login == 'admin')?'disabled':false)?>>
        <label class="custom-control-label" for="<?=$chave?>"><?=$valor?></label>
      </div>
      <?php
      }
      ?>
      <input <?=(($d->login == 'admin')?false:'form')?> type="hidden" id="permissoes" value="<?=$d->permissoes?>">
  </div>



  <div class="form-group">
    <label for="exampleInputEmail1">Situação</label>
    <select <?=(($d->login == 'admin')?false:'form')?> class="form-control" id="situacao" <?=(($d->login == 'admin')?'disabled':false)?>>
      <option value="1" <?=(($d->situacao == '1')?'selected':false)?>>Liberado</option>
      <option value="0" <?=(($d->situacao == '0')?'selected':false)?>>Bloqueado</option>
    </select>
  </div>

  <?php
  if($_SESSION['scw_usuario_perfil'] == 'adm'){
  ?>
  <div class="form-group">
    <label for="exampleInputEmail1">Tipo de Cadastro</label>
    <select form class="form-control" id="tipo" >
      <option value="tec" <?=(($d->tipo == 'tec')?'selected':false)?>>Técnico</option>
      <option value="opr" <?=(($d->tipo == 'opr')?'selected':false)?>>Operador</option>
    </select>
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

    <?php
    if(!$d->codigo){
    ?>
    $("input[usu]").prop("checked", true);

    permissoes = new Array();
    i=0;
    $("input[permissoes]").each(function(){
      if($(this).prop("checked") == true){
        permissoes[i] = $(this).attr("id");
        i++;
      }
    });
    $("#permissoes").val(permissoes);
    
    
    <?php
    }
    ?>


    $("#perfil").change(function(){
        
        opc = $(this).val();
        if(!opc){
            $("input[permissoes]").prop("checked", false);
        }else if(opc == 'usr'){
            $("input[permissoes]").prop("checked", false);
            $("input[usu]").prop("checked", true);
        }else if(opc == 'adm'){
            $("input[permissoes]").prop("checked", true);
        }
        
        permissoes = new Array();
        i=0;
        $("input[permissoes]").each(function(){
          if($(this).prop("checked") == true){
            permissoes[i] = $(this).attr("id");
            i++;
          }
        });
        $("#permissoes").val(permissoes);


    });
    
    
    $("input[permissoes]").click(function(){
        permissoes = new Array();
        i=0;
        $("input[permissoes]").each(function(){
          if($(this).prop("checked") == true){
            permissoes[i] = $(this).attr("id");
            i++;
          }
        });
        $("#permissoes").val(permissoes);
    });

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
        url:"src/usuarios/index.php",
        type:"GET",
        data:{
          tipo:"<?=$_SESSION['tipo']?>",
        },
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
              url:"src/usuarios/form.php",
              type:"POST",
              data:{
                campo:campo,
                valor:valor,
                codigo:codigo,
                /*tipo:"<?=$_SESSION['tipo']?>",*/
                acao:'salvar',
              },
              success:function(dados){
                //$.alert({ content:dados });
                
                
                if(dados == 'erro'){
                    
                    Carregando('none');
                    JanelaAlertaErro();
                    
                }else{

                    $.ajax({
                      url:"src/usuarios/index.php",
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
              content:"Favor preencha os campos para criar novo usuário!",
              title:false,
            })

        }
    });

  })
</script>