<?php
	$home = true;
	include('../includes/includes.php');

    $titulo = array(
		't' => '',
		'n' => 'Novo',
		'p' => 'Pendente',
		'c' => 'Concluído',
  	);

	$cor = array(
		't' => '',
		'n' => 'red',
		'p' => 'orange',
		'c' => 'green',
  	);

	$q = "SELECT
	a.codigo,
	a.status,
	a.time,
	a.motivo,
	tm.nome as time_nome,
	mt.nome as motivo_nome,
	s.nome as setor,
	m.nome as maquina,
	t.nome as tipo_manutencao,
	a.problema,
	f.nome as funcionario,
	tc.nome as tecnico
		FROM chamados a
		left join setores s on a.setor = s.codigo
		left join tipos_manutencao t on a.tipo_manutencao = t.codigo
		left join maquinas m on a.maquina = m.codigo
		left join time tm on a.time = tm.codigo
		left join motivos mt on a.motivo = mt.codigo
		left join login tc on a.tecnico = tc.codigo
		left join login f on a.funcionario = f.codigo
		order by a.codigo desc limit 20";
	$r = mysql_query($q);

	$TickDetalhe = [];
	$TickResumo = [];

	while($d = mysql_fetch_object($r)){

		$CorDetalhe[] = $cor[$d->status];
		$CorResumo[] = $cor[$d->status];

        $TickDetalhe[] = "<b>Cadastrado ID</b>: ".str_pad($d->codigo, 8, "0", STR_PAD_LEFT).
               "<br> <b>SETOR</b>: ".utf8_encode($d->setor).
               "<br> <b>MÁQUINA</b>: ".utf8_encode($d->maquina).
               "<br> <b>TIPO DE MANUTENÇÃO</b>: ".utf8_encode($d->tipo_manutencao).
               (($d->problema)?"<br> <b>PROBLEMA</b>: ".str_replace("\n"," ",utf8_encode($d->problema)):false).
               (($d->funcionario)?"<br> <b>FUNCIONÁRIO</b>: ".utf8_encode($d->funcionario):false).
               (($d->tecnico)?"<br> <b>TÉCNICO</b>: ".utf8_encode($d->tecnico):false).

               (($d->time_nome)?"<br> <b>TIME</b>: ".utf8_encode($d->time_nome):false).
               (($d->motivo_nome)?"<br> <b>MOTIVO</b>: ".utf8_encode($d->motivo_nome):false).


               (($d->status)?"<br> <b>SITUAÇÃO</b>: ".$titulo[$d->status]:false).
               (($d->observacao)?"<br> <b>OBSERVAÇÕES</b>: ".str_replace("\n"," ",$_POST['observacao']):false);

		$TickResumo[] = "<b>Cadastrado ID</b>: ".str_pad($d->codigo, 8, "0", STR_PAD_LEFT).
               "<br> <b>SETOR</b>: ".utf8_encode($d->setor).
               "<br> <b>MÁQUINA</b>: ".utf8_encode($d->maquina).
			   (($d->status)?"<br> <b>SITUAÇÃO</b>: ".$titulo[$d->status]:false);



	}

?>
<!DOCTYPE html>
<html>
<head>
	<title>SISTEMA - SCW</title>
	<?php include('../lib/header.php'); ?>
	<style>
		body{
			background-color:#ccc;
		}
		.topoTV{
			position:fixed;
			left:0;
			top:0;
			right:0;
			height:60px;
			border:solid 1px red;
		}
		.corpoTV{
			position:fixed;
			left:0;
			top:60px;
			right:0;
			bottom:200px;
			border:solid 1px green;
		}
		.rodapeTV{
			position:fixed;
			left:0;
			bottom:0;
			right:0;
			height:200px;
			border:solid 1px blue;
		}
		.slick-current{
			opacity:1 !important;
		}
		.statusDestaque{
			position:fixed;
			left:10px;
			top:70px;
			bottom:220px;
			width:30%;
			border-radius:10px;
			background:#eee !important;
			padding:15px;
		}
	</style>
</head>
<body>


<div class="topoTV d-flex justify-content-between">
	<div>
		<img src="img/logo.png" style="height:50px; margin:5px;">
	</div>
	<div>
		<i class="fas fa-square" style="color:green;"></i> Atendido
		<i class="fas fa-square" style="color:orange;"></i> Em Andamento
		<i class="fas fa-square" style="color:red;"></i> Novo
	</div>
	<div>posicao 3</div>
</div>
<div class="corpoTV">

	<div class="slider-for statusDestaque">
		<?php
		for($i=0;$i<count($TickDetalhe);$i++){
		?>
		<div style="opacity:0.5; padding-top:5px; border-top:8px solid <?=$CorDetalhe[$i]?>; border-radius:10px;"><?=$TickDetalhe[$i]?></div>
		<?php
		}
		?>
	</div>

</div>
<div class="rodapeTV">

	<div class="slider-nav">
		<?php
		for($i=0;$i<count($TickResumo);$i++){
		?>
		<div style="border:solid 1px #ccc; margin:5px; padding:10px; text-align:center; border-radius:10px; opacity:0.5; background-color:<?=$CorResumo[$i]?>; color:#fff;"><?=$TickResumo[$i]?></div>
		<?php
		}
		?>
	</div>

</div>
<script type="text/javascript">

	Carregando = (opc) => { $("#Carregando").css("display",(opc?opc:'block')) }

	$(function(){

		Carregando('none');



		$('.slider-for').slick({
			slidesToShow: 1,
			slidesToScroll: 1,
			arrows: false,
			fade: true,
			asNavFor: '.slider-nav'
		});
		$('.slider-nav').slick({
			slidesToShow: 4,
			slidesToScroll: 1,
			asNavFor: '.slider-for',
			dots: true,
			centerMode: false,
			focusOnSelect: true,
			autoplay: true,
  			autoplaySpeed: 5000,
		});



	})
</script>
</body>
</html>