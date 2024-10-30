<?php
if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('You are not allowed to call this page directly.'); }

/**
 * Check for hook
 */
if ( function_exists('register_uninstall_hook') )
	register_uninstall_hook(__FILE__, 'cSpriteUninstall');
 
 /**
 * Delete options in database
 */
function cSpriteUninstall() {
  delete_option('cSpriteJpgQuality');
  delete_option('cSpritePngCompression');
  delete_option('cSpriteCacheTime');
  delete_option('cSpriteForceNoPadding');
  delete_option('cSpriteClearCacheNow');
  delete_option('cSpriteProcessPng');
  delete_option('cSpriteProcessJpg');
  delete_option('cSpriteProcessGif');
  
  delete_option('cSpriteShowSize');
  delete_option('cSpriteAltString');
  delete_option('cSpriteReplaceAlt');
  delete_option('cSpriteTitleString');
  delete_option('cSpriteReplaceTitle');  
}
?>