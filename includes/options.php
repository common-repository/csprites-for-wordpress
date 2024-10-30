<?php
function cSpriteAdminMenuChecksFailed(){
 add_options_page(__('cSprites Options', CSPRITE_PLUGIN_NAME), __('cSprites', CSPRITE_PLUGIN_NAME), 8, __FILE__, 'cSpriteOptionsPageChecksFailed');
}
function cSpriteAdminMenu() {
  add_options_page(__('cSprites Options', CSPRITE_PLUGIN_NAME), __('cSprites', CSPRITE_PLUGIN_NAME), 8, __FILE__, 'cSpriteOptionsPage');
}

function cSpriteClearCache(){
  remove_action('update_option_cSpriteClearCacheNow', 'cSpriteClearCache');
  if(isset($_REQUEST['cSpriteClearCacheNow'])){
    Sprite::clearCache();
  }
  update_option('cSpriteClearCacheNow', 0);
  return 0;
}

function cSpriteOptionsPage(){

?>
<div class="wrap">


<h2><?php _e('cSprites for Wordpress', CSPRITE_PLUGIN_NAME);?></h2>

<form method="post" action="options.php">
<?php wp_nonce_field('update-options'); ?>
<table class="form-table">
<tr><td colspan="2"><span style="font-size:large;text-decoration:underline;"><?php _e('Quality & Style Settings', CSPRITE_PLUGIN_NAME);?></span></td></tr>
<tr valign="top">
<th scope="row"><label for="cSpriteJpgQuality"><?php _e('JPG Quality',CSPRITE_PLUGIN_NAME);?></label></th>
<td>
<div id="jpgCompressionSlider" style="width:300px"></div>
<?php _e('Quality', CSPRITE_PLUGIN_NAME);?>: <input type="text" id="cSpriteJpgQuality" name="cSpriteJpgQuality" style="width:3em; font-weight:bold;" value="<?php echo get_option('cSpriteJpgQuality'); ?>"/>
<span class="setting-description"><?php _e('0 lowest - 100 highest (Default: 70)', CSPRITE_PLUGIN_NAME);?></span></td>
</tr>

<tr valign="top">
<th scope="row"><label for="cSpritePngCompression"><?php _e('PNG Compression', CSPRITE_PLUGIN_NAME);?></label></th>
<td>
<div id="pngCompressionSlider" style="width:300px"></div>
<?php echo _e('Compression', CSPRITE_PLUGIN_NAME);?>: <input type="text" id="cSpritePngCompression" name="cSpritePngCompression" style="width:3em; font-weight:bold;" value="<?php echo get_option('cSpritePngCompression'); ?>"/>
<span class="setting-description"><?php _e('0 lowest - 9 highest (Default: 9)', CSPRITE_PLUGIN_NAME);?></span></td>
</tr>

<tr valign="top">
<th scope="row"><label for="cSpriteForceNoPadding"><?php _e('Force no padding in image style', CSPRITE_PLUGIN_NAME);?></label></th>
<td>
<input type="checkbox" class="cSpriteCheckbox" name="cSpriteForceNoPadding" id="cSpriteForceNoPadding" <?php echo (get_option('cSpriteForceNoPadding'))?(' checked="checked" '):('');?> />
    <span class="setting-description"><?php _e('turning this <strong>On</strong> will add a <strong>padding:0 !important;</strong> style to each image class; this will prevent padding from exposing other parts of the sprite. (Default: on)', CSPRITE_PLUGIN_NAME);?></span>
    </td>
</tr>

<tr><td colspan="2"><span style="font-size:large;text-decoration:underline;"><?php _e('Include or exclude certain image types from processing',CSPRITE_PLUGIN_NAME);?></span></td></tr>

<tr valign="top">
<th scope="row"><label for="cSpriteProcessJpg"><?php _e('Process JPG images', CSPRITE_PLUGIN_NAME);?></label></th>
<td><input type="checkbox" class="cSpriteCheckbox" name="cSpriteProcessJpg" id="cSpriteProcessJpg" <?php echo (get_option('cSpriteProcessJpg'))?(' checked="checked" '):('');?> />
    </td>
</tr>

<tr valign="top">
<th scope="row"><label for="cSpriteProcessGif"><?php _e('Process GIF images', CSPRITE_PLUGIN_NAME);?></label></th>
<td><input type="checkbox" class="cSpriteCheckbox" name="cSpriteProcessGif" id="cSpriteProcessGif" <?php echo (get_option('cSpriteProcessGif'))?(' checked="checked" '):('');?> />
    </td>
</tr>
<tr valign="top">
<th scope="row"><label for="cSpriteProcessPng"><?php _e('Process PNG images', CSPRITE_PLUGIN_NAME);?></label></th>
<td><input type="checkbox" class="cSpriteCheckbox" name="cSpriteProcessPng" id="cSpriteProcessPng" <?php echo (get_option('cSpriteProcessPng'))?(' checked="checked" '):('');?> />
    <span class="setting-description"><?php _e('Due to the way PHP handles PNG files, the resulting sprite may be larger than the sum of the original file sizes', CSPRITE_PLUGIN_NAME);?></span>
    </td>
</tr>

<tr><td colspan="2"><span style="font-size:large;text-decoration:underline;"><?php _e('Cache Settings',CSPRITE_PLUGIN_NAME);?></span></td></tr>

<tr valign="top">
<th scope="row"><label for="cSpriteCacheTime"><?php _e('Cache Time in Minutes', CSPRITE_PLUGIN_NAME);?></label></th>
<td><input type="text" name="cSpriteCacheTime" id="cSpriteCacheTime" value="<?php echo get_option('cSpriteCacheTime'); ?>" style="width:6em;"/><span class="setting-description"><?php _e('set to 0 for no cache (Default: 20)', CSPRITE_PLUGIN_NAME);?></span></td>
</tr>

<tr valign="top">
<th scope="row"><label for="cSpriteClearCacheNow"><?php _e('Clear cSprite Cache', CSPRITE_PLUGIN_NAME);?></label></th>
<td><input type="checkbox" class="cSpriteCheckbox" name="cSpriteClearCacheNow" id="cSpriteClearCacheNow"/>
    <span class="setting-description"><?php _e('Immediately deletes all the files in the cSprite cache. May help with images not displaying properly.', CSPRITE_PLUGIN_NAME);?></span>
    </td>
</tr>

<tr><td colspan="2"><span style="font-size:large;text-decoration:underline;"><?php _e('SEO Options',CSPRITE_PLUGIN_NAME);?></span></td></tr>

<tr valign="top">
<th scope="row"><label for="cSpriteShowSize"><?php _e('Show &lt;img&gt; tag Width/Height Attributes', CSPRITE_PLUGIN_NAME);?></label></th>
<td><input type="checkbox" class="cSpriteCheckbox" name="cSpriteShowSize" id="cSpriteShowSize" <?php echo (get_option('cSpriteShowSize'))?(' checked="checked" '):('');?> /></td>
</tr>
<tr><td colspan="2"><span style="font-size:medium;"><?php _e('Possible Values for pattern string:',CSPRITE_PLUGIN_NAME);?></span></td></tr>

<tr><td colspan="2"><?php _e('
Pattern can be left empty if you do not want to modify your existing alt/title tags<br/>
<b>%category</b> - the (first) post category slug <strong><em>my-category-1</em></strong><br/>
<b>%spacedcategory</b> - the (first) post category slug with dashes replace by spaces <strong><em>my category 1</em></strong><br/>
<b>%categories</b> - all post category slugs separated by space <strong><em>my-category-1 my-category-2</em></strong><br/>
<b>%spacedcategories</b> - all post category slugs separated by space, slug dashes replaced by spaces <strong><em>my category 1 my category 2</em></strong><br/>
<b>%title</b> - the post title <strong><em>My Post Title!</em></strong><br/>
<b>%cleantitle</b> - the sanitized title of the post with special characters and spaces replaced by dash <strong><em>my-post-title</em></strong><br/>
<b>%spacedtitle</b> - the sanitized title of the post with special characters and dashes replaced by space <strong><em>my post title</em></strong><br/>
<b>%path</b> - the full path of the image <strong><em>http://mysite.com/images/my_pic.jpg</em></strong><br/>
<b>%name</b> - the name of the image including extension <strong><em>my_pic.jpg</em></strong><br/>
<b>%basename</b> - the name of the image without the extension <strong><em>my_pic</em></strong><br/>
<b>%cleanbasename</b> - the sanitized name of the image with special characters replaced by dash <strong><em>my-pic</em></strong><br/>
<b>%spacedbasename</b> - the sanitized name of the image with special characters and dashes replaced by space <strong><em>my pic</em></strong><br/>
You can add as many of these as you want and also add any abritrary text to your pattern string.<br/>
These settings will be valid for <strong>all</strong> images processed in the Wordpress Loop (even if they are not sprite-ed) and are not dependent on the above PNG, JPG and GIF settings.',CSPRITE_PLUGIN_NAME);?></td></tr>
<tr valign="top">
<th scope="row"><label for="cSpriteAltString"><?php _e('Alt text pattern', CSPRITE_PLUGIN_NAME);?></label></th>
<td><input type="text" name="cSpriteAltString" id="cSpriteAltString" value="<?php echo get_option('cSpriteAltString'); ?>"/>
<span class="setting-description"><?php _e('Blank by default, but I like to use <strong>%categories %title %spacedbasename</strong>', CSPRITE_PLUGIN_NAME);?></span>
</td>
</tr>

<tr valign="top">
<th scope="row"><label for="cSpriteReplaceAlt"><?php _e('Replace all ALT text', CSPRITE_PLUGIN_NAME);?></label></th>
<td><input type="checkbox" class="cSpriteCheckbox" name="cSpriteReplaceAlt" id="cSpriteReplaceAlt" <?php echo (get_option('cSpriteReplaceAlt'))?(' checked="checked" '):('');?> />
    <span class="setting-description"><?php _e('If <strong>On</strong>, will replace any existing ALT text with the pattern text. If <strong>Off</strong> will prepend any existing ALT text. (Default: <strong>Off</strong>)', CSPRITE_PLUGIN_NAME);?></span>
    </td>
</tr>

<tr valign="top">
<th scope="row"><label for="cSpriteTitleString"><?php _e('Title pattern', CSPRITE_PLUGIN_NAME);?></label></th>
<td><input type="text" name="cSpriteTitleString" id="cSpriteTitleString" value="<?php echo get_option('cSpriteTitleString'); ?>"/>
<span class="setting-description"><?php _e('Blank by default, but I like to use <strong>%spacedbasename</strong>', CSPRITE_PLUGIN_NAME);?></span>
</td>
</tr>

<tr valign="top">
<th scope="row"><label for="cSpriteReplaceTitle"><?php _e('Replace all Titles', CSPRITE_PLUGIN_NAME);?></label></th>
<td><input type="checkbox" class="cSpriteCheckbox" name="cSpriteReplaceTitle" id="cSpriteReplaceTitle" <?php echo (get_option('cSpriteReplaceTitle'))?(' checked="checked" '):('');?> />
    <span class="setting-description"><?php _e('If <strong>On</strong>, will replace any existing Title text with the pattern text. If <strong>Off</strong> will prepend any existing Title text. (Default: <strong>Off</strong>)', CSPRITE_PLUGIN_NAME);?></span>
    </td>
</tr>

</table>

<input type="hidden" name="action" value="update" />
<input type="hidden" name="page_options" value="cSpriteJpgQuality,cSpritePngCompression,cSpriteCacheTime,cSpriteForceNoPadding,cSpriteClearCacheNow,cSpriteProcessJpg,cSpriteProcessPng,cSpriteProcessGif,cSpriteShowSize,cSpriteAltString,cSpriteReplaceAlt,cSpriteTitleString,cSpriteReplaceTitle" />

<p class="submit">
<input type="submit" class="button-primary" value="<?php _e('Save Changes', CSPRITE_PLUGIN_NAME) ?>" />
</p>

</form>
<img id="cSpriteEmptyImage" src="<?php echo CSPRITE_TRANS_GIF_URL;?>" style="display:none;"/>
</div>
<?php 
} //end function 

function cSpriteOptionsPageChecksFailed(){
?>
<div class="wrap">
<h2><?php _e('cSprites for Wordpress', CSPRITE_PLUGIN_NAME);?></h2>
<h3><?php _e('The following problems occurred in your installation', CSPRITE_PLUGIN_NAME);?>:</h3>
<?php if(!cSpriteCheckCacheWritable()):?>
  <div style="background-color: rgb(255, 251, 204);" id="message" class="error"><p>The directory: <strong><em><?php echo CSPRITE_CACHE_DIR; ?></em></strong> was not writable. Please modify permissions.</p></div>
<?php endif; ?>
<?php if(!cSpriteCheckPhpVersion()): ?>
  <div style="background-color: rgb(255, 251, 204);" id="message" class="error"><p>You are currently running PHP <strong><?php echo phpversion(); ?></strong>. cSprites only supports PHP versions &gt; <strong>5.0.0</strong></p></div>
<?php endif; ?>
<?php if(!cSpriteCheckWordPressVersion()): ?>
  <div style="background-color: rgb(255, 251, 204);" id="message" class="error"><p>You are currently running an unsupported version of Wordpress. cSprites only supports Wordpress versions &gt;= <strong>2.7</strong></p></div>
<?php endif; ?>

<h4><?php _e('In order to run cSprites please fix these problems and refresh this screen.', CSPRITE_PLUGIN_NAME);?></h4>
<?php
}
?>