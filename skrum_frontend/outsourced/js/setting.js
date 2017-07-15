/************************************************************************************************************

smooth scroll　外部ファイル無し
 
************************************************************************************************************/

function smoothscroll(){
		$("a.scroll[href*=#]").click(function(){
			$(this).scrollTo(600);
			return false;
		});
}
jQuery.fn.extend({
	scrollTo : function(speed, easing){
		if(!$(this)[0].hash || $(this)[0].hash == "#"){
			return false;
		}
		return this.each(function() {
			var targetOffset = $($(this)[0].hash).offset().top;
			$('html,body').animate({scrollTop: targetOffset}, speed, easing);
		});
	}
});
$(document).ready(smoothscroll);


