var Arya = {
    currentPage : 1,
    pageSize : 4,
    totalPages : 100,
    init : function() {
        jQuery('.open-side-menu').click(this.openSideMenu);
        jQuery('body').click(this.toggleSideMenu);
        jQuery('.st-menu').find('>ul').find('>li').find('>a').click(this.sideMenuInteraction);
        jQuery('.close-side-menu').click(this.closeSideMenu);
        setTimeout(function() {
            Arya.initWidgetsHeight();
        }, 100);
        this.resizeSlider();
        this.initSlider();
        this.initNormalSlider();
        this.initStickyPostHeader();
        this.likesFunctionality();
        this.postShare();
        //jQuery('.st-menu').outerHeight(jQuery(window).height());
        if(jQuery('body').hasClass('has-infinite-scroll')) {
            jQuery(window).scroll(Arya.initInfiniteScroll);
        }
        jQuery('.sidebar-slider').bind('mousewheel', function(e) {
            var body = jQuery('body');
            if(!body.hasClass('scrolling')) {
                Arya.initMouseMove(e.originalEvent.wheelDelta, true);
                body.addClass('scrolling');
            }
        });
        jQuery('.sidebar-slider').bind('DOMMouseScroll', function(e) {
            var body = jQuery('body');
            if(!body.hasClass('scrolling')) {
                Arya.initMouseMove(e.originalEvent.detail, false);
                body.addClass('scrolling');
            }
        });
        jQuery('#contact_submit').addClass('btn btn-default');
    },
    postShare : function() {
        jQuery('.post-share li a, .sticky-share li a').not('.dohref').on('click', function(e) {
            e.preventDefault();
            var href = jQuery(this).data('href');
            openWindow(href);
        });

        function openWindow(url){
            var width=640;
            var height=460;
            var topPos=window.screen.height/2-(height/2);
            var leftPos=window.screen.width/2-(width/2);
            window.open(url,"Share", "status=1,height=" + height + ",width=" + width + ",top=" + topPos + ",left=" + leftPos + ",resizable=0");
        }
    },
    likesFunctionality : function() {
        jQuery('.icn-like').on('click', function(e) {
            e.preventDefault();
            var $this = jQuery(this),
                id = $this.data("id");
            jQuery.ajax({
                url: MyAjax.ajaxurl,
                type: "POST",
                data: {
                    'action' : 'like-submit',
                    'post_id' : id
                }
            }).done(function() {
                if($this.hasClass('liked') ) {
                    $this.removeClass('liked');
                    var likes = parseInt($this.find('span').html() ) - 1;
                    $this.find('span').text(likes);
                }
                else {
                    $this.addClass('liked');
                    var likes = parseInt($this.find('span').html() ) + 1;
                    $this.find('span').text(likes);
                }
            });
        });
    },
    initMouseMove : function(index, inverse) {
          if(index > 0) {
              if(inverse) {
                  Arya.sidebarSlider.goToPrevSlide();
              } else {
                  Arya.sidebarSlider.goToNextSlide();
              }
          } else {
              if(inverse) {
                  Arya.sidebarSlider.goToNextSlide();
              } else {
                  Arya.sidebarSlider.goToPrevSlide();
              }
          }
        setTimeout(function() {
            jQuery('body').removeClass('scrolling');
        }, 1500);
    },
    initInfiniteScroll : function() {
        var windowH = jQuery(window).height();
        var scrollTop = jQuery(window).scrollTop();
        var watchContainer = jQuery('main').find('.left-container');
        if(jQuery('body').hasClass('homepage3')) {
            watchContainer = jQuery('main').find('.homepage-articles');
        }
        var treshold = watchContainer.outerHeight() + jQuery('header').outerHeight() + jQuery('.normal-slider-wrap').outerHeight();
        if(treshold * 0.95 < (scrollTop + windowH) ) {
            Arya.startInfiniteScroll();
            jQuery('body').addClass('loading');
        }
    },
    startInfiniteScroll : function() {
        var body = jQuery('body');
        if(body.hasClass('loading')) return false;
        if(Arya.currentPage >= Arya.totalPages) return false;
        if(body.hasClass('homepage3')) {
            Arya.infiniteScrollHomepage3();
        }
        if(body.hasClass('homepage2')) {
            Arya.infiniteScrollHomepage2();
        }
        return true;
    },
    infiniteScrollHomepage3 : function() {
        var appendContainer = jQuery('.homepage-articles').find('.col-sm-12');
        var id='';
        if(jQuery('.post_id').length) {
            id = jQuery('.post_id').data('id');
        }
        jQuery.ajax({
            method: "GET",
            url : MyAjax.ajaxurl,
            dataType : 'json',
            data : {
                action          : 'teo-infinite',
                page            : Arya.currentPage,
                pageSize        : Arya.pageSize,
                template        : 'home3',
                post_id         : id
            },
            success : function(rsp) {
                Arya.appendContent(rsp, appendContainer);
            }
        });
    },
    infiniteScrollHomepage2 : function() {
        var appendContainer = jQuery('#post-container').find('.left-container');
        var extra = '';
        var id = '';
        if(jQuery('.query_parameters').length) {
            extra = jQuery('.query_parameters').data('parameters');
        }
        if(jQuery('.post_id').length) {
            id = jQuery('.post_id').data('id');
        }
        jQuery.ajax({
            method: "GET",
            url : MyAjax.ajaxurl,
            dataType : 'json',
            data : {
                action          : 'teo-infinite',
                page            : Arya.currentPage,
                pageSize        : Arya.pageSize,
                template        : 'home2',
                query_string    : extra,
                post_id         : id
            },
            success : function(rsp) {
                Arya.appendContent(rsp, appendContainer);
                Arya.postShare();
            },
            error: function (request, status, error) {
                console.log(request + " --- " + status + " --- " + error);
            }
        });
    },
    initWidgetsHeight : function() {
        var container = jQuery('#post-container').find('.left-container').first();
        var widgets = jQuery('.widgets');
        container.removeAttr('style');
        widgets.removeAttr('style');
        var minHeight = container.height() > widgets.height() ? container.height() : widgets.height();
        if(jQuery(window).width() >= 768) {
            container.css('min-height', minHeight+'px');
            //widgets.css('min-height', minHeight+'px');
        }
    },
    appendContent : function(rsp, appendContainer) {
        Arya.currentPage++;
        Arya.totalPages = rsp.totalPages;
        setTimeout(function() {
            jQuery('body').removeClass('loading');
        }, 500);
        jQuery.each(rsp.items, function(index, item) {
            var source = jQuery("#article-template").html();
            var template = Handlebars.compile(source);
            item.title = jQuery("<div/>").html(item.title).text();
            item.excerpt = jQuery("<div/>").html(item.excerpt).text();
            var html = template(item);
            html = jQuery(html).hide();
            appendContainer.append(html.fadeIn(300, function() {
                Arya.initWidgetsHeight();
            }));
        });
    },
    openSideMenu : function(e) {
        var body = jQuery('body');
        jQuery('#main-container').css('min-height', jQuery(window).height() + "px");
        //jQuery('.st-menu').outerHeight(jQuery(window).height());
        if(body.hasClass('st-menu-open')) {
            body.removeClass('st-menu-open');
            setTimeout(function() {
                body.removeClass('body-overflow');
            }, 300);
        } else {
            setTimeout(function() {
                body.addClass('st-menu-open');
                setTimeout(function() {
                    body.addClass('body-overflow');
                }, 300);
            }, 50);
        }
        e.preventDefault();
    },
    closeSideMenu : function() {
        var body = jQuery('body');
        body.removeClass('st-menu-open');
        setTimeout(function() {
            body.removeClass('body-overflow');
        }, 300);
    },
    toggleSideMenu : function() {
        var body = jQuery('body');
        if(body.hasClass('st-menu-open') && !jQuery('.st-menu').is(':hover')) {
            body.removeClass('st-menu-open');
            setTimeout(function() {
                body.removeClass('body-overflow');
            }, 300);
        }
    },
    sideMenuInteraction : function(e) {
        var el = jQuery(this);
        var next = jQuery(this).next();
        if(next.is('ul')) {
            if(next.children('li').is(':visible')) {
                next.children('li').slideUp(300);
                el.parent().removeClass('selected');
            } else {
                next.children('li').slideDown(300);
                el.parent().addClass('selected');
            }
            e.preventDefault();
        }
    },
    resizeSlider : function() {
        var mainSlider = jQuery('.main-slider');
        var sidebarSlider = jQuery('.sidebar-slider');
        var windowH = jQuery(window).height();
        jQuery('.full-homepage').height(windowH);
        mainSlider.height(windowH);
        sidebarSlider.height(windowH);
        mainSlider.find('article').outerHeight(windowH);
        sidebarSlider.find('article').outerHeight(windowH/3);
        var slideH = windowH/3;
        jQuery('.right-section').find('.bx-viewport').css({
            'padding-top' : slideH + "px"
        });
    },
    initNormalSlider : function() {
        var normalSliderWrap = jQuery('.normal-slider-wrap');
        var slider = jQuery('.normal-slider').bxSlider({
            mode: 'horizontal',
            slideSelector : '.slide',
            controls : false,
            pager : false,
            onSlideBefore : function() {
                normalSliderWrap.find('.slider-controls').find('.current-slide').text(slider.getCurrentSlide()+1);
            }
        });
        if(normalSliderWrap.length) {
            normalSliderWrap.find('.slider-controls').find('.current-slide').text(slider.getCurrentSlide()+1);
            normalSliderWrap.find('.slider-controls').find('.count-slides').text(slider.getSlideCount());
            normalSliderWrap.find('.slider-controls').find('.prev').on('click', function() {
                slider.goToPrevSlide();
            });
            normalSliderWrap.find('.slider-controls').find('.next').on('click', function() {
                slider.goToNextSlide();
            });
        }
    },
    initSlider : function() {
        var sidebarSliderContainer = jQuery('.sidebar-slider');
        var rightSection = jQuery('.right-section');
        var mainSlider = jQuery('.main-slider');
        Arya.sidebarSlider = sidebarSliderContainer.bxSlider({
            mode: 'vertical',
            minSlides: 3,
            moveSlides : 1,
            controls : false,
            pager : false,
            onSliderLoad : function() {
                var windowH = jQuery(window).height();
                var slideH = windowH/3;
                jQuery('.right-section').find('.bx-viewport').css({
                    'padding-top' : slideH + "px"
                });
            },
            onSlideBefore : function($slideElement) {
                jQuery('html, body').animate({
                    scrollTop: 0
                }, 500);
                sidebarSliderContainer.find('.active').removeClass('active');
                $slideElement.addClass('active');
                rightSection.find('.slider-controls').find('.current-slide').text(Arya.sidebarSlider.getCurrentSlide()+1);
            },
            onSlideNext : function() {
                var visibleItem = mainSlider.find('.visible');
                var next = visibleItem.next();
                if(!visibleItem.next().is('article')) {
                    next = mainSlider.find('article').first();
                }
                visibleItem.addClass('firstAnim').removeClass('bigIndex');
                next.addClass('bigIndex');
                setTimeout(function() {
                    next.addClass('secondAnim').addClass('visible');
                }, 400);
                setTimeout(function() {
                    visibleItem.removeClass('visible').removeClass('firstAnim');
                    next.removeClass('secondAnim');
                }, 800);
                setTimeout(function() {
                    next.removeClass('bigIndex');
                }, 1200);
            },
            onSlidePrev : function() {
                var visibleItem = mainSlider.find('.visible');
                var prev = visibleItem.prev();
                if(!visibleItem.prev().is('article')) {
                    prev = mainSlider.find('article').last();
                }
                visibleItem.addClass('firstAnim').removeClass('bigIndex');
                prev.addClass('bigIndex');
                setTimeout(function() {
                    prev.addClass('bigIndex').addClass('secondAnim').addClass('visible');
                }, 400);
                setTimeout(function() {
                    visibleItem.removeClass('visible').removeClass('firstAnim');
                    prev.removeClass('secondAnim');
                }, 800);
                setTimeout(function() {
                    prev.removeClass('bigIndex');
                }, 1200);

            }
        });
        sidebarSliderContainer.find('article').on('click', function() {
            var index = jQuery(this).data('slide-index');
            if(Arya.sidebarSlider.getCurrentSlide() != index) {
                var visibleItem = mainSlider.find('.visible');
                var currentSlide = mainSlider.find('article').eq(index);
                Arya.sidebarSlider.goToSlide(index);
                visibleItem.addClass('firstAnim').removeClass('bigIndex');
                currentSlide.addClass('bigIndex');
                setTimeout(function() {
                    currentSlide.addClass('secondAnim').addClass('visible');
                }, 400);
                setTimeout(function() {
                    visibleItem.removeClass('visible').removeClass('firstAnim');
                    currentSlide.removeClass('secondAnim');
                }, 800);
                setTimeout(function() {
                    currentSlide.removeClass('bigIndex');
                }, 1200);
            }
        });
        if(Arya.sidebarSlider.length) {
            rightSection.find('.slider-controls').find('.current-slide').text(Arya.sidebarSlider.getCurrentSlide()+1);
            rightSection.find('.slider-controls').find('.count-slides').text(Arya.sidebarSlider.getSlideCount());
            rightSection.find('.slider-controls').find('.prev').on('click', function() {
                Arya.sidebarSlider.goToPrevSlide();
            });
            rightSection.find('.slider-controls').find('.next').on('click', function() {
                Arya.sidebarSlider.goToNextSlide();
            });
        }
    },
    initStickyPostHeader : function() {
        $postHeader = jQuery('.post-header');
        $headerHeight = jQuery('header').outerHeight();
        $containerWIdth = jQuery('.left-container').outerWidth();
        if (jQuery(window).scrollTop() <= $headerHeight) {
            $postHeader.removeClass('sticky');
        } else {
            $postHeader.addClass('sticky');
            jQuery('.sticky').css('width', $containerWIdth + 'px');
        }
    }
};
jQuery(document).ready(function() {
   Arya.init();
});
jQuery(window).resize(function() {
    Arya.initWidgetsHeight();    
    Arya.resizeSlider();
    Arya.initStickyPostHeader();
});
jQuery(window).scroll(function(){
    Arya.initStickyPostHeader();
});