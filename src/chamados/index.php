<?php
  include("../../includes/includes.php");


  if($_GET['status']) { $_SESSION['status'] = $_GET['status']; $_SESSION['scw_chamado_filtro'] = $_SESSION['scw_chamado_where_filtro'] = false; }
  if($_GET['status'] == 't') { $_SESSION['status'] = false; $_SESSION['scw_chamado_filtro'] = $_SESSION['scw_chamado_where_filtro'] = false; }

  if($_GET['acao'] == 'filtrar') { $_SESSION['scw_chamado_filtro'] = $_SESSION['scw_chamado_where_filtro'] = false; }

  if($_POST['acao'] == 'filtrar'){

      $_SESSION['scw_chamado_filtro'] = $_POST['busca'];
      $_SESSION['scw_chamado_filtro_data1'] = $_POST['data1'];
      $_SESSION['scw_chamado_filtro_data2'] = $_POST['data2'];

      $_SESSION['scw_chamado_where_filtro'] = (($_SESSION['status'])?' and ':' where ').
                                              (($_POST['busca'])?
                                              "(b.nome like '%".utf8_decode($_POST['busca'])."%' or ".
                                              " c.nome like '%".utf8_decode($_POST['busca'])."%' or ".
                                              " d.nome like '%".utf8_decode($_POST['busca'])."%') ":false
                                              ).
                                              (($_POST['busca'] and $_SESSION['scw_chamado_filtro_data1'])?" and ":false).
                                              (($_SESSION['scw_chamado_filtro_data1'] and !$_SESSION['scw_chamado_filtro_data2'])?" data_abertura between '".dataMysql($_POST['data1'])." 00:00:00' and '".dataMysql($_POST['data1'])."  23:59:59' ":false).
                                              (($_SESSION['scw_chamado_filtro_data1'] and $_SESSION['scw_chamado_filtro_data2'])?" data_abertura between '".dataMysql($_POST['data1'])."  00:00:00' and '".dataMysql($_POST['data2'])." 23:59:59' ":false);

  }


  $titulo = array(
                  't' => '',
                  'n' => 'Novo',
                  'p' => 'Pendente',
                  'c' => 'Concluído',
            );
  $parada = array(
    's' => 'SIM',
    'n' => 'NÃO'
  );
  if($_POST['acao'] == 'excluir'){
    mysql_query("delete from chamados where codigo = '".$_POST['codigo']."'");
    exit();
  }


  if($_POST['acao'] == 'situacao'){

    if($_POST['situacao'] == 'p'){
      $and = ", tecnico = '".$_SESSION['scw_usuario_logado']."', data_recebimento = NOW(), status='p' ";
      $msg = 'atualizar';
    }else if($_POST['situacao'] == 'n'){
      $and = ", tecnico = 0, data_recebimento = 0, data_fechamento = 0, status='n' ";
      $msg = 'novo';
    }else if($_POST['situacao'] == 'c'){
      $and = ", data_fechamento = NOW(), status='c' ";
      $msg = 'atualizar';
    }


    $q = "update chamados set data_atualizacao = '".date("Y-m-d H:i:s")."', status = '".$_POST['situacao']."'".$and." where codigo = '".$_POST['codigo']."'";
    mysql_query($q);

    if($msg){

        $q = "SELECT
                    a.codigo,
                    a.status,
                    a.time,
                    a.motivo,
                    a.parada,

                    a.peca,
                    a.modelo,
                    a.codigos,

                    tm.nome as time_nome,
                    mt.nome as motivo_nome,
                    s.nome as setor,
                    m.nome as maquina,

                    p.nome as peca_nome,
                    md.nome as modelo_nome,
                    cd.nome as codigo_nome,

                    t.nome as tipo_manutencao,
                    a.problema,
                    f.nome as funcionario,
                    tc.nome as tecnico
            FROM chamados a
                left join setores s on a.setor = s.codigo

                left join pecas p on a.peca = p.codigo
                left join modelos md on a.modelo = md.codigo
                left join codigos cd on a.codigos = cd.codigo

                left join tipos_manutencao t on a.tipo_manutencao = t.codigo
                left join maquinas m on a.maquina = m.codigo
                left join time tm on a.time = tm.codigo
                left join motivos mt on a.motivo = mt.codigo
                left join login tc on a.tecnico = tc.codigo
                left join login f on a.funcionario = f.codigo
            where a.codigo = '{$_POST['codigo']}'";
        $r = mysql_query($q);
        $d = mysql_fetch_object($r);

        $msg = "SCW-MUSASHI Informa: ".(($msg == 'novo')?"Um novo chamado":"Chamado com alteração ")." cadastrado ".
               "*ID*:".str_pad($d->codigo, 8, "0", STR_PAD_LEFT).
               ", *SETOR*: ".utf8_encode($d->setor).
               ", *MÁQUINA*: ".utf8_encode($d->maquina).
               ", *MÁQUINA PARADA*: ".($parada[$d->parada]).

               ", *PEÇA*: ".utf8_encode($d->peca_nome).
               ", *MODELO*: ".utf8_encode($d->modelo_nome).
               ", *CÓDIGO*: ".utf8_encode($d->codigo_nome).

               (($d->time_nome)?", *TIME*: ".utf8_encode($d->time_nome):false).
               (($d->motivo_nome)?", *OCORRÊNCIA*: ".utf8_encode($d->motivo_nome):false).

              //  ", *TIPO DE MANUTENÇÃO*: ".utf8_encode($d->tipo_manutencao).
               (($d->problema)?", *PROBLEMA*: ".str_replace("\n"," ",utf8_encode($d->problema)):false).
               (($d->funcionario)?", *FUNCIONÁRIO*: ".utf8_encode($d->funcionario):false).
               (($d->tecnico)?", *TÉCNICO*: ".utf8_encode($d->tecnico):false).

               (($d->status)?", *SITUAÇÃO*: ".$titulo[$d->status]:false).
               (($d->observacao)?", *OBSERVAÇÕES*: ".str_replace("\n"," ",$_POST['observacao']):false);

        //file_put_contents('wapp.txt',$msg);
        foreach($Notificacao['telefone'][$d->time] as $ind => $num){
            EnviarWappNovo($num, $msg);
        }

        ////////////////EMAIL//////////////////////////////////
        $postdata = http_build_query(
            array(
                'codigo' => $d->codigo,
                'time' => $d->time,
            )
        );
        $opts = array('http' =>
            array(
                'method' => 'POST',
                'header' => 'Content-type: application/x-www-form-urlencoded',
                'content' => $postdata
            )
        );
        $context = stream_context_create($opts);
        $result = file_get_contents('http://scw.mohatron.com/src/alertas/email.php', false, $context);
        ////////////////////////////////////////////////////////

    }



    //exit();
  }

?>

<style>
  td{
    white-space: nowrap;
  }
</style>

<h2>CHAMADOS <small><?=$titulo[$_SESSION['status']]?></small></h2>
<?php
if(!$_SESSION['status']){

?>
<div class="text-right">
  <button novoCadastro class="btn btn-primary"><i class="fa fa-file-o"></i> Novo Chamado</button>
</div>
<?php
        if($_SESSION['scw_usuario_perfil'] == 'adm' or $_SESSION['scw_usuario_tipo'] == 'tec'){
?>
<div class="text-left">
  <a href='./chamados.php' target='_blank'><i class="fa fa-download"></i> Baixar lista de chamados</a>
</div>
<?php
        }

}
?>
<div class="table-responsive" style="margin-top: 20px;">
  <p style="font-size: 10px;">N = Novo, P = Produção, C = Concluído</p>

    <div class="input-group mb-3">
      <div class="input-group-prepend">
          <span class="input-group-text">Busca</span>
      </div>
      <input type="text" id="busca_filtro" value="<?=$_SESSION['scw_chamado_filtro']?>" class="form-control" placeholder="Digite o texto para a sua busca" aria-label="Digite o texto de sua busca">
      <input type="hidden" id="codigo<?=$tabela?>" value="" />


      <div class="input-group-prepend">
          <span class="input-group-text">Data Ini</span>
      </div>
      <input type="text" id="busca_filtro_data1" value="<?=$_SESSION['scw_chamado_filtro_data1']?>" class="form-control" placeholder="Data Inicial" aria-label="Data Inicial">

      <div class="input-group-prepend">
          <span class="input-group-text">Data Fim</span>
      </div>
      <input type="text" id="busca_filtro_data2" value="<?=$_SESSION['scw_chamado_filtro_data2']?>" class="form-control" placeholder="Data Final" aria-label="Data Final">


      <div class="input-group-append">
        <button buscar class="btn btn-success"><i class="fa fa-search"></i> Buscar</button>
        <button limpar_busca class="btn btn-danger"><i class="fa fa-eraser"></i> Limpar</button>
      </div>
    </div>


  <table class="table table-hover">

      <?php
        $query = "select
                        a.*,
                        b.nome as setor,
                        c.nome as motivo,
                        t.nome as time,
                        d.nome as maquina,
                        (select count(*) from chamados_observacoes where chamado = a.codigo) as qt

                  from chamados a
                    left join setores b on a.setor = b.codigo
                    left join motivos c on a.motivo = c.codigo
                    left join time t on a.time = t.codigo
                    left join maquinas d on a.maquina = d.codigo

                ".(($_SESSION['status'])?" where status = '".$_SESSION['status']."'":false).$_SESSION['scw_chamado_where_filtro']."

                  order by a.data_abertura desc limit 50";
        $result = mysql_query($query);
        if(mysql_num_rows($result)){
      ?>

    <thead>
      <tr>
        <th scope="col-1"><i class="fa fa-cogs" aria-hidden="true"></i></th>
        <th scope="col-1">Chamado</th>
        <th scope="col">Setor</th>
        <th scope="col">Time<br>Ocorrência</th>
        <th scope="col">Máquina</th>
        <th scope="col">Data</th>
        <?php
        if($_SESSION['scw_usuario_perfil'] == 'adm' or $_SESSION['scw_usuario_tipo'] == 'tec'){
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

          if($d->status == 'n'){
            $classTr = 'table-danger';
          }else if($d->status == 'p'){
            $classTr = 'table-warning';
          }else if($d->status == 'c'){
            $classTr = 'table-success';
          }

      ?>
      <tr cadastro<?=$d->codigo?> class="<?=$classTr?>">
        <td><i class="fa fa-cogs" aria-hidden="true" style="color:<?=(($d->parada == 's')?'red':'green')?>"></i></td>
        <th scope="row">#<?=str_pad($d->codigo, 8, "0", STR_PAD_LEFT)?></th>
        <td><?=utf8_encode($d->setor)?></td>
        <td><span style='color:#a1a1a1'><?=utf8_encode($d->time)."</span><br>".utf8_encode($d->motivo)?></td>
        <td><?=utf8_encode($d->maquina)?></td>
        <td><?=dataBr($d->data_abertura)?></td>
        <?php
        if($_SESSION['scw_usuario_perfil'] == 'adm' or $_SESSION['scw_usuario_tipo'] == 'tec'){
        ?>
        <td>


          <div class="btn-group btn-group-toggle" data-toggle="buttons">
            <?php
            if(!$_SESSION['status'] or $_SESSION['status'] == 'n'){
            ?>
            <label title="Novo" class="btn btn-<?=(($d->status == 'n')?'danger active':'light')?>">
              <input status type="radio" name="status" value="n" codigo="<?=$d->codigo?>" <?=(($d->status == 'n')?'disabled': (($d->tecnico != $_SESSION['scw_usuario_logado'] and $_SESSION['scw_usuario_perfil'] != 'adm')?'disabled':false) )?>> N
            </label>
            <?php
            }if(!$_SESSION['status'] or $_SESSION['status'] == 'n' or $_SESSION['status'] == 'p'){
            ?>
            <label title="Pendente" class="btn btn-<?=(($d->status == 'p')?'warning active':'light')?>">
              <input status type="radio" name="status" value="p" codigo="<?=$d->codigo?>" <?=(($d->status == 'p')?'disabled': (($d->tecnico != $_SESSION['scw_usuario_logado'] and $_SESSION['scw_usuario_perfil'] != 'adm' and $d->status != 'n')?'disabled':false) )?>> P
            </label>
            <?php
            }if(!$_SESSION['status'] or $_SESSION['status'] == 'p' or $_SESSION['status'] == 'c'){
            ?>
            <label title="Concluído" class="btn btn-<?=(($d->status == 'c')?'success active':'light')?>">
              <input status type="radio" name="status" value="c" codigo="<?=$d->codigo?>" c<?=$d->codigo?> qt="<?=$d->qt?>" <?=(($d->status == 'c' or !$d->qt)?'disabled':(($d->tecnico != $_SESSION['scw_usuario_logado'] and $_SESSION['scw_usuario_perfil'] != 'adm' and $d->status != 'n')?'disabled':false) )?>> C
            </label>
            <?php
            }
            ?>
          </div>

        </td>
        <?php
        if($_SESSION['scw_usuario_perfil'] == 'adm' or $_SESSION['scw_usuario_tipo'] == 'tec'){
        ?>
        <td class="text-right">
          <button DetalhesCadastro title="Lista detelhes do Chamado" codigo="<?=$d->codigo?>" nome="<?=utf8_encode($d->nome)?>" class="btn btn-success"><i class="fa fa-list"></i></button>
          <?php
          if(!$_SESSION['status'] and $_SESSION['scw_usuario_perfil'] == 'adm'){
          ?>
          <button EditarCadastro title="Editar" codigo="<?=$d->codigo?>" nome="<?=utf8_encode($d->nome)?>" class="btn btn-info"><i class="fa fa-edit"></i></button>
          <button <?=(($d->qt)?false:'DeletarCadastro')?> title="Excluir" codigo="<?=$d->codigo?>" nome="chamado" class="btn btn-warning" <?=(($d->qt)?'disabled="disabled"':false)?>><i class="fa fa-trash-o"></i></button>
          <?php
          }
          ?>
        </td>
        <?php
          }
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

    $("#busca_filtro_data2, #busca_filtro_data1").mask("99/99/9999");


    $('input[situacao]').bootstrapToggle();

    $("button[novoCadastro]").click(function(){
      $("main").load("src/chamados/form.php?tipo=<?=$_SESSION['opc_tipo']?>");
    });

    $("button[EditarCadastro]").click(function(){
      Carregando();
      codigo = $(this).attr("codigo");
      $.ajax({
        url:"src/chamados/form.php",
        type:"GET",
        data:{
          codigo:codigo,
          tipo:"<?=$_SESSION['opc_tipo']?>",
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
              url:"src/chamados/index.php",
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

    $("input[status]").change(function(){
      obj = $(this);
      status = $(this).val();
      codigo = $(this).attr('codigo');
      $.alert({
        content:"Confirma realmente a alteração do status?",
        title:false,
        buttons:{
          'SIM':function(){

            Carregando();
            $.ajax({
              url:"src/chamados/index.php",
              type:"POST",
              data:{
                codigo:codigo,
                situacao:status,
                acao:'situacao',
              },
              success:function(dados){
                $("main").html(dados);
                JanelaAlerta();
                Carregando('none');
              }
            });



          },
          'NÃO':function(){
            $(obj).parent("label").removeClass('active');
          }
        }
      });
    });



    $("button[buscar]").click(function(){

        busca = $("#busca_filtro").val();
        data1 = $("#busca_filtro_data1").val();
        data2 = $("#busca_filtro_data2").val();

        if(!busca && !data1) {
            $.alert('Informe algun dado para a busca!');
            return false;
        }

        Carregando();

        $.ajax({
          url:"src/chamados/index.php",
          type:"POST",
          data:{
            busca,
            data1,
            data2,
            acao:'filtrar',
          },
          success:function(dados){
            $("main").html(dados);
            Carregando('none');
          }
        });


    });

    $("button[limpar_busca]").click(function(){
        Carregando();
        $.ajax({
          url:"src/chamados/index.php",
          type:"GET",
          data:{
            acao:'filtrar',
          },
          success:function(dados){
            $("main").html(dados);
            Carregando('none');
          }
        });


    });




    $("button[DetalhesCadastro]").click(function(){
      codigo = $(this).attr("codigo");

      JanelaDetalhes = $.dialog({
        content:"url:src/chamados/detalhes.php?codigo="+codigo,
        title:false,
        columnClass:'col col-md-8'
      });

    });

  })
</script>