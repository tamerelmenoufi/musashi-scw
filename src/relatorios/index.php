<?php
  include("../../includes/includes.php");
?>


<div class="input-group mb-3">
    <div class="input-group-prepend">
        <span class="input-group-text">Busca</span>
    </div>
    <input type="text" id="busca_filtro" value="<?=$_SESSION['scw_chamado_filtro']?>" class="form-control" placeholder="Digite o texto para a sua busca" aria-label="Digite o texto de sua busca">
    <input type="hidden" id="codigo<?=$tabela?>" value="" />


    <div class="input-group-prepend">
        <span class="input-group-text">Data Ini</span>
    </div>
    <input type="text" id="busca_filtro_data1" value="<?=$_SESSION['scw_chamado_filtro_data1']?>" class="form-control" placeholder="Data Inicial" aria-label="Data Inicial">

    <div class="input-group-prepend">
        <span class="input-group-text">Data Fim</span>
    </div>
    <input type="text" id="busca_filtro_data2" value="<?=$_SESSION['scw_chamado_filtro_data2']?>" class="form-control" placeholder="Data Final" aria-label="Data Final">


    <div class="input-group-append">
    <button buscar class="btn btn-success"><i class="fa fa-search"></i> Buscar</button>
    <button limpar_busca class="btn btn-danger"><i class="fa fa-eraser"></i> Limpar</button>
    </div>
</div>