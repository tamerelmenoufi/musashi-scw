<?php $home = true; include('../includes/includes.php'); ?>
<!DOCTYPE html>
<html>
<head>
	<title>SISTEMA - SCW</title>
	<?php include('../lib/header.php'); ?>
</head>
<body>
<div id="app">Aplicação na página</div>
<script type="text/javascript">

	Carregando = (opc) => { $("#Carregando").css("display",(opc?opc:'block')) }

	$(function(){

		Carregando('none');



	})
</script>
</body>
</html>