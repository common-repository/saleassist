var _____WB$wombat$assign$function_____ = function(name) {return (self._wb_wombat && self._wb_wombat.local_init && self._wb_wombat.local_init(name)) || self[name]; };
if (!self.__WB_pmw) { self.__WB_pmw = function(obj) { this.__WB_source = obj; return this; } }
{
  let window = _____WB$wombat$assign$function_____("window");
  let self = _____WB$wombat$assign$function_____("self");
  let document = _____WB$wombat$assign$function_____("document");
  let location = _____WB$wombat$assign$function_____("location");
  let top = _____WB$wombat$assign$function_____("top");
  let parent = _____WB$wombat$assign$function_____("parent");
  let frames = _____WB$wombat$assign$function_____("frames");
  let opener = _____WB$wombat$assign$function_____("opener");

Button = {};
Button.styles = [];
Button.styles_markup = '';
Button.styles_hover_markup = '';
Button.pixel_properties = ['font-size', 'border-radius', 'padding', 'padding-top', 'padding-right', 'padding-bottom', 'padding-left'];
Button.gradient_properties = ['bg-start-gradient', 'bg-end-gradient'];
Button.input_focus = '';

jQuery(function(){

  jQuery('input,textarea').attr('autocomplete', 'off');

  Button.attach_handlers();
  Button.initialize_controls();
  Button.update_styles();

  jQuery('.copy').zclip({
    path: 'js/ZeroClipboard.swf',
    copy: function(){
      return jQuery('#css-display').text();
    },
    afterCopy: function(){
      jQuery('.copy-success').fadeIn(200, function(){
        jQuery(this).fadeOut(1000);
      });
    }
  });

});

/**
 * Initialize any DOM functionality (clicks, changes, etc..)
 */
Button.attach_handlers = function(){

  // accordion
  jQuery('#settings-wrap .panel-wrap').click(function() {
    if(!jQuery(this).hasClass('active')){
      jQuery('.panel-wrap').removeClass('active');
      jQuery('.accordion-inner').slideUp(200);
      jQuery(this).addClass('active').next('.accordion-inner').slideDown(200);
    }
  });

  // more link
  jQuery('.more-link').click(function(e){
    e.preventDefault();
    jQuery(this).html() == 'More' ? jQuery(this).html('Hide') : jQuery(this).html('More');
    var section = jQuery(this).attr('data-section');
    jQuery('[data-section-more="' + section + '"]').slideToggle(200);
  });

  // settings input click
  jQuery('#settings-wrap [data-control]').change(function(){
    Button.control_update(jQuery(this));
  });

  // settings display click
  jQuery('#settings-wrap [data-control-display]').change(function(){
    Button.control_display_change(jQuery(this));
  });

  // apply a slider to any input with class "slider-bound"
  jQuery('input.slider-bound').each(function(index) {
    // get input ID
    var inputId = jQuery(this).attr('id');
    // get input value
    var inputValue = jQuery(this).val();
    // get input max
    var inputMax = jQuery(this).attr('max');
    jQuery('#'+inputId+'-slider').slider({
      value: inputValue,
      max: inputMax,
      min: 0,
      slide: function(event, ui){
        jQuery(this).prev().val(ui.value);
        Button.update_styles();
      }
    });
  });

  jQuery('input, select').on('change keyup', function(){
    Button.update_styles();
    if(jQuery(this).hasClass('slider-bound')){
      jQuery('#' + jQuery(this).attr('id') + '-slider').slider('value', jQuery(this).val());
    }
  });

  jQuery('.color').focus(function(e) {
    Button.input_focus = jQuery(this);
  });

  // open the color picker when the color preview is clicked
  jQuery('.color-view').click(function(){
    Button.input_focus = jQuery(this).prev();
    jQuery(this).prev().ColorPickerShow();
  });

  // initialize the color picker
  jQuery('.color').ColorPicker({
    onSubmit: function(hsb, hex, rgb, el){
      // hide the color picker
      jQuery(el).ColorPickerHide();
      jQuery(el).val('#'+hex);
    },
    onBeforeShow: function (){
      jQuery(this).ColorPickerSetColor(this.value);
    },
    onChange: function(hsb, hex, rgb, el){
      // populate the input with the hex value
      Button.input_focus.val('#'+hex);
      // update the color preview
      Button.input_focus.next('.color-view').css('backgroundColor', '#'+hex);
      Button.update_styles();
    }
  });

  jQuery('.saleassist-btn').click(function(e){
    e.preventDefault();
  });

}

/**
 * Initialize (enable / disable) controls on load
 */
Button.initialize_controls = function(){
  jQuery('#settings-wrap [data-control]').each(function(){
    Button.control_update(jQuery(this));
  });
  jQuery('#settings-wrap [data-control-display]:checked').each(function(){
    Button.control_display_change(jQuery(this));
  });
  jQuery('.color-view').each(function(){
    jQuery(this).css('background-color', jQuery(this).prev().val());
  });
}

/**
 * Change a control value (enable / disable controls)
 */
Button.control_update = function(el){
  var checked = el.is(':checked');
  var control = el.attr('data-control');

  // standard one-to-one controls
  jQuery('[data-control-group="' + control + '"]').each(function(){
    checked ? Button.enable_control(jQuery(this)) : Button.disable_control(jQuery(this));
  });

  // group-switch controls
  jQuery('[data-control-group-switch="' + control + '"]').each(function(){
    if(checked){
      Button.disable_control(jQuery(this));
    }else{
      jQuery(this).find(':checkbox').each(function(){
        this.disabled = false;
      });
      jQuery(this).removeClass('disabled').find('[data-control]').each(function(){
        Button.control_update(jQuery(this));
      });
    }
  });
}

/**
 * Change a control display (hide / show)
 */
Button.control_display_change = function(el){
  var control = el.attr('data-control-display');
  var display_selector = el.attr('name');
  jQuery('[data-control-display-selector="' + display_selector + '"]').addClass('hidden').hide();
  jQuery('[data-control-display-group="' + control + '"]').removeClass('hidden').show();
  Button.enable_control(jQuery('[data-control-display-group="' + control + '"]'));
  Button.disable_control(jQuery('[data-control-display-selector="' + display_selector + '"].hidden'));
}

/**
 * Disable all inputs and sliders in the element (el)
 */
Button.disable_control = function(el){
  el.addClass('disabled');
  el.find('.ui-slider').slider('disable');
  el.find('input, select').each(function(){
    this.disabled = true;
  });
}

/**
 * Enable all inputs and sliders in the element (el)
 */
Button.enable_control = function(el){
  el.removeClass('disabled');
  el.find('.ui-slider').slider('enable');
  el.find('input, select').each(function(){
    this.disabled = false;
  });
}

/**
 * Update the array that stores all css values
 */
Button.update_styles = function(){
  Button.prepare_styles();
  Button.generate_style_markup();
  Button.render_styles();
}

/**
 * Prepares the raw style data for css presentation (removes, combines, etc..)
 */
Button.prepare_styles = function(){
  Button.styles = {};
  Button.styles_markup = '';
  Button.styles_hover_markup = '';

  jQuery('#settings-wrap').find('input[type="text"], select').not(':disabled').each(function(){
    var css_property = jQuery(this).attr('id');
    Button.styles[css_property] = jQuery(this).val();
  });

  // remove the text data
  jQuery('.saleassist-btn').html(Button.styles['text']);
  delete Button.styles['text'];

  // combine padding if all are present
  var padding_top, padding_right, padding_bottom, padding_left;
  if((padding_top = Button.styles['padding-top']) &&
     (padding_right = Button.styles['padding-right']) &&
     (padding_bottom = Button.styles['padding-bottom']) &&
     (padding_left = Button.styles['padding-left'])){
    Button.styles['padding'] = padding_top + 'px ' + padding_right + 'px ' + padding_bottom + 'px ' + padding_left;
    delete Button.styles['padding-top'];
    delete Button.styles['padding-right'];
    delete Button.styles['padding-bottom'];
    delete Button.styles['padding-left'];
  }

  // combine border styles
  var border_style, border_color, border_width;
  if((border_style = Button.styles['border-style']) &&
     (border_color = Button.styles['border-color']) &&
     (border_width = Button.styles['border-width'])){
    Button.styles['border'] = border_style + ' ' + border_color + ' ' + border_width + 'px';
    delete Button.styles['border-style'];
    delete Button.styles['border-color'];
    delete Button.styles['border-width'];
  }

  // combine border-top styles
  var border_top_style, border_top_color, border_top_width;
  if((border_top_style = Button.styles['border-top-style']) &&
     (border_top_color = Button.styles['border-top-color']) &&
     (border_top_width = Button.styles['border-top-width'])){
    Button.styles['border-top'] = border_top_style + ' ' + border_top_color + ' ' + border_top_width + 'px';
    delete Button.styles['border-top-style'];
    delete Button.styles['border-top-color'];
    delete Button.styles['border-top-width'];
  }

  // combine border-right styles
  var border_right_style, border_right_color, border_right_width;
  if((border_right_style = Button.styles['border-right-style']) &&
     (border_right_color = Button.styles['border-right-color']) &&
     (border_right_width = Button.styles['border-right-width'])){
    Button.styles['border-right'] = border_right_style + ' ' + border_right_color + ' ' + border_right_width + 'px';
    delete Button.styles['border-right-style'];
    delete Button.styles['border-right-color'];
    delete Button.styles['border-right-width'];
  }

  // combine border-bottom styles
  var border_bottom_style, border_bottom_color, border_bottom_width;
  if((border_bottom_style = Button.styles['border-bottom-style']) &&
     (border_bottom_color = Button.styles['border-bottom-color']) &&
     (border_bottom_width = Button.styles['border-bottom-width'])){
    Button.styles['border-bottom'] = border_bottom_style + ' ' + border_bottom_color + ' ' + border_bottom_width + 'px';
    delete Button.styles['border-bottom-style'];
    delete Button.styles['border-bottom-color'];
    delete Button.styles['border-bottom-width'];
  }

  // combine border-left styles
  var border_left_style, border_left_color, border_left_width;
  if((border_left_style = Button.styles['border-left-style']) &&
     (border_left_color = Button.styles['border-left-color']) &&
     (border_left_width = Button.styles['border-left-width'])){
    Button.styles['border-left'] = border_left_style + ' ' + border_left_color + ' ' + border_left_width + 'px';
    delete Button.styles['border-left-style'];
    delete Button.styles['border-left-color'];
    delete Button.styles['border-left-width'];
  }
}

/**
 * Populates the Button.styles_markup property with the renderable string
 */
Button.generate_style_markup = function(){

  // gradients
  var gradient_start, gradient_end;
  if((gradient_start = Button.styles['bg-start-gradient']) &&
     (gradient_end = Button.styles['bg-end-gradient'])){
    Button.styles_markup += Button.render_style_line('background', gradient_start);
    Button.styles_markup += Button.render_style_line('background-image', '-webkit-linear-gradient(top, ' + gradient_start + ', ' + gradient_end + ')');
    Button.styles_markup += Button.render_style_line('background-image', '-moz-linear-gradient(top, ' + gradient_start + ', ' + gradient_end + ')');
    Button.styles_markup += Button.render_style_line('background-image', '-ms-linear-gradient(top, ' + gradient_start + ', ' + gradient_end + ')');
    Button.styles_markup += Button.render_style_line('background-image', '-o-linear-gradient(top, ' + gradient_start + ', ' + gradient_end + ')');
    Button.styles_markup += Button.render_style_line('background-image', 'linear-gradient(to bottom, ' + gradient_start + ', ' + gradient_end + ')');
    delete Button.styles['bg-start-gradient'];
    delete Button.styles['bg-end-gradient'];
    delete Button.styles['bg-color'];
  }

  // gradient hovers
  var gradient_hover_start, gradient_hover_end;
  if((gradient_hover_start = Button.styles['bg-start-gradient-hover']) &&
     (gradient_hover_end = Button.styles['bg-end-gradient-hover'])){
    Button.styles_hover_markup += Button.render_style_line('background', gradient_hover_start);
    Button.styles_hover_markup += Button.render_style_line('background-image', '-webkit-linear-gradient(top, ' + gradient_hover_start + ', ' + gradient_hover_end + ')');
    Button.styles_hover_markup += Button.render_style_line('background-image', '-moz-linear-gradient(top, ' + gradient_hover_start + ', ' + gradient_hover_end + ')');
    Button.styles_hover_markup += Button.render_style_line('background-image', '-ms-linear-gradient(top, ' + gradient_hover_start + ', ' + gradient_hover_end + ')');
    Button.styles_hover_markup += Button.render_style_line('background-image', '-o-linear-gradient(top, ' + gradient_hover_start + ', ' + gradient_hover_end + ')');
    Button.styles_hover_markup += Button.render_style_line('background-image', 'linear-gradient(to bottom, ' + gradient_hover_start + ', ' + gradient_hover_end + ')');
    delete Button.styles['bg-start-gradient-hover'];
    delete Button.styles['bg-end-gradient-hover'];
    delete Button.styles['background-hover'];
  }

  // border radius
  var border_radius;
  if((border_radius = Button.styles['border-radius'])){
    Button.styles_markup += Button.render_style_line('-webkit-border-radius', border_radius);
    Button.styles_markup += Button.render_style_line('-moz-border-radius', border_radius);
    Button.styles_markup += Button.render_style_line('border-radius', border_radius);
    delete Button.styles['border-radius'];
  }

  // text shadow
  var text_shadow_color, text_shadow_x, text_shadow_y, text_shadow_blur;
  if((text_shadow_color = Button.styles['text-shadow-color']) &&
     (text_shadow_x = Button.styles['text-shadow-x']) &&
     (text_shadow_y = Button.styles['text-shadow-y']) &&
     (text_shadow_blur = Button.styles['text-shadow-blur'])){
    Button.styles_markup += Button.render_style_line('text-shadow', text_shadow_x + 'px ' + text_shadow_y + 'px ' + text_shadow_blur + 'px ' + text_shadow_color);
    delete Button.styles['text-shadow-color'];
    delete Button.styles['text-shadow-x'];
    delete Button.styles['text-shadow-y'];
    delete Button.styles['text-shadow-blur'];
  }

  // box shadow
  var box_shadow_color, box_shadow_x, box_shadow_y, box_shadow_blur;
  if((box_shadow_color = Button.styles['box-shadow-color']) &&
     (box_shadow_x = Button.styles['box-shadow-x']) &&
     (box_shadow_y = Button.styles['box-shadow-y']) &&
     (box_shadow_blur = Button.styles['box-shadow-blur'])){
    Button.styles_markup += Button.render_style_line('-webkit-box-shadow', box_shadow_x + 'px ' + box_shadow_y + 'px ' + box_shadow_blur + 'px ' + box_shadow_color);
    Button.styles_markup += Button.render_style_line('-moz-box-shadow', box_shadow_x + 'px ' + box_shadow_y + 'px ' + box_shadow_blur + 'px ' + box_shadow_color);
    Button.styles_markup += Button.render_style_line('box-shadow', box_shadow_x + 'px ' + box_shadow_y + 'px ' + box_shadow_blur + 'px ' + box_shadow_color);
    delete Button.styles['box-shadow-color'];
    delete Button.styles['box-shadow-x'];
    delete Button.styles['box-shadow-y'];
    delete Button.styles['box-shadow-blur'];
  }

  jQuery.each(Button.styles, function(css_property, css_value){
    // check if "px" should appended to the style
    var px_value = jQuery.inArray(css_property, Button.pixel_properties) > -1 ? 'px' : '';
    var tab = '&nbsp;&nbsp;';
    // handle the hover background
    if(css_property == 'background-hover'){
      Button.styles_hover_markup = Button.render_style_line('background', css_value);
    }else{
      Button.styles_markup += Button.render_style_line(css_property, css_value);
    }
  });

  // remove text-decoration
  Button.styles_markup += Button.render_style_line('text-decoration', 'none');

  // wrap the style markups in proper css calls
  var btn_class_name  = jQuery("#btn_class_name").val();
  Button.styles_markup = '.'+btn_class_name+' {\n' + Button.styles_markup + '}';
  Button.styles_hover_markup += Button.render_style_line('text-decoration', 'none');
  Button.styles_hover_markup = '\n\n.'+btn_class_name+':hover {\n' + Button.styles_hover_markup + '}';
}

/**
 * Update the output of the css styles
 */
Button.render_styles = function(){
  var output = Button.styles_markup + Button.styles_hover_markup;   
  var style_tag = '<style id="dynamic-styles" type="text/css">' + output + '</style>';
  jQuery('#dynamic-styles').replaceWith(style_tag);
  jQuery('#css-display').html('<pre>' + output + '</pre>');
}

/**
 * Renders an individual style line
 */
Button.render_style_line = function(css_property, css_value){
  // check if "px" should appended to the style
  var px_value = jQuery.inArray(css_property, Button.pixel_properties) > -1 ? 'px' : '';
  var tab = '  ';
  return tab + css_property + ': ' + css_value + px_value + ';\n';
}

}