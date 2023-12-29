<?php
  include("../../includes/includes.php");


  if($_POST['utm']){
    $q = "select * from setores where utm = '{$_POST['utm']}' order by nome";
    $r = mysql_query($q);
    while($s = mysql_fetch_object($r)){
        echo "<option value='{$s->codigo}'>{$s->nome}</option>";
    }
    exit();
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
        <option value="">:: Selecione ::</option>
        <?php
        $q = "select * from utm order by nome";
        $r = mysql_query($q);
        while($s = mysql_fetch_object($r)){
        ?>
        <option value="<?=$s->codigo?>"><?=$s->nome?></option>
        <?php
        }
        ?>
    </select>

    <div class="input-group-prepend">
        <span class="input-group-text">Setor</span>
    </div>
    <select class="form-control" id="setor">
        <option value="">:: Selecione ::</option>
    </select>

    <div class="input-group-prepend">
        <span class="input-group-text">Data Ini</span>
    </div>
    <input type="text" id="relatorio_filtro_data1" value="<?=$_SESSION['scw_chamado_filtro_data1']?>" class="form-control" placeholder="Data Inicial" aria-label="Data Inicial">

    <div class="input-group-prepend">
        <span class="input-group-text">Data Fim</span>
    </div>
    <input type="text" id="relatorio_filtro_data2" value="<?=$_SESSION['scw_chamado_filtro_data2']?>" class="form-control" placeholder="Data Final" aria-label="Data Final">


    <div class="input-group-append">
    <button buscar class="btn btn-success"><i class="fa fa-search"></i> Buscar</button>
    <button limpar_relatorio class="btn btn-danger"><i class="fa fa-eraser"></i> Limpar</button>
    </div>
</div>


<script>
    $(funciton(){
        $("#relatorio_filtro_data1, #relatorio_filtro_data2").mask("99/99/9999");

        $("#utm").click(function(){
            utm = $(this).val();
            $.ajax({
                url:"src/relatorios/index.php",
                type:"POST",
                data:{
                    utm,
                },
                success:function(dados){
                    $("#setor").html(dados);
                }
            });
        })

    })
</script>