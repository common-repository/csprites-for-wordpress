=== cSprites - Speed Up Page Load Time with Dynamic Image Sprites ===
Contributors: amummey
Tags: csprites-for-wordpress, css, sprites, cSprites, images, content
Stable tag: 0.510
Requires at least: 2.7
Tested up to: 2.7.1
Donate link: http://mummey.org/csprites/

Automatically compress your images into a big sprite, decreasing web page load time. SEO features automatically generate rich ALT and TITLE tags.

== Description ==
The idea of combining images into a larger one and displaying this using CSS tricks has been around for awhile.

There are a couple big benefits to doing this: First it reduces the number of HTTP requests that the browser has to make, by combining say 5 images into 1 big image, the number of image requests has been reduced by 4. This can increase the effeciency of page loading and you can see gains in the speed at which your page is loaded.

cSprites will enable you to compress all of the images in your content into one big image, called a sprite and display this in parts. Thus the user will only have to make 1 request rather than many for all your content images.

cSprites now has some nice SEO behavior, you can let it automatically generate ALT and TITLE tags based on things like Post title, categories, image name, etc.

cSprites has another possible benefit or problem (depending on how you look at it), that is that people will no longer be able to directly copy/download your images without first going into the CSS finding the sprite-ed image file, then going into photoshop and cropping out the image that they want. You can still link the sprite-ed images to the originals, because cSprites doesn't delete your original images.

cSprites is compatible with lightbox plugins.
  
  **DEMO** on my home page: [View Demo](http://www.mummey.org/csprites/csprites-demo/ "cSprites for Wordpress demo") 
  
  For more information on this cSprites plugin : [cSprites for Wordpress](http://www.mummey.org/csprites/ "cSprites for Wordpress")
  
  For more information on the cSprites core API please see my blog post: [cSprites info](http://www.mummey.org/2008/12/csprites-a-dynamic-css-sprite-generator-in-php5/ "cSprites - A Dynamic CSS Sprite Generator in PHP5")
  
  **NOTE**
  
  By default I have disabled the sprite-ing of PNG images. PHP does not have a great PNG compression algorithm and therefore the image size of a sprite-ed PNG **could** actually be larger than the sum of the individual PNG images. You may still turn this option On in the settings if you like. I am currently investigating adding support for some great command-line PNG compression utilities.

== Installation ==

This section describes how to install the plugin and get it working.

1. You MUST be running PHP >= 5.0 and have GD library
1. Make sure that you have this line in your `php.ini` file: `allow_url_fopen = ON`
1. Upload `plugin-name.php` to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress 

* **How to use**
* If you don't want an image to be sprite-ed you can add `rel="no-sprite"` to the image tag
* You can now specify a height and width in the image tag and the image will be resized before sprite-ing
* If you want to do a resize and crop of the image (maintaining aspect ratio) add a `rel="crop"` tag to your image
* If you want a fixed width or fixed height image, just add either a height or width and cSprites will resize and maintain aspect ratio

== Frequently Asked Questions ==

= Images are not displayed at all =

Try clearing the cSprite Cache, there is an option for this in the settings. If you are using WP-Super Cache, try deleting the cache and refreshing the page. It is recommended that you clear both caches when you change the cSprite options.

= Images in my sidebars are not being sprite-ed =

cSprites only works on your content images, that is, any images that are contained in the main Wordpress loop.

= I see some parts of other pictures in the image =

You MAY NOT use padding on the image if you would like to use cSprites, this is because the images are tightly packed and when you use padding parts of other images may show through. Wrap the img tag in a div or other element and add some padding to that. Also make sure that the **force no padding** option is set to **On** in the options screen.

= I am getting PHP errors =

You MUST be running PHP >= 5.0 and have GD library

= I am running PHP 5.0 and I still get PHP errors =

Make sure that you have this line in your php.ini file: `allow_url_fopen = ON`

= Still not working =

Make sure that the *cache* directory in the csprites-for-wordpress plugin directory is writeable

== Screenshots ==

1. A sample dynamically generated sprite
2. cSprite Options Screen


== Requirements ==

1. Must be running PHP >= 5.0
1. GD Library for PHP installed
1. php.ini must have this line in it: `allow_url_fopen = ON`

== Notes ==
cSprites is still quite experimental so please use at your own discretion. Please post any bugs found.

== Release Notes ==
* **v0.510**
* Fixed bug where cSprites was conflicting with another plugin that uses HTML Simple Dom
* **v0.509**
* Fixed bug with cSprites trying to do its thing in feeds. No more...
* Added Italian Language File
* **v0.508**
* Ran a check to see if we are in a feed do not run cSprites
* Added new SEO features allowing you to have nice patterns for your alt and title text
* Added Settings link on Plugin actions link
* **v0.507**
* Fixed a problem with wp_footer hook, some templates don't use it, so switched to get_footer
* **v0.505**
* Added new Simple Dom extension for better HTML parsing
* cSprites now respects img height and width variables, and will resize images appropriately
* cSprites can now crop resized images using a rel="crop" in your img tag
* can ignore images with a rel="no-sprite" tag
* **v0.504**
* updated the javascript in the admin interface
* added some more security checks in the code
* cleaned up a lot of code to do things more wordpress way
* **v0.502**
* Bug fixes, new jUI scripts were messing with other admin stuff
* **v0.5**
* Added jQuery UI elements to the admin options page
* **v0.495**
* Added preprocessor cache function that dramatically improves performance
* Fixed bug with regex not match commas (,) in urls
* Fixed bug with cache time of 0 not working
* Added WP version check
* **v0.482**
* Added options to include/exclude certain image types from processing
* Fixed the PNG compression (was not working before)
* **v0.482**
* fixed regex not matching single quoted html attributes
* **v0.481**
* fails much more gracefully, if cSprites requirements are not met
* **v0.48**
* fixed support for relative image urls
* **v0.47**
* added support for relative image urls
* added new option in administrator to clear the cache manually
* added a reset function to the cSprite core, might be applicable in the future
* **v0.46**
* Hook architecture was still broken. Now shouldn't mess up you post orders.
* **v0.45**
* Did a full rewrite of the hook architecture, I got it totally wrong on previous versions. Now scans the full WP Loop
and processes it prior to displaying the content. In previous versions I was using *the_posts* hook and this was failing
because many other plugins will also call this and we only want to scan the content. Now we use the *loop_start* hook.
* **v0.43**
* Fixed cache, wasn't working at all for images. Now is MUCH faster than before. 