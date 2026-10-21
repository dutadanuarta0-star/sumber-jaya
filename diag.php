<?php
// Diag: paksa Router untuk admin/logout.
define('ENVIRONMENT', 'development');
error_reporting(-1); ini_set('display_errors', 1);
define('SELF', 'index.php');
define('BASEPATH', __DIR__.'/system/');
define('FCPATH', __DIR__.'/');
define('SYSDIR', 'system');
define('APPPATH', __DIR__.'/application/');
define('VIEWPATH', APPPATH.'views/');
define('MB_ENABLED', TRUE);
define('ICONV_ENABLED', TRUE);
define('UTF8_ENABLED', TRUE);

require_once BASEPATH.'core/Common.php';
$URI =& load_class('URI', 'core');
$URI->segments = array(1 => 'admin', 2 => 'logout');
$URI->uri_string = 'admin/logout';
$RTR =& load_class('Router', 'core', NULL);

echo "<pre>\n";
echo 'directory : ['.(string) $RTR->directory."]\n";
echo 'class     : ['.$RTR->class."]\n";
echo 'method    : ['.$RTR->method."]\n";
echo '</pre>';
