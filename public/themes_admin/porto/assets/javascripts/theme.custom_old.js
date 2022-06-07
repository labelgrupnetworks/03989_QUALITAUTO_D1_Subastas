$(function() {
	var action_tab = {};

	//Dropzone subida de imágenes para el slider
	Dropzone.options.sliderupload = {
		autoProcessQueue: true,
		maxFiles: 1,
		thumbnailWidth: null,
		thumbnailHeight: 312,
		previewsContainer: '#thumbnails',
		maxFilesize: 64,
		
		init: function() {
			var hide_overlay = true;

	      	this.on("maxfilesexceeded", function(file) {
	        	this.removeAllFiles();
	        	this.addFile(file);
	      	});

	      	this.on("sending", function() { 
	      		action_tab = $('.tab-pane.active');
	      		$('#data-loader').trigger('loading-overlay:show');
	      	});

	      	this.on("success", function(file, response) {
	      		response = $.parseJSON(response);
	      		
	      		if (response.status == "success"){
	      			$("input[name='file_url']").val(response.file);

	      			var $img = $(file.previewElement).find("img");
		      		if ($img.length > 0){
		      			$('.mb-md .img_place', action_tab).html($img.addClass('img-responsive'));
	      			}

	      		}else{
	      			
	      			if(response.redirect != undefined){
	      				hide_overlay = false;
	      				window.location.href = response.redirect;
	      			}

	      		}

	      		if (hide_overlay){
	      			$('#data-loader').trigger('loading-overlay:hide');
	      		}
	      	});

		}
	};

	//Slider submit
	var $sliders = $('#sliders');
	if ($sliders.length){
		var $form = $('#main_slider_form', $sliders);
		$form.on('click', '.submit', function(e){
			var text_value = $(".summernote").code();
			$form.append('<textarea name="description" style="display: none;">'+text_value+'</textarea>');
			$form.submit();
		});

		var $settings = $('#settings', $sliders);
		$settings.on('click', '.submit', function(){
			var a_order = store.getAll(),
			order = a_order.__portletOrder.order,
			$form = $settings.find('form');

			$.each(order, function( index, value ) {
	  			$form.append('<input type="hidden" name="order['+value+']" value="'+index+'" />');
			});

			$settings.find('form').submit();

		});

	}
        
       $(".save_config").click(function() {
           
            var str = $( "#form_config" ).serializeArray();
           $.ajax ({
                url: "config/save",
                type: "post",
                data: str,
                success: function() {
                    saved();
                },
                error: function() {
                     error();
                }
            });
        });
        
        $( ".msg_box" ).click(function() {
            new PNotify({
			title: $(this).attr("title"),
			text: $(this).attr("msg"),
			type: $(this).attr("type"),
		});
          });
          
          $(".save_bloque").click(function() {
            var str = $( "#edit_bloque" ).serializeArray();
            $.ajax ({
                url: "/admin/bloque/edit",
                type: "post",
                data: str,
                success: function(e) {
                    if(e == 'claves'){
                        claves();
                    }else if(e == 'injection'){
                        sqlinjection();
                    }else{
                        $( ".id_input" ).val(e[1]);
                        guardado(e[0]);
                        saved();
                    }
                },
                error: function() {
                     error;
                }
            });
          });
          
          function saved(){
              new PNotify({
                        title:'Saved',
                        text: '',
                        type: 'success'
                    });
          }
          
          function guardado(e){
              var text = "Se han encontrado " +e+ " resultados.";
              new PNotify({
                title: text,
                text: '',
                type: 'success'
            });
          }
          
          function claves(){
              new PNotify({
                title:'Hay caracteres que no pueden encontrar resultados',
                text: '',
                type: 'success'
            });
          }
          
          function error(){
                new PNotify({
                        title:'Error.',
			text: '',
			type: 'error'
                    });
          }
          
          function sqlinjection(){
              new PNotify({
                            title:'Este tipo de sentencia no se puede guardar',
                            text: '',
                            type: 'error'
                         });
          }
          
          $(".save_resources").click(function() {
            var html = ($(".note-editable").html()) ;
            $( "#html" ).val(html);
            var str = $( "#edit_resources" ).serializeArray();
            
            $.ajax ({
                url: "/admin/resources/edit",
                type: "post",
                data: str,
                success: function(e) {
                    $( ".id_input" ).val(e);
                    saved();
                },
            });
          });
          
          $(".save_banner").click(function() {
            var str = $( "#new_banner" ).serializeArray();
           $.ajax ({
                url: "/admin/banner/edit",
                type: "post",
                data: str,
                success: function(e) {
                    $( ".id_input" ).val(e);
                    saved();
                },
            });
          });

	//Recursos imagen o HTML

        $( ".target" ).change(function() {
            var value = $( this ).val();
            change_resources(value);
         });
         
         function change_resources(value){
             if(value=='I'){
                $( ".imagen" ).show();
                $( ".html" ).hide();
            }else{                 	
                $( ".html" ).show();
                 $( ".imagen" ).hide();
            }
         }
        
        $(".save_auc_index").click(function() {
            var str = $( "#auc_index" ).serializeArray();
            $(".save_auc_index").prop('disabled',true);
            $.ajax ({
                url: "/admin/auc-index/edit",
                type: "post",
                data: str,
                success: function(e) {
                $( ".id_input" ).val(e);
                   saved();
                    $(".save_auc_index").prop('disabled',false);
                },
                error: function() {
                     error();
                      $(".save_auc_index").prop('disabled',true);
                }
            });
          });
          
          $( ".auc-section" ).change(function() {
            var value = $( this ).val();
            change_auc_index(value);
         });
         
         function change_auc_index(value){
            if(value=='F'){
                $( ".familia" ).show();
                $( ".sessions" ).hide();
                $( ".padre" ).hide();
                $( ".familia_sessions" ).show();
            }else if(value=='S'){
                 $( ".familia" ).hide();
                 $( ".padre" ).hide();
                 $( ".sessions" ).show();
                 $( ".familia_sessions" ).show();
            }else{                 	
                 $( ".familia" ).hide();
                 $( ".sessions" ).hide();
                 $( ".padre" ).show();
                 $( ".familia_sessions" ).hide();
            }
         }
         
         $(".save_auc_lots_index").click(function() {
            var str = $( "#auc_index_lots" ).serializeArray();
            $.ajax ({
                url: "/admin/auc-index-lots/edit",
                type: "post",
                data: str,
                success: function(e) {
                  $( ".id_input" ).val(e);
                   saved();
                },
                error: function() {
                     error();
                }
            });
          });
          
          $(".new_session_auc_index").click(function() {
              var value =  parseInt($(".num_session").val());
                  value =  value + 1;
              
              $(".num_session").val(value);
              
              var id_auc_session = 'id_auc_session_' + value;
              $( "#id_auc_session" ).attr( "name", id_auc_session );
              
              var lots = 'lots_'+value;
              $( "#block-sesion #lots" ).attr( "name", lots );
              
              var data_delete = '.session_'+value;
              $( "#block-sesion #delete" ).attr( "data_delete", data_delete );
              
              var remove = value -1;
              var remove_class = 'session_'+remove;
              $( "#block-sesion" ).children().removeClass( remove_class );
              
              var row_delete = 'session_'+value;
             $("#block-sesion").children().addClass(row_delete);
              
             $("#container-section").append($("#block-sesion").html());
             
             $(".delete_session_auc_index").bind("click",function(){
                 var value =  parseInt($(".num_session").val());
              
                if(value != 0){
                    value =  value - 1;
                }
              
               $(".num_session").val(value);
               
               var value_delete = $(this).attr("data_delete");
              $( value_delete ).remove();
            });

          });
          
          $(".delete_session_auc_index").click(function() {
              var value =  parseInt($(".num_session").val());
              
              if(value != 0){
                  value =  value - 1;
              }
              
               $(".num_session").val(value);
               
               var value_delete = $(this).attr("data_delete");
              $( value_delete ).remove();
          });
          
          //Guardar Seo Categories
          $(".save_seo").click(function() {
               $.each(idiomes, function(key,val) {             
                var html = ($("#content-summernote_"+key.toUpperCase()+" .note-editable").html());
                $( "#webcont_"+key.toUpperCase()+"").val(html);
            }); 
              var str = $( "#seo-categ" ).serializeArray();
            $.ajax ({
                url: "/admin/seo-categories/edit",
                type: "post",
                data: str,
                success: function(e) {
                    $.each(e, function (index, value) {
                        $( "#id_"+index ).val(value);
                      })
                   saved();
                },
                error: function() {
                   error();
                }
            });
          });
          
          //Guardar Seo Familias y sessiones
          $(".save_seo_family_session").click(function() {
               $.each(idiomes, function(key,val) {             
                var html = ($("#content-summernote_"+key.toUpperCase()+" .note-editable").html());
                $( "#webcont_"+key.toUpperCase()+"").val(html);
            }); 
              var str = $( "#seo-categ" ).serializeArray();
            $.ajax ({
                url: "/admin/seo-familias-sessiones/edit",
                type: "post",
                data: str,
                success: function(e) {
                    $.each(e, function (index, value) {
                        $( "#id_"+index ).val(value);
                      })
                   saved();
                },
                error: function() {
                   error();
                }
            });
          });
          
          $(".save_traducciones").click(function() {
           
            var str = $( "#traducciones" ).serializeArray();
           $.ajax ({
                url: "/admin/traducciones/save",
                type: "post",
                data: str,
                success: function() {
                    saved();
                   // location.reload();
                },
                error: function() {
                     error();
                }
            });
        });
        
         $(".new_traducciones").click(function() {
           
            var str = $( "#new_traduction" ).serializeArray();
           $.ajax ({
                url: "/admin/traducciones/new",
                type: "post",
                data: str,
                success: function() {
                    saved();
                },
                error: function() {
                     error();
                }
            });
        });
        
        $(".save_page").click(function() {
            var html = ($(".note-editable").html()) ;
            $( "#html" ).val(html);
            var str = $( "#edit_page_content" ).serializeArray();
           $.ajax ({
                url: "/admin/content/save",
                type: "post",
                data: str,
                success: function() {
                    saved();
                },
                error: function() {
                     error();
                }
            });
        });
        
         $(".delete_resource").click(function() {
             $("#delete_resource").attr("value",$(this).attr( "data-id" ))
         });
          $("#delete_resource").click(function() {
              $.ajax ({
                url: "/admin/resources/delete",
                type: "post",
                data: { id_resource: $(this).attr( "value" )},
                success: function() {
                    location.reload();
                }
            });
          });
          
          $(".save_category_blog").click(function() {
              var descriptions = '';
            $('.summernote_descrip').each(function () {
                var code = $('#' + this.id).code();
                 descriptions += '&' + this.id + '=' +  $.trim(code);
            });
            var str = $("#save_category_blog").serialize() + descriptions;
            
            $.ajax ({
                url: "/admin/category-blog/edit",
                type: "post",
                data: str,
                success: function(e) {
                  $( ".id_input" ).val(e);
                   saved();
                },
                error: function() {
                     error();
                }
            });
          });

          
           $("#save_blog").submit(function( event ) {
            event.preventDefault();            
           
            $('.summernote_descrip').each(function () {               
                $( "#cont_"+ $(this).attr('lang') ).val( $('#summernote_' + $(this).attr('lang')).code()); 
                
            });
            
           
           
            var str = $( "#save_blog" ).serializeArray();
           
           $.ajax ({
                url: "/admin/blog/edit",
                type: "post",
                data: str,
                success: function(e) {
                   $( ".id_input" ).val(e);
                   saved();
                },
                error: function() {
                     error();
                }
            });
          });
        
          


          
         
});

function delete_resource(id_resource ){
    $.ajax ({
        url: "/admin/resources/delete",
        type: "post",
        data: { id_resource: id_resource},
        success: function() {
            location.reload();
        }
    });
}