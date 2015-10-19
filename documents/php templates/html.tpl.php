<?php
/**
 * @file
 * Nuboot's html template.
 */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML+RDFa 1.0//EN"
  "http://www.w3.org/MarkUp/DTD/xhtml-rdfa-1.dtd">
<html lang="<?php print $language->language; ?>" dir="<?php print $language->dir; ?>"<?php print $rdf_namespaces;?>>
<head profile="<?php print $grddl_profile; ?>">
  <meta http-equiv="X-UA-Compatible" content="IE=edge, chrome=1">
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <?php print $head; ?>
  <title>Data.gov.za</title>
  <?php print $styles; ?>
  <link href="/profiles/dkan/themes/contrib/nuboot_radix/assets/datatables/dataTables.bootstrap.css" rel="stylesheet" type="text/css" />
  <!-- HTML5 element support for IE6-8 -->
  <!--[if lt IE 9]>
    <script src="//html5shiv.googlecode.com/svn/trunk/html5.js"></script>
    <link href="/css/ie.css" media="screen" rel="stylesheet" type="text/css" />
  <![endif]-->
  <?php print $scripts; ?>
  <script src="/profiles/dkan/themes/contrib/nuboot_radix/assets/datatables/jQuery-2.1.3.min.js" type="text/javascript"></script>
  <script src="/profiles/dkan/themes/contrib/nuboot_radix/assets/datatables/jquery.dataTables.js" type="text/javascript"></script>
  <script src="/profiles/dkan/themes/contrib/nuboot_radix/assets/datatables/dataTables.bootstrap.js" type="text/javascript"></script>
</head>
<body class="<?php print $classes; ?>" <?php print $attributes;?>>
  <div id="skip-link">
    <a href="#main-content" class="element-invisible element-focusable"><?php print t('Skip to main content'); ?></a>
  </div>
  <?php print $page_top; ?>
  <?php print $page; ?>
  <?php print $page_bottom; ?>
</body>
</html>
