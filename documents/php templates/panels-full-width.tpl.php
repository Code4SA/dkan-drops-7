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

  <?php if($content['middle']): ?>
    <div class="panel-middle panel-row">
      <div class="container">
        <div class="inside">
          Welcome to the South African Government National Open Data Portal. 
          <img src="http://opendataportal.cloudapp.net/profiles/dkan/themes/contrib/nuboot_radix/assets/images/twitter_icon.png" class="socialIcon" ></img>
          <img src="http://opendataportal.cloudapp.net/profiles/dkan/themes/contrib/nuboot_radix/assets/images/facebook_icon.png" class="socialIcon" ></img>
          
          <p class="hrule"></p>
          
          <p class="sectionHeading">Explore datasets by theme</p>
          <p class="label label-default">10 THEMES</p>
          <p class="btn btn-primary datasetCountLabel">See all datasets</p>
        </div>
        <!-- TODO : insert datasets by theme -->
        <div class="inside">
          themes
        </div>
      </div>
      <!--
      <div class="container" style="background-color:orange;">
       foobar
      </div>
      <div class="container" style="background-color:green;">
       foobar
      </div>
     -->
    </div>
  <?php endif; ?>

  <!-- INJECT themes, popular and app showcase -->
</div>
