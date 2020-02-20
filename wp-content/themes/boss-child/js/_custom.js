jQuery( document ).ready(function($) {

	if($('#cours-personal-li a').length) {
		$('#cours-personal-li a').text('Mes formations');
	}

	if($('.learndash_profile_heading .title').length) {
		$('.learndash_profile_heading .title').html('Mes formations');
	}
	if($('.expand_collapse a:nth-child(1)').length) {
		$('.expand_collapse a:nth-child(1)').text('Tout déployer');
	}
	if($('.expand_collapse a:nth-child(3)').length) {
		$('.expand_collapse a:nth-child(3)').text('Tout contracter');
	}
	if($('#item-body h3').length) {
		var the_text = $('#item-body h3').html().replace("Registered Cours", "Formations");
		$('#item-body h3').html(the_text);
	}
	if($('.prev-link').length) {
		var the_text = $('.prev-link').html().replace("Previous Leçon", "Leçon précédente");
		$('.prev-link').html(the_text);
	}
	if($('.next-link').length) {
		var the_text = $('.next-link').html().replace("Next Leçon", "Leçon suivante");
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
		var the_text = $('#learndash_lessons').html().replace("Cours", "Formations");
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
});
