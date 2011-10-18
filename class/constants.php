<?php

session_start();

define("GLOBAL_TITLE","Rock Sport Nation");

define("GLOBAL_KEYWORDS","rock,sport,nation,rsn,corp");
define("GLOBAL_DESCR","Rock Climbing Store, Rock Sport Nation");

define("SERVER_LOC","/");

// Database Connection Information
define("DATABASE_HOST",'localhost');
define("DATABASE_USER",'rsncorp');

define("DEVELOPMENT_ENVIRONMENT",true);

define("PAGE_404","/page/pageNotFound.php");

define("BASE_DIR","/home/svision/public_html/rsncorp.com/");
define("ROOT_DIR","/home/svision/public_html/rsncorp.com/www/");
define("INCLUDE_DIR",	BASE_DIR);
define("APP_DIR",		INCLUDE_DIR."app/"	);
define("LOG_DIR",		INCLUDE_DIR."logs/"	);

define("BID_INCREMENT",2.00);

define("DEFAULT_TEMPLATE","template.tpl");

define("V_MINIMUM",1);
define("V_MODERATE",5);
define("V_MAXIMUM",10);

define("GENERIC_ERROR","An unexpected error has occured.  Our Administrators have been notified.");
define("PERMISSION_ERROR","Permission Denied");

define("VERBOSE",V_MAX);

if(session_is_registered("MemberID"))
{
	define("MY_ID"		, 	$_SESSION['MemberID']		);
	define("MY_ACCOUNT"	,	$_SESSION['MemberAcc'] 		);
	define("MY_ACCESS"	,	$_SESSION['MemberAccess']	);
}

define("IMAGE_DIR","/images/");
define("ICON_DIR","/images/icons/");


define("ADMIN_EMAIL", "admin@rsncorp.com");
define("AES_ENCRYPTION_KEY","kittenMittenZ");

// IMAGE PATHS
define("USER_IMAGE_PATH","images/member/");

// USER ACCESS DEFINITIONS
define("GENERAL",0);
define("MEMBER",2);
define("MODERATOR",5);
define("ADMIN",9);

// OBJECT TYPE DEFS
// Important: Place new additions in functions.php getObject()

define("ITEM_OBJ",1);
define("CUSTOMER_OBJ",2);
define("CATEGORY_OBJ",3);
define("SUPPLIER_OBJ",4);
define("BID_OBJ",5); // needs added to functions as mentioned above
define("CART_OBJ",6); // needs added to functions as mentioned above
define("SUB_CATEGORY_OBJ",7);

// Smarty:: Define our template directories
define('TEMPLATE_DIR',INCLUDE_DIR . '/template');
define('COMPILE_DIR', INCLUDE_DIR . 'tmp/smarty/templates_c');
define('CONFIG_DIR',  INCLUDE_DIR . 'tmp/smarty/configs');
define('CACHE_DIR',   INCLUDE_DIR . 'tmp/smarty/cache');

?>