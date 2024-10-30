(function($) {
  'use strict';

  $(document).ready(function() {
    //Initiate Color Picker
    $('.wp-color-picker-field').wpColorPicker();
    
    
    $('.selecttwo-select').select2({
      placeholder: cbxbusinesshours_setting.please_select,
      allowClear: false
    });

    // date picker    
    $('.datepicker').datepicker({
      dateFormat: 'yy-mm-dd'
    });

    // timepicker    
    $('.timepicker').timepicker({
      timeFormat: 'H:mm',
      interval: 30,
      //maxTime: '18:00',
      startTime: '0:00',
      dropdown: true,
      scrollbar: true
    });

    //copy fro one weekday to all
    $('.weekdays_day_wrapper').on('click', '.weekdays_day_copytoall', function(e) {
      e.preventDefault();

      var $this           = $(this);
      var $parent         = $this.closest('.weekdays_day');
      var $parent_wrapper = $parent.closest('.weekdays_day_wrapper');

      var $start_value = $parent.find('.timepicker-start').val();
      var $end_value   = $parent.find('.timepicker-end').val();

      $parent_wrapper.find('.timepicker-start').each(function(index, element) {
        var $element = $(element);
        $element.val($start_value);
      });

      $parent_wrapper.find('.timepicker-end').each(function(index, element) {
        var $element = $(element);
        $element.val($end_value);
      });
    });

    //reset all weekdays
    $('.weekdays_day_wrapper').on('click', '.weekdays_day_resetall', function(e) {
      var $this           = $(this);
      //var $parent = $this.closest('.weekdays_day');
      var $parent_wrapper = $this.closest('.weekdays_day_wrapper');

      $parent_wrapper.find('.timepicker-start').each(function(index, element) {
        var $element = $(element);
        $element.val('');
      });

      $parent_wrapper.find('.timepicker-end').each(function(index, element) {
        var $element = $(element);
        $element.val('');
      });
    });

    //reset weekdays single row
    $('.weekdays_day_wrapper').on('click', '.weekdays_day_resetday', function(e) {
      e.preventDefault();

      var $this   = $(this);
      var $parent = $this.closest('.weekdays_day');

      $parent.find('.timepicker-start').val('');
      $parent.find('.timepicker-end').val('');
    });

    //for dayexception field
    // exceptional field added
    $('.dayexception_wrapper').on('click', '.add_exception', function(e) {
      e.preventDefault();
      var $this    = $(this);
      var $name    = $this.data('name');
      var $section = $this.data('section');

      var $ex_wrapper = $this.closest('.dayexception_wrapper');
      var $ex_items   = $ex_wrapper.find('.dayexception_items');

      var $ex_last_count     = $ex_wrapper.find('.dayexception_last_count');
      var $ex_last_count_val = parseInt($ex_last_count.val());

      $ex_last_count_val++;

      $ex_last_count.val($ex_last_count_val);

      var field = '<p class="dayexception_item">' +
          '<input type="text" name="' + $section + '[' + $name + '][dayexceptions][' + $ex_last_count_val + '][ex_date]" placeholder="' + cbxbusinesshours_setting.date + '" class="datepicker" autocomplete="off" required />&nbsp;' +
          '<input type="text" name="' + $section + '[' + $name + '][dayexceptions][' + $ex_last_count_val + '][ex_start]" placeholder="' + cbxbusinesshours_setting.start + '" class="timepicker timepicker-start" autocomplete="off"  />&nbsp;' +
          '<input type="text" name="' + $section + '[' + $name + '][dayexceptions][' + $ex_last_count_val + '][ex_end]" placeholder="' + cbxbusinesshours_setting.end + '" class="timepicker timepicker-end" autocomplete="off"  />&nbsp;' +
          '<input type="text" name="' + $section + '[' + $name + '][dayexceptions][' + $ex_last_count_val + '][ex_subject]" placeholder="' + cbxbusinesshours_setting.subject + '" autocomplete="off" />&nbsp;' +
          '<a href="#" class="remove_exception button">' + '<span class="dashicons dashicons-trash" style="margin-top: 3px;color: red;"></span>' + cbxbusinesshours_setting.remove + '</a>' +
          '</p>';

      $ex_items.append(field);

      // timepicker
      $('#cbxbusinesshours_hours').find('.timepicker').timepicker({
        timeFormat: 'H:mm',
        interval: 30,
        //maxTime: '18:00',
        startTime: '0:00',
        dropdown: true,
        scrollbar: true,
      });

      // date picker
      $('#cbxbusinesshours_hours').find('.datepicker').datepicker({
        dateFormat: 'yy-mm-dd',
      });

    }); // end exceptional field

    // Remove single exception row
    $('.dayexception_wrapper').on('click', '.remove_exception', function(e) {
      e.preventDefault();

      var $this = $(this);
      $this.closest('.dayexception_item').remove();
    });

    // Remove all exception rows
    $('.dayexception_wrapper').on('click', '.removeall_exception', function(e) {
      e.preventDefault();

      var $this           = $(this);
      var $parent_wrapper = $this.closest('.dayexception_wrapper');
      $parent_wrapper.find('.dayexception_items').empty();
    });

    // Switches option sections
    //$('.cbxbusinesshours_group').hide();
    var activetab = '';
    if (typeof (localStorage) !== 'undefined') {      
      activetab = localStorage.getItem('cbxbusinesshoursactivetab');
    }

    //if url has section id as hash then set it as active or override the current local storage value
    if (window.location.hash) {
      if ($(window.location.hash).hasClass('cbxbusinesshours_group')) {
        activetab = window.location.hash;
        if (typeof (localStorage) !== 'undefined') {
          localStorage.setItem('cbxbusinesshoursactivetab', activetab);
        }
      }
    }

    if (activetab !== '' && $(activetab).length && $(activetab).hasClass('cbxbusinesshours_group')) {
      $('.cbxbusinesshours_group').hide();
      $(activetab).fadeIn();
    }


    if (activetab !== '' && $(activetab + '-tab').length) {
      $('.nav-tab-wrapper a.nav-tab').removeClass('nav-tab-active');
      $(activetab + '-tab').addClass('nav-tab-active');
    }

    $('.nav-tab-wrapper a').on('click', function(e) {
      e.preventDefault();

      var $this = $(this);

      $('.nav-tab-wrapper a.nav-tab').removeClass('nav-tab-active');
      $this.addClass('nav-tab-active').blur();

      var clicked_group = $(this).attr('href');

      if (typeof(localStorage) !== 'undefined') {
        localStorage.setItem('cbxbusinesshoursactivetab', $(this).attr('href'));
      }
      $('.cbxbusinesshours_group').hide();
      $(clicked_group).fadeIn();
    });

    $('.wpsa-browse').on('click', function(event) {
      event.preventDefault();

      var self = $(this);

      // Create the media frame.
      var file_frame = wp.media.frames.file_frame = wp.media({
        title: cbxbusinesshours_setting.upload_title,
        button: {
          text: cbxbusinesshours_setting.please_select
        },
        multiple: false
      });

      file_frame.on('select', function() {
        var attachment = file_frame.state().get('selection').first().toJSON();

        self.prev('.wpsa-url').val(attachment.url);
      });

      // Finally, open the modal
      file_frame.open();
    }); //end file chooser

    //make the subheading single row
    $('.setting_subheading').each(function(index, element) {
      var $element        = $(element);
      var $element_parent = $element.parent('td');
      $element_parent.attr('colspan', 2);
      $element_parent.prev('th').remove();
    });

    //make the subheading single row
    $('.setting_heading').each(function(index, element) {
      var $element        = $(element);
      var $element_parent = $element.parent('td');
      $element_parent.attr('colspan', 2);
      $element_parent.prev('th').remove();
    });

    $('.cbxbusinesshours_group').each(function(index, element) {
      var $element    = $(element);
      var $form_table = $element.find('.form-table');
      $form_table.prev('h2').remove();
    });

    $('.weekdays_day_wrapper').closest('td').attr('colspan', 2);
    $('.weekdays').find('th').remove();

    $('.dayexception_wrapper').closest('td').attr('colspan', 2);
    $('.dayexception').find('th').remove();

    //$('.shortcode_demo').find('th[scope="row"]').remove();
    //$('.shortcode_demo_wrap').closest('td').attr('colspan', 2);

    //copy shortcode
    $('.shortcode_demo_btn').on('click', function(event) {
      event.preventDefault();

      var $this      = $(this);
      var $target    = $this.data('target-cp');
      var $copy_area = $($target);

      $copy_area.focus();
      $copy_area.select();

      try {
        var successful = document.execCommand('copy');
        if (successful) {
          $this.text(cbxbusinesshours_setting.copy_success);
          $this.addClass('copy_success');
        } else {
          $this.text(cbxbusinesshours_setting.copy_fail);
          $this.addClass('copy_fail');
        }
      } catch (err) {
        $this.text(cbxbusinesshours_setting.copy_fail);
        $this.addClass('copy_fail');
      }
    });//end copy shortcode

    //one click save setting for the current tab
    $('#save_settings').on('click', function (e) {
      e.preventDefault();

      var $current_tab = $('.nav-tab.nav-tab-active');
      var $tab_id      = $current_tab.data('tabid');
      $('#' + $tab_id).find('.submit_cbxbusinesshours').trigger('click');
    });

  });//end dom ready

})(jQuery);