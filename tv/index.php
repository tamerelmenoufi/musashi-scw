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

        $TickDetalhe[] = "
				<div><b style='color:#a1a1a1; font-size:10px;''>Cadastrado ID:</b> <div>".str_pad($d->codigo, 8, "0", STR_PAD_LEFT)."</div></div>".
               "<div> <b style='color:#a1a1a1; font-size:10px;'>SETOR</b>:<div>".utf8_encode($d->setor)."</div></div>".
               "<div> <b style='color:#a1a1a1; font-size:10px;'>MÁQUINA</b>:<div>".utf8_encode($d->maquina)."</div></div>".
               "<div> <b style='color:#a1a1a1; font-size:10px;'>TIPO DE MANUTENÇÃO</b>:<div>".utf8_encode($d->tipo_manutencao)."</div></div>".
               "<div>".(($d->problema)?"<b style='color:#a1a1a1; font-size:10px;'>PROBLEMA</b>:<div>".str_replace("\n"," ",utf8_encode($d->problema))."</div>":false)."</div>".
               "<div>".(($d->funcionario)?"<b style='color:#a1a1a1; font-size:10px;'>FUNCIONÁRIO</b>:<div>".utf8_encode($d->funcionario)."</div>":false)."</div>".
               "<div>".(($d->tecnico)?"<b style='color:#a1a1a1; font-size:10px;'>TÉCNICO</b>:<div>".utf8_encode($d->tecnico)."</div>":false)."</div>".

               "<div>".(($d->time_nome)?"<b style='color:#a1a1a1; font-size:10px;'>TIME</b>: <div>".utf8_encode($d->time_nome)."</div>":false)."</div>".
               "<div>".(($d->motivo_nome)?"<b style='color:#a1a1a1; font-size:10px;'>MOTIVO</b>: <div>".utf8_encode($d->motivo_nome)."</div>":false)."</div>".


               "<div>".(($d->status)?"<b style='color:#a1a1a1; font-size:10px;'>SITUAÇÃO</b>: <div>".$titulo[$d->status]."</div>":false)."</div>".
               "<div>".(($d->observacao)?"<b style='color:#a1a1a1; font-size:10px;'>OBSERVAÇÕES</b>: <div>".str_replace("\n"," ",$_POST['observacao'])."</div>":false)."</div>";

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
			background-color:#6a6a6a;
			color:#fff;
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
		.listaDestaque div{
			margin:0;
			padding:0;
		}
		.listaDestaque div div{
			margin:0;
			padding:0;
			margin-top:-10px;
		}
		.listaDestaque div b{
			margin:0;
			padding:0;
		}
	</style>
</head>
<body>


<div class="topoTV d-flex justify-content-between">
	<div>
		<img src="img/logo.png" style="height:50px; margin:5px;">
	</div>
	<div>
		<div style="background-color:rgb(255,255,255,0.3); border-radius:5px; padding:2px; margin-top:15px;">
			<i class="fa fa-square" style="color:green;"></i> Atendido
			<i class="fa fa-square" style="color:orange; margin-left:10px;"></i> Em Andamento
			<i class="fa fa-square" style="color:red; margin-left:10px;"></i> Novo
		</div>
	</div>
	<div>
		<div style="margin-top:15px;">
			<h5>CHAMADA SCW (STOP CALL WAIT)</h5>
		</div>
	</div>
	<div>
		<div style="margin-top:10px; padding:10px;">
			<?=date("d/m/Y H:i")?>
		</div>
	</div>
</div>
<div class="corpoTV">

	<div class="slider-for statusDestaque">
		<?php
		for($i=0;$i<count($TickDetalhe);$i++){
		?>
		<div class="listaDestaque" style="opacity:0.5; padding-top:5px; border-top:8px solid <?=$CorDetalhe[$i]?>; border-radius:10px;"><?=$TickDetalhe[$i]?></div>
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