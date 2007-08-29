<?php
  
  ## Created by Matz [kematzy@gmail.com] 2007-08-20
  ## Released under the same licence as Blueprint CSS.
  ## Version:   v.04 (2007-08-28)
  $version_str = 'v.04 (2007-08-29)'; # used by the script below
  $bp_version = '0.5'; # used in generated CSS code
  $bp_version_date = '2007-08-28';
  
  
  ## KLUDGE::  really dirty stuff just to enable support for hidden versions of this generator
  $blueprint_version = 'v05'; # used as a filename
  # used in the <form> below, enables the creation of hidden versions of index.VERSION.php
  $form_action = dirname($_SERVER['PHP_SELF']).'/';  # removes the /index.php from the path
  # $form_action = $_SERVER['PHP_SELF'];
  $bgg_js_file = 'bgg.js';
  
    
  
  ## Please feel free to use as you wish. IF you make improvements to the script, please let me know, 
  ## so I too can benefit from those improvements ;-)
  
  ## WARRANTY & LIABILITY:
  
  ## NO WARRANTY WHATSOEVER IS GIVEN FOR THIS SCRIPT. USE AT YOUR OWN RISK AND ON A 'AS IS' BASIS.
    
  
  
  ###############  !! IMPORTANT INSTALLATION INSTRUCTIONS !! ################
  
  ## Solution developed on Mac OS X.4 with PHP 5.2.1 (entropy.ch)
  
  ## For the grid.png image generation to work you must ensure the following:
  
  ## 1. YOU MUST HAVE A DIRECTORY CALLED cache IN THE SAME DIRECTORY AS THIS FILE 
  ## 2. THIS cache DIRECTORY MUST BE WRITABLE BY THIS SCRIPT
  
  # To make the cache directory writable use the following command in the Terminal (Command Line)
  
  # cd /path/to/blueprint-generator/
  # chmod 777 cache
  
  # that should be it
  
  #####################################################
  
  
  
  
  ## Begin Script Execution:
  
  
  # check if we have a POST or not ?
  if (@$_POST['generator']) {
    # yes, we have a post, so begin work
    
    ## Helper functions:
    
    # calculates the column width based on the given page width, margin and number of columns:
    # rounds the value off to the nearest full integer value i.e: 48.33333  => 48
    function calc_col_width($page_width,$margin_width,$number_of_columns){
      return intval( ((($page_width + $margin_width) / $number_of_columns) - $margin_width) );
    }
    # calculates the page width based on the given column width, margin and number of columns:
    function calc_page_width($column_width,$margin_width,$number_of_columns){
      return ((($column_width + $margin_width) * $number_of_columns) - $margin_width);
    }
    # generates the .span-N CSS code chunk.
    function spans($column_width,$margin_width,$number_of_columns){
      $out = '';
      for ($i=1; $i <= $number_of_columns; $i++) { 
        $n = ($i <= 9) ? " " : '';
        $out .= ".span-$i $n{ width: " . ((($column_width + $margin_width) * $i) - $margin_width) ."px;}\n";
      }
      return substr($out, 0,strlen($out)-2) . " margin: 0; }\n";
    }
    # generates the .append-N CSS code chunk.
    function appends($column_width,$margin_width,$number_of_columns){
      $out = '';
      for ($i=1; $i <= ($number_of_columns-1); $i++) {
        $n = ($i <= 9) ? " " : '';
        $out .= ".append-$i $n{ padding-right: " . (($column_width + $margin_width) * $i) ."px;}\n";
      }
      return $out;
    }
    # generates the .prepend-N CSS code chunk.
    function prepends($column_width,$margin_width,$number_of_columns){
      $out = '';
      for ($i=1; $i <= ($number_of_columns-1); $i++) { 
        $n = ($i <= 9) ? " " : '';
        $out .= ".prepend-$i $n{ padding-left: " . (($column_width + $margin_width) * $i) ."px;}\n";
      }
      return $out;
    }
    # generates the .pull-N CSS code chunk.
    function pulls($column_width,$margin_width,$number_of_columns){
      $out = '';
      for ($i=1; $i <= $number_of_columns; $i++) {
        $out .= ".pull-$i { margin-left: -" . (($column_width + $margin_width) * $i) ."px;}\n";
      }
      return $out;
    }
    # generates the .push-N CSS code chunk.
    function pushs($column_width,$margin_width,$number_of_columns){
      $out = ".push-0  { margin: 0 0 0 18px; float: right; } /* Right aligns the image. */\n";
      for ($i=1; $i <= $number_of_columns; $i++) {
        $out .= ".push-$i { margin: 0 -" . (($column_width + $margin_width) * $i) ."px 0 18px; float: right;}\n";
      }
      return $out;
    }
    
    ##/ Helper Functions
    
    
    # extract the vars into $number_of_columns, $margin_width, $col_width, $page_width
    extract(@$_POST['generator']);
    
    # make sure we have a number of columns, defaults to 14
    $number_of_columns = ($number_of_columns != '') ? intval($number_of_columns) : '14';
    # make sure we have a margin numbers, defaults to 20px
    $margin_width = ($margin_width != '') ? intval($margin_width) : '20';
    # check that we have a page_width supplied
    $page_width = ($desired_page_width != '') ? intval($desired_page_width) : calc_page_width($column_width,$margin_width,$number_of_columns);
    # make sure we have a col_width
    $column_width = ($column_width != '') ? intval($column_width) : calc_col_width($page_width,$margin_width,$number_of_columns);
    # set the spans css
    $spans = spans($column_width,$margin_width,$number_of_columns);
    # set the appends css
    $appends = appends($column_width,$margin_width,$number_of_columns);
    # set the prepends css
    $prepends = prepends($column_width,$margin_width,$number_of_columns);
    # set the pulls css (defaults to 3 pulls)
    $pulls = pulls($column_width,$margin_width,4);
    # set the pushs css (defaults to 3 pushs)
    $pushs = pushs($column_width,$margin_width,4);
    
    ## DO A SANITY CHECK ON THE PAGE WIDTH TO CATCH INCORRECT VALUES
    $total_page_width = calc_page_width($column_width,$margin_width,$number_of_columns);
    if ($total_page_width !== $page_width) {
      # the page_width we were given does not correspond with the page_width we've calculated
      # so replace it with the calculated value
      $page_width = $total_page_width;
    }    
    
    # load the template files with the defined __VARIABLES__ in them.
    $grid_css = file_get_contents('tmpl/grid.'.$blueprint_version.'.css');
    $compressed_css = file_get_contents('tmpl/compressed.'.$blueprint_version.'.css');
    
    $gen_date = @strftime('%Y-%m-%d');
    $gen_url = 'http://'. $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    # now replace the variables within grid.css.
    $grid_css_replacements_arr = array(
      '__BP_VERSION__' => $bp_version,
      '__BP_VERSION_DATE__' => $bp_version_date,
      '__GEN_DATE__' => $gen_date,
      '__GEN_URL__' => $gen_url,
      '__NUMBER_OF_COLUMNS__' => $number_of_columns,
      '__COLUMN_WIDTH__' => $column_width,
      '__MARGIN_WIDTH__' => $margin_width,
      '__HALF_OF_MARGIN_WIDTH__' => ($margin_width/2),
      '__HALF_OF_MARGIN_WIDTH_MINUS_BORDER__' => (($margin_width/2)-1),
      '__PAGE_WIDTH__' => $page_width,
      '__SPANS__' => $spans,
      '__APPENDS__' => $appends,
      '__PREPENDS__' => $prepends,
      '__PULLS__' => $pulls,
      '__PUSHS__' => $pushs
    );
    foreach ($grid_css_replacements_arr as $key => $value) {
      $grid_css = str_replace($key,$value, $grid_css);
    }
    
    # now replace the variables within compressed.css.
    $compressed_css_replacements_arr = array(
      '__BP_VERSION__' => $bp_version,
      '__BP_VERSION_DATE__' => $bp_version_date,
      '__GEN_DATE__' => $gen_date,
      '__GEN_URL__' => $gen_url,
      '__MARGIN_WIDTH__' => $margin_width,
      '__HALF_OF_MARGIN_WIDTH__' => ($margin_width/2),
      '__HALF_OF_MARGIN_WIDTH_MINUS_BORDER__' => (($margin_width/2)-1),
      '__PAGE_WIDTH__' => $page_width,
      '__SPANS__' => $spans,
      '__APPENDS__' => $appends,
      '__PREPENDS__' => $prepends,
      '__PULLS__' => $pulls,
      '__PUSHS__' => $pushs
    );
    foreach ($compressed_css_replacements_arr as $key => $value) {
      $compressed_css = str_replace($key,$value, $compressed_css);
    }
    
    # Special usage monitoring:
    # stores a log of all the requests coming in and the numbers people choose
    $logmsg = @strftime('%Y-%m-%d %T').'::IP=['. $_SERVER['REMOTE_ADDR']. "]::nc=[$number_of_columns],cw=[$column_width],m=[$margin_width],pw=[$page_width];\n";
    $logfile = @strftime('%Y-%m-%d') . '-usage.log';
    error_log($logmsg,3,dirname(__FILE__).'/logs/'.$logfile);
    
    
    
    ##########  IMAGE GENERATION CODE BLOCK ##################
    
    # finally, let's add the grid image to the page.
    
    # set the heigth of the baseline to the default: 18px
    $baseline_height = '18';
    # create the grid image filename. Saving it with it's specifics just so that we can can cache & reuse it.
    $filename = 'blueprint_grid_' . $column_width . '+' . $margin_width .'x' . $baseline_height . '.png';
    
    # now look for the file first of all, does it exist?
    if(!file_exists(dirname(__FILE__).'/cache/'.$filename)){
      # echo "file does not exist";
      # crash out if the cache directory is NOT writable by the server
      if(!is_writable(dirname(__FILE__).'/cache/')){
        die("<html><head><title>ERROR:</title></head><body><h1>ERROR: The cache directory is not writable. Please read the installation instructions.</h1></body></html>");
      }
      
      # OK, let's create the image or die
      $imgh = @imagecreate(($column_width + $margin_width), $baseline_height)
          or die("<html><head><title>ERROR:</title></head><body><h1>ERROR: Cannot Initialize new GD image stream</h1></body></html>");
      
      # set the colours for the grid 
      $background_color = imagecolorallocate($imgh, 255, 255, 255);
      $grid_color = imagecolorallocate($imgh, 243, 245, 247);
      $stroke_color = imagecolorallocate($imgh, 230,230,236);
      
      # now add the background
      imagefill($imgh, 0, 0, $background_color);
      # then add the grid color
      imagefilledrectangle($imgh, 0, 0, $column_width, $baseline_height, $grid_color);
      # then add the baseline line
      imagefilledrectangle($imgh, 0, ($baseline_height -1), ($column_width + $margin_width), $baseline_height, $stroke_color);
      # write the .png file
      imagepng($imgh,dirname(__FILE__).'/cache/'.$filename);
      imagedestroy($imgh); # clean up after yourself
    }
    
    ##########  END IMAGE GENERATION CODE BLOCK ##################
  }
  else
  {
    # no POST has happened yet, so showing default values
    
    $baseline_height = '18'; # set the heigth of the baseline to the default: 18px
    $number_of_columns = '24';
    $column_width = '30';
    # margin
    $margin_width = '10';
    # page width (defaults to [empty])
    $page_width = '950';
    # other default values
    $grid_css = file_get_contents('cache/grid.'. $blueprint_version .'.css');
    $compressed_css = file_get_contents('cache/compressed.'. $blueprint_version .'.css');
        
    # create the grid image filename. 
    # The filename formula is "blueprint_grid_<COLUMN_WIDTH>+<MARGIN_WIDTH>x<BASELINE_HEIGHT.png"
    $filename = 'blueprint_grid_' . $column_width . '+' . $margin_width .'x' . $baseline_height . '.png';    
  }

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" 
  "http://www.w3.org/TR/html4/strict.dtd">
<html lang="en">
  <head>
    
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Blueprint Grid CSS Generator</title>
    
    <script type="text/javascript" charset="utf-8">
      // default grid value settings
      var default_settings = {
        desired_page_width: 950,
        total_page_width: '---',
        number_of_columns: 24,
        column_width: 30,
        margin_width: 10
      };
    </script>
    
    
    <script src="js/domtab.js" type="text/javascript" charset="utf-8"></script>
    <script src="js/<?php echo $bgg_js_file; ?>" type="text/javascript" charset="utf-8"></script>
        
    <!-- Framework CSS -->
    <link rel="stylesheet" href="css/blueprint/screen.css" type="text/css" media="screen, projection">
    <link rel="stylesheet" href="css/blueprint/print.css" type="text/css" media="print">    
    <link rel="stylesheet" href="css/bgg.css" type="text/css" media="screen, projection" title="overrides and extras" charset="utf-8">  
    
  </head>
  
  <body>
    
    <!-- div.container -->
    <div class="container">
      
      <!-- div#header -->
      <div class="column span-24">
        <!-- div.column span-17 -->
        <div class="column span-17">
          <h1>Blueprint Grid CSS Generator</h1>
        </div>
        <!-- /div.column span-17 -->
        <!-- div.column span-7 last -->
        <div class="column span-7 last">
          <p class="version">Version: <?php echo $version_str; ?></p>
        </div>
        <!-- /div.column span-7 last -->
      </div>
      <!-- /div#header -->
      
      <hr>
      
      <!-- div#intro -->
      <div class="column span-24">
        <div class="column span-12">
          <h2>INTRODUCTION:</h2>
          <p>This tool will help you generate more flexible versions of <a href="http://code.google.com/p/blueprintcss/">Blueprint's</a> <tt>grid.css</tt> and <tt>compressed.css</tt> and <tt>grid.png</tt> files. Whether you prefer <strong>8</strong>, <strong>10</strong>,<strong>16</strong> or <strong>24</strong> columns in your design, this generator now enables you that flexibility with <a href="http://code.google.com/p/blueprintcss/">Blueprint</a>.</p>
          
          <p class="nb">NB! The default Blueprint CSS files are already generated for you to copy below.</p>
        </div>
        
        <div class="column span-10 push-0 last">
          
          <h2>USAGE:</h2>
          <ol>
            <li>Enter your desired <strong>number of columns</strong>.</li>
            <li>Your <strong>column width</strong>.</li>
            <li>Enter the width of your <strong>margin</strong>.</li>
            <li>Enter the desired <strong>page width</strong>.</li>
            <li>Click <strong>'Generate CSS'</strong> button and copy the results from the <strong>Generated Code</strong> section.</li>
          </ol>
          
        </div>
        
      </div>
      <!-- /div#intro -->
      
      <hr>
      
      <!-- div#main -->
      <div class="column span-24">
        
        <!-- div#generator_form -->
        <div class="column span-7">
          <div class="box">
            <h2>Generator Form:</h2>
            <form name="generator" method="post" id="generator" action="<?php echo $form_action; ?>">
              <fieldset>
                <p><label>Number of Columns:</label>
                <br>
                <input type="text" id="num_cols" name="generator[number_of_columns]" value="<?php echo $number_of_columns; ?>" maxlength="2" onkeyup="fieldKeypress();" onblur="checkIntRange(this,2,40);" tabindex="1" class="txt" />
                &nbsp;&nbsp;&nbsp;
                <input type="button" value="-" id="btn_dec_columns" onclick="btnDecColumns();">
                <input type="button" value="+" id="btn_inc_columns" onclick="btnIncColumns();">
                
                <span class="btn_fit"><input type="button" id="btn_fit_column_count" value="&laquo; Fit" onclick="btnFitColumnCount();"></span>
                <br>
                </p>
                
                <p><label>Column Width:</label>
                <br>
                <input type="text" id="column_width" name="generator[column_width]" value="<?php echo $column_width; ?>" maxlength="3" onkeyup="fieldKeypress();" onblur="checkIntRange(this,10,200);" tabindex="2" class="txt" /> px
                
                <span class="btn_fit"><input type="button" id="btn_fit_column_width" value="&laquo; Fit" onclick="btnFitColumnWidth();"></span>
                <br>
                </p>
                
                <p><label>Margin Width:</label>
                <br>
                <input type="text" id="margin_width" name="generator[margin_width]" value="<?php echo $margin_width; ?>" maxlength="2" onkeyup="fieldKeypress();" onblur="checkIntRange(this,5,50);" tabindex="3" class="txt" /> px
                
                <span class="btn_fit"><input type="button" id="btn_fit_margin_width" value="&laquo; Fit" onclick="btnFitMarginWidth();"></span>
                <br>
                </p>
                
                <p><label>Total Page Width:</label>
                <br>
                <input type="hidden" name="total_page_width" id="total_page_width" value="" />
                <span id="total_width_output">---</span>
                </p>
                
                <p><label>Desired Page Width:</label>
                <br>
                <input type="text" id="desired_page_width" name="generator[desired_page_width]" value="<?php echo $page_width; ?>" maxlength="4" onkeyup="fieldKeypress();" onblur="checkIntRange(this,200,1800);" tabindex="4" class="txt" /> px</p>
                
                <p>
                  <input type="submit" id="submit" name="submit" value="Generate CSS" disabled="true" tabindex="5">
                  <span class="btn_fit"><input type="button" value="Reset" onclick="btnResetForm();"></span>
                </p>
                
              </fieldset>
            </form>
          </div>
        </div>
        <!-- /div#generator_form -->
        
        <!-- div#generated_code -->
        <div class="column span-16 push-0 last">
          <h2>Generated Code:</h2>
          
          <p>Click in the text area below to copy all the CSS code and save your versions of <strong>grid.css</strong>, <strong>compressed.css</strong> and <strong>grid.png</strong>. <span class="nb">Click tabs below to switch between files.</span></p>
          
          <!-- div.domtab -->
          <div class="domtab">
            <ul class="domtabs">
              <li><a href="#t1" class="active">Grid.css</a></li>
              <li><a href="#t2">Compressed.css</a></li>
              <li><a href="#t3">Grid.png</a></li>
            </ul>
            <div class="dombox">
              <a name="t1" id="t1"></a>
              <textarea id="g_css" class="txt_area" name="g_css" rows="50" cols="60" tabindex="6"><?php echo $grid_css ?></textarea>
            </div>
            <div class="dombox">
              <a name="t2" id="t2"></a>
              <textarea id="compressed_css" class="txt_area" name="compressed_css" rows="50" cols="60" tabindex="7"><?php echo $compressed_css ?></textarea>
            </div>
            <div class="dombox">
              <a name="t3" id="t3"></a>
              <!-- div#grid_img -->
              <div id="grid_img">
                <p>&nbsp;
                <br>
                <img class="attn" src="cache/<?php echo $filename; ?>" alt="<?php echo $filename; ?>" /></p>
                <p>Drag image to your desktop or right/control-click on the image to save it.</p>
                <p>&nbsp;</p>
              </div>
              <!-- /div#grid_img -->
            </div>
          </div>
          <!-- /div.domtab -->
          
        </div>
        <!-- /div#generated_code -->
        
      </div>
      <!-- /div#main -->
      
      <hr>
      
      <!-- div#credit -->
      <div class="column span-24">
        <div class="column span-6">
          <p>&nbsp;</p>
        </div>
        <div class="column span-18 last">
          <p>A big <strong>Thank You !</strong> goes out to all the developers involved in <a href="http://code.google.com/p/blueprintcss/">Blueprint CSS</a>. You guys have helped me loads.</p>
          
          <h2>LICENCE:</h2>
          <p>The code for this 'solution' is released under the same licence as <a href="http://code.google.com/p/blueprintcss/">Blueprint</a>. Please feel free to use as you wish. IF you make improvements to the script, please let me know, so I too can benefit from those improvements ;-)</p>
          
          <p><a href="blueprint-generator.zip" title="right-click/control-click to download file">Download .zip file (47 Kb)</a></p>
        </div>
        
      </div>
      <!-- div#credit -->
      
      <hr>
      
      <!-- div#footer -->
      <div class="column span-24">
        <p>&copy; Copyright 2007 matz at this domain AND other copyright holders</p>
      </div>
      <!-- /div#footer -->
      
    </div>
    <!-- /div.container -->
    
  </body>
  
</html>