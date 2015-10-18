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
  <div class="panel-top panel-row borderImage" style="background-image:url(/profiles/dkan/themes/contrib/nuboot_radix/assets/images/hero.jpg);">
  </div>
    <!-- Active theme menu item -->
    <?php
        function getConnection(){
          $con =  mysqli_connect("localhost", "dkan", "Dkan123__I__321!", "dkan");
        
          if(!$con){
              die("Connection failed");
          }
          
          return $con;
        }
            
        function fetchThemeName($activeTag){
          $con = getConnection();
          $res = "";
          $sql = "select Title ".
                  "from ( ".
                  "select field_dataset_ref_target_id as 'ThemeID' ".
                  "from taxonomy_index ti ".
                  "left join taxonomy_term_data ttd ".
                  "on ti.tid = ttd.tid ".
                  "left join node n ".
                  "on n.nid = ti.nid ".
                  "left join field_data_field_dataset_ref fdfdr ".
                  "on fdfdr.entity_id = n.nid ".
                  "where LOWER(ttd.name) = LOWER('$activeTag') limit 1) as fv ".
                  "left join node n ".
                  "on fv.ThemeID = n.nid;";
                  
          $result = mysqli_query($con, $sql);
          if(mysqli_num_rows($result) > 0){
            if($row = mysqli_fetch_assoc($result)) {
                $res = $row["Title"];
            }
          }
          
          mysqli_close($con);   
          
          return $res;
        }
        
        function fetchResourceData($uuid){
          $con = getConnection();
          $obj = new stdClass();
          $sql = "SELECT nid, title FROM node where uuid = '$uuid';";
                  
          $result = mysqli_query($con, $sql);
          if(mysqli_num_rows($result) > 0){
            if($row = mysqli_fetch_assoc($result)) {
                
                $obj->nid = $row["nid"];
                $obj->title = $row["title"];
            }
          }
          
          mysqli_close($con);   
          
          return $obj;
        }
        
        function extractActiveTag(){
          $data = $_SERVER['REQUEST_URI'];
          $startPos = strrpos($data,"/");
          $result = substr($data,$startPos+1);
          return str_replace("-"," ",$result);
        }
        
        function extractActiveTrail($data){
          $startPos = strrpos($data,"<li class=\"active-trail\">");
          $result = substr($data, $startPos);
          return $result;
        }
        
        function extractResourceUUID(){
          $data = $_SERVER['REQUEST_URI'];
          $startPos = strrpos($data,"/");
          $result = substr($data,$startPos+1);
          return $result;
        }
        
        function makeDownloadUrl($nid){
          $result = "http://opendataportal.cloudapp.net/node/$nid/download";
          return $result;
        }
        
        // set custom content flags
        $isCustomContent = false;
        $isAllDataset = false;
        if(strpos($_SERVER['REQUEST_URI'],"/all-datasets") === 0){
          $isCustomContent = true;
          $isAllDataset = true;
        }
        
        // its a theme page
        $isThemePage = false;
        $pageHeader = "";
        if(strpos($_SERVER['REQUEST_URI'],"dataset/") && !strpos($_SERVER['REQUEST_URI'],"resource/") && !strpos($_SERVER['QUERY_STRING'],"query=") && $showMenu){
          $isCustomContent = true;
          $isThemePage = true;
          $term = "<li class=\"active-trail\">";
          $startThemeName = strrpos($breadcrumb, $term) + strlen($term);
          $endThemeName = strrpos($breadcrumb, "</li>", $startThemeName);
          $pageHeader = substr($breadcrumb, $startThemeName, ($endThemeName - $startThemeName));
          $tabs = "<h2 class=\"element-invisible\">Primary tabs</h2><h1 class=\"page-header\">".$pageHeader."</h1>";
        }
        
        // its a dataset page
        $isDatasetPage = false;
        $activeTagSearch = "";
        $themeName = "";
        if(strpos($_SERVER['REQUEST_URI'],"tags/")){
          $isCustomContent = true;
          $isDatasetPage = true;
          $activeTagSearch = extractActiveTag();
          $themeName = fetchThemeName($activeTagSearch);
        }
        
        // its a resource page
        $isResourcePage = false;
        if(strpos($_SERVER['REQUEST_URI'],"resource/")){
          $isCustomContent = true;
          $isResourcePage = true;
          $resourceUUID = extractResourceUUID();
          $resourceData = fetchResourceData($resourceUUID);
          $downloadUrl = makeDownloadUrl($resourceData->nid);
          $resourceTitle = "$resourceData->title <div class=\"downloadButton\"><ul class=\"tabs--primary nav nav-pills\"><li>";
          $resourceTitle .= "<a href=\"$downloadUrl\" class=\"active\">Download <i class=\"fa fa-download\"></i></a> </li></ul></div>";
          $tabs = "<h2 class=\"element-invisible\">Primary tabs</h2><h1 class=\"page-header\" style=\"border-bottom:0px solid #fff; color:#1d6919;\">".$resourceTitle."</h1>";
        }
        
        // its a search page - need to inject fakt
        if(empty($tabs) && $showMenu && !$isAllDataset){
          $tabs = "<h2 class=\"element-invisible\">Primary tabs</h2><h1 class=\"page-header\" style=\"border-bottom:0px solid #fff;\"> &nbsp; </h1>";
        }
        
        // mod the breadcrumb trail for : Dataset aka Resource page
        $breadcrumb = str_replace("<a href=\"/dataset\">Datasets</a>","<a href=\"/all-datasets\">All Datasets</a>",$breadcrumb);
        if($isDatasetPage){
          $original = $breadcrumb;          
          
          $breadcrumb = "<ul class=\"breadcrumb\"><li class=\"home-link\"><a href=\"/\"><i class=\"fa fa fa-home\"></i><span> Home</span></a></li><li><a href=\"/\">Home</a></li>";
          $breadcrumb .= "<li><a href=\"/all-datasets\">All Datasets</a></li>";
          if(!empty($themeName)){
            $themeUrlReady = strtolower($themeName);
            $themeUrlReady = str_replace(" ","-",$themeUrlReady);
            $breadcrumb .= "<li><a href=\"/dataset/".$themeUrlReady."\">".$themeName."</a></li>"; // reverse look up?
          }
          $breadcrumb .= extractActiveTrail($original);
        }
    
        // activate the right theme in the menu
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
        
      ?>
  
    <?php print $messages; ?>
    <div id="main" class="main container">
      <?php if($showMenu): ?>
        <div class="themeMenuSpacer">
            &nbsp;
        </div>
      <?php endif; ?>
            
      <?php if (!empty($breadcrumb)): print $breadcrumb; endif;?>
      <div class="main-row">
        <section>
          <a id="main-content"></a>
          <?php print render($title_prefix); ?>
          <?php if (!empty($title) && empty($is_panel)): ?>
            <h1 class="page-header"><?php print $title; ?></h1>
          <?php endif; ?>
          <?php print render($title_suffix); ?>
          <!-- Inject's the buttons -->
          <?php if (!empty($tabs)): ?>
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
              // All datasets page
              if($isAllDataset){
                $content = "<table id=\"viewTable\" style=\"border:none;\"><thead style=\"border-top-color:#fff\"><tr class=\"headerRowStyle\"><th style=\"border:none; min-width:15px;\">#</th><th style=\"border:none;\">DATASET NAME</th><th style=\"border:none;\">THEME</th><th style=\"border:none;\">FILETYPE</th><th style=\"border:none;\">DATE ADDED</th></tr></thead><tbody style=\"border-top-color:#DEAB14\">";
                $datasets = getAllDatasets();
                $pos = 1;
                foreach($datasets as $row){
                  $displayType = extractFileType($row->fileType);
                  $displayTime = makeTimeHumanTime($row->created);
                  $displayLink = createResourceLink($row->theme, $row->uuid);
                  
                  $content .= "<tr><td style=\"border:none; font-weight:bold;\">".$pos."</td><td style=\"border:none;\"><a href=\"".$displayLink."\">".$row->datasetName."</a></td><td style=\"border:none;\">".$row->theme."</td><td style=\"border:none;\">".$displayType."</td><td style=\"border:none;\">".$displayTime."</td></tr>";
                  
                  $pos++;
                }
                
                $content .= "</tbody></table>";
                // force a bootstrap of table functionality
                $content .= "<script>$(function(){ $('#viewTable').dataTable({ \"bPaginate\": true,\"bLengthChange\": false,\"bFilter\": true,\"bSort\": true,\"bInfo\": true, \"bAutoWidth\": true, \"iDisplayLength\": 15}); });</script>";
              }
              
              // A Theme's page
              if($isThemePage){
                 // move sub-theme tags up
                 $search = "<div class=\"content\">"; 
                 $replace = "<div class=\"content\" style=\"margin-top:-10px;\">";
                 $content = str_replace($search, $replace, $content);
                 
                 // inject SUB-THEMES: text
                 $search = "<div class=\"field-items\">";
                 $replace = "<div class=\"field-items\"><div class=\"sub-theme-text\">SUB-THEMES:</div>";
                 $content = str_replace($search, $replace, $content);

                 // Cut the data and resources section
                 $search = "<div id=\"data-and-resources\">";
                 $startPos = strpos($content, $search);
                 $search = "<section";
                 $endPos = strpos($content, $search, $startPos);
                 
                 $replaceStr = substr($content, $startPos, ($endPos - $startPos));
                 $content = str_replace($replaceStr, "", $content);
                 
                 // remove header
                 $search = "<h2>Dataset Info</h2>";
                 $replace = "";
                 $content = str_replace($search, $replace, $content);
                 
                 // remove <span class="field-group-table-description">
                 $search = "<span class=\"field-group-table-description\">";
                 $startPos = strpos($content, $search);
                 $search = "</span>";
                 $endPos = strpos($content, $search, $startPos);
                 $replaceStr = substr($content, $startPos, ($endPos - $startPos));
                 $content = str_replace($replaceStr, "", $content);
                 
                 // remove <table class="field-group-format group_additional">
                 $search = "<table class=\"field-group-format group_additional\">";
                 $startPos = strpos($content, $search);
                 $search = "</table>";
                 $endPos = strpos($content, $search, $startPos);
                 $replaceStr = substr($content, $startPos, ($endPos - $startPos));
                 $content = str_replace($replaceStr, "", $content);
                 
                 // inject resources after - </article>
                 $search = "</article>";
                 $themeData = generateThemeData($pageHeader);
                 $replace = "</article><div style=\"diplay:block; min-height:400px; width100%; margin-top:10px\">".$themeData."</div>";
                 $content = str_replace($search, $replace, $content);
                 
              }
              
              if($isDatasetPage){
                $content = generateSubThemeData($activeTagSearch,$themeName);
              }
              
              if($isResourcePage){
                $content = str_replace("sub-theme","field-item",$content);
              }
            }
            
            function generateSubThemeData($subtheme, $theme){
               $result = "";
               
               $subthemeResources = array();
               if(!empty($theme)){
                $subthemeResources = getSubThemeResources($subtheme);
               }
               
               $content = "<table id=\"viewTable\" style=\"border:none;\"><thead style=\"border-top-color:#fff\"><tr class=\"headerRowStyle\"><th style=\"border:none;\">#</th><th style=\"border:none;\">DATASET NAME</th><th style=\"border:none;\">FILETYPE</th><th style=\"border:none;\">DATE ADDED</th></tr></thead><tbody style=\"border-top-color:#DEAB14\">";
                $pos = 1;
                foreach($subthemeResources as $row){
                  $displayType = extractFileType($row->fileType);
                  $displayTime = makeTimeHumanTime($row->created);
                  $displayLink = createResourceLink($theme, $row->uuid);
                  
                  $content .= "<tr><td style=\"border:none; font-weight:bold;\">".$pos."</td><td style=\"border:none;\"><a href=\"".$displayLink."\">".$row->name."</a></td><td style=\"border:none;\">".$displayType."</td><td style=\"border:none;\">".$displayTime."</td></tr>";
                  
                  $pos++;
                }
                
                $content .= "</tbody></table>";
                // force a bootstrap of table functionality
                $content .= "<script>$(function(){ $('#viewTable').dataTable({ \"bPaginate\": true,\"bLengthChange\": false,\"bSort\": true,\"bInfo\": true, \"bAutoWidth\": true, \"iDisplayLength\": 15}); });</script>";
               
               return $content;
            }
            
            function generateThemeData($theme){
               $result = "";
               $themeResources = getThemeResources($theme);
               
               $content = "<table id=\"viewTable\" style=\"border:none;\"><thead style=\"border-top-color:#fff\"><tr class=\"headerRowStyle\"><th style=\"border:none;\">#</th><th style=\"border:none;\">DATASET NAME</th><th style=\"border:none;\">SUB THEME</th><th style=\"border:none;\">FILETYPE</th><th style=\"border:none;\">DATE ADDED</th></tr></thead><tbody style=\"border-top-color:#DEAB14\">";
                $pos = 1;
                foreach($themeResources as $row){
                  $displayType = extractFileType($row->fileType);
                  $displayTime = makeTimeHumanTime($row->created);
                  $displayLink = createResourceLink($theme, $row->uuid);
                  $subTheme = fetchSubTheme($row->nid);
                  
                  $content .= "<tr><td style=\"border:none; font-weight:bold;\">".$pos."</td><td style=\"border:none;\"><a href=\"".$displayLink."\">".$row->datasetName."</a></td><td style=\"border:none;\">".$subTheme."</td><td style=\"border:none;\">".$displayType."</td><td style=\"border:none;\">".$displayTime."</td></tr>";
                  
                  $pos++;
                }
                
                $content .= "</tbody></table>";
                // force a bootstrap of table functionality
                $content .= "<script>$(function(){ $('#viewTable').dataTable({ \"bPaginate\": true,\"bLengthChange\": false,\"bSort\": true,\"bInfo\": true, \"bAutoWidth\": true, \"iDisplayLength\": 15}); });</script>";
               
               return $content;
            }
            
            function createResourceLink($theme, $uuid){
              
              $themeUrlReady = strtolower($theme);
              $themeUrlReady = str_replace(" ","-",$themeUrlReady);
              
              return "/dataset/".$themeUrlReady."/resource/".$uuid;
            }
            
            function makeTimeHumanTime($value){
              return date("Y/m/d", $value);
            }
            
            function extractFileType($fileType){
              $pos = strrpos($fileType, "/");
              $type = substr($fileType,$pos+1);
              $result = strtoupper($type);
              if($result == "IP"){
                $result = "ZIP";
              }
              return $result;
            }
            
            function fetchSubTheme($nid){
                $con = getConnection();
                $res = "";
                $sql = "select ti.tid,name ".
                      "from taxonomy_index ti ".
                      "left join taxonomy_term_data ttd ".
                      "on ti.tid = ttd.tid ".
                      "where nid = $nid ".
                      "and ttd.vid = 2;";
                
                $result = mysqli_query($con, $sql);
                if(mysqli_num_rows($result) > 0){
                  if($row = mysqli_fetch_assoc($result)) {
                     $res = $row["name"];
                  }
                }
                
                mysqli_close($con);   
                
                return $res;
            }
            
            function getThemeResources($theme){
              $con = getConnection();
                $datasets = array();
                $pos = 0;

                $sql = "select fd.title as 'Name', filemime, filesize, fd.uuid, fd.created, fd.nid from ".
                      "(select title, filemime, filesize, n.uuid, created, entity_id, nid ".
                      "from field_data_field_resources fdfr ".
                      "left join file_usage fu ".
                      "on fdfr.field_resources_target_id = fu.id ".
                      "left join file_managed fm ".
                      "on fu.fid = fm.fid ".
                      "left join node n ".
                      "on fu.id = n.nid) fd ".
                      "left join node n ".
                      "on fd.entity_id = n.nid ".
                      "where n.title = '".$theme."' ". 
                      "order by created desc;";
                
                
                $result = mysqli_query($con, $sql);
                if(mysqli_num_rows($result) > 0){
                  while($row = mysqli_fetch_assoc($result)) {
                    $obj = new stdClass();
                    $obj->datasetName = $row["Name"];
                    $obj->fileType = $row["filemime"];
                    $obj->uuid = $row["uuid"];
                    $obj->created = $row["created"];
                    $obj->nid = $row["nid"];
                    $datasets[$pos] = $obj;
                    $pos++;
                  }
                }
                
                mysqli_close($con);   
                
                return $datasets;
            }
            
            function getSubThemeResources($subtheme){
              $con = getConnection();
                $datasets = array();
                $pos = 0;

                $sql = "select n.title, n.uuid, filemime,n.created ".
                        "from taxonomy_index ti ".
                        "left join taxonomy_term_data ttd ".
                        "on ti.tid = ttd.tid ".
                        "left join node n ".
                        "on n.nid = ti.nid ".
                        "left join field_data_field_dataset_ref fdfdr ".
                        "on fdfdr.entity_id = n.nid ".
                        "left join file_usage fu ".
                        "on fu.id = n.nid ".
                        "left join file_managed fm ".
                        "on fm.fid = fu.fid ".
                        "where LOWER(ttd.name) = LOWER('$subtheme') and filemime IS NOT NULL;";
                
                
                $result = mysqli_query($con, $sql);
                if(mysqli_num_rows($result) > 0){
                  while($row = mysqli_fetch_assoc($result)) {
                    $obj = new stdClass();
                    $obj->name = $row["title"];
                    $obj->fileType = $row["filemime"];
                    $obj->uuid = $row["uuid"];
                    $obj->created = $row["created"];
                    $datasets[$pos] = $obj;
                    $pos++;
                  }
                }
                
                mysqli_close($con);   
                
                return $datasets;
            }
            
            function getAllDatasets(){
                $con = getConnection();
                $datasets = array();
                $pos = 0;
                
                $sql = "select n.title as 'Theme', fd.title as 'Name', filemime, filesize, fd.uuid, fd.created from ".
                      "(select title, filemime, filesize, n.uuid, created, entity_id ".
                      "from field_data_field_resources fdfr ".
                      "left join file_usage fu ".
                      "on fdfr.field_resources_target_id = fu.id ".
                      "left join file_managed fm ".
                      "on fu.fid = fm.fid ".
                      "left join node n ".
                      "on fu.id = n.nid) fd ".
                      "left join node n ".
                      "on fd.entity_id = n.nid ".
                      "where n.title in ('Test Theme', 'Test Theme 2', ". 
                      "'Economy and employment', ".
                      "'Environmental sustainability and resilience', ".
                      "'South Africa in the region and the world', ".
                      "'Human settlements', ".
                      "'Education, training and innovation', ".
                      "'Nation state building', ".
                      "'Corruption fighting and transparency', ".
                      "'Nation building and social cohesion', ".
                      "'Community and safety', ".
                      "'Healthcare for all' ".
                      ") order by created desc;";
                
                
                $result = mysqli_query($con, $sql);
                if(mysqli_num_rows($result) > 0){
                  while($row = mysqli_fetch_assoc($result)) {
                    $obj = new stdClass();
                    $obj->datasetName = $row["Name"];
                    $obj->theme = $row["Theme"];
                    $obj->fileType = $row["filemime"];
                    $obj->uuid = $row["uuid"];
                    $obj->created = $row["created"];
                    $datasets[$pos] = $obj;
                    $pos++;
                  }
                }
                
                mysqli_close($con);   
                
                return $datasets;
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
  <a href="http://www.microsoft.com" target="_blank"><img class="footerImage" src="http://opendataportal.cloudapp.net/profiles/dkan/themes/contrib/nuboot_radix/assets/images/microsoft_logo_small.png"></a>
  <a href="http://www.chillisoft.co.za" target="_blank"><img class="footerImage" src="http://opendataportal.cloudapp.net/profiles/dkan/themes/contrib/nuboot_radix/assets/images/chillisoft_logo_small.png"></a>
  <a href="http://code4sa.org/" target="_blank"><img class="footerImage" src="http://opendataportal.cloudapp.net/profiles/dkan/themes/contrib/nuboot_radix/assets/images/code4sa_logo_small.png"></a>
</footer>

