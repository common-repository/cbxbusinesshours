(function ($) {
	'use strict';

	$(document).ready(function () {
		//Initiate Color Picker
		//$('.wp-color-picker-field').wpColorPicker();

		//$('#post').attr('autocomplete', 'false');


		$('#cbxbusinesshours_meta_wrapper').find(".selecttwo-select").select2({
			placeholder: cbxbusinesshours_meta.please_select,
			allowClear: false
		});

		// date picker
		//$('#cbxbusinesshours_meta_wrapper').find(".datepicker").datepicker({
		$('#cbxbusinesshours_meta_wrapper').find(".datepicker").datepicker({
			dateFormat: 'yy-mm-dd'
		});

		/// timepicker
        //$('#cbxbusinesshours_meta_wrapper').find('.timepicker').timepicker({
		$('#cbxbusinesshours_meta_wrapper').find('.timepicker').timepicker({
            timeFormat: 'H:mm',
            interval: 30,
            //maxTime: '18:00',
            startTime: '0:00',
            dropdown: true,
            scrollbar: true
        });



		//copy from one weekday to all
		$('#cbxbusinesshours_meta_wrapper').find('.weekdays_day_wrapper').on('click', '.weekdays_day_copytoall', function (e) {
			e.preventDefault();

			var $this = $(this);
			var $parent = $this.closest('.weekdays_day');
			var $parent_wrapper = $parent.closest('.weekdays_day_wrapper');

			var $start_value = $parent.find('.timepicker-start').val();
			var $end_value = $parent.find('.timepicker-end').val();

			$parent_wrapper.find('.timepicker-start').each(function (index, element) {
				var $element = $(element);
				$element.val($start_value);
			});

			$parent_wrapper.find('.timepicker-end').each(function (index, element) {
				var $element = $(element);
				$element.val($end_value);
			});
		});

		//reset all weekdays
		$('#cbxbusinesshours_meta_wrapper').find('.weekdays_day_wrapper').on('click', '.weekdays_day_resetall', function (e) {
			var $this = $(this);
			//var $parent = $this.closest('.weekdays_day');
			var $parent_wrapper = $this.closest('.weekdays_day_wrapper');

			$parent_wrapper.find('.timepicker-start').each(function (index, element) {
				var $element = $(element);
				$element.val('');
			});

			$parent_wrapper.find('.timepicker-end').each(function (index, element) {
				var $element = $(element);
				$element.val('');
			});
		});

		//reset weekdays single row
		$('#cbxbusinesshours_meta_wrapper').find('.weekdays_day_wrapper').on('click', '.weekdays_day_resetday', function (e) {
			e.preventDefault();

			var $this = $(this);
			var $parent = $this.closest('.weekdays_day');

			$parent.find('.timepicker-start').val('');
			$parent.find('.timepicker-end').val('');
		});


		//for dayexception field
		// exceptional field added
		$('#cbxbusinesshours_meta_wrapper').find(".dayexception_wrapper").on('click', '.add_exception', function (e) {
			e.preventDefault();
			var $this = $(this);
			var $name = $this.data('name');

			var $ex_wrapper = $this.closest(".dayexception_wrapper");
			var $ex_items = $ex_wrapper.find(".dayexception_items");

			var $ex_last_count = $ex_wrapper.find('.dayexception_last_count');
			var $ex_last_count_val = parseInt($ex_last_count.val());


			$ex_last_count_val++;

			$ex_last_count.val($ex_last_count_val);

			var field = '<p class="dayexception_item">' +
				'<input type="text" name="cbxbusinesshours_meta_dayexception[dayexceptions][' + $ex_last_count_val + '][ex_date]" placeholder="' + cbxbusinesshours_meta.date + '" class="datepicker" autocomplete="new-password" required />&nbsp;' +

				'<input type="text" name="cbxbusinesshours_meta_dayexception[dayexceptions][' + $ex_last_count_val + '][ex_start]" placeholder="' + cbxbusinesshours_meta.start + '" class="timepicker timepicker-start" autocomplete="new-password"  />&nbsp;' +

				'<input type="text" name="cbxbusinesshours_meta_dayexception[dayexceptions][' + $ex_last_count_val + '][ex_end]" placeholder="' + cbxbusinesshours_meta.end + '" class="timepicker timepicker-end" autocomplete="new-password"  />&nbsp;' +

				'<input type="text" name="cbxbusinesshours_meta_dayexception[dayexceptions][' + $ex_last_count_val + '][ex_subject]" placeholder="' + cbxbusinesshours_meta.subject + '" autocomplete="new-password" />&nbsp;' +

				'<a href="#" class="remove_exception button">' +'<span class="dashicons dashicons-trash" style="margin-top: 3px;color: red;"></span>'+ cbxbusinesshours_meta.remove +'</a>' +
				'</p>';

			$ex_items.append(field);

			// timepicker
			$('#cbxbusinesshours_meta_wrapper').find('.timepicker').timepicker({
				timeFormat: 'H:mm',
				interval: 30,
				//maxTime: '18:00',
				startTime: '0:00',
				dropdown: true,
				scrollbar: true
			});

			// date picker
			$('#cbxbusinesshours_meta_wrapper').find(".datepicker").datepicker({
				dateFormat: 'yy-mm-dd'
			});


		}); // end exceptional field

		// Remove single exception row
		$('#cbxbusinesshours_meta_wrapper').find(".dayexception_wrapper").on('click', '.remove_exception', function (e) {
			e.preventDefault();

			var $this = $(this);
			$this.closest(".dayexception_item").remove();
		});

		// Remove all exception rows
		$('#cbxbusinesshours_meta_wrapper').find(".dayexception_wrapper").on('click', '.removeall_exception', function (e) {
			e.preventDefault();

			var $this = $(this);
			var $parent_wrapper = $this.closest('.dayexception_wrapper');
			$parent_wrapper.find('.dayexception_items').empty();
		});


		// Switches option sections
		$('.cbxbusinesshours_group').hide();
		var activetab = '';
		if (typeof(localStorage) != 'undefined') {
			//get
			activetab = localStorage.getItem("cbxbusinesshour_meta_activetab");
		}
		if (activetab != '' && $(activetab).length) {
			$(activetab).fadeIn();
		} else {
			$('.cbxbusinesshours_group:first').fadeIn();
		}
		$('.cbxbusinesshours_group .collapsed').each(function () {
			$(this).find('input:checked').parent().parent().parent().nextAll().each(
				function () {
					if ($(this).hasClass('last')) {
						$(this).removeClass('hidden');
						return false;
					}
					$(this).filter('.hidden').removeClass('hidden');
				});
		});

		if (activetab != '' && $(activetab + '-tab').length) {
			$(activetab + '-tab').addClass('nav-tab-active');
		}
		else {
			$('.nav-tab-wrapper a:first').addClass('nav-tab-active');
		}

		$('.nav-tab-wrapper a').on('click', function (evt) {
			evt.preventDefault();

			$('.nav-tab-wrapper a').removeClass('nav-tab-active');
			$(this).addClass('nav-tab-active').blur();
			var clicked_group = $(this).attr('href');
			if (typeof(localStorage) != 'undefined') {
				//set
				localStorage.setItem("cbxbusinesshour_meta_activetab", $(this).attr('href'));
			}
			$('.cbxbusinesshours_group').hide();
			$(clicked_group).fadeIn();

		});

		//copy shortcode
		$('.shortcode_demo_btn').on('click', function (event) {
			event.preventDefault();

			var $this = $(this);
			var $target = $this.data('target-cp');
			var $copy_area = $($target);

			$copy_area.focus();
			$copy_area.select();

			try {
				var successful = document.execCommand('copy');
				if(successful){
					$this.text(cbxbusinesshours_meta.copy_success);
					$this.addClass('copy_success');
				}
				else{
					$this.text(cbxbusinesshours_meta.copy_fail);
					$this.addClass('copy_fail');
				}
			} catch (err) {
				$this.text(cbxbusinesshours_meta.copy_fail);
				$this.addClass('copy_fail');
			}

		});//end copy shortcode

	});//end dom ready

})(jQuery);