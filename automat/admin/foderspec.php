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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $updateSQL = sprintf("UPDATE mf_foderspec SET Foder1_Spannmal=%s, Foder1_Spannmal_5=%s, Foder1_Spannmal_10=%s, Foder1_Spannmal_Namn=%s, Foder2_Pellets=%s, Foder2_Pellets_5=%s, Foder2_Pellets_10=%s, Foder2_Pellets_Namn=%s WHERE id=%s",
                       GetSQLValueString($_POST['Foder1_Spannmal'], "int"),
                       GetSQLValueString($_POST['Foder1_Spannmal_5'], "int"),
                       GetSQLValueString($_POST['Foder1_Spannmal_10'], "int"),
                       GetSQLValueString($_POST['Foder1_Spannmal_Namn'], "text"),
                       GetSQLValueString($_POST['Foder2_Pellets'], "int"),
                       GetSQLValueString($_POST['Foder2_Pellets_5'], "int"),
                       GetSQLValueString($_POST['Foder2_Pellets_10'], "int"),
                       GetSQLValueString($_POST['Foder2_Pellets_Namn'], "text"),
                       GetSQLValueString($_POST['id'], "int"));

  mysqli_select_db($connLivestockControl,$database_connLivestockControl);
  $Result1 = mysqli_query($connLivestockControl,$updateSQL) or die(mysqli_error($connLivestockControl));
}

mysqli_select_db($connLivestockControl,$database_connLivestockControl);
$query_rsFoderspec = "SELECT * FROM mf_foderspec WHERE FoderID = 1";
$rsFoderspec = mysqli_query($connLivestockControl,$query_rsFoderspec) or die(mysqli_error($connLivestockControl));
$row_rsFoderspec = mysqli_fetch_assoc($rsFoderspec);
$totalRows_rsFoderspec = mysqli_num_rows($rsFoderspec);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Foderspec</title>
</head>

<body>
<h1>Foderspecifikation</h1>
<p><a href="admin.php">&lt; Tillbaka</a></p>
<form action="<?php echo $editFormAction; ?>" method="post" name="form1" id="form1">
  <table>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Spannmål - vikt 1:</td>
      <td><input type="text" name="Foder1_Spannmal" value="<?php echo htmlentities($row_rsFoderspec['Foder1_Spannmal'], ENT_COMPAT, 'utf-8'); ?>" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Spannmå - vikt 5:</td>
      <td><input type="text" name="Foder1_Spannmal_5" value="<?php echo htmlentities($row_rsFoderspec['Foder1_Spannmal_5'], ENT_COMPAT, 'utf-8'); ?>" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Spannmål - vikt 10:</td>
      <td><input type="text" name="Foder1_Spannmal_10" value="<?php echo htmlentities($row_rsFoderspec['Foder1_Spannmal_10'], ENT_COMPAT, 'utf-8'); ?>" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Spannmal - Namn:</td>
      <td><input type="text" name="Foder1_Spannmal_Namn" value="<?php echo htmlentities($row_rsFoderspec['Foder1_Spannmal_Namn'], ENT_COMPAT, 'utf-8'); ?>" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Pellets - vikt 1:</td>
      <td><input type="text" name="Foder2_Pellets" value="<?php echo htmlentities($row_rsFoderspec['Foder2_Pellets'], ENT_COMPAT, 'utf-8'); ?>" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Pellets - vikt 5:</td>
      <td><input type="text" name="Foder2_Pellets_5" value="<?php echo htmlentities($row_rsFoderspec['Foder2_Pellets_5'], ENT_COMPAT, 'utf-8'); ?>" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Pellets - vikt 10:</td>
      <td><input type="text" name="Foder2_Pellets_10" value="<?php echo htmlentities($row_rsFoderspec['Foder2_Pellets_10'], ENT_COMPAT, 'utf-8'); ?>" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">Pellets - Namn:</td>
      <td><input type="text" name="Foder2_Pellets_Namn" value="<?php echo htmlentities($row_rsFoderspec['Foder2_Pellets_Namn'], ENT_COMPAT, 'utf-8'); ?>" size="32" /></td>
    </tr>
    <tr valign="baseline">
      <td nowrap="nowrap" align="right">&nbsp;</td>
      <td><input type="submit" value="Spara" /></td>
    </tr>
  </table>
  <input type="hidden" name="MM_update" value="form1" />
  <input type="hidden" name="id" value="<?php echo $row_rsFoderspec['id']; ?>" />
</form>
<p>&nbsp;</p>
</body>
</html>
<?php
mysqli_free_result($rsFoderspec);
?>
