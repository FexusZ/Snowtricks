$(function ()
{
	$(document).on('click', '#loadMore', function(e)
	{
		$(this).hide();
		e.preventDefault();
		$('.hide').show();
	})
});