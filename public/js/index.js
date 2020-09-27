$(function ()
{
	var elementPosition = $('#navigation').offset();

	$(window).scroll(function(){
		console.log($(window).scrollTop(), elementPosition)
			if($(window).scrollTop() > 979){
				$('#navigation').css('position','fixed');
				$('#navigation').css('bottom','5%');
				$('#navigation').css('left','95%');
				$('#navigation').show();

			} else {
				$('#navigation').css('position','static');
				$('#navigation').hide();
			}    
	});
});