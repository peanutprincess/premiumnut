<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
$hostname_connLivestockControl = "my94b.sqlserver.se";
$database_connLivestockControl = "147045-menhirfeeder";
$username_connLivestockControl = "147045_oc38165";
$password_connLivestockControl = "Foderautomat1";
$connLivestockControl = mysqli_connect($hostname_connLivestockControl, $username_connLivestockControl, $password_connLivestockControl) or trigger_error(mysqli_error($connLivestockControl),E_USER_ERROR); 
?>