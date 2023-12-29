<?php
  include("../../includes/includes.php");
?>

<h3>Relatório</h3>

<div class="input-group mb-3">

    <div class="input-group-prepend">
        <span class="input-group-text">Busca</span>
    </div>
    <select id="utm">
        <option value="">:: Selecione ::</option>
    </select>
    <select id="setor">
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