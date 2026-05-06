<?php require_once('Connections/connLivestockControl.php'); ?>
<?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysqli_real_escape_string($connLivestockControl,$theValue) : mysqli_escape_string($connLivestockControl,$theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}

mysqli_select_db($connLivestockControl,$database_connLivestockControl);
$query_rsLogg = "SELECT * FROM mf_logg ORDER BY Logg_Tid DESC";
$rsLogg = mysqli_query($connLivestockControl,$query_rsLogg) or die(mysqli_error($connLivestockControl));
$row_rsLogg = mysqli_fetch_assoc($rsLogg);
$totalRows_rsLogg = mysqli_num_rows($rsLogg);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Fullständig logg</title>
</head>

<body>
<h1>Logg</h1>
<p><a href="index.php">&lt;- Back</a></p>
<p>Senaste uppdatering från foderautomat: <em><?php echo $row_rsLogg['Logg_Tid']; ?></em></p>
<table width="100%" border="1" cellspacing="0" cellpadding="2">
  <tr>
    <td><strong>id</strong></td>
    <td><strong>Logg_ID</strong></td>
    <td><strong>Logg_RFID_ID</strong></td>
    <td><strong>Logg_Tid</strong></td>
  </tr>
  <?php do { ?>
    <tr>
      <td><?php echo $row_rsLogg['id']; ?></td>
      <td><?php echo $row_rsLogg['Logg_ID']; ?></td>
      <td><?php echo $row_rsLogg['Logg_RFID_ID']; ?></td>
      <td><?php echo $row_rsLogg['Logg_Tid']; ?></td>
    </tr>
    <?php } while ($row_rsLogg = mysqli_fetch_assoc($rsLogg)); ?>
</table>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
</html>
<?php
mysqli_free_result($rsLogg);
?>
