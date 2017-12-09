(function ($) {
  vcv.on('ready', function () {
    let sliders = $('.vce-simple-image-slider-list');
    if (sliders.length) {
      sliders.each(function () {
        let slider = $(this);
        let dots = slider.parent().find('.vce-simple-image-slider-dots');
        let prevArrow = slider.find('.vce-simple-image-slider-prev-arrow') || '';
        let nextArrow = slider.find('.vce-simple-image-slider-next-arrow') || '';
        let settings = {
          autoplay: slider[0].dataset.slickAutoplay === 'on',
          autoplaySpeed: slider[0].dataset.slickAutoplayDelay,
          fade: slider[0].dataset.slickEffect === 'fade',
          arrows: slider[0].dataset.slickArrows === 'on',
          prevArrow: prevArrow,
          nextArrow: nextArrow,
          appendDots: dots,
          dots: slider[0].dataset.slickDots === 'on',
          initialSlide: 0,
          respondTo: 'slider',
          swipe: slider[0].dataset.slickDisableSwipe !== 'on',
          swipeToSlide: slider[0].dataset.slickDisableSwipe !== 'on',
          touchMove: slider[0].dataset.slickDisableSwipe !== 'on'
        };
        if (slider.hasClass('slick-initialized')) {
          slider.vcSlick('unslick');
        }
        slider.vcSlick(settings);
      });
    }
  })
})(window.jQuery)