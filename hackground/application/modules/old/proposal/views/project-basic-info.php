<style>
	.sidebar-widget {
    margin-bottom: 20px;
    display: block;
}
.job-overview {
    border-radius: 4px;
    background-color: #f9f9f9;
}
.job-overview .job-overview-headline {
    color: #333;
    font-size: 2rem;
    padding: 12px 20px;
    background-color: #f0f0f0;
    color: #333;
    position: relative;
    border-radius: 4px 4px 0 0;
}
.job-overview .job-overview-inner {
    padding: 20px;
}
.job-overview .job-overview-inner ul {
    padding: 0;
    margin: 0;
    list-style: none;
}
.job-overview .job-overview-inner ul li {
    position: relative;
    display: block;
    padding-left: 30px;
    margin-bottom: 1rem;
}
.job-overview .job-overview-inner ul li span {
    font-weight: 600;
    color: #333;
    margin: 0;
    padding: 0;
    display: block;
}
.job-overview .job-overview-inner ul li i {
    position: absolute;
    left: 0;
    top: 0;
    font-size: 24px;
    color: #66676b;
}
</style>

    <form role="form" id="add_form" action="<?php echo $action; ?>" onsubmit="submitForm(this, event)">
    <input type="hidden" name="ID" value="<?php echo $project_id;?>"/>
    <input type="hidden" name="page" value="<?php echo $page;?>"/>
    
        <?php /*
        <div class="form-group">
            <label for="name">Posted On </label>  &nbsp;  &nbsp;  
            <span><?php echo $detail['project_posted_date']; ?></span>
        </div>
        
        <div class="form-group">
            <label for="name">Expire On </label> &nbsp;  &nbsp;  
            <span><?php echo $detail['project_expired_date']; ?></span>
        </div>
    
        <div class="form-group">
            <label for="project_title">Project Title </label>
            <input type="text" class="form-control reset_field" id="project_title" name="project[project_title]" autocomplete="off" value="<?php echo $detail['project_title']; ?>">
        </div>
        
        <div class="form-group">
            <label for="name">Short Info </label>
            <textarea class="form-control" name="project[project_short_info]"><?php echo $detail['project_short_info']; ?></textarea>
        </div>*/?>
        
        
        <div class="form-group">
            <label for="name" class="form-label">Description </label>
            <textarea class="form-control" id="project_description" rows="5" name="project_additional[project_description]"><?php echo $detail['project_description']; ?></textarea>
            <?php //echo get_editor('project_description');?>
        </div>
        <button type="submit" class="btn btn-site">Save</button>
        
    </form>
	
	<?php /*
	<div class="col-sm-4">
		<div class="sidebar-widget">
			<div class="job-overview">
				<div class="job-overview-headline">Contact Infomation</div>
				<div class="job-overview-inner">
					<ul>
						<li>
							<i class="ion ion-person"></i>
							<span><?php echo $detail['contact']['contact_name']; ?></span>
						</li>
						<li>
							<i class="fa fa-phone fa-lg"></i>
							<span><?php echo $detail['contact']['contact_phone']; ?></span>
						</li>
						<li>
							<i class="fa fa-mobile"></i>
							<span><?php echo $detail['contact']['contact_whatsapp']; ?></span>
						</li>
						</ul>
				</div>
			</div>
		</div>
		
		<div class="sidebar-widget">
			<div class="job-overview">
				<div class="job-overview-headline">Location Infomation</div>
				<div class="job-overview-inner">
					<ul>
						<li>
							<i class="fa fa-map-pin"></i>
							<h5><?php echo $detail['location']['state']['name']; ?></h5>
							<span><?php echo $detail['location']['country']['name']; ?></span>
						</li>
						<li>
							<i class="fa fa-map-pin"></i>
							<h5><?php echo !empty($detail['location']['location_locality']) ? $detail['location']['location_locality']: ''; ?></h5>
						</li>
					</ul>
				</div>
			</div>
		</div>
	</div>
	*/?>

<?php //get_print($detail, false); ?>

<script>

function CKupdate(){
    for ( instance in CKEDITOR.instances )
        CKEDITOR.instances[instance].updateElement();
}


function submitForm(form, evt){
	evt.preventDefault();
	/* CKupdate(); */
	ajaxSubmit($(form), onsuccess);
}

function onsuccess(res){
	if(res.cmd && res.cmd == 'reload'){
		location.reload();
	}
}

</script>