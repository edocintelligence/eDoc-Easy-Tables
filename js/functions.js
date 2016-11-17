jQuery(document).ready(function($){
	
	$('#create_new_column').click(function(){
		$('.add_panel').before("<div class='each_panel'>"+$('#first_panel').html()+"</div>");
		//$('.just_new')
	});
	$('.bottom_panel input').click(function(){
		$(this).addClass('posting');
		$('#ajaxloading').show();
		var table_name = $('#table_name_create').val();
		var arrss = new Array();
		$('.each_panel').each(function(){	
			var arr  = new Array();
			arr[0] = $(this).find('.column_name').val();
			arr[1] = $(this).find('.column_type').val();
			arr[2] = $(this).find('.column_sort').val();
			if($(this).find('.column_sort_default').prop('checked')){
				arr[3] = 'true';
			}else{
				arr[3] = 'false';
			}			
			arrss.push(arr);
		});	
		if($('#addtion_one_checkbox').is(':checked')){
			var check1 = 'yes';
		}else{
			var check1 = 'no';
		}	
		if($('#addtion_two_checkbox').is(':checked')){
			var check2 = 'yes';
		}else{
			var check2 = 'no';
		}
		var data = {
			action		: 'add_table',
			table_name	: table_name,
			arrs		: arrss,
			checkboxone : check1,
			value_one    : $('#value_one').val(),
			checkboxtwo : check2,
			value_two    : $('#value_two').val()
		};
		//alert(JSON.stringify(data));
		//return false;
		$.post(ajaxurl, data, function(response) {
			$('.reponse').html(response);
			$('#ajaxloading').hide();
			$('.bottom_panel input').removeClass('posting');
			//location.reload();
		});
		
	});
	
	$('#add_new_row').click(function(){
		$('#ajaxloading').show();
		$('#add_new').show();
		$('#submit_add_row').show();
		$('#nodata').hide();
		return false;
	});
	
	var custom_uploader;
 
 
    $('tr td .upload_image_button').click(function() {
 
        //e.preventDefault();
	$('.insert_here_ok').removeClass('insert_here_ok');
 	$(this).prev().addClass('insert_here_ok');
	
        //If the uploader object has already been created, reopen the dialog
        if (custom_uploader) {
            custom_uploader.open();
            return;
        }
 
        //Extend the wp.media object
        custom_uploader = wp.media.frames.file_frame = wp.media({
            title: 'Choose Image',
            button: {
                text: 'Choose Image'
            },
            multiple: false
        });
 
        //When a file is selected, grab the URL and set it as the text field's value
        custom_uploader.on('select', function() {
            attachment = custom_uploader.state().get('selection').first().toJSON();
            $('.insert_here_ok').val(attachment.url);
        });
 
        //Open the uploader dialog
        custom_uploader.open();
 	return false;
    });
    if($(".date_picker")[0]){
		$('.date_picker').datepicker({ dateFormat: 'yy-mm-dd' });
	}
    
	 

})