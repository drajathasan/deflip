<?php
/**
 * @author Heru Subekti
 * @modify by Drajat Hasan
 * @url https://www.facebook.com/heroe.soebekti
 * @create date 2022-03-29 07:28:44
 * @modify date 2022-03-30 18:46:21
 */

defined('INDEX_AUTH') or die ('Direct Access is not allow!');

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>PDF Reader</title>
  <meta name="viewport" content="width=device-width, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">
  <link href="<?= dflipUrl('viewer/css/dflip.min.css') ?>" rel="stylesheet" type="text/css">
  <link href="<?= dflipUrl('viewer/css/themify-icons.min.css') ?>" rel="stylesheet" type="text/css">
  <?php if (isset($meta['allowDownload']) && (bool)$meta['allowDownload'] === false): ?>
  <style>
    .ti-download {
      display: none !important;
    }
  </style>
  <?php endif; ?>
</head>
<body>
<div class="container">
  <div class="row">
    <div class="col-xs-12" style="padding-bottom:30px">
      <!--Normal FLipbook-->
      <div class="_df_book" height="500" webgl="true" backgroundcolor="white"
              source="<?= $file_loc_url; ?>"
              id="pdf_collections">
      </div>
    </div>
  </div>
</div>

<script src="<?= dflipUrl('viewer/js/libs/jquery.min.js') ?>" type="text/javascript"></script>
<script src="<?= dflipUrl('viewer/js/dflip.min.js') ?>" type="text/javascript"></script>
<script>
</script>
</body>
</html>
