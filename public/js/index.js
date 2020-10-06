$(function ()
{
	var elementPosition = $('#navigation').offset();

	$(window).scroll(function(){
		scroll();
	});

	$(window).ready(function(){
		scroll();
	});
	$( window ).resize(function() {
		scroll();
	});
	function scroll()
	{
		if($(window).scrollTop() > 979){
			$('#navigation').css('position','fixed');
			$('#navigation').css('bottom','10%');
			if ($(window).innerWidth() < 768) {
				$('#navigation').css('left','92%');
			} else {
				$('#navigation').css('left','95%');
			}
			$('#navigation').show();

		} else {
			$('#navigation').css('position','static');
			$('#navigation').hide();
		}
	}
});