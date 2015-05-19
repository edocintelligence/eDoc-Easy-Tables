<?php
$path = substr(__FILE__,0,-40);

include_once $path . '/wp-load.php';
//email_weekly_hourly();
function email_weekly_hourly(){
	global $wpdb;
	$table_name_admin = $wpdb->prefix ."edoc_tables";
	$sql = "SELECT * FROM  ".$table_name_admin;
	$sql_fists = $wpdb->get_results($sql);
	foreach($sql_fists as $sql_fist){
		$email_weekly = $sql_fist->email_weekly;
		$email_weekly = json_decode($email_weekly);
		if($email_weekly->adminset == 'yes' && $email_weekly->value != ''){
			$id = $sql_fist->id;
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
				wp_mail($email_weekly->value, 'Edoc Tables Weekly Email', 'Hello , this is list users who downloaded files in your tables', $headers, $attachments );		

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