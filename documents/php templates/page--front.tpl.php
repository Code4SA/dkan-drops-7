<?php

/**
 * @file
 * Front page - no container class in main section.
 */
?>
<header id="header" class="header" role="header">
  <div class="branding container">
    <?php if ($logo): ?>
      <a class="logo navbar-btn pull-left" href="<?php print $front_page; ?>" title="<?php print t('Home'); ?>">
        <img src="<?php print $logo; ?>" alt="<?php print t('Home'); ?>" />
      </a>
    <?php endif; ?>
    <?php if ($site_name): ?>
      <div class="site-name">
        <?php print $site_name; ?>
      </div>
    <?php endif; ?>
    <?php if (!empty($site_slogan)): ?>
      <div class="site-slogan"><?php print $site_slogan; ?></div>
    <?php endif; ?>
    <!-- views exposed search -->
    <?php
      $block = block_load('dkan_sitewide', 'dkan_sitewide_search_bar');
      if($block):
        $search = _block_get_renderable_array(_block_render_blocks(array($block)));
        print render($search);
      endif;
    ?>
   <div class="translateRegion">
     Translate this site:
     <?php
        $block = block_load('gtranslate', 'gtranslate');
        if($block):
          $translate = _block_get_renderable_array(_block_render_blocks(array($block)));
          print render($translate);
        endif;
      ?>
    </div>
  </div>
  <div class="navigation-wrapper">
    <div class="container">
      <nav class="navbar navbar-default" role="navigation">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar-collapse">
            <span class="sr-only"><?php print t('Toggle navigation'); ?></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
        </div> <!-- /.navbar-header -->

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="navbar-collapse">
          <?php if ($main_menu): ?>
            <ul id="main-menu" class="menu nav navbar-nav">
              <?php print render($main_menu); ?>
            </ul>
          <?php endif; ?>
          
          <!-- user menu -->
          <?php
            $block = block_load('dkan_sitewide', 'dkan_sitewide_user_menu');
            if($block):
              $user_menu = _block_get_renderable_array(_block_render_blocks(array($block)));
              print render($user_menu);
            endif;
          ?>
        </div><!-- /.navbar-collapse -->
      </nav><!-- /.navbar -->
    </div><!-- /.container -->
  </div> <!-- /.navigation -->
</header>

<div id="main-wrapper">
  <div id="main" class="main">

    <?php //if (!empty($breadcrumb)): print $breadcrumb; endif;?>
    <?php print $messages; ?>
    <div class="main-row">

      <section> 
        <a id="main-content"></a>
        <?php print render($title_prefix); ?>
        <?php if (!empty($title) && (arg(0) == 'admin' || arg(1) == 'add' || arg(1) == 'edit')): ?>
          <h1 class="page-header"><?php print $title; ?></h1>
        <?php endif; ?>
        <?php print render($title_suffix); ?>
        <?php if (!empty($tabs)): ?>
          <?php print render($tabs); ?>
        <?php endif; ?>
        <?php if (!empty($action_links)): ?>
          <ul class="action-links"><?php print render($action_links); ?></ul>
        <?php endif; ?>
        <?php print render($page['content']); ?>
      </section>

    </div>

  </div> <!-- /#main -->
</div> <!-- /#main-wrapper -->

<footer id="footer" class="footer" role="footer">
  <div class="container">
  <div class="footerDisclaimer">
	  <!--This is a placeholder for some disclaimer text.-->
  </div>     
  <div class="footerTagline">
	NATIONAL OPEN DATA PORTAL <!--<span class="footerNotice">Copyright notices<br/>License notices</span>-->
  </div>
  <div class="footerSupport">
	WITH THE SUPPORT OF:
  </div>
<a href="http://www.microsoft.com" target="_blank"><img class="footerImage" src="http://opendataportal.cloudapp.net/profiles/dkan/themes/contrib/nuboot_radix/assets/images/microsoft_logo_small.png"></a>
  <a href="http://www.chillisoft.co.za" target="_blank"><img class="footerImage" src="http://opendataportal.cloudapp.net/profiles/dkan/themes/contrib/nuboot_radix/assets/images/chillisoft_logo_small.png"></a>
  <a href="http://code4sa.org/" target="_blank"><img class="footerImage" src="http://opendataportal.cloudapp.net/profiles/dkan/themes/contrib/nuboot_radix/assets/images/code4sa_logo_small.png"></a>
</footer>

