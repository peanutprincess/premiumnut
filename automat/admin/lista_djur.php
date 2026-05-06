<?php require_once('../Connections/connLivestockControl.php'); ?>
<?php
if (!isset($_SESSION)) {
  session_start();
}
$MM_authorizedUsers = "";
$MM_donotCheckaccess = "true";

// *** Restrict Access To Page: Grant or deny access to this page
function isAuthorized($strUsers, $strGroups, $UserName, $UserGroup) { 
  // For security, start by assuming the visitor is NOT authorized. 
  $isValid = False; 

  // When a visitor has logged into this site, the Session variable MM_Username set equal to their username. 
  // Therefore, we know that a user is NOT logged in if that Session variable is blank. 
  if (!empty($UserName)) { 
    // Besides being logged in, you may restrict access to only certain users based on an ID established when they login. 
    // Parse the strings into arrays. 
    $arrUsers = Explode(",", $strUsers); 
    $arrGroups = Explode(",", $strGroups); 
    if (in_array($UserName, $arrUsers)) { 
      $isValid = true; 
    } 
    // Or, you may restrict access to only certain users based on their username. 
    if (in_array($UserGroup, $arrGroups)) { 
      $isValid = true; 
    } 
    if (($strUsers == "") && true) { 
      $isValid = true; 
    } 
  } 
  return $isValid; 
}

$MM_restrictGoTo = "/admin/index.php";
if (!((isset($_SESSION['MM_Username'])) && (isAuthorized("",$MM_authorizedUsers, $_SESSION['MM_Username'], $_SESSION['MM_UserGroup'])))) {   
  $MM_qsChar = "?";
  $MM_referrer = $_SERVER['PHP_SELF'];
  if (strpos($MM_restrictGoTo, "?")) $MM_qsChar = "&";
  if (isset($_SERVER['QUERY_STRING']) && strlen($_SERVER['QUERY_STRING']) > 0) 
  $MM_referrer .= "?" . $_SERVER['QUERY_STRING'];
  $MM_restrictGoTo = $MM_restrictGoTo. $MM_qsChar . "accesscheck=" . urlencode($MM_referrer);
  header("Location: ". $MM_restrictGoTo); 
  exit;
}
?>
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
$query_rsDjurAktiva = "SELECT * FROM mf_djur WHERE Djur_Inaktiv <> -1 ORDER BY Djur_RFID_ID ASC";
$rsDjurAktiva = mysqli_query($connLivestockControl,$query_rsDjurAktiva) or die(mysqli_error($connLivestockControl));
$row_rsDjurAktiva = mysqli_fetch_assoc($rsDjurAktiva);
$totalRows_rsDjurAktiva = mysqli_num_rows($rsDjurAktiva);

mysqli_select_db($connLivestockControl,$database_connLivestockControl);
$query_rsDjurInaktiva = "SELECT * FROM mf_djur WHERE Djur_Inaktiv = -1";
$rsDjurInaktiva = mysqli_query($connLivestockControl,$query_rsDjurInaktiva) or die(mysqli_error($connLivestockControl));
$row_rsDjurInaktiva = mysqli_fetch_assoc($rsDjurInaktiva);
$totalRows_rsDjurInaktiva = mysqli_num_rows($rsDjurInaktiva);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Lista djur</title>
</head>

<body>
<h1>Djur</h1>
<p><a href="admin.php">&lt; Tillbaka</a></p>
<h2>Aktiva djur</h2>
<table width="100%" border="0">
  <tr>
    <td>&nbsp;</td>
    <td><strong>Tid mellan huvudmåltider</strong></td>
    <td colspan="2" align="center"><strong>Giva mellanmål</strong></td>
    <td colspan="2" align="center"><strong>Giva huvudmål</strong></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td><em><strong>Giva spannmål</strong></em></td>
    <td><em><strong>Giva pellets</strong></em></td>
    <td><em><strong>Giva spannmål</strong></em></td>
    <td><em><strong>Giva pellets</strong></em></td>
  </tr>
  <?php do { ?>
    <tr>
      <td>- <a href="djur_redigera.php?rfid=<?php echo $row_rsDjurAktiva['Djur_RFID_ID']; ?>"><?php echo substr($row_rsDjurAktiva['Djur_RFID_ID'],-5); ?></a></td>
      <td><?php echo $row_rsDjurAktiva['Djur_Intervall']; ?></td>
      <td><?php echo $row_rsDjurAktiva['Djur_Intervall_Foder1']; ?></td>
      <td><?php echo $row_rsDjurAktiva['Djur_Intervall_Foder2']; ?></td>
      <td><?php echo $row_rsDjurAktiva['Djur_Foder1']; ?></td>
      <td><?php echo $row_rsDjurAktiva['Djur_Foder2']; ?></td>
    </tr>
    <?php } while ($row_rsDjurAktiva = mysqli_fetch_assoc($rsDjurAktiva)); ?>
</table>
<p>&nbsp;</p>
<h2>Inaktiva djur</h2>
<table width="100%" border="0">
  <tr>
    <td>&nbsp;</td>
    <td><strong>Tid mellan huvudmåltider</strong></td>
    <td colspan="2" align="center"><strong>Giva mellanmål</strong></td>
    <td colspan="2" align="center"><strong>Giva huvudmål</strong></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td><em><strong>Giva spannmål</strong></em></td>
    <td><em><strong>Giva pellets</strong></em></td>
    <td><em><strong>Giva spannmål</strong></em></td>
    <td><em><strong>Giva pellets</strong></em></td>
  </tr>
  <?php do { ?>
    <tr>
      <td>- <a href="djur_redigera.php?rfid=<?php echo $row_rsDjurInaktiva['Djur_RFID_ID']; ?>"><?php echo substr($row_rsDjurInaktiva['Djur_RFID_ID'],-5); ?></a></td>
      <td><?php echo $row_rsDjurInaktiva['Djur_Intervall']; ?></td>
      <td><?php echo $row_rsDjurInaktiva['Djur_Intervall_Foder1']; ?></td>
      <td><?php echo $row_rsDjurInaktiva['Djur_Intervall_Foder2']; ?></td>
      <td><?php echo $row_rsDjurInaktiva['Djur_Foder1']; ?></td>
      <td><?php echo $row_rsDjurInaktiva['Djur_Foder2']; ?></td>
    </tr>
    <?php } while ($row_rsDjurInaktiva = mysqli_fetch_assoc($rsDjurInaktiva)); ?>
</table>
<p>&nbsp;</p>
</body>
</html>
<?php
mysqli_free_result($rsDjurAktiva);

mysqli_free_result($rsDjurInaktiva);

mysqli_free_result($rsDjurAktiva);

mysqli_free_result($rsDjurInaktiva);
?>
