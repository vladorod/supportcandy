<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'WPSC_Ticket_Form_Field' ) ) :
    
    class WPSC_Ticket_Form_Field {
        
        var $slug;
        var $type;
        var $label;
        var $extra_info;
				var $status;
				var $options;
        var $required;
        var $width;
        var $col_class;
				var $visibility;
				var $visibility_conditions;
				var $limit;
				var $placeholder;
								
        function print_field($field){
          
          $this->slug = $field->slug;
					$this->type = get_term_meta( $field->term_id, 'wpsc_tf_type', true);
					$this->placeholder = get_term_meta($field->term_id,'wpsc_tf_placeholder_text',true);
          $this->label = get_term_meta( $field->term_id, 'wpsc_tf_label', true);
          $this->extra_info = get_term_meta( $field->term_id, 'wpsc_tf_extra_info', true);
					$this->status = get_term_meta( $field->term_id, 'wpsc_tf_status', true);
					$this->options = get_term_meta( $field->term_id, 'wpsc_tf_options', true);
          $this->required = get_term_meta( $field->term_id, 'wpsc_tf_required', true);
          $this->width = get_term_meta( $field->term_id, 'wpsc_tf_width', true);
					$this->visibility = get_term_meta( $field->term_id, 'wpsc_tf_visibility', true);
					$this->visibility_conditions = is_array($this->visibility) && $this->visibility ? implode(';;', $this->visibility) : '';
					$this->visibility_conditions = str_replace('"','&quot;',$this->visibility_conditions);
					$this->limit = get_term_meta($field->term_id,'wpsc_tf_limit',true);
					$this->limit = $this->limit ? $this->limit:"0";
						
          switch ($this->width) {
            case '1/3':
              $this->col_class = 'col-sm-4';
              break;
              
            case '1/2':
              $this->col_class = 'col-sm-6';
              break;
                
            case '1':
              $this->col_class = 'col-sm-12';
              break;
          }
          
          if ($this->type=='0') {
            switch ($field->slug) {
              
              case 'customer_name':
								$this->print_customer_name($field);
								break;
							
              case 'customer_email':
                $this->print_customer_email($field);
                break;
                
              case 'ticket_subject':
                if ($this->status == '1') {
									$this->print_ticket_subject($field);
								}
                break;
                
              case 'ticket_description':
                if ($this->status == '1') {
									$this->print_ticket_description($field);
								}
                break;
                
              case 'ticket_category':
                if ($this->status == '1') {
                	$this->print_ticket_category($field);
                }
                break;
								
							case 'ticket_priority':
                if ($this->status == '1') {
									$this->print_ticket_priority($field);
								}
                break;
              
              default:
                do_action('wpsc_print_default_form_field', $field, $this);
                break;
            }
						
          } else {
						
						switch ($this->type) {
							
							case '1':
								$this->print_text_field($field);
								break;
								
							case '2':
								$this->print_drop_down($field);
								break;
								
							case '3':
								$this->print_checkbox($field);
								break;
								
							case '4':
								$this->print_radio_btn($field);
								break;
								
							case '5':
								$this->print_textarea($field);
								break;
								
							case '6':
								$this->print_date($field);
								break;
								
							case '7':
								$this->print_url($field);
								break;
								
							case '8':
								$this->print_email($field);
								break;
								
							case '9':
								$this->print_numberonly($field);
								break;
								
							case '10': 
								$this->print_file_attachment($field);
								break;
								
							case '18':
									$this->print_date_time($field);
									break;
									
							default:
								do_action('wpsc_print_custom_form_field', $field, $this);
								break;
						}
						
					}
          
        }
        
				function print_text_field($field){
					$wpsc_appearance_create_ticket = get_option('wpsc_create_ticket');

					$extra_info_css = 'color:'.$wpsc_appearance_create_ticket['wpsc_extra_info_text_color'].' !important;';
					$value = apply_filters('wpsc_custom_text_default_value', '', $field);
					$label = get_option('wpsc_custom_fields_localize');
					$extra_info = get_option('wpsc_custom_fields_extra_info');
          ?>
          <div  data-fieldtype="text" data-visibility="<?php echo $this->visibility_conditions?>" class="<?php echo $this->col_class?> <?php echo $this->visibility? 'hidden':'visible'?> <?php echo $this->required? 'wpsc_required':''?> form-group wpsc_form_field <?php echo 'field_'.$field->term_id?>">
            <label class="wpsc_ct_field_label" for="<?php echo $this->slug;?>">
              <?php echo $label['custom_fields_'.$field->term_id];?> <?php echo $this->required? '<span style="color:red;">*</span>':'';?>
            </label>
            <?php if($extra_info['custom_fields_extra_info_'.$field->term_id]){?><p class="help-block" style="<?php echo $extra_info_css?>"><?php echo $extra_info['custom_fields_extra_info_'.$field->term_id];?></p><?php }?>
            <input type="text" id="<?php echo $this->slug;?>" class="form-control wpsc_textfield" placeholder="<?php echo $this->placeholder; ?>" name="<?php echo $this->slug;?>" autocomplete="off" value="<?php echo $value?>" onkeypress="wpsc_text_limit(event,this,<?php echo $this->limit ?>);">
          </div>
          <?php
        }
				
				function print_drop_down($field){
					$wpsc_appearance_create_ticket = get_option('wpsc_create_ticket');
					$extra_info_css = 'color:'.$wpsc_appearance_create_ticket['wpsc_extra_info_text_color'].' !important;';
					$label = get_option('wpsc_custom_fields_localize');
					$extra_info = get_option('wpsc_custom_fields_extra_info');
					$selected_value = apply_filters('wpsc_custom_dropdown_default_value', '', $field);
					?>
          <div  data-fieldtype="dropdown" data-visibility="<?php echo $this->visibility_conditions?>" class="<?php echo $this->col_class?> <?php echo $this->visibility? 'hidden':'visible'?> <?php echo $this->required? 'wpsc_required':''?> form-group wpsc_form_field <?php echo 'field_'.$field->term_id?>">
            <label class="wpsc_ct_field_label" for="<?php echo $this->slug;?>">
              <?php echo $label['custom_fields_'.$field->term_id];?> <?php echo $this->required? '<span style="color:red;">*</span>':'';?>
            </label>
            <?php if($extra_info['custom_fields_extra_info_'.$field->term_id]){?><p class="help-block" style="<?php echo $extra_info_css?>"><?php echo $extra_info['custom_fields_extra_info_'.$field->term_id];?></p><?php }?>
						<select id="<?php echo $this->slug;?>" class="form-control wpsc_drop_down" name="<?php echo $this->slug;?>" >
			        <option value=""></option>
							<?php
							foreach ( $this->options as $key => $value ) :
								$selected = $selected_value == $value ? 'selected="selected"' : '' ;
								echo '<option '.$selected. 'value="'.str_replace('"','&quot;',$value).'">'.$value.'</option>';
			        endforeach;
			        ?>
			      </select>
          </div>
          <?php
				}
				
				function print_checkbox($field){
					$wpsc_appearance_create_ticket = get_option('wpsc_create_ticket');
					$extra_info_css = 'color:'.$wpsc_appearance_create_ticket['wpsc_extra_info_text_color'].' !important;';
					$label = get_option('wpsc_custom_fields_localize');
					$extra_info = get_option('wpsc_custom_fields_extra_info');
					?>
          <div  data-fieldtype="checkbox" data-visibility="<?php echo $this->visibility_conditions?>" class="<?php echo $this->col_class?> <?php echo $this->visibility? 'hidden':'visible'?> <?php echo $this->required? 'wpsc_required':''?> form-group wpsc_form_field <?php echo 'field_'.$field->term_id?>">
            <label class="wpsc_ct_field_label" for="<?php echo $this->slug;?>">
              <?php echo $label['custom_fields_'.$field->term_id];?> <?php echo $this->required? '<span style="color:red;">*</span>':'';?>
            </label>
            <?php if($extra_info['custom_fields_extra_info_'.$field->term_id]){?><p class="help-block" style="<?php echo $extra_info_css?>"><?php echo $extra_info['custom_fields_extra_info_'.$field->term_id];?></p><?php }?>
						<?php
						foreach ( $this->options as $key => $value ) :
							?>
							<div class="row">
				        <div class="col-sm-12" style="margin-bottom:10px; display:flex;">
				          <div style="width:25px;"><input type="checkbox" class="wpsc_checkbox" name="<?php echo $this->slug?>[]" value="<?php echo str_replace('"','&quot;',$value)?>"></div>
				          <div style="padding-top:3px;"><?php echo $value?></div>
				        </div>
              </div>
							<?php
						endforeach;
						?>
          </div>
          <?php
				}
				
				function print_radio_btn($field){
					$wpsc_appearance_create_ticket = get_option('wpsc_create_ticket');
					$extra_info_css = 'color:'.$wpsc_appearance_create_ticket['wpsc_extra_info_text_color'].' !important;';
					$label = get_option('wpsc_custom_fields_localize');
					$extra_info = get_option('wpsc_custom_fields_extra_info');
					?>
          <div  data-fieldtype="radio" data-visibility="<?php echo $this->visibility_conditions?>" class="<?php echo $this->col_class?> <?php echo $this->visibility? 'hidden':'visible'?> <?php echo $this->required? 'wpsc_required':''?> form-group wpsc_form_field <?php echo 'field_'.$field->term_id?>">
            <label class="wpsc_ct_field_label" for="<?php echo $this->slug;?>">
              <?php echo $label['custom_fields_'.$field->term_id];?> <?php echo $this->required? '<span style="color:red;">*</span>':'';?>
            </label>
            <?php if($extra_info['custom_fields_extra_info_'.$field->term_id]){?><p class="help-block" style="<?php echo $extra_info_css?>"><?php echo $extra_info['custom_fields_extra_info_'.$field->term_id];?></p><?php }?>
						<?php
						foreach ( $this->options as $key => $value ) :
							?>
							<div class="row">
				        <div class="col-sm-12" style="margin-bottom:10px; display:flex;">
				          <div style="width:25px;"><input type="radio" class="wpsc_radio_btn" name="<?php echo $this->slug?>" value="<?php echo str_replace('"','&quot;',$value)?>"></div>
				          <div style="padding-top:3px;"><?php echo $value?></div>
				        </div>
              </div>
							<?php
						endforeach;
						?>
          </div>
          <?php
				}
				
				function print_textarea($field){
					$wpsc_appearance_create_ticket = get_option('wpsc_create_ticket');
					$extra_info_css = 'color:'.$wpsc_appearance_create_ticket['wpsc_extra_info_text_color'].' !important;';
					$label = get_option('wpsc_custom_fields_localize');
					$extra_info = get_option('wpsc_custom_fields_extra_info');
					$value = apply_filters('wpsc_custom_textarea_default_value', '', $field);
					?>
          <div  data-fieldtype="textarea" data-visibility="<?php echo $this->visibility_conditions?>" class="<?php echo $this->col_class?> <?php echo $this->visibility? 'hidden':'visible'?> <?php echo $this->required? 'wpsc_required':''?> form-group wpsc_form_field <?php echo 'field_'.$field->term_id?>">
            <label class="wpsc_ct_field_label" for="<?php echo $this->slug;?>">
              <?php echo $label['custom_fields_'.$field->term_id];?> <?php echo $this->required? '<span style="color:red;">*</span>':'';?>
            </label>
            <?php if($extra_info['custom_fields_extra_info_'.$field->term_id]){?><p class="help-block" style="<?php echo $extra_info_css?>"><?php echo $extra_info['custom_fields_extra_info_'.$field->term_id];?></p><?php }?>
            <textarea id="<?php echo $this->slug;?>" placeholder="<?php echo $this->placeholder;?>" class="wpsc_textarea" name="<?php echo $this->slug;?>" onkeypress="wpsc_text_limit(event,this,<?php echo $this->limit ?>);"><?php echo $value?></textarea>
          </div>
          <?php
				}
				
				function print_date($field){
					global $wpscfunction;
					$wpsc_appearance_create_ticket = get_option('wpsc_create_ticket');
					$extra_info_css = 'color:'.$wpsc_appearance_create_ticket['wpsc_extra_info_text_color'].' !important;';
					$label = get_option('wpsc_custom_fields_localize');
					$extra_info = get_option('wpsc_custom_fields_extra_info');
					$value = apply_filters('wpsc_custom_date_default_value', '', $field);
					$value = $wpscfunction->datetimeToCalenderFormat($value);
					
					?>
          <div  data-fieldtype="date" data-visibility="<?php echo $this->visibility_conditions?>" class="<?php echo $this->col_class?> <?php echo $this->visibility? 'hidden':'visible'?> <?php echo $this->required? 'wpsc_required':''?> form-group wpsc_form_field <?php echo 'field_'.$field->term_id?>">
            <label class="wpsc_ct_field_label" for="<?php echo $this->slug;?>">
              <?php echo $label['custom_fields_'.$field->term_id];?> <?php echo $this->required? '<span style="color:red;">*</span>':'';?>
            </label>
            <?php if($extra_info['custom_fields_extra_info_'.$field->term_id]){?><p class="help-block" style="<?php echo $extra_info_css?>"><?php echo $extra_info['custom_fields_extra_info_'.$field->term_id];?></p><?php }?>
            <input type="text" id="<?php echo $this->slug;?>" class="form-control wpsc_date" name="<?php echo $this->slug;?>" autocomplete="off" value="<?php echo $value?>" placeholder="<?php echo $this->placeholder;?>">
          </div>
          <?php
				}
				
				function print_url($field){
					$wpsc_appearance_create_ticket = get_option('wpsc_create_ticket');
					$extra_info_css = 'color:'.$wpsc_appearance_create_ticket['wpsc_extra_info_text_color'].' !important;';
					$label = get_option('wpsc_custom_fields_localize');
					$extra_info = get_option('wpsc_custom_fields_extra_info');
					$value = apply_filters('wpsc_custom_url_default_value', '', $field);
					
					?>
          <div  data-fieldtype="url" data-visibility="<?php echo $this->visibility_conditions?>" class="<?php echo $this->col_class?> <?php echo $this->visibility? 'hidden':'visible'?> <?php echo $this->required? 'wpsc_required':''?> form-group wpsc_form_field <?php echo 'field_'.$field->term_id?>">
            <label class="wpsc_ct_field_label" for="<?php echo $this->slug;?>">
              <?php echo $label['custom_fields_'.$field->term_id];?> <?php echo $this->required? '<span style="color:red;">*</span>':'';?>
            </label>
            <?php if($extra_info['custom_fields_extra_info_'.$field->term_id]){?><p class="help-block" style="<?php echo $extra_info_css?>"><?php echo $extra_info['custom_fields_extra_info_'.$field->term_id];?></p><?php }?>
            <input type="text" id="<?php echo $this->slug;?>" class="form-control wpsc_url" placeholder="<?php echo $this->placeholder;?>" name="<?php echo $this->slug;?>" autocomplete="off" value="<?php echo $value?>" onkeypress="wpsc_text_limit(event,this,<?php echo $this->limit ?>);">
          </div>
          <?php
				}
				
				function print_email($field){
					$wpsc_appearance_create_ticket = get_option('wpsc_create_ticket');
					$extra_info_css = 'color:'.$wpsc_appearance_create_ticket['wpsc_extra_info_text_color'].' !important;';
					$label = get_option('wpsc_custom_fields_localize');
					$extra_info = get_option('wpsc_custom_fields_extra_info');
					?>
          <div  data-fieldtype="email" data-visibility="<?php echo $this->visibility_conditions?>" class="<?php echo $this->col_class?> <?php echo $this->visibility? 'hidden':'visible'?> <?php echo $this->required? 'wpsc_required':''?> form-group wpsc_form_field <?php echo 'field_'.$field->term_id?>">
            <label class="wpsc_ct_field_label" for="<?php echo $this->slug;?>">
              <?php echo $label['custom_fields_'.$field->term_id];?> <?php echo $this->required? '<span style="color:red;">*</span>':'';?>
            </label>
            <?php if($extra_info['custom_fields_extra_info_'.$field->term_id]){?><p class="help-block" style="<?php echo $extra_info_css?>"><?php echo $extra_info['custom_fields_extra_info_'.$field->term_id];?></p><?php }?>
            <input type="text" id="<?php echo $this->slug;?>" class="form-control wpsc_email" placeholder="<?php echo $this->placeholder;?>" name="<?php echo $this->slug;?>" autocomplete="off" value=""  onkeypress="wpsc_text_limit(event,this,<?php echo $this->limit ?>);">
          </div>
          <?php
				}
				
				function print_numberonly($field){
					$wpsc_appearance_create_ticket = get_option('wpsc_create_ticket');
					$extra_info_css = 'color:'.$wpsc_appearance_create_ticket['wpsc_extra_info_text_color'].' !important;';
					$label = get_option('wpsc_custom_fields_localize');
					$extra_info = get_option('wpsc_custom_fields_extra_info');
					$value = apply_filters('wpsc_custom_number_default_value', '', $field);
					?>
          <div  data-fieldtype="number" data-visibility="<?php echo $this->visibility_conditions?>" class="<?php echo $this->col_class?> <?php echo $this->visibility? 'hidden':'visible'?> <?php echo $this->required? 'wpsc_required':''?> form-group wpsc_form_field <?php echo 'field_'.$field->term_id?>">
            <label class="wpsc_ct_field_label" for="<?php echo $this->slug;?>">
              <?php echo $label['custom_fields_'.$field->term_id];?> <?php echo $this->required? '<span style="color:red;">*</span>':'';?>
            </label>
            <?php if($extra_info['custom_fields_extra_info_'.$field->term_id]){?><p class="help-block" style="<?php echo $extra_info_css?>"><?php echo $extra_info['custom_fields_extra_info_'.$field->term_id];?></p><?php }?>
            <input type="number" id="<?php echo $this->slug;?>" class="form-control wpsc_numberonly" placeholder="<?php echo $this->placeholder;?>" name="<?php echo $this->slug;?>" autocomplete="off" value="<?php echo $value?>" onkeypress="wpsc_text_limit(event,this,<?php echo $this->limit ?>);" >
          </div>
          <?php
				}
				
				function print_customer_name($field){
					global $current_user;
					$wpsc_appearance_create_ticket = get_option('wpsc_create_ticket');
					$extra_info_css = 'color:'.$wpsc_appearance_create_ticket['wpsc_extra_info_text_color'].' !important;';							
					$label = get_option('wpsc_custom_fields_localize');
					$extra_info = get_option('wpsc_custom_fields_extra_info');
					$readonly = '';
					if(!$current_user->has_cap('wpsc_agent') && is_user_logged_in()){
						$readonly = 'readonly';
					}
					$readonly = apply_filters('wpsc_customer_name_readonly',$readonly);
          ?>
          <div  data-fieldtype="text" data-visibility="<?php echo $this->visibility_conditions?>" class="<?php echo $this->col_class?> <?php echo $this->visibility? 'hidden':'visible'?> <?php echo $this->required? 'wpsc_required':''?> form-group wpsc_form_field <?php echo 'field_'.$field->term_id?>">
            <label class="wpsc_ct_field_label" for="<?php echo $this->slug;?>">
              <?php echo $label['custom_fields_'.$field->term_id];?> <?php echo $this->required? '<span style="color:red;">*</span>':'';?>
            </label>
            <?php if($extra_info['custom_fields_extra_info_'.$field->term_id]){?><p class="help-block" style="<?php echo $extra_info_css?>"><?php echo $extra_info['custom_fields_extra_info_'.$field->term_id];?></p><?php }?>
						<input type="text" id="<?php echo $this->slug;?>" class="form-control wpsc_customer_name" placeholder="<?php echo $this->placeholder; ?>" name="<?php echo $this->slug;?>" autocomplete="off" value="<?php echo is_user_logged_in() ? $current_user->display_name :'' ?>" <?php echo $readonly ?>  onkeypress="wpsc_text_limit(event,this,<?php echo $this->limit ?>);">
					</div>	
					<?php
				}
				
				function print_customer_email($field){
					global $current_user;
					$wpsc_appearance_create_ticket = get_option('wpsc_create_ticket');
					$extra_info_css = 'color:'.$wpsc_appearance_create_ticket['wpsc_extra_info_text_color'].' !important;';
					$label = get_option('wpsc_custom_fields_localize');
					$extra_info = get_option('wpsc_custom_fields_extra_info');
					$readonly = '';
					if(!$current_user->has_cap('wpsc_agent') && is_user_logged_in()){
						$readonly = 'readonly';
					}
					$readonly = apply_filters('wpsc_customer_email_readonly', $readonly);
					?>
          <div  data-fieldtype="email" data-visibility="<?php echo $this->visibility_conditions?>" class="<?php echo $this->col_class?> <?php echo $this->visibility? 'hidden':'visible'?> <?php echo $this->required? 'wpsc_required':''?> form-group wpsc_form_field <?php echo 'field_'.$field->term_id?>">
            <label class="wpsc_ct_field_label" for="<?php echo $this->slug;?>">
              <?php echo $label['custom_fields_'.$field->term_id];?> <?php echo $this->required? '<span style="color:red;">*</span>':'';?>
            </label>
            <?php if($extra_info['custom_fields_extra_info_'.$field->term_id]){?><p class="help-block" style="<?php echo $extra_info_css?>" ><?php echo $extra_info['custom_fields_extra_info_'.$field->term_id];?></p><?php }?>
						<input type="text" id="<?php echo $this->slug;?>" class="form-control wpsc_email" placeholder= "<?php echo $this->placeholder;?>" name="<?php echo $this->slug;?>" autocomplete="off" value="<?php echo is_user_logged_in() ? $current_user->user_email :'' ?>" 
						<?php echo $readonly ?> onkeypress="wpsc_text_limit(event,this,<?php echo $this->limit ?>);">
					</div>
					<?php
				}
        
				function print_ticket_subject($field){
					$wpsc_appearance_create_ticket = get_option('wpsc_create_ticket');
					$extra_info_css = 'color:'.$wpsc_appearance_create_ticket['wpsc_extra_info_text_color'].' !important;';
					$label = get_option('wpsc_custom_fields_localize');
					$extra_info = get_option('wpsc_custom_fields_extra_info');
          ?>
          <div  data-fieldtype="text" data-visibility="<?php echo $this->visibility_conditions?>" class="<?php echo $this->col_class?> <?php echo $this->visibility? 'hidden':'visible'?> <?php echo $this->required? 'wpsc_required':''?> form-group wpsc_form_field <?php echo 'field_'.$field->term_id?>">
            <label class="wpsc_ct_field_label" for="<?php echo $this->slug;?>">
              <?php echo $label['custom_fields_'.$field->term_id];?> <?php echo $this->required? '<span style="color:red;">*</span>':'';?>
            </label>
            <?php if($extra_info['custom_fields_extra_info_'.$field->term_id]){?><p class="help-block" style="<?php echo $extra_info_css?>" ><?php echo $extra_info['custom_fields_extra_info_'.$field->term_id];?></p><?php }?>
            <input type="text" id="<?php echo $this->slug;?>" class="form-control wpsc_subject" placeholder="<?php echo $this->placeholder; ?>" name="<?php echo $this->slug;?>" autocomplete="off" value=""  onkeypress="wpsc_text_limit(event,this,<?php echo $this->limit ?>);">
          </div>
					<?php
				}
			
				function print_file_attachment($field){
					$wpsc_appearance_create_ticket = get_option('wpsc_create_ticket');
					$extra_info_css = 'color:'.$wpsc_appearance_create_ticket['wpsc_extra_info_text_color'].' !important;';
					$label = get_option('wpsc_custom_fields_localize');
					$extra_info = get_option('wpsc_custom_fields_extra_info');
          ?>
          <div data-fieldtype="file_attachment" data-visibility="<?php echo $this->visibility_conditions?>" class="<?php echo $this->col_class?> <?php echo $this->visibility? 'hidden':'visible'?> <?php echo $this->required? 'wpsc_required':''?> form-group wpsc_form_field <?php echo 'field_'.$field->term_id?>">
            <label class="wpsc_ct_field_label" for="<?php echo $this->slug;?>">
              <?php echo $label['custom_fields_'.$field->term_id];?> <?php echo $this->required? '<span style="color:red;">*</span>':'';?>
            </label>
            <?php if($extra_info['custom_fields_extra_info_'.$field->term_id]){?><p class="help-block" style="<?php echo $extra_info_css?>" ><?php echo $extra_info['custom_fields_extra_info_'.$field->term_id];?></p><?php }?>
						<div class="row attachment" style="margin-bottom:20px;">
							<div id="<?php echo 'attach_'.$field->term_id?>" class="row attachment_container"></div>
	  					<div class="row attachment_link">
	  						<span onclick="wpsc_attachment_upload('<?php echo 'attach_'.$field->term_id?>','<?php echo $this->slug?>');"><?php _e('Attach file','supportcandy')?></span>
	  					</div>
	  				</div>
          </div>
          <?php
        }
				
				function print_date_time($field){
					$wpsc_appearance_create_ticket = get_option('wpsc_create_ticket');
					$extra_info_css = 'color:'.$wpsc_appearance_create_ticket['wpsc_extra_info_text_color'].' !important;';
					$label = get_option('wpsc_custom_fields_localize');
					$extra_info = get_option('wpsc_custom_fields_extra_info');
					?>
          <div  data-fieldtype="date" data-visibility="<?php echo $this->visibility_conditions?>" class="<?php echo $this->col_class?> <?php echo $this->visibility? 'hidden':'visible'?> <?php echo $this->required? 'wpsc_required':''?> form-group wpsc_form_field <?php echo 'field_'.$field->term_id?>">
            <label class="wpsc_ct_field_label" for="<?php echo $this->slug;?>">
              <?php echo $label['custom_fields_'.$field->term_id];?> <?php echo $this->required? '<span style="color:red;">*</span>':'';?>
            </label>
            <?php if($extra_info['custom_fields_extra_info_'.$field->term_id]){?><p class="help-block" style="<?php echo $extra_info_css?>"><?php echo $extra_info['custom_fields_extra_info_'.$field->term_id];?></p><?php }?>
            <input type="text" id="<?php echo $this->slug;?>" class="form-control wpsc_datetime" name="<?php echo $this->slug;?>" autocomplete="off" value="" placeholder="<?php echo $this->placeholder;?>">
          </div>
          <?php
				}
				
				function print_ticket_category($field){
					$wpsc_appearance_create_ticket = get_option('wpsc_create_ticket');
					$extra_info_css = 'color:'.$wpsc_appearance_create_ticket['wpsc_extra_info_text_color'].' !important;';
					$label = get_option('wpsc_custom_fields_localize');
					$extra_info = get_option('wpsc_custom_fields_extra_info');
					?>
          <div  data-fieldtype="dropdown" data-visibility="<?php echo $this->visibility_conditions?>" class="<?php echo $this->col_class?> <?php echo $this->visibility? 'hidden':'visible'?> <?php echo $this->required? 'wpsc_required':''?> form-group wpsc_form_field <?php echo 'field_'.$field->term_id?>">
            <label class="wpsc_ct_field_label" for="<?php echo $this->slug;?>">
              <?php echo $label['custom_fields_'.$field->term_id];?> <?php echo $this->required? '<span style="color:red;">*</span>':'';?>
            </label>
            <?php if($extra_info['custom_fields_extra_info_'.$field->term_id]){?><p class="help-block" style="<?php echo $extra_info_css?>" ><?php echo $extra_info['custom_fields_extra_info_'.$field->term_id];?></p><?php }?>
						<select id="<?php echo $this->slug;?>" class="form-control wpsc_category" name="<?php echo $this->slug;?>">
			        <option value=""></option>
							<?php
							$categories = get_terms([
							  'taxonomy'   => 'wpsc_categories',
							  'hide_empty' => false,
								'orderby'    => 'meta_value_num',
							  'order'    	 => 'ASC',
								'meta_query' => array('order_clause' => array('key' => 'wpsc_category_load_order')),
							]);
							
							$wpsc_default_ticket_category = '';
							$wpsc_default_ticket_category = apply_filters('wpsc_default_ticket_category', $wpsc_default_ticket_category);
							
							foreach ( $categories as $category ) :
								$wpsc_custom_category_localize = get_option('wpsc_custom_category_localize');
								$category_id   = $category->term_id;
								$selected      = $wpsc_default_ticket_category == $category->term_id ? 'selected="selected"' : '';
			          echo '<option '.$selected.' value="'.$category->term_id.'">'.$wpsc_custom_category_localize['custom_category_'.$category_id].'</option>';
			        endforeach;
			        ?>
			      </select>
          </div>
          <?php
				}
				
				function print_ticket_priority($field){
					$wpsc_appearance_create_ticket = get_option('wpsc_create_ticket');
					$extra_info_css = 'color:'.$wpsc_appearance_create_ticket['wpsc_extra_info_text_color'].' !important;';
					$label = get_option('wpsc_custom_fields_localize');
					$extra_info = get_option('wpsc_custom_fields_extra_info');
					?>
          <div  data-fieldtype="dropdown" data-visibility="<?php echo $this->visibility_conditions?>" class="<?php echo $this->col_class?> <?php echo $this->visibility? 'hidden':'visible'?> <?php echo $this->required? 'wpsc_required':''?> form-group wpsc_form_field <?php echo 'field_'.$field->term_id?>">
            <label class="wpsc_ct_field_label" for="<?php echo $this->slug;?>">
              <?php echo $label['custom_fields_'.$field->term_id];?> <?php echo $this->required? '<span style="color:red;">*</span>':'';?>
            </label>
            <?php if($extra_info['custom_fields_extra_info_'.$field->term_id]){?><p class="help-block" style="<?php echo $extra_info_css?>" ><?php echo $extra_info['custom_fields_extra_info_'.$field->term_id];?></p><?php }?>
						<select id="<?php echo $this->slug;?>" class="form-control wpsc_priority" name="<?php echo $this->slug;?>">
							<option value=""></option>
							<?php
			 				$priorities = get_terms([
			 				  'taxonomy'   => 'wpsc_priorities',
			 				  'hide_empty' => false,
			 					'orderby'    => 'meta_value_num',
			 				  'order'    	 => 'ASC',
			 					'meta_query' => array('order_clause' => array('key' => 'wpsc_priority_load_order')),
			 				]);
			 				foreach ( $priorities as $priority ) :
								$wpsc_custom_priority_localize = get_option('wpsc_custom_priority_localize');
			           echo '<option value="'.$priority->term_id.'">'.$wpsc_custom_priority_localize['custom_priority_'.$priority->term_id].'</option>';
		          endforeach;
			         ?>
			      </select>
          </div>
          <?php
				}
        
				function print_ticket_description($field){
					global $current_user;
					$wpsc_appearance_create_ticket = get_option('wpsc_create_ticket');
					$wpsc_guest_can_upload_files = get_option('wpsc_guest_can_upload_files');
					$extra_info_css = 'color:'.$wpsc_appearance_create_ticket['wpsc_extra_info_text_color'].' !important;';
					$wpsc_allow_attachment = get_option('wpsc_allow_attachment');
					$label = get_option('wpsc_custom_fields_localize');
					$extra_info = get_option('wpsc_custom_fields_extra_info');
          ?>
          <div  data-fieldtype="tinymce" data-visibility="<?php echo $this->visibility_conditions?>" class="<?php echo $this->col_class?> <?php echo $this->visibility? 'hidden':'visible'?> <?php echo $this->required? 'wpsc_required':''?> form-group wpsc_form_field <?php echo 'field_'.$field->term_id?>">
            <label class="wpsc_ct_field_label">
              <?php echo $label['custom_fields_'.$field->term_id];?> <?php echo $this->required? '<span style="color:red;">*</span>':'';?>
            </label>
            <?php if($extra_info['custom_fields_extra_info_'.$field->term_id]){?><p class="help-block" style="<?php echo $extra_info_css?>"><?php echo $extra_info['custom_fields_extra_info_'.$field->term_id];?></p><?php }?>
            <textarea id="<?php echo $this->slug;?>" class="wpsc_textarea" name="<?php echo $this->slug;?>" placeholder ="<?php echo $this->placeholder; ?>" ></textarea>
						<?php 
						if(is_user_logged_in() || $wpsc_guest_can_upload_files ){?>
							<div class="row attachment" style="margin-bottom:20px;">
		  					<div class="row attachment_link">
									<?php if(in_array('create',$wpsc_allow_attachment)) : ?>
										<span onclick="wpsc_attachment_upload('<?php echo 'attach_'.$field->term_id?>','desc_attachment');"><?php _e('Attach file','supportcandy')?></span>
									<?php endif;?>
									<?php if ($current_user->has_cap('wpsc_agent')):?>
									<span id="wpsc_insert_macros" onclick="wpsc_get_templates()" ><?php _e('Insert Macros','supportcandy')?></span>
									<?php endif;?>
		  						<?php do_action('wpsc_add_addon_tab_after_macro');?>
		  					</div>
		  					<div id="<?php echo 'attach_'.$field->term_id?>" class="row attachment_container"></div>
		  				</div>
							<?php 
						}?>
          </div>
					<?php 
					 $wpsc_allow_tinymce_in_guest_ticket = get_option('wpsc_allow_tinymce_in_guest_ticket'); 
					 if($current_user->has_cap('wpsc_agent') || ($wpsc_allow_tinymce_in_guest_ticket && !($current_user->has_cap('wpsc_agent'))) || is_user_logged_in()){
						 $wpsc_tinymce_toolbar = get_option('wpsc_tinymce_toolbar');
						 $toolbar_active = get_option('wpsc_tinymce_toolbar_active');
						 $tinymce_toolbox = array();
						 foreach ($toolbar_active as $key => $value) {
						 		$tinymce_toolbox[] = $wpsc_tinymce_toolbar[$value]['value'];
								if($value == 'blockquote' || $value == 'align' || $value == 'numbered_list' || $value == 'right_to_left'){
									$tinymce_toolbox[] = ' | ';
								}
						 }
						 ?>
						 <script>
						 		tinymce.remove();
								
		          	tinymce.init({ 
		          	  selector:'#<?php echo $this->slug;?>',
		          	  body_id: '<?php echo $this->slug;?>',
		          	  menubar: false,
									statusbar: false,
									autoresize_min_height: 150,
							    wp_autoresize_on: true,
							    plugins: [
										'wpautoresize lists link image directionality'
								  ],
		          	  toolbar: '<?php echo implode(' ', $tinymce_toolbox) ?> | wpsc_templates ',
									file_picker_types: 'image',
									file_picker_callback: function(cb, value, meta) {
										var input = document.createElement('input');
								    input.setAttribute('type', 'file');
								    input.setAttribute('accept', 'image/*');

										input.onchange = function() {
								      var file = this.files[0];
											var form_data = new FormData();
											form_data.append('file',file);
											form_data.append('file_name',file.name);
											form_data.append('action','wpsc_tickets');
											form_data.append('setting_action','rb_upload_file');
										
											jQuery.ajax({
												type : 'post',
												url : wpsc_admin.ajax_url,
								        cache: false,
								        contentType: false,
								        processData: false,
								        data: form_data,
								        success: function(response_data){
													var responce = JSON.parse(response_data);
													var reader   = new FileReader();
													reader.onload = function () {
														var id        = 'blobid' + (new Date()).getTime();
														var blobCache = tinymce.activeEditor.editorUpload.blobCache;
														var base64    = reader.result.split(',')[1];
														var blobInfo  = blobCache.create(id, file, base64);
														blobCache.add(blobInfo);
														if (responce) {
															cb(responce, { title: 'attach' });
														} else {
															alert("<?php _e('Attached file type not allowed!','supportcandy')?>");
														}	
														
													};
													reader.readAsDataURL(file);
												}
											});
										};
							    
										input.click();
									},
									branding: false,
									autoresize_bottom_margin: 20,
	          	  	browser_spellcheck : true,
	          	  	relative_urls : false,
		          	  remove_script_host : false,
		          	  convert_urls : true
		          	});
								
							</script>
						<?php
					}
				}
        
			}
    
endif;