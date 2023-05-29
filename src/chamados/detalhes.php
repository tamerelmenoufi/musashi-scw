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
  if($_POST['observacao']){

  	$query = "insert into chamados_observacoes set chamado = '".$_POST['codigo']."', data = NOW(), tecnico='".$_SESSION['scw_usuario_logado']."', observacao = '".utf8_decode($_POST['observacao'])."'";
  	mysql_query($query);

  	$_GET['codigo'] = $_POST['codigo'];


//*
        $query = "SELECT
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
                    m.nome as maquina,

                    p.nome as peca_nome,
                    md.nome as modelo_nome,
                    /*cd.nome as codigo_nome,*/

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
                 where a.codigo = '".$_GET['codigo']."'";
        $result = mysql_query($query);
        $d = mysql_fetch_object($result);


        $msg = "SCW-MUSASHI Informa: Chamado com alteração cadastrado ".
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

        foreach($Notificacao['telefone'][$d->time] as $ind => $num){
          EnviarWappNovo($num, $msg);
        }


        EnviaEmailNovo($d->codigo, $d->time);

//*/


  }

  $query = "select
                        a.*,
                        b.nome as setor,
                        c.nome as tipo_manutencao,
                        d.nome as maquina,
                        e.nome as funcionario,
                        f.nome as tecnico,
                        f.codigo as tecnico_codigo

                  from chamados a
                    left join setores b on a.setor = b.codigo
                    left join tipos_manutencao c on a.tipo_manutencao = c.codigo
                    left join maquinas d on a.maquina = d.codigo
                    left join login e on a.funcionario = e.codigo
                    left join login f on a.tecnico = f.codigo

                 where a.codigo = '".$_GET['codigo']."'";


  $query = "SELECT
                 a.*,
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
         where a.codigo = '{$_GET['codigo']}'";

  $result = mysql_query($query);
  $d = mysql_fetch_object($result);

?>

<style type="text/css">
  .list-group-item{
    min-height:70px;
  }
	.list-group-item label{
		font-size: 10px;
		font-weight: bold;
		margin:0;
		padding: 0;
	}
	.list-group-item p{
		font-size: 14px;
		margin:0;
		padding: 0;
	}
	.list-group-item span{
		position: absolute;
		right:20px;
		top: 20px;
		font-size: 14px;
	}
	.obs{
		font-size: 10px;
	}
</style>

<h3>Chamado #<?=str_pad($d->codigo, 8, "0", STR_PAD_LEFT)?></h3>
<div class="card" style="margin-top: 20px;">
  <ul class="list-group list-group-flush">

    <li class="list-group-item">
    	<label>Solicitante:</label>
    	<p><?=utf8_encode($d->funcionario)?></p>
    	<span><?=dataBr($d->data_abertura)?></span>
    </li>

    <li class="list-group-item">
    	<label>Setor:</label>
    	<p><?=utf8_encode($d->setor)?></p>
      <span>
      <label>Time:</label>
    	<p><?=utf8_encode($d->time_nome)?></p>
      </span>
    </li>

    <li class="list-group-item">
    	<label>Problema:</label>
    	<p><?=utf8_encode($d->motivo_nome)?></p>
      <span>
      <label>Máquina: <?=(($d->parada == 's')?'<font style="color:red">Parada</font>':'<font style="color:green">Funcionando</font>')?></label>
    	<p><?=utf8_encode($d->maquina)?></p>
      </span>
    </li>

    <li class="list-group-item d-flex justify-content-between">
      <div>
        <label>Peça:</label>
        <p><?=utf8_encode($d->peca_nome)?></p>        
      </div>
      <div>
        <label>Modelo:</label>
        <p><?=utf8_encode($d->modelo_nome)?></p>
      </div>
      <div>
        <label>Código:</label>
        <p><?=utf8_encode($d->codigos)?></p>
      </div>
    </li>


    <?php
    if($d->status != 'n'){
    ?>
    <li class="list-group-item">
    	<label>Técnico:</label>
    	<p><?=utf8_encode($d->tecnico)?></p>
    	<span><?=dataBr($d->data_recebimento)?></span>

    <?php
    if($d->status == 'p' and $d->tecnico_codigo == $_SESSION['scw_usuario_logado']){
    ?>
		<div class="form-group">
			<label for="observacoes">Incluir Observações</label>
			<textarea class="form-control" id="observacoes" rows="3"></textarea>
		</div>
		<div class="form-group">
			<button obs codigo="<?=$d->codigo?>" class="btn btn-secondary">Salvar</button>
		</div>

	<?php
	}
	?>

    </li>
    <?php
	}
    ?>

    <li class="list-group-item">
    	<label>Situação:</label>
    	<p><?=$status[$d->status]?></p>
    	<?php
    	if(dataBr($d->data_fechamento)){
    	?>
    	<span><?=dataBr($d->data_fechamento)?></span>
    	<?php
    	}
    	?>
    </li>


  </ul>

  <div class="card-body">
    <h5 class="card-title">Descrição do problema</h5>
    <p class="card-text"><?=utf8_encode($d->problema)?></p>
  </div>

  <hr>


  <div class="card-body">
    <h5 class="card-title">Observações Técnicas</h5>
    <?php
    $q = "select a.*, b.nome as tecnico from chamados_observacoes a left join login b on a.tecnico = b.codigo where a.chamado = '".$d->codigo."'";
    $r = mysql_query($q);
    $n = mysql_num_rows($r);
    while($o = mysql_fetch_object($r)){
    ?>
    <p class="card-text"><label class="obs">Escrito por: <b><?=utf8_encode($o->tecnico)?></b> em <b><?=dataBr($o->data)?></b></label><br><?=utf8_encode($o->observacao)?></p>
    <?php
    }
    ?>
  </div>

  <div class="card-body">
    <a fechar href="#" class="card-link">Fechar</a>
  </div>
</div>
<br>
<script type="text/javascript">

	$(function(){

		$("a[fechar]").click(function(){
			JanelaDetalhes.close();
		});

		<?php
		if($n and $d->status != 'c'){
		?>
		$("input[c<?=$_GET['codigo']?>]").attr("qt","<?=$n?>");
		$("input[c<?=$_GET['codigo']?>]").removeAttr("disabled");
		<?php
		}else{
		?>
		$("input[c<?=$_GET['codigo']?>]").attr("qt","0");
		$("input[c<?=$_GET['codigo']?>]").attr("disabled","disabled");
		<?php
		}
		?>


		$("button[obs]").click(function(){
			codigo = $(this).attr("codigo");
			observacao = $("#observacoes").val();
			if(observacao.trim()){
				$.ajax({
					url:"src/chamados/detalhes.php",
					type:"POST",
					data:{
						codigo:codigo,
						observacao:observacao,
					},
					success:function(dados){
						JanelaDetalhes.setContent(dados);
					}
				});
			}
		})

	});

</script>