<?php
$this->response->header ('Expires', 'Mon, 1 Apr 1974 05:00:00 GMT');
$this->response->header ('Last-Modified', gmdate('D,d M YH:i:s') . ' GMT');
$this->response->header ('Pragma', 'no-cache');
$this->response->header('Content-type', $mimeType);
echo $sourceFile;
//die;
