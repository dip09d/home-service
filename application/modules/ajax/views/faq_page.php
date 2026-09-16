<section class="sub-banner">
	<h2 class="text-center">Faq</h2>
</section>
<div class="dark-wrapper ">
  <div class="container inner">    
	<div class="row">
    <aside class="col-md-3 col-sm-4 col-xs-12">
	<h4 class="title-21">Topics</h4>
    
<div class="list-group">
<?php if(count($faq_topics) > 0){foreach($faq_topics as $k => $v){ ?>
  <a href="<?php echo base_url('cms/faq/'.$v['id']);?>" class="list-group-item <?php echo $v['id'] == $active_topic ? 'active' : '';?>"><?php echo $v['category'];?></a>    
<?php } } ?>
</div>
	</aside>
    <aside class="col-md-9 col-sm-8 col-xs-12">    
    <h4 class="title-21">&nbsp;</h4>
		<div class="panel-group" id="accordion">
		<?php if(count($questions) > 0){foreach($questions as $k => $v){ ?>
		<div class="panel panel-default">
		<div class="panel-heading">
		  <h4 class="panel-title">
			<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapse<?php echo $v['id']?>">
			  <span class="glyphicon glyphicon-minus"></span>
				<?php echo $v['question'];?>
			</a>
		  </h4>
		</div>
		<div id="collapse<?php echo $v['id']?>" class="panel-collapse collapse <?php echo $k == 0 ? 'in' : ''?>">
		  <div class="panel-body">
			<?php echo $v['answer'];?>
		  </div>
		</div>
	  </div>
		<?php } }else{ ?>
		<h4 class="text-danger">No Faq</h4>
		<?php } ?>
</div>
	</aside>
	</div>

  </div>
</div>

<script type="text/javascript">
$('.collapse').on('shown.bs.collapse', function(){
$(this).parent().find(".glyphicon-plus").removeClass("glyphicon-plus").addClass("glyphicon-minus");
}).on('hidden.bs.collapse', function(){
$(this).parent().find(".glyphicon-minus").removeClass("glyphicon-minus").addClass("glyphicon-plus");
});
</script>