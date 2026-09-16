/*
Date : 10.07.2019
Modified Date: 08.09.19


Author : Venkatesh bishu

Version : 1.2

Load More module. 

Quick Start :

var user_load_more = LoadMore.getInstance();  // get load more instance 

// configure load more
user_load_more.config({
	url : 'https://www.irctc.co.in/nget/train-list', // url 
	target : '#target', // container where result will be appended
  load_more : '#next' // load more button id or reference
});

user_load_more.start() // start

Dependency : jQuery

Modification :

-> Added new attribute 'load_more'
-> Now results will be automatically loaded on scroll event

*/


var LoadMore = (function(){
	
	if(typeof jQuery == undefined){
		throw new Error('jQuery is required ');
	}
	
	function LoadMoreClass(){
		this.defaults = {
			target : '#load_more_wrapper',
			load_more: '#load_more_btn',
			url : null,
			data:{},
			autoload: {
				autoload: false,
				target: 'window' /*possible values ('window' or element reference like '#load_more_wrapper')*/ 
			},
			onResult: function(){},
		};
 		this.setting = this.defaults;
		this.next_page_url = null;
		this.loading = false;
	}
  
	LoadMoreClass.prototype.config = function(options){
  		this.setting = $.extend({}, this.defaults, options);
	};
  
	LoadMoreClass.prototype.loadPage = function(){
		this.getData();
	};
  
	LoadMoreClass.prototype.getData = function(){
		var url = this.setting.url;
		var _self = this;
		
		if(!_self.loading){
			_self.loading = true;
			if(url){
				$.ajax({
					url : url,
					type: 'get',
					dataType: 'json',
					success: function(res){
						_self.loading = false;
					   _self.processData(res);	
					   
					   if(typeof  _self.setting.onResult == 'function'){
						  _self.setting.onResult(res);
					   }
					},
					error: function(){
					   _self.loading = false;
					}
				});
			}
		}
		
  
	};
  
	LoadMoreClass.prototype.getNext = function(){
		var url = this.next_page_url;
		var _self = this;
		
		if(!_self.loading){
			
			_self.loading = true;
			if(url){
				$.ajax({
					url : url,
					type: 'get',
					dataType: 'json',
					success: function(res){
						_self.loading = false;
					   _self.processData(res);	
					   
					    if(typeof  _self.setting.onResult == 'function'){
						  _self.setting.onResult(res);
						}
					   
					},
					error: function(){
					   _self.loading = false;
					}
				});
			}
		}
		
	};
  
	LoadMoreClass.prototype.start = function(options){
  		if(options){
			this.setting = $.extend({}, this.defaults, options);
		}
		var _self = this;
		_self.$target = $(this.setting.target);
		_self.$load_more = $(this.setting.load_more);
		
		_self._attach_events();
		
		_self.loadPage();
	}
  
	LoadMoreClass.prototype.processData = function(res){
  		if(res.status === 1){
			this.$target.append(res.html);
			if(res.next){
			   this.next_page_url = res.next;
			}else{
				this.next_page_url = null;
			   this.$load_more.remove();
			}
		}
	};
	
	
	LoadMoreClass.prototype._attach_events = function(){
		var _self = this;
		_self.$load_more.click(function(e){
			e.preventDefault();
		  _self.getNext();
		});
		
		if(_self.setting.autoload.autoload){
			var target = _self.setting.autoload.target;
			if(target === 'window'){
				$(document).on('scroll', function(){
					var bottomPos = $(document).height();
					bottomPos -= 5; /* Top from buttom in pixels */ 
					
					var winHeight = $(window).height();
					var scrollTopPos = $(window).scrollTop();
					var scrollPos = (winHeight+scrollTopPos);
					if(scrollPos >= bottomPos){
						_self.getNext();
					}
				});
			}else{
				$(target).on('scroll', function(){
					var bottomPos = $(target)[0].scrollHeight;
					bottomPos -= 5; /* Top from buttom in pixels */ 
					
					var winHeight = $(target).height();
					var scrollTopPos = $(target).scrollTop();
					var scrollPos = (winHeight+scrollTopPos);
					if(scrollPos >= bottomPos){
						_self.getNext();
					}
				});
			}
			
		}
	};
  
  
  /*----------------------------------------------------------*/
  
  var getInstance = function(){
  	var instance = new LoadMoreClass();
    return instance;
  };
  
  
  return {
  	getInstance: getInstance,
	version: '1.2'
  }

})();

/* var user_load_more = LoadMore.getInstance();
var user_load_more2 = LoadMore.getInstance();

user_load_more.config({
  url : 'https://www.irctc.co.in/nget/train-list',
	target : '#target',
  load_more : '#next'
});

user_load_more2.config({
  url : 'https://www.irctc.co.in/nget/train-list',
	target : '#target2',
  load_more : '#next2'
});

user_load_more.start();

console.log(user_load_more);
console.log(user_load_more2); */


