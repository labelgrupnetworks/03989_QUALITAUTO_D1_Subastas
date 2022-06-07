/*
Name: 			UI Elements / Nestable - Examples
Written by: 	Okler Themes - (http://www.okler.net)
Theme Version: 	1.4.1
*/

(function( $ ) {

	'use strict';
		
	/*
	Update Output
	*/
	var updateOutput = function (e) {
		var list = e.length ? e : $(e.target),
			output = list.data('output');
                var menu_order = list.nestable('serialize');
                $.ajax ({
                    url: "/admin/auc-index-menu/save",
                    type: "post",
                    data: {menu_order},
                    success: function(e) {
                        if(e == 0){
                            saved();
                        }else if(e==1){
                            error('Un padre no puede ser hijo.');
                        }else if(e==2){
                            error('Un hijo no puede ser padre.');
                        }else if(e==3){
                            error('No puede haber un padre dentro de otro padre.');
                        }
                    },
                    error: function() {
                         error(' ');
                    }
                });
	};
        
         function saved(){
              new PNotify({
                        title:'Saved',
                        text: '',
                        type: 'success'
                    });
          }
          
          function error(text){
                new PNotify({
                        title:'Error.',
			text: text,
			type: 'error'
                    });
          }

	/*
	Nestable 1
	*/
	$('#nestable-menu').nestable({
		group: 1
	}).on('change', updateOutput);


}).apply(this, [ jQuery ]);