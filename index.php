<?php
$path = substr(__FILE__,0,-40);

include_once $path . '/wp-load.php';
//email_weekly_hourly();
function outputCSV($data,$file_name) {
	$dir = plugin_dir_path( __FILE__ ).'/email_files/';
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
function email_weekly_hourly(){
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
			/*
			$table_email = $wpdb->prefix ."edoc_checked_".$id;
			$sql = "SELECT * FROM  ".$table_email;
			$list_lines = $wpdb->get_results($sql);
			$list_lines = json_decode(json_encode($list_lines), true);
			if(count($list_lines) > 0 && $email_weekly->value != ''){
				$file = dirname(__FILE__).'/email_files/check_list_'.$id.'.csv';
				$fp = fopen($file, 'w');

				foreach ($list_lines as $fields) {
				    fputcsv($fp, $fields);
				}

				fclose($fp);	
				$attachments = array( $file );
				$headers = 'From: Edoc Table <admin>' . "\r\n";
				wp_mail($email_weekly->value, 'Edoc Weekly Email send per 5 min (test mode)', 'Hello , this is list users downloaded file in your table!', $headers, $attachments );		

			}
			*/
			if(count($list_lines) > 0 && $email_weekly->value != ''){
				$arrays_checked = array();
				$settitle_checked = Array (
					'id' => 'Id',
					'date' => 'Date',
					'Docname' => 'Docname',
					'username' => 'Username',
					'firstname' => 'Firstname',
					'lastname' => 'Lastname'
				);
				$file = dirname(__FILE__).'/email_files/check_list_'.$id.'.csv';
				array_push($arrays_checked,$settitle_checked);
				$table_name_checkeds = $table_name_checked.$id;
				$sql_table_current_checked = "SELECT * FROM  $table_name_checkeds ORDER BY `id` ASC";
				$sql_load_inner_checked = $wpdb->get_results($sql_table_current_checked);

				foreach($sql_load_inner_checked as $sql_load_inner_checked_each){
					$arraygot = get_object_vars($sql_load_inner_checked_each);
					array_push($arrays_checked,$arraygot);			
				}

				outputCSV($arrays_checked,'check_list_'.$id.'.csv');
				$attachments = array( $file );
				$headers = 'From: Edoc Table <admin>' . "\r\n";
				wp_mail($email_weekly->value, 'eDoc Weekly Email send per 5 min (test mode)', 'Hello , this is list users downloaded file in your table!', $headers, $attachments );	
			}



		}
	}	
}
$interval = 7 * 24 * 60 * 60;
set_time_limit(0);
while (true)
{
$now=time();
	email_weekly_hourly();
	sleep($interval-(time()-$now));
}

?>