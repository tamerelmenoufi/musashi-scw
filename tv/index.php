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
			border:solid 1px red;
		}
		.corpoTV{
			position:fixed;
			left:0;
			top:60px;
			right:0;
			height:60px;
			border:solid 1px red;
		}
	</style>
</head>
<body>


<div class="topoTV d-flex justify-content-between">
	<div>posicao 1</div>
	<div>posicao 2</div>
	<div>posicao 3</div>
</div>
<div class="corpoTV">Aplicação na página</div>

<script type="text/javascript">

	Carregando = (opc) => { $("#Carregando").css("display",(opc?opc:'block')) }

	$(function(){

		Carregando('none');



	})
</script>
</body>
</html>