<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cms extends MX_Controller {
	private $data;
	function __construct()
	{
		$this->loggedUser=$this->session->userdata('loggedUser');
		$this->access_member_type='';
		if($this->loggedUser){
			$this->access_user_id=$this->loggedUser['LID'];	
			$this->access_member_type=$this->loggedUser['ACC_P_TYP'];
			$this->member_id=$this->loggedUser['MID'];
		}
		$this->data['curr_class'] = $this->router->fetch_class();
		$this->data['curr_method'] = $this->router->fetch_method();
		$this->load->model('cms_model');
		parent::__construct();
	}
	
	public function index($page){
		$arr=array(
			'select'=>'c.content_id,c.content_slug,c_n.title,c_n.content,c_n.meta_title,c_n.meta_keys,c_n.meta_description',
			'table'=>'content as c',
			'join'=>array(
			array('table'=>'content_names as c_n','on'=>"c.content_id=c_n.content_id and c_n.lang='".get_active_lang()."'",'position'=>'left'),
			),
			'where'=>array('c.content_slug'=>$page),
			'single_row'=>TRUE
		);
		$this->data['cms']=getData($arr);
		if (!$this->data['cms']) {
			redirect(base_url());
		}

		$this->data['cms_temp']=$this->cms_model->getTempContent($page);
		$this->layout->set_title($this->data['cms']->meta_title);
		$this->layout->set_meta('keywords',$this->data['cms']->meta_keys);
		$this->layout->set_meta('description',strip_tags(html_entity_decode($this->data['cms']->meta_description)));
		$this->layout->view('cms', $this->data);
	}
	
	public function help_old(){				
		$arr=array(
				'select'=>'c.cms_help_slug,c.parent_id,c_n.*',
				'table'=>'cms_help as c',
				'join'=>array(
				array('table'=>'cms_help_names as c_n','on'=>"c.cms_help_id=c_n.cms_help_id and c_n.lang='".get_active_lang()."'",'position'=>'left'),
				),
				'where'=>array('c.status'=>1, 'c.parent_id' => '0'),
				'single_row'=>FALSE
			);
		$this->data['help']=getData($arr);
		if($this->data['help']){
			foreach($this->data['help'] as $k => $help){
				$this->data['help'][$k]->child = getData(array(
					'select'=>'c.cms_help_slug,c.parent_id,c_n.*',
					'table'=>'cms_help as c',
					'join'=>array(
					array('table'=>'cms_help_names as c_n','on'=>"c.cms_help_id=c_n.cms_help_id and c_n.lang='".get_active_lang()."'",'position'=>'left'),
					),
					'where'=>array('c.status'=>1, 'c.parent_id' => $help->cms_help_id),
					'single_row'=>FALSE
				));
				
				if($this->data['help'][$k]->child){
					foreach($this->data['help'][$k]->child as $key => $help_category){
						$this->data['help'][$k]->child[$key]->articles = getData(array(
							'select'=>'c.slug,c.cms_help_id,c_n.title',
							'table'=>'cms_help_article as c',
							'join'=>array(
							array('table'=>'cms_help_article_names as c_n','on'=>"c.cms_help_article_id=c_n.cms_help_article_id and c_n.lang='".get_active_lang()."'",'position'=>'left'),
							),
							'where'=>array('c.status'=>1, 'c.cms_help_id' => $help_category->cms_help_id),
							'single_row'=>FALSE
						));
					}
				}
			}
		}
		
		//dd($this->data['help']);

		$this->layout->set_title('Help');
		$this->layout->set_meta('keywords','Help');
		$this->layout->set_meta('description','Help');
		$this->layout->view('help', $this->data);
		
	}
	public function help(){		

		$this->data['help'] = get_results(array('select' => 'b.*,a.slug', 'from' => 'cms_help_article a', 'join' => array(array('cms_help_article_names b', 'a.cms_help_article_id=b.cms_help_article_id', 'LEFT')), 'offset' => 'all', 'where' => array('b.lang' => get_active_lang(), 'a.status' => 1)));
		
		//dd($this->data['help']);

		$this->layout->set_title('Help');
		$this->layout->set_meta('keywords','Help');
		$this->layout->set_meta('description','Help');
		$this->layout->view('help', $this->data);
		
	}

	public function help_details($slug=''){
		
		$this->data['details']=getData(array(
				'select'=>'c.slug,c.cms_help_id,c_n.title,c_n.description',
				'table'=>'cms_help_article as c',
				'join'=>array(
				array('table'=>'cms_help_article_names as c_n','on'=>"c.cms_help_article_id=c_n.cms_help_article_id and c_n.lang='".get_active_lang()."'",'position'=>'left'),
				),
				'where'=>array('c.status'=>1, 'c.slug' => $slug),
				'single_row'=>TRUE
		));
		if(!$this->data['details']){
			redirect(get_link('CMShelp'));
		}
		$this->layout->set_title($this->data['details']->title);
		$this->layout->set_meta('keywords','How It Works');
		$this->layout->set_meta('description',substr(strip_tags($this->data['details']->description),0,150));
		$this->layout->view('help-details', $this->data);
	}
	public function howitworks(){
		$this->layout->set_title('How It Works');
		$this->layout->set_meta('keywords','How It Works');
		$this->layout->set_meta('description','How It Works');
		$page="how-it-works";
		$this->data['cms_temp']=$this->cms_model->getTempContent($page);
		$page="how-it-works-freelancer";
		$arr=array(
			'select'=>'c.content_id,c.content_slug,c_n.title,c_n.content,c_n.meta_title,c_n.meta_keys,c_n.meta_description',
			'table'=>'content as c',
			'join'=>array(
			array('table'=>'content_names as c_n','on'=>"c.content_id=c_n.content_id and c_n.lang='".get_active_lang()."'",'position'=>'left'),
			),
			'where'=>array('c.content_slug'=>$page),
			'single_row'=>TRUE
		);
		$this->data['cms_freelancer']=getData($arr);
		$this->data['how_it_works_freelancer']=$this->cms_model->getTempContent($page);
		$page="how-it-works-employer";
		$arr=array(
			'select'=>'c.content_id,c.content_slug,c_n.title,c_n.content,c_n.meta_title,c_n.meta_keys,c_n.meta_description',
			'table'=>'content as c',
			'join'=>array(
			array('table'=>'content_names as c_n','on'=>"c.content_id=c_n.content_id and c_n.lang='".get_active_lang()."'",'position'=>'left'),
			),
			'where'=>array('c.content_slug'=>$page),
			'single_row'=>TRUE
		);
		$this->data['cms_employer']=getData($arr);
		$this->data['how_it_works_employer']=$this->cms_model->getTempContent($page);
		$this->layout->view('howitwork', $this->data);
	}
	public function knowledgebank($page='knowledge-bank'){
		$arr=array(
				'select'=>'c.content_id,c.content_slug,c_n.title,c_n.content,c_n.meta_title,c_n.meta_keys,c_n.meta_description',
				'table'=>'content as c',
				'join'=>array(
				array('table'=>'content_names as c_n','on'=>"c.content_id=c_n.content_id and c_n.lang='".get_active_lang()."'",'posiotion'=>'left'),
				),
				'where'=>array('c.content_slug'=>$page),
				'single_row'=>TRUE
			);
		$this->data['cms']=getData($arr);
		$this->data['seo_tags']=array(
		'meta_title'=>$this->data['cms']->meta_title,
		'meta_key'=>$this->data['cms']->meta_keys,
		'meta_description'=>strip_tags(html_entity_decode($this->data['cms']->meta_description)),
		'seo_images'=>array(),
		);
		$this->data['load_css']=array('knowledge_base.css');
		$templateLayout=array('view'=>'knowledgebank','type'=>'default','buffer'=>FALSE,'theme'=>'');
		load_template($templateLayout,$this->data);
	}
	public function support(){
		//redirect('https://meshhelp.freshdesk.com');
		exit();
	}
	public function contactus(){
		$this->layout->set_title('Contact Us');
		$this->layout->set_meta('keywords','Contact Us');
		$this->layout->set_meta('description','Contact Us');
		$this->layout->view('contactus', $this->data);
	}
	
	public function submit_contact(){
		$this->load->library('form_validation');
		
		$this->form_validation->set_rules('inquiry', 'enquiry', 'required');
		$this->form_validation->set_rules('email', 'email', 'required|valid_email');
		$this->form_validation->set_rules('description', 'description', 'required');
		
		if($this->form_validation->run()){
			$attachment = '';
			
			if(!empty($_FILES['attachment']['name'])){
				$upload_dir = UPLOAD_PATH.'contact-attachment/';
				if(!is_dir($upload_dir)){
					mkdir($upload_dir);
				}
				$config['upload_path']          = $upload_dir;
				$config['allowed_types']        = 'gif|jpg|png|jpeg|pdf|zip';
				$config['file_ext_tolower']        = TRUE;
				$config['encrypt_name']        = TRUE;
				
				$this->load->library('upload', $config);
				
				if(! $this->upload->do_upload('attachment')){
					$json['error_html'] = '<div class="alert alert-danger" role="alert"><h4>The following errors are found </h4> '.$this->upload->display_errors().'</div>';
					$json['status'] = 0;
					echo json_encode($json);
					die;
				}else{
					$attachment = $this->upload->data('file_name');
					
				}
			}
			
			$this->data = array(
				'inquiry' => post('inquiry'),
				'email' => post('email'),
				'description' => post('description'),
				'attachment' => $attachment,
				'date' => date('Y-m-d'),
			);
			$this->db->insert('contact', $this->data);
			
			$template = 'admin-user-contact-request';
			
			$admin_contact_url = ADMIN_URL.'contact/list_record';
			$category_name = post('inquiry');

			$this->data_parse = array(
				'EMAIL' => $this->data['email'],
				'ENQUIRY_FOR' => $category_name, 
				'MESSAGE' => $this->data['description'], 
			);
			
			$this->admin_notification_model->parse($template, $this->data_parse, 'contact/list_record');
			$this->data_parse['VIEW_URL']=$admin_contact_url;
			SendMail(get_setting('admin_email'),$template,$this->data_parse);
			
			$json['status'] = 1;
			$json['success_html'] = '<div class="alert alert-success" role="alert"><h4>Thank you for contacting us. </h4> <div>We will contact you soon. </div></div>';
		}else{
			$json['error_html'] = '<div class="alert alert-danger" role="alert"><h4>The following errors are found </h4> '.validation_errors('<div>', '</div>').'</div>';
			$json['status'] = 0;
		}
		
		echo json_encode($json);
		die;
		
	}
}
