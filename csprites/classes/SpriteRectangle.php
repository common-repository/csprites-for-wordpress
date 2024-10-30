<?php 
class SpriteRectangle{

  public $width;
  public $height;
  public $left;
  public $top;
  public $right;
  public $bottom;

  public function SpriteRectangle($left, $top, $right, $bottom){
  
    $this->left = $left;
    $this->top = $top;
    $this->right = $right;
    $this->bottom = $bottom;
    $this->width = $this->right - $this->left;
    $this->height = $this->bottom - $this->top;
  }
  
  public function willFit(SpriteImage $spriteImage){
    SpriteConfig::debug("willFit :".$spriteImage->getWidth()." <= ".$this->width.") && (".$spriteImage->getHeight()." <= ".$this->height."))");
    return (($spriteImage->getWidth() <= $this->width) && ($spriteImage->getHeight() <= $this->height));
  }
  
  public function willFitPerfectly(SpriteImage $spriteImage){
    SpriteConfig::debug('Perfect Fit: '.$spriteImage->getWidth().' '.$spriteImage->getHeight());
    return (($spriteImage->getWidth() == $this->width) && ($spriteImage->getHeight() == $this->height));
  }
  
  public function grow($x=100, $y=100){
    $this->right += $x;
    $this->bottom += $y;
    SpriteConfig::debug('Growing : '.$this->right.' '.$this->bottom);
    $this->width = $this->right - $this->left;
    $this->height = $this->bottom - $this->top;
  }
  
  public function __toString(){
    return 'l:'.$this->left.' t:'.$this->top.' r:'.$this->right.' b:'.$this->bottom;
  }
  
  public function isValidRectangle(){
    return (($this->bottom - $this->top > 0) && ($this->right - $this->left > 0));
  }
  
  public function update($left, $top, $right, $bottom){
    $this->left = $left;
    $this->top = $top;
    $this->right = $right;
    $this->bottom = $bottom;
    $this->width = $this->right - $this->left;
    $this->height = $this->bottom - $this->top;
  }
  
  public function getResizedRectangle(SpriteRectangle $source, $crop=false){
    if(!$crop){
      return $source;
    }
    else{
     $dest = new SpriteRectangle(0,0,0,0);
      //dest is square
      if($this->right == $this->bottom){
        $dest->right = $this->right;
        $dest->bottom = $this->bottom;
      }
      //dest is wide
      else if($this->right > $this->bottom){
       SpriteConfig::debug('wide');
       $dest->right = $this->right;
       $dest->bottom = ceil($source->bottom * ($this->bottom/$this->right));
      }
      //dest is tall
      else{
        SpriteConfig::debug('tall');
        $dest->bottom = $this->bottom;
        $dest->right = ceil($source->right * ($this->right/$this->bottom));
      }

      $dest->left = floor(abs($source->right - $dest->right)/2);
      $dest->right += $dest->left;
      $dest->width = $dest->right -$dest->left;
      $dest->top = floor(abs($source->bottom - $dest->bottom)/2);
      $dest->bottom += $dest->top;
      $dest->height = $dest->bottom - $dest->top;
      return $dest;
    }
  }
}