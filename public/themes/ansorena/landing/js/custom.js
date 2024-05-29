/*AFFIX*/


$(document).ready(function() {
  $(window).scroll(function() {
    if ($(document).scrollTop() > 150) {
      $("header").addClass("scroll");
    } else {
      $("header").removeClass("scroll");
    }
  });
});


$('.co-menu-estaciones a').on('click', function(e) {
e.preventDefault();
$(this).tab('show');
var theThis = $(this);
$('.co-menu-estaciones a').removeClass('active');
theThis.addClass('active');
});


/*SUB TABS*/

$(function () {

  var activeIndex = $('.active-tab').index(),
      $contentlis = $('.tabs-content li'),
      $contentdivs = $('.tab-images > div'),

      $tabslis = $('.tabs li');
  
  // Show content of active tab on loads
  $contentlis.eq(activeIndex).show();

  $('.tabs').on('click', 'li', function (e) {
    var $current = $(e.currentTarget),
        index = $current.index();
    
    $tabslis.removeClass('active-tab');
    $current.addClass('active-tab');
    $contentlis.hide().eq(index).show();
    $contentdivs.hide().eq(index).show();
     });
});

$(function () {

  var activeIndex = $('.active-tab2').index(),
      $contentlis2 = $('.tabs-content2 li'),
      $contentdivs2 = $('.tab-images2 div'),
      $tabslis2 = $('.tabs2 li');
  
  // Show content of active tab on loads
  $contentlis2.eq(activeIndex).show();

  $('.tabs2').on('click', 'li', function (e) {
    var $current2 = $(e.currentTarget),
        index = $current2.index();
    
    $tabslis2.removeClass('active-tab2');
    $current2.addClass('active-tab2');
    $contentlis2.hide().eq(index).show();
    $contentdivs2.hide().eq(index).show();
     });
});

$(function () {

  var activeIndex = $('.active-tab3').index(),
      $contentlis3 = $('.tabs-content3 li'),
      $contentdivs3 = $('.tab-images3 div'),
      $tabslis3 = $('.tabs3 li');
  
  // Show content of active tab on loads
  $contentlis3.eq(activeIndex).show();

  $('.tabs3').on('click', 'li', function (e) {
    var $current3 = $(e.currentTarget),
        index = $current3.index();
    
    $tabslis3.removeClass('active-tab3');
    $current3.addClass('active-tab3');
    $contentlis3.hide().eq(index).show();
    $contentdivs3.hide().eq(index).show();
     });
});

$(function () {

  var activeIndex = $('.active-tab4').index(),
      $contentlis4 = $('.tabs-content4 li'),
      $contentdivs4 = $('.tab-images4 div'),
      $tabslis4 = $('.tabs4 li');
  
  // Show content of active tab on loads
  $contentlis4.eq(activeIndex).show();

  $('.tabs4').on('click', 'li', function (e) {
    var $current4 = $(e.currentTarget),
        index = $current4.index();
    
    $tabslis4.removeClass('active-tab4');
    $current4.addClass('active-tab4 ');
    $contentlis4.hide().eq(index).show();
    $contentdivs4.hide().eq(index).show();
     });
});

