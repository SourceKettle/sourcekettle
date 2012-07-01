<?php
header ('Expires: Mon, 1 Apr 1974 05:00:00 GMT');
header ('Last-Modified: ' . gmdate('D,d M YH:i:s') . ' GMT');
header ('Pragma: no-cache');
header('Content-type: text/plain');
echo $source_files;
die;
