<?php
	$home = true;
	include("../../includes/includes.php");
	if($_GET['s']) $_SESSION = array();
	if($_POST){
		$query = "select * from login where login = '".$_POST[login]."' and senha = '".($_POST[senha])."' and situacao = '1'";
		$result = mysql_query($query);
		if(mysql_num_rows($result)){
			$d = mysql_fetch_object($result);
			$_SESSION['scw_usuario_logado'] = $d->codigo;
			$_SESSION['scw_usuario_perfil'] = $d->perfil;
			$_SESSION['scw_usuario_tipo'] = $d->tipo;
			$_SESSION['scw_usuario_permissoes'] = explode(',',$d->permissoes);
			echo "ok";
		}else{
			echo "erro";
		}
		
		exit();	
	}
?>
<style type="text/css">
	#senhaAjuda{
		cursor: pointer;
	}
</style>

<div class="container" style="margin-top: 50px;">
	<div class="row justify-content-center">
		<div class="col-md-4">
		    <center style="margin-bottom:20px;">
		        <img style="width:300px" src="img/musashi_logo.png" />
		    </center>
			<div class="card">
				<div class="card-header">
					LOGIN DE ACESSO
				</div>
				<div class="card-body">
						<div class="form-group">
							<label for="Login">Login</label>
							<input type="text" class="form-control" id="Login" aria-describedby="loginAjuda">
						</div>
						<div class="form-group">
							<label for="Senha">Senha</label>
							<input type="password" class="form-control" id="Senha" aria-describedby="senhaAjuda">
							<small id="senhaAjuda" class="form-text text-muted">Esquece seu login e a senha? clique aqui!</small>
						</div>
					<a href="#" class="btn btn-primary" id="AcessarPainel">Acessar o painel</a>
				</div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	
	$(function(){
		var home = "src/login/";

		var AlertErro = (txt) => { $.alert({ content:txt, title:false }); }

		$("#AcessarPainel").click(function(){
			login = $("#Login").val();
			senha = $("#Senha").val();
			if(login && senha){
				$.ajax({
					url:home+"login.php",
					type:"POST",
					data:{
						login:login,
						senha:senha,
					},
					success:function(dados){
						if(dados == 'ok'){
							Carregando()
							$.ajax({
								url:"src/scripts/home.php",
								success:function(dados){
									$("#app").html(dados);
									Carregando('none')
								},
								error:function(dados){
									Carregando('none')
								}
							})
						}else{
							AlertErro('<center>Erro nos dados de acesso <br>ou <br>usuário sem permissão<br><br>Entre em contato com o administrador do sistema!</center>');
						}
						
					}
				});
			}else{
				AlertErro('Digite os dados de acesso!');
			}	

		});


		$("#senhaAjuda").click(function(){
			$.alert({
				content:"<center>Para recuperar o seu login e a senha ou criar um novo acesso, favor entre em contato com o administrador do sistema!</center>",
				title:false
			});
		});

	})

</script>