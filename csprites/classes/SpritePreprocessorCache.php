<?php
class SpritePreprocessorCache extends SpriteCache{
  protected static $imgSourceTagPairs;
  protected static $originalImageSources = array();
  protected static $originalImageParams = array();
  protected static $imgTag = '';
  protected static $cachedAttributesFilename = '';
  protected static $cachedAttributes;
  
  public static function addOriginalImageSource($imgSource, array $params = array()){
    self::$originalImageSources[] = $imgSource;
    self::$originalImageParams[$imgSource] = $params;
  }
  public static function addOriginalImageTag($imgTag){
    self::$imgTag .= $imgTag;
  }
  
  public static function preprocessorCacheNeedsCreation(){
    self::$cachedAttributesFilename = SpriteConfig::get('rootDir').SpriteConfig::get('relTmplOutputDirectory').'/'.md5(self::$imgTag).'.cache';
    if(parent::needsCreation(self::$cachedAttributesFilename)){
      SpriteConfig::debug('Sprite Preprocessor .cache file needs Creation');
      return true;
    }
    try{
      self::loadCachedAttributes();
    }
    catch(SpriteException $e){
      return true;
    }
    
    $needsCreation = false;
    if(isset(self::$cachedAttributes['css_file'])){
      $needsCreation = $needsCreation || !file_exists(SpriteConfig::get('rootDir').self::$cachedAttributes['css_file']);
    }
    if(isset(self::$cachedAttributes['backgroundImages'])){
      foreach(self::$cachedAttributes['backgroundImages'] as $backgroundImage){
        $needsCreation = $needsCreation || !file_exists(SpriteConfig::get('rootDir').$backgroundImage);
      }
      
    }
    return $needsCreation;
  }
  public static function registerImageSources(){
    foreach(self::$originalImageSources as $imgSource){
      $params = isset(self::$originalImageParams[$imgSource])?(self::$originalImageParams[$imgSource]):(array());
      SpriteImageRegistry::register($imgSource, $params);
    }
  }
  public static function getCssFile(){
    if(!is_array(self::$cachedAttributes)){ self::$cachedAttributes = array();}
    if(!isset(self::$cachedAttributes['css_file'])){
      self::$cachedAttributes['css_file'] = SpriteStyleRegistry::getRelativePath();
      self::$cachedAttributes['backgroundImages'] = array();
      $styleRegistry = SpriteStyleRegistry::getStyleNodes();
      foreach($styleRegistry as $styleGroup){
        self::$cachedAttributes['backgroundImages'][] = $styleGroup->getBackgroundStyleNode()->getBackgroundImage();  
      }
    }
    return self::$cachedAttributes['css_file'];
  }
  public static function getImageSize($imgSource){
    if(!is_array(self::$cachedAttributes)){ self::$cachedAttributes = array();}
     if(!isset(self::$cachedAttributes[$imgSource]['size'])){
      self::$cachedAttributes[$imgSource]['size'] = Sprite::styleSize($imgSource);
    }
    return self::$cachedAttributes[$imgSource]['size'];
  }
  
  public static function getImageClass($imgSource){
    if(!is_array(self::$cachedAttributes)){ self::$cachedAttributes = array();}
     if(!isset(self::$cachedAttributes[$imgSource]['class'])){
      self::$cachedAttributes[$imgSource]['class'] = Sprite::styleClass($imgSource);
    }
    return self::$cachedAttributes[$imgSource]['class'];
  }
  protected static function loadCachedAttributes(){
    if(!file_exists(self::$cachedAttributesFilename)){
      throw new SpriteException('Preproccesor cache file does not exist');
    }
    self::$cachedAttributes = unserialize(file_get_contents(self::$cachedAttributesFilename));
    if(self::$cachedAttributes === false || !is_array(self::$cachedAttributes)){
      throw new SpriteException('Preproccesor cache can not be unserialized or is not an array');
    }
  }
  public static function writeCachedAttributes(){
    if(!self::$cachedAttributesFilename){
      throw new SpriteException('Preprocessor cache name does not exist');
    }
    if(is_array(self::$cachedAttributes)){
      if(file_put_contents(self::$cachedAttributesFilename, serialize(self::$cachedAttributes)) === false){
        throw new SpriteException('Could not write preprocessor cache, directory may not be writable');
      } 
    }
  }
}
?>