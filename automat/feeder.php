<?php 
require_once('Connections/connLivestockControl.php'); 

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

$maxRows_rsLogg = 10;
$pageNum_rsLogg = 0;
if (isset($_GET['pageNum_rsLogg'])) {
  $pageNum_rsLogg = $_GET['pageNum_rsLogg'];
}
$startRow_rsLogg = $pageNum_rsLogg * $maxRows_rsLogg;

mysqli_select_db($connLivestockControl,$database_connLivestockControl);
$query_rsLogg = "SELECT * FROM mf_logg ORDER BY Logg_Tid DESC";
$query_limit_rsLogg = sprintf("%s LIMIT %d, %d", $query_rsLogg, $startRow_rsLogg, $maxRows_rsLogg);
$rsLogg = mysqli_query($connLivestockControl,$query_limit_rsLogg) or die(mysqli_error($connLivestockControl));
$row_rsLogg = mysqli_fetch_assoc($rsLogg);

if (isset($_GET['totalRows_rsLogg'])) {
  $totalRows_rsLogg = $_GET['totalRows_rsLogg'];
} else {
  $all_rsLogg = mysqli_query($connLivestockControl,$query_rsLogg);
  $totalRows_rsLogg = mysqli_num_rows($all_rsLogg);
}
$totalPages_rsLogg = ceil($totalRows_rsLogg/$maxRows_rsLogg)-1;

mysqli_select_db($connLivestockControl,$database_connLivestockControl);
$query_rsFlestBesokSenasteVeckan = "SELECT COUNT(0) AS Antal, mf_logg.Logg_RFID_ID AS Djurnummer, mf_djur.Djur_Namn, (SUM(mf_logg.Logg_Giva_Foder1)/7) AS MVFoder1, (SUM(mf_logg.Logg_Giva_Foder2)/7) AS MVFoder2 FROM mf_foderspec, mf_logg INNER JOIN mf_djur ON mf_logg.Logg_RFID_ID = mf_djur.Djur_RFID_ID WHERE mf_logg.Logg_Tid >= NOW() - INTERVAL 1 WEEK AND mf_djur.Djur_Inaktiv != '-1' GROUP BY mf_logg.Logg_RFID_ID, mf_djur.Djur_Namn, mf_djur.Djur_Foder1, mf_djur.Djur_Foder2 ORDER BY Antal DESC";
$rsFlestBesokSenasteVeckan = mysqli_query($connLivestockControl,$query_rsFlestBesokSenasteVeckan) or die(mysqli_error($connLivestockControl));
$row_rsFlestBesokSenasteVeckan = mysqli_fetch_assoc($rsFlestBesokSenasteVeckan);
$totalRows_rsFlestBesokSenasteVeckan = mysqli_num_rows($rsFlestBesokSenasteVeckan);

mysqli_select_db($connLivestockControl,$database_connLivestockControl);
$query_rsSenasteDygnet = "SELECT COUNT(0) AS Antal, mf_logg.Logg_RFID_ID AS Djurnummer, mf_djur.Djur_Namn, mf_djur.Djur_Maxgiva1_Dygn, SUM(mf_logg.Logg_Giva_Foder1) AS Foder1Tot, SUM(mf_logg.Logg_Giva_Foder2) AS Foder2Tot FROM mf_foderspec, mf_logg INNER JOIN mf_djur ON mf_logg.Logg_RFID_ID = mf_djur.Djur_RFID_ID WHERE mf_logg.Logg_Tid >= NOW() - INTERVAL 1 DAY AND mf_djur.Djur_Inaktiv != '-1' GROUP BY mf_logg.Logg_RFID_ID, mf_djur.Djur_Namn, mf_djur.Djur_Foder1, mf_djur.Djur_Foder2 ORDER BY Djur_Maxgiva1_Dygn, Djurnummer DESC";
$rsSenasteDygnet = mysqli_query($connLivestockControl,$query_rsSenasteDygnet) or die(mysqli_error($connLivestockControl));
$row_rsSenasteDygnet = mysqli_fetch_assoc($rsSenasteDygnet);
$totalRows_rsSenasteDygnet = mysqli_num_rows($rsSenasteDygnet);

mysqli_select_db($connLivestockControl,$database_connLivestockControl);
$query_rsAldrigSenasteVeckan = "SELECT COUNT(0) AS `Antal_besök`, mf_djur.Djur_RFID_ID, mf_djur.Djur_Namn FROM mf_djur WHERE mf_djur.Djur_RFID_ID NOT IN (SELECT mf_logg.Logg_RFID_ID FROM mf_logg WHERE mf_logg.Logg_Tid >= now() - INTERVAL 1 WEEK GROUP BY mf_logg.Logg_RFID_ID) AND mf_djur.Djur_Inaktiv != '-1' GROUP BY mf_djur.Djur_RFID_ID";
$rsAldrigSenasteVeckan = mysqli_query($connLivestockControl,$query_rsAldrigSenasteVeckan) or die(mysqli_error($connLivestockControl));
$row_rsAldrigSenasteVeckan = mysqli_fetch_assoc($rsAldrigSenasteVeckan);
$totalRows_rsAldrigSenasteVeckan = mysqli_num_rows($rsAldrigSenasteVeckan);

mysqli_select_db($connLivestockControl,$database_connLivestockControl);
$query_rsFoderSpec = "SELECT * FROM mf_foderspec WHERE FoderID = 1";
$rsFoderSpec = mysqli_query($connLivestockControl,$query_rsFoderSpec) or die(mysqli_error($connLivestockControl));
$row_rsFoderSpec = mysqli_fetch_assoc($rsFoderSpec);
$totalRows_rsFoderSpec = mysqli_num_rows($rsFoderSpec);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Foderautomat - test</title>
</head>

<body>
<h1>Automat</h1>
<p>- <a href="logg.php">Fullständig logg</a><br />
  - <a href="http://78.70.139.138/admin/EditAnimal.php">Redigera djurdata och individuella givor</a><br />
- <a href="http://78.70.139.138/admin/SpecFoder.php">Specificera foder</a></p>
<h2>Tio senaste besöken</h2>
<table border="0" cellspacing="0" cellpadding="2">
  <tr>
    <td rowspan="2" align="center" valign="top"><strong>&nbsp;&nbsp;&nbsp;Djurnummer&nbsp;&nbsp;&nbsp;</strong></td>
    <td rowspan="2" align="center" valign="top"><strong>&nbsp;&nbsp;&nbsp;Tid&nbsp;&nbsp;&nbsp;&nbsp;</strong></td>
    <td rowspan="2" align="center" valign="top"><strong>&nbsp;&nbsp;&nbsp;Typ av måltid&nbsp;&nbsp;&nbsp;</strong></td>
    <td colspan="4" align="center" valign="top"><strong>&nbsp;&nbsp;Giva (gram)&nbsp;&nbsp;</strong></td>
  </tr>
  <tr>
    <td colspan="2" align="center" valign="top"><em><strong>&nbsp;&nbsp;Spannmål&nbsp;&nbsp; </strong></em></td>
    <td colspan="2" align="center" valign="top"><em><strong>&nbsp;&nbsp;Pellets&nbsp;&nbsp;</strong></em></td>
  </tr>
  <?php do { ?>
  <tr>
    
      <td align="center">&nbsp;&nbsp;<?php echo substr($row_rsLogg['Logg_RFID_ID'],-5); ?>&nbsp;&nbsp;</td>
      <td align="center">&nbsp;&nbsp;<?php echo $row_rsLogg['Logg_Tid']; ?>&nbsp;&nbsp;</td>
      <td align="center"><?php echo $row_rsLogg['Logg_Giva_Typ']; ?></td>
      <td align="center"><?php echo $row_rsLogg['Logg_Giva_Foder1']; ?>&nbsp;</td>
      <td align="center"><?php echo $row_rsLogg['Logg_Giva_Foder1_Namn']; ?>&nbsp;</td>
      <td align="center"><?php echo $row_rsLogg['Logg_Giva_Foder2']; ?>&nbsp;</td>
      <td align="center"><?php echo $row_rsLogg['Logg_Giva_Foder2_Namn']; ?></td>
      
  </tr>
  <?php } while ($row_rsLogg = mysqli_fetch_assoc($rsLogg)); ?>
</table>

<h2>Statistik över senaste dygnet</h2>
<table border="0" cellspacing="0" cellpadding="2">
  <tr>
    <td rowspan="2" align="center" valign="top"><strong>&nbsp;&nbsp;&nbsp;Djurnummer&nbsp;&nbsp;&nbsp;</strong></td>
    <td rowspan="2" align="center" valign="top"><strong>&nbsp;&nbsp;&nbsp;Djurets namn&nbsp;&nbsp;&nbsp;</strong></td>
    <td rowspan="2" align="center" valign="top"><strong>&nbsp;&nbsp;&nbsp;Antal besök&nbsp;&nbsp;&nbsp;</strong></td>
    <td rowspan="2" align="center" valign="top"><strong>Maxgiva 1</strong></td>
    <td colspan="2" align="center" valign="top"><strong>&nbsp;&nbsp;&nbsp;Total giva (gram)&nbsp;&nbsp;&nbsp;</strong></td>
  </tr>
  <tr>
    <td align="center"><strong><em>&nbsp;Spannmål&nbsp;</em></strong></td>
    <td align="center"><strong><em>&nbsp;Pellets&nbsp;</em></strong></td>
  </tr>
  <?php do { ?>
  <tr>
    <td align="center">&nbsp;&nbsp;<a href="admin/djur_redigera.php?rfid=<?php echo $row_rsSenasteDygnet['Djurnummer']; ?>"><?php echo substr($row_rsSenasteDygnet['Djurnummer'],-5); ?></a>&nbsp;&nbsp;</td>
    
    
      
        <td align="center">&nbsp;&nbsp;<?php echo $row_rsSenasteDygnet['Djur_Namn']; ?>&nbsp;&nbsp;</td>
        <td align="center">&nbsp;&nbsp;<?php echo $row_rsSenasteDygnet['Antal']; ?>&nbsp;&nbsp;</td>
        <td align="center">&nbsp;&nbsp;<?php echo $row_rsSenasteDygnet['Djur_Maxgiva1_Dygn']; ?>&nbsp;&nbsp;</td>
        <td align="center">&nbsp;&nbsp;<?php echo $row_rsSenasteDygnet['Foder1Tot']; ?>&nbsp;&nbsp;</td>
        <td align="center">&nbsp;&nbsp;<?php echo $row_rsSenasteDygnet['Foder2Tot']; ?>&nbsp;&nbsp;</td>
         
  </tr>
        <?php } while ($row_rsSenasteDygnet = mysqli_fetch_assoc($rsSenasteDygnet)); ?>
  
</table>

<h2>Statistik över senaste veckan</h2>
<table border="0" cellspacing="0" cellpadding="2">
  <tr>
    <td rowspan="2" align="center" valign="top"><strong>&nbsp;&nbsp;&nbsp;Djurnummer&nbsp;&nbsp;&nbsp;</strong></td>
    <td rowspan="2" align="center" valign="top"><strong>&nbsp;&nbsp;&nbsp;Djurets namn&nbsp;&nbsp;&nbsp;</strong></td>
    <td rowspan="2" align="center" valign="top"><strong>&nbsp;&nbsp;&nbsp;Antal besök&nbsp;&nbsp;&nbsp;</strong></td>
    <td colspan="2" align="center" valign="top"><strong>&nbsp;Giva<br />
      </strong>&nbsp;&nbsp;&nbsp;<em>(medelvärde per dygn i gram)</em>&nbsp;&nbsp;&nbsp;</td>
  </tr>
  <tr>
    <td align="center" valign="top"><em><strong>&nbsp;Spannmål&nbsp;</strong></em></td>
    <td align="center" valign="top"><em><strong>&nbsp;Pellets&nbsp;</strong></em></td>
  </tr>
  <?php do { ?>
  <tr>
    
    
      <td align="center"><a href="admin/djur_redigera.php?rfid=<?php echo $row_rsFlestBesokSenasteVeckan['Djurnummer']; ?>"><?php echo substr($row_rsFlestBesokSenasteVeckan['Djurnummer'],-5); ?></a></td>
      <td align="center"><?php echo $row_rsFlestBesokSenasteVeckan['Djur_Namn']; ?></td>
      <td align="center"><?php echo $row_rsFlestBesokSenasteVeckan['Antal']; ?></td>
      <td align="center"><?php echo round($row_rsFlestBesokSenasteVeckan['MVFoder1']); ?></td>
      <td align="center"><?php echo round($row_rsFlestBesokSenasteVeckan['MVFoder2']); ?></td>
         
  </tr>
  <?php } while ($row_rsFlestBesokSenasteVeckan = mysqli_fetch_assoc($rsFlestBesokSenasteVeckan)); ?>
</table>
<h2>Djur som inte besökt automaten senaste veckan</h2>
<table border="0" cellspacing="0" cellpadding="2">
  <tr>
    <td ><strong>Djurnummer&nbsp;&nbsp;&nbsp;</strong></td>
    <td><strong>Djurets namn</strong></td>
  </tr>
  
  <?php do { ?>
    <tr>
      <td><a href="admin/djur_redigera.php?rfid=<?php echo $row_rsAldrigSenasteVeckan['Djur_RFID_ID']; ?>"><?php echo substr($row_rsAldrigSenasteVeckan['Djur_RFID_ID'], -5); ?></a>&nbsp;&nbsp;&nbsp;</td>
      <td><?php echo $row_rsAldrigSenasteVeckan['Djur_Namn'] ?></td>
    </tr>
    <?php } while ($row_rsAldrigSenasteVeckan = mysqli_fetch_assoc($rsAldrigSenasteVeckan)); ?>
  
</table>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
</html>
<?php
mysqli_free_result($rsLogg);

mysqli_free_result($rsFlestBesokSenasteVeckan);

mysqli_free_result($rsSenasteDygnet);

mysqli_free_result($rsAldrigSenasteVeckan);

mysqli_free_result($rsFoderSpec);
?>