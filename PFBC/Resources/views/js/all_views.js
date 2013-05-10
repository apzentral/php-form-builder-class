//===== Common JS scripts ====//
jQuery(document).ready(function($){
	$("a[data-toggle=popover]").popover({html:true, trigger:"manual"}).click(function(e){e.preventDefault();})
	.mouseover(function (e) {
		$(this).popover('show');
    }).mouseout(function (e) {
		$(this).next().hide();
    });
});
