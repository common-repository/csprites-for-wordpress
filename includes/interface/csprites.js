/*Author: Adrian Mummey*/
try{
	jQuery(document).ready(function() {
				jQuery('input[type=checkbox].cSpriteCheckbox').checkbox({
  cls:'jquery-checkbox',
  empty: jQuery('#cSpriteEmptyImage').attr('src')
});
				jQuery('a.code').each(function() {
					jQuery(this).click(function(){
						eval(jQuery(this).text());
						return false;
					})
				});
      
      jQuery('#jpgCompressionSlider').slider({
			 value: jQuery('#cSpriteJpgQuality').val(),
			 min: 0,
			 max: 100,
			 step: 1,
			 slide: function(event, ui) {
				jQuery('#cSpriteJpgQuality').val(ui.value);
			 }
		});
		jQuery('#cSpriteJpgQuality').change(function(obj) {
           currentVal = parseInt(jQuery(this).val());
            if(currentVal > 100){
              currentVal = 100;
            }
            else if(currentVal < 0){
              currentVal = 0;
            }
            else if(isNaN(currentVal)){
              currentVal = 0;
            }
            jQuery(this).val(currentVal);
            jQuery('#jpgCompressionSlider').slider('moveTo', currentVal, null, false);
       }); 
   
		jQuery('#jpgCompressionSlider').slider('moveTo', jQuery('#cSpriteJpgQuality').val(), null, false);
		
		jQuery('#pngCompressionSlider').slider({
			 value: jQuery('#cSpritePngCompression').val(),
			 min: 0,
			 max: 9,
			 step: 1,
			 slide: function(event, ui) {
				jQuery('#cSpritePngCompression').val(ui.value);
			 }
		});
		jQuery('#cSpritePngCompression').change(function(obj) {
           currentVal = parseInt(jQuery(this).val());
            if(currentVal > 9){
              currentVal = 9;
            }
            else if(currentVal < 0){
              currentVal = 0;
            }
            else if(isNaN(currentVal)){
              currentVal = 0;
            }
            jQuery(this).val(currentVal);
            jQuery('#pngCompressionSlider').slider('moveTo', currentVal, null, false);
       }); 
		
		jQuery('#pngCompressionSlider').slider('moveTo', jQuery('#cSpritePngCompression').val() , null, false);
  });
  
}
catch(err) {}