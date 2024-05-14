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

		div[tvcorp] {
			width: 100%;
			height: 100%;
			border: none; /* Remove a borda padrão do iframe */
		}
	</style>
</head>
<body opc="scw">

<div tvcorp opc="scw"><iframe src="../app/" frameborder="0" allowfullscreen></iframe></div>
<div tvcorp opc="tv"><iframe src="http://tvcorp.mohatron.com/v2___/" frameborder="0" allowfullscreen></iframe></div>

<script type="text/javascript">

	Carregando = (opc) => { $("#Carregando").css("display",(opc?opc:'block')) }

	$(function(){
		Carregando('none')

		setInterval(() => {
			opc = $("body").attr("opc");
			if(opc == 'scw'){
				$(`div[opc="scw"]`).css("height","100%");
				$(`div[opc="tv"]`).css("height","0");
				$("body").attr("opc","tv");
			}else{
				$(`div[opc="tv"]`).css("height","100%");
				$(`div[opc="scw"]`).css("height","0");
				$("body").attr("opc","scw");		
			}
		}, 60000);

	})
</script>
</body>
</html>