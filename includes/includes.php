<?php
	$UrlProjeto = "http://scw.mohatron.com/";
	session_start();
	include("connect.php");
	include("funcoes.php");
	if(!$home){
		if(!$_SESSION['scw_usuario_logado']){
			header("Location:".$UrlProjeto);
			exit();
		}
	}
	///include("funcoes.php");
	include("send.php");

    $qc = "select * from notificacoes where situacao = '1'";
    $rc = mysql_query($qc);
    $Notificacao = [];
    while($dc = mysql_fetch_object($rc)){
        $times = explode(",",$dc->time);
        foreach($times as $ind => $val){
            $Notificacao['email'][$val][] = $dc->email;
            $Notificacao['telefone'][$val][] = $dc->telefone;
            $Notificacao['nome'][$val][] = utf8_encode($dc->nome);
        }
    }
