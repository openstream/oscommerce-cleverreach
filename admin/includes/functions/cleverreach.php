<?php

 // Check if configuration group exists and create one if not

 $query = 'SELECT configuration_group_id FROM configuration_group WHERE configuration_group_title = "CleverReach"';
 $res = mysql_query($query);
 if($res && !mysql_num_rows($res)){
  $query = 'INSERT INTO configuration_group (configuration_group_title, sort_order, visible) VALUES ("CleverReach", 7, 1)';
  mysql_query($query);
  $group_id = mysql_insert_id();
  $query = 'INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_group_id, sort_order, set_function) VALUES ("Enable CleverReach", "CR_ENABLED", "false", '.$group_id.', 1, "tep_cfg_select_option(array(\'true\', \'false\'),")';
  mysql_query($query);
  echo mysql_error();
 }else{
  $group_id = mysql_result($res, 0, 0);
 }

 // If CleverReach is enabled but there is no configuration fields, add them

 if(CR_ENABLED == 'true'){
  $query = 'SELECT * FROM configuration WHERE configuration_key IN("CR_API_KEY", "CR_LIST_ID")';
  $res = mysql_query($query);
  if($res && mysql_num_rows($res) != 2){
   $query = 'DELETE FROM configuration WHERE configuration_key IN("CR_API_KEY", "CR_LIST_ID")';
   mysql_query($query);
   $query = 'INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_group_id, sort_order, configuration_description) VALUES ("API Key", "CR_API_KEY", "", '.$group_id.', 2, "This can be found on your List settings page in CleverReach.")';
   mysql_query($query);
   $query = 'INSERT INTO configuration (configuration_title, configuration_key, configuration_value, configuration_group_id, sort_order, configuration_description) VALUES ("List ID", "CR_LIST_ID", "", '.$group_id.', 3, "SwissCart users who subscribe/unsubscribe to the newsletter will be added/removed from this CleverReach receiver list.")';
   mysql_query($query);
  }
 }else{
  $query = 'DELETE FROM configuration WHERE configuration_key IN("CR_API_KEY", "CR_LIST_ID")';
  mysql_query($query); 
 }

?>