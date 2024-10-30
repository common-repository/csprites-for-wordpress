<?php
class SpriteImageRegistry{

  protected static $registry = array();  
  
  public static function register($imgPath, array $params = array()){
    $relPath = $imgPath;
    $absPath = (self::is_url($imgPath))?($imgPath):(SpriteConfig::get('rootDir').$relPath);
    
    if(is_dir($absPath)){
      $files = self::buildFileList($relPath);
    }
    else{
      $files = array($relPath);
    }

    foreach($files as $imgFile){
      self::addImage($imgFile, $params);
    }   
  }
  
  public static function getRegistry(){
    return self::$registry;
  }
   
  public static function processSprites(){
    SpriteCache::updateCache();
    if(count(self::$registry)){
      //call_user_func(self::$packerClass.'::pack', self::$registry, self::$longestWidth, self::$longestHeight, self::$totalArea);
      foreach(self::$registry as &$sprite){
        //First lets prepare all the sprite properties
        $sprite->prepareSprite();
        //Now lets sort it
        call_user_func(SpriteConfig::get('sorter').'::sort',$sprite);
        //And pack the sprite
        call_user_func(SpriteConfig::get('packer').'::pack', $sprite);
        //Write the sprite image to a file
        SpriteImageWriter::writeImages($sprite);
        //Update all the sprite styles to the registry
        SpriteStyleRegistry::addSprite($sprite);
      }
      //SpriteStyleRegistry::processCssMetaFiles();
    }
  }
  
  protected static function loadSorter(){
    if (include_once 'sorters/' .SpriteConfig::getSorter().'.php') {
      $classname = SpriteConfig::getSorter();
      return new $classname;
    }
    else {
      throw new SpriteException ('Sorter class not found.');
    }
  }
  
  protected static function addImage($path, $params){
    $spriteName = @$params['name'];
    $imageType  = @$params['imageType'];
    
    try{
      $spriteImage = new SpriteImage($path, $params);
    }
    catch(SpriteException $e){
      return NULL;
    }
    
    $type = ($imageType)?($imageType):($spriteImage->getType());
    $tempSprite = new SpriteSprite($spriteName, $type);
    
    if(!isset(self::$registry[$tempSprite->getKey()])){

      self::$registry[$tempSprite->getKey()] = $tempSprite;
    }
    self::$registry[$tempSprite->getKey()][] = $spriteImage;
  }
  
  public static function buildFileList($path){
    $root = SpriteConfig::get('rootDir');
    $path = $root.$path;
    $files = array();
    $fileObjs = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path));
    foreach($fileObjs as $name=>$fileObj){
      if($fileObj->isFile()){
        $files[] = preg_replace('`'.$root.'`si', '', $name, 1);        
      }
    }
    return $files;
  }
    
  public static function debug(){
    $output = '';
    foreach(self::$registry as $type=>$imageAr){
      $output .=  $type."<br>";
      foreach($imageAr as $key=>$image){
        $output .= $image;
      }
    }
    return $output;
  }
  
  public static function getHash(){
    return md5(serialize(self::$registry));
  }
  
  //Utitlity function
  public static function is_url($url){
    $url = substr($url,-1) == "/" ? substr($url,0,-1) : $url;
    if ( !$url || $url=="" ) return false;
    if ( !( $parts = @parse_url( $url ) ) ) return false;
    else {
        if ( $parts[scheme] != "http" && $parts[scheme] != "https" && $parts[scheme] != "ftp" && $parts[scheme] != "gopher" ) return false;
        else if ( !eregi( "^[0-9a-z]([-.]?[0-9a-z])*.[a-z]{2,4}$", $parts[host], $regs ) ) return false;
        else if ( !eregi( "^([0-9a-z-]|[_])*$", $parts[user], $regs ) ) return false;
        else if ( !eregi( "^([0-9a-z-]|[_])*$", $parts[pass], $regs ) ) return false;
       // else if ( !eregi( "^([0-9a-z/_.@~-]|[,])*$", $parts[path], $regs ) ){return false;} 
       // else if ( !eregi( "^[0-9a-z?&=#,]*$", $parts[query], $regs ) ) return false;
    }
    return true;
  }
  
  public static function reset(){
    self::$registry = array();
  }
}
?>