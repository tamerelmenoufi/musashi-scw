<?php $home = true; include('../includes/includes.php'); ?>
<!DOCTYPE html>
<html>
<head>
	<title>SISTEMA - SCW</title>
	<?php include('../lib/header.php'); ?>
	<style>
		.topoTV{
			position:fixed;
			left:0;
			top:0;
			right:0;
			height:60px;
		}
	</style>
</head>
<body>
<div id="app">Aplicação na página</div>

<div class="topoTV d-flex justify-content-between">
	<div>posicao 1</div>
	<div>posicao 2</div>
	<div>posicao 3</div>
</div>


<script type="text/javascript">

	Carregando = (opc) => { $("#Carregando").css("display",(opc?opc:'block')) }

	$(function(){

		Carregando('none');



	})
</script>
</body>
</html>