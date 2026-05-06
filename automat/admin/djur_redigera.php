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

$MM_restrictGoTo = "/admin/";
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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE mf_djur SET Djur_Namn=%s, Djur_Intervall=%s, Djur_Intervall_Foder1=%s, Djur_Intervall_Foder2=%s, Djur_Foder1=%s, Djur_Foder2=%s, Djur_Inaktiv=%s WHERE Djur_RFID_ID=%s",
                       GetSQLValueString($_POST['Djur_Namn'], "text"),
                       GetSQLValueString($_POST['Djur_Intervall'], "int"),
                       GetSQLValueString($_POST['Djur_Intervall_Foder1'], "int"),
                       GetSQLValueString($_POST['Djur_Intervall_Foder2'], "int"),
                       GetSQLValueString($_POST['Djur_Foder1'], "int"),
                       GetSQLValueString($_POST['Djur_Foder2'], "int"),
                       GetSQLValueString(isset($_POST['Djur_Inaktiv']) ? "true" : "", "defined","-1","0"),
                       GetSQLValueString($_POST['RFID_ID'], "float"));

  mysqli_select_db($connLivestockControl,$database_connLivestockControl);
  $Result1 = mysqli_query($connLivestockControl,$updateSQL) or die(mysqli_error($connLivestockControl));

  $updateGoTo = "lista_djur.php";
  if (isset($_SERVER['QUERY_STRING'])) {
    $updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
    $updateGoTo .= $_SERVER['QUERY_STRING'];
  }
  header(sprintf("Location: %s", $updateGoTo));
}

$colname_rsDjur = "-1";
if (isset($_GET['rfid'])) {
  $colname_rsDjur = $_GET['rfid'];
}
mysqli_select_db($connLivestockControl,$database_connLivestockControl);
$query_rsDjur = sprintf("SELECT Djur_RFID_ID, Djur_Namn, Djur_Intervall, Djur_Intervall_Foder1, Djur_Intervall_Foder2, Djur_Foder1, Djur_Foder2, Djur_Inaktiv FROM mf_djur WHERE Djur_RFID_ID = %s", GetSQLValueString($colname_rsDjur, "float"));
$rsDjur = mysqli_query($connLivestockControl,$query_rsDjur) or die(mysqli_error($connLivestockControl));
$row_rsDjur = mysqli_fetch_assoc($rsDjur);
$totalRows_rsDjur = mysqli_num_rows($rsDjur);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Redigera data</title>
</head>

<body>
<h1>Redigera data för <?php echo substr($row_rsDjur['Djur_RFID_ID'], -5); ?></h1>
<p><a href="lista_djur.php">&lt;- Tillbaka</a></p>
<form action="<?php echo $editFormAction; ?>" method="POST" name="form1" id="form1">
  <table>
    <tr valign="baseline">
      <td nowrap="nowrap">Namn:</td>
      <td><input type="text" name="Djur_Namn" value="<?php echo htmlentities($row_rsDjur['Djur_Namn'], ENT_COMPAT, 'utf-8'); ?>" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap">Tid mellan huvudmål:</td>
      <td><input type="text" name="Djur_Intervall" value="<?php echo htmlentities($row_rsDjur['Djur_Intervall'], ENT_COMPAT, 'utf-8'); ?>" size="32" /> 
      minuter</td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap">Mellanmål - spannmål:</td>
      <td><input type="text" name="Djur_Intervall_Foder1" value="<?php echo htmlentities($row_rsDjur['Djur_Intervall_Foder1'], ENT_COMPAT, 'utf-8'); ?>" size="32" /> 
      sekunder</td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap">Mellanmål - pellets:</td>
      <td><input type="text" name="Djur_Intervall_Foder2" value="<?php echo htmlentities($row_rsDjur['Djur_Intervall_Foder2'], ENT_COMPAT, 'utf-8'); ?>" size="32" /> 
      sekunder</td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap">Huvudmål - spannmål:</td>
      <td><input type="text" name="Djur_Foder1" value="<?php echo htmlentities($row_rsDjur['Djur_Foder1'], ENT_COMPAT, 'utf-8'); ?>" size="32" /> 
      sekunder</td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap">Huvudmål - pellets:</td>
      <td><input type="text" name="Djur_Foder2" value="<?php echo htmlentities($row_rsDjur['Djur_Foder2'], ENT_COMPAT, 'utf-8'); ?>" size="32" /> 
      sekunder</td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap">Inaktivera:</td>
      <td><input type="checkbox" name="Djur_Inaktiv" value=""  <?php if (!(strcmp(htmlentities($row_rsDjur['Djur_Inaktiv'], ENT_COMPAT, 'utf-8'),-1))) {echo "checked=\"checked\"";} ?> /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap">&nbsp;</td>
      <td><input type="submit" value="Uppdatera" /></td>
    </tr>
  </table>
  <input type="hidden" name="RFID_ID" value="<?php echo $row_rsDjur['Djur_RFID_ID']; ?>" />
  <input type="hidden" name="MM_update" value="form1" />
</form>
<p>&nbsp;</p>
</body>
</html>
<?php
mysqli_free_result($rsDjur);
?>
