<?php
if(!function_exists('create_invoice_invoice')){
	function create_invoice_invoice($issuer_member_id='', $recipient_member_id='',$data=array()){
		if(empty($recipient_member_id)){
			die('Invalid input data');
		}
		
		if(array_key_exists('recipient_email',$data)){
			$recipient_email=$data['recipient_email'];	
		}else{
			$recipient_email=getFieldData('member_email','member','member_id',$recipient_member_id);
		}
		$round_up_val=0;
		if(array_key_exists('round_up_val',$data)){
			$round_up_val=$data['round_up_val'];	
		}
		$recipient_organization_id=NULL;
		if(array_key_exists('recipient_organization_id',$data)){
			$recipient_organization_id=$data['recipient_organization_id'];	
		}
		
		$invoice_number=generate_invoice_number();
		$invoice_type_id=getFieldData('invoice_type_id','invoice_type','name_tkey',$data['invoice_type']);
		$invoice=array(
		'invoice_type_id'=>$invoice_type_id,
		'invoice_number'=>$invoice_number,
		'issuer_member_id'=>$issuer_member_id,
		'issuer_organization_id'=>NULL,
		'recipient_member_id'=>$recipient_member_id,
		'recipient_organization_id'=>NULL,
		'invoice_date'=>date('Y-m-d H:i:s'),
		'recipient_email'=>$recipient_email,
		'round_up_amount'=>$round_up_val,
		'invoice_status'=>0,
		);
		if($recipient_organization_id){
			$invoice['recipient_organization_id']=$recipient_organization_id;
		}
		$invoice_id=insert_record('invoice',$invoice,TRUE);
		if($invoice_id){
			$issuer_information_arr=array();
			$recipient_information_arr=array();
			if($issuer_member_id){
				$memberInfo=getData(array(
					'select'=>'m.member_name,m_a.member_timezone,m_a.member_city,m_a.member_state,m_a.member_address_1,m_a.member_address_2,m_a.member_pincode,m_a.member_mobile,,m_a.member_mobile_code,c_n.country_name',
					'table'=>'member as m',
					'join'=>array(
						array('table'=>'member_address as m_a','on'=>'m.member_id=m_a.member_id','position'=>'left'),
						array('table'=>'country as c','on'=>'m_a.member_country=c.country_code','position'=>'left'),
						array('table'=>'country_names as c_n','on'=>"(c.country_code=c_n.country_code and c_n.country_lang='".get_active_lang()."')",'position'=>'left')
					),
					'where'=>array('m.member_id'=>$issuer_member_id),
					'single_row'=>true,
				));
				$issuer_information_arr['I_name']=$memberInfo->member_name;
				$I_addr=$I_addr2=$I_country=$I_city=$I_state=$I_pin='';
				if($memberInfo->member_address_1){
					$I_addr=$memberInfo->member_address_1;
				}
				if($memberInfo->member_address_2){
					$I_addr2=$memberInfo->member_address_2;
				}
				if($memberInfo->member_city){
					$I_city=$memberInfo->member_city;
				}
				if($memberInfo->member_state){
					$I_state=$memberInfo->member_state;
				}
				if($memberInfo->member_pincode){
					$I_pin=$memberInfo->member_pincode;
				}
				if($memberInfo->country_name){
					$I_country=$memberInfo->country_name;
				}
				$issuer_information_arr['I_addr']=$I_addr;
				$issuer_information_arr['I_addr2']=$I_addr2;
				$issuer_information_arr['I_city']=$I_city;
				$issuer_information_arr['I_state']=$I_state;
				$issuer_information_arr['I_country']=$I_country;
				$issuer_information_arr['I_pin']=$I_pin;

			}else{
				$issuer_information_arr['I_name']=get_setting('website_name');
				$issuer_information_arr['I_addr']='';
				$issuer_information_arr['I_addr2']='';
				$issuer_information_arr['I_city']='';
				$issuer_information_arr['I_state']='';
				$issuer_information_arr['I_country']='';
				$issuer_information_arr['I_pin']='';
			}
			
			if($recipient_organization_id){
				$organizationInfo=getData(array(
					'select'=>'m.member_name,o.organization_name,o_a.organization_timezone,o_a.organization_city,o_a.organization_state,o_a.organization_address_1,o_a.organization_address_2,o_a.organization_pincode,o_a.organization_mobile,o_a.organization_vat_number,o_a.display_in_invoice,o_a.organization_mobile_code,c_n.country_name',
					'table'=>'member as m',
					'join'=>array(
					array('table'=>'organization as o','on'=>'m.member_id=o.member_id','position'=>'left'),
					array('table'=>'organization_address as o_a','on'=>'o.organization_id=o_a.organization_id','position'=>'left'),
					array('table'=>'country as c','on'=>'o_a.organization_country=c.country_code','position'=>'left'),
					array('table'=>'country_names as c_n','on'=>"(c.country_code=c_n.country_code and c_n.country_lang='".get_active_lang()."')",'position'=>'left')
					),
					'where'=>array('m.member_id'=>$recipient_member_id),
					'single_row'=>true,
				));
				if($organizationInfo->organization_name){
					$R_name=$organizationInfo->organization_name;
				}else{
					$R_name=$organizationInfo->member_name;
				}
				$recipient_information_arr['R_name']=$R_name;
				$R_addr=$R_addr2=$R_country=$R_city=$R_state=$R_pin=$R_vat='';
				if($organizationInfo->organization_address_1){
					$R_addr=$organizationInfo->organization_address_1;
				}
				if($organizationInfo->organization_address_2){
					$R_addr2=$organizationInfo->organization_address_2;
				}
				if($organizationInfo->organization_city){
					$R_city=$organizationInfo->organization_city;
				}
				if($organizationInfo->organization_state){
					$R_state=$organizationInfo->organization_state;
				}
				if($organizationInfo->organization_pincode){
					$R_pin=$organizationInfo->organization_pincode;
				}
				if($organizationInfo->organization_vat_number){
					$R_vat=$organizationInfo->organization_vat_number;
				}
				if($organizationInfo->country_name){
					$R_country=$organizationInfo->country_name;
				}
				$recipient_information_arr['R_addr']=$R_addr;
				$recipient_information_arr['R_addr2']=$R_addr2;
				$recipient_information_arr['R_city']=$R_city;
				$recipient_information_arr['R_state']=$R_state;
				$recipient_information_arr['R_country']=$R_country;
				$recipient_information_arr['R_pin']=$R_pin;
				$recipient_information_arr['R_vat']=$R_vat;
			}else{	
				$memberInfo=getData(array(
					'select'=>'m.member_name,m_a.member_timezone,m_a.member_city,m_a.member_state,m_a.member_address_1,m_a.member_address_2,m_a.member_pincode,m_a.member_mobile,,m_a.member_mobile_code,c_n.country_name',
					'table'=>'member as m',
					'join'=>array(
						array('table'=>'member_address as m_a','on'=>'m.member_id=m_a.member_id','position'=>'left'),
						array('table'=>'country as c','on'=>'m_a.member_country=c.country_code','position'=>'left'),
						array('table'=>'country_names as c_n','on'=>"(c.country_code=c_n.country_code and c_n.country_lang='".get_active_lang()."')",'position'=>'left')
					),
					'where'=>array('m.member_id'=>$recipient_member_id),
					'single_row'=>true,
				));
				$recipient_information_arr['R_name']=$memberInfo->member_name;
				$R_addr=$R_addr2=$R_country=$R_city=$R_state=$R_pin='';
				if($memberInfo->member_address_1){
					$R_addr=$memberInfo->member_address_1;
				}
				if($memberInfo->member_address_2){
					$R_addr2=$memberInfo->member_address_2;
				}
				if($memberInfo->member_city){
					$R_city=$memberInfo->member_city;
				}
				if($memberInfo->member_state){
					$R_state=$memberInfo->member_state;
				}
				if($memberInfo->member_pincode){
					$R_pin=$memberInfo->member_pincode;
				}
				if($memberInfo->country_name){
					$R_country=$memberInfo->country_name;
				}
				$recipient_information_arr['R_addr']=$R_addr;
				$recipient_information_arr['R_addr2']=$R_addr2;
				$recipient_information_arr['R_city']=$R_city;
				$recipient_information_arr['R_state']=$R_state;
				$recipient_information_arr['R_country']=$R_country;
				$recipient_information_arr['R_pin']=$R_pin;
			}
			
			if($issuer_information_arr){
				$issuer_information=serialize($issuer_information_arr);
			}else{
				$issuer_information="";
			}
			if($recipient_information_arr){
				$recipient_information=serialize($recipient_information_arr);
			}else{
				$recipient_information="";
			}
			$invoice_reference=array(
				'invoice_id'=>$invoice_id,
				'issuer_information'=>$issuer_information,
				'recipient_information'=>$recipient_information,
			);
			insert_record('invoice_reference',$invoice_reference);
		}
		return $invoice_id;
	}
}
if(!function_exists('add_invoice_row')){
	function add_invoice_row($invoice_id,$invoicerow_array=array()){
		if($invoice_id){
			if($invoicerow_array){
				foreach($invoicerow_array as $invoice_row){
					$invoice_row['invoice_id']=$invoice_id;
					insert_record('invoice_row',$invoice_row);
				}
			}
		}
	}
}



?>