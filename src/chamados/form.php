<?php
  include("../../includes/includes.php");

    $status = array(
      	'n' => 'Novo',
      	'p' => 'Em Produção',
      	'c' => 'Concluído'
  	);
    $parada = array(
      's' => 'SIM',
      'n' => 'NÃO'
    );


  if($_POST['acao'] == 'salvar'){

    for ($i = 0; $i < count($_POST['campo']); $i++) {
      $campos[] = $_POST['campo'][$i] . " = '".utf8_decode($_POST['valor'][$i])."'";

      if($_POST['campo'][$i] == 'setor'){
        $campos[] = "utm = (select utm from setores where codigo = '".utf8_decode($_POST['valor'][$i])."')";
      }

    }

    if($_POST['codigo'] and $campos){
      $query = "update chamados set data_atualizacao = '".date("Y-m-d H:i:s")."', ".implode(", ",$campos)." where codigo = '".$_POST['codigo']."'";
      $msg = 'atualiza';
    }else if($campos){
      $campos[] = "funcionario = '".$_SESSION['scw_usuario_logado']."'";
      $campos[] = "data_abertura = NOW()";
      $query = "insert into chamados set data_atualizacao = '".date("Y-m-d H:i:s")."', ".implode(", ",$campos);
      $msg = 'novo';
    }
    mysql_query($query);

    if($msg){
        if($msg == 'novo') {$cod = mysql_insert_id();} else { $cod = $_POST['codigo']; }

        $q = "SELECT
                    a.codigo,
                    a.status,
                    a.time,
                    a.motivo,
                    a.parada,

                    a.peca,
                    a.modelo,
                    a.codigos as codigo_nome,

                    tm.nome as time_nome,
                    mt.nome as motivo_nome,
                    s.nome as setor,

                    p.nome as peca_nome,
                    md.nome as modelo_nome,
                    /*cd.nome as codigo_nome,*/

                    m.nome as maquina,
                    t.nome as tipo_manutencao,
                    a.problema,
                    f.nome as funcionario,
                    tc.nome as tecnico
            FROM chamados a
                left join setores s on a.setor = s.codigo

                left join pecas p on a.peca = p.codigo
                left join modelos md on a.modelo = md.codigo
                /*left join codigos cd on a.codigos = cd.codigo*/

                left join tipos_manutencao t on a.tipo_manutencao = t.codigo
                left join maquinas m on a.maquina = m.codigo
                left join time tm on a.time = tm.codigo
                left join motivos mt on a.motivo = mt.codigo
                left join login tc on a.tecnico = tc.codigo
                left join login f on a.funcionario = f.codigo
            where a.codigo = '{$cod}'";
        $r = mysql_query($q);
        $d = mysql_fetch_object($r);

        $msg = /*"SCW-MUSASHI Informa: ".(($msg == 'novo')?"Um novo chamado":"Chamado com alteração ")." cadastrado ".*/
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

               (($d->status)?", *SITUAÇÃO*: ".$status[$d->status]:false).
               (($d->observacao)?", *OBSERVAÇÕES*: ".str_replace("\n"," ",$_POST['observacao']):false);

        //str_replace("[msg]", str_pad($cod, 8, "0", STR_PAD_LEFT) ,$msg);
        //file_put_contents('wapp.txt',$msg);
        foreach($Notificacao['telefone'][$d->time] as $ind => $num){
          EnviarWappNovo($num, $msg);
        }

        EnviaEmailNovo($d->codigo, $d->time);

    }



    exit();
  }


  if($_GET['codigo']){
    $query = "select * from chamados where codigo = '".$_GET['codigo']."'";
    $result = mysql_query($query);
    $d = mysql_fetch_object($result);
  }

?>

<div voltar class="text-left" style="cursor: pointer;"> <i class="fa fa-angle-left" style="margin-right: 10px;"></i> voltar </div>

<h2>Chamado <?=(($d->codigo)?'#'.str_pad($d->codigo, 8, "0", STR_PAD_LEFT):false)?></h2>
<form id="formCadastros">


  <div class="form-group">
    <label for="setor">Setor</label>
    <select form id="setor" class="form-control">
      <option value="">:: Setores ::</option>
      <?php
        $q = "select * from setores order by nome";
        $r = mysql_query($q);
        while($s = mysql_fetch_object($r)){
      ?>
      <option value="<?=$s->codigo?>" <?=(($s->codigo == $d->setor)?'selected':false)?>><?=utf8_encode($s->nome)?></option>
      <?php
        }
      ?>
    </select>
  </div>


  <div class="form-group">
    <label for="time">Área de Atuação</label>
    <select form id="time" class="form-control">
      <option value="">:: Time ::</option>
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
  </div>

  <div class="form-group">
    <label for="motivo">Ocorrência</label>
    <select form id="motivo" class="form-control">
      <option value="">:: Ocorrências ::</option>
      <?php
        $q = "select * from motivos where competencia = '{$d->time}' order by codigo";
        $r = mysql_query($q);
        while($s = mysql_fetch_object($r)){
      ?>
      <option value="<?=$s->codigo?>" <?=(($s->codigo == $d->motivo)?'selected':false)?>><?=utf8_encode($s->nome)?></option>
      <?php
        }
      ?>
    </select>
  </div>


  <!-- <div class="form-group">
    <label for="tipo_manutencao">Problemas</label>
    <select form id="tipo_manutencao" class="form-control">
      <option value="">:: Selecione o problema ::</option>
      <?php
        $q = "select * from tipos_manutencao order by nome";
        $r = mysql_query($q);
        while($s = mysql_fetch_object($r)){
      ?>
      <option value="<?=$s->codigo?>" <?=(($s->codigo == $d->tipo_manutencao)?'selected':false)?>><?=utf8_encode($s->nome)?></option>
      <?php
        }
      ?>
    </select>
  </div> -->

  <div class="form-group">
    <label for="maquina">Máquina</label>
    <select form id="maquina" class="form-control" data-live-search="true">
      <option value="">:: Máquinas ::</option>
      <?php
        $q = "select * from maquinas order by nome";
        $r = mysql_query($q);
        while($s = mysql_fetch_object($r)){
      ?>
      <option value="<?=$s->codigo?>" <?=(($s->codigo == $d->maquina)?'selected':false)?>><?=utf8_encode($s->nome)?></option>
      <?php
        }
      ?>
    </select>
  </div>

  <div class="form-group">
    <label for="parada">Máquina Parada?</label>
    <select form id="parada" class="form-control">
      <option value="n" <?=(('n' == $d->parada)?'selected':false)?>>Não</option>
      <option value="s" <?=(('s' == $d->parada)?'selected':false)?>>Sim</option>
    </select>
  </div>



  <div class="form-group">
    <label for="peca">Peça</label>
    <select form id="peca" class="form-control">
      <option value="">:: Peças ::</option>
      <?php
        $q = "select * from pecas order by nome";
        $r = mysql_query($q);
        while($s = mysql_fetch_object($r)){
      ?>
      <option value="<?=$s->codigo?>" <?=(($s->codigo == $d->peca)?'selected':false)?>><?=utf8_encode($s->nome)?></option>
      <?php
        }
      ?>
    </select>
  </div>


  <div class="form-group">
    <label for="modelo">Modelo</label>
    <select form id="modelo" class="form-control">
      <option value="">:: Modelos ::</option>
      <?php
        $q = "select * from modelos order by nome";
        $r = mysql_query($q);
        while($s = mysql_fetch_object($r)){
      ?>
      <option value="<?=$s->codigo?>" <?=(($s->codigo == $d->modelo)?'selected':false)?>><?=utf8_encode($s->nome)?></option>
      <?php
        }
      ?>
    </select>
  </div>


  <!-- <div class="form-group">
    <label for="codigos">Código</label>
    <select form id="codigos" class="form-control">
      <option value="">:: Códigos ::</option>
      <?php
        $q = "select * from codigos order by nome";
        $r = mysql_query($q);
        while($s = mysql_fetch_object($r)){
      ?>
      <option value="<?=$s->codigo?>" <?=(($s->codigo == $d->codigos)?'selected':false)?>><?=utf8_encode($s->nome)?></option>
      <?php
        }
      ?>
    </select>
  </div> -->

  <div class="form-group">
    <label for="codigos">Código</label>
    <input form type="text" class="form-control" id="codigos" aria-describedby="Código" value="<?=utf8_encode($d->codigos)?>">
  </div>


  <div class="form-group">
      <label>Descrição do problema</label>
    <textarea form id="problema" class="form-control"><?=utf8_encode($d->problema)?></textarea>
  </div>

  <div class="form-group">
    <butron SalvarCadastro codigo="<?=$d->codigo?>" tipo="<?=$_SESSION['opc_tipo']?>" class="btn btn-primary">Salvar</butron>
  </div>


</form>

<script type="text/javascript">
  $(function(){


    // $('#maquina').selectpicker();
	  // data-live-search="true"

    $( 'select' ).select2( {
      theme: "bootstrap-5",
    } );

    $("#time").change(function(){
      time = $(this).val();
      $.ajax({
        url:"src/chamados/motivos.php",
        type:"POST",
        data:{
          time
        },
        success:function(dados){
          $("#motivo").html(dados);
        }
      });
    });


    $("div[voltar]").click(function(){
      Carregando();
      $.ajax({
        url:"src/chamados/index.php",
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
              url:"src/chamados/form.php",
              type:"POST",
              data:{
                campo:campo,
                valor:valor,
                codigo:codigo,
                acao:'salvar',
              },
              success:function(dados){
                //$.alert({ content:dados });

                $.ajax({
                  url:"src/chamados/index.php",
                  success:function(dados){
                    Carregando('none');
                    $("main").html(dados);
                    JanelaAlerta();
                  }
                });

              }
            });

        }else{

            $.alert({
              content:"Favor preencha os campos para criar o chamado!",
              title:false,
            })

        }

    });

  })
</script>