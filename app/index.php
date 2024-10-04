<?php
	$home = true;
	include('../includes/includes.php');

	function periodo($dt){
		$mes = [
			'01'=>'jan',
			'02'=>'fev',
			'03'=>'mar',
			'04'=>'abr',
			'05'=>'mai',
			'06'=>'jun',
			'07'=>'jul',
			'08'=>'ago',
			'09'=>'set',
			'10'=>'out',
			'11'=>'nov',
			'12'=>'dez'
		];
		$d = explode("-",$dt);
		return strtoupper($mes[$d[1]])."/".$d[0];
	}

	$mes_passado = date("Y-m", mktime(0,0,0, date("m"), 1 - 1 , date("Y")));

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

	DATEDIFF (NOW(), a.data_abertura) as dias,

	a.peca,
	a.modelo,
	a.codigos as codigos_nome,


	tm.nome as time_nome,
	mt.nome as motivo_nome,
	s.nome as setor_nome,
	s.utm as utm,
    u.nome as utm_nome,
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
        left join utm u on s.utm = u.codigo
		left join tipos_manutencao t on a.tipo_manutencao = t.codigo
		left join maquinas m on a.maquina = m.codigo

		left join pecas p on a.peca = p.codigo
		left join modelos md on a.modelo = md.codigo
		/*left join codigos cd on a.codigos = cd.codigo*/

		left join time tm on a.time = tm.codigo
		left join motivos mt on a.motivo = mt.codigo
		left join login tc on a.tecnico = tc.codigo
		left join login f on a.funcionario = f.codigo
	/*where (a.status != 'c') or (a.status = 'c' and a.data_fechamento >= NOW() - INTERVAL 30 DAY)*/
	where a.data_abertura like '".date("Y-m")."%' and a.status in ('n', 'p') and a.time != '20'
		order by dias desc";
	$r = mysql_query($q);
	// exit();
	$TickDetalhe = [];
	$TickResumo = [];

	$visor = 0;
	while($d = mysql_fetch_object($r)){


		if($d->status != 'c' and $visor < 7){

			$visor++;

			$CorDetalhe[] = $cor[(($d->parada == 's' and $d->status == 'n')?$d->parada:$d->status)];
			$CorResumo[] = $cor[(($d->parada == 's' and $d->status == 'n')?$d->parada:$d->status)];
			$CorBorda[] = (($d->parada == 's')?'red':'yellow');

			$Codigo[] = $d->codigo;


			$TickDetalhe[] = "
					<div style='float:left; width:30%;'><b>Cadastrado ID:</b> <div class='detalhesTexto'>".str_pad($d->codigo, 8, "0", STR_PAD_LEFT)."</div></div>".
					"<div style='float:left; width:50%;'>".((dataBr($d->data_abertura))?"<b>Data: <span style='color:red'>{$d->dias} dias atraso</span></b><div class='detalhesTexto'>".dataBr($d->data_abertura)."</div>":false)."</div>".
					"<div style='float:left; width:20%;'>".(($d->status)?"<b>Situação:</b>
						<div class='detalhesTexto' style='color:{$cor[$d->status]}; font-weight:bold;'>".$titulo[$d->status]."</div>":false)."</div>".

					"<div style='float:left; width:60%;'><b>Peça:</b> <div class='detalhesTexto'>".utf8_encode($d->peca_nome)."</div></div>".
					"<div style='float:left; width:20%;'><b>Modelo:</b><div class='detalhesTexto'>".utf8_encode($d->modelo_nome)."</div></div>".
					"<div style='float:left; width:20%;'><b>Código:</b><div class='detalhesTexto'>".utf8_encode($d->codigos_nome)."</div></div>".

					"<div style='float:left; width:60%;'> <b>Setor:</b><div class='detalhesTexto'>".utf8_encode($d->setor_nome)." (".utf8_encode($d->utm_nome).")</div></div>".
					"<div style='float:left; width:40%;'> <b>Máquina:<span style='color:".(($d->parada == 's')?'red':'#333').";'> (".$parada[$d->parada].")</span></b><div class='detalhesTexto'>".utf8_encode($d->maquina_nome)."</div></div>".

					"<div style='float:left; width:50%;'>".(($d->time_nome)?"<b>Time:</b><div class='detalhesTexto'>".utf8_encode($d->time_nome)."</div>":false)."</div>".
					"<div style='float:left; width:50%;'>".(($d->motivo_nome)?"<b>Ocorrência:</b><div class='detalhesTexto' >".utf8_encode($d->motivo_nome)."</div>":false)."</div>".


				//    "<div style='width:100%;'> <b style='color:#a1a1a1;'>Tipo de Manutenção:</b><div>".utf8_encode($d->tipo_manutencao_nome)."</div></div>".
				"<div style='width:100%;'>".(($d->problema)?"<b>Problema:</b><div class='detalhesTexto' style='overflow: hidden; display: -webkit-box; -webkit-box-orient: vertical; -webkit-line-clamp: 2;' >".str_replace("\n"," ",utf8_encode($d->problema))."</div>":false)."</div>".

				"<div style='float:left; width:100%;'>".(($d->funcionario)?"<b>Funcionário:</b><div class='detalhesTexto' style='overflow: hidden; display: -webkit-box; -webkit-box-orient: vertical; -webkit-line-clamp: 1;'>".utf8_encode($d->funcionario)."</div>":false)."</div>".
				"<div style='float:left; width:100%;'>".(($d->tecnico)?"<b>Técnico:</b><div class='detalhesTexto' style='overflow: hidden; display: -webkit-box; -webkit-box-orient: vertical; -webkit-line-clamp: 1;'>".utf8_encode($d->tecnico)."</div>":false)."</div>".

				"<div style='width:100%;'>".(($d->observacao)?"<b>Observações:</b><div class='detalhesTexto'>".str_replace("\n"," ",$_POST['observacao'])."</div>":false)."</div><br>";

			$TickResumo[] = "<div style='float:left; width:40%;'><b >Cadastrado ID:</b> <div class='detalhesTexto'>".str_pad($d->codigo, 8, "0", STR_PAD_LEFT)."</div></div>".
							"<div style='float:left; width:60%;'>".(($d->status)?"<b >Situação:</b><div class='detalhesTexto'>".$titulo[$d->status]."</div>":false)."</div>".
							"<div style='float:left; width:100%;'> <b >Setor:</b><div class='detalhesTexto'>".utf8_encode($d->setor_nome)." (".utf8_encode($d->utm_nome).")</div></div>".
							"<div style='float:left; width:100%;'> <b >Máquina: (".$parada[$d->parada].")</b><div class='detalhesTexto'>".utf8_encode($d->maquina_nome)."</div></div>".
							"<div style='float:left; width:100%;'> <b >Time:</b><div class='detalhesTexto'>".utf8_encode($d->time_nome)."</div></div>".
							"<div style='float:left; width:100%;'> <b >Ocorrência:</b><div class='detalhesTexto'>".utf8_encode($d->motivo_nome)."</div></div>";
		}


	}


//////////////////////////////////MES ATUAL/////////////////////////////////////


	$query = "select 
					(SELECT count(*) FROM `chamados` where data_abertura like '".date("Y-m")."%' and time != '20') as ch, 
					(SELECT count(*) FROM `chamados` where data_abertura like '".date("Y-m")."%' and status in ('n', 'p') and time != '20') as pn, 
					(SELECT count(*) FROM `chamados` where data_abertura like '".date("Y-m")."%' and status in ('c') and time != '20') as cl,
					(SELECT count(*) FROM `chamados` where data_abertura like '".date("Y-m")."%' and status not in ('c') and parada = 's' and time != '20') as pd";
	$result = mysql_query($query);
	$t = mysql_fetch_object($result);
	$Qt['novos'] = $t->ch;
	$Qt['pendentes'] = $t->pn;
	$Qt['concluidos'] = $t->cl;
	$Qt['parados'] = $t->pd; 

//////////////////////////////////MES PASSADO/////////////////////////////////////


	$query = "select 
					(SELECT count(*) FROM `chamados` where data_abertura like '".$mes_passado."%' and time != '20') as ch, 
					(SELECT count(*) FROM `chamados` where data_abertura like '".$mes_passado."%' and status in ('n', 'p') and time != '20') as pn, 
					(SELECT count(*) FROM `chamados` where data_abertura like '".$mes_passado."%' and status in ('c') and time != '20') as cl,
					(SELECT count(*) FROM `chamados` where data_abertura like '".$mes_passado."%' and status not in ('c') and parada = 's' and time != '20') as pd";
	$result = mysql_query($query);
	$t = mysql_fetch_object($result);
	$MP['novos'] = $t->ch;
	$MP['pendentes'] = $t->pn;
	$MP['concluidos'] = $t->cl;
	$MP['parados'] = $t->pd; 
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
			bottom:130px;
			padding:5px;
			border:0px blue solid;
		}
		.rodapeTV{
			position:fixed;
			left:0;
			bottom:0;
			right:0;
			height:135px;
		}
		.slick-current{
			opacity:1 !important;
		}
		.statusDestaque{
			position:fixed;
			left:10px;
			top:45px;
			bottom:10px;
			width:30%;
			border-radius:10px;
			background:#eee !important;
			padding:15px;
		}

		.detalhesTexto{
			font-size:9px;
		}
		.listaDestaque div{
			margin:0;
			padding:0;
			margin-top:0px;
			font-size:9px;
		}
		.listaDestaque div div{
			margin:0;
			padding:0;
			margin-top:0px;
			font-size:11px;
			border:solid 0px red;
			min-height:20px;
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
			font-size:11px;
			text-overflow: ellipsis;
			white-space: nowrap;
			overflow: hidden;
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
			font-size:10px;
		}
		.Qt h1{
			width:100%;
			text-align:center;
			font-size:18px;
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
			font-size:10px;
			font-weight:bold;
			margin-top:0px;
			padding:0;
			margin:0;
		}
		.grafico div span{
			color:#a1a1a1;
			font-size:11px;
			font-weight:bold;
			margin-top:0px;
			padding:0;
			margin:0;
		}
		.grafico div div{
			height:15px;
			background-color:green;
			color:#fff;
			font-size:10px;
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
			font-size:12px;
			font-weight:bold;
		}
		button:{
			opacity:0!important;
		}


		table{
        width:calc(100% - 30px);
		margin-left:10px;
		}
		th{
			font-size:10px;
			font-family:verdana;   
			text-align:center;     
		}
		td{
			text-align:center;
			font-size:10px;
			font-family:verdana;
			color:#333;
			padding:3px;
		}
		.bg1{
			background-color:#fff;
		}
		.bg2{
			background-color:#ccc;
		}

		.h6{
			font-size:15px;
		}

	</style>
</head>
<body>


<div class="topoTV d-flex justify-content-between">
	<div>
		<img src="img/logo.png?logo" style="height:30px; margin:5px;">
	</div>
	<div>
		<div style="background-color:rgb(255,255,255,0.3); border-radius:5px; padding:2px; margin-top:10px; font-size:10px;">
			<i class="fa fa-square" style="color:blue;"></i> Chamados
			<i class="fa fa-square" style="color:orange; margin-left:10px;"></i> Pendente
			<i class="fa fa-square" style="color:red; margin-left:10px;"></i> Paradas
			<i class="fa fa-square" style="color:green; margin-left:10px;"></i> Concluído
			<!-- <i class="fa fa-square" style="color:yellow; margin-left:10px;"></i> Máquina Funcionando -->
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
		<div data-codigo="<?=$Codigo[$i]?>" class="listaDestaque" style="opacity:1; padding-top:5px; border-top:8px solid <?=$CorDetalhe[$i]?>; border-radius:10px; height:220px;"><?=$TickDetalhe[$i]?></div>
		<?php
		}
		?>
	</div>
</div>
<div class="RelatorioTV">
	<div class="row">
		<div class="col">
			<div class="Qt" style="background-color:blue">
				<div>Chamados-CH </div><h1><?=str_pad(trim($Qt['novos']) , 4 , '0' , STR_PAD_LEFT)?></h1>
			</div>
		</div>
		<div class="col">
			<div class="Qt" style="background-color:orange">
				<div>Pendentes-PD </div><h1><?=str_pad(trim($Qt['pendentes']) , 4 , '0' , STR_PAD_LEFT)?></h1>
			</div>
		</div>
		<div class="col">
			<div class="Qt" style="background-color:red">
				<div>Paradas-PR </div><h1><?=str_pad(trim($Qt['parados']) , 4 , '0' , STR_PAD_LEFT)?></h1>
			</div>
		</div>
		<div class="col">
			<div class="Qt" style="background-color:green">
				<div>Concluído-CL/<?=periodo(date("Y-m"))?></div><h1><?=str_pad(trim($Qt['concluidos']) , 4 , '0' , STR_PAD_LEFT)?></h1>
			</div>
		</div>
	</div>

	<div class="row" style="margin-top:-2px;">
		<div class="col-12" style="position:relative;">


		<?php
    ////////////////////////////////////////////////// UTMs ///////////////////////////////////////////////////
	$query = "select 
                    a.*,
					count(*) as qt,
                    u.nome as utm_nome
                from chamados a 
                    left join utm u on a.utm = u.codigo
                where data_abertura like '".date("Y-m")."%' and a.time != '20' group by a.utm, status order by qt desc";

    $result = mysql_query($query);
	while($d = mysql_fetch_object($result)){
		$utm['nome'][$d->utm_nome] = $d->utm_nome;
		$utm['qt'][$d->utm_nome][$d->status] += $d->qt;
	}
?>
<table cellspacing="0" cellpadding="0">
    <thead>
        <tr>
            <th colspan="4"><span class="h6">UTM</span></th>
        </tr>
        <tr>
            <th style="width:60%; text-align:left;">Nome</th>
            <th>CH</th>
            <th>PD</th>
            <th>CL</th>
        </tr>
    </thead>
    <tbody>
<?php
    $j = 0;
	$outros = [];
    foreach($utm['nome'] as $i => $v){
		// if($d->utm_nome){
        if($j%2 == 0){
            $bg = 'bg1';
        }else{
            $bg = 'bg2';
        }
		if($j<6){
?>
        <tr class="<?=$bg?>">
            <td style="text-align:left;"><?=(utf8_encode($utm['nome'][$i])?:('NÃO IDENTIFICADO'))?></td>
            <td><?=($utm['qt'][$utm['nome'][$i]]['n'] + $utm['qt'][$utm['nome'][$i]]['p'] + $utm['qt'][$utm['nome'][$i]]['c'])?></td>
            <td><?=($utm['qt'][$utm['nome'][$i]]['n'] + $utm['qt'][$utm['nome'][$i]]['p'])?></td>
            <td><?=$utm['qt'][$utm['nome'][$i]]['c']*1?></td>
        </tr>
<?php
		}else{
			$outros['nome'] = 'Demais Setores';
			$outros['ch'] += ($utm['qt'][$utm['nome'][$i]]['n'] + $utm['qt'][$utm['nome'][$i]]['p'] + $utm['qt'][$utm['nome'][$i]]['c']);
			$outros['pd'] += ($utm['qt'][$utm['nome'][$i]]['n'] + $utm['qt'][$utm['nome'][$i]]['p']);
			$outros['cl'] += $utm['qt'][$utm['nome'][$i]]['c']*1;

		}
    $j++;
	}

	if($outros){
?>
        <tr class="<?=$bg?>">
            <td style="text-align:left;"><?=($outros['nome'])?></td>
            <td><?=($outros['ch'])?></td>
            <td><?=($outros['pd'])?></td>
            <td><?=$outros['cl']?></td>
        </tr>
<?php
	}

    // }
?>
    </tbody>
</table>

</div>
<div class="col-6" style="position:relative;">

<?php
    ////////////////////////////////////////////////// SETORES ///////////////////////////////////////////////////

	$query = "select 
                    a.*,
					count(*) as qt,
                    s.nome as setor_nome,
                    u.nome as utm_nome
                from chamados a 
                    left join utm u on a.utm = u.codigo
                    left join setores s on a.setor = s.codigo
                where data_abertura like '".date("Y-m")."%' and a.time != '20' group by a.setor, status order by qt desc";

    $result = mysql_query($query);
	while($d = mysql_fetch_object($result)){
		$setor['nome']["{$d->setor_nome} / {$d->utm_nome}"] = "{$d->setor_nome} / {$d->utm_nome}";
		$setor['qt']["{$d->setor_nome} / {$d->utm_nome}"][$d->status] += $d->qt;
	}

?>
<table cellspacing="0" cellpadding="0" style="margin-top:10px;">
    <thead>
        <tr>
            <th colspan="4"><span class="h6">SETORES / UTM</span></th>
        </tr>
        <tr>
            <th style="width:60%; text-align:left;">Nome</th>
            <th>CH</th>
            <th>PD</th>
            <th>CL</th>
        </tr>
    </thead>
    <tbody>
	<?php
    $j = 0;
	$outros = [];
    foreach($setor['nome'] as $i => $v){
        if($j%2 == 0){
            $bg = 'bg1';
        }else{
            $bg = 'bg2';
        }
		if($j<6){
?>
        <tr class="<?=$bg?>">
            <td style="text-align:left;"><?=(utf8_encode($setor['nome'][$i])?:('NÃO IDENTIFICADO'))?></td>
            <td><?=($setor['qt'][$setor['nome'][$i]]['n'] + $setor['qt'][$setor['nome'][$i]]['p'] + $setor['qt'][$setor['nome'][$i]]['c'])?></td>
            <td><?=($setor['qt'][$setor['nome'][$i]]['n'] + $setor['qt'][$setor['nome'][$i]]['p'])?></td>
            <td><?=$setor['qt'][$setor['nome'][$i]]['c']*1?></td>
        </tr>
<?php
		}else{
			$outros['nome'] = 'Demais Setores';
			$outros['ch'] += ($setor['qt'][$setor['nome'][$i]]['n'] + $setor['qt'][$setor['nome'][$i]]['p'] + $setor['qt'][$setor['nome'][$i]]['c']);
			$outros['pd'] += ($setor['qt'][$setor['nome'][$i]]['n'] + $setor['qt'][$setor['nome'][$i]]['p']);
			$outros['cl'] += $setor['qt'][$setor['nome'][$i]]['c']*1;

		}
    $j++;
	}

	if($outros){
?>
		<tr class="<?=$bg?>">
			<td style="text-align:left;"><?=($outros['nome'])?></td>
			<td><?=($outros['ch'])?></td>
			<td><?=($outros['pd'])?></td>
			<td><?=$outros['cl']?></td>
		</tr>
<?php

	}
		
    // }
?>
    </tbody>
</table>

</div>
<div class="col-6" style="position:relative;">

<?php
    ////////////////////////////////////////////////// TIMES ///////////////////////////////////////////////////

	$query = "select 
                    a.*,
					count(*) as qt,
                    t.nome as time_nome
                from chamados a 
                    left join time t on a.time = t.codigo
                where data_abertura like '".date("Y-m")."%' and a.time != '20' group by a.time, status order by qt desc";

    $result = mysql_query($query);
	while($d = mysql_fetch_object($result)){
		$time['nome'][$d->time_nome] = $d->time_nome;
		$time['qt'][$d->time_nome][$d->status] += $d->qt;
	}

?>
<table cellspacing="0" cellpadding="0" style="margin-top:10px;">
    <thead>
        <tr>
            <th colspan="4"><span class="h6">TIMES DE ATUAÇÃO</span></th>
        </tr>
        <tr>
            <th style="width:60%; text-align:left;">Nome</th>
            <th>CH</th>
            <th>PD</th>
            <th>CL</th>
        </tr>
    </thead>
    <tbody>
	<?php
    $j = 0;
	$outros = [];
    foreach($time['nome'] as $i => $v){
        if($j%2 == 0){
            $bg = 'bg1';
        }else{
            $bg = 'bg2';
        }
		if($j<6){
?>
        <tr class="<?=$bg?>">
            <td style="text-align:left;"><?=(utf8_encode($time['nome'][$i])?:('NÃO IDENTIFICADO'))?></td>
            <td><?=($time['qt'][$time['nome'][$i]]['n'] + $time['qt'][$time['nome'][$i]]['p'] + $time['qt'][$time['nome'][$i]]['c'])?></td>
            <td><?=($time['qt'][$time['nome'][$i]]['n'] + $time['qt'][$time['nome'][$i]]['p'])?></td>
            <td><?=$time['qt'][$time['nome'][$i]]['c']*1?></td>
        </tr>
<?php
		}else{
			$outros['nome'] = 'Demais Times';
			$outros['ch'] += ($time['qt'][$time['nome'][$i]]['n'] + $time['qt'][$time['nome'][$i]]['p'] + $time['qt'][$time['nome'][$i]]['c']);
			$outros['pd'] += ($time['qt'][$time['nome'][$i]]['n'] + $time['qt'][$time['nome'][$i]]['p']);
			$outros['cl'] += $time['qt'][$time['nome'][$i]]['c']*1;

		}
    $j++;
	}

	if($outros){
?>
		<tr class="<?=$bg?>">
			<td style="text-align:left;"><?=($outros['nome'])?></td>
			<td><?=($outros['ch'])?></td>
			<td><?=($outros['pd'])?></td>
			<td><?=$outros['cl']?></td>
		</tr>
<?php

	}
		
    // }
?>
    </tbody>
</table>
</div>

<div style="margin-left:20px; margin-right:25px; width:calc(100% - 45px); margin-top:5px; border-top:solid 2px #fff">
	<span style="font-weight:bold; padding-left:10px;">Histórico do mês anterior</span>
	<div class="row">
		<div class="col">
			<div class="Qt" style="background-color:blue">
				<div>Chamados-CH </div><h1><?=str_pad(trim($MP['novos']) , 4 , '0' , STR_PAD_LEFT)?></h1>
			</div>
		</div>
		<div class="col">
			<div class="Qt" style="background-color:orange">
				<div>Pendentes-PD </div><h1><?=str_pad(trim($MP['pendentes']) , 4 , '0' , STR_PAD_LEFT)?></h1>
			</div>
		</div>
		<div class="col">
			<div class="Qt" style="background-color:red">
				<div>Paradas-PR </div><h1><?=str_pad(trim($MP['parados']) , 4 , '0' , STR_PAD_LEFT)?></h1>
			</div>
		</div>
		<div class="col">
			<div class="Qt" style="background-color:green">
				<div>Concluído-CL/<?=periodo($mes_passado)?></div><h1><?=str_pad(trim($MP['concluidos']) , 4 , '0' , STR_PAD_LEFT)?></h1>
			</div>
		</div>
	</div>
</div>








			<!-- <div class="graficos" style="position:absolute; right:0; left:20px;">
				<h5>Setores</h5>
				<?php
				arsort($Rlt['setor']['qt']);
				$i=0;
				foreach($Rlt['setor']['qt'] as $ind => $vet){
					if($Rlt['setor']['nome'][$ind] and $i < 7){
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
			</div> -->

<!-- 
			<div class="graficos" style="position:absolute; right:0; left:20px;">
				<h5>UTM's</h5>
				<?php
				arsort($Rlt['utm']['qt']);
				$i=0;
				foreach($Rlt['utm']['qt'] as $ind => $vet){
					if($Rlt['utm']['nome'][$ind] and $i < 7){
				?>
				<div class="grafico">
					<div class="rotulo"><?=$Rlt['utm']['nome'][$ind]?></div>
					<div class="d-flex justify-content-start">
						<div class="rotulo" style="width:<?=number_format(($Rlt['utm']['qt'][$ind]*100/$Rlt['utm']['tot']),0,false,false)?>%"></div>
						<span style="margin-left:3px; font-weight:normal;">[<?=$Rlt['utm']['qt'][$ind]?>] <?=number_format(($Rlt['utm']['qt'][$ind]*100/$Rlt['utm']['tot']),0,false,false)?>%</span>
					</div>
				</div>
				<?php
					$i++;
					}
				}
				?>
			</div> -->


		</div>
		<!-- <div class="col">
			<div class="graficos">
				<h5>Manutenção</h5>
				<?php
				arsort($Rlt['tipo_manutencao']['qt']);
				$i=0;
				foreach($Rlt['tipo_manutencao']['qt'] as $ind => $vet){
					if($Rlt['tipo_manutencao']['nome'][$ind] and $i < 7){
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

		<!-- <div class="col" style="position:relative;">
			<div class="graficos" style="position:absolute; left:5px; right:5px;">
				<h5>Time de Atuação</h5>
				<?php
				arsort($Rlt['time']['qt']);
				$i=0;
				foreach($Rlt['time']['qt'] as $ind => $vet){
					if($Rlt['time']['nome'][$ind] and $i < 7){
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
		</div> -->



		<!-- <div class="col" style="position:relative;">
			<div class="graficos" style="position:absolute; left:0; right:20px;">
				<h5>Ocorrência</h5>
				<?php
				arsort($Rlt['motivo']['qt']);
				$i=0;
				foreach($Rlt['motivo']['qt'] as $ind => $vet){
					if($Rlt['motivo']['nome'][$ind] and $i < 7){
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
		</div> -->


</div>
<!-- <div class="rodapeTV">

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

</div> -->
<script type="text/javascript">

	Carregando = (opc) => { $("#Carregando").css("display",(opc?opc:'block')) }

	$(function(){

		Carregando('none');

		// $('.slider-for').slick({
		// 	slidesToShow: 1,
		// 	slidesToScroll: 1,
		// 	arrows: false,
		// 	fade: true,
		// 	asNavFor: '.slider-nav'
		// });
		// $('.slider-nav').slick({
		// 	slidesToShow: 5,
		// 	slidesToScroll: 1,
		// 	asNavFor: '.slider-for',
		// 	dots: false,
		// 	centerMode: false,
		// 	focusOnSelect: true,
		// 	autoplay: true,
  		// 	autoplaySpeed: 5000,
		// });


		$('.slider-for').slick({
			slidesToShow: 1,
			slidesToScroll: 1,
			rows:2,
			vertical:true,
			dots: false,
			centerMode: false,
			focusOnSelect: true,
			autoplay: true,
  			autoplaySpeed: 20000,
		});

		// $('.lista_maquinas_paradas').slick({
		// 	slidesToShow: 5,
		// 	slidesToScroll: 1,
		// 	dots: false,
		// 	centerMode: false,
		// 	focusOnSelect: true,
		// 	autoplay: true,
  		// 	autoplaySpeed: 2000,
		// });


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
		}, 60000);

	})
</script>
</body>
</html>