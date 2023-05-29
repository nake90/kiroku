<?php
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Expires: 0");
$hoyUrl = date('Ymd', time());
header("Refresh: 0; url=index.php?dia=$hoyUrl");
?>

