<?php
$classStr = "";
if (isset($classes)) {
    foreach ($classes as $class) {
        $classStr .= " $class";
    }
}
?>
<div <?= isset($id) ? "id=\"{$id}\"" : '' ?> class="iconButton<?=$classStr?>">
    <img src="<?=$icon?>" width="20" height="20">
    <span><?=$text?></span>
</div>
