<?php
/**
 * Template Functions
 * 
 * @package Strong_Testimonials
 */

/**
 * @param string $before
 * @param string $after
 */
function wpmtst_the_title( $before = '', $after = '' ) {
	if ( WPMST()->atts( 'title' ) && get_the_title() ) {
		echo $before . get_the_title() . $after;
	}
}

/**
 * @param null $length
 */
function wpmtst_the_content( $length = null ) {
	if ( $length ) {
		$output = wpmtst_get_field( 'truncated', array( 'char_limit' => $length ) );
	} else {
		// excerpt overrides length overrides full content
		extract( WPMST()->atts( array( 'excerpt', 'length' ) ) );
		if ( $excerpt ) {
			$output = get_the_excerpt();
		} elseif ( $length ) {
			$output = wpmtst_get_field( 'truncated', array( 'char_limit' => $length ) );
		} else {
			$output = wpautop( get_the_content() );
		}
	}
	echo do_shortcode( $output );
}

/**
 * Display the thumbnail.
 *
 * TODO WP 4.2+ has better filters.
 *
 * @param null $size
 */
function wpmtst_the_thumbnail( $size = null ) {
	if ( ! WPMST()->atts( 'thumbnail' ) )
		return;
	
	$size = ( null === $size ) ? WPMST()->atts( 'thumbnail_size' ) : $size ;
	$id   = get_the_ID();
	$img  = false;
	
	if ( has_post_thumbnail( $id ) ) {
		$img = get_the_post_thumbnail( $id, $size );
	} elseif ( 'no' != WPMST()->atts( 'gravatar' ) ) {
		// avatars are square so get the width of the requested size
		if ( is_array( $size ) ) {
			// if dimension array
			$gravatar_size = $size[0];
		} else {
			// if named size
			$image_sizes   = wpmtst_get_image_sizes();
			$gravatar_size = $image_sizes[$size]['width'];
		}
		$img = get_avatar( wpmtst_get_field( 'email' ), apply_filters( 'wpmtst_gravatar_size', $gravatar_size ), '' );
	}
	
	if ( $img ) {
		if ( WPMST()->atts( 'lightbox' ) ) {
			$url = wp_get_attachment_url( get_post_thumbnail_id( $id ) );
			if ( $url ) {
				$img = '<a href="' . $url . '">' . $img . '</a>';
				/**
				 * Adjust settings for Simple Colorbox plugin. 
				 * TODO do the same for other lightbox plugins
				 */
				if ( defined( 'SIMPLECOLORBOX_VERSION' ) ) {
					add_action( 'wp_footer', 'wpmtst_colorbox_manual_settings', 100 );
				}
			}
		}
		echo '<div class="testimonial-image">' . $img . '</div>';
	}
}

/**
 * Filter the avatar.
 *
 * @since 1.22.0
 *
 * TODO WP 4.2+ has better filters.
 */
 function wpmtst_avatar_filter( $avatar, $id_or_email, $size, $default, $alt ) {
	if ( 'if' == WPMST()->atts( 'gravatar' ) && false !== strpos( $avatar, 'avatar-default' ) ) {
		$avatar = '';
	}
	return $avatar;
}

/**
 * Global Colorbox settings.
 * 
 * @param $settings
 *
 * @return mixed
 */
function wpmtst_colorbox_settings( $settings ) {
	$settings['returnFocus'] = false;
	$settings['rel'] = 'nofollow';
	return $settings;
}
//add_filter( 'simple_colorbox_settings', 'wpmtst_colorbox_settings' );

/**
 * Colorbox settings for testimonials only.
 */
function wpmtst_colorbox_manual_settings() {
	?>
	<script>
	// de-focus and disable grouping 
	jQuery(function($){
		$(".testimonial-image a").colorbox({rel:"nofollow",returnFocus:false});
	});
	</script>
	<?php
}

/**
 * @param string $format
 * @param string $class
 *
 * @return bool
 */
function wpmtst_the_date( $format = '', $class = '' ) {
	global $post;
	if ( ! $post )
		return false;

	if ( ! $format )
		$format = get_option( 'date_format' );

	$the_date = apply_filters( 'wpmtst_the_date', mysql2date( $format, $post->post_date ), $format, $post );
	echo '<div class="' . $class . '">' . $the_date . '</div>';
}

function wpmtst_the_client() {
	$atts = WPMST()->atts();
	if ( isset( $atts['client_section'] ) ) {
		// View
		echo wpmtst_client_section( $atts['client_section'] );
	} elseif ( $atts['content'] ) {
		// Child shortcodes
		$shortcode_content = reverse_wpautop( $atts['content'] );
		if ( has_child_shortcode( $shortcode_content, 'client', $atts['parent_tag'] ) ) {
			echo do_child_shortcode( $atts['parent_tag'], $shortcode_content );
		}
	}
}

/**
 * Client section
 *
 * @since 1.21.0
 * @param array $client_section An array of client fields.
 * @return mixed
 */
function wpmtst_client_section( $client_section ) {
	global $post;
	$html = '';
	foreach ( $client_section as $field ) {
		if ( 'link' == $field['type'] ) {
			$text = get_post_meta( $post->ID, $field['field'], true );
			$url  = get_post_meta( $post->ID, $field['url'], true );
			if ( $url ) {
				$new_tab = isset( $field['new_tab'] ) ? $field['new_tab'] : false;

				// TODO Make this an option.
				$nofollow = get_post_meta( $post->ID, 'nofollow', true );

				// if field empty, use domain instead
				if ( '' == $text )
					$text = preg_replace( '(^https?://)', '', $url );

				$output = sprintf( '<a href="%s"%s%s>%s</a>', $url, link_new_tab( $new_tab, false ), link_nofollow( $nofollow, false ), $text );
			}
			else {
				// if no URL, just show the field
				$output = $text;
			}
		}
		elseif ( 'date' == $field['type'] ) {
			$format = isset( $field['format'] ) && $field['format'] ? $field['format'] : get_option( 'date_format' );
			$the_date = mysql2date( $format, $post->post_date );
			$output = apply_filters( 'wpmtst_the_date', $the_date, $format, $post );
		}
		else {
			// text field
			$output = get_post_meta( $post->ID, $field['field'], true );
		}

		if ( $output ) {
			$html .= '<div class="' . $field['class'] . '">' . $output . '</div>';
		}
	}
	return $html;
}

function wpmtst_read_more() {
	$atts = WPMST()->atts( array( 'more_post', 'more_page', 'more_text' ) );
	if ( $atts['more_post'] ) {
		echo '<div class="readmore"><a href="' . get_permalink() . '">' . $atts['more_text'] . '</a></div>';
	}
	if ( $atts['more_page'] ) {
		/**
		 * more_page - "Read more" link to a page by ID or slug
		 *
		 * @since 1.20.0
		 */
		if ( ! is_numeric( $atts['more_page'] ) ) {
			$post_object = get_page_by_slug( $atts['more_page'] );
			$atts['more_page'] = $post_object->ID;
		}
		echo '<div class="readmore"><a href="' . get_permalink( $atts['more_page'] ) . '">' . $atts['more_text'] . '</a></div>';
	}
}

function wpmtst_container_class() {
	echo apply_filters( 'wpmtst_container_class', WPMST()->atts( 'container_class' ) );
}

function wpmtst_content_class() {
	echo apply_filters( 'wpmtst_content_class', WPMST()->atts( 'content_class' ) );
}

function wpmtst_post_class() {
	echo apply_filters( 'wpmtst_post_class', WPMST()->atts( 'post_class' ) . ' post-' . get_the_ID() );
}

/**
 * Echo custom field.
 *
 * @since 1.11.0
 */
function wpmtst_field( $field = null, $args = array() ) {
	echo wpmtst_get_field( $field, $args );
}

/**
 * Fetch custom field.
 *
 * Thanks to Matthew Harris.
 * @link https://github.com/cdillon/strong-testimonials/issues/2
 * @since 1.15.7
 */
function wpmtst_get_field( $field, $args = array() ) {
	if ( ! $field ) return '';

	global $post;

	switch( $field ) {

		// Apply a character limit to post content.
		case 'truncated' :
			$html = wpmtst_truncate( $post->post_content, $args['char_limit'] );
			break;

		// Process child shortcodes in [strong] content.
		case 'client' :
			$html = do_child_shortcode( wpmtst_get_shortcode(), $args['content'] );
			break;

		// Get the custom field.
		default :
			$html = get_post_meta( $post->ID, $field, true );

	}
	return $html;
}
