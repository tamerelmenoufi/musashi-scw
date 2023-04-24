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
	</style>
</head>
<body>


<div class="topoTV d-flex justify-content-between">
	<div>posicao 1</div>
	<div>posicao 2</div>
	<div>posicao 3</div>
</div>
<div class="corpoTV">

		<div class="slider-for">
			<h1>1</h1>
			<h1>2</h1>
			<h1>3</h1>
			<h1>4</h1>
			<h1>5</h1>
		</div>

</div>
<div class="rodapeTV">

		<div class="slider-nav">
			<h1>1</h1>
			<h1>2</h1>
			<h1>3</h1>
			<h1>4</h1>
			<h1>5</h1>
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
			slidesToShow: 3,
			slidesToScroll: 1,
			asNavFor: '.slider-for',
			dots: true,
			centerMode: true,
			focusOnSelect: true
		});



	})
</script>
</body>
</html>