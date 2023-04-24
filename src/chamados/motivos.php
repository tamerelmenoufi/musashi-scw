<?php
  include("../../includes/includes.php");
?>
<option value="">:: Motivo ::</option>
<?php
$q = "select * from motivos where competencia = '{$_POST['time']}' order by codigo";
$r = mysql_query($q);
while($s = mysql_fetch_object($r)){
?>
<option value="<?=$s->codigo?>"><?=utf8_encode($s->nome)?></option>
<?php
}
?>