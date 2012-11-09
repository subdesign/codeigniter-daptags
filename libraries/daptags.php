<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Daptags tagcloud library
 *
 * @author Barna Szalai <b.sz@devartpro.com>
 * @license http://www.opensource.org/licenses/MIT  MIT License
 * @link https://github.com/subdesign/codeigniter-daptags
 * @version 1.0.0
 */

class Daptags
{
	/**
	* The CodeIgniter instance.
	*
	* @var object
	* @access private
	*/
	private $_CI;

	/**
	* Limit of the rendered tags
	*
	* @var integer/null
	* @access private
	*/
	private $_limit;

	/**
	* Divider html tag(s) between tags
	*
	* @var string
	* @access private
	*/
	private $_divider;

	/**
	* Alphabetical order of tags
	*
	* @var string
	* @access private
	*/
	private $_tags_order;

	/**
	* Class of tags container div
	*
	* @var string
	* @access private
	*/
	private $_tag_container;

	/**
	* Use cache
	*
	* @var bool
	* @access public
	*/
	public $cache;

	/**
	* Cache TTL value
	*
	* @var integer
	* @access private
	*/
	private $_cache_ttl;
	
	/**
	 * Constructor
	 *
	 * Gets the CI instance and runs initialization method. 
	 * If cache setting is on, then loads the Cache spark
	 * 
	 * @param array $config Config settings 
	 */
	public function __construct($config = array())
	{
		$this->_CI =& get_instance();
		
		if( ! empty($config))
		{
			$this->_initialize($config);			
		}
		else
		{
			$config = $this->_CI->config->load('daptags');	
			$this->_initialize($config);
		}		

		// If cache enabled load Cache spark
		if($this->cache)
		{
			$this->_CI->load->spark('cache/2.0.0');
		}
	}

	/**
	 * Initialize class
	 * 
	 * @param array $config Config settings
	 * @return void
	 * @access private
	 */
	private function _initialize($config = array())
	{
		if( ! is_array($config))
		{
			throw new Exception("Config variable must be an array");			
		}

		foreach($config as $k => $v)
		{
			$this->{'_'.$k} = $v;
		}
	}

	/**
	 * Renders the tag cloud from tags
	 * If cache is on, caches the tags
	 * 
	 * @return string  Html code of tag cloud
	 * @access public
	 */
	public function render()
	{				 
		if($this->_cache) 
		{
			if( ! $tags = $this->_CI->cache->get('tags'))
			{
				$tags = $this->_build_html();
				$this->_CI->cache->write($tags, 'tags', $this->_cache_ttl);
			}

			return $tags;
		}
		
		$tags = $this->_build_html();
		
		return $tags;
	}

	/**
	 * Builds the html code with tags
	 * 
	 * @return string  Html code of tag cloud
	 * @access private
	 */
	private function _build_html()
	{
		// Set limit of tags shown
		$limit = ($this->_limit === NULL) ? $this->_CI->db->count_all('tags') : $this->_limit; 

		// Get tags from DB 
		$tags = $this->_CI->tag_m->order_by('name',$this->_tags_order)->limit($limit)->get_all();

		// Choose active tags only
		foreach($tags as $t)
		{
			foreach($t->article_ids as $key => $value)
			{
				if($this->_CI->article_m->get_by('id', $value)->status == 1)
				{
					$actives[$t->name][] = $value;
				}
			}
		}

		// Get the biggest count tag
		$max = $this->_get_max($actives);
		
		$html = "<div class='{$this->_tag_container}'>";

		foreach($actives as $key => $value)
		{
			$count = count($value);

			// Count tag size
			$size = $this->_get_size(( $count / $max) * 100);
			
			$html .= anchor('tag/'.urlencode($key), $key, array('class' => 'size'.$size, 'title' => 'Number of articles: '.$count)).$this->_divider;
		}

		$html .= '</div>';

		return $html;
	}
	
	/**
	 * Get the biggest count tag
	 * 
	 * @param  array $tags Tags
	 * @return integer $max
	 * @access private
	 */
	private function _get_max($actives)
	{
		$max = 0;
		$x = 0;
		foreach($actives as $tag)
		{
			$y = count($tag);
			if($y > $x)
			{
				$max = $y;
				$x = $y;
			}
		}

		return $max;
	}

	/**
	 * Count tag size by percentage
	 * 
	 * @param  integer $value Percentage
	 * @return integer $class "size" of the tag
	 * @access private
	 */
	private function _get_size($value)
	{
		$value = floor($value);

		switch($value)
		{
			case $value >= 99 : 
			$class = 9;
			break;

			case $value >= 70 : 
			$class = 8;
			break;

			case $value >= 60 : 
			$class = 7;
			break;

			case $value >= 50 : 
			$class = 6;
			break;

			case $value >= 40 : 
			$class = 5;
			break;

			case $value >= 30 : 
			$class = 4;
			break;

			case $value >= 20 : 
			$class = 3;
			break;

			case $value >= 10 : 
			$class = 2;
			break;

			case $value >= 5 : 
			$class = 1;
			break;

			default :
			$class = 0;
		}

		return $class;
	}

	/**
	 * Get articles clicking on a tag in tag cloud
	 * 
	 * @param  string $tag Tag name
	 * @return object      Articles
	 * @access public
	 */
	public function get_article_ids_by_tag($tag)
	{
		$ids = $this->_CI->tag_m->get_by('name', $tag)->article_ids;

		foreach($ids as $key => $value)
		{
			if($this->_CI->article_m->get_by('id', $value)->status == 1)
			{
				$data[] = $value;
			}
		}

		return $data;
	}

	/**
	 * Get tags of a specific article
	 * 
	 * @param  integer $article_id Article id
	 * @return array               Tag names
	 * @access public
	 */
	public function get_tags_by_article($article_id)
	{
		$all_tags = $this->_CI->tag_m->get_all();

		$tagnames = array();

		foreach($all_tags as $tag)
		{
			foreach($tag->article_ids as $k => $v)
			{
				if($v == $article_id)
				{
					$tagnames[] = $tag->name;		
					break;
				}
			}			
		}

		return $tagnames;
	}

	/**
	 * Add tag(s)
	 * 
	 * @param array $tags Tags
	 * @param integer $article_id Article id
	 * @access public
	 */
	public function add_tag($tags, $article_id)
	{		
		$clean_tags = $this->_transform_tags($tags);

		foreach($clean_tags as $key => $value)			
		{
			$exists = NULL;

			$exists = $this->_CI->tag_m->get_by('name', $value);

			// If doesn't exists
			if(is_array($exists))
			{
				$data = array(
					'name'        => $value,
					'article_ids' => array((int)$article_id)
				);

				$this->_CI->tag_m->insert($data);
			}
			else
			{
				// Init $ids
				$ids = array();

				// More readable
				$ids = $exists->article_ids;

				// Add element to array
				array_push($ids, (int)$article_id);

				// If some duplicated, remove them	
				$ids = array_unique($ids);

				$data = array(
					'article_ids' => $ids
				);

				$this->_CI->tag_m->update($exists->id, $data);
			}
		}		

		if($this->cache)
		{
			$this->delete_cache();
		}
	}
	
	/**
	 * Edit tag(s)
	 *
	 * If tag(s) removed, then update tag(s) without the removed tag(s)
	 * If tag(s) added, add the new tag(s)
	 * 
	 * @param  array $tags Tags
	 * @param  integer $article_id Article id
	 * @return void
	 * @access public
	 */
	public function edit_tag($tags, $article_id)
	{
		$tags = $this->_transform_tags($tags);

		// Get the "old" tags
		$oldtags = $this->get_tags_by_article($article_id);

		// What is/are deleted
		$delete = array_diff($oldtags, $tags);

		// What is/are the same as the old tags
		$same = array_intersect($oldtags, $tags);

		// Pull out what is/are the same
		foreach($same as $s => $v)
		{			
			unset($tags[$s]);
		}

		// Pull out what is/are deleted		
		if(count($delete))
		{
			$this->_delete_from_tag($delete, $article_id);
		}

		// Add the remaining (new) tag(s)
		$this->add_tag($tags, $article_id);

		if($this->cache)
		{
			$this->delete_cache();
		}		
	}

	/**
	 * Transform tags into arrays, trims spaces, ..
	 * 
	 * @param  string $tags Tags
	 * @return array
	 * @access private
	 */
	private function _transform_tags($tags)
	{
		$temp = explode(",", $tags);

		$clean_tags = array();

		foreach($temp as $k => $v)
		{
			if(strlen($v))
				$clean_tags[] = trim($v);
		}

		return $clean_tags;
	}

	/**
	 * Delete a specific Article id from a tag
	 * 
	 * @param  array $delete Tags
	 * @param  integer $article_id Article id
	 * @return void
	 * @access private
	 */
	private function _delete_from_tag($delete, $article_id)
	{
		foreach($delete as $key => $value)
		{
			$tag = $this->_CI->tag_m->get_by('name', $value);

			// Call _delete() submethod
			$this->_delete($tag, $article_id);
		}

		if($this->cache)
		{
			$this->delete_cache();
		}	
	}

	/**
	 * Delete all corresponding tags by deleting an article
	 * 
	 * @param  integer $article_id Article id
	 * @return void
	 * @access public
	 */
	public function delete_tags($article_id)
	{
		$all_tags = $this->_CI->tag_m->get_all();			

		foreach($all_tags as $tag)
		{
			// Call _delete() submethod
			$this->_delete($tag, $article_id);			
		}

		if($this->cache)
		{
			$this->delete_cache();
		}	
	}

	/**
	 * Sub method of _delete_from_tag() and delete_tags()
	 * 
	 * @param  array $tag Tags
	 * @param  integer $article_id Article id
	 * @return void
	 * @access private
	 */
	private function _delete($tag, $article_id)
	{		
		$count = count($tag->article_ids);

		foreach($tag->article_ids as $k => $v)
		{		
			if($v == (int)$article_id)
			{
				unset($tag->article_ids[$k]);		

				// If it was the last tag, then delete
				if($count == 1)
				{				
					$this->_CI->tag_m->delete_by(array('name' => $tag->name));
				}
				else
				{
					// Update the remaining article ids	
					$this->_CI->tag_m->update_by(array('name' => $tag->name), array('article_ids' => $tag->article_ids));
				}					
			}				
		}
	}

	/**
	 * Delete cache submethod
	 * @return void
	 * @access public
	 */
	public function delete_cache()
	{
		$this->_CI->cache->delete('tags');
	}
}