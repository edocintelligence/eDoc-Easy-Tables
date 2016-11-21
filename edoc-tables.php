<?php
/*
Plugin Name: eDoc Easy Tables
Plugin URI: https://edocintelligence.com/
Description: Easy to use table tool. Create, update and reporting with intuitive manager interface
Author: eDoc Intelligence 
Version: 1.25
author URI: https://edocintelligence.com/
*/ 
if ( ! defined( 'ABSPATH' ) ) exit;
add_action('admin_menu', 'edoc_wpet_create_menu');
function edoc_wpet_create_menu() {

	$page_hook_suffix =  add_menu_page('eDoc Tables', 'eDoc Tables', 'read', "dp-table-admin", 'edoc_wpet_admin_tables_page',plugins_url( 'images/logoNewSquare.png' , __FILE__));
	$page_hook_suffixsub = add_submenu_page( "dp-table-admin", "Edit Tables", "Edit Tables", 'read', "edit-tables", "edoc_wpet_edit_tables_page" ,plugins_url( 'images/logoNewSquare.png' , __FILE__));

	add_action('admin_print_scripts-' . $page_hook_suffix, 'edoc_wpet_manager_scripts');
	add_action('admin_print_scripts-' . $page_hook_suffixsub, 'edoc_wpet_manager_scripts');
}

function edoc_wpet_manager_scripts() {
    if (isset($_GET['table-id']) && $_GET['page'] == 'edit-tables') {
        wp_enqueue_media();
		wp_enqueue_script('jquery-ui-core');;	
		wp_enqueue_script('jquery-ui-datepicker');
		wp_enqueue_style('jquery-ui-style', plugins_url('css/jquery-ui.min.css', __FILE__));
    }
	wp_enqueue_style( 'edoc-wpet-style-css', plugins_url('css/styles.css', __FILE__) );
	wp_enqueue_script( 'edoc-wpet-function', plugins_url('js/functions.js', __FILE__),array('jquery'), '1.0.0', true);	
	
}
	add_action('wp_head', 'edoc_wpet_pagging_scripts');
function edoc_wpet_pagging_scripts() {

	wp_enqueue_style( 'edoc-wpet-pagging-css', plugins_url('css/pagging.css', __FILE__) );
	
}
function edoc_wpet_admin_tables_page(){
	global $title,$wpdb;
	$current_user = wp_get_current_user();
	$loading = plugins_url('images/ajaxloading.gif', __FILE__);
	$table_name_admin = $wpdb->prefix ."edoc_tables";
	$table_id = @$_GET['table-id'];
	$table_name_edit = $wpdb->prefix ."edoc_table_".$table_id;
	
	$sql_load_table = "Select * FROM $table_name_admin";
	$sql_load_table = $wpdb->get_results($sql_load_table);
	$actions = @$_GET['actions'];
	$notification = '';

	if(isset($actions) && $actions="delete" &&  wp_verify_nonce($_GET['delete'], 'doing_delete')){
		$DELETE = "DELETE FROM $table_name_admin WHERE $table_name_admin.`id` = $table_id";
		$CHECK = $wpdb->query($DELETE);
		IF($CHECK){
			$notification =  "Table id ".$table_id." and data for this table are now deleted";
			$DELETE = "DROP TABLE $table_name_edit";
			$CHECK = $wpdb->query($DELETE);
			$notification =  '<div id="message" class="updated notice is-dismissible"><p>'.$notification.'</p><button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button></div>';
		}	
	}

	$show_panel  = edoc_wpet_load_panel();
	$home = home_url();
	$current_user_roles =$current_user->roles;

	if(in_array('administrator', $current_user_roles)){	
	echo <<<EOT
		<div class="edoc_wpet_wrapper">
			<h1>$title  - {$current_user->display_name}</h1>
			$notification
			<div id="wpet_main_body">
				<div class='content_table'>
					<!--<button id="create_new_table">Create new table</button>-->
					<div class="create_panel">
						<h3>Create New Table</h2>
						<div class='right_session'>
							<div class='haf_div'>
								<label><input type='checkbox' name='checkboxone' id='addtion_one_checkbox' value='yes'>Add Table Admin</label>
								<label><input type='text' value='' name='value_one' id='value_one'></label>
							</div>
							<div class='haf_div'>
								<label><input type='checkbox' name='checkboxtwo' id='addtion_two_checkbox' value='yes'>Email Weekly CSV</label>
								<label><input type='email' value='' name='value_two' id='value_two'></label>
							</div>
						</div>
						<div class="top_panel">
							<input type="text" id="table_name_create" name="table_name_create" value="" placeholder="Table Name" />
						</div>
						<div class="main_panel">
							<div class="each_panel" id="first_panel">
								<p><input type="text" name="column_name" required class='column_name' value="" placeholder="Column Name"/></p>
								
								<p>Type : <select name="column_type" class="column_type">
									<option value="text">Text Input</option>
									<option value="date_picker">Date Picker</option>
									<option value="upload">Uploaded File</option>
								</select>
								</p>
								<p>Sort : <select name="column_sort" class="column_sort">
									<option value="no">No</option>
									<option value="yes">Yes</option>
								</select>
								</p>
								<p>Default Sort : <input type="checkbox" name="column_sort_default" class='column_sort_default' value="yes"/>
								</p>
							</div>
							<div class="add_panel">
								<button class="button button-primary" id="create_new_column">Create New Column</button>
							</div>						
						</div>
						<div class="bottom_panel">
							<input type="submit" id='create_table_layout' name="create_table" class="button button-primary" value="Save Table Layout" /><img src="$loading" id="ajaxloading" />
						</div>				
					</div>
				</div>
			</div>
			<div id="wpet_sidebar">
				<div class="wpet_sidebarTitle"><h3>Quick Assists</h3></div>
					<div class="wpet_sidebarHolder">
						<div class="wpet_sidebarVideo">
							<iframe width="100%" src="https://www.youtube.com/embed/NZn9xnue0Dk" frameborder="0" allowfullscreen></iframe>
						</div>
					<hr>
						<div class="wpet_sidebarLinks">
							<a href="#">Quick Help Guide</a>
							<a href="#">Help Forums</a>
							<a target="_blank" href="https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=care%40edocintelligence%2ecom&item_name=eDoc%20easy%20tables&item_number=Support%20Open%20Source&no_shipping=0&no_note=1&tax=0&currency_code=USD&lc=US&bn=PP%2dDonationsBF&charset=UTF%2d8">Donate</a>
						</div>
				</div>
			</div>
			<div id="wpet_response_group" class="reponse">
				$show_panel
			</div>
		</div>
EOT;
}else{
		echo '<div class="edoc_wpet_wrapper">';
				echo "<p>Do you have access level for this page? Try Again.</p>";
		echo '</div>';	
}
}
function edoc_wpet_edit_tables_page(){
	global $title,$wpdb;		
	echo "<h1>".$title."</h1>";
	if( edoc_wpet_load_panel() != ''){
		echo edoc_wpet_load_panel();
	}else{
		echo '<div class="edoc_wpet_wrapper">';
				echo '<p>You cannot edit a table, without first creating one.</p><br/>';
				echo '<a href="/wp-admin/admin.php?page=dp-table-admin">Click here to create one now</a>';
		echo '</div>';
	}
	$table_id = @$_GET['table-id'];
	$table_name_edit = $wpdb->prefix ."edoc_table_".$table_id;
	if(isset($_GET['row_id']) && wp_verify_nonce($_GET['delete'], 'doing_delete')){
		$ROW_ID = $_GET['row_id'];
		$DELETE = "DELETE FROM $table_name_edit WHERE $table_name_edit.`id` = $ROW_ID";
		$CHECK = $wpdb->query($DELETE);
		IF($CHECK){
			echo '<div id="message" class="updated notice is-dismissible"><p>Row '.$ROW_ID.' deleted</p><button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss notice.</span></button></div>';
		}
	}
	
	if(isset($_POST["save_edit"]) &&  isset( $_POST['edoc_wpet_save_edit'] )  && wp_verify_nonce( $_POST['edoc_wpet_save_edit'], 'edoc_wpet_save_edit_action' ) ){
		$checkboxone = sanitize_text_field( $_POST['checkboxone'] );
		$checkboxtwo = sanitize_text_field( $_POST['checkboxtwo'] );
		if($checkboxone != 'yes'){
			$checkboxone = 'no';
		}
		if($checkboxtwo != 'yes'){
			$checkboxtwo = 'no';
		}			
		$manager_table = array(
			'adminset' => $checkboxone,
			'value' => sanitize_text_field($_POST['value_one'])
		); 
		$email_table = array(
			'adminset' => $checkboxtwo,
			'value' => sanitize_email($_POST['value_two'])
		); 
		$wpdb->query( $wpdb->prepare( 
			"
				UPDATE ".$wpdb->prefix."edoc_tables
				SET `admin_table` = %s ,`email_weekly` = %s 
				WHERE  `id` = %d
			", 
			json_encode($manager_table), 
			json_encode($email_table) ,
			(int)$_GET['table-id'] 
		) );
		$wpdb->show_errors();
		
	}
	$loading = plugins_url('images/ajaxloading.gif', __FILE__);
	if(isset($_POST['add_row']) && isset( $_POST['edoc_wpet_save_edit'] )  && wp_verify_nonce( $_POST['edoc_wpet_save_edit'], 'edoc_wpet_save_edit_action' )){
		$data_row = $_POST;
		array_pop($data_row);
		array_pop($data_row);
		array_pop($data_row);
		$data_row['id'] = null;		
		$wpdb->insert($table_name_edit, $data_row);
		$table_insert_id = $wpdb->insert_id;
		if($table_insert_id){
			echo '<div id="message" class="updated notice is-dismissible"><p>Row id '.$table_insert_id.' added !</p><button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button></div>';
		}
	}
	if(isset($table_id) and $table_id != "") {
		$table_name_admin = $wpdb->prefix ."edoc_tables";
		$table_name_ad = $wpdb->prefix ."edoc_table_".$table_id;
		
		$sql = "SELECT * FROM  $table_name_admin WHERE id=$table_id";
		$sql_fist = $wpdb->get_results($sql);

		$sql_fist = $sql_fist[0];
		$load_tables = $sql_fist->table_data;
		$mana_tables = $sql_fist->admin_table;
		$mana_tables = json_decode($mana_tables);
		$current_user = wp_get_current_user();
		$userlogged = $current_user->user_login;
		$userroles = $current_user->roles;
		$list_set = explode(',', $mana_tables->value);

		if($mana_tables->adminset == 'yes' && !in_array($userlogged, $list_set) && !in_array('administrator', $userroles)){
			echo "<p style='color:Red;font-weight:bold'>You don't have permission manage this table</p>";
			return false;
		}			

		$checked = '';
		$checked2 = '';
		$admindata = $sql_fist->admin_table;
		$admindata  = json_decode($admindata);
		$adminchecked = $admindata->adminset;
		if($adminchecked == 'yes'){
			$checked = 'checked="checked"';
		}
		$email_weekly = $sql_fist->email_weekly;
		$email_weekly  = json_decode($email_weekly);	
		$email_weeklychecked = $email_weekly->adminset;
		if($email_weeklychecked == 'yes'){
			$checked2 = 'checked="checked"';
		}	
		$current_user = wp_get_current_user();
		$current_user_roles =$current_user->roles;
		echo '<div class="left_session">
				<form method="post">';
		if(in_array('administrator', $current_user_roles)){

			echo	'<div class="haf_div">
						<label><input type="checkbox" name="checkboxone" '.$checked.' id="addtion_one_checkbox" value="yes">Add Table Admin</label>
						<label><input type="text" value="'.$admindata->value.'" name="value_one" id="value_one"></label>
					</div>';
		}
			echo	'<div class="haf_div">
						<label><input type="checkbox" name="checkboxtwo" '.$checked2.' id="addtion_two_checkbox" value="yes">Email Weekly CSV</label>
						<label><input type="email" value="'.$email_weekly->value.'" name="value_two" id="value_two"></label>
					</div>
					<div class="haf_div">
					'.wp_nonce_field( 'edoc_wpet_save_edit_action', 'edoc_wpet_save_edit' ).'
						<label><input type="submit" value="save" name="save_edit"></label>
					</div>
					
				</form>					
		</div>';
		
		$load_tables = json_decode($load_tables);
		echo '<h2>Editing `'.$sql_fist->table_name."` Table</h2>";
		$sql_table_current = "SELECT * FROM  $table_name_ad";
		$sql_table_current = $wpdb->get_results($sql_table_current);
		echo "<div class='table_show'><form method='post' ><table>";
		echo "<tr>";					
		foreach($load_tables as $load_table){
			echo "<th>".$load_table[0]."</th>";		
		}
			echo "<th width='100px'><button class='button button-primary' id='add_new_row'>Add New Row</button></th>";
		echo "</tr>";		
		if(count($sql_table_current) ==0){
			echo "<tr id='nodata'><td colspan='".(count($load_tables)+1)."'>No data</td></tr>";
		}
		echo "<tr id='add_new'>";
		$dem = 0;
		foreach($load_tables as $load_table){
			$dem++;
			if($load_table[1] == 'upload'){
			$add = '<label for="upload_image">
				<input class="upload_image button_'.$dem.'_class" type="text" size="36" name="'.$load_table[4].'" value="http://" /> 
				<input id="button_'.$dem.'_class" class="button upload_image_button" type="button" value="Upload File" />
				<br />Enter a URL or upload file
			</label>';

			}else{
				$add="<input type='text' name='".$load_table[4]."' placeholder='".$load_table[0]."' class='".$load_table[1]."'>";
			}
			echo "<td>".$add."</td>";	
		}	
		echo "<td>";
		wp_nonce_field( 'edoc_wpet_save_edit_action', 'edoc_wpet_save_edit' );
		echo "<input type='submit' name='add_row' class='button button-primary' value='Save' id='submit_add_row' />
		</td></tr>";
	
		$sql = "SELECT * FROM  $table_name_edit";
		$table_LOAD = $wpdb->get_results($sql);
		foreach($table_LOAD as $table_content){
			echo "<tr>";
				
			for($i = 0; $i< count($load_tables) ; $i++){
				$colums = 'column_'.$i;
				if(!filter_var($table_content->$colums, FILTER_VALIDATE_URL))
				  {
					echo "<td>".$table_content->$colums."</td>";
				  }
				else
				  {

					echo "<td><a target='_blank' href='".wp_nonce_url($table_content->$colums,'doing_show','show_table')."'>click here</a></td>";
				  }
			}
			$delete_link = home_url()."/wp-admin/admin.php?page=edit-tables&table-id=".$table_id."&row_id=".$table_content->id;
			echo "<td><a href='".wp_nonce_url($delete_link, 'doing_delete', 'delete')."'>delete</a></td>";
			echo "</tr>";
		}
			
		echo "</table>						
			</form>
		</div>";
	}

}
register_activation_hook(__FILE__, 'on_activation');
function on_activation()
{
	if ( ! current_user_can( 'activate_plugins' ) )
		return;

	global $wpdb;
	$table_name = $wpdb->prefix . "edoc_tables";
	$sql = "CREATE TABLE $table_name (
	id mediumint(9) NOT NULL AUTO_INCREMENT,
	table_name text  NULL,
	table_data longtext  NULL,
	admin_table text  NULL,
	email_weekly text  NULL,	
	UNIQUE KEY id (id)
	);";
   require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
   dbDelta( $sql);

}
add_action( 'wp_ajax_add_table', 'edoc_wpet_add_table_callback' );
function edoc_wpet_add_table_callback(){
		$current_user = wp_get_current_user();
		$current_user_roles =$current_user->roles;
		global $wpdb;
		$admin_table	= $_POST['table_name'];
		$arrs			= $_POST['arrs'];
		if(!in_array('administrator', $current_user_roles)){
			echo '<div id="message" class="error warning is-dismissible">';
			echo "<p>Sorry ! You don't have permission to create a new table.</p>";
			echo '</div>';
			die;
		}
		if($admin_table == ""){
			echo '<div id="message" class="error warning is-dismissible">';
			echo "<p>Please complete all fields !</p>";
			echo '</div>';
			echo edoc_wpet_load_panel();
			die;
		}
		foreach ($arrs as $key => $value) {
			if($value[0] == ''){
				echo '<div id="message" class="error warning is-dismissible">';
				echo "<p>Please complete all fields !</p>";
				echo '</div>';
				echo edoc_wpet_load_panel();
				die;
			}

		}
		$checkboxone = sanitize_text_field( $_POST['checkboxone'] );
		$checkboxtwo = sanitize_text_field( $_POST['checkboxtwo'] );
		if($checkboxone != 'yes'){
			$checkboxone = 'no';
		}
		if($checkboxtwo != 'yes'){
			$checkboxtwo = 'no';
		}			
		$manager_table = array(
			'adminset' => $checkboxone,
			'value' => sanitize_text_field($_POST['value_one'])
		); 
		$email_table = array(
			'adminset' => $checkboxtwo,
			'value' => sanitize_email($_POST['value_two'])
		); 
		$sql_add = "";
		$insert_head = array();
		$insert_head['id'] = NULL;
		$check = -1;
		foreach($arrs as $key=>$attr){
			$check++;			
			$arrs[$check][4] = "column_".$key;
			$sql_add.= "column_".$key." text  NULL,";
			$insert_head["column_".$key] = $attr;
		}
		$arraysave = json_encode($arrs);
		$table_name_admin = $wpdb->prefix ."edoc_tables";
		$edoc_table = $wpdb->prefix .$admin_table;
		$wpdb->insert($table_name_admin, array('id' => NULL,'table_name' => $admin_table,'table_data' => $arraysave,'admin_table'=> json_encode($manager_table),'email_weekly'=>json_encode($email_table)));
		$table_name_id = $wpdb->insert_id;
		if($table_name_id){
			echo '<div id="message" class="updated notice is-dismissible">';
				
			echo "<p style='color:green;font-weight:bold'>Column id = ".$table_name_id."  has been saved".$table_name_admin."</p>";
			
			$edoc_table_sql = "CREATE TABLE ".$wpdb->prefix ."edoc_table_".$table_name_id." (id mediumint(9) NOT NULL AUTO_INCREMENT,$sql_add UNIQUE KEY id (id));";
			$edoc_table_check = $wpdb->query($edoc_table_sql);	
			if($edoc_table_check){
				echo "<p style='color:green;font-weight:bold'>Table ".$wpdb->prefix ."edoc_table_".$table_name_id." has been saved"."</p>";
			}
			$edoc_table_sql = "CREATE TABLE ".$wpdb->prefix ."edoc_checked_".$table_name_id." (id mediumint(9) NOT NULL AUTO_INCREMENT,  `date` datetime NOT NULL,  `doc_name` varchar(1024) NOT NULL,  `username` varchar(256) NOT NULL ,`firstname` varchar(256) NOT NULL,`lastname` varchar(256) NOT NULL, UNIQUE KEY id (id));";
			$edoc_table_check = $wpdb->query($edoc_table_sql);	

			if($edoc_table_check){
				echo "<p style='color:green;font-weight:bold'>Table ".$wpdb->prefix ."edoc_checked_".$table_name_id." has been saved"."</p>";
			}				
			echo '</div>';
		}else{
			echo '<div id="message" class="updated notice is-dismissible">';
			echo "<p style='color:Red;font-weight:bold'>Can not insert database !</p>";
			echo '<button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button></div>';
		}
		
		echo edoc_wpet_load_panel();
		die;
}
function edoc_wpet_load_panel(){
	global $wpdb;
	$table_name_admin = $wpdb->prefix ."edoc_tables";
	$sql_load_table = "Select * FROM $table_name_admin";
	$sql_load_table = $wpdb->get_results($sql_load_table);
	$current_user = wp_get_current_user();
	$current_user_login = $current_user->user_login;
	$count = $wpdb->num_rows;
	$panel = '<div class="table_show"><h2>Edit Other Tables</h2>';
	$panel.= "<div class='table_show'>";
	$panel.="<table class='wpetEditTables'>";
	$panel.="<tr style='background-color:purple;color:white;'><th>Table Name</th><th>Click to Edit</th><th>Shortcode</th><th>Remove Table</th></tr>";
	if(count($sql_load_table)  == 0){
		$panel.="<tr><td colspan='4'> data not found !</td></tr>";
	}
	foreach($sql_load_table as $sql_load_table_each){

		$admin_table = json_decode($sql_load_table_each->admin_table);

		if(in_array( 'administrator', (array) $current_user->roles )|| ($admin_table->adminset == "yes" && in_array($current_user_login, explode(',',$admin_table->value ))) ){


			$delete_link = home_url()."/wp-admin/admin.php?page=dp-table-admin&table-id=".$sql_load_table_each->id."&actions=delete";
			$panel.="<tr><td>".$sql_load_table_each->table_name."</td><td><a href='".wp_nonce_url(home_url()."/wp-admin/admin.php?page=edit-tables&table-id=".$sql_load_table_each->id,'doing_show','show_table')."' >Click Here</a></td><td>[EDOCTABLE id='".$sql_load_table_each->id."']</td><td><a href='".wp_nonce_url($delete_link, 'doing_delete', 'delete')."'>Delete</a></td></tr>";

		}
	}
	$panel.="</table>";
	$panel.="</div></div>";
	if($count > 0){
		return $panel;
	}else{
		return "";
	}
	
}
include "pagging_class.php";

add_action('wp_head','edoc_wpet_ajaxurl');
function edoc_wpet_ajaxurl() {
?>
<script type="text/javascript">
var ajaxurls = '<?php echo admin_url('admin-ajax.php'); ?>';
</script>
<?php
}

function edoc_wpet_func( $atts ) {	

	wp_enqueue_style( 'sort-style', plugins_url( '/css/sortstyle.css' , __FILE__ ));
	wp_enqueue_script( 'checked-click-function', plugins_url( '/js/checked_click.js' , __FILE__ ), array(), '1.0.0', true );	
	wp_enqueue_script( 'sort-function', plugins_url( '/js/jquery.tablesorter.min.js' , __FILE__ ), array(), '1.0.0', true );

	extract( shortcode_atts( array(
		'id' => ''
	), $atts ) );
	global  $wpdb;
	$table_id = $id;
	$current_user = wp_get_current_user();
	$userlogged = $current_user->user_login;
	if(!$userlogged){
		$userlogged = 'non_member';
	}
	if(isset($_GET['nonce']) && !wp_verify_nonce( $_GET['nonce'], 'doing_pagging' ) && isset($_GET['ipp'])){
		return;
	}
	$table_name_admin = $wpdb->prefix ."edoc_tables";
	$sql = "SELECT * FROM  $table_name_admin WHERE id=$table_id";
	$sql_fist = $wpdb->get_results($sql);
	if(count($sql_fist) > 0){
	$sql_fist = $sql_fist[0];
	$load_tables = $sql_fist->table_data;
	$table_name_ad = $wpdb->prefix ."edoc_table_".$table_id;
	$load_tables = json_decode($load_tables);
	$paged = (get_query_var('paged')) ? get_query_var('paged') : @$_GET["paged"];
	$perpages = @$_GET["ipp"];	
	if(!$perpages){$perpages = 10;};
	if($paged){
		$paged = $paged-1;
		$milits = ' LIMIT '.$paged*$perpages.' , '.$perpages;
	}else{
		$paged = 0;
		$milits = ' LIMIT '.$paged*$perpages.' , '.$perpages;
	}
	$sql_table_all = "SELECT * FROM  $table_name_ad";
	$sql_table_current = "SELECT * FROM  $table_name_ad "." ORDER BY column_0 DESC "." $milits";
	ob_start();
	$sql_table_current = $wpdb->get_results($sql_table_current);
	$sql_table_all = $wpdb->get_results($sql_table_all);
	global $wp;
	$pages = new Edoc_wpet_pagination;
	$pages->items_total = count($sql_table_all);
	$pages->current_url = get_permalink();
	$pages->mid_range = 5;
	$pages->paginate();	
	$showfrom = $paged*$perpages;
	echo "<br><span style='float:left;width:100%'>".$pages->display_items_per_page()."</span>";
	
	echo "<div class='table_show'><table table_id='".$table_id."' current_user='".$userlogged."' class='tablesorter' id='sort_table'>";
	echo "<thead><tr>";
	
	$scriptaddd1 = '';	$scriptaddd2 = '';
	foreach($load_tables as $key=>$load_table){
		$pos = strpos($load_table[0], 'Date');
		if($pos !== false){
			$scriptaddd1.=$key.':{ sorter: "shortDate" },';
		}		
		if($load_table[2] != 'yes'){
			$scriptaddd1.=$key.':{sorter: false},';
		}
		if($load_table[3] == 'true'){
			$scriptaddd2.='['.$key.',1],';
		}		
		echo "<th>".$load_table[0]."</th>";		
	}	
	$scriptaddd1 = substr($scriptaddd1,0,-1);
	$scriptaddd2 = substr($scriptaddd2,0,-1);
	echo "</tr></thead>";		
	if(count($sql_table_current) ==0){
		echo "<tr id='nodata'><td colspan='".count($load_tables)."'>No data</td></tr>";
	}else{
		foreach($sql_table_current as $table_content){
			echo "<tr>";
			for($i = 0; $i< count($load_tables) ; $i++){
				$colums = 'column_'.$i;
				if(!filter_var($table_content->$colums, FILTER_VALIDATE_URL))
				  {
					echo "<td>".$table_content->$colums."</td>";
				  }
				else
				  {
					echo "<td><a target='_blank' href='".$table_content->$colums."'>click here</a></td>";
				  }
				
			}
			echo "</tr>";
		}
	}		
	echo	"</table></div>";
	echo 	'<script type="text/javascript">
				jQuery(document).ready(function($){	
					$("#sort_table").tablesorter({sortList: ['.$scriptaddd2.'], headers: {'.$scriptaddd1.'}});
				});
			</script>';
	?>
		<div class="paging_function">			
			<ul class="list_page"><?php echo $pages->display_pages(); ?></ul>
			<p>showing <?php echo $showfrom;?> to <?php if(count($sql_table_all) < $showfrom+$perpages){echo count($sql_table_all);}else{echo $showfrom+$perpages;}?> of <?php echo count($sql_table_all);?> entries</p>
		</div>	
	
	<?php
	}else{
		echo "<b style='color:red'>Table does not exist</b>";
	}
	return ob_get_clean();
}
add_shortcode( 'EDOCTABLE', 'edoc_wpet_func' );

add_action( 'wp_ajax_nopriv_check_click', 'edoc_wpet_click_callback' );
add_action( 'wp_ajax_check_click', 'edoc_wpet_click_callback' );
function edoc_wpet_click_callback(){

	$fileUrl		= $_POST['fileUrl'];
	$fileUrl = explode('/', $fileUrl);
	$fileUrl =$fileUrl[count($fileUrl)-1];
	$fileName		= $_POST['fileName'];
	$userClicked	= $_POST['userClicked'];
	$tableId 		= $_POST['tableId'];
	if($userClicked == 'non_member'){
		die;
	}
	$user = get_user_by( 'login', $userClicked );
	global $wpdb;
	$check_table = $wpdb->prefix ."edoc_checked_".$tableId;
	$wpdb->insert($check_table, array('id' => NULL,'date' => date("Y-m-d H:i:s"),'doc_name' => $fileUrl,'username'=> $userClicked,'firstname'=> $user->first_name,'lastname'=> $user->last_name));
	$table_name_id = $wpdb->insert_id;	
	echo "complete add id = ".$table_name_id;
	die;

}

function edoc_wpet_file_creation($data,$file_name) {
	$upload_dir = wp_upload_dir();

	$dir = $upload_dir['basedir'].'/edoc_wpet/';
	if ( ! file_exists( $dir ) ) {
	    wp_mkdir_p( $dir );
	}
    $outstream = fopen($dir.$file_name, 'w');
    
	foreach ($data as $fields) {
		$newarray = array();
		foreach ($fields as $field) {
			array_push($newarray,' '.$field);	
		}
	    fputcsv($outstream, $newarray,';','"');
	}
    fclose($outstream);
}
function edoc_wpet_send_email(){

	global $wpdb;
	$table_name_admin = $wpdb->prefix ."edoc_tables";
	$table_name_checked = $wpdb->prefix ."edoc_checked_";
	$sql = "SELECT * FROM  ".$table_name_admin;
	$sql_fists = $wpdb->get_results($sql);
	foreach($sql_fists as $sql_fist){
		$email_weekly = $sql_fist->email_weekly;
		$email_weekly = json_decode($email_weekly);
		if($email_weekly->adminset == 'yes' && $email_weekly->value != ''){
			$id = $sql_fist->id;
			if($email_weekly->value != ''){
				$arrays_checked = array();
				$settitle_checked = Array (
					'id' => 'Id',
					'date' => 'Date',
					'Docname' => 'Docname',
					'username' => 'Username',
					'firstname' => 'Firstname',
					'lastname' => 'Lastname'
				);
				$upload_dir = wp_upload_dir();
				$dir = $upload_dir['basedir'].'/edoc_wpet';
				$file = $dir.'/check_list_'.$id.'.csv';

				array_push($arrays_checked,$settitle_checked);
				$table_name_checkeds = $table_name_checked.$id;
				$sql_table_current_checked = "SELECT * FROM  $table_name_checkeds ORDER BY `id` ASC";
				$sql_load_inner_checked = $wpdb->get_results($sql_table_current_checked);

				foreach($sql_load_inner_checked as $sql_load_inner_checked_each){
					$arraygot = get_object_vars($sql_load_inner_checked_each);
					array_push($arrays_checked,$arraygot);			
				}

				edoc_wpet_file_creation($arrays_checked,'check_list_'.$id.'.csv');
				$attachments = array( $file );
				$headers[] = 'From: eDoc Easy Tables - <'.get_option('admin_email').'>';
				$headers[] = 'Cc: wordpress.org'; 
				$interval = 7 * 24 * 60 * 60;
				$email = str_replace('@', "___", $email_weekly->value);
				if(!get_option($email)){
					update_option($email,time());
					wp_mail($email_weekly->value, 'eDoc Weekly Email send per 5 min (test mode)', 'Hello , this is list of users that downloaded file in your table!', $headers, $attachments );	
				}else{
					if(time() - (int)get_option($email) > (int)$interval ){
						update_option($email,time());
						wp_mail($email_weekly->value, 'eDoc Weekly Email send per 5 min (test mode)', 'Hello , this is list of users that downloaded file in your table!', $headers, $attachments );
					}
				}
			}
		}
	}	
}
add_action('init','edoc_wpet_send_email');

?>