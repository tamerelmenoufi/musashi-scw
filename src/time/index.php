<?php
  include("../../includes/includes.php");

  $r = array(
      #'tecnicos' => 'Técnicos',
      #'maquinas' => 'Máquinas',
      # 'tipos_manutencao' => 'Descrição dos Problemas de Qualidade',
      #'setores' => 'Setores',
      #'funcionarios' => 'Funcionários',
      'time' => 'Time de Atuação',
  );

?>
<style>
  td{
    white-space: nowrap;
  }
  .margin{
    margin-bottom: 20px;
  }
</style>
<h3>Time de Atuação</h3>
<?php
  foreach ($r as $key => $value) {
?>
<div class="card margin">
  <div class="card-header">
    <h3><?=$value?> --- A</h3>
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
      $("div[<?=$key?>]").load("src/time/form.php?tabela=<?=$key?>");
    <?php
      }
    ?>

  })
</script>