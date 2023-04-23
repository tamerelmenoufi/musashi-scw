<?php
  include("../../includes/includes.php");

    $status = array(
      	'n' => 'Novo',
      	'p' => 'Em Produção',
      	'c' => 'Concluído'
  	);


  if($_POST['acao'] == 'salvar'){

    for ($i = 0; $i < count($_POST['campo']); $i++) {
      $campos[] = $_POST['campo'][$i] . " = '".utf8_decode($_POST['valor'][$i])."'";
    }
    				
    if($_POST['codigo'] and $campos){
      $query = "update chamados set ".implode(", ",$campos)." where codigo = '".$_POST['codigo']."'";
      $msg = 'atualiza';
    }else if($campos){
      $campos[] = "funcionario = '".$_SESSION['scw_usuario_logado']."'";
      $campos[] = "data_abertura = NOW()";
      $query = "insert into chamados set ".implode(", ",$campos);
      $msg = 'novo';
    }
    mysql_query($query);
    
    if($msg){
        if($msg == 'novo') {$cod = mysql_insert_id();} else { $cod = $_POST['codigo']; } 

        $q = "SELECT 
                    a.codigo,
                    a.status,
                    s.nome as setor, 
                    m.nome as maquina, 
                    t.nome as tipo_manutencao, 
                    a.problema, 
                    f.nome as funcionario, 
                    tc.nome as tecnico 
            FROM chamados a 
                left join setores s on a.setor = s.codigo 
                left join tipos_manutencao t on a.tipo_manutencao = t.codigo 
                left join maquinas m on a.maquina = m.codigo 
                left join login tc on a.tecnico = tc.codigo 
                left join login f on a.funcionario = f.codigo 
            where a.codigo = '{$cod}'";
        $r = mysql_query($q);
        $d = mysql_fetch_object($r);
        
        $msg = "SCW-MUSASHI Informa: ".(($msg == 'novo')?"Um novo chamado":"Chamado com alteração ")." cadastrado ".
               "*ID*:".str_pad($d->codigo, 8, "0", STR_PAD_LEFT).
               ", *SETOR*: ".utf8_encode($d->setor).
               ", *MÁQUINA*: ".utf8_encode($d->maquina).
               ", *TIPO DE MANUTENÇÃO*: ".utf8_encode($d->tipo_manutencao).
               (($d->problema)?", *PROBLEMA*: ".str_replace("\n"," ",utf8_encode($d->problema)):false).
               (($d->funcionario)?", *FUNCIONÁRIO*: ".utf8_encode($d->funcionario):false).
               (($d->tecnico)?", *TÉCNICO*: ".utf8_encode($d->tecnico):false).
               (($d->status)?", *SITUAÇÃO*: ".$status[$d->status]:false).
               (($d->observacao)?", *OBSERVAÇÕES*: ".str_replace("\n"," ",$_POST['observacao']):false);

        //str_replace("[msg]", str_pad($cod, 8, "0", STR_PAD_LEFT) ,$msg);
        //file_put_contents('wapp.txt',$msg);
        foreach($WappPhones as $ind => $num){
            EnviarWappNovo($num, $msg);
        }
        
        
        ////////////////EMAIL//////////////////////////////////
        $postdata = http_build_query(
            array(
                'codigo' => $d->codigo,
            )
        );
        $opts = array('http' =>
            array(
                'method' => 'POST',
                'header' => 'Content-type: application/x-www-form-urlencoded',
                'content' => $postdata
            )
        );
        $context = stream_context_create($opts);
        $result = file_get_contents('http://moh1.com.br/musashi/scw/src/alertas/email.php', false, $context);
        ////////////////////////////////////////////////////////
        
        
    }
    exit();
  }


  if($_GET['codigo']){
    $query = "select * from chamados where codigo = '".$_GET['codigo']."'";
    $result = mysql_query($query);
    $d = mysql_fetch_object($result);
  }

?>

<div voltar class="text-left" style="cursor: pointer;"> <i class="fa fa-angle-left" style="margin-right: 10px;"></i> voltar </div>

<h2>Chamado <?=(($d->codigo)?'#'.str_pad($d->codigo, 8, "0", STR_PAD_LEFT):false)?></h2>
<form id="formCadastros">


  <div class="form-setor">
    <label for="setor">Setor</label>
    <select form id="setor" class="form-control">
      <option value="">:: Setores ::</option>
      <?php
        $q = "select * from setores order by nome";
        $r = mysql_query($q);
        while($s = mysql_fetch_object($r)){
      ?>
      <option value="<?=$s->codigo?>" <?=(($s->codigo == $d->setor)?'selected':false)?>><?=utf8_encode($s->nome)?></option>
      <?php    
        }
      ?>
    </select>
  </div>

  <div class="form-group">
    <label for="tipo_manutencao">Problemas</label>
    <select form id="tipo_manutencao" class="form-control">
      <option value="">:: Selecione o problema ::</option>
      <?php
        $q = "select * from tipos_manutencao order by nome";
        $r = mysql_query($q);
        while($s = mysql_fetch_object($r)){
      ?>
      <option value="<?=$s->codigo?>" <?=(($s->codigo == $d->tipo_manutencao)?'selected':false)?>><?=utf8_encode($s->nome)?></option>
      <?php    
        }
      ?>
    </select>
  </div>

  <div class="form-group">
    <label for="maquina">Máquina</label>
    <select form id="maquina" class="form-control">
      <option value="">:: Máquinas ::</option>
      <?php
        $q = "select * from maquinas order by nome";
        $r = mysql_query($q);
        while($s = mysql_fetch_object($r)){
      ?>
      <option value="<?=$s->codigo?>" <?=(($s->codigo == $d->maquina)?'selected':false)?>><?=utf8_encode($s->nome)?></option>
      <?php    
        }
      ?>
    </select>
  </div>

  <div class="form-group">
      <label>Descrição do problema</label>
    <textarea form id="problema" class="form-control"><?=utf8_encode($d->problema)?></textarea> 
  </div>

  <div class="form-group">
    <butron SalvarCadastro codigo="<?=$d->codigo?>" tipo="<?=$_SESSION['opc_tipo']?>" class="btn btn-primary">Salvar</butron>
  </div>


</form>

<script type="text/javascript">
  $(function(){


    $("div[voltar]").click(function(){
      Carregando();
      $.ajax({
        url:"src/chamados/index.php",
        success:function(dados){
          $("main").html(dados);
          Carregando('none');
        }
      });
    });

    $("butron[SalvarCadastro]").click(function(){
        obj = $("#formCadastros");
        codigo = $(this).attr('codigo');
        GetForm(obj);
        valida = false;
        for(i=0;i<valor.length;i++){
          if(!valor[i]){
            valida = true;
          }
        }

        if(!valida){
            Carregando();
            $.ajax({
              url:"src/chamados/form.php",
              type:"POST",
              data:{
                campo:campo,
                valor:valor,
                codigo:codigo,
                acao:'salvar',
              },
              success:function(dados){
                //$.alert({ content:dados });

                $.ajax({
                  url:"src/chamados/index.php",
                  success:function(dados){
                    Carregando('none');
                    $("main").html(dados);
                    JanelaAlerta();
                  }
                });
                
              }
            });

        }else{

            $.alert({
              content:"Favor preencha os campos para criar o chamado!",
              title:false,
            })

        }

    });

  })
</script>