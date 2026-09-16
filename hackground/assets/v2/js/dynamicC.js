String.prototype.replaceAll = function(search, replacement) {
      var target = this;
      return target.replace(new RegExp(search, 'g'), replacement);
    };

    function replaceAll(str, map){
        for(key in map){
            str = str.replaceAll('{'+key+'}', map[key]);
        }
        return str;
    }


      var DynamicC = function(obj){
          this.ele = obj;
      };

      DynamicC.prototype.createTemplete = function(temp) {
        this.templete = temp;
      };

      DynamicC.prototype.prepare = function(data) {
        hhtml = '';
        temp = this.templete;
        if( data instanceof Array){
           data.forEach(function(v , k){
              hhtml += replaceAll(temp , v);
           });

        this.templete = hhtml;

        }else{
            hhtml = replaceAll(temp , data);
        }

        this.templete = hhtml;
      };

      DynamicC.prototype.render = function(type) {
        if(typeof type == 'undefined'){
          type = 'insert';
        }
        if(type == 'insert'){
          this.ele.html(this.templete);
        }
        
        if(type == 'append'){
          this.ele.append(this.templete);
        }

      };

      DynamicC.prototype.getTemplete = function() {
        return this.templete;
      };
	  
	  
(function($, window ) {

  'use strict';
		  
	  
function Template(){
	var t = {};
  
  t.templates = {};
  
  t.add = function(name, template){
  	t.templates[name] = template;
  };
  
  t.get = function(name){
  	return t.templates[name] ? t.templates[name]  : null;
  };
  
  t.getAll = function(name){
  	return t.templates;
  };
  
t.parse = function(name, data){
  var temp_str = t.get(name);
  if(temp_str != null){
	temp_str = replaceAll(temp_str , data);
   }
   return temp_str;
};
  
  return t;
}

window.Template = Template;

})(jQuery,  window );