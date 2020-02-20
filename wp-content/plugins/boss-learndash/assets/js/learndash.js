(function($) {

    /* Courses */
     var equalHeights = function(options) {
        var maxHeight = 0,
            $this = $('.course-flexible-area'),
            equalHeightsFn = function() {
                var height = $(this).innerHeight();

                if ( height > maxHeight ) { maxHeight = height; }
            };
        options = options || {};

        $this.each(equalHeightsFn);

        return $this.css('height', maxHeight);
    };

    // get viewport size
    function viewport() {
        var e = window, a = 'inner';
        if (!('innerWidth' in window )) {
            a = 'client';
            e = document.documentElement || document.body;
        }
        return { width : e[ a+'Width' ] , height : e[ a+'Height' ] };
    }

    // Append "See more" button inside each box in course grid/list
    $.fn.generateMoreLink = function(options) {
        var opts = $.extend( {}, $.fn.generateMoreLink.defaults, options );

        return this.each(function() {
            var
                elm = $(this),
                content = elm.html();

            if(content.length > opts.showChar) {

                var c = content.substr(0, opts.showChar);
                var h = content.substr(opts.showChar, content.length - opts.showChar);
                var html = c + '<span class="moreellipses">' + opts.ellipsesText+ '&nbsp;</span><span class="morecontent"><span>' + h +
                    '</span>&nbsp;&nbsp;<a href="" class="morelink">' + opts.moreText + '</a></span>';
                elm.html(html);
            }

        });
    };

    //  defaults – added as a property on our function.
    $.fn.generateMoreLink.defaults = {
        showChar: bossLdVars.showchar,
        ellipsesText: "...",
        moreText: bossLdVars.seemore,
        lessText: bossLdVars.seeless
    };

    $('.course-inner p.entry-content').generateMoreLink();

    /**
     * -------------------------------------------------------------------------------------
     * Javascript-Equal-Height-Responsive-Rows
     * https://github.com/Sam152/Javascript-Equal-Height-Responsive-Rows
     * -------------------------------------------------------------------------------------
     */

    /**
     * Set all elements within the collection to have the same height.
     */
    $.fn.equalHeight = function() {
        var heights = [];
        $.each(this, function(i, element) {
            var $element = $(element);
            var elementHeight;
            // Should we include the elements padding in it's height?
            var includePadding = ($element.css('box-sizing') === 'border-box') || ($element.css('-moz-box-sizing') === 'border-box');
            if (includePadding) {
                elementHeight = $element.innerHeight();
            } else {
                elementHeight = $element.height();
            }
            heights.push(elementHeight);
        });
        this.css('height', Math.max.apply(window, heights) + 15 + 'px');
        return this;
    };

    /**
     * Create a grid of equal height elements.
     */
    $.fn.equalHeightGrid = function(columns) {
        var $tiles = this.filter(':visible');
        $tiles.css('height', 'auto');
        for (var i = 0; i < $tiles.length; i++) {
            if (i % columns === 0) {
                var row = $($tiles[i]);
                for (var n = 1; n < columns; n++) {
                    row = row.add($tiles[i + n]);
                }
                row.equalHeight();
            }
        }
        return this;
    };

    /**
     * Detect how many columns there are in a given layout.
     */
    $.fn.detectGridColumns = function() {
        var offset = 0,
            cols = 0,
            $tiles = this.filter(':visible');
        $tiles.each(function(i, elem) {
            var elemOffset = $(elem).offset().top;
            if (offset === 0 || elemOffset === offset) {
                cols++;
                offset = elemOffset;
            } else {
                return false;
            }
        });
        return cols;
    };

    /**
     * Ensure equal heights now, on ready, load and resize.
     */
    var grids_event_uid = 0;
    $.fn.responsiveEqualHeightGrid = function() {
        var _this = this;
        var event_namespace = '.grids_' + grids_event_uid;
        _this.data('grids-event-namespace', event_namespace);
        function syncHeights() {
            var cols = _this.detectGridColumns();
            _this.equalHeightGrid(cols);
        }
        $(window).bind('resize' + event_namespace + ' load' + event_namespace, syncHeights);
        syncHeights();
        grids_event_uid++;
        return this;
    };

    /**
     * Unbind created events for a set of elements.
     */
    $.fn.responsiveEqualHeightGridDestroy = function() {
        var _this = this;
        _this.css('height', 'auto');
        $(window).unbind(_this.data('grids-event-namespace'));
        return this;
    };

    function equalProjects() {

        if ($('.course-inner section.entry .caption').length) {
            var column_width = $('.course.type-sfwd-courses').width() / $('.course.type-sfwd-courses').parent().width() * 100;

            if ( column_width <= 50 && column_width > 33 ) {
                $('.course-inner section.entry .caption').equalHeightGrid(2);
            } else if ( column_width <= 33 && column_width > 25 ) {
                $('.course-inner section.entry .caption').equalHeightGrid(3);
            } else if ( column_width <= 25 && column_width > 20 ) {
                $('.course-inner section.entry .caption').equalHeightGrid(4);
            } else if ( column_width <= 20 && column_width > 0 ) {
                $('.course-inner section.entry .caption').equalHeightGrid(5);
            }
        }
    }

    $(document).ready(function(){

        //    imagesLoaded( '.course-flexible-area', function( instance ) {
        equalProjects();
        //    });

        /* throttle */
        $(window).resize(function(){
            clearTimeout($.data(this, 'resizeTimer'));
            $.data(this, 'resizeTimer', setTimeout(function() {
                equalProjects();
            }, 1000));
        });

        $('#left-menu-toggle').click(function(){
            setTimeout(function() {
                equalProjects();
            }, 550);
        });

        $(window).trigger('resize');


        var video_frame = $("#course-video").find('iframe'),
        video_src = video_frame.attr('src');

        $('#show-video').click(function(e){
            e.preventDefault();
            $('.course-header').fadeOut(200,
            function(){
                $("#course-video").fadeIn(200);
            });
            $(this).addClass('hide');
        });

        $('#hide-video').click(function(e){
            e.preventDefault();
            $('#course-video').fadeOut(200,
            function(){
                video_frame.attr('src','');
                $(".course-header").fadeIn(200, function() {
                    video_frame.attr('src',video_src);
                });
            });
            $('#show-video').removeClass('hide');
        });

        //Ajax for contact teacher widget
        $( '.boss-edu-send-message-widget' ).on( 'click', function ( e ) {

            e.preventDefault();

            $.post( ajaxurl, {
                    action: 'boss_edu_contact_teacher_ajax',
                    content: $('.boss-edu-teacher-message').val(),
                    sender_id: $('.boss-edu-msg-sender-id').val(),
                    reciever_id: $('.boss-edu-msg-receiver-id').val(),
                    course_id: $('.boss-edu-msg-course-id').val()
                },
                function(response) {

                    if ( response.length > 0 && response != 'Failed' ) {
	                    $('.boss-edu-teacher-message').val('');
                        $('.widget_course_teacher h3').append('<div class="learndash-message tick">' + bossLdVars.messagesent + '</div>');
                    }
                });


        } );

        /** Responsive Tables **/
        $('.learndash_profile_quiz_heading').each(function(){
            var $this = $(this),
                array = [],
                jj = 0;

            $this.find('div').each(function(){
                array.push($(this).text());
            });

            $this.nextAll().children().each(function(){
                $(this).attr( "data-heading", array[jj] );
                jj++;
                if( jj == 5 ) {
                    jj = 0;
                }
            });
        });

        // Unwrap div.row
        $single_box = $('.course.type-sfwd-courses,.course.sfwd-lessons');
        if ( 'undefined' !== typeof $single_box && $single_box.parent().is('div.row') ) {
            $single_box.unwrap('div.row');
        }
    });

    /* Course Progress */
//    $.fn.removeComplete = function(){
//        var text = $(this).text(),
//            lastIndex = text.lastIndexOf(" ");
//        $(this).text(text.substring(0, lastIndex));
//    }
//    $(document).ready(function(){
//        $('.course_progress_blue').each(function(){
//            var $this = $(this),
//                style = $this.attr('style');
//            $this.parents('.course_progress').next('.right').attr('style', style).removeComplete();
//        });
//    });
//
//
//    $('#learndash_profile').find('.expand_collapse').insertBefore('#course_list');

    /* Quiz */
    $('.wpProQuiz_questionInput[type=radio], .wpProQuiz_questionInput[type=checkbox]').each(function(){
        var $this = $(this);
        if($this.attr('checked') == true) {
            $this.parents('label').addClass('selected');
        } else {
            $this.parents('label').removeClass('selected');
        }
    });

    $('.wpProQuiz_questionInput[type=radio], .wpProQuiz_questionInput[type=checkbox]').each(function(){
	var $this = $( this );

        if( ! $this.hasClass('styled') ) {
            $this.addClass( 'styled' );
            if ( $this.next( "label" ).length === 0 && $this.next( "strong" ).length === 0 ) {
                $this.after( '<strong></strong>' );
            }
        }
    } );

    $('.wpProQuiz_questionInput').change(function(){
        if($(this).attr('type') == 'radio') {
            $(this).parents('.wpProQuiz_questionList').find('.wpProQuiz_questionListItem').each(function(){
                $(this).find('label').removeClass('selected');
            });
            $(this).parent('label').addClass('selected');
        } else if($(this).attr('type') == 'checkbox') {
            $(this).parent('label').toggleClass('selected');
        }
    });

    $('.wpProQuiz_results').on('click','input[name="restartQuiz"]',function(){
	    $('.wpProQuiz_questionInput[type=radio], .wpProQuiz_questionInput[type=checkbox]').each(function(){
		    var $this = $(this);
		    $this.parents('label').removeClass('selected');
	    });
    });

    $('.drop-list').click(function(){
        var $parent = $(this).parents('.has-topics');
        $parent.find('.learndash_topic_dots').slideToggle();
        $parent.toggleClass('expanded');
    });

    /* Course Participants Widget View All */
    $('.learndash-see-more-participants a').click( function( event ) {

        event.preventDefault();

        var $list       = $('ul.learndash-course-participants-list'),
            $this       = $(this),
            course_id   = +$this.attr('data-course_id'),
            total       = +$this.attr('data-total'),
            paged       = +$this.attr('data-paged'),
            number      = +$this.attr('data-number');
            total_pg    = Math.ceil(total/number);

        $.post(
            ajaxurl,
            {
                action:'boss_edu_paged_course_participants',
                course_id: course_id,
                paged: paged,
                number: number
            },
            function(response) {
                $list.fadeOut( 100, function() {
                    $list.append(response);
                    $list.fadeIn(100);
                    $this.attr('data-paged', ++paged);
                    if (total_pg < paged ) {
                        $this.remove();
                    }
                });
            }
        );

    });

    /* Expand course description inside course box */
    $( '.ld-course-list-content' ).on( 'click', '.morelink', function(event) {
        event.preventDefault();

        var elm = $(this);

        if(elm.hasClass("less")) {
            elm.removeClass("less");
            elm.html(bossLdVars.seemore);
        } else {
            elm.addClass("less");
            elm.html(bossLdVars.seeless);
        }

        elm.parent().prev().toggle();
        elm.prev().toggle();

        equalProjects();

        return false;
    });

	// Ajax complete See more description button Fix
	$( document ).ajaxComplete( function ( event, request, settings ) {
		var action = bossLdgetQueryVariable(settings.data, 'action');

		if ( typeof action == 'undefined' || action != 'ld_course_list_shortcode_pager' )
			return;

		setTimeout( function () {
			$('.course-inner p.entry-content').generateMoreLink();
			equalProjects();
			$(window).trigger('resize');
		}, 500 );
	} );

	/* get querystring value */
	function bossLdgetQueryVariable( query, variable ) {
		if ( typeof query !== 'string' || query == '' || typeof variable == 'undefined' || variable == '' )
			return '';

		var vars = query.split( "&" );

		for ( var i = 0; i < vars.length; i ++ ) {
			var pair = vars[i].split( "=" );

			if ( pair[0] == variable ) {
				return pair[1];
			}
		}
		return( false );
	}

})(jQuery);