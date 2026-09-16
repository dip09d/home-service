<div class="row">
	<div class="col-sm-6">
    </div>
</div>
<form role="form" id="add_form" action="<?php echo $action; ?>" onsubmit="submitForm(this, event)">
    <input type="hidden" name="ID" value="<?php echo $member_id;?>"/>
    <input type="hidden" name="page" value="<?php echo $page;?>"/>            
        <div class="form-group">
            <label class="form-label">Career Level</label>
            <select class="form-control" name="member_professional[member_career_level]">
                <option value="">-Select-</option>
                <?php print_select_option($options['career_level'], 'career_level_id', 'name', (!empty($detail['member_professional']['member_career_level']) ? $detail['member_professional']['member_career_level']['ID'] : '')); ?>
            </select>
        </div>
        
        <div class="form-group">
            <label class="form-label">Current Location</label>
            <select class="form-control" name="member_address[member_current_location]">
                <option value="">-Select-</option>
                <?php print_select_option(get_all_country(), 'country_code', 'country_name', (!empty($detail['member_address']['member_current_location']) ? $detail['member_address']['member_current_location']['code'] : '')); ?>
            </select>
        </div>
        
        <div class="form-group">
            <label class="form-label">Current Position</label>
            <select class="form-control" name="member_professional[member_current_position]">
                <option value="">-Select-</option>
                <?php print_select_option($options['current_position'], 'position_id', 'name', (!empty($detail['member_professional']['member_current_position']) ? $detail['member_professional']['member_current_position']['ID'] : '')); ?>
            </select>
        </div>
        
        <div class="form-group">
            <label class="form-label">Current Company</label>
            <input type="text" class="form-control" name="member_professional[member_current_company]" value="<?php echo !empty($detail['member_professional']['member_current_company']) ? $detail['member_professional']['member_current_company'] : ''; ?>" /> 
            
        </div>
        
        
        <div class="form-group">
            <label class="form-label">Salary Expectations</label>
            <select class="form-control" name="member_professional[member_salary_expectation]">
                <option value="">-Select-</option>
                <?php print_select_option($options['salary_expectation'], 'salary_id', 'name', (!empty($detail['member_professional']['member_salary_expectation']) ? $detail['member_professional']['member_salary_expectation']['ID'] : '')); ?>
            </select>
        </div>
        
        <div class="form-group">
            <label class="form-label">Commitment</label>
            <select class="form-control" name="member_professional[member_commitment]">
                <option value="">-Select-</option>
                <?php print_select_option($options['commitment'], 'commitment_id', 'name', (!empty($detail['member_professional']['member_commitment']) ? $detail['member_professional']['member_commitment']['ID'] : '')); ?>
            </select>
        </div>
        
        <div class="form-group">
            <label class="form-label">Notice Period</label>
            <select class="form-control" name="member_professional[member_notice_period]">
                <option value="">-Select-</option>
                <?php print_select_option($options['notice_period'], 'notice_period_id', 'name', (!empty($detail['member_professional']['member_notice_period']) ? $detail['member_professional']['member_notice_period']['ID'] : '')); ?>
            </select>
        </div>
        
        <div class="form-group">
            <label class="form-label">Visa Status</label>
            <select class="form-control" name="member_professional[member_visa_status]">
                <option value="">-Select-</option>
                <?php print_select_option($options['visa_status'], 'visa_status_id', 'name', (!empty($detail['member_professional']['member_visa_status']) ? $detail['member_professional']['member_visa_status']['ID'] : '')); ?>
            </select>
        </div>
        <button type="submit" class="btn btn-site">Save</button>
</form>
	
<script>
function submitForm(form, evt){
	evt.preventDefault();
	ajaxSubmit($(form), onsuccess);
}

function onsuccess(res){
	if(res.cmd && res.cmd == 'reload'){
		location.reload();
	}
}

</script>