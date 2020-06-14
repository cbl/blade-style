
<?php
if(!$styleId) {
    return;
}

app(BladeStyle\StyleCompiler::class)->compile($slot, $styleId, $lang);
?>