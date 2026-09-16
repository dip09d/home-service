/*   ------------- DOCUMENTATION -------------------- */
/*
|		Developed by Venkatesh bishu
|		
|		how to use ?
|		
		Step 1 : var yourvar = new Appautocomplete($('#inputelementid'), {option});
|
|		options : 
|		{
			data: [],
			dataURL : URL // if data is fetched from the server
			value : Input field name If autocomplete has different value to store  (Input type is hidden for this option)
			
		}
		dataformat : {label : label, value: value, icon: icon_src}
|

*//*----------------------------------------------------*/



function Appautocomplete(ele, setting){
	var nlen = $('.autocomplete').length + 1;
	this.element = ele;
	this.setting = setting;
	this.term = '';
	this.id = 'autocomplete-'+new Date().getTime()+'_'+nlen;
	var self = this;
	this.no_result_tmp = '<li class="noresult">No Result found for {term}</li>';
	this.footer = '';
	this.css_class = setting.css_class || '';
	this.suggestion = setting.suggestion || false;
	this.init = function(){
		self.element.wrap('<div class="autocomplete autocompleteCustomBox '+self.css_class+'" id="'+self.id+'"></div>');
		if(setting.value !== undefined){
			self.element.after('<input type="hidden" name="'+setting.value+'" />');
		}
		$('#'+self.id).append('<ul class="data-list autocomplete-custom"></ul>');
		$('#'+self.id).prepend('<span class="unset remove" style="display:none;"><i class="material-icons">close</i></span>');
		
		$(self.element).on('input', function(){
			var val = self.element.val();
			self.element.removeClass('valueset');
			self.term = val;
			$('#'+self.id).find('ul.data-list').show();
			if(self.setting.dataURL){
				$.ajax({
					url: self.setting.dataURL,
					type: 'get',
					data: {term : val},
					dataType: 'json',
					success: function(res){
						self.render(res, val);
					}
				});
			}else{
				self.render(self.setting.data, val);
			}
			
		});
		
		$('#'+self.id).find('ul.data-list').on('click', 'li:not(.noresult)', function(){
			var item = $(this).data('item');
			self.setValue(item);
			
		});
		
		$('#'+self.id).find('span.unset').on('click', function(){
			$('#'+self.id).find('input').val('');
			$(this).hide();
		});
		
		$('body').click(function(e){
			var c = $('#'+self.id);
			if($(e.target).is(c) && c.has(e.target).length > 0){
				
			}else{
				self.hideList();
				self.checkValue();
			}
		  
		});
    
  };
  
  
  this.resetField = function(){
	  $('#'+self.id).find('span.unset').click();
  };
  
  this.hideList = function(){
  	$('#'+self.id).find('ul.data-list').hide();
  };
  
  this.setValue = function(item){
		self.element.val(item.label);
		self.element.addClass('valueset');
		if(item.value !== undefined && setting.value !== undefined){
			$('input[name="'+setting.value+'"]').val(item.value);
		}else if(setting.value !== undefined){
			$('input[name="'+setting.value+'"]').val(item.label);
		  }	
		$('#'+self.id).find('span.unset').show();    
  };
  
   this.checkValue = function(){
   		if(setting.value !== undefined && !self.suggestion){
      		var v = $('input[name="'+setting.value+'"]').val();
          var vset = self.element.is('.valueset');
          if(v == '' || !vset){
          			$('#'+self.id).find('input').val('');
                $('#'+self.id).find('span.unset').hide();
          }
      }
   };
  
	this.render = function(d, text){
 
		var no_res = self.no_result_tmp;
		no_res = no_res.replace('{term}', text);
		var html = '';
		if(d.length > 0){
		for(var i=0; i< d.length; i++){
			var icon = d[i].icon || '';
			html += '<li data-item=\''+JSON.stringify(d[i])+'\'">'+d[i].label+'</li>';
		}
		html += self.footer.replace('{term}', text);
		}else{
			html += no_res;
		}
		$('#'+this.id).find('ul.data-list').html(html);
	};
  
  self.init();
  
}

