function show_loader()
{
	$(".page").html('<div id="loading"></div>');
}

function hide_loader()
{
	$("#loading").fadeOut('slow');
}

function show_users(page)
{
    
    show_loader();
    
    $.ajax({
		type: "GET",
		url: "./"+ page +"/",
		cache: false,
		success: function(html)
		{
			$(".page").html(html);
			hide_loader();
			handle_pages(page);
		}
	});
}

function handle_pages(page)
{
    $(".pagination li a").each(function() {
        $(this).click(function() {
            var page = $(this).attr('name');
            show_users(page);
        });
	});
}

$(document).ready(function() { 
    show_users(1, 2);
});