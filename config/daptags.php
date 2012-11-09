<?php 

/**
 * Daptags config file
 */

// limit the number of rendered tags
$config['limit'] = NULL; // NULL, 50, 100, ..

// diviter html tag(s) between tags
$config['divider'] = '&nbsp;&nbsp;';

// ordering tags
$config['tags_order'] = 'ASC';	// ASC, DESC

// class of tags container DIV 
$config['tag_container'] = 'tags';

// use file cache?
$config['cache'] = FALSE;	// TRUE, FALSE 	

// cache TTL
$config['cache_ttl'] = 60;