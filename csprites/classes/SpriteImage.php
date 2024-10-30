	<?php

class SpriteImage implements SpriteIterable, SpriteHashable {
  
  protected $imgPath;
  protected $relativePath;
  protected $imgType;
  protected $imgExtension;
  protected $sizeArray;
  protected $area;
  protected $fileSize;
  protected $imageInfo;
  protected $position;
  protected $margin;
  protected $params;
  protected $newSize;
  protected $newSizeCrop = false;
  
  public function SpriteImage($path, array $params = array()){
    $this->imgPath = (SpriteImageRegistry::is_url($path))?($path):(SpriteConfig::get('rootDir').$path);
    $this->relativePath = $path;
    if(SpriteImageRegistry::is_url(html_entity_decode($path))){
      if(!($this->fileSize = $this->urlfilesize($this->imgPath))){
        SpriteConfig::debug("file existence problem");
        throw new SpriteException($this->imgPath.' : File does not exist or is size 0');
      }  
    }
    else{
      if(!($this->fileSize = filesize($this->imgPath))){
        SpriteConfig::debug("file existence problem");
        throw new SpriteException($this->imgPath.' : File does not exist or is size 0');
      }
    }
    

    if(!($this->sizeArray = getimagesize($this->imgPath, $this->imageinfo))){
      SpriteConfig::debug($this->imgPath."image size read problem");
      throw new SpriteException($this->imgPath.' : Image size could not be read');
    }
    SpriteConfig::debug('bits: '.$this->sizeArray['bits'].' channels:'.@$this->sizeArray['channels'].' mime:'.$this->sizeArray['mime']);
    $this->position = new SpriteRectangle(0, 0, $this->sizeArray[0], $this->sizeArray[1]);
    $this->processType();
    if(!$this->sizeArray){
      SpriteConfig::debug('Image size misread');
      throw new SpriteException($this->imgPath.' : Image size could not be read');
    }
    $this->setNewSize($params);
    $this->setMargins($params);
    $this->params = $params;
    
  }
  
  public function getPath(){
    return $this->imgPath;
  }  
  
  public function getRelativePath(){
    return $this->relativePath;
  }
  
  public function getType(){
    return $this->imgType;
  }
  
  public function getWidth(){
    return $this->newSize->right + $this->margin->left + $this->margin->right;
  }
  
  public function getOriginalWidth(){
    return $this->sizeArray[0];
  }
  
  public function getHeight(){
    return $this->newSize->bottom + $this->margin->top + $this->margin->bottom;
  }
  
  public function getOriginalHeight(){
    return $this->sizeArray[1];
  }
  
  public function getExtension(){
    return $this->imgExtension;
  }
  
  public function getArea(){
    return ($this->getWidth() * $this->getHeight());
  }
  
  public function getOriginalArea(){
    return $this->getOriginalWidth() * $this->getOriginalHeight();
  }
  
  public function getOriginalRectangle(){
    return new SpriteRectangle(0,0, $this->getOriginalWidth(), $this->getOriginalHeight());
  }
  
  public function getCrop(){
    return $this->newSizeCrop;
  }
  
  public function getSizeArray(){
    return $this->sizeArray;
  }
  
  public function getFileSize(){
    return $this->fileSize;
  }
  
  public function getImageInfo(){
    return $this->imageInfo;
  }
  
  public function getColorDepth(){
    return $this->sizeArray['bits'];
  }
  
  public function getMimeType(){
    return $this->sizeArray['mime'];
  }
  
  public function getPosition(){
    return $this->position;
  }
  
  public function getNewSize(){
    return $this->newSize;
  }
  
  public function getMargin(){
    return $this->margin;
  }
  
  public function getParams(){
    return $this->params;
  }
  
  public function setPosition(SpriteRectangle $rect){
    $this->position = $rect;
  }
  
  public function isTall(){
    return ($this->getHeight() > $this->getWidth());
  }
  
  public function isWide(){
    return ($this->getWidth() > $this->getHeight());
  }
  
  public function isSquare(){
    return ($this->getWidth() == $this->getHeight());
  }
  
  public function needsResize(){
    return ($this->newSize->right - $this->getOriginalWidth() != 0 || $this->newSize->bottom - $this->getOriginalHeight() != 0);
  }
  
  public function getLongestDimension(){
    return ($this->isTall())?($this->getHeight()):($this->getWidth());
  }
  
  public function getKey(){
    return $this->getRelativePath();
  }
  
  public function __toString(){
    $output = ''."\n";
    $output .= '<li>Path :'.$this->getPath().'</li>'."\n";
    $output .= '<li>Type :'.$this->getType().'</li>'."\n";
    $output .= '<li>Extension :'.$this->getExtension().'</li>'."\n";
    $output .= '<li>FileSize :'.$this->getFileSize().'</li>'."\n";            
    $output .= '<li>Dimension :'.$this->getWidth().'x'.$this->getHeight().'</li>'."\n";
    $output .= ''."\n";
    return $output;
  }
  
  public function getHash(){
    return md5($this->getRelativePath());
  }
  
  public function getCssClass(){
    return 'sprite'.$this->getHash();
  }
  
  public function updateAlignment(array $spriteParams = array()){
    if(isset($spriteParams['longestWidth']) && isset($spriteParams['longestHeight'])){
      if(isset($this->params['sprite-align'])){
      switch($this->params['sprite-align']){
        case 'left':{
          $rightMargin = $spriteParams['longestWidth'] - ($this->margin->left + $this->newSize->right);
          $this->margin = new SpriteRectangle($this->margin->left, $this->margin->top, $rightMargin, $this->margin->bottom);
          $this->position = new SpriteRectangle(0,0, $spriteParams['longestWidth'], $this->newSize->bottom);
          break;
        }
        case 'right':{
          $leftMargin = $spriteParams['longestWidth'] - ($this->margin->right + $this->newSize->right);
          $this->margin = new SpriteRectangle($leftMargin, $this->margin->top, $this->margin->right, $this->margin->bottom);
          $this->position = new SpriteRectangle(0,0, $spriteParams['longestWidth'], $this->position->bottom);
          break;
        }
        case 'top':{
          $bottomMargin = $spriteParams['longestHeight'] - ($this->margin->top + $this->newSize->bottom);
          $this->margin = new SpriteRectangle($this->margin->left, $this->margin->top, $this->margin->right, $bottomMargin);
          $this->position = new SpriteRectangle(0,0, $this->position->right, $spriteParams['longestHeight']);
          break;
        }
        case 'bottom':{
          $topMargin = $spriteParams['longestHeight'] - ($this->margin->bottom + $this->newSize->bottom);
          $this->margin = new SpriteRectangle($this->margin->left, $topMargin, $this->margin->right, $this->margin->bottom);
          $this->position = new SpriteRectangle(0,0, $this->position->right, $spriteParams['longestHeight']);
          break;        
        }
      }//end switch
      }
    }
  }
  
  protected function processType(){
    $this->imgExtension = trim(strtolower(pathinfo($this->getPath(), PATHINFO_EXTENSION)));
    
    if($this->getExtension() == 'png'){
      //$this->imgType = $this->getExtension().'-'.$this->getColorDepth();
      $this->imgType = $this->getExtension();      
    }
    else{
      $this->imgType = $this->getExtension();
    }
    if(!in_array(strtolower($this->getExtension()), SpriteConfig::get('acceptedTypes'))){
      SpriteConfig::debug('Extension Type Mismatch: '.$this->getExtension());
      throw new SpriteException($this->getExtension().' : is not an acceptable image type.');
    }
  }
  protected function setMargins(array $params = array()){
    //First Handle Margins
    if(isset($params['sprite-margin'])){
      if(is_array($params['sprite-margin'])){
        $this->margin = new SpriteRectangle($params['sprite-margin'][3], $params['sprite-margin'][0], $params['sprite-margin'][1], $params['sprite-margin'][2]);
        $this->position = new SpriteRectangle(0, 0, $this->newSize->right + $this->margin->left + $this->margin->right, $this->newSize->bottom + $this->margin->top + $this->margin->bottom);
      }
    }
    else{
        $this->margin = new SpriteRectangle(0,0,0,0);
    }
    
  }
  protected function setNewSize(array $params = array()){
    $this->newSize = new SpriteRectangle(0,0, $this->getOriginalWidth(), $this->getOriginalHeight());
    
    if(isset($params['new_width']) && isset($params['new_height'])){
      $this->newSize = new SpriteRectangle(0,0, $params['new_width'], $params['new_height']);
    }
    else if(isset($params['new_width'])){
      if($params['new_width'] != 0){
        $this->newSize = new SpriteRectangle(0,0, $params['new_width'], ceil($this->getOriginalHeight() * ($params['new_width']/$this->getOriginalWidth())));
      }
      else{
        throw new SpriteException('Width can not be a zero value');
      }
    }
    else if(isset($params['new_height'])){
      if($params['new_height'] != 0){
        $this->newSize = new SpriteRectangle(0,0, ceil($this->getOriginalWidth() * ($params['new_height']/$this->getOriginalHeight())),$params['new_height']);
      }
      else{
        throw new SpriteException('Height can not be a zero value');
      }
    }
    
    if(isset($params['crop'])){
      $this->newSizeCrop = true;
    }
    
    if(!$this->newSize->isValidRectangle()){
        throw new SpriteException('New size is not a valid value');
    }
    $this->position = new SpriteRectangle(0,0, $this->newSize->right, $this->newSize->bottom);    
    SpriteConfig::debug('New Position: '.$this->position);
  }
  protected function urlfilesize($url,$thereturn = null) {
    $headers = @get_headers($url, 1);
    if($headers == null){
      return null;
    }
    $x = array_change_key_case($headers,CASE_LOWER);
    $x = $x['content-length'];
    return $x;
  }
}

?>