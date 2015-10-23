<?php
/**
 * Default options.
 *
 * Populates default_options and default_fields.
 *
 * @since 1.8.0
 * @package Strong_Testimonials
 */

/**
 * Settings
 * 
 * @return array
 */
function wpmtst_get_default_options() {
	$default_options = array(
		'per_page'          => '5',
		'load_page_style'   => 1,
		'load_widget_style' => 1,
		'load_form_style'   => 1,
		'load_rtl_style'    => 0,
		'reorder'           => false,
		'shortcode'         => 'strong',
	);

	$default_options['default_template'] = '[wpmtst-text field="client_name" class="name"]' . PHP_EOL . '[wpmtst-link url="company_website" text="company_name" new_tab class="company"]';

	$default_options['client_section'] = $default_options['default_template'];

	return $default_options;
}

/**
 * Cycle shortcode
 * 
 * @return array
 */
function wpmtst_get_default_cycle() {
	$default_cycle = array(
		'category'   => 'all',
		'order'      => 'recent',
		'all'        => 0,
		'limit'      => 3,
		'title'      => 1,
		'content'    => 'entire',
		'char_limit' => 200,
		'images'     => 1,
		'client'     => 0,
		'more'       => 0,
		'more_page'  => '',
		'effect'     => 'fade',
		'speed'      => 1.5,
		'timeout'    => 8,
		'pause'      => 1,
	);
	return $default_cycle;
}

/**
 * Fields
 * 
 * @return array
 */
function wpmtst_get_default_fields() {
	// common field properties
	$field_base = array(
		'name'                    => '',
		'label'                   => '',
		'input_type'              => '',
		'required'                => 0,
		'error'                   => __( 'This field is required.', 'strong-testimonials' ),
		'placeholder'             => '',
		'show_placeholder_option' => 1,
		'before'                  => '',
		'after'                   => '',
		'admin_table'             => 0,
		'admin_table_option'      => 1,
		'show_admin_table_option' => 1,
	);

	// Assemble field type groups.
	$field_types = array();

	// Post
	$field_types['post'] = array(
		'post_title'     => array(
			'input_type'         => 'text',
			'option_label'       => __( 'Testimonial Title', 'strong-testimonials' ),
			'map'                => 'post_title',
			'admin_table'        => 1,
			'admin_table_option' => 0,
		),
		'post_content'   => array(
			'input_type'   => 'textarea',
			'option_label' => __( 'Testimonial Content', 'strong-testimonials' ),
			'map'          => 'post_content',
			'required'     => 1,
			'core'         => 0,
			'admin_table'  => 0,
		),
		'featured_image' => array(
			'input_type'              => 'file',
			'option_label'            => __( 'Featured Image', 'strong-testimonials' ),
			'map'                     => 'featured_image',
			'show_placeholder_option' => 0,
			'admin_table'             => 0,
		),
	);
	foreach ( $field_types['post'] as $key => $array ) {
		$field_types['post'][ $key ] = array_merge( $field_base, $array );
	}

	// Custom
	$field_types['custom'] = array(
		'text'  => array(
			'input_type'   => 'text',
			'option_label' => __( 'text', 'strong-testimonials' ),
		),
		'email' => array(
			'input_type'   => 'text',
			'option_label' => __( 'email (text)', 'strong-testimonials' ),
		),
		'url'   => array(
			'input_type'   => 'text',
			'option_label' => __( 'URL (text)', 'strong-testimonials' ),
		)
	);
	foreach ( $field_types['custom'] as $key => $array ) {
		$field_types['custom'][ $key ] = array_merge( $field_base, $array );
	}

	/**
	 * Optional field types
	 *
	 * @since 1.18
	 */
	$field_types['optional'] = array(
		'categories' => array(
			'input_type'              => __( 'categories', 'strong-testimonials' ),
			'option_label'            => __( 'category selector', 'strong-testimonials' ),
			'show_placeholder_option' => 0,
			'show_admin_table_option' => 0,
		)
	);
	foreach ( $field_types['optional'] as $key => $array ) {
		$field_types['optional'][ $key ] = array_merge( $field_base, $array );
	}

	// Assemble field groups.
	$field_groups = array(
		'default' => array(
			'name'   => 'default',
			'label'  => __( 'Default Field Group', 'strong-testimonials' ),
			'fields' => array(
				// ------
				// CUSTOM
				// ------
				0 => array(
					'record_type' => 'custom',
					'name'        => 'client_name',
					'label'       => __( 'Full Name', 'strong-testimonials' ),
					'input_type'  => 'text',
					'required'    => 1,
					'after'       => __( 'What is your full name?', 'strong-testimonials' ),
					'admin_table' => 1,
				),
				1 => array(
					'record_type' => 'custom',
					'name'        => 'email',
					'label'       => __( 'Email', 'strong-testimonials' ),
					'input_type'  => 'email',
					'required'    => 1,
					'after'       => __( 'What is your email address?', 'strong-testimonials' ),
				),
				3 => array(
					'record_type' => 'custom',
					'name'        => 'company_name',
					'label'       => __( 'Company Name', 'strong-testimonials' ),
					'input_type'  => 'text',
					'after'       => __( 'What is your company name?', 'strong-testimonials' ),
				),
				4 => array(
					'record_type' => 'custom',
					'name'        => 'company_website',
					'label'       => __( 'Company Website', 'strong-testimonials' ),
					'input_type'  => 'url',
					'after'       => __( 'Does your company have a website?', 'strong-testimonials' ),
				),
				// ----
				// POST
				// ----
				5 => array(
					'record_type' => 'post',
					'name'        => 'post_title',
					'label'       => __( 'Heading', 'strong-testimonials' ),
					'input_type'  => 'text',
					'required'    => 0,
					'after'       => __( 'A headline for your testimonial.', 'strong-testimonials' ),
				),
				6 => array(
					'record_type' => 'post',
					'name'        => 'post_content',
					'label'       => __( 'Testimonial', 'strong-testimonials' ),
					'input_type'  => 'textarea',
					'required'    => 1,
					'after'       => __( 'What do you think about us?', 'strong-testimonials' ),
				),
				7 => array(
					'record_type' => 'post',
					'name'        => 'featured_image',
					'label'       => __( 'Photo', 'strong-testimonials' ),
					'input_type'  => 'file',
					'after'       => __( 'Would you like to include a photo?', 'strong-testimonials' ),
					'admin_table' => 1,
				),
			)
		)
	);
	foreach ( $field_groups['default']['fields'] as $key => $array ) {
		if ( 'post' == $array['record_type'] ) {
			$field_groups['default']['fields'][ $key ] = array_merge( $field_types['post'][ $array['name'] ], $array );
		} else {
			$field_groups['default']['fields'][ $key ] = array_merge( $field_types['custom'][ $array['input_type'] ], $array );
		}
	}

	// Copy default field group to custom field group.
	$field_groups['custom'] = array(
		'name'   => 'custom',
		'label'  => __( 'Custom Field Group', 'strong-testimonials' ),
		'fields' => $field_groups['default']['fields'],
	);

	// Assemble default field settings.
	$default_fields['field_base']          = $field_base;
	$default_fields['field_types']         = $field_types;
	$default_fields['field_groups']        = $field_groups;
	$default_fields['current_field_group'] = 'custom';
	
	return $default_fields;
}

/**
 * Form
 * 
 * @return array
 */
function wpmtst_get_default_form_options() {
	/**
	 * Messages
	 *
	 * @since 1.13
	 */
	$default_messages = array(
		'required-field'     => array(
			'order'       => 1,
			/* translators: Settings > Forms > Messages tab */
			'description' => __( 'Required field', 'strong-testimonials' ),
			/* translators: Default message for required field message at top of form. */
			'text'        => __( 'Required field', 'strong-testimonials' ),
		),
		'captcha'            => array(
			'order'       => 2,
			/* translators: Settings > Forms > Messages tab */
			'description' => _x( 'Captcha label', 'description', 'strong-testimonials' ),
			/* translators: Default label for Captcha field on submission form. */
			'text'        => _x( 'Captcha', 'strong-testimonials' ),
		),
		'form-submit-button' => array(
			'order'       => 3,
			/* translators: Settings > Forms > Messages tab */
			'description' => _x( 'Submit button', 'description', 'strong-testimonials' ),
			/* translators: Default label for the Submit button on testimonial form. */
			'text'        => _x( 'Add Testimonial', 'the Submit button', 'strong-testimonials' ),
		),
		'submission-error'   => array(
			'order'       => 4,
			/* translators: Settings > Forms > Messages tab */
			'description' => _x( 'Submission error', 'description', 'strong-testimonials' ),
			/* translators: Default message for submission form error. */
			'text'        => _x( 'There was a problem processing your testimonial.', 'error message', 'strong-testimonials' ),
		),
		'submission-success' => array(
			'order'       => 5,
			/* translators: Settings > Forms > Messages tab */
			'description' => _x( 'Submission success', 'description', 'strong-testimonials' ),
			/* translators: Default message for submission form success message. */
			'text'        => _x( 'Thank you! Your testimonial is awaiting moderation.', 'success message', 'strong-testimonials' ),
		),
	);

	uasort( $default_messages, 'wpmtst_uasort' );

	$default_form_options = array(
		'post_status'       => 'pending',
		'admin_notify'      => 0,
		'sender_name'       => get_bloginfo( 'name' ),
		'sender_site_email' => 1,
		//'sender_email'      => 'noreply@' . preg_replace( '/^www\./', '', $_SERVER['HTTP_HOST'] ),
		'sender_email'      => '',
		'recipients'        => array(
			array(
				'admin_name'       => '',
				'admin_email'      => '',
				'admin_site_email' => 1,
				'primary'          => 1,  // cannot be deleted
			),
		),
		'default_recipient' => array(
			'admin_name'  => '',
			'admin_email' => '',
		),
		/* translators: Default subject line for new testimonial notification email. */
		'email_subject'     => __( 'New testimonial for %BLOGNAME%', 'strong-testimonials' ),
		/* translators: Default message for new testimonial notification email. */
		'email_message'     => __( 'New testimonial submission for %BLOGNAME%. This is awaiting action from the website administrator.', 'strong-testimonials' ),
		'captcha'           => '',
		'honeypot_before'   => 0,
		'honeypot_after'    => 1,
		'messages'          => $default_messages,
	);

	return $default_form_options;
}

/**
 * Some default view options.
 *
 * @since 1.21.0
 */
function wpmtst_get_default_view_options() {
	$default_view_options = array(
		'mode'    => array(
			'options' => array(
				array( 'name' => 'display', 'label' => __( 'Display', 'strong-testimonials' ), 'description' => '', 'help' => '' ),
				array( 'name' => 'slideshow', 'label' => __( 'Slideshow', 'strong-testimonials' ), 'description' => '', 'help' => '' ),
				array( 'name' => 'form', 'label' => __( 'Form', 'strong-testimonials' ), 'description' => '', 'help' => '' ),
			)
		),
		'order'   => array(
			'options' => array(
				array( 'name' => 'random', 'label' => __( 'Random', 'strong-testimonials' ), 'description' => '', 'help' => '' ),
				array( 'name' => 'newest', 'label' => __( 'Newest first', 'strong-testimonials' ), 'description' => '', 'help' => '' ),
				array( 'name' => 'oldest', 'label' => __( 'Oldest first', 'strong-testimonials' ), 'description' => '', 'help' => '' )
			)
		),
		'content' => array(
			'options' => array(
				array( 'name' => 'excerpt', 'label' => __( 'Excerpt', 'strong-testimonials' ), 'description' => '', 'help' => '' ),
				array( 'name' => 'length', 'label' => __( 'Length', 'strong-testimonials' ), 'description' => '', 'help' => '' ),
				array( 'name' => 'entire', 'label' => __( 'Entire', 'strong-testimonials' ), 'description' => '', 'help' => '' )
			)
		),
	);
	return $default_view_options;
}

/**
 * option name: wpmtst_view_default
 * 
 * @since 1.21.0
 */
function wpmtst_get_default_view() {
	$default_view = array(
		'all'              => true,
		'background'       => '',
		'category'         => 'all',
		'class'            => '',
		'client_section'   => array(
			0 => array(
				'field' => 'client_name',
				'type'  => 'text',
				'class' => 'testimonial-name'
			),
			1 => array(
				'field'   => 'company_name',
				'type'    => 'link',
				'url'     => 'company_website',
				'class'   => 'testimonial-company',
				'new_tab' => true
			)
		),
		'content'          => 'entire',
		'count'            => 5,
		'effect_for'       => 1.5,
		'gravatar'         => 'no',
		'id'               => '',
		'length'           => 200,
		'lightbox'         => '',
		'mode'             => 'display',
		'more_page'        => false,
		'more_post'        => false,
		'more_text'        => 'Read more',
		'nav'              => 'after',
		'no_pause'         => false,
		'order'            => 'oldest',
		'page'             => '',
		'pagination'       => false,
		'per_page'         => 5,
		'show_for'         => 8,
		'template'         => '',
		'thumbnail'        => true,
		'thumbnail_size'   => 'thumbnail',
		'thumbnail_height' => null,
		'thumbnail_width'  => null,
		'title'            => true,
	);

	ksort( $default_view );
	return $default_view;
}

/**
 * The contexts for string translation in WPML & Polylang plugins.
 * 
 * @since 1.21.0
 * 
 * @return array
 */
function wpmtst_get_default_l10n_contexts() {
	/* Translators: For string translation in WPML & Polylang plugins. */
	$contexts = array(
		'form-fields'   => __( 'Testimonial Form Fields', 'strong-testimonials' ),
		'form-messages' => __( 'Testimonial Form Messages', 'strong-testimonials' ),
		'notification'  => __( 'Testimonial Notification Options', 'strong-testimonials' ),
	);
	return $contexts;
}
