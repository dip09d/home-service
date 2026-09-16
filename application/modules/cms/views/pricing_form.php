<section class="sub-banner">
	<?php $plan_name=getField('title','plan','id',$plan); ?>
	<h2 class="text-center">Send Request For <span><?php echo !empty($plan_name)?$plan_name:'FREE'; ?></span></h2>
</section>    
<section class="sec overview" id="overview">
    <div class="container">		
        <div id="contact">
        <?php $this->load->view('pricing_form_ajax');?>
        </div>
    </div>    
</section>
<script>
function submitContact(event , ele){
	event.preventDefault();
		var form_data = $('#contact_form').serialize();
		$.ajax({
			url : '<?php echo base_url('cms/user_contact_ajax/'.$plan)?>',
			type: 'POST',
			data: form_data,
			beforeSend: function(){
				$(ele).html('Posting..');
			},
			success: function(res){
				$('#contact').html(res);
			}
		});
}

</script>