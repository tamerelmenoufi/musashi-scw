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
		's' => 'red',
		'n' => 'blue',
		'p' => 'orange',
		'c' => 'green',
  	);

	$parada = array(
		's' => 'Parada',
		'n' => 'Funcionando',
  	);


	$q = "SELECT count(*) as qt, max(UNIX_TIMESTAMP(data_atualizacao)) as tempo FROM `chamados`";
	$r = mysql_query($q);
    $st = mysql_fetch_object($r);

	$q = "SELECT
	a.codigo,
	a.data_abertura,
	a.status,
	a.time,
	a.motivo,
	a.parada,
	a.setor,
	a.tipo_manutencao,
	a.maquina,

	a.peca,
	a.modelo,
	a.codigos as codigos_nome,


	tm.nome as time_nome,
	mt.nome as motivo_nome,
	s.nome as setor_nome,
	m.nome as maquina_nome,

	p.nome as peca_nome,
	md.nome as modelo_nome,
	/*cd.nome as codigos_nome,*/


	t.nome as tipo_manutencao_nome,
	a.problema,
	f.nome as funcionario,
	tc.nome as tecnico
		FROM chamados a
		left join setores s on a.setor = s.codigo
		left join tipos_manutencao t on a.tipo_manutencao = t.codigo
		left join maquinas m on a.maquina = m.codigo

		left join pecas p on a.peca = p.codigo
		left join modelos md on a.modelo = md.codigo
		/*left join codigos cd on a.codigos = cd.codigo*/


		left join time tm on a.time = tm.codigo
		left join motivos mt on a.motivo = mt.codigo
		left join login tc on a.tecnico = tc.codigo
		left join login f on a.funcionario = f.codigo
	where (a.status != 'c') or (a.status = 'c' and a.data_fechamento >= NOW() - INTERVAL 1 DAY)
		order by a.codigo desc";
	$r = mysql_query($q);
	// exit();
	$TickDetalhe = [];
	$TickResumo = [];

	$Qt['novos'] = 0;
	$Qt['pendentes'] = 0;
	$Qt['concluidos'] = 0;
	$Qt['parados'] = 0;

	while($d = mysql_fetch_object($r)){


		$Qt['novos'] = (($d->status == 'n')?($Qt['novos'] = ($Qt['novos'] + 1)):($Qt['novos']));
		$Qt['pendentes'] = (($d->status == 'p')?($Qt['pendentes'] = ($Qt['pendentes'] + 1)):($Qt['pendentes']));
		$Qt['concluidos'] = (($d->status == 'c')?($Qt['concluidos'] = ($Qt['concluidos'] + 1)):($Qt['concluidos']));
		$Qt['parados'] = (($d->parada == 's' and $d->status != 'c')?($Qt['parados'] = ($Qt['parados'] + 1)):($Qt['parados']));

		//Setores
		$Rlt['setor']['nome'][$d->setor] = utf8_encode($d->setor_nome);
		$Rlt['setor']['qt'][$d->setor] = ($Rlt['setor']['qt'][$d->setor] + 1);
		$Rlt['setor']['tot'] = ($Rlt['setor']['tot'] + 1);

		// //Setores
		// $Rlt['tipo_manutencao']['nome'][$d->tipo_manutencao] = utf8_encode($d->tipo_manutencao_nome);
		// $Rlt['tipo_manutencao']['qt'][$d->tipo_manutencao] = ($Rlt['tipo_manutencao']['qt'][$d->tipo_manutencao] + 1);
		// $Rlt['tipo_manutencao']['tot'] = ($Rlt['tipo_manutencao']['tot'] + 1);

		//Motivos
		$Rlt['motivo']['nome'][$d->motivo] = utf8_encode($d->motivo_nome);
		$Rlt['motivo']['qt'][$d->motivo] = ($Rlt['motivo']['qt'][$d->motivo] + 1);
		$Rlt['motivo']['tot'] = ($Rlt['motivo']['tot'] + 1);


		//Peças
		$Rlt['peca']['nome'][$d->peca] = utf8_encode($d->peca_nome);
		$Rlt['peca']['qt'][$d->peca] = ($Rlt['peca']['qt'][$d->peca] + 1);
		$Rlt['peca']['tot'] = ($Rlt['peca']['tot'] + 1);


		//Modelos
		$Rlt['modelo']['nome'][$d->modelo] = utf8_encode($d->modelo_nome);
		$Rlt['modelo']['qt'][$d->modelo] = ($Rlt['modelo']['qt'][$d->modelo] + 1);
		$Rlt['modelo']['tot'] = ($Rlt['modelo']['tot'] + 1);


		//Códigos
		$Rlt['codigos']['nome'][$d->codigos] = utf8_encode($d->codigos_nome);
		$Rlt['codigos']['qt'][$d->codigos] = ($Rlt['codigos']['qt'][$d->codigos] + 1);
		$Rlt['codigos']['tot'] = ($Rlt['codigos']['tot'] + 1);


		//Time
		$Rlt['time']['nome'][$d->time] = utf8_encode($d->time_nome);
		$Rlt['time']['qt'][$d->time] = ($Rlt['time']['qt'][$d->time] + 1);
		$Rlt['time']['tot'] = ($Rlt['time']['tot'] + 1);

		//Paradas
		if($d->parada == 's' and $d->status != 'c'){
			$Rlt['paradas'][] = utf8_encode($d->maquina_nome);
		}

		if($d->status != 'c'){

			$CorDetalhe[] = $cor[(($d->parada == 's' and $d->status == 'n')?$d->parada:$d->status)];
			$CorResumo[] = $cor[(($d->parada == 's' and $d->status == 'n')?$d->parada:$d->status)];
			$CorBorda[] = (($d->parada == 's')?'red':'yellow');

			$Codigo[] = $d->codigo;


			$TickDetalhe[] = "
					<div style='float:left; width:25%;'><b>Cadastrado ID:</b> <div >".str_pad($d->codigo, 8, "0", STR_PAD_LEFT)."</div></div>".
					"<div style='float:left; width:50%;'>".((dataBr($d->data_abertura))?"<b'>Data:</b><div >".dataBr($d->data_abertura)."</div>":false)."</div>".
					"<div style='float:left; width:25%;'>".(($d->status)?"<b>Situação:</b>
						<div  style='color:{$cor[$d->status]}; font-weight:bold;'>".$titulo[$d->status]."</div>":false)."</div>".

					"<div style='float:left; width:60%;'><b'>Peça:</b> <div >".utf8_encode($d->peca_nome)."</div></div>".
					"<div style='float:left; width:20%;'><b'>Modelo:</b><div >".utf8_encode($d->modelo_nome)."</div></div>".
					"<div style='float:left; width:20%;'><b>Código:</b><div >".utf8_encode($d->codigos_nome)."</div></div>".

					"<div style='float:left; width:50%;'> <b>Setor:</b><div >".utf8_encode($d->setor_nome)."</div></div>".
					"<div style='float:left; width:50%;'> <b>Máquina:<span style='color:".(($d->parada == 's')?'red':'#333').";'> (".$parada[$d->parada].")</span></b><div >".utf8_encode($d->maquina_nome)."</div></div>".

					"<div style='float:left; width:50%;'>".(($d->time_nome)?"<b>Time:</b><div >".utf8_encode($d->time_nome)."</div>":false)."</div>".
					"<div style='float:left; width:50%;'>".(($d->motivo_nome)?"<b>Ocorrência:</b><div >".utf8_encode($d->motivo_nome)."</div>":false)."</div>".


				//    "<div style='width:100%;'> <b style='color:#a1a1a1;'>Tipo de Manutenção:</b><div>".utf8_encode($d->tipo_manutencao_nome)."</div></div>".
				"<div style='width:100%;'>".(($d->problema)?"<b>Problema:</b><div >".str_replace("\n"," ",utf8_encode($d->problema))."</div>":false)."</div>".

				"<div style='float:left; width:50%;'>".(($d->funcionario)?"<b>Funcionário:</b><div >".utf8_encode($d->funcionario)."</div>":false)."</div>".
				"<div style='float:left; width:50%;'>".(($d->tecnico)?"<b>Técnico:</b><div >".utf8_encode($d->tecnico)."</div>":false)."</div>".

				"<div style='width:100%;'>".(($d->observacao)?"<b>Observações:</b><div >".str_replace("\n"," ",$_POST['observacao'])."</div>":false)."</div><br>";

			$TickResumo[] = "<div style='float:left; width:40%;'><b >Cadastrado ID:</b> <div>".str_pad($d->codigo, 8, "0", STR_PAD_LEFT)."</div></div>".
							"<div style='float:left; width:60%;'>".(($d->status)?"<b >Situação:</b><div>".$titulo[$d->status]."</div>":false)."</div>".
							"<div style='float:left; width:100%;'> <b >Setor:</b><div>".utf8_encode($d->setor_nome)."</div></div>".
							"<div style='float:left; width:100%;'> <b >Máquina: (".$parada[$d->parada].")</b><div>".utf8_encode($d->maquina_nome)."</div></div>".
							"<div style='float:left; width:100%;'> <b >Time:</b><div>".utf8_encode($d->time_nome)."</div></div>".
							"<div style='float:left; width:100%;'> <b >Ocorrência:</b><div>".utf8_encode($d->motivo_nome)."</div></div>";
		}


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
			height:40px;
			background-color:#6a6a6a;
			color:#fff;
		}
		.corpoTV{
			position:fixed;
			left:0;
			top:40px;
			right:0;
			bottom:114px;
		}
		.RelatorioTV{
			position:fixed;
			right:0;
			top:35px;
			left:calc(30% + 10px);
			width:70%;
			bottom:200px;
			padding:5px;
			border:0px blue solid;
		}
		.rodapeTV{
			position:fixed;
			left:0;
			bottom:0;
			right:0;
			height:200px;
		}
		.slick-current{
			opacity:1 !important;
		}
		.statusDestaque{
			position:fixed;
			left:10px;
			top:45px;
			bottom:111px;
			width:30%;
			border-radius:10px;
			background:#eee !important;
			padding:15px;
		}

		.listaDestaque div{
			margin:0;
			padding:0;
			margin-top:0px;
			font-size:7px;
		}
		.listaDestaque div div{
			margin:0;
			padding:0;
			margin-top:0px;
			font-size:8px;
		}
		.listaDestaque div b{
			margin:0;
			padding:0;
			margin-top:0px;
			color:#a1a1a1;
		}

		.listaResumo div{
			margin:0;
			padding:0;
			margin-top:-10px;
		}
		.listaResumo b{
			font-size:10px;
		}
		.listaResumo div div{
			margin:0;
			padding:0;
			margin-top:-7px;
			font-size:12px;
		}
		.listaResumo div b{
			margin:0;
			padding:0;
			margin-top:-9px;
		}
		.Qt{
			margin:5px;
			border-radius:5px;
			padding:5px;
			height:45px;
			color:#fff;
		}
		.Qt div{
			font-size:8px;
		}
		.Qt h1{
			width:100%;
			text-align:center;
			font-size:16px;
		}
		.graficos{
			background-color:#fff;
			border-radius:10px;
			padding:3px;

		}
		.grafico{
			margin-top:2px;
		}
		.graficos h5{
			font-size:10px;
			margin:0;
			padding:0;
		}
		.grafico > .rotulo{
			color:#a1a1a1;
			font-size:8px;
			font-weight:bold;
			margin-top:0px;
			padding:0;
			margin:0;
		}
		.grafico div span{
			color:#a1a1a1;
			font-size:9px;
			font-weight:bold;
			margin-top:0px;
			padding:0;
			margin:0;
		}
		.grafico div div{
			height:10px;
			background-color:green;
			color:#fff;
			font-size:7px;
			margin-top:0px;
			padding:2px;
			margin:0;
			border-radius:3px;
			text-align:right;
		}
		.lista_maquinas{
			background-color:#eee;
			margin:5px;
			margin-top:0;
			border-radius:10px;
		}
		.lista_maquinas h5{
			margin:5px;
			margin-bottom:0;
			padding-bottom:0;
			font-size:10px;
		}
		.lista_maquinas_paradas div{
			text-align:center;
			color:#fff;
			font-size:10px;
			font-weight:bold;
		}
		button:{
			opacity:0!important;
		}

	</style>
</head>
<body>


<div class="topoTV d-flex justify-content-between">
	<div>
		<img src="img/logo.png" style="height:30px; margin:5px;">
	</div>
	<div>
		<div style="background-color:rgb(255,255,255,0.3); border-radius:5px; padding:2px; margin-top:10px; font-size:10px;">
			<i class="fa fa-square" style="color:blue;"></i> Novo
			<i class="fa fa-square" style="color:orange; margin-left:10px;"></i> Pendente
			<i class="fa fa-square" style="color:green; margin-left:10px;"></i> Concluído
			<i class="fa fa-square" style="color:red; margin-left:10px;"></i> Máquina Parada
			<i class="fa fa-square" style="color:yellow; margin-left:10px;"></i> Máquina Funcionando
		</div>
	</div>
	<div>
		<div style="margin-top:10px;">
			<h5 style="font-size:15px;">CHAMADA SCW (STOP CALL WAIT)</h5>
		</div>
	</div>
	<div>
		<div class="dataHora" style="margin-top:0px; padding:10px; font-size:10px;">
			<?=date("d/m/Y H:i")?>
		</div>
	</div>
</div>
<div class="corpoTV">

	<div class="slider-for statusDestaque">
		<?php
		for($i=0;$i<count($TickDetalhe);$i++){
		?>
		<div data-codigo="<?=$Codigo[$i]?>" class="listaDestaque" style="opacity:0.5; padding-top:5px; border-top:8px solid <?=$CorDetalhe[$i]?>; border-radius:10px;"><?=$TickDetalhe[$i]?></div>
		<?php
		}
		?>
	</div>
</div>
<div class="RelatorioTV">
	<div class="row">
		<div class="col">
			<div class="Qt" style="background-color:blue">
				<div>Novos</div><h1><?=str_pad(trim($Qt['novos']) , 4 , '0' , STR_PAD_LEFT)?></h1>
			</div>
		</div>
		<div class="col">
			<div class="Qt" style="background-color:orange">
				<div>Em Andamento</div><h1><?=str_pad(trim($Qt['pendentes']) , 4 , '0' , STR_PAD_LEFT)?></h1>
			</div>
		</div>
		<div class="col">
			<div class="Qt" style="background-color:red">
				<div>Máquinas Paradas</div><h1><?=str_pad(trim($Qt['parados']) , 4 , '0' , STR_PAD_LEFT)?></h1>
			</div>
		</div>
		<div class="col">
			<div class="Qt" style="background-color:green">
				<div>Concluído (últimas 24H)</div><h1><?=str_pad(trim($Qt['concluidos']) , 4 , '0' , STR_PAD_LEFT)?></h1>
			</div>
		</div>
	</div>
	<?php
	if($Rlt['paradas']){
	?>
	<div class="row" style="margin-top:-5px;">
		<div class="col">
			<div class="lista_maquinas">
				<h5 >Máquinas Paradas</h5>
				<div class="lista_maquinas_paradas">
				<?php

					foreach($Rlt['paradas'] as $ind => $maq){
						// for($i=0;$i<20;$i++){
				?>
					<div style="margin:5px; border-radius:5px; background-color:red; padding:5px;"><?=$maq?></div>
				<?php
						// }
					}

				?>
				</div>
			</div>
		</div>
	</div>
	<?php
	}
	?>
	<div class="row" style="margin-top:-2px;">
		<div class="col" style="position:relative;">
			<div class="graficos" style="position:absolute; right:0; left:20px;">
				<h5>Setores</h5>
				<?php
				arsort($Rlt['setor']['qt']);
				$i=0;
				foreach($Rlt['setor']['qt'] as $ind => $vet){
					if($Rlt['setor']['nome'][$ind] and $i < 5){
				?>
				<div class="grafico">
					<div class="rotulo"><?=$Rlt['setor']['nome'][$ind]?></div>
					<div class="d-flex justify-content-start">
						<div class="rotulo" style="width:<?=number_format(($Rlt['setor']['qt'][$ind]*100/$Rlt['setor']['tot']),0,false,false)?>%"></div>
						<span style="margin-left:3px; font-weight:normal;">[<?=$Rlt['setor']['qt'][$ind]?>] <?=number_format(($Rlt['setor']['qt'][$ind]*100/$Rlt['setor']['tot']),0,false,false)?>%</span>
					</div>
				</div>
				<?php
					$i++;
					}
				}
				?>
			</div>
		</div>
		<!-- <div class="col">
			<div class="graficos">
				<h5>Manutenção</h5>
				<?php
				arsort($Rlt['tipo_manutencao']['qt']);
				$i=0;
				foreach($Rlt['tipo_manutencao']['qt'] as $ind => $vet){
					if($Rlt['tipo_manutencao']['nome'][$ind] and $i < 5){
				?>
				<div class="grafico">
					<span><?=$Rlt['tipo_manutencao']['nome'][$ind]?></span>
					<div class="d-flex justify-content-start">
						<div style="width:<?=number_format(($Rlt['tipo_manutencao']['qt'][$ind]*100/$Rlt['tipo_manutencao']['tot']),0,false,false)?>%"></div>
						<span style="margin-left:3px; font-weight:normal;">[<?=$Rlt['tipo_manutencao']['qt'][$ind]?>] <?=number_format(($Rlt['tipo_manutencao']['qt'][$ind]*100/$Rlt['tipo_manutencao']['tot']),0,false,false)?>%</span>
					</div>
				</div>
				<?php
					$i++;
					}
				}
				?>
			</div>
		</div> -->

		<div class="col" style="position:relative;">
			<div class="graficos" style="position:absolute; left:5px; right:5px;">
				<h5>Time de Atuação</h5>
				<?php
				arsort($Rlt['time']['qt']);
				$i=0;
				foreach($Rlt['time']['qt'] as $ind => $vet){
					if($Rlt['time']['nome'][$ind] and $i < 5){
				?>
				<div class="grafico">
					<div class="rotulo"><?=$Rlt['time']['nome'][$ind]?></div>
					<div class="d-flex justify-content-start">
						<div class="rotulo" style="width:<?=number_format(($Rlt['time']['qt'][$ind]*100/$Rlt['time']['tot']),0,false,false)?>%"></div>
						<span style="margin-left:3px; font-weight:normal;">[<?=$Rlt['time']['qt'][$ind]?>] <?=number_format(($Rlt['time']['qt'][$ind]*100/$Rlt['time']['tot']),0,false,false)?>%</span>
					</div>
				</div>
				<?php
					$i++;
					}
				}
				?>
			</div>
		</div>



		<div class="col" style="position:relative;">
			<div class="graficos" style="position:absolute; left:0; right:20px;">
				<h5>Ocorrência</h5>
				<?php
				arsort($Rlt['motivo']['qt']);
				$i=0;
				foreach($Rlt['motivo']['qt'] as $ind => $vet){
					if($Rlt['motivo']['nome'][$ind] and $i < 5){
				?>
				<div class="grafico">
					<div class="rotulo"><?=$Rlt['motivo']['nome'][$ind]?></div>
					<div class="d-flex justify-content-start">
						<div class="rotulo" style="width:<?=number_format(($Rlt['motivo']['qt'][$ind]*100/$Rlt['motivo']['tot']),0,false,false)?>%"></div>
						<span style="margin-left:3px; font-weight:normal;">[<?=$Rlt['motivo']['qt'][$ind]?>] <?=number_format(($Rlt['motivo']['qt'][$ind]*100/$Rlt['motivo']['tot']),0,false,false)?>%</span>
					</div>
				</div>
				<?php
					$i++;
					}
				}
				?>
			</div>
		</div>


	</div>
</div>
<div class="rodapeTV">

	<div class="slider-nav">
		<?php
		for($i=0;$i<count($TickResumo);$i++){
			// $Codigo[$i]
		?>
		<div class="listaResumo" style="margin:3px; padding:5px; text-align:left; border-radius:10px; opacity:0.5; color:#fff; background-color:<?=$CorResumo[$i]?>; border-right:solid 10px <?=$CorBorda[$i]?>;"><?=$TickResumo[$i]?></div>
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
			slidesToShow: 5,
			slidesToScroll: 1,
			asNavFor: '.slider-for',
			dots: false,
			centerMode: false,
			focusOnSelect: true,
			autoplay: true,
  			autoplaySpeed: 5000,
		});




		$('.lista_maquinas_paradas').slick({
			slidesToShow: 5,
			slidesToScroll: 1,
			dots: false,
			centerMode: false,
			focusOnSelect: true,
			autoplay: true,
  			autoplaySpeed: 2000,
		});


		setInterval(() => {
			$.ajax({
				url:"update.php",
				type:"POST",
				data:{
					qt:'<?=$st->qt?>',
					tempo:'<?=$st->tempo?>'
				},
				dataType:"json",
				success:function(dados){
					console.log(dados.status)
					if(dados.status == true){
						window.location.href='./';
					}
				}
			});
		}, 50000);


		setInterval(() => {
			$.ajax({
				url:"hora.php",
				success:function(dados){
					$(".dataHora").html(dados);
				}
			});
		}, 10000);

	})
</script>
</body>
</html>