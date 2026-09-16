<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/*  @author             Venkatesh bishu
 *   This function filter any input data
 *   @param                 Description 
 *  @ data                  The data to filter
 *  
 */

if (!function_exists('filter_data')) {

    function filter_data($data) {
        if (is_array($data)) {
            foreach ($data as $k => $v) {
                if(!is_array($v)){
                     $data[$k] = trim(htmlentities($v));
                }
            }
            return $data;
        } else {
            return trim(htmlentities($data));
        }
    }

}

/*
 *  @author                 Venkatesh bishu
 * This function will print everything and die the rest of the codes
 * 
 */


 if (!function_exists('filter_decode')) {

    function filter_decode($data) {
        if (is_array($data)) {
            foreach ($data as $k => $v) {
                if(!is_array($v)){
                     $data[$k] = html_entity_decode($v);
                }
            }
            return $data;
        } else {
            return html_entity_decode($data);
        }
    }

}


if (!function_exists('get_print')) {

    function get_print($arr, $die = true) {
        echo "<pre>";
        print_r($arr);
        echo "</pre>";
        if ($die) {
            die;
        }
    }

}

if (!function_exists('getField')) {
    /*
     *  @author              Venkatesh bishu
     *  This function will return a single column from the database
     *  
     *  Parameter                   Description
     *  $column                     The name of column which you want to select [String]
     *  $table                      the name of the table from which you want to fetch the condition [string]
     *  $con_where                  the where condition your want to apply
     *  $con_value                  The value of the where condition
     *  @Return                     The value of that column [String]
     * 
     *
     */

    function getField($column = '', $table = '', $where = '', $val = '') {
        $ci = &get_instance();
        $res = $ci->db->select($column)
                ->from($table)
                ->where($where, $val)
                ->get()
                ->row_array();

        if (is_array($res)) {
            if (isset($res[$column])) {
                return $res[$column];
            }
        }
        return null;
    }

}


if(!function_exists('get_row')){
	/*
		Date : 03/08/2016
		
		This function fetch a single row from database in array or json format 
		@param1 $q				Array of query format
		@param2	$format			Result format
		@param3 $for_list		Whether the query is used for list or for count purpose
		@return 				Query Result either in json or array format
		
		Author					Venkatesh bishu
		Note :					If you are using multiple table joining use multidimensional array in join key Ex. $q['join'] = array(array(table , base , jointype) , array(table , base , jointype))
		Query array format
		------------------
			* Same as get_results
			
	*/
	function get_row($q=array() , $format='array'){
		
		if(empty($q) OR empty($q['from']) OR empty($q['select'])){
			die("Enter a valid paramenter");
		}
		
		$ci = &get_instance();
		
		$result = '';
		
		$ci->db->select($q['select']);
		$ci->db->from($q['from']);
		if(!empty($q['join']) && is_array($q['join'])){
			foreach($q['join'] as $k => $v){
				if(empty($q['join'][$k][2])){
					$q['join'][$k][2] = 'INNER';
				}
				$ci->db->join($q['join'][$k][0] , $q['join'][$k][1] , $q['join'][$k][2]);
			}
		}
		
		
		if(!empty($q['where'])){
			$ci->db->where($q['where']);
		}
		
		$result = $ci->db->get()->row_array();
		$format = strtolower($format);
		if($format == 'json'){
			return json_encode($result);
		}
		if($format == 'object'){
			return (object) $result;
		}
		return $result;
		
	}
}

if(!function_exists('get_results')){
	/*
		Date : 03/08/2016
		
		This function fetch results from database in array or json format 
		@param1 $q				Array of query format
		@param2	$format			Result format
		@param3 $for_list		Whether the query is used for list or for count purpose
		@return 				Query Result either in json or array format
		
		Author					Venkatesh bishu
		Note :					If you are using multiple table joining use multidimensional array in join key Ex. $q['join'] = array(array(table , base , jointype) , array(table , base , jointype))
		Query array format
		------------------
			$q = array(
			'select' => 'a.* , b.category as n_category ,  c.type as n_type',
			'from' => 'news a',
			'join' => array(array('newscategory b' , 'b.id=a.category' , 'INNER'),array('newstype c' , 'c.id=a.type' , 'INNER')),
			'where' => array('a.id' => '1'),
			'limit' => 0,
			'offset' => 10
		);
			
	*/
	function get_results($q=array() , $format='array' , $for_list = TRUE){
		
		if(empty($q) OR empty($q['from']) OR empty($q['select'])){
			die("Enter a valid parameter");
		}
		
		$ci = &get_instance();
		$limit = 0;
		$offset = 10;
		$result = '';
		
		if(!empty($q['limit'])){
			$limit = $q['limit'];
		}
		
		if(!empty($q['offset'])){
			$offset = $q['offset'];
		}
		
		$ci->db->select($q['select']);
		$ci->db->from($q['from']);
		
		if(!empty($q['join']) AND is_array($q['join'])){
			
			// $q['join'] = array(array('table2' , 'col=val AND col=val' , 'JOINTYPE'),array('table2' , 'col=val AND col=val' , 'JOINTYPE'));
			foreach($q['join'] as $k => $v){
					if(empty($q['join'][$k][2])){
						$q['join'][$k][2] = 'INNER';
					}
					$ci->db->join($q['join'][$k][0] , $q['join'][$k][1] , $q['join'][$k][2]);
			}
		}
		
		if(!empty($q['where'])){
			$ci->db->where($q['where']);
		}
		
		if(!empty($q['group_by'])){
			$ci->db->group_by($q['group_by']);
		}
		
		if($for_list){
			if(!empty($q['order_by'])){
				$ci->db->order_by($q['order_by'][0] , $q['order_by'][1]);
			}
			if($offset != 'all'){
				$result = $ci->db->limit($offset , $limit)->get()->result_array();
			}else{
				$result = $ci->db->get()->result_array();
			}
			$format = strtolower($format);
			if($format == 'json'){
				return json_encode($result);
			}
			if($format == 'object'){
				return (object) $result;
			}
			return $result;
		}else{
			$result = $ci->db->get()->num_rows();
			return $result;
		}
	}
}



if (!function_exists('count_results')) {
    /*
     *  @author                     Venkatesh bishu
     *  This function will count all the active records return by the query made against database
     *  
     *  @param                          Description
     *  $table_name                     The name of the table [String]
     *  @condition                      The condition you want to make against database[array]
     *  
     *  @Return                         Number of active records made from the above query [intger]                    
     */

    function count_results($table = '', $condition = array(),$col='*') {
        $ci = &get_instance();
        $ci->db->select($col)
                ->from($table);
        if (!empty($condition)) {
            $ci->db->where($condition);
        }

        $res = $ci->db->count_all_results();

        return $res;
    }

}

if (!function_exists('insert_record')) {
    /*
     *  @author                 Venkatesh bishu
     *  This function will insert data in the database
     *   @param                             Description
     * 
     *   $table                               The name of table in which you want to insert data[String]
     *   $data                               The data you want to insert [Array]
     *  
     *  $is_return_key                        Is the function return last insert key default is no
     * 
     *  @return                             Boolean [in case of simple entry of data] Interger [In case of returning the last inserted key]
     */

    function insert_record($table = '', $data=array(), $is_return_key = false) {
        $ci = &get_instance();
        if ($table == 'wallet_transaction' || $table == 'pref_wallet_transaction') {
            if (empty($data['wallet_transaction_type_id'])) {
                $data['wallet_transaction_type_id'] = 13; // default fallback transaction type
            }
            if (empty($data['transaction_date'])) {
                $data['transaction_date'] = date('Y-m-d H:i:s');
            }
        }
        if ($is_return_key) {
            $ci->db->insert($table, $data);
            $res = $ci->db->insert_id();
            return $res;
        } else {
            return $ci->db->insert($table, $data);
        }
    }

}

if (!function_exists('update_record')) {
    /*
     *  @author                 Venkatesh bishu
     *  This function will update the record in the database
     *  
     *  @param                  Description
     *  $table                  The name of the table you want to update
     *  $data                   The data to update
     *  $condition              The condition for updating data
     * 
     *  Return                  Boolean
     */

    function update_record($table = '', $data = array(), $condition = array()) {
        $ci = &get_instance($data);
		$ci->db->set($data);
        $ci->db->where($condition);
		return $ci->db->update($table);
    }

}


if(!function_exists('update')){
	/*
		Date : 03/08/2016
		
		This function fetch a single row from database in array or json format 
		@param1 $query			Array of query format
		@return 				Void 
		
		Author					Venkatesh bishu
		
	*/
	
	function update($query=array()){
		if(empty($query['data']) OR empty($query['table'])){
			die("Enter a valid parameter");
		}
		
		$ci = &get_instance();
		
		if(!empty($query['where'])){
			$ci->db->where($query['where']);
		}
		
		return $ci->db->update($query['table'] , $query['data']);
		
	}
}




if(!function_exists('delete')){
	/*
		Date : 03/08/2016
		
		This function fetch a single row from database in array or json format 
		@param1 $query			Array of query format
		@return 				Void
		
		Author					Venkatesh bishu
		
	*/
	function delete($query=array()){
		if(empty($query['table']) OR empty($query['where'])){
			die("Enter a valid parameter");
		}
		
		$ci = &get_instance();
		
		$ci->db->where($query['where']);
		return $ci->db->delete($query['table']);
		
	}
}


if(!function_exists('insert')){
	/*
		Date : 03/08/2016
		
		This function fetch a single row from database in array or json format 
		@param1 $query			Array of query format
		@return 				Void
		
		Author					Venkatesh bishu
		
	*/
	function insert($query=array() , $insert_id=FALSE){
		if(empty($query['table']) OR empty($query['data'])){
			die("Enter a valid parameter");
		}
		
		$ci = &get_instance();
		
		$ins = $ci->db->insert($query['table'] , $query['data']);
		if($ins){
			if($insert_id){
				return $ci->db->insert_id();
			}
			return TRUE;
		}else{
			return FALSE;
		}
		
	}
}


if(!function_exists('query')){
	/*
		Date : 03/08/2016
		
		This function fetch result from a sql string and return array
		@param1 $query			String of query
		@param2 $format			The return data format
		
		@return 				Array result
		
		Author					Venkatesh bishu
	*/
	
	function query($sql='' , $format='array' , $for_list=TRUE){
		if(empty($sql)){
			die("Enter a valid paramter");
		}
		$ci = &get_instance();
		$query = $ci->db->query($sql);
		if($for_list){
			$result = $query->result_array();
			$format = strtolower($format);
			if($format == 'object'){
				return (object) $result;
			}else if($format == 'json'){
				return json_encode($result);
			}else{
				return $result;
			}
		}else{
			return $query->num_rows();
		}
		
	}
}


if (!function_exists('delete_record')) {
    /*
     *  @author                 Venkatesh bishu
     *  This function will delete record from the database table
     *  @param                      Description
     *  $table                      The name of table you want to delete the record from 
     *  $condition                  The condition you want to make against database
     *  
     * Return                       Boolean
     */

    function delete_record($table = '', $condition = array()) {
        $ci = &get_instance();
        $ci->db->where($condition);
        return $ci->db->delete($table);
    }

}

if (!function_exists('get_last_query')) {
    /*
     *  @author                 Venkatesh bishu
     *  This function will return the last database query
     */

    function get_last_query() {
        $ci = &get_instance();
        return $ci->db->last_query();
    }

}


if (!function_exists('get_affected_rows')) {
    /*
     *  @author                 Venkatesh bishu
     *  This function will return all the affected rows when a query run against database
     */

    function get_affected_rows() {
        $ci = &get_instance();
        return $ci->db->affected_rows();
    }

}

if (!function_exists('simple_query')) {
    /*
     *  @param              Venkatesh bishu
     *  This function will make a simple query 
     * Return               Array of generated result
     */

    function simple_query($sql = '', $for_listing = TRUE) {
        if (empty($sql)) {
            return false;
        }

        $ci = &get_instance();
        $q = $ci->db->query($sql);
        if ($for_listing) {
            return $q->result_array();
        } else {
            return $q->num_rows();
        }
    }

}

if (!function_exists('get_last_row')) {
    /*
     *  @author                 Venkatesh bishu
     *  This function will return the last row from an active record
     * 
     *  @param                 `Description
     *  $table                  the name of table[string]
     *  $condition              the condition you want to make against databas[array]
     * 
     *  Return                  Single array
     */

    function get_last_row($table = '', $condition = array(), $col = '*') {
        $ci = &get_instance();
        $ci->db->select($col)
                ->from($table);
        if (!empty($condition)) {
            $ci->db->where($condition);
        }
        $res = $ci->db->get()
                ->last_row('array');

        return $res;
    }

}

if (!function_exists('get_dbprefix')) {
    /*
     *  This function will return the db prefix of a given table
     *  @param              the tablename
     *  retrun              The table name with the db prefix
     */

    function get_dbprefix($table = '') {
        $ci = &get_instance();
        $result = $ci->db->dbprefix($table);
        return $result;
    }

}


if (!function_exists('today_time')) {
    /*
     *  @author             Venkatesh bishu
     *  This function will return the today timestamp 
     */

    function today_time($type = 'timestamp') {
        $result = null;
        switch ($type) {
            case 'timestamp':
                $result = date('Y-m-d h:i:s');
                break;

            case 'date':
                $result = date('Y-m-d');
                break;

            case 'time':
                $result = date('h:i:s');
                break;

            case 'year':
                $result = date('Y');
        }

        return $result;
    }

}


if (!function_exists('get_time')) {
    /*
     *  @author                 Venkatesh bishu
     *  This function will compute the difference from today to past provided date
     *  if no any parameter are passed through this function then on that condition the 
     *  function will return a simple timestamp
     * 
     *  @param                  Description
     *  $date                   The previous timestamp [string]
     *  @Return                 The difference from the provided timestamp and current day
     */

    function get_time($date = '') {
        if (empty($date)) {
            return date("Y-m-d h:i:s");
        }

        $prev_time = strtotime($date);
        $curr_tme = time();
		if(($curr_tme - $prev_time) < 60*1000){
			$return_date = round((($curr_tme - $prev_time)/1000)) . " seconds ago";
		}else if((($curr_tme -  $prev_time) > 60*1000) && (($curr_tme -  $prev_time) < 60*60*1000)){
			$return_date = round((($curr_tme - $prev_time)/(60*1000))) . " minute ago";
		}else if(($curr_tme -  $prev_time) > 60*60*1000 && ($curr_tme - $prev_time) < 60*60*24*1000){
			$return_date = round((($curr_tme - $prev_time)/(60*60*1000))) ." hour ago";
		}else if(($curr_tme - $prev_time) > 60*60*24*1000 && ($curr_tme -  $prev_time) < 60*60*24*30*1000){
			$return_date = round((($curr_tme - $prev_time)/(60*60*24*1000))) . " days ago";
		}else{
			$return_date= date('d M , Y', strtotime($date));
		}
		return $return_date;
	}

}
if (!function_exists('age')) {
    /*
     *  @author                 Venkatesh bishu
     *  This function will compute the difference from today to past provided date
     *  if no any parameter are passed through this function then on that condition the 
     *  function will return a simple timestamp
     * 
     *  @param                  Description
     *  $birthday                   The birthday of the user
     *  @Return                 	age of the user
*/

	function age($birthday){
		 list($year, $month, $date) = explode("-", $birthday);
		 $year_diff  = date("Y") - $year;
		 $month_diff = date("m") - $month;
		 $day_diff   = date("d") - $day;
		 if ($day_diff < 0 && $month_diff==0) $year_diff--;
		 if ($day_diff < 0 && $month_diff < 0) $year_diff--;
		 return $year_diff;
	}

}



if (!function_exists('upload_file')) {
    /*
     *  @author                         Venkatesh bishu
     *  This function will upload any type of file
     *  
     *  @param                          Description
     *  $field_name                     the name of the field
     *  $filepath                       the path of the file where you want to upload the file
     *  $allowed_type                   The file type you want to upload
     *  $create_thumb					Whether you want to create the thumb image or not (bool)
     */

    function upload_file($field_name = 'userfile', $filepath = '', $create_thumb = TRUE , $allowed_type = 'gif|jpg|png|jpeg|gif') {
        $files = null;
        $ci = &get_instance();
       
		if (!is_dir('./assets/uploads')) {
            mkdir('./assets/uploads');
        }
		
        if (!is_dir('./assets/uploads/' . $filepath)) {
            mkdir('./assets/uploads/' . $filepath);
        }
        if (!empty($field_name) && is_array($_FILES[$field_name]['name'])) {
			if($create_thumb){
				$rconfig['image_library'] = 'gd2';
				$rconfig['create_thumb'] = TRUE;
				$rconfig['maintain_ratio'] = TRUE;
				$rconfig['width'] = 150;
				$rconfig['height'] = 150;
				$ci->load->library('image_lib');
			}
            for ($i = 0; $i < count($_FILES[$field_name]['name']); $i++) {
                $_FILES[$field_name . $i] = array(
                    'name' => $_FILES[$field_name]['name'][$i],
                    'type' => $_FILES[$field_name]['type'][$i],
                    'tmp_name' => $_FILES[$field_name]['tmp_name'][$i],
                    'error' => $_FILES[$field_name]['error'][$i],
                    'size' => $_FILES[$field_name]['size'][$i]
                );

                $config['upload_path'] = "./assets/uploads/$filepath";
                $config['allowed_types'] = $allowed_type;
                $config['file_name'] = md5(date('y-m-d h:i:s'));
                $config['file_ext_tolower'] = TRUE;

                $ci->load->library('upload');
                $ci->upload->initialize($config); // Initializing the config file for the upload library

                $res = $ci->upload->do_upload($field_name . $i);
                if ($res) {
                    $files[] = $ci->upload->data('file_name');
					if($create_thumb){
						$rconfig['source_image'] = "./assets/uploads/$filepath/{$files[$i]}";
						$rconfig['new_image'] = "./assets/uploads/$filepath"."_thumb/{$files[$i]}";
						$ci->image_lib->initialize($rconfig);
						if (!$ci->image_lib->resize()) {
							echo $ci->image_lib->display_errors();
						}
						$ci->image_lib->clear();
					}
                }
            }

            return $files;
        }

        if (!empty($field_name) AND $_FILES[$field_name]) {
            $config['upload_path'] = "./assets/uploads/$filepath";
            $config['allowed_types'] = $allowed_type;
            $config['encrypt_name'] = TRUE;
            $config['file_ext_tolower'] = TRUE;

            $ci->load->library('upload');
            $ci->upload->initialize($config); // Initialising the config file for the upload library
            $res = $ci->upload->do_upload($field_name);
            if ($res) {
                $files = $ci->upload->data('file_name');
				if($create_thumb){
					
					 if (!is_dir('./assets/uploads/' . $filepath.'/_thumb')) {
						mkdir('./assets/uploads/' . $filepath.'/_thumb');
					}
					
					$rconfig['image_library'] = 'gd2';
					$rconfig['source_image'] = "./assets/uploads/$filepath/$files";
					$rconfig['maintain_ratio'] = TRUE;
					$rconfig['new_image'] = "./assets/uploads/$filepath/_thumb" . "/$files";
					
					$rconfig['width'] = 150;
					$rconfig['height'] = 150;
					$ci->load->library('image_lib', $rconfig);
					$ci->image_lib->resize();
					$ci->image_lib->clear();
				}
            } else {
                $files = $ci->upload->display_errors();
            }

            return $files;
        }
    }

}



if (!function_exists('upload_file_tmp')) {
    /*
     *  @author                         Venkatesh bishu
     *  This function will upload any type of file
     *  
     *  @param                          Description
     *  $field_name                     the name of the field
     *  $filepath                       the path of the file where you want to upload the file
     *  $allowed_type                   The file type you want to upload
     *  $create_thumb					Whether you want to create the thumb image or not (bool)
     */

    function upload_file_tmp($field_name = 'userfile', $allowed_type = 'gif|jpg|png|jpeg|gif') {

        $files = null;
        $ci = &get_instance();
       
	   
		if (!is_dir('./assets/tmp_uploads')) {
            mkdir('./assets/tmp_uploads');
        }
		
		if (!empty($field_name) AND $_FILES[$field_name]) {
            $config['upload_path'] = "./assets/tmp_uploads";
            $config['allowed_types'] = $allowed_type;
            $config['encrypt_name'] = TRUE;
            $config['file_ext_tolower'] = TRUE;

            $ci->load->library('upload');
            $ci->upload->initialize($config); // Initialising the config file for the upload library
            $res = $ci->upload->do_upload($field_name);
            if ($res) {
                $files['name'] = $ci->upload->data('file_name');	
                $files['data'] = $ci->upload->data();
				$files['status'] = 1;
            } else {
                $files['error'] = $ci->upload->display_errors();
				$files['status'] = 0;
				
            }

            return $files;
        }
    }

}

if (!function_exists('move_tmp_file')) {
    /*
     *  @author                         Venkatesh bishu
     *  This function move tmp file to a destination file
     *  
     *  @param                          Description
     *  $filename                     the name of file to be moved
     *  $dest                       the destination folder where file is going to move
     */

    function move_tmp_file($filename = '', $dest='') {
        if(!$filename || !$dest){
			die("Invalid Parameter");
		}
		
		if(!is_dir('./assets/uploads/'.$dest)){
			mkdir('./assets/uploads/'.$dest);
		}
		
		return rename('./assets/tmp_uploads/'.$filename, './assets/uploads/'.$dest.'/'.$filename); // moving file to user folder
    }

}


if (!function_exists('get_session')) {
    /*
     *  @author                 Venkatesh bishu
     *  this function will return the session data by passing key to it
     *  @param                  Session Key
     */

    function get_session($key = '') {
        $ci = &get_instance();
        $ci->load->library('session');
        $data = $ci->session->userdata($key);
        return $data;
    }

}

if (!function_exists('set_session')) {
    /*
     *  @author             Venkatesh bishu
     *  This function will set the session data
     *  @param              key
     *  @param              value
     */

    function set_session($key = '', $value = '') {
        $ci = &get_instance();
        $ci->load->library('session');
        $ci->session->set_userdata($key, $value);
    }

}

if (!function_exists('delete_session')) {
    /*
     *  @param              Venkatesh bishu
     *  This function will delete the session data
     *  @param              Key
     */

    function delete_session($key = '') {
        $ci = &get_instance();
        $ci->load->library('session');
        $ci->session->unset_userdata($key);
    }

}

if (!function_exists('destroy_session')) {
    /*
     *  @author             Venkatesh bishu
     *  This function will destroy all the sessions data
     */

    function destroy_session() {
        $ci = &get_instance();
        $ci->load->library('session');
        $ci->session->sess_destroy();
    }

}


if(!function_exists('load_helper')){
	/*
		Date : 03/08/2016
		
		This function will load a helper
		@param 					Helper Name
		@return 				Void
		
		Author					Venkatesh bishu
	*/
	
	function load_helper($helper=''){
		$ci = &get_instance();
		$ci->load->helper($helper);
	}
}

if(!function_exists('load_class')){
	/*
		Date : 03/08/2016
		
		This function will load a library/class
		@param 					Class Name
		@return 				Void
		
		Author					Venkatesh bishu
	*/
	
	function load_class($class=''){
		$ci = &get_instance();
		$ci->load->library($class);
	}
}

if(!function_exists('load_config')){
	/*
		Date : 03/08/2016
		
		This function will load config file 
		@param 					Config Name
		@return 				Void
		
		Author					Venkatesh bishu
	*/
	
	function load_config($config=''){
		$ci = &get_instance();
		$ci->load->config($config);
	}
}

if(!function_exists('post')){
	/*
		Date : 03/08/2016
		
		This function will return all the $_POST value
		@param1 $key			The $_POST key
		@return 				$_POST array or single value
		
		Author					Venkatesh bishu
		
	*/
	function post($key=''){
		$ci = &get_instance();
		if($key == ''){
			return $ci->input->post();
		}else{
			return $ci->input->post($key);
		}
		
	}
}

if(!function_exists('get')){
	/*
		Date : 05/08/2016
		
		This function will return all the $_GET value
		@param1 $key			The $_GET key
		@return 				$_GET array or single value
		
		Author					Venkatesh bishu
		
	*/
	function get($key=''){
		$ci = &get_instance();
		if($key == ''){
			return $ci->input->get();
		}else{
			return $ci->input->get($key);
		}
		
	}
}



if(!function_exists('getIP')){
	/*
		Date : 05/08/2016
		
		This function fetch the user ip address
		
		@return 				IP Address
		
		Author					Venkatesh bishu
		
	*/
	function getIP(){
		$ip = '';
		if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
			$ip = $_SERVER['HTTP_CLIENT_IP'];
		} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		} else {
			$ip = $_SERVER['REMOTE_ADDR'];
		}
		return $ip;
	}
}



if(!function_exists('get_ip_info')){
	/*
		Date : 05/08/2016
		
		This function return array of ip information
		$param ip				The ip address
		@return 				Ip details Array
		
		Author					Venkatesh bishu
		
	*/
	
	function get_ip_info($ip=''){
		if($ip == ''){
			return FALSE;
		}
		$details = json_decode(file_get_contents("http://ipinfo.io/{$ip}/json"));
		return $details;
	}
}

if (!function_exists('check_user_log')) {
    /*
     *  this function check where the user is login or not
     *  If the user is not login then this function will redirect the user to the login page
     */

    function check_user_log() {
        $ci = &get_instance();
        $ci->load->library('session');
        $ci->load->helper('url');
        $flag = FALSE;
        $curr_url = base_url(uri_string());
        $get = $ci->input->get();
        if($get){
            $get = "?".http_build_query($get);
            $curr_url .= $get;
        }
        if (!$ci->session->has_userdata('user_id')) {
            $flag = TRUE;
        }
        if ($flag) {
			if($ci->input->is_ajax_request()){
				$location = base_url('login/?ref='.urlencode($curr_url));
				//echo '<script type="text/javascript">window.location.href ="'.$location.'";</script>';
			}else{
				redirect(base_url('login/?ref='.urlencode($curr_url)));
			}
        }
    }

}

if (!function_exists('is_login_user')) {
    /*
     *  This function will check whether the user is login or not and it will return true or false
     */

    function is_login_user() {
        $ci = &get_instance();
        $ci->load->library('session');
        $status = null;
        if ($ci->session->has_userdata('loggedUser')) {
            $status = TRUE;
        } else {
            $status = FALSE;
        }
        return $status;
    }

}

if (!function_exists('delete_file')) {
    /*
     *  This function will delete the file from the assets folder in a specified directory
     *  $file       The name of file to delete
     *  $path       The directory where the file will found
     *  $del_thumb  Whether to delete the thumb file also [true/false]
     */

    function delete_file($file = '', $path = '', $del_thumb = TRUE) {
        $fname = "assets/uploads/" . $path . "/" . $file;
        if (file_exists($fname)) {
            $del = unlink($fname);
            if ($del) {
                if ($del_thumb) {
                    $f = "assets/uploads/" . $path. '_thumb' . "/" . $file;
                    if (file_exists($f)) {
                        unlink($f);
                    }
                }
                return TRUE;
            }
        }
        return $fname;
    }

}


if (!function_exists('delete_file_tmp')) {
    /*
     *  This function will delete the file from the assets folder in a specified directory
     *  $file       The name of file to delete
     *  $path       The directory where the file will found
     *  $del_thumb  Whether to delete the thumb file also [true/false]
     */

    function delete_file_tmp($file = '') {
        $fname = "assets/tmp_uploads/"  . $file;
        if (file_exists($fname)) {
            $del = unlink($fname);
            if ($del) {
                return TRUE;
            }
        }
        return FALSE;
    }

}


if (!function_exists('set_flash')) {
    /*
     *  This function will set a flash session 
     *  $key       The session name or key
     *  value       The session value
     */

    function set_flash($key = '' , $value = '') {
        $ci = &get_instance();
        if(empty($key) || empty($value)){
            die("please check your paramenter");
            return false;
        }
        $ci->load->library('session');
        $ci->session->set_flashdata($key, $value);
    }

}

if (!function_exists('get_flash')) {
    /*
     *  This function will set a flash session 
     *  $key       The key of the session which is set by set_flash
     *  return      The flash session value associated with the key or false if no any key is associated with that session
     */

    function get_flash($key = '') {
        $ci = &get_instance();
        if(empty($key)){
            die("please enter a key");
            return false;
        }
        $ci->load->library('session');
        $data = $ci->session->flashdata($key);
        if(!$data){
            return false;
        }
        return $data;
    }

}

if (!function_exists('array_to_assoc')) {
    /*
     *  This function will make any array as simple in key value pair specified in the second and third parameter 
     *  $array      The array which has to make simple
     *  $key        The array key name
     *  $value      The array key value format
     *  return      The simple array in key value pair
     */

    function array_to_assoc($array = array() , $key = '' , $val = '') {
        if(empty($array)){
            return false;
        }
        $new_array = array();
        if(count($array) > 0){
            foreach($array as $k => $v){
                $new_array[$v[$key]] = $v[$val];
            }
        }else{
            return FALSE;
        }
        return $new_array;

    }

}

if (!function_exists('check_image')) {
    /*
     *  This function will make any array as simple in key value pair specified in the second and third parameter 
     *  $file      The name of file you want to search
     *  $path 	   The path for that file (TRUE|FALSE)
     *  return     The absolute path for that file or default image if no image is found in the searching directory
     */

    function check_image($file = '' , $path = '' , $default = 'no-img.jpg' , $is_thumb = FALSE) {
        $default = $default;
		$result = null;
        if($is_thumb AND $file) {
			$path = $path.'/thumb';
			//$thumb = '150x150_'.$file;
			$thumb = $file;
			$f_path = "./assets/uploads/"."$path/$thumb";
			
			if(file_exists($f_path)){
				$result = ASSETS."uploads/$path/$thumb";
			}else{
				$result = $default;
			}
		} else {
			$f_path = "./assets/uploads/"."$path/$file";
			
			if(file_exists($f_path) AND $file){
				$result = ASSETS."uploads/$path/$file";
				
			}else{
				$result = $default;
			
			}
		}
		
		return $result;
	}

}




if (!function_exists('check_icon')) {
    /*
     *  This function will make any array as simple in key value pair specified in the second and third parameter 
     *  $file      The name of file you want to search
     *  $path 	   The path for that file (TRUE|FALSE)
     *  return     The absolute path for that file or default image if no image is found in the searching directory
     */

    function check_icon($file = '') {
        $default = 'default-icon.png';
		$result = null;
        $f_path = "./assets/icons/$file";
			
		if(file_exists($f_path) AND $file){
			$result = ASSETS."icons/$file";
			
		}else{
			$result = $default;
		
		}
		
		return $result;
	}

}


if (!function_exists('check_image_thumb')) {
    /*
     *  This function will make any array as simple in key value pair specified in the second and third parameter 
     *  $file      The name of file you want to search
     *  $path 	   The path for that file (TRUE|FALSE)
     *  return     The absolute path for that file or default image if no image is found in the searching directory
     */

    function check_image_thumb($file = '' , $path = '' , $default = 'no-img.jpg') {
        return check_image($file , $path, $default, TRUE);
	}

}


if(!function_exists('clean_url')){
	/*
     * 	This function removes all whitespaces with - and return string
	 *	@param  	String
	 *	return 		String
     */
	 
	function clean_url($str){
		return trim(strtolower(preg_replace('/[\s+\'+\"+&+\?+]/', '-', $str)), '-');
	}
}


if(!function_exists('clean_string')){
	/*
     * 	This function removes all whitespaces with - and return string
	 *	@param  	String
	 *	return 		String
     */
	 
	function clean_string($str){
		return trim(strtolower(preg_replace('/[\s+\'+\"+&+\?+]/', '-', $str)), '-');
	}
}


if(!function_exists('seo_string')){
	/*
     * 	This function create a seo string
	 *	@param  	String
	 *	return 		String
     */
	 
	function seo_string($vp_string){
    
		$vp_string = trim($vp_string);
		
		$vp_string = html_entity_decode($vp_string);
		
		$vp_string = strip_tags($vp_string);
		
		$vp_string = strtolower($vp_string);
		
		$vp_string = preg_replace('~[^ a-z0-9_.]~', ' ', $vp_string);
		
		$vp_string = preg_replace('~ ~', '-', $vp_string);
		
		$vp_string = preg_replace('~-+~', '-', $vp_string);
			
		return $vp_string;
    } 
}


if(!function_exists('log_user_ip')){
	/*
     * 	This function will check for a user ip in the database if the user already visted a store or a product page then nothing happen otherwise insert the record in the database
	 *	@param $user_ip 	Ip address of the user visit the page - String 
	 *	@param $visited 	The page he visited - String 
	 *	@param $obj_id 		The id of the object (product_id/store_id)- String/int 
	 *	return  			boolean TRUE If success otherwise FALSE
     */
	 
	function log_user_ip($obj_id = ''){
		if(empty($obj_id)){
			die("Invalid data");
		}
		$ci = &get_instance();
		$user_ip = $ci->input->ip_address();
		$count = (bool) count_results('clicks' , array('clicker_IP' => $user_ip  , 'ad_id' => $obj_id),'id');
		if($count == FALSE){
			$insert = array(
			'clicker_IP' => $user_ip,
			'ad_id' => $obj_id,
			);
			
			$ins = insert_record('clicks' , $insert);
			return TRUE;
		}
		return FALSE;
	}
}


if(!function_exists('count_log_ip')){
	/*
     * 	This function return the count from the given query condition 
	 *	@param $condition 	The condition for where clause in the query 
	 *	return  			int
     */
	 
	function count_log_ip($cond = array()){
		return count_results('visitors' , $cond ,'id');
	}
}

if(!function_exists('keyword_check')){
	/*
     * 	This function return the count from the given query condition 
	 *	@param $condition 	The condition for where clause in the query 
	 *	return  			int
     */
	 
	function keyword_check($keyword=''){
		$ci = &get_instance();
		$count = (bool) count_results('keywords' , array('keyword' => $keyword),'id');
		if($count == FALSE){
			// insert the keyword
			return insert_record('keywords' , array('keyword' => $keyword , 'date' => date('Y-m-d h:i:s')));
		}else{
			// update the count
			return $ci->db->set('keyword_count' , 'keyword_count + 1' , FALSE)->where('keyword' , $keyword)->update('keywords');
		}
	}
}

if(!function_exists('get_template')){
	/*
		This function return content of a given template  name;
		@param $name		The name of the template file
		return 				String The content of template file
	*/
	
	function get_template($temp = ''){
		$ext = '.template';
		$file = 'template/'.$temp.$ext;
		if(!file_exists($file)){
			die("Template not found");
		}
		$content = file_get_contents($file);
		return $content;
	}
}

if(!function_exists('parse_temp')){
	/*
		This function parse template data and return a prepared string
		@param $temp = The templete content as string
		@param $data = The templete data in form of array
		
		return 			String Prepared string
	*/
	
	
	function parse_temp($temp ='' , $content = array()){
		$ci = &get_instance();
		$ci->load->library('parser');
		return $ci->parser->parse_string($temp , $content , TRUE);
	}
}

if(!function_exists('parse_string')){
	
	function parse_string($temp ='' , $content = array()){
		parse_temp($temp, $content);
	}
}
if(!function_exists('SendMail')){
	/*
		The function is used to send mail
		@param $to				The user email where you want to send the email
		@param $temp			The templete name
		@param $data			The templete data
	*/
	function SendMail($to='', $template='', $data_parse=array(),$data_subject=array()) {
 		$CI = get_instance();
 		$default_lang=get_setting('admin_default_lang');
 		$mailemailID=get_setting('admin_email');
		$name=get_setting('website_name');
		$site_logo=LOGO;
 		$mailcontent = getData(array(
 		'select'=>'m.template_id,mt_n.template_content,mt_n.template_subject',
 		'table'=>'mailtemplate as m',
 		'join'=>array(array('table'=>'mailtemplate_names as mt_n','on'=>"m.template_id=mt_n.template_id and mt_n.lang='".$default_lang."'",'position'=>'left')),
 		'where'=>array('m.template_type'=>$template),
 		'single_row'=>TRUE
 		));
       if($mailcontent){
            $subject = $mailcontent->template_subject;
            $contents = $mailcontent->template_content;
	   }else{
	   		$contents = 'Invalid Template: '.$template;
            $subject ='Invalid Template: '.$template;
	   }
        if($data_subject){
			foreach ($data_subject as $key => $val) {
           	 $subject = str_replace('{' . $key . '}', $val, $subject);
        	}
		}
		$preparse=array(
		'WEBSITE_NAME'=>$name,
		'WEBSITE_LOGO'=>"<img src='".$site_logo."' width='100' >",
		'ADMIN_URL'=>ADMIN_URL,
		);
		foreach ($data_parse as $key => $val) {
            $contents = str_replace('{' . $key . '}', $val, $contents);
            $subject = str_replace('{' . $key . '}', $val, $subject);
        }
        foreach($preparse as $key=>$val){
			$contents = str_replace('{' . $key . '}', $val, $contents);
            $subject = str_replace('{' . $key . '}', $val, $subject);
		}
		
		if(SET_EMAIL_CRON==1 && $to!=''){
			
 		$pending_emails=array(
 		'to_email'=>$to,
 		'email_subject'=>$subject,
 		'email_content'=>$contents,
 		'request_date'=>date('Y-m-d H:i:s'),
 		'email_unique_id'=>time().'_'.rand(1,10000),
 		);
 		$CI->db->insert('pending_emails',$pending_emails);
 		return 1;
 		die;
		}
 		$send='';
 		$CI->load->library("PhpMailerLib");
        $mail = $CI->phpmailerlib->load();
        try {
			$mail->SMTPOptions = array(
				'ssl' => array(
				'verify_peer' => false,
				'verify_peer_name' => false,
				'allow_self_signed' => true
				)
				);
        	$user=get_setting('smtp_user');
        	$is_smtp=get_setting('is_smtp');
        	if($is_smtp){
	        	$mail->SMTPDebug = 0;
	        	$mail->isSMTP();
	        	$mail->Host = get_setting('smtp_host'); 
	        	$mail->SMTPAuth =true; 
	        	$mail->Username = $user;
	        	$mail->Password = get_setting('smtp_pass');                           // SMTP password
			    $mail->SMTPSecure = 'TLS';                            // Enable TLS encryption, `ssl` also accepted
			    $mail->Port = get_setting('smtp_port');                                    // TCP port to connect to
			}
		    //Recipients
		    $mail->setFrom($user,$name);
		    $mail->addAddress($to);
		    $mail->addReplyTo($user);
		    $mail->isHTML(true);                                  // Set email format to HTML
		    $mail->Subject = $subject;
		    $mail->Body    = $contents;
		    $send=$mail->send();
        	$mail->ClearAllRecipients(); 
    		$mail->ClearAttachments();   //Remove all attachements
        } catch (Exception $e) {
		    echo 'Message could not be sent.';
		    echo 'Mailer Error: ' . $mail->ErrorInfo;
		}
        return $send;
    }
}



if(!function_exists('get_title_from_url')){
	/*
		This function fetch the title from a url 
		@param $url			The URL from where you want to fetch the title
		retrun 				String title
	*/
	
	function get_title_from_url($url=''){
		$urlContents = file_get_contents($url);
		$dom = new DOMDocument();
		@$dom->loadHTML($urlContents);
		$title = $dom->getElementsByTagName('title');
		return $title->item(0)->nodeValue;
	}
}

if(!function_exists('get_image_from_url')){
	/*
		This function fetch the all image from a url 
		@param $url			The URL from where you want to fetch the title
		retrun 				String title
	*/
	
	function get_image_from_url($url=''){
		$urlContents = file_get_contents($url);
		$dom = new DOMDocument();
		@$dom->loadHTML($urlContents);
		$imageTags = $dom->getElementsByTagName('img');
		$image = array();
		foreach($imageTags as $tag) {
			$image[] = $tag->getAttribute('src');
		}
		return $image;
	}
}

if(!function_exists('get_meta_data_from_url')){
	/*
		This function fetch all meta data having attribute property 
		@param $url			The URL from where you want to fetch the title
		retrun 				String title
	*/
	
	function get_meta_data_from_url($url=''){
		$urlContents = file_get_contents($url);
		$doc = new DomDocument();
		@$doc->loadHTML($urlContents);
		$xpath = new DOMXPath($doc);
		$query = '//*/meta[starts-with(@property, \'og:\')]';
		$metas = $xpath->query($query);
		$rmetas = array();
		foreach ($metas as $meta) {
			$property = $meta->getAttribute('property');
			$content = $meta->getAttribute('content');
			$rmetas[$property] = $content;
		}
		return $rmetas;
	}
}


if(!function_exists('crop_image')){
	/*
		This function fetch all meta data having attribute property 
		@param $image			The server path of the image
		@param $config 			Crop configuration
		@param $new_image		The new cropped image name
		return 					true/false
	*/
	
	function crop($image='', $config=array() , $new_image_name=''){
		$ci = &get_instance();
		$config['image_library'] = 'gd2';
		$config['source_image'] = $image;
		$config['x_axis'] = $config['x'];
		$config['y_axis'] = $config['y'];
		$config['width']  = $config['width'];
		$config['height']  = $config['height'];
		$config['maintain_ratio']  = FALSE;
		if($new_image_name){
			$config['new_image']  = $new_image_name;
		}
		$ci->load->library('image_lib');
		$ci->image_lib->initialize($config);
		/*if ( ! $ci->image_lib->crop()){
				return $this->image_lib->display_errors();
		}else{
			return 
		}*/
		return  $ci->image_lib->crop();
		
	}
}

if(!function_exists('resize')){
	/*
		This function fetch all meta data having attribute property 
		@param $url			The URL from where you want to fetch the title
		retrun 				String title
	*/
	
	function resize($image='', $size=array()){
		$ci = &get_instance();
		$img_arr = explode('/', $image);
		$img_file = end($img_arr);
		$ci->load->library('image_lib');
		$config['image_library'] = 'gd2';
		$config['source_image'] = $image;
		if(count($size) > 0){
			foreach($size as $k => $v){
				$config['width']  = $v['width'];
				$config['height']  = $v['height'];
				if(!empty($v['location'])){
					if (!is_dir('./assets/uploads/' . $v['location'])) {
						mkdir('./assets/uploads/' . $v['location']);
					}
					$config['new_image']  = './assets/uploads/'.$v['location'].'/'.$img_file;
				}else{
					$config['new_image']  = $v['width'].'x'.$v['height'].'_'.$img_file;
				}
				
				$ci->image_lib->initialize($config);
				$ci->image_lib->resize();
				$ci->image_lib->clear();
			}
		}	
		
	}
}

if(!function_exists('replace_email')){
	
	
	function replace_email($str=''){
		$str = trim($str);
		if(strlen($str) == 0){
			return $str;
		}
		
		$email_pattern = '/[a-z0-9!#$%&\'*+\/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&\'*+\/=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?/';
		
		$replace_str = preg_replace($email_pattern, 'xxxxxxxxxxxx',$str);
		return $replace_str;
		
	}
	
}

if(!function_exists('replace_phone')){
	
	
	function replace_phone($str=''){
		$str = trim($str);
		if(strlen($str) == 0){
			return $str;
		}
		
		$phone_pattern = '/(\+?[\d-\(\)\s]{7,}?\d)/';
		
		$replace_str = preg_replace($phone_pattern, 'xxxxxxxxxxxx',$str);
		return $replace_str;
		
	}
	
}


if(!function_exists('print_select_option')){
	
	
	function print_select_option($array=array(), $value='', $name='', $selected=''){
		if(count($array) > 0){
			
			if(!empty($value) && !empty($name)){
				
				foreach($array as $k => $v){
					$select = '';
					
					if(!empty($selected)){
						if(is_array($selected)){
							if(in_array($v[$value], $selected)){
								$select = 'selected';
							}
						}else{
							if($selected == $v[$value]){
								$select = 'selected';
							}
						}
					
					}
					if($select){
						echo  '<option value="'.$v[$value].'" '.$select.'>'.$v[$name].'</option>';
					}else{
						echo  '<option value="'.$v[$value].'">'.$v[$name].'</option>';
					}
					
				
				}
			
			}
			
		}
		
	}
	
}

if(!function_exists('print_select_option_assoc')){
	
	
	function print_select_option_assoc($array=array(),  $selected=''){
		if(count($array) > 0){
			
			foreach($array as $k => $v){
				$select = '';
				
				if(!empty($selected)){
					if($selected == $k){
						$select = 'selected';
					}
				}
				if($select){
					echo  '<option value="'.$k.'" '.$select.'>'.$v.'</option>';
				}else{
					echo  '<option value="'.$k.'">'.$v.'</option>';
				}
				
			
			}
			
		}
		
	}
	
}



if(!function_exists('get_k_value_from_array')){
	
	
	function get_k_value_from_array($array=array(), $key_name=''){
		$val = array();
		if(count($array) > 0){
			foreach($array as $k => $v){
				if(is_array($v)){
					$val[] = $v[$key_name];
				}
				
			}
		}
		
		return $val;
		
	}
	
}

if(!function_exists('is_image')){
	
	function is_image($file=''){
		$file_part = explode('.', $file);
		$file_ext = strtolower(end($file_part));
		$image_ext = array('jpg', 'jpeg', 'png', 'gif', 'bit');
		if(in_array($file_ext, $image_ext)){
			return TRUE;
		}
		return FALSE;
	}
	
}

if(!function_exists('is_pdf')){
	
	function is_pdf($file=''){
		$file_part = explode('.', $file);
		$file_ext = strtolower(end($file_part));
		$allowed_ext = array('pdf');
		if(in_array($file_ext, $allowed_ext)){
			return TRUE;
		}
		return FALSE;
	}
	
}

if(!function_exists('get_month')){
	
	function get_month($type=''){
		
		/*
			type : option, array (default)
		*/
		
		$month_arr = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
		if($type == 'option'){
			$html = '';
			foreach($month_arr as $k => $v){
				$html .= '<option value="'.$v.'">'.$v.'</option>';
			}
			return $html;
		}
		
		return $month_arr;
		
	}
	
}

if(!function_exists('get_currency')){
	
	
	function get_currency($display_only=array()){
		$currency = array();
		$ci = &get_instance();
		$ci->db->select("DISTINCT(currency_code) as code")
			->from('country')
			->where('currency_code <>', '');
			if($display_only){
				$ci->db->where_in('currency_code',$display_only);
			}
			$all_currency = $ci->db->get()->result_array();
			
		if(count($all_currency) > 0){
			foreach($all_currency as $k => $v){
				$currency[] = $v['code'];
			}
		}
		
		return $currency;
	}
	
}

if(!function_exists('str_check_length')){
	
	
	function str_check_length($str='', $len=null){
		if(!$len){
			return $str;
		}
		
		return strlen($str) > $len ? substr($str, 0, $len).'...' : $str;
	}
	
}



if(!function_exists('get_setting')){
	
	function get_setting($key=''){
		$val = getField('setting_value', 'settings', 'setting_key', $key);
		return $val;
	}
}


if(!function_exists('get_lang')){
	
	function get_lang(){
		$data = array();
		$val = getField('setting_value', 'settings', 'setting_key', 'language');
		if($val){
			$data = explode(',', $val);
		}else{
			$data[] = 'en';
		}
		
		return $data;
	}
}

if(!function_exists('get_lang_attr')){
	
	function get_lang_attr($type='', $lang=''){
		$ci = &get_instance();
		$ci->config->load('lang', TRUE);
		$all_langs =$ci->config->item('all_language', 'lang');
		$all_flags =$ci->config->item('flags', 'lang');
		if($type == 'language'){
			return $all_langs[$lang] ? $all_langs[$lang] : FALSE;
		}else if($type == 'flag'){
			return $all_flags[$lang] ? $all_flags[$lang] : FALSE;
		}else if($type == 'filename'){
			return $all_langs[$lang] ? strtolower($all_langs[$lang]) : FALSE;
		}
		
		return FALSE;
	}
}


if(!function_exists('get_display_name')){
	
	function get_display_name($id='', $table='', $lang='en'){
		$suffix = '_lang';
		if(!$id){
			die('ERR_PRIMARY_KEY_MISSING : '.__FILE__);
		}
		if(!$table){
			die('ERR_TABLE_NAME_MISSING : '.__FILE__);
		}
		$row = get_row(array('select' => '*', 'from' => $table.$suffix, 'where' => array('id' => $id, 'lang_code' => $lang)));
		if($row){
			return $row['display_value'];
		}
		
		return FALSE;
	}
}



if(!function_exists('set_lang')){
	
	
	function set_lang($lang=''){
		
		set_session('active_lang', $lang);
		
	}
	
}

if(!function_exists('set_default_lang')){
	
	
	function set_default_lang(){
		$default_lang = get_default_lang();
		set_session('active_lang', $default_lang);
		
	}
	
}

if(!function_exists('get_default_lang')){
	
	
	function get_default_lang(){
		$default_lang = get_setting('default_lang');
		if(!$default_lang){
			$default_lang = 'en';
		}
		return $default_lang;
		
	}
	
}


if(!function_exists('get_active_lang')){
	
	
	function get_active_lang(){
		$ci = &get_instance();
		$default_lang = get_default_lang();
	//	$lang = get_session('active_lang');
		$lang = $ci->config->item('language');
		if(!$lang){
			$lang = $default_lang;
		}
		
		return $lang;
		
	}
	
}


/* if(!function_exists('__')){
	
	
	function __($lang_key='', $default_val=''){
		$ci = &get_instance();
		$line = $ci->lang->line($lang_key, FALSE);
		if(!$line){
			$line=$default_val;
		}
		if(get('show_lang_key')){
			$line = '<font color="red">'.$lang_key.'</font>';
		}
		return $line;
		
	}
	
} */
if ( ! function_exists('getData'))
{
	function getData($data=array()){
	$ci =& get_instance();
	$ci->load->database();
	$select='*';
	$table =null;
	$type=null;
	$order=null;
	$group=null;
	$limit=null;
	$having=null;
	$where=$where_in=array();
	$join=array();
	$return_count=FALSE;
	$single_row=FALSE;
	if(!empty($data['select'])){
		$select=$data['select'];
	}
	if(!empty($data['table'])){
		$table=$data['table'];
	}
	if(!empty($data['where'])){
		$where=$data['where'];
	}
	if(!empty($data['where_in'])){
		$where_in=$data['where_in'];
	}
	if(!empty($data['join'])){
		$join=$data['join'];
	}
	if(!empty($data['type'])){
		$type=$data['type'];
	}
	if(!empty($type) || $type != null){
		$type = strtolower(trim($type));
	}else{
		$type = 'object';
	}
	if(!empty($data['order'])){
		$order=$data['order'];
	}
	if(!empty($data['limit'])){
		$limit=$data['limit'];
	}
	if(!empty($data['having'])){
		$having=$data['having'];
	}
	if(!empty($data['group'])){
		$group=$data['group'];
	}
	if(!empty($data['return_count'])){
		$return_count=$data['return_count'];
	}
	if(!empty($data['single_row'])){
		$single_row=$data['single_row'];
	}
	$ci->db->select($select);
	if(!empty($table) || $table != null){
		 $table = trim($table);
	}else{
		echo "Table is not set! Please use a table name as first argument.";
		return false;
	}
	
	
	$ci->db->from($table);
	if ($join) {
		foreach($join as $j){
			$ci->db->join($j['table'],$j['on'],$j['position']);		
		}
			
	}
	if ($where) {
		$ci->db->where($where);			
	}
	if($where_in){
		foreach($where_in as $k=>$wherec){
			$ci->db->where_in($k, $wherec);
		}
	}
	
	if(!empty($group) || $group != null){
			$ci->db->group_by($group);
	}
	if(!empty($order) || $order != null){
		foreach($order as $ord){
			$ci->db->order_by($ord[0],$ord[1]);
		}
	}
	if(!empty($having) || $having != null){
			$ci->db->having($having);
	}
	if((!empty($group) || $group != null) && strtoupper($return_count)==TRUE){
		 return $ci->db->get()->num_rows();
	}elseif(strtoupper($return_count)==TRUE){
		 return $ci->db->count_all_results();
		
	}else{
		if($limit != null){
			if(is_array($limit)){
				$ci->db->limit($limit[0],$limit[1]);
			}else{
				$ci->db->limit($limit);
			}
			
		}
		 $query = $ci->db->get();
		 if($type == 'object'){
		 	if($single_row){
		 		return $query->row();
		 	}else{
				return $query->result();
			}
		}elseif($type == 'array'){
			if($single_row){
		 		return $query->row_array();
		 	}else{
				return $query->result_array();
			}
		}	
	}
	}
}
if(!function_exists('getFieldData')){
	function getFieldData($select, $table, $feild = "", $value = "", $where = null, $limit_from = 0, $limit_to = 0) {
		$ci =& get_instance();
		$ci->db->select($select);
		if ($value != '' AND $feild != '') {
			if ($limit_from > 0) {
				$rs = $ci->db->get_where($table, array($feild => $value), $limit_to, $limit_from);
			} else {
				$rs = $ci->db->get_where($table, array($feild => $value));
			}
		} else {
			if ($limit_from > 0) {
				$rs = $ci->db->get_where($table, $where, $limit_to, $limit_from);
			} else {
				$rs = $ci->db->get_where($table, $where);
			}
		}
		$data = '';
		foreach ($rs->result() as $row) {
			$data = $row->$select;
		}
		return $data;
	}
}
if(!function_exists('getMemberLogo')){
	function getMemberLogo($member_id=''){
		$organization_id=getFieldData('organization_id','organization','member_id',$member_id);
		if($organization_id){
			return getCompanyLogo($organization_id);
		}
		$userimage=IMAGE.'default/thumb/default-member-logo.svg';
		$logo=getFieldData('logo','member_logo','','',array('status'=>1,'member_id'=>$member_id));
		if($logo && file_exists(UPLOAD_PATH.'member-logo/'.$logo)){
			$userimage=UPLOAD_HTTP_PATH.'member-logo/'.$logo;
		}
		return $userimage;
}
}
if(!function_exists('getCompanyLogo')){
	function getCompanyLogo($company_id=''){
		$userimage=IMAGE.'default/thumb/default-organization-logo.png';
		$logo=getFieldData('logo','organization_logo','','',array('organization_id'=>$company_id));
		if($logo && file_exists(UPLOAD_PATH.'company-logo/'.$logo)){
			$userimage=UPLOAD_HTTP_PATH.'company-logo/'.$logo;
		}
		return $userimage;
	}
}
if(!function_exists('getWorkerLogo')){
	function getWorkerLogo($worker_id=''){
		$userimage=IMAGE.'default/thumb/default-organization-logo.png';
		$logo=getFieldData('logo','worker_logo','','',array('worker_id'=>$worker_id));
		if($logo && file_exists(UPLOAD_PATH.'worker-logo/'.$logo)){
			$userimage=UPLOAD_HTTP_PATH.'worker-logo/'.$logo;
		}
		return $userimage;
	}
}
if(!function_exists('addhttp')){
	function addhttp($url) {
	    if (!preg_match("~^(?:f|ht)tps?://~i", $url)) {
	        $url = "http://" . $url;
	    }
	    return $url;
	}
}
if ( ! function_exists('updateTable'))
{
	function updateTable($table = null, $data = null, $array = array(), $or_array = array()) {
		$ci =& get_instance();

		if(!empty($table) || $table != null){
			$table = strtolower(trim($table));
		}else{
			echo "Table is not set! Please use a table name as first argument.";
			return false;
		}

		if(!is_array($data)){
			echo "Please use an array as second argument!";
			return false;
		}else{
			if (!is_array($array)) {
				echo "An associative array required!";
				return false;
			}else{
				$ci->db->where($array);
				if(!empty($or_array)){
					$ci->db->or_where($or_array);
				}
				return $ci->db->update($table, $data);
			}
		}

	}
}
if (!function_exists('getMembershipData')) {
	function getMembershipData($member_id='',$filter=array()) {
		$lang = get_active_lang();
		$ci = &get_instance();
		$maxdata=array();
		$res=array('status'=>0);
		$addedit=0;
		$check=getData(array(
			'select'=>'m.membership_id,m.membership_expire_date,m.is_free,m.max_bid,m.max_portfolio,m.max_skills,m.commission_percent',
			'table'=>'member_membership m',
			'where'=>array('m.member_id'=>$member_id),
			'single_row'=>TRUE
			));
		if($check){
			$maxdata=array('max_bid'=>$check->max_bid,'max_portfolio'=>$check->max_portfolio,'max_skills'=>$check->max_skills,'commission_percent'=>$check->commission_percent);
			if($check->is_free==0 && date('Y-m-d') > $check->membership_expire_date){
				$addedit=2;
			}
		}else{
			$addedit=1;
		}
		if($addedit>0){
			$membership_id=get_setting('FREE_MEMBERSHIP_ID');
			$membership=getData(array(
				'select'=>'b.membership_id,b.membership_bid,b.membership_portfolio,b.membership_skills,b.membership_commission_percent,b.price_per_month,b.price_per_year,l.name,l.description',
				'table'=>'membership b',
				'join'=>array(array('table'=>'membership_names l','on'=>"(l.membership_id=b.membership_id and l.lang='".$lang."')",'position'=>'left')),
				'where'=>array('b.membership_status'=>1,'b.membership_id'=>$membership_id),
				'single_row'=>TRUE
				));
			if($membership){
				$member_membership=array(
					'membership_id'=>$membership_id,
					'is_free'=>1,
					//'membership_expire_date'=>$membership_expire_date,
					'max_bid'=>$membership->membership_bid,
					'max_portfolio'=>$membership->membership_portfolio,
					'max_skills'=>$membership->membership_skills,
					'commission_percent'=>$membership->membership_commission_percent,
				);
				$dura='+ 1 month';
				$membership_expire_date=date('Y-m-d',strtotime($dura));
				$member_membership['membership_expire_date']=$membership_expire_date;
				
				if($addedit==1){
					$member_membership['member_id']=$member_id;
					insert_record('member_membership',$member_membership);
					$res['status']=1;
				}else{
					updateTable('member_membership',$member_membership,array('member_id'=>$member_id));
					$res['status']=1;
				}
				$maxdata=array('max_bid'=>$member_membership['max_bid'],'max_portfolio'=>$member_membership['max_portfolio'],'max_skills'=>$member_membership['max_skills'],'commission_percent'=>$member_membership['commission_percent']);
			}
		}
		if($filter){
			if(in_array('bid',$filter)){
				$res['max_bid']=$maxdata['max_bid'];
				$res['used_bid']=$ci->db->where('MONTH(bid_date)',date('m'))->where('YEAR(bid_date)',date('Y'))->where('member_id',$member_id)->count_all_results('project_bids');
			}
			elseif(in_array('portfolio',$filter)){
				$res['max_portfolio']=$maxdata['max_portfolio'];
				$res['used_portfolio']=$ci->db->where('member_id',$member_id)->where('portfolio_status',1)->count_all_results('member_portfolio');
			}
			elseif(in_array('skills',$filter)){
				$res['max_skills']=$maxdata['max_skills'];
				$res['used_skills']=$ci->db->where('member_id',$member_id)->count_all_results('member_skills');
			}
			elseif(in_array('commission',$filter)){
				$res['commission']=$maxdata['commission_percent'];
			}
		}
		return $res;
	}
}
if ( ! function_exists('getSiteCommissionFee'))
{
	function getSiteCommissionFee($member_id='',$project_id=''){
		$commission=5;
		$membership=getMembershipData($member_id,array('commission'));
		if($membership){
			if(array_key_exists('commission',$membership)){
				$commission=round($membership['commission'],2);
			}
		}
		return $commission;
	}
}
if ( ! function_exists('generateProcessingFee'))
{
function generateProcessingFee($type,$amount=0){
	$data=array('processing_fee_text'=>'','processing_fee'=>0,'total_amount'=>0);
	$is_valid=0;
	if($type=='paypal'){
		$processing_fee_fixed=get_setting('paypal_processing_fee_fixed');
		$processing_fee_percent=get_setting('paypal_processing_fee_percent');
		$is_valid=1;
	}elseif($type=='stripe'){
		$processing_fee_fixed=get_setting('stripe_processing_fee_fixed');
		$processing_fee_percent=get_setting('stripe_processing_fee_percent');
		$is_valid=1;
	}elseif($type=='telr'){
		$processing_fee_fixed=get_setting('telr_processing_fee_fixed');
		$processing_fee_percent=get_setting('telr_processing_fee_percent');
		$is_valid=1;
	}elseif($type=='ngenius'){
		$processing_fee_fixed=get_setting('ngenius_processing_fee_fixed');
		$processing_fee_percent=get_setting('ngenius_processing_fee_percent');
		$is_valid=1;
	}elseif($type=='wallet'){
		$processing_fee_fixed=0;
		$processing_fee_percent=0;
		$is_valid=1;
	}elseif($type=='bank'){
		$processing_fee_fixed=0;
		$processing_fee_percent=0;
		$is_valid=1;
	}elseif($type=='withdrawal_paypal'){
		$processing_fee_fixed=get_setting('withdrawal_paypal_processing_fee_fixed');
		$processing_fee_percent=get_setting('withdrawal_paypal_processing_fee_percent');
		$is_valid=1;
	}elseif($type=='withdrawal_bank'){
		$processing_fee_fixed=get_setting('withdrawal_bank_processing_fee_fixed');
		$processing_fee_percent=get_setting('withdrawal_bank_processing_fee_percent');
		$is_valid=1;
	}elseif($type=='withdrawal_payoneer'){
		$processing_fee_fixed=get_setting('withdrawal_payoneer_processing_fee_fixed');
		$processing_fee_percent=get_setting('withdrawal_payoneer_processing_fee_percent');
		$is_valid=1;
	}
	if($is_valid){
		$total_fee_text="";
		if($processing_fee_percent>0){
			$total_fee_text.=$processing_fee_percent.'%';
		}
		if($processing_fee_percent>0 && $processing_fee_fixed>0){
			$total_fee_text.=' + ';
		}
		if($processing_fee_fixed>0){
			$total_fee_text.=CurrencySymbol().$processing_fee_fixed;
		}
		$data['processing_fee_text']=$total_fee_text;
		$processing_fee_percent_amt=($amount*$processing_fee_percent)/100;
		$total_fee=$processing_fee_percent_amt+$processing_fee_fixed;
		$data['processing_fee']=displayamount($total_fee);
		$data['total_amount']=displayamount($total_fee+$amount);
	}
	return $data;
}
}
if ( ! function_exists('displayamount'))
{
	function displayamount($amount,$limit='2'){
		$amount=number_format($amount,$limit, '.', '');
		if($limit==4){
			return sprintf("%1.4f",$amount);
		}elseif($limit==2){
			return sprintf("%1.2f",$amount);
		}else{
			return sprintf("%1.2f",$amount);
		}
		
	}
}
if ( ! function_exists('getWallet'))
{
function getWallet($wallet_id){
	$data=getData(array(
				'select'=>'w.wallet_id,w.balance,w.user_id,w.title',
				'table'=>'wallet as w',
				'where'=>array('w.wallet_id'=>$wallet_id),
				'single_row'=>true,
			)
		);	
	return $data;
}
}
if ( ! function_exists('getWalletMember'))
{
function getWalletMember($member_id){
	$data=getData(array(
				'select'=>'w.wallet_id,w.balance,w.user_id,w.title,if(o.organization_name IS NOT NULL,o.organization_name,m.member_name) as name,m.member_email',
				'table'=>'wallet as w',
				'join'=>array(
					array('table'=>'member as m','on'=>'w.user_id=m.member_id','position'=>'left'),
					array('table'=>'organization as o','on'=>'m.member_id=o.member_id','position'=>'left')
				),
				'where'=>array('w.user_id'=>$member_id),
				'single_row'=>true,
			)
		);
	return $data;
}
}
if ( ! function_exists('updateMemberRatting'))
{
	function updateMemberRatting($member_id=''){
		if($member_id){
			$avg_review=$total_review=0;
			$data=getData(array(
				'select'=>'avg(average_review) as avg_review,count(review_id) as total_review',
				'table'=>'contract_reviews',
				'where'=>array('review_to'=>$member_id,'review_status'=>1,'is_display_public'=>1),
				'group'=>'review_to',
				'single_row'=>true,
			));
			if($data){
				$avg_review=$data->avg_review;
				$total_review=$data->total_review;
			}
			$memberDatacount=getData(array(
				'select'=>'m_s.member_id',
				'table'=>'member_statistics as m_s',
				'where'=>array('m_s.member_id'=>$member_id),
				'return_count'=>true,
			));
			if($memberDatacount){
				updateTable('member_statistics',array('avg_rating'=>$avg_review,'no_of_reviews'=>$total_review),array('member_id'=>$member_id));
			}else{
				insert_record('member_statistics',array('member_id'=>$member_id,'avg_rating'=>$avg_review,'no_of_reviews'=>$total_review),TRUE);
			}
		}
		updateMemberSuccessRate($member_id);
	}
}
if ( ! function_exists('updateMemberSuccessRate'))
{
	function updateMemberSuccessRate($member_id=''){
		//echo 'dasd';
		if($member_id){
			$success_rate=0;
			$review_date=date('Y-m-d',strtotime('-12 months'));
			$positive=getData(array(
				'select'=>'review_id',
				'table'=>'contract_reviews',
				'where'=>array('review_to'=>$member_id,'average_review >= '=>'4','review_status'=>1,'is_display_public'=>1,'date(review_date) >'=>$review_date),
				'return_count'=>true,
			));
			//echo get_last_query();
			$negative=getData(array(
				'select'=>'review_id',
				'table'=>'contract_reviews',
				'where'=>array('review_to'=>$member_id,'average_review <= '=>'3','review_status'=>1,'is_display_public'=>1,'date(review_date) >'=>$review_date),
				'return_count'=>true,
			));
			$total=getData(array(
				'select'=>'review_id',
				'table'=>'contract_reviews',
				'where'=>array('review_to'=>$member_id,'review_status'=>1,'is_display_public'=>1,'date(review_date) >'=>$review_date),
				'return_count'=>true,
			));
			if($total){
				$calculate_rate=(($positive-$negative)/$total)*100;
				if($calculate_rate>0){
					$success_rate=round($calculate_rate);
				}
			}
			$memberDatacount=getData(array(
				'select'=>'m_s.member_id',
				'table'=>'member_statistics as m_s',
				'where'=>array('m_s.member_id'=>$member_id),
				'return_count'=>true,
			));
			if($memberDatacount){
				updateTable('member_statistics',array('success_rate'=>$success_rate),array('member_id'=>$member_id));
			}else{
				insert_record('member_statistics',array('member_id'=>$member_id,'success_rate'=>$success_rate),TRUE);
			}
		}
	}
}	
if ( ! function_exists('updateMemberEarning'))
{
	function updateMemberEarning($member_id=''){
		if($member_id){
			$total_refund=$total_amount=0;
			$data=getData(array(
				'select'=>'sum(p_c_d.owner_amount) as total_refund',
				'table'=>'project_contract_dispute as p_c_d',
				'join'=>array(
					array('table'=>'project_contract as c','on'=>'p_c_d.contract_id=c.contract_id','position'=>'left'),
				),
				'where'=>array('c.contractor_id'=>$member_id,'c.contract_status'=>1,'p_c_d.dispute_status'=>1),
				'group'=>'c.contractor_id',
				'single_row'=>true,
			));
			if($data){
				$total_refund=$data->total_refund;
			}
			$data=getData(array(
				'select'=>'sum(p_p_e.debit) as total_amount',
				'table'=>'project_payment_escrow as p_p_e',
				'join'=>array(
					array('table'=>'project_contract as c','on'=>'p_p_e.contract_id=c.contract_id','position'=>'left'),
				),
				'where'=>array('c.contractor_id'=>$member_id,'c.contract_status'=>1,'p_p_e.status'=>1),
				'group'=>'c.contractor_id',
				'single_row'=>true,
			));
			if($data){
				$total_amount=$data->total_amount;
			}
			$total_earning=displayamount($total_amount-$total_refund,2);
			$memberDatacount=getData(array(
				'select'=>'m_s.member_id',
				'table'=>'member_statistics as m_s',
				'where'=>array('m_s.member_id'=>$member_id),
				'return_count'=>true,
			));
			if($memberDatacount){
				updateTable('member_statistics',array('total_earning'=>$total_earning),array('member_id'=>$member_id));
			}else{
				insert_record('member_statistics',array('member_id'=>$member_id,'total_earning'=>$total_earning),TRUE);
			}
		}
	}
}
if ( ! function_exists('updateMemberSpent'))
{
	function updateMemberSpent($member_id=''){
		if($member_id){
			$total_refund=$total_amount=0;
			$data=getData(array(
				'select'=>'sum(p_c_d.owner_amount) as total_refund',
				'table'=>'project_contract_dispute as p_c_d',
				'join'=>array(
					array('table'=>'project_contract as c','on'=>'p_c_d.contract_id=c.contract_id','position'=>'left'),
					array('table'=>'project_owner as p_o','on'=>'c.project_id=p_o.project_id','position'=>'left'),
				),
				'where'=>array('p_o.member_id'=>$member_id,'c.contract_status'=>1,'p_c_d.dispute_status'=>1),
				'group'=>'c.contractor_id',
				'single_row'=>true,
			));
			//print_r($data);
			if($data){
				$total_refund=$data->total_refund;
			}
			$data=getData(array(
				'select'=>'sum(p_p_e.debit) as total_amount',
				'table'=>'project_payment_escrow as p_p_e',
				'join'=>array(
					array('table'=>'project_contract as c','on'=>'p_p_e.contract_id=c.contract_id','position'=>'left'),
					array('table'=>'project_owner as p_o','on'=>'c.project_id=p_o.project_id','position'=>'left'),
				),
				'where'=>array('p_o.member_id'=>$member_id,'c.contract_status'=>1,'p_p_e.status'=>1),
				'group'=>'c.contractor_id',
				'single_row'=>true,
			));
			//print_r($data);
			if($data){
				$total_amount=$data->total_amount;
			}
			$total_spent=displayamount($total_amount-$total_refund,2);
			$memberDatacount=getData(array(
				'select'=>'m_s.member_id',
				'table'=>'member_statistics as m_s',
				'where'=>array('m_s.member_id'=>$member_id),
				'return_count'=>true,
			));
			if($memberDatacount){
				updateTable('member_statistics',array('total_spent'=>$total_spent),array('member_id'=>$member_id));
			}else{
				insert_record('member_statistics',array('member_id'=>$member_id,'total_spent'=>$total_spent),TRUE);
			}
		}
	}
}
if ( ! function_exists('updateMemberHour'))
{
	function updateMemberHour($member_id=''){
		if($member_id){
			$total_working_min=0;
			$data=getData(array(
				'select'=>'sum(total_time_worked) as total_time_worked_log',
				'table'=>'project_contract_hour_log as p_c_h_l',
				'join'=>array(
					array('table'=>'project_contract as c','on'=>'p_c_h_l.contract_id=c.contract_id','position'=>'left'),
				),
				'where'=>array('c.contractor_id'=>$member_id,'c.is_hourly'=>1,'c.contract_status'=>1),
				'group'=>'c.contractor_id',
				'single_row'=>true,
			));
			if($data){
				$total_working_min=$data->total_time_worked_log;
			}
			$total_working_hour=displayamount($total_working_min/60,2);
			$memberDatacount=getData(array(
				'select'=>'m_s.member_id',
				'table'=>'member_statistics as m_s',
				'where'=>array('m_s.member_id'=>$member_id),
				'return_count'=>true,
			));
			if($memberDatacount){
				updateTable('member_statistics',array('total_working_hour'=>$total_working_hour),array('member_id'=>$member_id));
			}else{
				insert_record('member_statistics',array('member_id'=>$member_id,'total_working_hour'=>$total_working_hour),TRUE);
			}
		}
	}
}

if (!function_exists('generate_invoice_number')) {
	function generate_invoice_number() {
		$currentno=get_setting('INVOICE_NUMBER');
		$INV=$currentno+1;
		updateTable('settings',array('setting_value'=>$INV),array('setting_key'=>'INVOICE_NUMBER'));
		$num =make_invoice_number($INV);
		return $num;
	}
}
if (!function_exists('make_invoice_number')) {
	function make_invoice_number($INV) {
		$num =str_pad($INV,8,'0',STR_PAD_LEFT);
		return $num;
	}
}
if ( ! function_exists('is_online'))
{
function is_online($memberId){
	$ci = &get_instance();
    $ci->load->database();
	return $ci->db->where('user_id',$memberId)->from('online_user')->count_all_results();
}
}
if(!function_exists('get_all_religion')){
	
	function get_all_religion(){
		
		$default_lang = get_default_lang();
		$results = get_results(array(
			'select' => 'c.religion_id,c_n.religion_name',
			'from' => 'religion c',
			'join' => array(
						array('religion_names as c_n', 'c.religion_id=c_n.religion_id', 'left')
					),
			'where' => array(
				'c_n.religion_lang' => $default_lang,
				'c.religion_status' => 1,
			),
			'group'=>'c.religion_id',
			'offset' => 'all',
			'order_by'=>array('c_n.religion_name','asc'),
			
		));
		
		return $results;
	}
	
}
if(!function_exists('get_pdf')){
	/*
		Date : 05/08/2016
		
		This function generate pdf using api 
		@param1 $src			The source for generating PDF
		@pram2 $type			The source type either url or html
		@return 				Void
		
		Author					OriginateSoft
		
	*/
	function get_pdf($html='' , $name='' , $config=array(), $download=false,$small=false){
		$invoice_path = UPLOAD_PATH.'/invoice';
		if($html == ''){
			die("Invalid parameter");
		}
		if($name == ''){
			$name = 'download.pdf';
		}
		$ci = &get_instance();
		$ci->load->helper('tcpdf_helper');
		if($small){
			$maximum_page_height=500;
			$obj_pdf = new TCPDF('Portrait', 'mm', [62, $maximum_page_height], true, 'UTF-8', false);
			
		}else{
			$obj_pdf = new TCPDF('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
		}
		
		//
		//$obj_pdf->SetCreator(PDF_CREATOR);
		$title = !empty($config['title']) ? $config['title'] : "PDF Report";
		//$obj_pdf->SetTitle($title);
		//$obj_pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, $title, '');
		$obj_pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
		$obj_pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
		$obj_pdf->SetDefaultMonospacedFont('helvetica');
		//$obj_pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
		$obj_pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
		$obj_pdf->SetMargins(PDF_MARGIN_LEFT, 2, PDF_MARGIN_RIGHT);
		$obj_pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
		$obj_pdf->SetFont('aealarabiya', '', 9);
		$obj_pdf->setFontSubsetting(false);
		$obj_pdf->AddPage();
		/*ob_start();
			$html;
			$content = ob_get_contents();
		ob_end_clean();*/
		
		//$obj_pdf->WriteHTML($htmlcontent, true, 0, true, 0);
	
		$obj_pdf->writeHTML($html, true, 0, true, 0);
		if($download){
			if(!is_dir($invoice_path)){
				mkdir($invoice_path);
			}
			
			$obj_pdf->Output($invoice_path.'/'.$name, 'F');
		}else{
			$obj_pdf->Output($name, 'I');
		}
		
	}
}
if ( ! function_exists('getWalletWorker')){
	function getWalletWorker($worker_id){
		$data=getData(array(
					'select'=>'w.wallet_id,w.balance,w.worker_id,w.title,m.worker_email,m.worker_name',
					'table'=>'wallet as w',
					'join'=>array(
						array('table'=>'worker as m','on'=>'w.worker_id=m.worker_id','position'=>'left'),
					),
					'where'=>array('w.worker_id'=>$worker_id),
					'single_row'=>true,
				)
			);
		return $data;
	}
}
if (!function_exists('updateMembershipUser')) {
function formatHours($decimalHour) {
    $hours = floor($decimalHour);
    $minutes = round(($decimalHour - $hours) * 60);

    // Handle edge case: 60 minutes
    if ($minutes == 60) {
        $hours++;
        $minutes = 0;
    }

    return "{$hours} hr {$minutes} min";
}
}
if (!function_exists('calculate_charge_final')) {
    function calculate_charge_final($start_time, $end_time, $booked_hours, $fees = ['first_hour_charges'=>150,'next_hour_charges'=>100])
    {
		$ci = &get_instance();
        $tax_percent  = 5;
        $platform_fee = 0;

        $start = strtotime($start_time);
        $end   = strtotime($end_time);

        // Total minutes
        $total_minutes = ceil(($end - $start) / 60);

        // Round up to nearest 30 min
        //$total_minutes = ceil($total_minutes / 30) * 30;

        // $actual_hours = $total_minutes / 60;
        $actual_hours = number_format($total_minutes / 60, 4, '.', '');
        // Minimum 1 hour calculation
        $actual_hours = max($actual_hours, 1);

        $first_hour_rate = !empty($fees['first_hour_charges']) ? $fees['first_hour_charges'] : 150;
        $next_hour_rate  = !empty($fees['next_hour_charges'])  ? $fees['next_hour_charges']  : 100;
        $late_night_rate = !empty($fees['late_night_rate'])    ? $fees['late_night_rate']    : (!empty($fees['late_night_price']) ? $fees['late_night_price'] : 0);
        $cancle_charge = !empty($fees['cancle_charge'])    ? $fees['cancle_charge']    : 0;

        $first_hour_charge = 0;
        $next_hours_charge = 0;
        $late_night_charge = 0;

        // Apply late night logic ONLY if rate > 0
        $apply_late_night = ($late_night_rate > 0);

        // Define 9 PM time (only meaningful if enabled)
        $late_night_start = strtotime(date('Y-m-d', $start) . ' 21:00:00');

        // Hour-by-hour calculation
        $current_time    = $start;
        $remaining_hours = $actual_hours;
        $hour_index      = 1;

        // Ensure we calculate even for partial hours
        while ($remaining_hours > 0) {
            $chunk = min(1, $remaining_hours);

            // Check late night only if enabled
             $is_late_night = ($apply_late_night && $current_time >= $late_night_start);

            if ($is_late_night) {
                $late_night_charge += $chunk * $late_night_rate;
            } else {
                if ($hour_index == 1) {
                    $first_hour_charge += $chunk * $first_hour_rate;
                } else {
                    $next_hours_charge += $chunk * $next_hour_rate;
                }
            }

            $current_time += 3600;
            $remaining_hours -= $chunk;
            $hour_index++;
        }
		
		

        $subtotal = $first_hour_charge + $next_hours_charge + $late_night_charge + $cancle_charge;

        $tax_amount = ($tax_percent / 100) * $subtotal;
        $total      = $subtotal + $tax_amount + $platform_fee;

        return [
            'total_minutes'      => round($total_minutes, 2),
            'actual_hours'      =>sprintf("%0.2f",$actual_hours),
            'first_hour_charge' => round($first_hour_charge, 2),
            'first_hour_rate'   => $first_hour_rate,
            'next_hours_charge' => round($next_hours_charge, 2),
            'next_hour_rate'    => $next_hour_rate,
            'late_night_charge' => round($late_night_charge, 2),
            'late_night_rate'   => $late_night_rate,
            'cancel_charge'   => round($cancle_charge, 2),
            'subtotal'          => round($subtotal, 2),
            'tax_amount'        => round($tax_amount, 2),
            'platform_fee'      => round($platform_fee, 2),
            'total'             => round($total, 2),
			'fees'=>$fees,
			'tax_percent'=>$tax_percent
        ];
    }
}
if (!function_exists('sendSMS')) {
    function sendSMS($to,$templateId='',$message='')
    {
		$apiKey = "9f73aa89058a727489b70dd1ad390ec0";     
		$senderId = "SNAPHIVE";   
		

		$data = array(
			"api_key" => $apiKey,
			"msg" => $message,
			"senderid" => $senderId,
			"templateID" => $templateId,
			"coding" => "1",
			"to" => $to,
			"callbackData" => "cb"
		);

		$headers = array(
			"Content-Type: application/json",
			"Authorization: Key ".$apiKey
		);

		$url = "https://sms.rupayaninfotech.com/api/api.php";

		$ch = curl_init($url);

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

		$result = curl_exec($ch);

		if (curl_errno($ch)) {
			log_message('error', 'SMS CURL ERROR: '.curl_error($ch));
		}

		curl_close($ch);

		// ALWAYS LOG RESPONSE
		//log_message('error', 'SMS API RESPONSE: '.$result);

		$response_data = json_decode($result, true);
		//print_r($response_data);
		if(isset($response_data['success']) && strtolower($response_data['success']) == '1'){
			$response['status'] = 1;
			$response['otp'] = isset($otp) ? $otp : '';

		}else{
			$response['otp'] = isset($otp) ? $otp : '';
			$response['status'] = 0;
			$response['errors'] = 'SMS sending failed';

			log_message('error', 'SMS FAILED RESPONSE: '.$result);
		}
		return $response;
	}
}
