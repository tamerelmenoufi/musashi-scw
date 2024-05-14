<?php
	$home = true;
	include('../includes/includes.php');
?>
<!DOCTYPE html>
<html>
<head>
	<title>SISTEMA - SCW/TV</title>
	<?php include('../lib/header.php'); ?>
	<style>
		html, body {
			margin: 0;
			padding: 0;
			width: 100%;
			height: 100%;
			overflow: hidden; /* Para evitar barras de rolagem */
		}

		iframe {
			width: 100%;
			height: 100%;
			border: none; /* Remove a borda padrão do iframe */
		}
	</style>
</head>
<body opc="scw">

<iframe opc="scw" src="../app/" frameborder="0" allowfullscreen></iframe>
<iframe opc="tv" class="oculto" src="http://tvcorp.mohatron.com/v2___/" frameborder="0" allowfullscreen></iframe>

<script type="text/javascript">

	Carregando = (opc) => { $("#Carregando").css("display",(opc?opc:'block')) }

	$(function(){
		Carregando('none')

		setInterval(() => {
			opc = $("body").attr("opc");
			if(opc == 'scw'){
				$(`iframe[opc="scw"]`).css("z-index",10);
				$(`iframe[opc="tv"]`).css("z-index",1);
				$("body").attr("opc","tv");
			}else{
				$(`iframe[opc="tv"]`).css("z-index",10);
				$(`iframe[opc="scw"]`).css("z-index",1);
				$("body").attr("opc","scw");		
			}
		}, 5000);

	})
</script>
</body>
</html>