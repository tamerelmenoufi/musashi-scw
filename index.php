<?php $home = true; include('includes/includes.php'); ?>
<!DOCTYPE html>
<html>
<head>
	<title>SISTEMA - SCW</title>
	<?php include('lib/header.php'); ?>
</head>
<body>
<div id="app"></div>
<script type="text/javascript">
	
	Carregando = (opc) => { $("#Carregando").css("display",(opc?opc:'block')) }

	$(function(){
		<?php
			if($_SESSION['scw_usuario_logado']){
				$cam = 'src/scripts/home.php';
			}else{
				$cam = 'src/login/login.php';
			}
		?>
		$.ajax({
			url:"<?=$cam?>",
			success:function(dados){
				$("#app").html(dados);
				Carregando('none');
			}
		});

	})
</script>
</body>
</html>