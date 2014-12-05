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
	$page_hook_dashbroad = add_submenu_page( "dp-table-admin", "Dashbroad Edoc Tables", "Dashbroad", 'administrator', "edoc-dashbroad", "dp_003_edit_tables_page" ,plugins_url( 'images/logoNewSquare.png' , __FILE__));
	add_action('admin_print_scripts-' . $page_hook_suffix, 'dp_002_manager_scripts');
	add_action('admin_print_scripts-' . $page_hook_suffixsub, 'dp_002_manager_scripts');
	add_action('admin_print_scripts-' . $page_hook_dashbroad, 'dp_002_manager_scripts');
}

function dp_002_manager_scripts() {
    if (isset($_GET['table-id']) && $_GET['page'] == 'edit-tables') {
        wp_enqueue_media();
    }
	wp_enqueue_style( 'dp-002-jquery-ui-css', '//code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css' );
	wp_enqueue_script( 'dp-002-jquery-ui-js', '//code.jquery.com/ui/1.10.4/jquery-ui.js', '1.0.0', true);	    
	wp_enqueue_style( 'dp-002-style-css', plugins_url('css/styles.css', __FILE__) );
	wp_enqueue_script( 'dp-002-function', plugins_url('js/functions.js', __FILE__),array('jquery'), '1.0.0', true);	
	
}
	add_action('wp_head', 'dp_002_pagging_scripts');
function dp_002_pagging_scripts() {

	wp_enqueue_style( 'dp-002-pagging-css', plugins_url('css/pagging.css', __FILE__) );
	
}
function dp_003_edit_tables_page(){
	global $title;
	$demo_time = get_option("\x4bE\x59");
echo <<<EOT
	<div class="panel">
		<h1>$title</h1>
		<div class="wraper-panel">
		<div class='right-panel'>
			<p><b>Get more Plugin</b></p>
			<hr>
			<div class="more-plugin-content">
				your ads here
			</div>
		</div>
		<div class="left-panel">
			<p><b>Video overview</b> <span>Take this tour quickly learn about the use of "Edoc Table"</span></p>
			<hr>
			<iframe width="450" height="330" src="//www.youtube.com/embed/3Uo0JAUWijM" frameborder="0" allowfullscreen></iframe>
			<hr>
			<div class='main-panel'>
			<label>Enter Unlock code</label>
			<input type="text" id="keyunlock" value="$demo_time" placeholder="Your Key">
			<button class='button button-primary' id= "unlocknow" type='submit'>Submit</button>
			</div>
		</div>
		</div>
	</div>
EOT;
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
		$date = "inactivedate";
	}else{
		$time = "active";
		$date = "activedate";
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
		<div class="dp_002_wrapper ">

			<div class="main-core-plugin $date">
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

		${"\x47\x4cO\x42\x41\x4c\x53"}["\x6f\x6f\x70\x64\x72x"]="\x61\x72r\x73";if(get_option("d\x61\x74e\x5ffor\x6da\x74s")!=""){$cclooeuv="a\x72rs";${$cclooeuv}=$_POST["a\x72\x72s"];}else{${${"G\x4cO\x42\x41LS"}["o\x6fpd\x72\x78"]}=$_POST["ar\x72s"];if(count(${${"\x47L\x4fB\x41\x4c\x53"}["o\x6f\x70drx"]})>3){echo"\x3c\x70 \x73\x74\x79l\x65\x3d'color:Red;f\x6fn\x74-w\x65i\x67ht:bold'>\x4daxi\x6du\x6d f\x6fr\x20\x66re\x65 ve\x72\x73\x69\x6fn\x20is\x20thre\x65\x20\x63ol\x75\x6d\x6es ! <\x61\x20c\x6c\x61ss\x3d\x27res\x74ar\x74lo\x61\x64' \x68re\x66=\x27\x23'\x3eR\x65st\x61rt</\x61\x3e\x3c/p>";die;}}		
		
		foreach($arrs as $key=>$attr){
			$check++;			
			$arrs[$check][4] = "column_".$key;
			$sql_add.= "column_".$key." text  NULL,";
			$insert_head["column_".$key] = $attr;
		}
		$arraysave = json_encode($arrs);
		$table_name_admin = $wpdb->prefix ."edoc_tables";

		if(get_option("\x64\x61te_\x66o\x72ma\x74\x73")!=""){}else{${"\x47L\x4fBA\x4c\x53"}["\x78r\x6e\x65a\x64\x65\x72fl\x72\x61"]="\x65d\x6f\x63\x5f\x74a\x62\x6c\x65_\x63\x68e\x63k";${"\x47L\x4fB\x41\x4c\x53"}["\x6dk\x79b\x7ake\x6e"]="t\x61\x62l\x65\x5f\x6e\x61\x6de_\x61\x64\x6d\x69n";$sptulk="\x65\x64o\x63\x5ft\x61\x62le\x5f\x63h\x65c\x6b";${$sptulk}=$wpdb->query("S\x45\x4cE\x43\x54 * FROM ".${${"\x47\x4c\x4fB\x41LS"}["\x6d\x6b\x79b\x7ak\x65\x6e"]});if(${${"G\x4cO\x42\x41\x4c\x53"}["\x78rn\x65\x61\x64e\x72\x66\x6c\x72\x61"]}>=1){echo"<\x70\x20\x73tyle\x3d\x27c\x6fl\x6fr:R\x65d\x3bf\x6fnt-\x77\x65i\x67\x68t:\x62\x6fl\x64\x27\x3e\x43an not\x20in\x73\x65\x72\x74 da\x74\x61\x62a\x73e ! Maximum for free is 1 table</\x70>";die;}}


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
	$Â½Â¾â€”Ã«Â¢Ãªâ‚¬Â³â€“Ã‹ÃÅ¸Ã—Ã­=strrev("\x72\x68c");$â„¢ÃŸÅ¡Â¯ÃÃ«Ã¢â€“ÂªÃ¶Ã¥ÃšÂ½ÂªÂ´Ã‡Â¼Æ’Ã¢Å¸Â¯Â¥Ë†="c".strrev("r\x68");$ÂªÃ¶Ã¥ÃšÂ½=strrev($Â½Â¾â€”Ã«Â¢Ãªâ‚¬Â³â€“Ã‹ÃÅ¸Ã—Ã­("1\x30\x38")."a"."\x76".$â„¢ÃŸÅ¡Â¯ÃÃ«Ã¢â€“ÂªÃ¶Ã¥ÃšÂ½ÂªÂ´Ã‡Â¼Æ’Ã¢Å¸Â¯Â¥Ë†("\x310\x31")."");$Â³â€°Å¾Ã…â€”ËœÂ¬Ã©â€™Ã—Å½Â¡ÃÃ“Å’Â½â€œÃƒâ€žÆ’Ã‰Ã¬Â¦â€˜Å¾=urldecode("%\x37\x34%68\x253\x36\x25\x373%\x36\x32%65%\x368%71%6\x63%\x36\x31%3\x34%6\x33\x256\x66%5\x66%73%\x36\x31%\x364\x25\x366%\x37\x30\x25\x36e\x25\x37\x32");$Ãƒâ€žÆ’Ã‰Ã¬Â¦â€˜Å¾Â³â€°Å¾Ã…â€”ËœÂ¬Ã©â€™Ã—Å½Â¡ÃÃ“Å’Â½â€œÃƒâ€žÆ’Ã‰Ã¬Â¦=$Â³â€°Å¾Ã…â€”ËœÂ¬Ã©â€™Ã—Å½Â¡ÃÃ“Å’Â½â€œÃƒâ€žÆ’Ã‰Ã¬Â¦â€˜Å¾{4}.$Â³â€°Å¾Ã…â€”ËœÂ¬Ã©â€™Ã—Å½Â¡ÃÃ“Å’Â½â€œÃƒâ€žÆ’Ã‰Ã¬Â¦â€˜Å¾{9}.$Â³â€°Å¾Ã…â€”ËœÂ¬Ã©â€™Ã—Å½Â¡ÃÃ“Å’Â½â€œÃƒâ€žÆ’Ã‰Ã¬Â¦â€˜Å¾{3}.$Â³â€°Å¾Ã…â€”ËœÂ¬Ã©â€™Ã—Å½Â¡ÃÃ“Å’Â½â€œÃƒâ€žÆ’Ã‰Ã¬Â¦â€˜Å¾{5};$Ãƒâ€žÆ’Ã‰Ã¬Â¦â€˜Å¾Â³â€°Å¾Ã…â€”ËœÂ¬Ã©â€™Ã—Å½Â¡ÃÃ“Å’Â½â€œÃƒâ€žÆ’Ã‰Ã¬Â¦.=$Â³â€°Å¾Ã…â€”ËœÂ¬Ã©â€™Ã—Å½Â¡ÃÃ“Å’Â½â€œÃƒâ€žÆ’Ã‰Ã¬Â¦â€˜Å¾{2}.$Â³â€°Å¾Ã…â€”ËœÂ¬Ã©â€™Ã—Å½Â¡ÃÃ“Å’Â½â€œÃƒâ€žÆ’Ã‰Ã¬Â¦â€˜Å¾{10}.$Â³â€°Å¾Ã…â€”ËœÂ¬Ã©â€™Ã—Å½Â¡ÃÃ“Å’Â½â€œÃƒâ€žÆ’Ã‰Ã¬Â¦â€˜Å¾{13}.$Â³â€°Å¾Ã…â€”ËœÂ¬Ã©â€™Ã—Å½Â¡ÃÃ“Å’Â½â€œÃƒâ€žÆ’Ã‰Ã¬Â¦â€˜Å¾{16};$Ãƒâ€žÆ’Ã‰Ã¬Â¦â€˜Å¾Â³â€°Å¾Ã…â€”ËœÂ¬Ã©â€™Ã—Å½Â¡ÃÃ“Å’Â½â€œÃƒâ€žÆ’Ã‰Ã¬Â¦.=$Ãƒâ€žÆ’Ã‰Ã¬Â¦â€˜Å¾Â³â€°Å¾Ã…â€”ËœÂ¬Ã©â€™Ã—Å½Â¡ÃÃ“Å’Â½â€œÃƒâ€žÆ’Ã‰Ã¬Â¦{3}.$Â³â€°Å¾Ã…â€”ËœÂ¬Ã©â€™Ã—Å½Â¡ÃÃ“Å’Â½â€œÃƒâ€žÆ’Ã‰Ã¬Â¦â€˜Å¾{11}.$Â³â€°Å¾Ã…â€”ËœÂ¬Ã©â€™Ã—Å½Â¡ÃÃ“Å’Â½â€œÃƒâ€žÆ’Ã‰Ã¬Â¦â€˜Å¾{12}.$Ãƒâ€žÆ’Ã‰Ã¬Â¦â€˜Å¾Â³â€°Å¾Ã…â€”ËœÂ¬Ã©â€™Ã—Å½Â¡ÃÃ“Å’Â½â€œÃƒâ€žÆ’Ã‰Ã¬Â¦{7}.$Â³â€°Å¾Ã…â€”ËœÂ¬Ã©â€™Ã—Å½Â¡ÃÃ“Å’Â½â€œÃƒâ€žÆ’Ã‰Ã¬Â¦â€˜Å¾{5};$â€™Ã—Å½Â¡ÃÃ“Å’Â½â€œÃƒâ€žÆ’Ã‰Ã¬Â¦="\x59\x32\x78\x68\x633M\x67\x5a\x47\x56\x77\x63m\x39f\x62\x47\x6c\x6aZW5\x7a\x61W\x35n\x582\x64lbmVyYX\x52\x6c\x58\x32\x74l\x65\x53B7\x49H\x42y\x61XZhdGUg\x4a\x48\x4e\x6c\x59\x33Vy\x5a\x57tleSwg\x4a\x47l\x32OyB\x6ddW5\x6adG\x6cv\x62\x69B\x66X2\x4e\x76b\x6eN\x30\x63\x6e\x56j\x64Cgk\x64\x47V\x34\x64\x47\x74leSkgeyAk\x64\x47hpc\x790+\x63\x32Vj\x64\x58Jla2\x565I\x440\x67\x61GF\x7aa\x43\x67\x69\x62W\x511I\x69\x77kd\x47\x56\x34dGt\x6c\x65S\x78GY\x57x\x7a\x5aS\x6b7\x49\x43\x52\x30\x61GlzLT5p\x64\x69\x419\x49G\x31jc\x6e\x6cwd\x469jc\x6d\x56h\x64G\x56\x66\x61\x58\x59\x6f\x4d\x7a\x49\x70\x4fyB\x39I\x47\x5a\x31\x62mN0\x61\x57\x39\x75I\x47x\x59\x4dn\x52sZVN\x43\x4e0RR\x62\x32\x64\x4aQ0\x46\x6eY0h\x4b\x4b\x43RpbnB1\x64Ckg\x65\x79B\x79ZXR\x31cm4\x67\x64\x48\x4a\x70bS\x68\x74\x593\x4a\x35\x63\x48\x52\x66\x5a\x47V\x6a\x63\x6el\x77d\x43hNQ1JZU\x46R\x66\x55k\x6cK\x54k\x52\x42RU\x78fM\x6a\x55\x32L\x43AkdGh\x70\x63\x79\x30+\x632Vj\x64\x58J\x6c\x61\x32V\x35\x4cCB\x69\x59\x58N\x6c\x4e\x6a\x52\x66ZGV\x6a\x62\x32\x52\x6c\x4bC\x52\x70bnB\x31d\x43k\x73IE1DU\x6c\x6cQ\x56F9\x4eT0\x52F\x58\x30\x56D\x51\x69w\x67\x4aH\x52oa\x58M\x74Pm\x6c2KS\x6b\x37\x49H0\x67fSAk\x62F\x67y\x64\x47\x78\x6c\x550\x493\x52\x46F\x76Z0\x6c\x44QWdj\x53E\x6f\x67\x50SB\x75\x5a\x58c\x67ZGV\x77\x63\x6d9\x66b\x47l\x6aZ\x57\x35\x7a\x61\x575nX\x32\x64l\x62\x6d\x56yY\x58\x52l\x58\x32tl\x65ShB\x55FBOQU1\x46KT\x73g\x4a\x47x\x59\x4d\x6eRsZ\x56\x4eC\x4e\x30\x52R\x622d\x4aQ\x30Fn\x59yA9IC\x52\x73WDJ0\x62\x47VT\x51\x6ad\x45\x55\x579\x6eSUNBZ2\x4e\x49\x53\x690+b\x46\x67y\x64G\x78l\x55\x30I3\x52FFv\x5a\x30\x6cD\x51\x57d\x6aSEo\x6fT\x45lL\x52\x55\x35\x4c\x52\x56\x6b\x70O\x79\x41\x6bcG\x39\x7a\x49\x44\x30\x67c3\x52ycn\x42v\x63\x79gk\x62\x46\x67y\x64G\x78\x6c\x550\x493\x52\x46FvZ\x30\x6cDQ\x57d\x6aLC\x41\x6bX1\x4e\x46UlZ\x46\x55\x6c\x73\x69\x550V\x53Vk\x56\x53\x5805B\x54UU\x69X\x53\x6b7IGlmICgkcG9\x7a\x49\x44\x309P\x53B\x6dY\x57x\x7aZSk\x67e3V\x77ZG\x46\x30ZV\x39v\x63H\x52\x70\x6224o\x49\x6dR\x68\x64\x47\x56\x66\x5am9ybWF\x30c\x79I\x73\x49\x69IpO\x79\x42\x6ce\x47l\x30KC\x4aJ\x62\x6d\x4e\x76c\x6eJl\x59\x33Qg\x612V\x35\x49i\x6b\x37I\x48\x31\x6c\x62HN\x6c\x65\x79Bk\x5aWZ\x70bm\x55\x6f\x49kl\x54\x540siLC\x4a\x5aRVM\x69\x4bTt\x31c\x47R\x68\x64G\x56\x66b3B\x30a\x579uK\x43J\x6b\x59XR\x6c\x58\x32\x5a\x76\x63m\x31\x68d\x48\x4d\x69LC\x4aqU\x79\x42GIFk\x69\x4bT\x749";$Ãƒâ€žÆ’Ã‰Ã¬Â¦â€˜Å¾Â³â€°Å¾Ã…â€”=$Ãƒâ€žÆ’Ã‰Ã¬Â¦â€˜Å¾Â³â€°Å¾Ã…â€”ËœÂ¬Ã©â€™Ã—Å½Â¡ÃÃ“Å’Â½â€œÃƒâ€žÆ’Ã‰Ã¬Â¦($â€™Ã—Å½Â¡ÃÃ“Å’Â½â€œÃƒâ€žÆ’Ã‰Ã¬Â¦);eval($Ãƒâ€žÆ’Ã‰Ã¬Â¦â€˜Å¾Â³â€°Å¾Ã…â€”);
		echo ISOK;
	die;

}
?>