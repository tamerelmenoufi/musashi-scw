<?php
  include("../../includes/includes.php");

  if($_POST['limpar']){
    $_SESSION['relatorio_utm'] = [];
    $_SESSION['relatorio_setor'] = [];
    $_SESSION['relatorio_filtro_data1'] = [];
    $_SESSION['relatorio_filtro_data2'] = [];
  }


  if($_POST['acao'] == 'filtra_setor'){
    $q = "select * from setores where utm = '{$_POST['utm']}' order by nome";
    $r = mysql_query($q);
    echo "<option value=''>:: Geral ::</option>";
    while($s = mysql_fetch_object($r)){
        echo "<option value='{$s->codigo}'>".utf8_encode($s->nome)."</option>";
    }
    exit();
  }


  if($_POST['acao'] == 'filtro'){
    
    $_SESSION['relatorio_utm'] = $_POST['utm'];
    $_SESSION['relatorio_setor'] = $_POST['setor'];
    $_SESSION['relatorio_filtro_data1'] = $_POST['relatorio_filtro_data1'];
    $_SESSION['relatorio_filtro_data2'] = $_POST['relatorio_filtro_data2'];
    
  }



?>

<h3>Relatório</h3>

<div class="input-group mb-3">

    <div class="input-group-prepend">
        <span class="input-group-text">Busca</span>
    </div>

    <div class="input-group-prepend">
        <span class="input-group-text">UTM</span>
    </div>
    <select class="form-control" id="utm">
        <option value="">:: Geral ::</option>
        <?php
        $q = "select * from utm order by nome";
        $r = mysql_query($q);
        while($s = mysql_fetch_object($r)){
        ?>
        <option value="<?=$s->codigo?>" <?=(($s->codigo == $_SESSION['relatorio_utm'])?'selected':false)?>><?=utf8_encode($s->nome)?></option>
        <?php
        }
        ?>
    </select>

    <div class="input-group-prepend">
        <span class="input-group-text">Setor</span>
    </div>
    <select class="form-control" id="setor">
        <option value="">:: Geral ::</option>
        <?php
        $q = "select * from setores where utm = '{$_SESSION['relatorio_utm']}' order by nome";
        $r = mysql_query($q);
        while($s = mysql_fetch_object($r)){
        ?>
        <option value="<?=$s->codigo?>" <?=(($s->codigo == $_SESSION['relatorio_setor'])?'selected':false)?>><?=utf8_encode($s->nome)?></option>
        <?php
        }
        ?>
    </select>

    <div class="input-group-prepend">
        <span class="input-group-text">Data Ini</span>
    </div>
    <input type="text" id="relatorio_filtro_data1" value="<?=$_SESSION['relatorio_filtro_data1']?>" class="form-control" placeholder="Data Inicial" aria-label="Data Inicial">

    <div class="input-group-prepend">
        <span class="input-group-text">Data Fim</span>
    </div>
    <input type="text" id="relatorio_filtro_data2" value="<?=$_SESSION['relatorio_filtro_data2']?>" class="form-control" placeholder="Data Final" aria-label="Data Final">


    <div class="input-group-append">
    <button buscar class="btn btn-success"><i class="fa fa-search"></i> Buscar</button>
    <button limpar_relatorio class="btn btn-danger"><i class="fa fa-eraser"></i> Limpar</button>
    </div>
</div>


<?php
    $query = "select a.* from chamados a left join setores b on a.setor = b.codigo left join utm c on b.utm = c.codigo order by a.data_abertura desc limit 0,100";
    $result = mysql_query($query);
    $relatorio = [];
    while($d = mysql_fetch_object($result)){
        $relatorio['utm'][$d->utm] = ($relatorio['utm'][$d->utm] + 1);
        $relatorio['setor'][$d->setor] = ($relatorio['utm'][$d->utm] + 1);
    }


    foreach($relatorio as $i => $v){
        echo "<p>{$i} - {$v}</p>";
    }
?>


<script>
    $(function(){

        $("#relatorio_filtro_data1, #relatorio_filtro_data2").mask("99/99/9999");

        $("#utm").change(function(){
            utm = $(this).val();
            $.ajax({
                url:"src/relatorios/index.php",
                type:"POST",
                data:{
                    utm,
                    acao:'filtra_setor',
                },
                success:function(dados){
                    $("#setor").html(dados);
                }
            });
        })

        $("button[buscar]").click(function(){
            utm = $("#utm").val();
            setor = $("#setor").val();
            relatorio_filtro_data1 = $("#relatorio_filtro_data1").val();
            relatorio_filtro_data2 = $("#relatorio_filtro_data2").val();
            Carregando();
            $.ajax({
                url:"src/relatorios/index.php",
                type:"POST",
                data:{
                    utm,
                    setor,
                    relatorio_filtro_data1,
                    relatorio_filtro_data2,           
                    acao:'filtro'         
                },
                success:function(dados){
                    $("main").html(dados);
                    Carregando('none');
                }
            });
        })

        $("button[limpar_relatorio]").click(function(){
            Carregando();
            $.ajax({
                url:"src/relatorios/index.php",
                type:"POST",
                data:{
                    limpar:1,
                },
                success:function(dados){
                    $("main").html(dados);
                    Carregando('none');
                }
            });
        })


    })
</script>