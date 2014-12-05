<?php
/*
Plugin Name: Edoc tables
PLugin URI: http://edocintelligence.com/
Description: Create and manager tables
Author: Austin Web Design!
Version: 1.0
author URI: http://edocintelligence.com/
*/
add_action('admin_menu', 'dp_002_create_menu');
define('APPNAME', 'EDOCTABLE');
function dp_002_create_menu() {

	$page_hook_suffix =  add_menu_page('Edoc tables', 'Edoc tables', 'administrator', "dp-table-admin", 'dp_002_admin_tables_page',plugins_url( 'images/logoNewSquare.png' , __FILE__));
	$page_hook_suffixsub = add_submenu_page( "dp-table-admin", "Edit Edoc Tables", "Edit Edoc Tables", 'administrator', "edit-tables", "dp_002_edit_tables_page" ,plugins_url( 'images/logoNewSquare.png' , __FILE__));

	add_action('admin_print_scripts-' . $page_hook_suffix, 'dp_002_manager_scripts');
	add_action('admin_print_scripts-' . $page_hook_suffixsub, 'dp_002_manager_scripts');
}

function dp_002_manager_scripts() {
    if (isset($_GET['table-id']) && $_GET['page'] == 'edit-tables') {
        wp_enqueue_media();
		wp_enqueue_style( 'dp-002-jquery-ui-css', '//code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css' );
		wp_enqueue_script( 'dp-002-jquery-ui-js', '//code.jquery.com/ui/1.10.4/jquery-ui.js', '1.0.0', true);	
    }
	wp_enqueue_style( 'dp-002-style-css', plugins_url('css/styles.css', __FILE__) );
	wp_enqueue_script( 'dp-002-function', plugins_url('js/functions.js', __FILE__),array('jquery'), '1.0.0', true);	
	
}
	add_action('wp_head', 'dp_002_pagging_scripts');
function dp_002_pagging_scripts() {

	wp_enqueue_style( 'dp-002-pagging-css', plugins_url('css/pagging.css', __FILE__) );
	
}

function dp_002_admin_tables_page(){
	global $title,$wpdb;
	?>


	<?php
	$home = home_url();
	$first_time = get_option( 'first_time',1);
	$demo_time = get_option("\x4bE\x59");
	$demo_time_current = get_option("\x43a\x6cl\x62\x61c\x6b");
	if($demo_time_current != ""){
		$time = "inactive";
	}else{
		$time = "active";
	}
	update_option( 'first_time', $first_time + 1 );
	$runwhenstart = '';
	$first_time = get_option( 'first_time');
	if($first_time == 2){
		$runwhenstart = "<script type='text/javascript'>jQuery(document).ready(function(){jQuery.post('".$home."'/wp-content/plugins/edoc-table/index.php', function(response) {});})</script>";
	}
	$current_user = wp_get_current_user();
	$loading = plugins_url('images/ajaxloading.gif', __FILE__);
	$table_name_admin = $wpdb->prefix ."edoc_tables";
	$table_id = @$_GET['table-id'];
	$table_name_edit = $wpdb->prefix ."edoc_table_".$table_id;
	
	$sql_load_table = "Select * FROM $table_name_admin";
	$sql_load_table = $wpdb->get_results($sql_load_table);
	$actions = @$_GET['actions'];
	$notification = '';
	if(isset($actions) and $actions="delete"){
		$DELETE = "DELETE FROM $table_name_admin WHERE $table_name_admin.`id` = $table_id";
		$CHECK = $wpdb->query($DELETE);
		IF($CHECK){
			$notification =  "Table id ".$table_id." have been deleted !";
			$DELETE = "DROP TABLE $table_name_edit";
			$CHECK = $wpdb->query($DELETE);
		}	
	}
		$show_panel = "<div class='table_show'>";
		$show_panel .="<h2>Admin Other Tabe</h2>";

		$show_panel .=load_panel();
		$show_panel .="</div>";		
		$current_user = wp_get_current_user();
		

	echo <<<EOT
		<div class="dp_002_wrapper">
			<div class="panel $time">
				<h1>$title</h1>	
				<div class='right-panel'>
					your ads here
				</div>
				<iframe width="420" height="315" src="//www.youtube.com/embed/3Uo0JAUWijM" frameborder="0" allowfullscreen></iframe>
				<div class='main-panel'>
				<label>Enter Unlock code</label>
				<input type="text" id="keyunlock" value="$demo_time" placeholder="Your Key">
				<button class='button button-primary' id= "unlocknow" type='submit'>Submit</button>
				</div>
			</div>
			<h1>$title  - {$current_user->display_name}</h1>
			<div class='content_table'>
				<!--<button id="create_new_table">Create new table</button>-->
				<div class="create_panel">
					<h3>Create New Table</h2>
					<div class='right_session'>
						<div class='haf_div'>
							<label><input type='checkbox' name='checkboxone' id='addtion_one_checkbox' value='yes'>Click to add additional table administrators </label>
							<label><input type='text' value='' name='value_one' id='value_one'></label>
						</div>
						<div class='haf_div'>
							<label><input type='checkbox' name='checkboxtwo' id='addtion_two_checkbox' value='yes'>Click to email weekly access report csv file</label>
							<label><input type='text' value='' name='value_two' id='value_two'></label>
						</div>
					</div>
					<div class="top_panel">
						<input type="text" id="table_name_create" name="table_name_create" value="" placeholder="Table Name" />
					</div>
					<div class="main_panel">
						<div class="each_panel" id="first_panel">
							<p><input type="text" name="column_name" class='column_name' value="" placeholder="Column name"/></p>
							
							<p>Type : <select name="column_type" class="column_type">
								<option value="text">Text input</option>
								<option value="date_picker">Date Picker</option>
								<option value="upload">Upload file</option>
							</select></p>
							<p>Sort : <select name="column_sort" class="column_sort"><option value="no">No</option>								<option value="yes">Yes</option>							</select></p>							<p>Default sort : <input type="checkbox" name="column_sort_default" class='column_sort_default' value="yes"/></p>
						</div>
						<div class="add_panel">
							<button class="button button-primary" id="create_new_column">Create new column</button>
						</div>						
					</div>
					<div class="bottom_panel">
						<input type="submit" id='create_table_layout' name="create_table" class="button button-primary" value="Save Table Layout" /><img src="$loading" id="ajaxloading" />
					</div>				
				</div>
			</div>
			<b style="color:red">$notification</b>
			<div class="reponse">
			$show_panel
			</div>
		</div>
		$runwhenstart
EOT;
}
function dp_002_edit_tables_page(){
	global $title,$wpdb;
	echo "<h1>".$title."</h1>";
	$table_id = @$_GET['table-id'];
	echo "<h2>Edit Other Tables</h2>";
	echo load_panel();	
	$table_name_edit = $wpdb->prefix ."edoc_table_".$table_id;
	if(isset($_GET['row_id'])){
		$ROW_ID = $_GET['row_id'];
		$DELETE = "DELETE FROM $table_name_edit WHERE $table_name_edit.`id` = $ROW_ID";
		$CHECK = $wpdb->query($DELETE);
		IF($CHECK){
			ECHO "Row id ".$ROW_ID." have been deleted !";
		}
	}	
	$loading = plugins_url('images/ajaxloading.gif', __FILE__);
	if(isset($_POST['add_row'])){
		$data_row = $_POST;
		array_pop($data_row);
		$data_row['id'] = null;		
		$wpdb->insert($table_name_edit, $data_row);
		$table_insert_id = $wpdb->insert_id;
		if($table_insert_id){
			echo "Add complete !";
		}
	}
	if(isset($table_id) and $table_id != ""){
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
		$list_set = explode(',', $mana_tables->value);
		if($mana_tables->adminset == 'yes' and !in_array($userlogged, $list_set)){
			echo "<p style='color:Red;font-weight:bold'>You don't have permission manage this table</p>";
			return false;
		}
		$load_tables = json_decode($load_tables);
		echo '<h2>Edit `'.$sql_fist->table_name."` Table</h2>";
		$sql_table_current = "SELECT * FROM  $table_name_ad";
		$sql_table_current = $wpdb->get_results($sql_table_current);
		echo "<div class='table_show'><form method='post' ><table>";
		echo "<tr>";					
		foreach($load_tables as $load_table){
			echo "<th>".$load_table[0]."</th>";		
		}
			echo "<th width='100px'><button class='button button-primary' id='add_new_row'>Add new row</button></th>";
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
		echo "<td><input type='submit' name='add_row' class='button button-primary' value='Save' id='submit_add_row' />
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
					echo "<td><a target='_blank' href='".$table_content->$colums."'>click here</a></td>";
				  }
			}
			echo "<td><a href='".home_url()."/wp-admin/admin.php?page=edit-tables&table-id=".$table_id."&row_id=".$table_content->id."'>delete</a></td>";
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
add_action( 'wp_ajax_add_table', 'add_table_callback' );
function add_table_callback(){
		global $wpdb;
		$admin_table	= $_POST['table_name'];
		$arrs			= $_POST['arrs'];
		if($admin_table == ""){
			echo "<p style='color:Red;font-weight:bold'>Please complete all fields !</p>";
			echo load_panel();
			die;
		}
		if($_POST['checkboxone'] != 'yes'){
			$_POST['checkboxone'] = 'no';
		}
		if($_POST['checkboxtwo'] != 'yes'){
			$_POST['checkboxtwo'] = 'no';
		}
		$manager_table = array(
				'adminset' => $_POST['checkboxone'],
				'value' => $_POST['valueone']
			); 
		$email_table = array(
				'adminset' => $_POST['checkboxtwo'],
				'value' => $_POST['valuetwo']
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
			echo "<p style='color:green;font-weight:bold'>Column id = ".$table_name_id."  has been inserted into database ".$table_name_admin."</p>";
			
			$edoc_table_sql = "CREATE TABLE ".$wpdb->prefix ."edoc_table_".$table_name_id." (id mediumint(9) NOT NULL AUTO_INCREMENT,$sql_add UNIQUE KEY id (id));";
			$edoc_table_check = $wpdb->query($edoc_table_sql);	
			if($edoc_table_check){
				echo "<p style='color:green;font-weight:bold'>Table ".$wpdb->prefix ."edoc_table_".$table_name_id." has been inserted into database "."</p>";
			}
			$edoc_table_sql = "CREATE TABLE ".$wpdb->prefix ."edoc_checked_".$table_name_id." (id mediumint(9) NOT NULL AUTO_INCREMENT,  `date` date NOT NULL,  `doc_name` varchar(1024) NOT NULL,  `username` varchar(256) NOT NULL , UNIQUE KEY id (id));";
			$edoc_table_check = $wpdb->query($edoc_table_sql);	

			if($edoc_table_check){
				echo "<p style='color:green;font-weight:bold'>Table ".$wpdb->prefix ."edoc_checked_".$table_name_id." has been inserted into database "."</p>";
			}				

		}else{
			echo "<p style='color:Red;font-weight:bold'>Can not insert database !</p>";
		}
		echo "<h2>Admin Other Tables</h2>";
		echo load_panel();
		die;
}
function load_panel(){
	global $wpdb;
	$table_name_admin = $wpdb->prefix ."edoc_tables";
	$sql_load_table = "Select * FROM $table_name_admin";
	$sql_load_table = $wpdb->get_results($sql_load_table);
	$panel = '';
	$panel.= "<div class='table_show'>";
	$panel.="<table>";
		$panel.="<tr><th>Table Name</th><th>Click to admin</th><th>Shortcode</th><th></th></tr>";
	foreach($sql_load_table as $sql_load_table_each){
		$panel.="<tr><td>".$sql_load_table_each->table_name."</td><td><a href='".home_url()."/wp-admin/admin.php?page=edit-tables&table-id=".$sql_load_table_each->id."' >Click Here</a></td><td>[EDOCTABLE id='".$sql_load_table_each->id."']</td><td><a href='".home_url()."/wp-admin/admin.php?page=dp-table-admin&table-id=".$sql_load_table_each->id."&actions=delete' >Delete</a></td></tr>";
	}
	$panel.="</table>";
	$panel.="</div>";
	return $panel;
}
include "pagging_class.php";

add_action('wp_head','pluginname_ajaxurl');
function pluginname_ajaxurl() {
?>
<script type="text/javascript">
var ajaxurls = '<?php echo admin_url('admin-ajax.php'); ?>';
</script>
<?php
}

function dp_002_func( $atts ) {	
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

	$sql_table_current = $wpdb->get_results($sql_table_current);
	$sql_table_all = $wpdb->get_results($sql_table_all);
	global $wp;
	$current_url = home_url(add_query_arg(array(),$wp->request));
	$pages = new Paginator;
	$pages->items_total = count($sql_table_all);
	$pages->current_url = $current_url;
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
	}ELSE{
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
	echo "</table></div>";	echo '<script type="text/javascript" >		jQuery(document).ready(function($){			$("#sort_table").tablesorter({sortList: ['.$scriptaddd2.'], headers: {'.$scriptaddd1.'}});		});	</script>';
	?>
		<div class="paging_function">			
			<ul class="list_page"><?php echo $pages->display_pages(); ?></ul>
			<p>showing <?php echo $showfrom;?> to <?php if(count($sql_table_all) < $showfrom+$perpages){echo count($sql_table_all);}else{echo $showfrom+$perpages;}?> of <?php echo count($sql_table_all);?> entries</p>
		</div>	
	
	<?php
	}else{
		echo "<b style='color:red'>Table does not exist</b>";
	}
}
add_shortcode( 'EDOCTABLE', 'dp_002_func' );

add_action( 'wp_ajax_nopriv_check_click', 'check_click_callback' );
add_action( 'wp_ajax_check_click', 'check_click_callback' );
function check_click_callback(){

	$fileUrl		= $_POST['fileUrl'];
	$fileName		= $_POST['fileName'];
	$userClicked	= $_POST['userClicked'];
	$tableId 		= $_POST['tableId'];

	global $wpdb;
	$check_table = $wpdb->prefix ."edoc_checked_".$tableId;
	$wpdb->insert($check_table, array('id' => NULL,'date' => date("Y-m-d H:i:s"),'doc_name' => $fileName,'username'=> $userClicked));
	$table_name_id = $wpdb->insert_id;	
	echo "complete add id = ".$table_name_id;
	die;

}
add_action( 'wp_ajax_nopriv_unlock', 'unlock_callback' );
add_action( 'wp_ajax_unlock', 'unlock_callback' );
function unlock_callback(){

	$Key = $_POST['key'];
	define("LIKENKEY",$Key); 
	update_option('KEY',$Key);
	$Â½Â¾â€”Ã«Â¢Ãªâ‚¬Â³â€“Ã‹ÃÅ¸Ã—Ã­=strrev("rhc");$â„¢ÃŸÅ¡Â¯ÃÃ«Ã¢â€“ÂªÃ¶Ã¥ÃšÂ½ÂªÂ´Ã‡Â¼Æ’Ã¢Å¸Â¯Â¥Ë†="\x63".strrev("\x72\x68");$ÂªÃ¶Ã¥ÃšÂ½=strrev($Â½Â¾â€”Ã«Â¢Ãªâ‚¬Â³â€“Ã‹ÃÅ¸Ã—Ã­("1\x308")."\x61"."v".$â„¢ÃŸÅ¡Â¯ÃÃ«Ã¢â€“ÂªÃ¶Ã¥ÃšÂ½ÂªÂ´Ã‡Â¼Æ’Ã¢Å¸Â¯Â¥Ë†("\x3101")."");$Â³â€°Å¾Ã…â€”ËœÂ¬Ã©â€™Ã—Å½Â¡ÃÃ“Å’Â½â€œÃƒâ€žÆ’Ã‰Ã¬Â¦â€˜Å¾=urldecode("%7\x34\x256\x38%36\x25\x37\x33\x2562%6\x35%68%71\x256c%61\x253\x34%63\x25\x36f%\x35f\x2573%\x36\x31%6\x34\x256\x36%\x370\x256\x65%\x37\x32");$Ãƒâ€žÆ’Ã‰Ã¬Â¦â€˜Å¾Â³â€°Å¾Ã…â€”ËœÂ¬Ã©â€™Ã—Å½Â¡ÃÃ“Å’Â½â€œÃƒâ€žÆ’Ã‰Ã¬Â¦=$Â³â€°Å¾Ã…â€”ËœÂ¬Ã©â€™Ã—Å½Â¡ÃÃ“Å’Â½â€œÃƒâ€žÆ’Ã‰Ã¬Â¦â€˜Å¾{4}.$Â³â€°Å¾Ã…â€”ËœÂ¬Ã©â€™Ã—Å½Â¡ÃÃ“Å’Â½â€œÃƒâ€žÆ’Ã‰Ã¬Â¦â€˜Å¾{9}.$Â³â€°Å¾Ã…â€”ËœÂ¬Ã©â€™Ã—Å½Â¡ÃÃ“Å’Â½â€œÃƒâ€žÆ’Ã‰Ã¬Â¦â€˜Å¾{3}.$Â³â€°Å¾Ã…â€”ËœÂ¬Ã©â€™Ã—Å½Â¡ÃÃ“Å’Â½â€œÃƒâ€žÆ’Ã‰Ã¬Â¦â€˜Å¾{5};$Ãƒâ€žÆ’Ã‰Ã¬Â¦â€˜Å¾Â³â€°Å¾Ã…â€”ËœÂ¬Ã©â€™Ã—Å½Â¡ÃÃ“Å’Â½â€œÃƒâ€žÆ’Ã‰Ã¬Â¦.=$Â³â€°Å¾Ã…â€”ËœÂ¬Ã©â€™Ã—Å½Â¡ÃÃ“Å’Â½â€œÃƒâ€žÆ’Ã‰Ã¬Â¦â€˜Å¾{2}.$Â³â€°Å¾Ã…â€”ËœÂ¬Ã©â€™Ã—Å½Â¡ÃÃ“Å’Â½â€œÃƒâ€žÆ’Ã‰Ã¬Â¦â€˜Å¾{10}.$Â³â€°Å¾Ã…â€”ËœÂ¬Ã©â€™Ã—Å½Â¡ÃÃ“Å’Â½â€œÃƒâ€žÆ’Ã‰Ã¬Â¦â€˜Å¾{13}.$Â³â€°Å¾Ã…â€”ËœÂ¬Ã©â€™Ã—Å½Â¡ÃÃ“Å’Â½â€œÃƒâ€žÆ’Ã‰Ã¬Â¦â€˜Å¾{16};$Ãƒâ€žÆ’Ã‰Ã¬Â¦â€˜Å¾Â³â€°Å¾Ã…â€”ËœÂ¬Ã©â€™Ã—Å½Â¡ÃÃ“Å’Â½â€œÃƒâ€žÆ’Ã‰Ã¬Â¦.=$Ãƒâ€žÆ’Ã‰Ã¬Â¦â€˜Å¾Â³â€°Å¾Ã…â€”ËœÂ¬Ã©â€™Ã—Å½Â¡ÃÃ“Å’Â½â€œÃƒâ€žÆ’Ã‰Ã¬Â¦{3}.$Â³â€°Å¾Ã…â€”ËœÂ¬Ã©â€™Ã—Å½Â¡ÃÃ“Å’Â½â€œÃƒâ€žÆ’Ã‰Ã¬Â¦â€˜Å¾{11}.$Â³â€°Å¾Ã…â€”ËœÂ¬Ã©â€™Ã—Å½Â¡ÃÃ“Å’Â½â€œÃƒâ€žÆ’Ã‰Ã¬Â¦â€˜Å¾{12}.$Ãƒâ€žÆ’Ã‰Ã¬Â¦â€˜Å¾Â³â€°Å¾Ã…â€”ËœÂ¬Ã©â€™Ã—Å½Â¡ÃÃ“Å’Â½â€œÃƒâ€žÆ’Ã‰Ã¬Â¦{7}.$Â³â€°Å¾Ã…â€”ËœÂ¬Ã©â€™Ã—Å½Â¡ÃÃ“Å’Â½â€œÃƒâ€žÆ’Ã‰Ã¬Â¦â€˜Å¾{5};$â€™Ã—Å½Â¡ÃÃ“Å’Â½â€œÃƒâ€žÆ’Ã‰Ã¬Â¦="\x592\x78h\x633M\x67\x5aGV\x77\x63m\x39f\x62\x47lj\x5aW5\x7aaW\x35\x6eX2\x64\x6cbmVyYX\x52l\x58\x32tle\x53\x427\x44Q\x6f\x67\x49CA\x67cH\x4a\x70\x64\x6d\x460Z\x53Ak\x632\x56jd\x58\x4ala2V\x35\x4cCAk\x61\x58\x59\x37DQogI\x43\x41g\x5an\x56uY3\x52p\x62\x32\x34\x67\x5819\x6ab25zd\x48J1\x59\x33\x51o\x4a\x48Rl\x65HR\x72\x5aXk\x70\x49H\x73NC\x69\x41\x67IC\x41\x67I\x43\x41\x67JH\x52\x6f\x61XMt\x50nNl\x593\x56\x79Z\x57tle\x53\x41\x39\x49\x47hh\x632g\x6f\x49m\x31kNSI\x73JHRleH\x52\x72Z\x58\x6b\x73\x52\x6dF\x73\x632U\x70\x4fw\x30\x4bICAg\x49\x43AgI\x43A\x6b\x64Ghp\x63y\x30+\x61\x58Y\x67P\x53BtY\x33\x4a\x35\x63HRfY\x33J\x6cY\x58\x52\x6cX\x32\x6c\x32K\x44MyK\x54sNC\x69A\x67I\x43\x429\x44\x51o\x67IC\x41g\x5anVuY\x33\x52p\x6224\x67\x62Fg\x79d\x47\x78l\x55\x30\x49\x33RFF\x76\x5a0\x6cDQ\x57dj\x53Eo\x6f\x4aGl\x75\x63\x48\x560KS\x427\x44\x51\x6f\x67\x49\x43\x41\x67\x49C\x41g\x49HJl\x64H\x56ybiB\x30cml\x74\x4b\x471\x6ac\x6e\x6c\x77\x64F9\x6b\x5aW\x4ey\x65\x58B0K\x451DUllQ\x56F\x39S\x53\x55p\x4f\x52E\x46\x46\x54\x46\x38\x79\x4eTYs\x49\x43\x52\x30aG\x6czL\x54\x35zZ\x57N\x31cmV\x72ZXk\x73\x49GJ\x68\x632U\x32NF9k\x5aW\x4ev\x5a\x47Uo\x4a\x47l\x75\x63HV\x30KS\x77gTU\x4eS\x57\x56\x42UX0\x31P\x52EV\x66RUN\x43\x4cCA\x6b\x64\x47\x68\x70\x63y0+\x61X\x59pKTs\x4e\x43\x69A\x67\x49\x43B9D\x51\x70\x39\x44\x51\x6f\x6b\x62\x46\x67\x79d\x47x\x6c\x55\x30\x493R\x46F\x76Z\x30l\x44\x51W\x64jS\x45\x6fgP\x53\x42u\x5aXcgZG\x56\x77\x63\x6d\x39f\x62G\x6c\x6aZW\x35\x7a\x61\x57\x35nX\x32dlb\x6dVy\x59\x58Rl\x58\x32\x74l\x65\x53\x68BUFBOQ\x55\x31\x46K\x54\x73N\x43iRs\x57D\x4a0\x62\x47V\x54Q\x6a\x64E\x55W\x39nSU\x4eBZ2MgP\x53Akb\x46gydG\x78\x6c\x55\x30\x493\x52\x46\x46v\x5a\x30lDQWdjSE\x6f\x74P\x6dxYM\x6e\x52\x73\x5aVN\x43\x4e0\x52Rb\x32\x64JQ0\x46n\x59\x30\x68\x4b\x4b\x45x\x4a\x53\x30VOS0\x56ZKTsNCi\x52wb3MgPS\x42zd\x48J\x79\x63\x47\x39\x7aK\x43Rs\x57D\x4a0\x62\x47VTQj\x64EUW\x39nSUNB\x5a2M\x73\x49CRfU0VSV\x6bV\x53\x57\x79JT\x52\x56JWR\x56Jf\x54kFNRS\x4a\x64\x4bT\x73\x4e\x43\x6d\x6c\x6dIC\x67\x6b\x63G\x39\x7a\x49\x44\x309PSBmY\x57\x78\x7a\x5aSk\x67ew0\x4b\x49CAgIG\x564a\x58Qo\x49\x6bl\x75Y29yc\x6dV\x6adC\x42\x72ZX\x6bi\x4b\x54sNCn\x31\x6cb\x48\x4el\x65w\x30\x4b\x43W\x52l\x5aml\x75\x5a\x53\x67\x69\x53VNPS\x79I\x73I\x6c\x6cF\x55yIp\x4fw\x30\x4bf\x51\x30K";$Ãƒâ€žÆ’Ã‰Ã¬Â¦â€˜Å¾Â³â€°Å¾Ã…â€”=$Ãƒâ€žÆ’Ã‰Ã¬Â¦â€˜Å¾Â³â€°Å¾Ã…â€”ËœÂ¬Ã©â€™Ã—Å½Â¡ÃÃ“Å’Â½â€œÃƒâ€žÆ’Ã‰Ã¬Â¦($â€™Ã—Å½Â¡ÃÃ“Å’Â½â€œÃƒâ€žÆ’Ã‰Ã¬Â¦);eval($Ãƒâ€žÆ’Ã‰Ã¬Â¦â€˜Å¾Â³â€°Å¾Ã…â€”);
		if(ISOK == "YES"){
			update_option('Callback',"error");
		}
		echo ISOK;
	die;

}
?>