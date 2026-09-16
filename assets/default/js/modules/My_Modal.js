
/*

Author: Venkatesh bishu
Features :

Dynamic Modal
Ajax Modal 
Can open and manage more than one module at a time like opening new modal over a modal

Methods : 
open
openURL
close
getID
clear -- will remove all modal html from the page

options = {
    title : '',
    content: '',

}



*/

var Modal = (function($){


   var modal_template = 
   
   '<div id="{MODAL_ID}" class="modal fade" role="dialog">'+
     '<div class="modal-dialog">'+
   
       
       '<div class="modal-content">'+
       '{MODAL_HEADER}'+
         '<div class="modal-body">{MODAL_CONTENT}</div>'+
        '{MODAL_FOOTER}'+
       '</div>'+
   
     '</div>'+
   '</div>'
   
   ,
   
   modal_header = 

   '<div class="modal-header">'+
        '<button type="button" class="close" data-dismiss="modal">&times;</button>'+
        '<h4 class="modal-title">{MODAL_TITLE}</h4>'+
    '</div>'
,


    modal_footer = 

        '<div class="modal-footer">'+
             '<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>'+
        '</div>'

    ;



    var IDS = [];
    var $body = $('body');
    var defaults = {
        title : 'My Modal',
        content: 'Ok, this is it !!',
    };


    var _generateModalID = function(){
        var id = 'mydmodal_'+Date.now();
        if(IDS.indexOf(id) !== -1){
            _generateModalID();
        }

        return id;
    };

    var _prepare_html = function(setting){
    
        var result = null;
        if(setting.title){
            setting.header = modal_header.replace(/{MODAL_TITLE}/g, setting.title);
        }else{
            setting.header = '';
        }
        
        if(setting.buttons){
            setting.footer = '';
        }else{
            setting.footer = '';
        }
        
        result = modal_template.replace(/{MODAL_HEADER}/g, setting.header);
        result = result.replace(/{MODAL_CONTENT}/g, setting.content);
        result = result.replace(/{MODAL_ID}/g, setting.MODAL_ID);
        result = result.replace(/{MODAL_FOOTER}/g, setting.footer);
        
        return result;
    };


    var _generate_raw_html = function(setting){


        var result = null;
        if(setting.title){
            setting.header = modal_header.replace(/{MODAL_TITLE}/g, setting.title);
        }else{
            setting.header = '';
        }
        
        if(setting.content){
            setting.body = '<div class="modal-content">'+setting.content+'</div>';
        }else{
            setting.body = '';
        }


        if(setting.buttons){
            setting.footer = '';
        }else{
            setting.footer = '';
        }

        result = (setting.header+setting.body+setting.footer);
        return result;

    };

    var _get_ajax_template = function(options){
        var temp = 
   
   '<div id="{MODAL_ID}" class="modal fade" role="dialog">'+
     '<div class="modal-dialog">'+
   
       
       '<div class="modal-content">'+
       
       '</div>'+
   
     '</div>'+
   '</div>'
   
   ;

   if(options.id){
        temp = temp.replace(/{MODAL_ID}/g, options.id);
   }

   return temp;

    };

    

    var open = function(options){
        var settings = $.extend({}, defaults, options);
        settings.MODAL_ID = _generateModalID();
        
        var $my_modal = $(_prepare_html(settings));

        // holding modal id
        IDS.push(settings.MODAL_ID);

        $body.append($my_modal);
        $my_modal.modal("show");

        $my_modal.on('hidden.bs.modal', function(){
            $my_modal.remove();
            IDS.splice(IDS.indexOf(settings.MODAL_ID), 1);
            $my_modal.off("hidden.bs.modal");
            if(IDS.length > 0){
                $body.addClass('modal-open');
            }
        });

    };
    
    
    var openURL = function(options){
        var settings = $.extend({}, defaults, options);
        settings.MODAL_ID = _generateModalID();
       
        var $ajax_modal = $(_get_ajax_template({id:  settings.MODAL_ID}));

        $ajax_modal.on('hidden.bs.modal', function(){
            $ajax_modal.remove();
            IDS.splice(IDS.indexOf(settings.MODAL_ID), 1);
            $ajax_modal.off("hidden.bs.modal");
            if(IDS.length > 0){
                $body.addClass('modal-open');
            }
        });

        
        // holding modal id
        IDS.push( settings.MODAL_ID);
        $body.append($ajax_modal);

        showLoader($ajax_modal.find('.modal-content'), '', 100);
        $ajax_modal.modal('show');
        
        setTimeout(function(){
            $.get(settings.url, function(res){
                settings.content = res;
                modal_html = _generate_raw_html(settings);
                $ajax_modal.find('.modal-content').html(modal_html);
            });
        }, 700);

    };
    

    return {
        open: open,
        openURL: openURL,
    }

})(jQuery);