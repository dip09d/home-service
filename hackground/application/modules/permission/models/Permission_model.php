<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Permission_model extends CI_Model {
	
	private $menu_codes = array();
	
    public function __construct() {
		$this->menu_codes = $this->_getUserMenuCode();
        return parent::__construct();
    }
	
	
	/* -------------- [NEW PERMISSION MODEL] ---- */
	
	public function addMenu($data=array()){
		
		$data['url'] = trim($data['url'], '/');
		$content = array(
			'name' => !empty($data['name']) ? $data['name'] : '',
			'title' => !empty($data['name']) ? $data['name'] : '',
			'url' => !empty($data['url']) ? $data['url'] : '',
			'parent_id' => !empty($data['parent_id']) ? $data['parent_id'] : 0,
			'menu_desc' => !empty($data['menu_desc']) ? $data['menu_desc'] : '',
			'style_class' => !empty($data['style_class']) ? $data['style_class'] : '',
			'status' => !empty($data['status']) ? $data['status'] : '',
			'show_left' => !empty($data['show_left']) ? $data['show_left'] : '',
			'ord' => !empty($data['ord']) ? $data['ord'] : '',
			'action' => $data['action'],
		
		);
		
		$this->db->insert('adminmenu', $content);
		$ins_id = $this->db->insert_id();
		$menu_code = 'MEN';
		$menu_code .= str_pad($ins_id, 4, '0', STR_PAD_LEFT);
		if(!empty($data['action'])){
			$menu_code .= '_'.$data['action'];
		}
		
		return $this->db->where('id', $ins_id)->update('adminmenu', array('menu_code' => $menu_code));
		
	}
	
	public function updateMenu($data=array(), $id=''){
		$data['url'] = trim($data['url'], '/');
		$content = array(
			'name' => !empty($data['name']) ? $data['name'] : '',
			'title' => !empty($data['name']) ? $data['name'] : '',
			'url' => !empty($data['url']) ? $data['url'] : '',
			'menu_desc' => !empty($data['menu_desc']) ? $data['menu_desc'] : '',
			'style_class' => !empty($data['style_class']) ? $data['style_class'] : '',
			'status' => !empty($data['status']) ? $data['status'] : '',
			'show_left' => !empty($data['show_left']) ? $data['show_left'] : '',
			'ord' => !empty($data['ord']) ? $data['ord'] : '',
		);
		
		return $this->db->where('id', $id)->update('adminmenu', $content);
	}
	
	public function get_menu_for_list(){
		$permission = array();
		$permission = get_results(array('select' => '*', 'from' => 'adminmenu', 'where' => array('parent_id' => 0), 'offset' => 'all'));
		if(count($permission) > 0){
			foreach($permission as $k => $v){
				$permission[$k]['child'] = get_results(array('select' => '*', 'from' => 'adminmenu', 'where' => array('parent_id' => $v['id']), 'offset' => 'all'));
			}
		}
		return $permission;
	}
	
	public function get_active_menu_for_list(){
		$permission = array();
		$permission = get_results(array('select' => '*', 'from' => 'adminmenu', 'where' => array('parent_id' => 0, 'status' => ACTIVE_STATUS), 'offset' => 'all'));
		if(count($permission) > 0){
			foreach($permission as $k => $v){
				$permission[$k]['child'] = get_results(array('select' => '*', 'from' => 'adminmenu', 'where' => array('parent_id' => $v['id'] , 'status' => ACTIVE_STATUS), 'offset' => 'all'));
			}
		}
		return $permission;
	}
	
	
	public function getUserMenu(){
		$role = get_admin_role();
		/* $role = 0; */
		$this->db->select('m.*')
			->from('adminmenu m')
			->join('adminmenu_permission a_p', 'a_p.menu_id=m.id', 'LEFT');
		
		if($role > 0){
			$this->db->where('a_p.role_id', $role);
		}
		
		$this->db->where('m.parent_id', 0);
		$this->db->where('m.status', ACTIVE_STATUS);
		$this->db->where('m.show_left', 'Y');
		$this->db->order_by('m.ord','asc');
		$this->db->group_by('m.id');
		
		$result = $this->db->get()->result_array();
		if($result){
			foreach($result as $k => $v){
				$this->db->select('m.*')
					->from('adminmenu m')
					->join('adminmenu_permission a_p', 'a_p.menu_id=m.id', 'LEFT');
				
				if($role > 0){
					$this->db->where('a_p.role_id', $role);
				}
				$this->db->where('m.parent_id', $v['id']);
				$this->db->where('m.status', ACTIVE_STATUS);
				$this->db->where('m.show_left', 'Y');
				$this->db->order_by('m.ord','asc');
				$this->db->group_by('m.id');
				$result[$k]['child'] = $this->db->get()->result_array();
			}
		}
		return $result;
	}
	
	public function update_permission($role_id='', $menus = array()){
		if($role_id){
			$this->db->where('role_id', $role_id)->delete('adminmenu_permission');
			if($menus){
				foreach($menus as $k => $v){
					$v_arr = explode('|', $v);
					$menu_id = $v_arr[1];
					$menu_code = $v_arr[0];
					$ins = array(
						'menu_id' => $menu_id,
						'menu_code' => $menu_code,
						'role_id' => $role_id,
					);
					$this->db->insert('adminmenu_permission', $ins);
				}
			}
			
		}
		
		return TRUE;
	}
	
	private function _getUserMenuCode(){
		$role = get_admin_role();
		
		$this->db->select('m.id,m.menu_code,m.url')
			->from('adminmenu m')
			->join('adminmenu_permission a_p', 'a_p.menu_id=m.id', 'LEFT');
		
		if($role > 0){
			$this->db->where('a_p.role_id', $role);
		}
		
		$this->db->where('m.status', ACTIVE_STATUS);
		$this->db->group_by('m.id');
		
		$result = $this->db->get()->result_array();
		$codes = array();
		
		if($result){
			
			foreach($result as $k => $v){
				$codes[] = $v['menu_code'];
			}
		}
		
		
		return $codes;
		
	}
	
	public function checkPermission($code='', $show_error=TRUE){
		if(in_array($code, $this->menu_codes)){
			return TRUE;
		}else{
			if($show_error){
				show_error('You don\'t have permission to access this page', 403, 'Permission Denied');
			}
		}
		return FALSE;
		
	}
	
	public function getEditBtn($code='', $link=''){
		$btn = null;
		
		if(in_array($code, $this->menu_codes)){
			return $this->_generateBtn($link, 'edit');
		}
		
		return $btn;

	}
	
	public function getDeleteBtn($code='', $link=''){
		$btn = null;
		
		if(in_array($code, $this->menu_codes)){
			return $this->_generateBtn($link, 'delete');
		}
		
		return $btn;

	}
	
	private function _generateBtn($link='', $type=''){
		$html = null;
		$btn_icons = array(
			'edit' => '<i class="fa fa-edit fa-lg"></i>',
			'delete' => '<i class="icon-feather-trash fa-lg"></i>',
		);
		if($type == 'edit'){
			$html = '<a href="'.$link.'"> '. $btn_icons[$type]. '</a>';
		}else if($type == 'delete'){
			$html = '<a href="'.$link.'" onclick="return confirm(\' Are you sure to delete this record ? \')"> '. $btn_icons[$type]. '</a>';
		}
		
		return $html;
		
	}
	
	public function getlink($code='',$link='', $text='',$attr=''){
		if($this->checkPermission($code, FALSE)){
			return anchor($link,$text,$attr);
		}
		
	}
	
}
