$(function ()
{
    $(document).ready(function()
    {
        var input = $('#figure_videos_video').data('prototype');
        var nb = $('#figure_videos>.form-group>#figure_videos_video>.form-group').length + 1;
        input = input.replace(/__name__label__/g, 'Url vidéo '+nb);

        input = input.replace(/__name__/g, '');
        var html = $('#figure_videos>.form-group>#figure_videos_video').html();
        console.log(input);
        $('#figure_videos>.form-group>#figure_videos_video').html(html+input);
    })

    $('.plus').click(function()
    {
        var input = $('#figure_videos_video').data('prototype');
        var nb = $('#figure_videos>.form-group>#figure_videos_video>.form-group').length + 1;
        input = input.replace(/__name__label__/g, 'Url vidéo '+nb);

        input = input.replace(/__name__/g, '');
        // var html = $('#figure_videos>.form-group>#figure_videos_video').html();
        console.log(input);
        $('#figure_videos>.form-group>#figure_videos_video').append(input);
    });
});