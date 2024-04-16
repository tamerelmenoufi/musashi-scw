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
      $query = "update ".$_POST['tabela']." set nome = '".utf8_decode($_POST['nome'])."', utm='".utf8_decode($_POST['utm'])."', sigla='".utf8_decode($_POST['sigla'])."' where codigo = '".$_POST['codigo']."'";
    }else{
        
      $n = mysql_num_rows(mysql_query("select * from ".$_POST['tabela']." where nome = '".utf8_decode($_POST['nome'])."'"));
        
      $query = "insert into ".$_POST['tabela']." set nome = '".utf8_decode($_POST['nome'])."', utm='".utf8_decode($_POST['utm'])."', sigla='".utf8_decode($_POST['sigla'])."'";
    }
    mysql_query($query);

    if($n){
        echo "erro"; exit();
    }

    $tabela = $_POST['tabela'];

  }


?>
    <div class="input-group mb-3">
      <div class="input-group-prepend">
          <span class="input-group-text">Setor</span>
      </div>
      <input type="text" id="nome<?=$tabela?>" value="" class="form-control" placeholder="Digite a descrição" aria-label="Digite a descrição">
      <input type="hidden" id="codigo<?=$tabela?>" value="" />

      <div class="input-group-prepend">
          <span class="input-group-text">Sigla</span>
      </div>
      <input type="text" id="sigla<?=$tabela?>" value="" class="form-control" placeholder="Digite a sigla" aria-label="Digite a sigla">
      
      <div class="input-group-prepend">
          <span class="input-group-text">UTM</span>
      </div>
      <select id="utm<?=$tabela?>" class="form-select">
        <?php
        $q1 = "select * from utm order by nome";
        $r1 = mysql_query($q1);
        ?>
        <option value=""></option>
        <?php
        while($d1 = mysql_fetch_object($r1)){
        ?>
        <option value="<?=$d1->codigo?>"><?=$d1->nome?></option>
        <?php
        }
        ?>
      </select>
      <div class="input-group-append">
        <button salvar<?=$tabela?> class="btn btn-primary"><i class="fa fa-save"></i> Salvar</button>
      </div>
    </div>


    <table class="table table-hover">
    
        <?php
          $query = "select a.*, b.nome as utm_nome from ".$tabela." a left join utm b on a.utm = b.codigo order by a.nome";
          $result = mysql_query($query);
          if(mysql_num_rows($result)){
        ?>
    
      <thead>
        <tr>
          <!--<th scope="col-1">#</th>-->
          <th scope="col">Sigla</th>
          <th scope="col">Nome (UTM)</th>
          <th scope="col-2" class="text-right"></th>
        </tr>
      </thead>
      <tbody>
        <?php
          }
          while($d = mysql_fetch_object($result)){
        ?>
        <tr <?=$tabela.$d->codigo?>>
          <!--<th scope="row"><?=$d->codigo?></th>-->
          <td><?=$d->sigla?></td>
          <td><?=utf8_encode($d->nome)?><?=(($d->utm)?" ({$d->utm_nome})":false)?></td>
          <td class="text-right">
            <button Editar<?=$tabela?> title="Editar Registro" codigo="<?=$d->codigo?>" nome="<?=utf8_encode($d->nome)?>" utm="<?=$d->utm?>" sigla="<?=$d->sigla?>" class="btn btn-info"><i class="fa fa-edit"></i> Editar</button>
            <button Deletar<?=$tabela?> title="Excluir Registro" codigo="<?=$d->codigo?>" nome="<?=utf8_encode($d->nome)?>" utm="<?=$d->utm?>" sigla="<?=$d->sigla?>" class="btn btn-danger"><i class="fa fa-close"></i> Excluir</button>
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
      utm = $("#utm<?=$tabela?>").val();
      sigla = $("#sigla<?=$tabela?>").val();
      codigo = $("#codigo<?=$tabela?>").val();
      if(nome && utm){
        Carregando();
        $.ajax({
          url:"src/setores/form.php",
          type:"POST",
          data:{
            codigo:codigo,
            nome:nome,
            utm:utm,
            sigla:sigla,
            tabela:'<?=$tabela?>',
            acao:'editar',
          },
          success:function(dados){
              if(dados == 'erro'){
                JanelaAlertaErro();
                Carregando('none');  
              }else{
                $("div[<?=$tabela?>]").html(dados)
                JanelaAlerta();
                Carregando('none');
              }
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
      utm = $(this).attr("utm");
      sigla = $(this).attr("sigla");
      $("#nome<?=$tabela?>").val(nome);
      $("#sigla<?=$tabela?>").val(sigla);
      $("#utm<?=$tabela?>").val(utm);
      $("#codigo<?=$tabela?>").val(codigo);
      $("#nome<?=$tabela?>").focus();
    });


    $("button[Deletar<?=$tabela?>]").click(function(){

      codigo = $(this).attr("codigo");
      nome = $(this).attr("nome");
      utm = $(this).attr("utm");
      
      $.confirm({
        content:"Confirma a excluisão de <b>"+nome+"</b> pertencente a <b>"+utm+"</b>?",
        title:false,
        buttons:{
          "SIM":function(){
            $("tr[<?=$tabela?>"+codigo+"]").remove();
            $.ajax({
              url:"src/setores/form.php",
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