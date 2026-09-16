
<section class="sub_banner">
	<h2 class="center">Faq</h2>
</section>  
<section class="sec">
<div class="dark-wrapper" style="min-height:600px">
	<div class="container inner">
  
	<div class="row">
    <aside class="col m3">
	<h4 class="title-21">Topics</h4>  
    <div class="collection">
    <?php if(count($faq_topics) > 0){foreach($faq_topics as $k => $v){ ?>
      <a href="<?php echo base_url('cms/faq/'.$v['id']);?>" class="collection-item <?php echo $v['id'] == $active_topic ? 'active' : '';?>"><?php echo $v['category'];?></a>    
    <?php } } ?>
    </div>
	</aside>
    <aside class="col m9">  
    <h4 class="title-21">&nbsp;</h4>
	<ul class="collapsible" data-collapsible="accordion">
	<?php if(count($questions) > 0){foreach($questions as $k => $v){ ?>
    <li>
      <div class="collapsible-header">
		<?php echo $v['question'];?>
	  </div>
      <div id="collapse<?php echo $v['id']?>" class="collapsible-body <?php echo $k == 0 ? 'in' : ''?>"><span><?php echo $v['answer'];?></span></div>
	  <?php } }else{ ?>
		<h4 class="text-danger center white" style="padding:10px">No Faq</h4>
		<?php } ?>
    </li>
  </ul>
	</aside>
	</div>
    
	</div>
</div>
</section>
<script type="text/javascript">
$('.collapse').on('shown.bs.collapse', function(){
$(this).parent().find(".glyphicon-plus").removeClass("glyphicon-plus").addClass("glyphicon-minus");
}).on('hidden.bs.collapse', function(){
$(this).parent().find(".glyphicon-minus").removeClass("glyphicon-minus").addClass("glyphicon-plus");
});
</script>