<?php
$Shortcut = "[InternetShortcut] 
URL=http://www.20cm.com.cn
IDList= 
[{000214A0-0000-0000-C000-000000000046}] 
Prop3=19,2 
"; 
Header("Content-type: application/octet-stream"); 
header("Content-Disposition: attachment; filename=20cmС˵��.url;"); 
echo $Shortcut; 
?>