jQuery(document).ready(function($){
	$('#sort_table a').click(function(){
		var data = {
			action: 'check_click',
			fileUrl: $(this).attr('href'),
			fileName: $(this).parent().prev().html(),
			userClicked : $('#sort_table').attr('current_user'),
			tableId : $('#sort_table').attr('table_id')
		}
		$.post(ajaxurls, data, function(response) {
			console.log(response);
		});
	});
});
