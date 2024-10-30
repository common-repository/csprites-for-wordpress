<?php
/*
Plugin Name: cSprites for Wordpress
Plugin URI: http://wordpress.org/extend/plugins/csprites-for-wordpress/
Description: Automatically compress you content images into a big sprite, decreasing web page load time.
Version: 0.510
Author: Adrian Mummey
Author URI: http://mummey.org/
Copyright 2008-2009 Adrian Mummey

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('You are not allowed to call this page directly.'); }

function cSpriteFindImg($content){
  $html = str_get_html($content);
  $imgs = $html->find('img');
  foreach($imgs as $img){
    if($img->rel){
      $rels = explode(' ', $img->rel);
      if(in_array('no-sprite', $rels)){
        continue;
      }
      if(in_array('crop', $rels)){
        $params['crop'] = true;
      }
    }
    $params = array();
    $imgSource = $img->src;
    if($img->width){
      $params['new_width'] = $img->width;
    }
    if($img->height){
      $params['new_height'] = $img->height;
    }
    $cleanedSource = (SpriteImageRegistry::is_url($imgSource))?($imgSource):(cSpriteCleanUrl($imgSource));      
    SpritePreprocessorCache::addOriginalImageSource($cleanedSource, $params);
    SpritePreprocessorCache::addOriginalImageTag($img->outertext());
  }
  $html->clear();
}
function cSpriteImgReplace($content){

  $html = new simple_html_dom();
  $html = str_get_html($content);
  $html->set_callback('cSpriteReplacementCallback');
  $output = $html->save();
  $html->clear();
  return $output;
}
function cSpriteReplacementCallback(&$element){
  global $post;
  
  if ($element->tag =='img'){
    $originalSource = $element->src;
    $src = (SpriteImageRegistry::is_url($element->src))?($element->src):(cSpriteCleanUrl($element->src));
    $spriteClass = SpritePreprocessorCache::getImageClass($src);
    
    if($spriteClass){
      $element->src = CSPRITE_TRANS_GIF_URL;
      $element->class = $element->class.' '.$spriteClass;
      //Should we add width and height attributes to tag?
      if(CSPRITE_SHOW_SIZE){
        $size = SpritePreprocessorCache::getImageSize($src);
        if(isset($size['width'])){$element->width = $size['width'];}
        if(isset($size['height'])){$element->height = $size['height'];}
      }
    }
      $name_array   = array();
      $val_array    = array();
      $path_parts   = pathinfo($originalSource);
      $name_array[] = '%category';
      $categories   = get_the_category(); 
      $val_array[]  = @$categories[0]->slug;
      $name_array[] = '%spacedcategory';
      $val_array[]  = str_replace('-', ' ', @$categories[0]->slug);

      $cats = '';
      $spacedcats = '';
      foreach($categories as $cat){
        $cats .= $cat->slug.' ';  
        $spacedcats .= str_replace('-', ' ', $cat->slug).' ';
      }
      $name_array[] = '%categories';
      $val_array[]  = trim($cats);
      $name_array[] = '%spacedcategories';
      $val_array[]  = trim($spacedcats);
      $name_array[] = '%title';
      $val_array[]  = $post->post_title;
      $name_array[] = '%cleantitle';
      $val_array[]  = sanitize_title_with_dashes($post->post_title);
      $name_array[] = '%spacedtitle';
      $val_array[]  = str_replace('-', ' ', sanitize_title_with_dashes($post->post_title));
      $name_array[] = '%path';
      $val_array[]  = $originalSource;
      $name_array[] = '%name';
      $val_array[]  = @$path_parts['basename'];
      $name_array[] = '%basename';
      $val_array[]  = @$path_parts['filename'];
      $name_array[] = '%cleanbasename';
      $val_array[]  = sanitize_title_with_dashes(@$path_parts['filename']);
      $name_array[] = '%spacedbasename';
      $val_array[]  = str_replace('-', ' ', sanitize_title_with_dashes(@$path_parts['filename']));
      
      
      
      $title_pattern = str_replace($name_array, $val_array, CSPRITE_TITLE_STRING);
      if(CSPRITE_ALT_STRING){
        $alt_pattern = str_replace($name_array, $val_array, CSPRITE_ALT_STRING);
        $element->alt = htmlspecialchars((CSPRITE_REPLACE_ALT || is_null($element->alt))?($alt_pattern):($element->alt.' '.$alt_pattern));
      }
      if(CSPRITE_TITLE_STRING){
        $element->title = htmlspecialchars((CSPRITE_REPLACE_TITLE || is_null($element->title))?($title_pattern):($element->title.' '.$title_pattern));
      }
      
  }
  
}

function cspriteStyles(){

	$cstyles = "
	<!-- begin wpcsprites scripts -->
	<script type=\"text/javascript\">
    //<![CDATA[
    document.write('<link rel=\"stylesheet\" href=\"". untrailingslashit(CSPRITE_PLUGIN_URL).untrailingslashit(SpritePreprocessorCache::getCssFile())."\" type=\"text/css\" media=\"screen\" />');
    //]]>
    </script>
	<!-- end wpcsprites scripts -->\n";
  echo $cstyles; 

}

function updatecSpriteOptions(){
  $imagePropertiesOrig = SpriteConfig::get('imageProperties');
  add_option('cSpriteJpgQuality', $imagePropertiesOrig['jpgQuality']);
  add_option('cSpritePngCompression', $imagePropertiesOrig['pngCompression']);
  add_option('cSpriteCacheTime', SpriteConfig::get('cacheTime'));
  add_option('cSpriteForceNoPadding', SpriteConfig::get('forceNoPadding'));
  add_option('cSpriteClearCacheNow', '');
  add_option('cSpriteProcessPng', 0);
  add_option('cSpriteProcessJpg', 1);
  add_option('cSpriteProcessGif', 1);
  add_option('cSpriteShowSize', 1);
  add_option('cSpriteAltString', '');
  add_option('cSpriteReplaceAlt', 0);
  add_option('cSpriteTitleString', '');
  add_option('cSpriteReplaceTitle', 0);
  
  $imageProperties['jpgQuality'] = (is_numeric(get_option('cSpriteJpgQuality')))?(get_option('cSpriteJpgQuality')):($imagePropertiesOrig['jpgQuality']);
  $imageProperties['pngCompression'] = (is_numeric(get_option('cSpritePngCompression')))?(get_option('cSpritePngCompression')):($imagePropertiesOrig['pngCompression']);
  $acceptedTypes = array();
  if(get_option('cSpriteProcessPng')){
    $acceptedTypes[] = 'png';
  }
  if(get_option('cSpriteProcessJpg')){
    $acceptedTypes[] = 'jpg';
    $acceptedTypes[] = 'jpeg';    
  }
  if(get_option('cSpriteProcessGif')){
    $acceptedTypes[] = 'gif';
  }
  SpriteConfig::set('acceptedTypes', $acceptedTypes);
  SpriteConfig::set('imageProperties', $imageProperties);
  SpriteConfig::set('cacheTime', (is_numeric(get_option('cacheTime')))?(get_option('cSpriteCacheTime')):(20));
  SpriteConfig::set('forceNoPadding', get_option('cSpriteForceNoPadding'));
  
  /*Let's load some options into constants to use later. Good idea? probably not...*/
  define('CSPRITE_SHOW_SIZE', get_option('cSpriteShowSize'));
  define('CSPRITE_ALT_STRING', get_option('cSpriteAltString'));
  define('CSPRITE_REPLACE_ALT', get_option('cSpriteReplaceAlt'));
  define('CSPRITE_TITLE_STRING', get_option('cSpriteTitleString'));
  define('CSPRITE_REPLACE_TITLE', get_option('cSpriteReplaceTitle'));  
  //define('CSPRITE_REMOVE_CHARS', get_option('cSpriteRemoveChars'));
 // define('CSPRITE_REPLACE_CHARS', get_option('cSpriteReplaceChars'));

}

function cSpriteLoopStart(){
  global $wp_query;
  
  if(in_the_loop() && !is_admin()){
    $the_posts = $wp_query->posts;
    foreach($the_posts as $post){
      $content = $post->post_content;
    	$content = apply_filters('the_content', $content);
    	$content = str_replace(']]>', ']]&gt;', $content);
    	$content = do_shortcode($content);
      cSpriteFindImg($content);
    }
    if(SpritePreprocessorCache::preprocessorCacheNeedsCreation()){
        SpritePreprocessorCache::registerImageSources();
        Sprite::process();
    }
    add_filter('the_content', 'cSpriteImgReplace',50);
  }
  
}

/*function cSpriteLoopEnd(){
  Sprite::reset();
}*/

function cSpriteCleanUrl($url){
  if(@$url[0] != '/'){
    $url = '/'.$url;
  }
  return get_bloginfo('wpurl').$url;
}

function cSpriteCheckCacheWritable(){
  $file = CSPRITE_CACHE_DIR.'/permission.txt'; 
  return (@file_put_contents($file, 'sanity check') !== false);
}

function cSpriteCheckPhpVersion(){
  return version_compare(PHP_VERSION, '5.0.0', '>');
}

function cSpriteCheckWordPressVersion(){
  global $wp_version;
  $minimum_wp = '2.7';
  return version_compare($wp_version, $minimum_wp, '>=');
}

function cSpritePreflightChecks(){
  return cSpriteCheckCacheWritable() && cSpriteCheckPhpVersion() && cSpriteCheckWordPressVersion() && !is_feed();
}
function cSpriteWriteCssCache(){
  if(!is_admin()){
    try{
      SpritePreprocessorCache::writeCachedAttributes();
    }
    catch(SpriteException $e){}
  }
}

if (!function_exists('is_admin_page')) {
	function is_admin_page() {
		if (function_exists('is_admin')) {
			return is_admin();
		}
		if (function_exists('check_admin_referer')) {
			return true;
		}
		else {
			return false;
		}
	}
}
function cSpriteFeedCheck(){
  if(is_feed()){cSpriteShutdownFrontend();}
}
function cSpriteShutdownFrontend(){
  remove_action('get_footer', 'cspriteStyles');
  remove_action('loop_start', 'cSpriteLoopStart');
  remove_action('shutdown', 'cSpriteWriteCssCache');
  remove_filter('the_content', 'cSpriteImgReplace');
}
function startcSprites(){
  /*cSprites config*/
  if (!defined('PLUGINDIR')) { define('PLUGINDIR','wp-content/plugins');}
  define('CSPRITE_PLUGIN_NAME', basename(dirname(__FILE__))); /*csprites-for-wordpress*/
  define('CSPRITE_PLUGIN_BASENAME', plugin_basename(__FILE__));
  define('CSPRITE_PLUGIN_DIR', trailingslashit(WP_PLUGIN_DIR).CSPRITE_PLUGIN_NAME);
  define('CSPRITE_PLUGIN_URL', trailingslashit(WP_PLUGIN_URL).CSPRITE_PLUGIN_NAME);
  define('SPRITE_CONFIG_FILE', trailingslashit(CSPRITE_PLUGIN_DIR).'config.yml');
  define('CSPRITE_CACHE_DIR', trailingslashit(CSPRITE_PLUGIN_DIR).'cache');
  define('CSPRITE_LANG_DIR', trailingslashit(CSPRITE_PLUGIN_NAME).'lang');
  define('CSPRITE_TRANS_GIF_URL', trailingslashit(CSPRITE_PLUGIN_URL).'1_1_trans.gif');
  define('CSPRITE_CSS_URL_DIR', trailingslashit(CSPRITE_PLUGIN_URL).'includes/interface');
  define('CSPRITE_JS_URL_DIR', trailingslashit(CSPRITE_PLUGIN_URL).'includes/interface');
  
  require_once('includes/options.php');
  require_once('includes/uninstall.php');

  load_plugin_textdomain( CSPRITE_PLUGIN_NAME, trailingslashit(PLUGINDIR).CSPRITE_LANG_DIR, CSPRITE_PLUGIN_NAME );
  
  if (cSpritePreflightChecks()) {  
    /*Init global vars*/
    require_once('csprites/Sprite.php');
    if(!class_exists('simple_html_dom_node')){
      require_once('includes/simple_html_dom.php');
    }
    SpriteConfig::set('rootDir', CSPRITE_PLUGIN_DIR);
    SpriteConfig::set('transparentImagePath', CSPRITE_TRANS_GIF_URL);
    SpriteConfig::set('cssImagePath', CSPRITE_PLUGIN_URL);
    
    if(!is_admin_page()){
      add_action('get_footer', 'cspriteStyles');
      add_action('loop_start', 'cSpriteLoopStart');
      add_action('template_redirect', 'cSpriteFeedCheck' );
      add_action('shutdown', 'cSpriteWriteCssCache');
    }
    else{
      if (current_user_can('manage_options')) {
        add_action('update_option_cSpriteClearCacheNow', 'cSpriteClearCache');
        add_action('admin_menu', 'cSpriteAdminMenu');
//        add_filter("plugin_action_links", 'cSpriteActionLinks', 10, 2);
        add_filter('plugin_action_links_'.CSPRITE_PLUGIN_BASENAME, 'cSpriteActionLinks');
      }      
      //Only load scripts and styles if we are on the csprites option page
      if(strstr($_REQUEST['page'], CSPRITE_PLUGIN_NAME)!== false){
        if (function_exists('wp_enqueue_style')) {
          wp_enqueue_style('cSpritesAdminStyles', trailingslashit(CSPRITE_CSS_URL_DIR).'csprites.min.css', array(), '', 'screen');
        }
        if (function_exists('wp_enqueue_script')) {
          wp_enqueue_script('cSpritesCheckbox', trailingslashit(CSPRITE_JS_URL_DIR).'jquery.checkbox.js', array('jquery'), '1.0');
          wp_enqueue_script('jquery-slider', trailingslashit(CSPRITE_JS_URL_DIR).'ui.slider.js', array('jquery', 'jquery-ui-core'), '1.0');
          wp_enqueue_script('cSpriteInit', trailingslashit(CSPRITE_JS_URL_DIR).'csprites.js', array('jquery-slider'), '1.0');        
        }
        
        add_action('admin_head', 'wp_print_styles', '100');
      }
    }
    updatecSpriteOptions();  
  }
  else{
    add_action('admin_menu', 'cSpriteAdminMenuChecksFailed');
  }

}
add_action('plugins_loaded', 'startcSprites');

function cSpriteActionLinks( $links) { 
	// Add a link to this plugin's settings page
	$settings_link = '<a href="options-general.php?page='.CSPRITE_PLUGIN_NAME.'/includes/options.php" alt="'.__('cSprites Settings Page').'" title="'.__('cSprites Settings Page').'">'.__('Settings').'</a>';
	array_unshift( $links, $settings_link ); 
	return $links; 
}



?>