<div class="panel-display panel-full-width clearfix" <?php if (!empty($css_id)) { print "id=\"$css_id\""; } ?>>

    <div class="panel-middle panel-row">
      <div class="container">
        <div class="inside middleContent">
          Welcome to the South African Government National Open Data Portal. 
          <!--<img src="/profiles/dkan/themes/contrib/nuboot_radix/assets/images/twitter_icon.png" class="socialIcon" ></img>-->
          <!-- https://twitter.com/intent/tweet?original_referer=http%3A%2F%2Fopendataportal.cloudapp.net%2F&ref_src=twsrc%5Etfw&text=National%20Open%20Data%20Portal&tw_p=tweetbutton&url=http%3A%2F%2Fopendataportal.cloudapp.net%2F-->
          <div class="socialIcon">
            <a href="https://twitter.com/share" class="twitter-share-button" data-size="large" data-count="none">Tweet</a>
            <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
          </div>
          <img src="/profiles/dkan/themes/contrib/nuboot_radix/assets/images/facebook_icon.png" class="socialIcon" ></img>
          
          <p class="hrule"></p>
          
          <p class="sectionHeading">Explore datasets by theme</p>
          <a href="/all-datasets" class="btn btn-primary datasetCountLabel">See all datasets</a>
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
            <div class="themeText"><a href="/dataset/community-and-safety">Community and safety</a></div>
          </div>
          <div class="datasetTheme">
            <i class="fa fa-building-o themeIcon"></i>
            <div class="themeText"><a href="/dataset/nation-state-building">Nation state building</a></div>
          </div>
          <div class="datasetTheme">
            <i class="fa fa-eye themeIcon"></i>
            <div class="themeText"><a href="/dataset/government-transparency">Government Transparency</a></div>
          </div>
          <div class="datasetTheme">
            <i class="fa fa-users themeIcon"></i>
            <div class="themeText"><a href="/dataset/nation-building-and-social-cohesion">Nation building and social cohesion</a></div>
          </div>
        </div>
      </div>
            
      <?php
        function getConnection(){
          $con =  mysqli_connect("localhost", "dkan", "Dkan123__I__321!", "dkan");
        
          if(!$con){
              die("Connection failed");
          }
          
          return $con;
        }
        
        function getAllRecentDatasets(){
                $con = getConnection();
                $datasets = array();
                $pos = 0;
                
                $sql = "select n.title as 'Theme',fd.title as 'Name',fd.uuid, fd.created from ".
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
                      "'Governement transparency', ".
                      "'Nation building and social cohesion', ".
                      "'Community and safety', ".
                      "'Healthcare for all' ".
                      ") order by created desc limit 10;";
                
                
                $result = mysqli_query($con, $sql);
                if(mysqli_num_rows($result) > 0){
                  while($row = mysqli_fetch_assoc($result)) {
                    $obj = new stdClass();
                    $obj->theme = $row["Theme"];
                    $obj->datasetName = $row["Name"];
                    $obj->created = $row["created"];
                    $obj->uuid = $row["uuid"];
                    $datasets[$pos] = $obj;
                    $pos++;
                  }
                }
                
                mysqli_close($con);   
                
                return $datasets;
            }
            
            function createResourceLink($theme, $uuid){
              
              $themeUrlReady = strtolower($theme);
              $themeUrlReady = str_replace(" ","-",$themeUrlReady);
              $themeUrlReady = str_replace("-for-","-", $themeUrlReady);
              
              return "/dataset/".$themeUrlReady."/resource/".$uuid;
            }
            
            function makeTimeHumanTime($value){
              return date("Y/m/d", $value);
            }
            
            // build recent content
            $recentContent = "<table id=\"viewTable\" class=\"dataTable\" style=\"border:none;\"><thead style=\"border-top-color:#fff;\"><tr class=\"headerRowStyle\"><th style=\"border:none; background-color:#935416; \">DATASET NAME</th><th style=\"border:none; background-color:#935416;\">DATE ADDED</th></tr></thead><tbody style=\"border-top-color:#DEAB14\">";
            $datasets = getAllRecentDatasets();
            $pos = 1;
            foreach($datasets as $row){
              $displayTime = makeTimeHumanTime($row->created);
              $displayLink = createResourceLink($row->theme, $row->uuid);
              $rowClass = (($pos % 2 != 0) ? "odd" : "even" );
              $recentContent .= "<tr class=\"$rowClass\"><td style=\"border:none;\"><a href=\"".$displayLink."\">".$row->datasetName."</a></td><td style=\"border:none;\">".$displayTime."</td></tr>";
//              $recentContent .= "<tr><td style=\"border:none;\"><a href=\"".$displayLink."\">".$row->datasetName."</a></td><td style=\"border:none;\">".$displayTime."</td></tr>";
          
              $pos++;
            }
            
            $recentContent .= "</tbody></table>";
            //$recentContent .= "<script>$(function(){ $('#viewTable').dataTable(bFilter: false, bInfo: false});</script>";
            
      ?>
      
      <div class="panel-full-width panel-row" style="background-color:#EA761E; min-height:450px; padding-top:30px; padding-bottom:30px;">
        <div class="panel-middle panel-row">
          <div class="container">
            <div class="orangeLeftBlock">
              <i class="fa fa-clock-o" style="color:#fff; font-size:24px;"></i> <span class="orangeBlockHeader"> &nbsp; Recently added datasets</span>
              <br/>
              <?php print $recentContent; ?>           
            </div>
            <div class="orangeRightBlock">
              <a class="twitter-timeline"  href="https://twitter.com/OGPSA" data-widget-id="655842520258211841">Tweets by @OGPSA</a>
              <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+"://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
              </a>
            </div>
          </div>
        </div>
      </div>
      <div class="panel-top panel-row" style="background-color:#80B35B; min-height:200px;">
        <div class="panel-middle panel-row">
          <div class="container middleContent">
            <div class="greenLeftBlock">
              <i class="fa fa-terminal" style="color:#fff; font-size:24px;"></i> <span class="orangeBlockHeader">&nbsp; App showcases</span>        
               <br/>
                <div class="imageContainer">
                  <a href="http://wazimap.co.za/"><img src="profiles/dkan/themes/contrib/nuboot_radix/assets/images/wazimap.png" width="195" height="90"/></a>
                  <div class="imageTitle">
                     Wazimap
                  </div>
                </div>
                <div class="imageContainer" style="margin-left:10px;">
                  <a href="http://mpr.code4sa.org/"><img src="profiles/dkan/themes/contrib/nuboot_radix/assets/images/medicine_registry.jpg" width="195" height="90"/></a>
                  <div class="imageTitle">
                     Medicine price registry
                  </div>
                </div> 
                <div class="imageContainer" style="margin-left:10px;">
                  <a href="http://openbylaws.org.za/"><img src="profiles/dkan/themes/contrib/nuboot_radix/assets/images/openbylaws.png" width="195" height="90"/></a>
                  <div class="imageTitle">
                     Open By-laws
                  </div>
                </div>      
            </div>
            <!--<div class="greenRightBlock">
               Data suggestions here
            </div>-->
          </div>
        </div>
      </div>
     
</div>


