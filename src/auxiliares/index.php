<?php
  include("../../includes/includes.php");

  $r = array(
      #'tecnicos' => 'Técnicos',
      #'maquinas' => 'Máquinas',
      'tipos_manutencao' => 'Tipos de Manutenção',
      'setores' => 'Setores',
      #'funcionarios' => 'Funcionários',
  );

?>
<style>
  .margin{
    margin-bottom: 20px;
  }
</style>
<h3>Informações Auxiliares</h3>
<?php
  foreach ($r as $key => $value) {
?>
<div class="card margin">
  <div class="card-header">
    <?=$value?>
  </div>
  <div <?=$key?> class="card-body"></div>
</div>
<?php
  }
?>

<script type="text/javascript">
  $(function(){

    <?php
      foreach ($r as $key => $value) {
    ?>
      $("div[<?=$key?>]").load("src/auxiliares/form.php?tabela=<?=$key?>");
    <?php
      }
    ?>

  })
</script>