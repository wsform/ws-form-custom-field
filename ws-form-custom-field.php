<?php

	/**
	 * Plugin Name:       WS Form PRO - My Custom Field
	 * Plugin URI:        https://my-site.com/
	 * Description:       My custom field for WS Form PRO
	 * Version:           1.0.0
	 * License:           GPLv3 or later
	 * License URI:       https://www.gnu.org/licenses/gpl-3.0.html
	 * Text Domain:       ws-form-custom-field
	 */

	Class WS_Form_My_Custom_Field {

		// Constants
		const VERSION = '1.0.0';

		// Class construct
		public function __construct() {

			// Config field types filter hook
			add_filter( 'wsf_config_field_types', array($this, 'config_field_types'), 10, 2 );

			// Config meta keys filter hook (Optional)
			add_filter( 'wsf_config_meta_keys', array($this, 'config_meta_keys'), 10, 2 );

			// Config enqueue action hook (Options)
			add_action( 'wsf_enqueue', array($this, 'enqueue'), 10, 0 );

			// Add any other filter and action hooks here

			// WS Form hooks: https://wsform.com/knowledgebase_category/hooks/
		}

		// Define your custom field type
		public function config_field_types($field_types) {

			// The example below adds a custom field containing built-in meta keys that can apply to most fields
			$field_types['my_custom_field_group'] = array(

				// Group label
				'label' => __('My Custom Field Group', 'ws-form-custom-field'),

				// Group field types
				'types' => array(

					// Type ID
					'my_custom_field' => array (

						// Label shown in the side bar
						'label'                       => __('My Custom Field', 'ws-form-custom-field'),

						// Label added to the field by default
						'label_default'               => __('My Custom Field Label', 'ws-form-custom-field'),

						// If set to true, the "Show Label" setting will be disabled by default 
						'label_disabled'              => false,

						// SVG icon (See get_icon_svg method below)
						'icon'					      => self::get_icon_svg(),

						// Is WS Form PRO required? 'basic' = Works with LITE, 'pro' = Requires WS Form PRO
						'pro_required'			      => !WS_Form_Common::is_edition('basic'),

						// Does this field submit data that should be saved?
						'submit_save'			      => false,

						// Can this field be edited in the submissions page?
						'submit_edit'			      => false,

						// Keywords for field search (lowercase)
						'keyword'                     => __('custom field', 'ws-form-custom-field'),

						// If set to true, multiple instances of this field can be added to a form
						'multiple'                    => true,

						// If set to true, this field will appear in mappings
						'mappable'                    => false,

						// Custom default invalid feedback text (Optional)
						'invalid_feedback'            => __('Please complete my custom field.', 'ws-form-custom-field'),

						// Does completing this field contribute towards the form progress
						'progress'				      => false,

						// Conditional logic configuration
						'conditional'			      => array(

							'exclude_condition' => true,
							'exclude_then'      => true,
							'exclude_else'      => true
						),

						// Is this a static field?
						'static'                      => true,

						// If true, this field will not be contained within a WS Form field wrapper
						'mask_wrappers_drop'          => false,

						// Define which meta keys can be used for the #attributes variable in mask_field_label (Optional)
						'mask_field_label_attributes' => array(

							'class',
						),

						// The mask for the label (Remove this for no label)
						'mask_field_label'            => '<label id="#label_id" for="#id"#attributes>#label</label>',

						// Define which meta keys can be used for the #attributes variable in mask_field (Optional)
						'mask_field_attributes'       => array(

							'class',
							'custom_attributes',  // Adds custom attributes from the custom_attributes meta key

							// Custom meta keys defined in the config_meta_keys function below
							'my_custom_field_setting_text',
							'my_custom_field_setting_select',
							'my_custom_field_setting_checkbox',
						),

						// The mask for the field
						'mask_field'                  => '#pre_label#pre_help<div id="#id"#attributes>My Custom Field HTML</div>#post_label#post_help',

						// Settings meta keys
						'fieldsets' => array(

							// Tab ID
							'basic' => array(

								// Tab label
								'label'     => __('Basic', 'ws-form-custom-field'),

								// Meta keys shown at top of tab
								'meta_keys' => array(

									'label_render', // Remove this for no label
									'hidden'
								),

								// Tab fieldsets
								'fieldsets' => array(

									array(

										// Fieldset label
										'label'     => __('Example Fieldset', 'ws-form-custom-field'),

										// Fieldset meta keys
										'meta_keys' => array(

											// Custom meta keys defined in the config_meta_keys function below
											'my_custom_field_setting_text',
											'my_custom_field_setting_select',
											'my_custom_field_setting_checkbox',
										)
									)
								)
							),

							// Tab ID
							'advanced' => array(

								// Tab label
								'label'     => __('Advanced', 'ws-form-custom-field'),

								// Tab fieldsets
								'fieldsets' => array(

									array(

										// Fieldset label
										'label'     => __('Style', 'ws-form-custom-field'),

										// Fieldset meta keys
										'meta_keys' => array(

											'label_position',     // Remove this for no label
											'label_column_width', // Remove this for no label
											'help_position'
										)
									),

									array(

										// Fieldset label
										'label'     => __('Classes', 'ws-form-custom-field'),

										// Fieldset meta keys
										'meta_keys' => array(

											'class_field_wrapper',
											'class_field'
										)
									),

									array(

										// Fieldset label
										'label'     => __('Restrictions', 'ws-form-custom-field'),

										// Fieldset meta keys
										'meta_keys' => array(

											'field_user_status',
											'field_user_roles',
											'field_user_capabilities'
										)
									),

									array(

										// Fieldset label
										'label'     => __('Validation', 'ws-form-custom-field'),

										// Fieldset meta keys
										'meta_keys' => array(

											'invalid_feedback_render',
											'validate_inline',
											'invalid_feedback'
										)
									),

									array(

										// Fieldset label
										'label'     => __('Custom Attributes', 'ws-form-custom-field'),

										// Fieldset meta keys
										'meta_keys' => array(

											'custom_attributes'
										)
									),

									array(

										// Fieldset label
										'label'     => __('Breakpoints', 'ws-form-custom-field'),

										// Fieldset meta keys
										'meta_keys' => array(

											'breakpoint_sizes'
										),

										// Fieldset custom class
										'class'		=> array('wsf-fieldset-panel')
									)
								)
							)
						)
					)
				)
			);

			return $field_types;
		}

		// Define your custom field settings (Optional)
		public function config_meta_keys($meta_keys = array(), $form_id = 0) {

			// These meta keys each represent a setting for the field. They typically add an attribute to the #attributes mask variable

			// Text
			$meta_keys['my_custom_field_setting_text']	= array(

				// Label
				'label'                   => __('Example Text Setting', 'ws-form-custom-field'),

				// Type
				'type'                    => 'text',

				// Attribute mask
				'mask'                    => 'data-my-text="#value"',

				// If set to true, the attribute will be excluded if empty
				'mask_disregard_on_empty' => true,

				// Default value
				'default'                 => '',

				// Placeholder
				'placeholder'             => __('Example placeholder', 'ws-form-custom-field'),

				// Help text shown under setting
				'help'                    => __('Example help text.', 'ws-form-custom-field'),
			);

			// Select
			$meta_keys['my_custom_field_setting_select']	= array(

				// Label
				'label'                   => __('Example Select Setting', 'ws-form-custom-field'),

				// Type
				'type'                    => 'select',

				// Blank option
				'options_blank'					=>	__('Select...', 'ws-form-custom-field'),

				// Options
				'options'                 => array(

					array('value' => 'option_1', 'text' => __('Option 1', 'ws-form-custom-field')),
					array('value' => 'option_2', 'text' => __('Option 2', 'ws-form-custom-field')),
					array('value' => 'option_3', 'text' => __('Option 3', 'ws-form-custom-field')),
				),

				// Attribute mask
				'mask'                    => 'data-my-select="#value"',

				// If set to true, the attribute will be excluded if empty
				'mask_disregard_on_empty' => true,

				// Default value
				'default'                 => '',

				// Placeholder
				'placeholder'             => __('Example placeholder', 'ws-form-custom-field'),

				// Help text shown under setting
				'help'                    => __('Example help text.', 'ws-form-custom-field'),
			);

			// Checkbox
			$meta_keys['my_custom_field_setting_checkbox']	= array(

				// Label
				'label'                   => __('Example Checkbox Setting', 'ws-form-custom-field'),

				// Type
				'type'                    => 'checkbox',

				// Attribute mask
				'mask'                    => 'data-my-checkbox="#value"',

				// If set to true, the attribute will be excluded if empty
				'mask_disregard_on_empty' => true,

				// Default value
				'default'                 => '',

				// Placeholder
				'placeholder'             => __('Example placeholder', 'ws-form-custom-field'),

				// Help text shown under setting
				'help'                    => __('Example help text.', 'ws-form-custom-field'),
			);

			return $meta_keys;
		}

		// Enqueue scripts / styles
		public function enqueue() {

			// Enqueue script (https://developer.wordpress.org/reference/functions/wp_enqueue_script/)
			wp_enqueue_script( 'ws-form-custom-field-script', sprintf( '%s/js/ws-form-custom-field.js', plugin_dir_url( __FILE__ ) ), array(), self::VERSION );

			// Enqueue style (https://developer.wordpress.org/reference/functions/wp_enqueue_style/)
			wp_enqueue_style( 'ws-form-custom-field-style', sprintf( '%s/css/ws-form-custom-field.css', plugin_dir_url( __FILE__ ) ), array(), self::VERSION );
		}

		// SVG icon for the field
		// Recommend size: 20 x 20
		// Showm in the toolbox sidebar and on the field itself in the layout editor 
		public function get_icon_svg() {

			return '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" xml:space="preserve"><path d="M0 0v20h20V0H0zm8.832 13.693-.838.003-1.117-5a1.225 1.225 0 0 1-.026-.116L6.617 7.27h-.014l-.108.58-.17.843-1.156 5h-.84L2.57 6.313h.708l1.006 4.299.467 2.245h.047c.089-.648.233-1.401.432-2.259l1.006-4.287h.747l1.013 4.3c.08.322.221 1.07.425 2.245h.047c.015-.15.088-.526.22-1.13.13-.603.551-2.409 1.261-5.416h.701l-1.818 7.383zm5.682-.389c-.357.352-.874.527-1.55.528a3.268 3.268 0 0 1-.968-.13 2.484 2.484 0 0 1-.648-.285l.31-.538c.21.128.149.094.421.187.3.104.614.155.931.153.418 0 .745-.125.984-.374s.358-.583.357-1c0-.327-.085-.602-.257-.827-.171-.225-.498-.48-.98-.765-.552-.318-.929-.572-1.13-.762a2.142 2.142 0 0 1-.467-.64 1.956 1.956 0 0 1-.168-.844 1.682 1.682 0 0 1 .589-1.317c.39-.348.889-.522 1.496-.522.65 0 1.14.168 1.578.45l-.311.537a2.441 2.441 0 0 0-1.297-.374c-.417 0-.75.112-.998.337-.25.225-.374.52-.374.888 0 .327.084.6.253.82.169.22.527.486 1.075.8.536.322.904.58 1.103.772.19.18.341.398.443.64.101.257.15.531.145.807 0 .621-.179 1.108-.537 1.46zm2.339.593a.67.67 0 1 1 0-1.34.67.67 0 0 1 0 1.34zm0-3.239a.67.67 0 1 1 0-1.341.67.67 0 0 1 0 1.341zm0-3.281a.67.67 0 1 1 0-1.341.67.67 0 0 1 0 1.34z"/></svg>';
		}
	}

	new WS_Form_My_Custom_Field();