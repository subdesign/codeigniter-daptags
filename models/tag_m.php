<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tag_m extends MY_Model 
{

	public $before_create = array(' serialize(article_ids) ');
	public $before_update = array(' serialize(article_ids) ');
	public $after_get = array(' unserialize(article_ids) ');

}