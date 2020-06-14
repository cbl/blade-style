
<?php
if(!$parentViewPath) {
    return;
}

if(!config('app.debug')) {
    return;
}

// Only compile when app debug is true.
app('blade.style.compiler')->compile($parentViewPath);
?>