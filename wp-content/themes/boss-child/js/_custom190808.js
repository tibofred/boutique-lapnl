jQuery( document ).ready(function($) {



	if($('.no-lesson-msg').length) {
		var the_text = $('.no-lesson-msg').html().replace("No cours found.", "Aucun cours trouvé");
		$('.no-lesson-msg').html(the_text);
	}
	if($('#cours-personal-li a').length) {
		$('#cours-personal-li a').text('Mes formations');
	}

	if($('.learndash_profile_heading .title').length) {
		$('.learndash_profile_heading .title').html('Mes formations');
	}
	if($('.expand_collapse a:nth-child(1)').length) {
		$('.expand_collapse a:nth-child(1)').text('Développer tout');
	}
	if($('.expand_collapse a:nth-child(3)').length) {
		$('.expand_collapse a:nth-child(3)').text('Tout réduire');
	}
	if($('#item-body h3').length) {
		var the_text = $('#item-body h3').html().replace("Registered Cours", "Vous avez accès à :");
		$('#item-body h3').html(the_text);
	}
	if($('.prev-link').length) {
		var the_text = $('.prev-link').html().replace("Previous Leçon", "Chapitre précédent");
		the_text = the_text.replace("Previous Chapitre", "Chapitre précédent");
		the_text = the_text.replace("Leçon précédente", "Chapitre précédent");
		$('.prev-link').html(the_text);
	}
	if($('.next-link').length) {
		var the_text = $('.next-link').html().replace("Next Leçon", "Chapitre suivant");
		the_text = $('.next-link').html().replace("Next Chapitre", "Chapitre suivant");
		the_text = $('.next-link').html().replace("Leçon suivante", "Chapitre suivant");
		$('.next-link').html(the_text);
	}
	if($('.course-statistic').length) {
		var the_text = $('.course-statistic').html().replace("Cours", "Chapitres");
		$('.course-statistic').html(the_text);
	}
	if($('.course-completion-rate').length) {
		var the_text = $('.course-completion-rate').html().replace("out of", "sur");
		var the_text = the_text.replace("steps completed", "étapes complétées");
		$('.course-completion-rate').html(the_text);
	}
	if($('.bp-widget.base').length) {
		var the_text = $('.bp-widget.base').html().replace("Base", "");
		$('.bp-widget.base').html(the_text);
	}
	if($('#learndash_lessons').length) {
		var the_text = $('#learndash_lessons').html().replace("Cours", "Formation");
		$('#learndash_lessons').html(the_text);
	}
	if($('.field_gender legend').length) {
		var the_text = $('.field_gender legend').html().replace("Gender", "Sexe");
		$('.field_gender legend').html(the_text);
	}
	if($('.field_gender label').length) {
		var the_text = $('.field_gender label:nth-child(1)').html().replace(">Male", ">Masculin");
		$('.field_gender label:nth-child(1)').html(the_text);
		var the_text = $('.field_gender label:nth-child(2)').html().replace(">Female", ">Féminin");
		$('.field_gender label:nth-child(2)').html(the_text);
	}

	if($('.single-sfwd-lessons.postid-2155 header.entry-header span').length) {
		$('.single-sfwd-lessons.postid-2155 header.entry-header span').text('');
	}
	if($('header.entry-header span').length) {
		var the_text = $('header.entry-header span').html().replace("Chapitre", "");
		var the_text = $('header.entry-header span').html().replace("Leçon", "");
		$('header.entry-header span').html(the_text);
	}

	if($('.mepr-submit').length) {
		var the_text = $('.mepr-submit').val().replace("Sign Up", "Adhérer");
		$('.mepr-submit').val(the_text);
	}

	if($('.course-statistic').length) {
		var the_text = $('.course-statistic').html().replace("Leçon", "Chapitre");
		$('.course-statistic').html(the_text);
	}
	if($('#lesson_heading').length) {
		var the_text = $('#lesson_heading').html().replace("Leçon", "Chapitre");
		$('#lesson_heading').html(the_text);
	}

	/*if($('.expand_collapse').length) {
		$( '.expand_collapse a' ).each(function( index ) {
			var the_text = $(this).html().replace("Expand All", "Développer tout");
			var the_text = the_text.replace("Collapse All", "Tout réduire");
			$(this).html(the_text);	  
		});

	}*/


	if($('.post-12817').length) {
		$('.post-12817').after("<div class='title_module'>Module 1</div>");
	}	
	if($('.post-13026').length) {
		$('.post-13026').after("<div class='title_module'>Module 2</div>");
	}	
	if($('.post-13043').length) {
		$('.post-13043').after("<div class='title_module'>Module 3</div>");
	}	
	if($('.post-13070').length) {
		$('.post-13070').after("<div class='title_module'>Module 4</div>");
	}	

	if($('#lesson_list-12817').length) {
		$('#lesson_list-12817').after("<div class='title_module'>Module 1</div>");
	}	
	if($('#lesson_list-13026').length) {
		$('#lesson_list-13026').after("<div class='title_module'>Module 2</div>");
	}	
	if($('#lesson_list-13043').length) {
		$('#lesson_list-13043').after("<div class='title_module'>Module 3</div>");
	}	
	if($('#lesson_list-13070').length) {
		$('#lesson_list-13070').after("<div class='title_module'>Module 4</div>");
	}


	if($('.post-13267').length) {
		$('.post-13267').after("<div class='title_module'>Module 1</div>");
	}	
	if($('.post-16137').length) {
		$('.post-16137').after("<div class='title_module'>Module 2</div>");
	}

	if($('#lesson_list-13267').length) {
		$('#lesson_list-13267').after("<div class='title_module'>Module 1</div>");
	}	
	if($('#lesson_list-16137').length) {
		$('#lesson_list-16137').after("<div class='title_module'>Module 2</div>");
	}


	var labelText = $('label[for=affwp-payment-email]');
	if(labelText.length) {
		labelText.text(labelText.text().replace("Adresse de messagerie de paiement", "Inscrivez votre courriel (Payal) * Le courriel que vous utilisez lors de votre connexion à votre compte."));
	}	
	


	$( ".show_sub" ).click(function() {
		$(this).parent().find('.submenu').toggle( "slow" );
	});

	/*$( ".button_preinscript" ).click(function() {
		$('.frame_prinscript').slideToggle();
	});*/

	/*if($('.slide_content .readmore').length) {
		setTimeout(function(){
			var elem = $('.slide_content .readmore');
			var height = $(elem).parent().parent().parent().height();
			$(elem).css('bottom', 49);
		 }, 500);	
	}*/

	$( ".link_faq" ).click(function() {
		$('.links_faq').slideToggle();
	});
	$( ".repat_quest" ).click(function() {
		$(this).parent().find('.repat_rep').slideToggle();
	});

	var av = $('#item-header-avatar a img');
	if(av.length) {
		if(av.attr('src').indexOf('gravatar.com') != -1) {
			av.hide();
		}
	}

	$( ".avatar" ).each(function( index ) {
	  	if($(this).attr('src').indexOf('gravatar.com') != -1) {
			$(this).hide();
		}
	});
	$( ".affwp-affiliate-dashboard-tab a" ).each(function() {
		var html = $(this).html() ;
	  	if(html == 'Payouts') {
			$(this).html('Paiements');
		}	
	  	if(html == 'Log out') {
			$(this).html('Déconnexion');
		}	
	});

	var a = ["16002", "13720", "16027", "16030", "16031", "16032", "16033", "16034", "16035", "16036", "16037", "16038", "16039", "16040", "16041", "16042", "16043", "16044", "16045", "16046", "16047", "16048", "16049", "16050", "16051", "16052", "16053", "16054", "16056", "16057", "16058", "16059", "16060", "16061", "16062", "16063", "16064", "16065", "16066", "16067", "16068", "16069", "16070", "16071", "16072"];
	a.forEach(function(item){
		if($(".products li.post-" + item + ".product").length) {
			$(".products li.post-" + item + ".product").append("<div style='text-align:center; color:#FF0000; font-weight:bold; font-size:18px;'>Disponible bientôt</div>")
		}
	});
});
