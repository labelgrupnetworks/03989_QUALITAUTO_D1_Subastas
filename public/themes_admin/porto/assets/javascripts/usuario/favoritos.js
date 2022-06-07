/* Añadida funcionalidad en el DOM
$(document).ready(function(){
        $(".JSauction").click(function(){
            
            idul = "#JSul" +  $(this).data("auction");
            console.log(idul);
            if($(idul).is(":visible")){
                $(idul).hide();
            }else{
                 $(idul).show();
            }
        });
    });
*/