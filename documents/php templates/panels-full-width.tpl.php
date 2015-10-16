<?php
/**
 * @file
 * Template for a 4 row panel layout.
 */
$fid = theme_get_setting('hero_file');
$file = !empty($fid) ? file_load($fid) : FALSE;
if($file):
  $uri = $file->uri;
  $tint = 'tint';
  $bg_color = 'transparent';
  $path = file_create_url($uri);
else :
  $background_option = theme_get_setting('background_option');
  if(empty($background_option)):
    $uri = 'profiles/dkan/themes/contrib/nuboot_radix/assets/images/hero.jpg';
    $tint = 'tint';
    $bg_color = 'transparent';
    $path = file_create_url($uri);
  else :
    $uri = '';
    $tint = 'no-tint';
    $bg_color = '#' . ltrim($background_option, '#');
    $path = '';
  endif;
endif;
?>
<div class="panel-display panel-full-width clearfix" <?php if (!empty($css_id)) { print "id=\"$css_id\""; } ?>>

  <!-- Change image dynamically here -->
  <div class="panel-top panel-row" <?php print 'style="background-image:url(' . $path . ');background-color:' . $bg_color . '"'; ?>>
  </div>
  
    <div class="panel-middle panel-row">
      <div class="container">
        <div class="inside middleContent">
          Welcome to the South African Government National Open Data Portal. 
          <img src="/profiles/dkan/themes/contrib/nuboot_radix/assets/images/twitter_icon.png" class="socialIcon" ></img>
          <img src="/profiles/dkan/themes/contrib/nuboot_radix/assets/images/facebook_icon.png" class="socialIcon" ></img>
          
          <p class="hrule"></p>
          
          <p class="sectionHeading">Explore datasets by theme</p>
          <p class="label label-default">10 THEMES</p>
          <a href="/dataset" class="btn btn-primary datasetCountLabel">See all datasets</a>
        </div>
       
        <!-- datasets by theme -->
        <div class="datasetThemeRow middleContent">
          <div class="datasetTheme">
            <i class="fa fa-money themeIcon"></i>
            <div class="themeText"><a href="/dataset/economy-and-employment">Economy and employment</a></div>
          </div>
          <div class="datasetTheme">
            <i class="fa fa-leaf themeIcon"></i>
            <div class="themeText"><a href="/dataset/environmental-sustainability-and-resilience">Environmental sustainability and resilience</a></div>
          </div>
          <div class="datasetTheme">
            <i class="fa fa-globe themeIcon"></i>
            <div class="themeText"><a href="/dataset/south-africa-region-and-world">South Africa in the region and the world</a></div>
          </div>
          <div class="datasetTheme">
            <i class="fa fa-home themeIcon"></i>
            <div class="themeText"><a href="/dataset/human-settlements">Human settlements</a></div>
          </div>
          <div class="datasetTheme">
            <i class="fa fa-book themeIcon"></i>
            <div class="themeText"><a href="/dataset/education-training-and-innovation">Education, training and innovation</a></div>
          </div>
        </div>
        <div class="datasetThemeRow middleContent">
          <div class="datasetTheme">
            <i class="fa fa-medkit themeIcon"></i>
            <div class="themeText"><a href="/dataset/healthcare-all">Healthcare for all</a></div>
          </div>
          <div class="datasetTheme">
            <i class="fa fa-bullhorn themeIcon"></i>
            <div class="themeText"><a href="/dataset/community-safety">Community and safety</a></div>
          </div>
          <div class="datasetTheme">
            <i class="fa fa-building-o themeIcon"></i>
            <div class="themeText"><a href="/dataset/nation-state-building">Nation state building</a></div>
          </div>
          <div class="datasetTheme">
            <i class="fa fa-eye themeIcon"></i>
            <div class="themeText"><a href="/dataset/corruption-fighting-and-transparency">Corruption fighting and transparency</a></div>
          </div>
          <div class="datasetTheme">
            <i class="fa fa-users themeIcon"></i>
            <div class="themeText"><a href="/dataset/nation-building-and-social-cohesion">Nation building and social cohesion</a></div>
          </div>
        </div>
      </div>
      <!--
         HERE Or OUTSIDE
      <div class="container" style="background-color:orange;">
       foobar
      </div>
      <div class="container" style="background-color:green;">
       foobar
      </div>
     -->
    </div>

  <!-- INJECT themes, popular and app showcase -->
</div>
