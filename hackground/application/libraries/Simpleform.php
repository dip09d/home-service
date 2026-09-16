<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Simpleform {
	private $input_type;
	private $input_name;
	private $input_class;
	private $input_id;
	private $input_help;
	private $input_placeholder;
	private $input_value;
	private $input_option;
	private $input_label;
   
	public function __construct(){
		$this->input_name = '';
		$this->input_type = '';
		$this->input_class = '';
		$this->input_id = $this->input_name ;
		$this->input_help = '';
		$this->input_placeholder =  $this->input_name ;
		$this->input_value = '';
		$this->input_option = array();
		$this->input_label = '';
		$this->attribute = '';
	}
	
	// function for printing form
	
	public function print_form($form = array()){
		if(!$form){
			echo "Please pass the forms";
			return false;
		}
		echo '<form role="form" class="form-horizontal" action="" enctype="multipart/form-data" method="post">'; 
		foreach($form['fields'] as $k => $v){
			$this->input_name = $k;
			$this->input_type = $v['type'];
			$this->input_label = $v['label'];
			$this->input_class = (isset( $v['class'])) ? $v['class'] : "";
			$this->input_id = (isset( $v['id'])) ? $v['id'] : $this->input_name ;
			$this->input_help = (isset( $v['help'])) ? $v['help'] : "";
			$this->input_placeholder = (isset( $v['placeholder'])) ? $v['placeholder'] : "";
			$this->input_value = (isset( $v['value'])) ? $v['value'] : "";
			$this->input_option = (isset( $v['option'])) ? $v['option'] : array();
			$this->attribute = (!empty($v['attribute'])) ? $v['attribute'] : '';
			if($this->input_type == 'dropdown'){
				$options = $this->input_option;
				?>
					<div class="form-group">
						<label class="col-sm-4 control-label" style="text-align: left !important;"><?php echo $this->input_label; ?></label>
						<div class="col-sm-8">
						  <select name="<?php echo $this->input_name;?>" class="form-control <?php echo $this->input_class;?>" id="<?php echo $this->input_id;?>" <?php echo $this->attribute;?>>
								<option value=""><?php echo $this->input_label; ?></option>
								<?php if(is_array($this->input_value)){ ?>
								<?php foreach($options as $key => $value){ ?>
									<option value="<?php echo $key;?>" <?php echo (isset($this->input_value) AND in_array($key , $this->input_value)) ? 'selected="selected"' : '';?>><?php echo $value;?></option>
								<?php } ?>
								<?php }else{ ?>
								<?php foreach($options as $key => $value){ ?>
									<option value="<?php echo $key;?>" <?php echo (isset($this->input_value) AND $this->input_value == $key) ? 'selected="selected"' : '';?>><?php echo $value;?></option>
								<?php } } ?>
						  </select>
						</div>
					</div>
				<?php
			}else if($this->input_type == 'file'){
				?>
					<div class="form-group">
						<label class="col-sm-4 control-label" style="text-align: left !important;"><?php echo $this->input_label; ?></label>
						<div class="col-sm-8">
							<input type="file" name="<?php echo $this->input_name ; ?>" <?php if($this->input_id){ ?> id="<?php echo $this->input_id;?>" <?php } ?> <?php if($this->input_class){ ?> class="<?php echo $this->input_class;?>" <?php } ?> value=""/>
						</div>
					</div>
					
				<?php
			}else if($this->input_type == 'textarea'){
				?>
					<div class="form-group">
						<label class="col-sm-4 control-label" style="text-align: left !important;"><?php echo $this->input_label; ?></label>
						<div class="col-sm-8">
							<textarea class="form-control <?php echo $this->input_class?>"  <?php if($this->input_id){ ?> id="<?php echo $this->input_id;?>" <?php } ?> placeholder="<?php echo $this->input_placeholder; ?>"><?php echo $this->input_value;?></textarea>
						</div>
					</div>
				<?php
				
			}else if($this->input_type == 'text'){
				?>
				<div class="form-group">
					<label class="col-sm-4 control-label" style="text-align: left !important;"><?php echo $this->input_label; ?></label>
					<div class="col-sm-8">
						<input type="text" name="<?php echo $this->input_name ; ?>" <?php if($this->input_id){ ?> id="<?php echo $this->input_id;?>" <?php } ?> class="form-control <?php echo $this->input_class;?>" value="<?php echo $this->input_value;?>" placeholder="<?php echo $this->input_placeholder; ?>"/>
					</div>
				</div>
				<?php
				
			}else if($this->input_type == 'radio' || $this->input_type == 'checkbox'){
				$option = $this->input_option;
				?>
				<div class="form-group">
					<label class="col-sm-4 control-label" style="text-align: left !important;"><?php echo $this->input_label; ?></label>
					<div class="col-sm-8">
						<?php foreach($option as $key => $value){ ?>
						<div class="radio-inline">
							<label>
								<input name="<?php echo $this->input_name;?>" id="<?php echo ($this->input_id) ? $this->input_id : $this->input_name; ?>" value="<?php echo $key; ?>" type="<?php echo $this->input_type; ?>" <?php echo ($this->input_value AND $this->input_value == $key) ? "checked" : ""?>><?php echo $value ; ?>
							</label>
						</div>
						<?php } ?>
					</div>
				</div>
				
				<?php
			}else{
				?>
				<div class="form-group">
						<label class="col-sm-4 control-label" style="text-align: left !important;"><?php echo $this->input_label; ?></label>
						<div class="col-sm-8">
							<input type="<?php echo $this->input_type;?>" name="<?php echo $this->input_name ; ?>" <?php if($this->input_id){ ?> id="<?php echo $this->input_id;?>" <?php } ?> class="form-control <?php echo $this->input_class;?>" value="<?php echo $this->input_value;?>"/>
						</div>
				</div>
			<?php
			}
			
		}
		echo '<div class="form-group">
			<div class="col-lg-4"></div>
				<div class="col-lg-8">
					<button type="submit" class="btn btn-success btn-sm" style="margin-right: 30px"><i class="glyphicon glyphicon-ok"></i>  &nbsp; Save Changes</button>
					<button type="button" name="cancel" value="cancel" class="btn btn-danger btn-sm"><i class="glyphicon glyphicon-remove"></i> &nbsp; Cancel</button>
				</div>
			</div>';
			
		echo '</form>';
		
	}
	
}