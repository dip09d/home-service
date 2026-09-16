<div class="row">
	<?php if($list){foreach($list as $k => $v){ ?>
	<aside class="col-lg-4 col">
		<!-- Photo Box -->
		<div href="#" class="photo-box" data-background-image="<?php echo $v->cover_photo_url; ?>">
			<div class="photo-box-title"><h3><?php echo $v->title; ?></h3></div>
			<div class="photo-box-content">
				<h3><?php echo strlen($v->title) > 25 ? substr($v->title, 0, 25).'...' : $v->title; ?></h3>
				<p><?php echo $v->short_dscr; ?></p>
				<a href="<?php echo base_url('project-detail-'.$v->project_id); ?>" class="btn btn-border"><?php echo __('component_joblist_read_more','Read More');?></a>
			</div>
		</div>
	</aside>
	<?php } } ?>
	
	<!--
	<aside class="col-md-4">
		
		<div href="#" class="photo-box" data-background-image="<?php echo IMAGE; ?>project_2.jpg">
			<div class="photo-box-title"><h3>Project Title</h3></div>
			<div class="photo-box-content">
				<h3>Project Title</h3>
				<p>Nam libero tempore, cum soluta nobis est repellendus. Temporibus autem quibusdam aut officiis debitis aut rerum necessitatibus saepe eveniet ut et voluptates repudiandae sint et molestiae non recusandae.</p>
				<a href="<?php echo base_url('project-detail'); ?>" class="btn btn-border">Read More</a>
			</div>
		</div>
	</aside>
	 -->

</div>