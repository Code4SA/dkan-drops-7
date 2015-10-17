<?php

/**
 * @file
 * Default theme implementation to display a single Drupal page.
 *
 * @see template_preprocess()
 * @see template_preprocess_page()
 * @see template_process()
 * @see html.tpl.php
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
            $showMenu = false;
            $block = block_load('dkan_sitewide', 'dkan_sitewide_user_menu');
            if($block):
              $user_menu = _block_get_renderable_array(_block_render_blocks(array($block)));
              print render($user_menu);
              if(sizeof($user_menu) == 3){
                $showMenu = true;
              }
            endif;
          ?>
        </div><!-- /.navbar-collapse -->
      </nav><!-- /.navbar -->
    </div><!-- /.container -->
  </div> <!-- /.navigation -->
</header>

<div id="main-wrapper">
  <!-- TODO Cycle images -->
  <div class="panel-top panel-row borderImage" style="background-image:url(http://opendataportal.cloudapp.net/profiles/dkan/themes/contrib/nuboot_radix/assets/images/hero.jpg);">
  </div>
    <!-- Active theme menu item -->
    <?php
        $item1Class = "";
        $item2Class = "";
        $item3Class = "";
        $item4Class = "";
        $item5Class = "";
        $item6Class = "";
        $item7Class = "";
        $item8Class = "";
        $item9Class = "";
        $item10Class = "";
        if(strrpos($breadcrumb,"Economy and employment") > 0){
          $item1Class = "selectedTheme";
        }
        if(strrpos($breadcrumb,"Environmental sustainability") > 0){
          $item2Class = "selectedTheme";
        }
        if(strrpos($breadcrumb,"South Africa in the region") > 0){
          $item3Class = "selectedTheme";
        }
        if(strrpos($breadcrumb,"Human settlements") > 0){
          $item4Class = "selectedTheme";
        }
        if(strrpos($breadcrumb,"Education, training and innovation") > 0){
          $item5Class = "selectedTheme";
        }
        if(strrpos($breadcrumb,"Healthcare for all") > 0){
          $item6Class = "selectedTheme";
        }
        if(strrpos($breadcrumb,"Community and safety") > 0){
          $item7Class = "selectedTheme";
        }
        if(strrpos($breadcrumb,"Nation state building") > 0){
          $item8Class = "selectedTheme";
        }
        if(strrpos($breadcrumb,"Corruption fighting and transparency") > 0){
          $item9Class = "selectedTheme";
        }
        if(strrpos($breadcrumb,"Nation building and social cohesion") > 0){
          $item10Class = "selectedTheme";
        }
      
        $isCustomContent = false;
        $isAllDataset = false;
        if(strrpos($breadcrumb,"All Datasets")){
          $isCustomContent = true;
          $isAllDataset = true;
        }
      ?>
  
    <?php print $messages; ?>
    <div id="main" class="main container">
      <?php if($showMenu): ?>
        <div class="themeMenuSpacer">
            &nbsp;
        </div>
      <?php endif; ?>
            
      <?php if (!empty($breadcrumb)): print str_replace("<a href=\"/dataset\">Datasets</a>","<a href=\"/dataset\">Themes</a>",$breadcrumb); endif;?>
      <div class="main-row">
        <section>
          <a id="main-content"></a>
          <?php print render($title_prefix); ?>
          <?php if (!empty($title) && empty($is_panel) && !$isCustomContent): ?>
            <h1 class="page-header"><?php print $title; ?></h1>
          <?php endif; ?>
          <?php print render($title_suffix); ?>
          <?php if (!empty($tabs) && !$isCustomContent): ?>
            <?php print render($tabs); ?>
          <?php endif; ?>
          <?php if (!empty($action_links)): ?>
            <ul class="action-links"><?php print render($action_links); ?></ul>
          <?php endif; ?>
          <!-- Theme menu -->
          <?php if($showMenu): ?>
            <div class="themeMenu">
              <div class="themeMenuHeader">
                <div style="margin-left:20px;">
                  <i class="fa fa-folder themeIconStandard"></i> &nbsp; Themes
                </div>
              </div>
              
              <div class="datasetThemeSmall <?php print $item1Class; ?>">
                <a href="/dataset/economy-and-employment">Economy and employment</a>
              </div>
              <div class="datasetThemeSmall datasetThemeSmallOdd dataSetThemeSmallLineBreak <?php print $item2Class; ?>">
                <a href="/dataset/environmental-sustainability-and-resilience">Environmental sustainability<br/> and resilience</a>
              </div>
              <div class="datasetThemeSmall dataSetThemeSmallLineBreak <?php print $item3Class; ?>">
                <a href="/dataset/south-africa-region-and-world">South Africa in the region<br/> and the world</a>
              </div>
              <div class="datasetThemeSmall datasetThemeSmallOdd <?php print $item4Class; ?>">
                <a href="/dataset/human-settlements">Human settlements</a>
              </div>
              <div class="datasetThemeSmall dataSetThemeSmallLineBreak <?php print $item5Class; ?>">
                <a href="/dataset/education-training-and-innovation">Education, training and</br> innovation</a>
              </div>
              <div class="datasetThemeSmall datasetThemeSmallOdd <?php print $item6Class; ?>">
                <a href="/dataset/healthcare-all">Healthcare for all</a>
              </div>
              <div class="datasetThemeSmall <?php print $item7Class; ?>">
                <a href="/dataset/community-and-safety">Community and safety</a>
              </div>
              <div class="datasetThemeSmall datasetThemeSmallOdd <?php print $item8Class; ?>">
                <a href="/dataset/nation-state-building">Nation state building</a>
              </div>
              <div class="datasetThemeSmall dataSetThemeSmallLineBreak <?php print $item9Class; ?>">
                <a href="/dataset/corruption-fighting-and-transparency">Corruption fighting and<br/> transparency</a>
              </div>
              <div class="datasetThemeSmall datasetThemeSmallOdd dataSetThemeSmallLineBreak <?php print $item10Class; ?>">
                <a href="/dataset/nation-building-and-social-cohesion">Nation building and<br/> social cohesion</a>
              </div>
            </div>
          <?php endif; ?>
          <!-- Inject our own custom view data -->
          <?php
            $content = render($page['content']);
            if($isCustomContent){
              $content = "TODO";
               
              if($isAllDataset){
                $content = "Coming soon...";
              }
            }
          ?>
          <div class="mainContentWrapper">
              <?php print $content; ?>
          </div>
        </section>
  
      </div>
  
    </div> <!-- /#main -->
</div> <!-- /#main-wrapper -->


<footer id="footer" class="footer" role="footer">
  <div class="container">
  <div class="footerDisclaimer">
          This is a placeholder for some disclaimer text.
  </div>
  <div class="footerTagline">
        NATIONAL OPEN DATA PORTAL <span class="footerNotice">Copyright notices<br/>License notices</span>
  </div>
  <div class="footerSupport">
        WITH THE SUPPORT OF:
  </div>
  <img class="footerImage" src="http://opendataportal.cloudapp.net/profiles/dkan/themes/contrib/nuboot_radix/assets/images/microsoft_logo_small.png">
  <img class="footerImage" src="http://opendataportal.cloudapp.net/profiles/dkan/themes/contrib/nuboot_radix/assets/images/chillisoft_logo_small.png">
  <img class="footerImage" src="http://opendataportal.cloudapp.net/profiles/dkan/themes/contrib/nuboot_radix/assets/images/code4sa_logo_small.png">
</footer>

