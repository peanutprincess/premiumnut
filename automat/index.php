<?php 
require_once('Connections/connLivestockControl.php'); 

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


<!DOCTYPE html>
<html>
<head>
  <!-- Site made with Mobirise Website Builder v2.11, https://mobirise.com -->
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="generator" content="Mobirise v2.11, mobirise.com">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="shortcut icon" href="assets/images/gotfarm-logga-ej-text.gif" type="image/x-icon">
  <meta name="description" content="">
  <title>Gotfarm Feeder Control Panel - demo</title>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:700,400&amp;subset=cyrillic,latin,greek,vietnamese">
  <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="assets/mobirise/css/style.css">
  <link rel="stylesheet" href="assets/mobirise/css/mbr-additional.css" type="text/css">
  
  
  
</head>
<body>
<section class="mbr-navbar mbr-navbar--freeze mbr-navbar--absolute mbr-navbar--sticky mbr-navbar--auto-collapse" id="menu-0">
    <div class="mbr-navbar__section mbr-section">
        <div class="mbr-section__container container">
            <div class="mbr-navbar__container">
                <div class="mbr-navbar__column mbr-navbar__column--s mbr-navbar__brand">
                    <span class="mbr-navbar__brand-link mbr-brand mbr-brand--inline">
                        <span class="mbr-brand__logo"><img src="assets/images/gotfarm-logga.gif" class="mbr-navbar__brand-img mbr-brand__img" alt="GotFarm - RFID systems for sheep"></span>
                        
                    </span>
                </div>
                <div class="mbr-navbar__hamburger mbr-hamburger"><span class="mbr-hamburger__line"></span></div>
                <div class="mbr-navbar__column mbr-navbar__menu">
                    <nav class="mbr-navbar__menu-box mbr-navbar__menu-box--inline-right">
                        <div class="mbr-navbar__column">
                            <ul class="mbr-navbar__items mbr-navbar__items--right float-left mbr-buttons mbr-buttons--freeze mbr-buttons--right btn-decorator mbr-buttons--active mbr-buttons--only-links"><li class="mbr-navbar__item"><a class="mbr-buttons__link btn text-white" href="#">HEM</a></li>  <li class="mbr-navbar__item"><a class="mbr-buttons__link btn text-white" href="#">OM</a></li> <li class="mbr-navbar__item"><a class="mbr-buttons__link btn text-white" href="#">KONTAKT</a></li> <li class="mbr-navbar__item"><a class="mbr-buttons__link btn text-white" href="/admin/">LOGGA IN</a></li></ul>                            
                            
                        </div>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="engine"><a rel="external" href="https://www.gotfarm.se/">GotFarm</a></section><section class="mbr-section mbr-section--relative mbr-section--fixed-size mbr-after-navbar" id="msg-box3-11" style="background-color: rgb(151, 189, 30);">
    
    <div class="mbr-section__container container mbr-section__container--first" style="padding-top: 93px;">
        <div class="mbr-header mbr-header--wysiwyg row">
            <div class="col-sm-8 col-sm-offset-2">
                <h3 class="mbr-header__text">Kontrollpanel för Gotfarm Feeder</h3>
                
            </div>
        </div>
    </div>
    <div class="mbr-section__container container mbr-section__container--last" style="padding-bottom: 93px;">
        <div class="row">
            <div class="mbr-article mbr-article--wysiwyg col-sm-8 col-sm-offset-2"><p>I kontrollpanelen för Gotfarm Feeder kan du följa hur mycket dina djur äter samt redigera givor, foderspecifikationer samt besättningsinformation.</p></div>
        </div>
    </div>
    
</section>

<section class="mbr-section mbr-section--relative mbr-section--fixed-size" id="content4-4" style="background-color: rgb(255, 255, 255);">
    
    
    <div class="mbr-section__container container mbr-section__container--std-top-padding" style="padding-top: 93px;">
        <div class="mbr-section__row row">
            <div class="mbr-section__col col-xs-12 col-sm-6">
                
                <div class="mbr-section__container mbr-section__container--middle">
                    <div class="mbr-header mbr-header--reduce mbr-header--center mbr-header--wysiwyg">
                        <h3 class="mbr-header__text">Tio senaste givorna</h3>
                    </div>
                </div>
                <div class="mbr-section__container mbr-section__container--last" style="padding-bottom: 93px;">
                    <div class="mbr-article mbr-article--wysiwyg">
                        <table class="table table-striped">
                        	<thead>
                              <tr>
                                <td rowspan="2" align="center" valign="top"><strong>&nbsp;&nbsp;&nbsp;Djurnummer&nbsp;&nbsp;&nbsp;</strong></td>
                                <td rowspan="2" align="center" valign="top"><strong>&nbsp;&nbsp;&nbsp;Tid&nbsp;&nbsp;&nbsp;&nbsp;</strong></td>
                                <td colspan="4" align="center" valign="top"><strong>&nbsp;&nbsp;Giva (gram)&nbsp;&nbsp;</strong></td>
                              </tr>
                              <tr>
                                <td colspan="2" align="center" valign="top"><em><strong>&nbsp;&nbsp;Spannmål&nbsp;&nbsp; </strong></em></td>
                                <td colspan="2" align="center" valign="top"><em><strong>&nbsp;&nbsp;Pellets&nbsp;&nbsp;</strong></em></td>
                              </tr>
                          	</thead>
                            <tbody>
							  <?php do { ?>
                              <tr>
                                
                                  <td align="center">&nbsp;&nbsp;<?php echo substr($row_rsLogg['Logg_RFID_ID'],-5); ?>&nbsp;&nbsp;</td>
                                  <td align="center">&nbsp;&nbsp;<?php echo $row_rsLogg['Logg_Tid']; ?>&nbsp;&nbsp;</td>
                                  <td align="center"><?php echo $row_rsLogg['Logg_Giva_Foder1']; ?>&nbsp;</td>
                                  <td align="center"><?php echo $row_rsLogg['Logg_Giva_Foder1_Namn']; ?>&nbsp;</td>
                                  <td align="center"><?php echo $row_rsLogg['Logg_Giva_Foder2']; ?>&nbsp;</td>
                                  <td align="center"><?php echo $row_rsLogg['Logg_Giva_Foder2_Namn']; ?></td>
                                  
                              </tr>
                              <?php } while ($row_rsLogg = mysqli_fetch_assoc($rsLogg)); ?>
                          	</tbody>
                        </table>
                    </div>
                </div>
                
            </div>
            <div class="mbr-section__col col-xs-12 col-sm-6">
                
                <div class="mbr-section__container mbr-section__container--middle">
                    <div class="mbr-header mbr-header--reduce mbr-header--center mbr-header--wysiwyg">
                        <h3 class="mbr-header__text">Senaste dygnet</h3>
                    </div>
                </div>
                <div class="mbr-section__container mbr-section__container--last" style="padding-bottom: 93px;">
                    <div class="mbr-article mbr-article--wysiwyg">
                        <table class="table table-striped">
                        	<thead>
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
                          	</thead>
                            <tbody>
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
                          	</tbody>
                        </table>
                    </div>
                </div>
                
            </div>
            
            
            
            
            
            
            
        </div>
    </div>
</section>

<section class="mbr-section mbr-section--relative mbr-section--fixed-size" id="content4-5" style="background-color: rgb(255, 255, 255);">
    
    
    <div class="mbr-section__container container mbr-section__container--std-top-padding" style="padding-top: 93px;">
        <div class="mbr-section__row row">
            <div class="mbr-section__col col-xs-12 col-sm-6">
                
                <div class="mbr-section__container mbr-section__container--middle">
                    <div class="mbr-header mbr-header--reduce mbr-header--center mbr-header--wysiwyg">
                        <h3 class="mbr-header__text">Statistik över senaste veckan</h3>
                    </div>
                </div>
                <div class="mbr-section__container mbr-section__container--last" style="padding-bottom: 93px;">
                    <div class="mbr-article mbr-article--wysiwyg">
                        <table class="table table-striped">
                        	<thead>
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
                          	</thead>
                            <tbody>
							  <?php do { ?>
                              <tr>
                                  <td align="center"><a href="admin/djur_redigera.php?rfid=<?php echo $row_rsFlestBesokSenasteVeckan['Djurnummer']; ?>"><?php echo substr($row_rsFlestBesokSenasteVeckan['Djurnummer'],-5); ?></a></td>
                                  <td align="center"><?php echo $row_rsFlestBesokSenasteVeckan['Djur_Namn']; ?></td>
                                  <td align="center"><?php echo $row_rsFlestBesokSenasteVeckan['Antal']; ?></td>
                                  <td align="center"><?php echo round($row_rsFlestBesokSenasteVeckan['MVFoder1']); ?></td>
                                  <td align="center"><?php echo round($row_rsFlestBesokSenasteVeckan['MVFoder2']); ?></td>
                                     
                              </tr>
                              <?php } while ($row_rsFlestBesokSenasteVeckan = mysqli_fetch_assoc($rsFlestBesokSenasteVeckan)); ?>
                          </tbody>
                        </table>
                    </div>
                </div>
                
            </div>
            <div class="mbr-section__col col-xs-12 col-sm-6">
                
                <div class="mbr-section__container mbr-section__container--middle">
                    <div class="mbr-header mbr-header--reduce mbr-header--center mbr-header--wysiwyg">
                        <h3 class="mbr-header__text">Djur som inte ätit senaste veckan</h3>
                    </div>
                </div>
                <div class="mbr-section__container mbr-section__container--last" style="padding-bottom: 93px;">
                    <div class="mbr-article mbr-article--wysiwyg">
                        <table class="table table-striped">
                        	<thead>
                              <tr>
                                <td ><strong>Djurnummer&nbsp;&nbsp;&nbsp;</strong></td>
                                <td><strong>Djurets namn</strong></td>
                              </tr>
                          	</thead>
                            <tbody>
							  <?php do { ?>
                                <tr>
                                  <td><a href="admin/djur_redigera.php?rfid=<?php echo $row_rsAldrigSenasteVeckan['Djur_RFID_ID']; ?>"><?php echo substr($row_rsAldrigSenasteVeckan['Djur_RFID_ID'], -5); ?></a>&nbsp;&nbsp;&nbsp;</td>
                                  <td><?php echo $row_rsAldrigSenasteVeckan['Djur_Namn'] ?></td>
                                </tr>
                                <?php } while ($row_rsAldrigSenasteVeckan = mysqli_fetch_assoc($rsAldrigSenasteVeckan)); ?>
                          	</tbody>
                        </table>
                    </div>
                </div>
                
            </div>
  
        </div>
    </div>
</section>

<section class="mbr-section mbr-section--relative mbr-section--fixed-size" id="contacts1-8" style="background-color: rgb(34, 34, 34);">
    
    <div class="mbr-section__container container">
        <div class="mbr-contacts mbr-contacts--wysiwyg row" style="padding-top: 45px; padding-bottom: 45px;">
            <div class="col-sm-4">
                <div><img src="assets/images/gotfarm-logga-ej-text.gif" class="mbr-contacts__img mbr-contacts__img--left"></div>
            </div>
            <div class="col-sm-8">
                <div class="row">
                    <div class="col-sm-4">
                        <p class="mbr-contacts__text"><strong>Adress</strong><br>Boge Klinte 176<br>624 36 Slite<br>Sweden</p>
                    </div>
                    <div class="col-sm-4">
                        <p class="mbr-contacts__text"><strong>Kontakt</strong><br>
E-post: info@gotfarm.se<br>
Tel: +46 (0)70-738 78 96<br></p>
                    </div>
                    <div class="col-sm-4"><p class="mbr-contacts__text"><strong>Länkar</strong></p><ul class="mbr-contacts__list"><li><br></li></ul></div>
                </div>
            </div>
        </div>
    </div>
</section>


  <script src="assets/web/assets/jquery/jquery.min.js"></script>
  <script src="assets/bootstrap/js/bootstrap.min.js"></script>
  <script src="assets/smooth-scroll/SmoothScroll.js"></script>
  <script src="assets/mobirise/js/script.js"></script>
  
  
</body>
</html>

<?php
mysqli_free_result($rsLogg);

mysqli_free_result($rsFlestBesokSenasteVeckan);

mysqli_free_result($rsSenasteDygnet);

mysqli_free_result($rsAldrigSenasteVeckan);

mysqli_free_result($rsFoderSpec);
?>