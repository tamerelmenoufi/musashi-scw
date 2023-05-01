<?php
  include("../../includes/includes.php");

  $r = array(
      #'tecnicos' => 'Técnicos',
      #'maquinas' => 'Máquinas',
      'pecas' => 'Descrição das Peças',
      #'setores' => 'Setores',
      #'funcionarios' => 'Funcionários',
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
<?php
  foreach ($r as $key => $value) {
?>
<div class="card margin">
  <div class="card-header">
    <h3><?=$value?></h3>
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
      $("div[<?=$key?>]").load("src/pecas/form.php?tabela=<?=$key?>");
    <?php
      }
    ?>

  })
</script>