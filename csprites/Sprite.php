<?php
if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('You are not allowed to call this page directly.'); }
//require_once('config/spyc/spyc.php5');

if(!defined('SPRITE_ROOT_DIR')){
  define('SPRITE_ROOT_DIR', realpath(dirname(__FILE__)));
}

if(!defined('SPRITE_CONFIG_FILE')){
  define('SPRITE_CONFIG_FILE', SPRITE_ROOT_DIR.'/config/config.yml');
}

/*This will only work with PHP 5 >= 5.1.2*/
spl_autoload_register(array('SpriteBootstrap', 'autoload'));

/*  If you have PHP 5 < 5.1.2 Comment out the above line and do the following:
    1. If you have an __autoload($class) function already add the following line
        after the function definition
        SpriteBootstrap::autoload($class);
    2. If you don't have an autoload function uncomment the following line */
//function __autoload($class){ SpriteBootstrap::autoload($class); }

//If that STILL doesn't work then comment out spl_autoload_register line and uncomment all these requires
/*require_once('classes/SpritePreprocessorCache.php');
require_once('classes/SpriteIterable.php');
require_once('classes/Sprite.php');
require_once('classes/SpriteAbstractPacker.php');
require_once('classes/SpriteAbstractPackingNode.php');
require_once('classes/SpriteAbstractParser.php');
require_once('classes/SpriteCache.php');
require_once('classes/SpriteConfig.php');
require_once('classes/SpriteException.php');
require_once('classes/SpriteHashable.php');
require_once('classes/SpriteImage.php');
require_once('classes/SpriteImageRegistry.php');
require_once('classes/SpriteImageWriter.php');
require_once('classes/SpriteRectangle.php');
require_once('classes/SpriteSorter.php');
require_once('classes/SpriteSprite.php');
require_once('classes/SpriteStyleGroup.php');
require_once('classes/SpriteStyleNode.php');
require_once('classes/SpriteStyleRegistry.php');
require_once('classes/SpriteTemplate.php');
require_once('classes/SpriteTemplateRegistry.php');
require_once('classes/Spyc.php');
require_once('packers/SpriteDefaultPacker.php');
require_once('packers/SpriteDefaultPackingNode.php');
require_once('parsers/SpriteDefaultCssParser.php');
require_once('sorters/SpriteAreaSorter.php');
require_once('sorters/SpriteLongestDimensionSorter.php');*/


SpriteConfig::set('rootDir', spriteGetWebRoot());

class SpriteBootstrap{

  public static function autoload($class_name){
    if(is_array($class_name)){
     // $class_name = $class_name[0];
    }
    if(file_exists(SPRITE_ROOT_DIR.'/classes/'.$class_name.'.php')){
      require_once 'classes/'.$class_name . '.php';
    }
    else if(file_exists(SPRITE_ROOT_DIR.'/sorters/'.$class_name.'.php')){
      require_once 'sorters/'.$class_name.'.php';
    }
    else if(file_exists(SPRITE_ROOT_DIR.'/packers/'.$class_name.'.php')){
      require_once 'packers/'.$class_name.'.php';
    }
    else if(file_exists(SPRITE_ROOT_DIR.'/parsers/'.$class_name.'.php')){
      require_once 'parsers/'.$class_name.'.php';
    }
    else{
      //throw new Exception('Class loading Failed');
    }
  }
}

function spriteGetWebRoot(){
  $local= getenv("SCRIPT_NAME");
  $absolute = realpath(basename($local));
  $absolute =str_replace("\\","/",$absolute);
  $fullPath = preg_replace('`'.$local.'`si', '', $absolute, 1);
  return $fullPath;

}
?>