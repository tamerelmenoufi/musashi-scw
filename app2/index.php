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
        iframe{
			position:fixed;
            padding:0;
            margin:0;
            left:0;
            right:0;
			top:0;
			bottom:0;
            border:solid 1px red;
        }
	</style>
</head>
<body>

<iframe frameborder="0" src="../app/"></iframe>

<script type="text/javascript">

	Carregando = (opc) => { $("#Carregando").css("display",(opc?opc:'block')) }

	$(function(){
		Carregando('none')

	})
</script>
</body>
</html>