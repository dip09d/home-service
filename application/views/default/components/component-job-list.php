<?php if($view && $view == 'list'){ ?>

<?php if($jobs){foreach($jobs as $k => $v){ ?>
<div class="card">
			<div class="card-body">
				<h4><?php echo $v->title;?></h4>
				<p><b><?php echo __('component_joblist_experience','Experience');?>:</b> <?php echo $v->experience;?></p>
				<p><b><?php echo __('component_joblist_skill','Skills');?>:</b> <?php echo $v->skills;?></p>
				<p><b><?php echo __('component_joblist_salary','Salary');?>:</b> <?php echo $v->salary;?></p>
				<div class="details">
				<p><?php echo $v->short_dscr;?></p>
				<a href="javascript:void(0)" class="btn btn-border" onclick="apply_job('<?php echo $v->job_id;?>');"><?php echo __('component_joblist_apply_now','Apply Now');?></a>
				<a href="<?php echo base_url('job-detail-'.$v->job_id);?>" class="btn btn-border float-end"><?php echo __('component_joblist_view_details','View Details');?></a>
				</div>
			</div>
		</div>
<?php } } ?>

<?php }else{ ?>
<div class="row">
	<?php if($jobs){foreach($jobs as $k => $v){ ?>
	<aside class="col-md-4">
		<div class="card">
			<div class="card-body">
				<h4><?php echo $v->title;?></h4>
				<p><b><?php echo __('component_joblist_experience','Experience');?>:</b> <?php echo $v->experience;?></p>
				<p><b><?php echo __('component_joblist_skill','Skills');?>:</b> <?php echo $v->skills;?></p>
				<p><b><?php echo __('component_joblist_salary','Salary');?>:</b> <?php echo $v->salary;?></p>
				<div class="details">
				<p><?php echo $v->short_dscr;?></p>
				<a href="javascript:void(0)" class="btn btn-border" onclick="apply_job('<?php echo $v->job_id;?>');"><?php echo __('component_joblist_apply_now','Apply Now');?></a>
				<a href="<?php echo base_url('job-detail-'.$v->job_id);?>" class="btn btn-border float-end"><?php echo __('component_joblist_view_details','View Details');?></a>
				</div>
			</div>
		</div>
	</aside>
	<?php } } ?>
</div>

<?php } ?>


<script>
function apply_job(job_id){
	var url = '<?php echo base_url('career/load_ajax_page?page=apply_job&job_id=');?>'+job_id;
	load_ajax_modal(url);
}
</script>