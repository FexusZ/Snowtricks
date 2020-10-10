$(function()
{
	$(document).on('click', '.show-image', function()
	{
		$(this).hide();
		$('.hide-image').show();
		$('.show-responsive').show();
	});

	$(document).on('click', '.hide-image', function()
	{
		$(this).hide();
		$('.show-image').show();
		$('.show-responsive').hide();
	});
});