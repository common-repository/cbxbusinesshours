'use strict';

(function(wp, blocks, element, components, editor) {


  var el                = element.createElement,
      registerBlockType = blocks.registerBlockType,


      RangeControl      = components.RangeControl,
      Panel             = components.Panel,
      PanelBody         = components.PanelBody,
      PanelRow          = components.PanelRow,
      TextControl       = components.TextControl,
      TextareaControl   = components.TextareaControl,
      CheckboxControl   = components.CheckboxControl,
      RadioControl      = components.RadioControl,
      SelectControl     = components.SelectControl,
      ToggleControl     = components.ToggleControl,
      ColorPicker       = components.ColorPalette,
      //ColorPicker = components.ColorPicker,
      //ColorPicker = components.ColorIndicator,
      DateTimePicker    = components.DateTimePicker;

  if(typeof wp.serverSideRender !== 'undefined'){
      var ServerSideRender  = wp.serverSideRender;
  }
  else{
      ServerSideRender  = components.ServerSideRender;
  }

  if(typeof wp.blockEditor.InspectorControls !== 'undefined'){
      var InspectorControls = wp.blockEditor.InspectorControls;
  }
  else{
      var InspectorControls = editor.InspectorControls;
  }

  registerBlockType('codeboxr/cbxbusinesshours', {
    title: cbxbusinesshours_block.block_title,
    icon: 'clock',
    category: cbxbusinesshours_block.block_category,

    //https://rudrastyh.com/gutenberg/inspector-controls.html

    /*
     * In most other blocks, you'd see an 'attributes' property being defined here.
     * We've defined attributes in the PHP, that information is automatically sent
     * to the block editor, so we don't need to redefine it here.
     */
    edit: function(props) {
      return [
        /*
         * The ServerSideRender element uses the REST API to automatically call
         * php_block_render() in your PHP code whenever it needs to get an updated
         * view of the block.
         */
        el(ServerSideRender, {
          block: 'codeboxr/cbxbusinesshours',
          attributes: props.attributes,
        }),

        el(InspectorControls, {},
            // 1st Panel – Form Settings
            el(PanelBody, {title: cbxbusinesshours_block.general_settings.heading, initialOpen: true},
		            el(TextControl,
				            {
					            label: cbxbusinesshours_block.general_settings.title,
					            onChange: (value) => {
						            props.setAttributes({title: value});
					            },
					            value: props.attributes.title,
				            },
		            ),
                el(TextControl,
                    {
                      label: cbxbusinesshours_block.general_settings.post_id,
                      onChange: (value) => {
                        props.setAttributes({post_id: parseInt(value)});
                      },
                      value: props.attributes.post_id,
                    },
                ),
                el( 'p', {'className' :'cbxbusinesshours_block_note'}, cbxbusinesshours_block.general_settings.post_id_note),
                el(SelectControl,
                    {
                        label: cbxbusinesshours_block.general_settings.honor_post_meta,
                        options: cbxbusinesshours_block.general_settings.honor_post_meta_options,
                        onChange: (value) => {
                            props.setAttributes({honor_post_meta: parseInt(value)});
                        },
                        //value: props.attributes.honor_post_meta ? parseInt(props.attributes.honor_post_meta) : 1,
                        value: parseInt(props.attributes.honor_post_meta),
                    },
                ),
                el( 'p', {'className' :'cbxbusinesshours_block_note'}, cbxbusinesshours_block.general_settings.honor_post_meta_note),
                el( 'hr', {} ),

                // Select dropdown field
                el(SelectControl,
                    {
                      label: cbxbusinesshours_block.general_settings.start_of_week,
                      options: cbxbusinesshours_block.general_settings.start_of_week_options,
                      onChange: (value) => {
                        props.setAttributes({start_of_week: parseInt(value)});
                      },
                      //value: props.attributes.start_of_week ? parseInt(props.attributes.start_of_week) : 0,
                      value: parseInt(props.attributes.start_of_week),
                    },
                ),
                el(SelectControl,
                    {
                      label: cbxbusinesshours_block.general_settings.compact,
                      options: cbxbusinesshours_block.general_settings.compact_options,
                      onChange: (value) => {
                        props.setAttributes({compact: parseInt(value)});
                      },
                      value: parseInt(props.attributes.compact)
                      //value: props.attributes.compact ? parseInt(props.attributes.compact) : 0,
                     //value: parseInt(props.attributes.compact),
                    },
                ),
                el(SelectControl,
                    {
                      label: cbxbusinesshours_block.general_settings.time_format,
                      options: cbxbusinesshours_block.general_settings.time_format_options,
                      onChange: (value) => {
                        props.setAttributes({time_format: parseInt(value)});
                      },
                      value: parseInt(props.attributes.time_format),
                      //value: props.attributes.time_format ? parseInt(props.attributes.time_format) : 24,
                      //value: parseInt(props.attributes.time_format),
                    },
                ),
                el(SelectControl,
                    {
                      label: cbxbusinesshours_block.general_settings.day_format,
                      options: cbxbusinesshours_block.general_settings.day_format_options,
                      onChange: (value) => {
                        props.setAttributes({day_format: value});
                      },
                      value: props.attributes.day_format,
                      //value: props.attributes.day_format ? props.attributes.day_format : 'long',
                    },
                ),
                el(SelectControl,
                    {
                      label: cbxbusinesshours_block.general_settings.today,
                      options: cbxbusinesshours_block.general_settings.today_options,
                      onChange: (value) => {
                        props.setAttributes({today: value});
                      },
                      value: props.attributes.today,
                      //value: props.attributes.today ? props.attributes.today : 'week',
                    },
                ),
                el(TextControl,
                    {
                      label: cbxbusinesshours_block.general_settings.custom_date,
                      className: 'cbxbusinesshours_block_datepicker',
                      onChange: (value) => {
                        props.setAttributes({custom_date: value});
                      },
                      //value: props.attributes.custom_date,
                      value: props.attributes.custom_date ? props.attributes.custom_date : '',
                    },
                ),
                el(TextControl,
                    {
                      label: cbxbusinesshours_block.general_settings.before_text,
                      onChange: (value) => {
                        props.setAttributes({before_text: value});
                      },
                      value: props.attributes.before_text,
                    },
                ),
                el(TextControl,
                    {
                      label: cbxbusinesshours_block.general_settings.after_text,
                      onChange: (value) => {
                        props.setAttributes({after_text: value});
                      },
                      value: props.attributes.after_text,
                    },
                ),
            ),
        ),

      ];
    },
    // We're going to be rendering in PHP, so save() can just return null.
    save: function() {
      return null;
    },
  });
}(
    window.wp,
    window.wp.blocks,
    window.wp.element,
    window.wp.components,
    window.wp.editor,
));