<script>
var cod_sub = '{{$data["cod_sub"]}}';
routing.node_url 	 = '{{ Config::get("app.node_url") }}';
routing.comprar		 = '{{ $data["node"]["comprar"] }}';
routing.ol		 = '{{ $data["node"]["ol"] }}';
</script>

<div class="container">
    @include('includes.subasta.layout_options')
</div>

<div class="container">
    <div class="row">
        <div class="col-xs-12 col-sm-4 col-md-3">
            @include('includes.subasta.filters')
        </div>

        <div class="col-xs-12 col-sm-8 col-md-9">

            <div class="list_lot">
				@include('includes.subasta.lots')
            </div>
        </div>
        <div class="col-xs-12 col-md-8 col-md-offset-3 col-xs-offset-0">
            <?php echo $data['subastas.paginator']; ?>
        </div>
    </div>
</div>

@if(!empty($data['seo']->meta_content) && $data['subastas.paginator']->currentPage == 1)
<div class="container category">
	<div class="row">
		<div class="col-lg-12">
                <?= $data['seo']->meta_content?>
                </div>
        </div>
</div>
@endif



{{--
	|------------------------------------------|
	|---------------Inicio de JS---------------|
	|------------------------------------------|
--}}

<script>

// Visor de imagen en grande en lot-list-large

$(function() {
  $(".thumbPop").thumbPopup({
    imgSmallFlag: "lote_medium",
    imgLargeFlag: "lote_large",
    cursorTopOffset: 0,
    cursorLeftOffset: 20

  });
});

(function($) {



  $.fn.thumbPopup = function(options) {

    //Combine the passed in options with the default settings
    settings = jQuery.extend({
      popupId: "thumbPopup",
      popupCSS: {
        'border': '1px solid #000000',
        'background': '#FFFFFF'
      },
      imgSmallFlag: "lote_medium",
      imgLargeFlag: "lote_large",
      cursorTopOffset: 15,
      cursorLeftOffset: 15,
      loadingHtml: "<span style='padding: 5px;'>Loading</span>"
    }, options);

    //Create our popup element
    popup =
      $("<div />")
    .css(settings.popupCSS)
    .attr("id", settings.popupId)
    .css("position", "absolute")
    .css('z-index', 99999999)
    .appendTo("body").hide();

    //Attach hover events that manage the popup
    $(this)
    .hover(setPopup)
    .mousemove(updatePopupPosition)
    .mouseout(hidePopup);

    function setPopup(event) {

      var fullImgURL = $(this).attr("src").replace(settings.imgSmallFlag, settings.imgLargeFlag);
      $(this).data("hovered", true);

      var style = "style";
      if ($(this).attr("src").indexOf("portada") > -1) {
        style = "styleX";
      }

      //Load full image in popup
      $("<img />")
      .attr(style, "height:450px;max-width:100%; padding: 5px;z-index:9999999;")
      .bind("load", {
        thumbImage: this
      }, function(event) {
        //Only display the larger image if the thumbnail is still being hovered
        if ($(event.data.thumbImage).data("hovered") == true) {
          $(popup).empty().append(this);
          updatePopupPosition(event, style);
          $(popup).show();
        }
        $(event.data.thumbImage).data("cached", true);
      })
      .attr("src", fullImgURL);

      //If no image has been loaded yet then place a loading message
      if ($(this).data("cached") != true) {
        $(popup).append($(settings.loadingHtml));
        $(popup).show();
      }

      updatePopupPosition(event);
    }

    function updatePopupPosition(event, style) {
      var windowSize = getWindowSize();
      var popupSize = getPopupSize(style);

      var rectificaY = 0;
      var rectificaX = 0;

      /*	if (windowSize.width + windowSize.scrollLeft < event.pageX + popupSize.width + settings.cursorLeftOffset){
				$(popup).css("left", event.pageX - popupSize.width - settings.cursorLeftOffset);
			} else {
				$(popup).css("left", event.pageX + settings.cursorLeftOffset);
			}
			if (windowSize.height + windowSize.scrollTop < event.pageY + popupSize.height + settings.cursorTopOffset){
				$(popup).css("top", event.pageY - popupSize.height - settings.cursorTopOffset);
			} else {
				$(popup).css("top", event.pageY + settings.cursorTopOffset);
			} */

      if (event.pageX + popupSize.width > screen.width) {
        rectificaX = -(popupSize.width + 40);
      }
      $(popup).css("left", event.pageX + settings.cursorLeftOffset + rectificaX);

      if (event.pageY + popupSize.height > windowSize.height + windowSize.scrollTop) {
        rectificaY = (windowSize.height + windowSize.scrollTop) - (event.pageY + popupSize.height + 10);
      }
      $(popup).css("top", event.pageY + settings.cursorTopOffset + rectificaY);

    }

    function hidePopup(event) {
      $(this).data("hovered", false);
      $(popup).empty().hide();
    }

    function getWindowSize() {
      return {
        scrollLeft: $(window).scrollLeft(),
        scrollTop: $(window).scrollTop(),
        width: $(window).width(),
        height: $(window).height()
      };
    }

    function getPopupSize(style) {
      if (style == "styleX") {
        return {
          width: $(popup).width(),
          height: $(popup).height()
        };
      }

      return {
        width: 450,
        height: 450
      };
    }

    //Return original selection for chaining
    return this;
  };
})(jQuery);


</script>
