<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-190765795-1"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}


  window.onload= function(){
   for(i=0; i<document.getElementsByTagName("img").length; i++){
     if(document.getElementsByTagName("img")[i].getAttribute("alt")=="" || document.getElementsByTagName("img")[i].getAttribute("alt")== null){
       var srcim= document.getElementsByTagName("img")[i].getAttribute("src");
	   if(srcim != null){
		srcim= srcim.split("/");
		srcim= srcim[srcim.length-1].replace(/-|.jpg/g, " ");
		document.getElementsByTagName("img")[i].setAttribute("alt", srcim);
	   }

     }
   }
}

  gtag('js', new Date());

  gtag('config', 'UA-190765795-1');

  gtag('config', 'G-G2F5CY1R1J');
</script>
