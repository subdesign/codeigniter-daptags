<?php 

/**
 * Example controller and theoretical methods demo for Daptags
 */

class Example extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();

		$this->load->model(array('article_m', 'tag_m'));
		$this->load->library('daptags');
	}	

	/* FRONTEND PART */

	/**
	 * Render the tag cloud (in sidebar or something)
	 */
	public function index()
	{
		$data['tags'] = $this->daptags->render();
		$this->load->view('tags_index', $data);		
	}

	/**
	 * Get the tags for a specific article
	 */
	public function article($id)
	{
		$data['tags'] = $this->daptags->get_tags_by_article($id);		
		$data['article'] = $this->article_m->get_by('id', $id);
		$this->load->view('tags_article', $data);		
	}	

	/**
	 * Get all articles by tag
	 *
	 * Class uses "tag/" so the route is like: $route['tag/(:any)'] = 'example/tag/$1'; 
	 */
	public function tag($tag)
	{
		$article_ids = $this->daptags->get_article_ids_by_tag(urldecode($tag));
		$data['articles'] = $this->article_m->get_many($article_ids);
		$this->load->view('tags_articles_by_tags', $data);
	}

	/* ADMIN PART */

	/**
	 * Add an article and add tags
	 *
	 * CRUD -> Create
	 */
	public function add()
	{
		if($this->input->post())
		{
			$val = $this->form_validation;
			$val->set_rules('title', 'Title', 'trim|required');
			$val->set_rules('content', 'Content', 'trim|required');
			$val->set_rules('tags', 'Tags', 'trim');
			$val->set_error_delimiters('<div class="error">','</div>');
		
			if($val->run())
			{
				if( ! $result_id = $this->article_m->insert(array('title' => $val->set_value('title'), 'content' => $val->set_value('content'))))
				{					
					die('error');
				}
				else
				{
					if(strlen($val->set_value('tags')))
					{						
						$this->daptags->add_tag(trim($val->set_value('tags')), $result_id);	
					}

					redirect('example');
				}
			}
		}			

		$this->load->view('tags_add');
	}

	/**
	 * Edit article by $id and edit tags	 
	 *
	 * CRUD -> Update
	 */
	public function edit($id)
	{
		$data['article'] = $this->article_m->get_by('id', $id);
		$data['article']->tags = implode(", ", $this->daptags->get_tags_by_article($id));

		if($this->input->post())
		{
			$val = $this->form_validation;
			$val->set_rules('title', 'Title', 'trim|required');
			$val->set_rules('content', 'Content', 'trim|required');
			$val->set_rules('tags', 'Tags', 'trim');
			$val->set_error_delimiters('<div class="error">','</div>');
		
			if($val->run())
			{
				if( ! $this->article_m->update($id, array('title' => $val->set_value('title'), 'content' => $val->set_value('content'))))
				{
					die('error');
				}
				else
				{					
					if(strlen($val->set_value('tags')))
					{
						$this->daptags->edit_tag(trim($val->set_value('tags')), $id);	
					}
					else
					{						
						$this->daptags->delete_tags($id);
					}
			
					redirect('example');
				}
			}
		}			

		$this->load->view('tags_edit', $data);
	}	

	/**
	 * Set article status
	 *
	 * status = 0, 1
	 */
	public function status($id)
	{
		// Your code to change article status (0 / 1)
		 
		// Because status setting deals only with the article,
		// you have to delete tags cache in the controller
		if($this->cache)
		{
			$this->daptags->delete_cache();	
		}		

		redirect('example');
	}
	
	/**
	 * Delete article by $id
	 *
	 * CRUD -> Delete
	 */
	public function delete($id)
	{
		$this->daptags->delete_tags($id);

		// Your code to delete article..

		redirect('example');
	}

}