<?php
/*
Plugin Name: copo
Plugin URI:   https://www.copo.erfanmoshafi.com 
Description: copo for calendar program. 
Version:      1.0
Author:       erfan moshafi 
Author URI:   https://www.erfanmoshafi.com
License:      GPL3
License URI:  https://www.gnu.org/licenses/gpl-3.0.html
Text Domain:  
Domain Path:  
*/
function create_table()
{
  global $wpdb;
  dbDelta("CREATE TABLE  copo_plugin  (
        id  int NOT NULL AUTO_INCREMENT,
        title  varchar(355) DEFAULT NULL,
        description  text,
        cover  varchar(500) DEFAULT NULL,
        files  json DEFAULT NULL,
        color  varchar(255) NOT NULL,
        start_date  varchar(355) NOT NULL,
        end_date  varchar(355) NOT NULL,
        date  timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
       PRIMARY KEY (id)
     ) ");
}
register_activation_hook( __FILE__, 'create_table' );
require_once(ABSPATH.'wp-admin/includes/upgrade.php');
include "function.php";