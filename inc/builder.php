<?php
/**
 * Class Spine_Builder_Custom
 */
class Fais_Spine_Builder_Custom
{

	/**
	 * Add hooks, start up custom builder components.
	 */
	public function __construct() {
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ), 13 );
		add_action( 'admin_init', array( $this, 'remove_extra_make' ), 13 );
		add_action( 'admin_init', array( $this, 'remove_builder_sections' ), 13 );
		add_action( 'admin_init', array( $this, 'add_builder_sections' ), 14 );
		add_filter( 'content_edit_pre', array( $this, 'filter_function_name' ), 10, 2 );
		add_filter( 'make_insert_post_data_sections', array( $this, 'set_section_meta' ), 13, 1 );
	}


	public function get_column_default_size($section_type){

		$column_size_defaults = [ 1 => 'fourths-4' ];

		if ( 'faiswsuwphalves' === $section_type ) {
			$column_size_defaults = [ 1 => 'fourths-2', 2 => ' fourths-2' ];
		} elseif ( 'faiswsuwpsidebarright' === $section_type ) {
			$column_size_defaults = [ 1 => 'fifths-3', 2 => 'fifths-2' ];
		} elseif ( 'faiswsuwpsidebarleft' === $section_type ) {
			$column_size_defaults = [ 1 => 'fifths-2', 2 => 'fifths-3' ];
		} elseif ( 'faiswsuwpthirds' === $section_type ) {
			$column_size_defaults = [ 1 => 'thirds-1',2 => 'thirds-1', 3 => 'thirds-1' ];
		} elseif ( 'faiswsuwpquarters' === $section_type ) {
			$column_size_defaults = [ 1 => 'fourths-1', 2 => 'fourths-1', 3 => 'fourths-1', 4 => 'fourths-1' ];
		}
		return $column_size_defaults;
	}


	public function filter_function_name( $content, $post_id ) {

		// Process content here
		if ( ! ttfmake_post_type_supports_builder( get_post_type() ) ) {
			return $content;
		}

		$old_wsu_items = [ 'wsuwpsingle', 'wsuwphalves', 'wsuwpsidebarleft', 'wsuwpsidebarright', 'wsuwpthirds' ];
		$section_data        = ttfmake_get_section_data( $post_id );

		//var_dump('was');
		//var_dump($section_data);
		// Print the current sections
		$needed_conversion = false;
		$would_update = [];
		foreach ( $section_data as $id => $section ) {
			if ( in_array( $section['section-type'], $old_wsu_items, true ) ) {

				$section['section-type'] = 'fais'.$section['section-type'];

				foreach ( $section['columns'] as $cid => $object ) {
					// 'column-type' => string 'flex-column  fifths-3  order-1  grid-part'
					$order = array_flip ( $section['columns-order'] )[$cid] + 1;
					$object['column-type']='flex-column  '.$this->get_column_default_size( $section['section-type'] )[$cid].'  order-'. $order .'  grid-part';
					$section['columns'][$cid] = $object;
				}

				$section['columns'][$cid] = $object;
				if( false !== strpos( trim($section['section-classes']), 'gutter pad-top') ){
					$section['section-classes'] = implode('',explode('gutter pad-top',$section['section-classes']));
				}
				$section['section-classes'] = 'flex-row items-start '.$section['section-classes'];
				$section['section-layout'] = null;
				$needed_conversion = true;
				$section_data[$id] = $section;
			}
		}

		//var_dump('is');
		//var_dump($section_data);

		if ( $needed_conversion ) {
			$var_data = array( 'ID' => $post_id );
			$this->wp_insert_post_data( $var_data, $section_data );
			$url = admin_url().'post.php?post='.$post_id.'&action=edit';
			wp_redirect( $url );
		}

	//var_dump( $would_update );
	//var_dump( $ttfmake_sections );

	//$ttfmake_sections = get_post_meta( $post_id, '', true );
	//var_dump( $ttfmake_sections );
	//var_dump( $needed_conversion );die();

		return $content;
	}
/**
	 * On post save, use a theme template to generate content from metadata.
	 *
	 * @since  1.0.0.
	 *
	 * @param  array    $data       The processed post data.
	 * @param  array    $section_data    The processed sections.
	 * @return array                Modified post data.
	 */
	public function wp_insert_post_data( $data, $section_data ) {

		// save meta
		ttfmake_get_builder_save()->save_data( get_the_ID(), $post_id );

		// Generate the post content
		$post_content = ttfmake_get_builder_save()->generate_post_content( $section_data );

		// Sanitize and set the content
		kses_remove_filters();
		$data['post_content'] = sanitize_post_field( 'post_content', $post_content, get_the_ID(), 'db' );
		kses_init_filters();

		//var_dump($data);
		//die();
		wp_update_post( $data, true );
		if (is_wp_error($post_id)) {
			$errors = $post_id->get_error_messages();
			foreach ($errors as $error) {
				echo $error;
			}
		}

		return $data;
	}

	/**
	 * Enqueue the scripts and styles used with the page builder.
	 */
	public function enqueue_scripts( $hook_suffix ) {
		// Only load resources if they are needed on the current page
		if ( ! in_array( $hook_suffix, array( 'post.php', 'post-new.php' ), true ) || ! ttfmake_post_type_supports_builder( get_post_type() )	) {
			return;
		}

		wp_dequeue_script( 'ttfmake-admin-edit-page' );
		wp_enqueue_script( 'fais-ttfmake-admin-edit-page', get_stylesheet_directory_uri() . '/inc/builder-custom/js/edit-page.js', array( 'jquery' ), spine_get_script_version(), true );

		wp_enqueue_style( 'flexwork-devices', get_stylesheet_directory_uri() . '/TempAssests/css/flexwork-devices.css' );

		wp_enqueue_script( 'flexibility', get_stylesheet_directory_uri() . '/TempAssests/js/flexibility.js', array( 'jquery' ), spine_get_script_version(), true );
		wp_script_add_data( 'flexibility', 'conditional', 'lte IE 10' );

	}

	/**
	 * Check to see if specific sections are being saved and enqueue necessary front end scripts
	 * and styles if applicable.
	 *
	 * @param array $sections List of sections being saved as content in page builder.
	 *
	 * @return array Same list of sections.
	 */
	public function set_section_meta( $sections ) {
		$section_types = wp_list_pluck( $sections, 'section-type' );

		if ( in_array( 'banner', $section_types, true ) ) {
			update_post_meta( get_the_ID(), '_has_builder_banner', 1 );
		} else {
			delete_post_meta( get_the_ID(), '_has_builder_banner' );
		}

		return $sections;
	}

	/**
	 * Remove some of the add-on functionality for Make that we are not able to
	 * support in the Spine parent theme.
	 */
	public function remove_extra_make() {
		remove_action( 'edit_form_after_title', 'ttfmake_plus_quick_start' );
		remove_action( 'post_submitbox_misc_actions', array( ttfmake_get_builder_base(), 'post_submitbox_misc_actions' ) );
		remove_action( 'tiny_mce_before_init', array( ttfmake_get_builder_base(), 'tiny_mce_before_init' ), 15 );
	}

	/**
	 * Remove sections that were previously defined in the upstream Make project.
	 */
	public function remove_builder_sections() {
		ttfmake_remove_section( 'text' );
		ttfmake_remove_section( 'gallery' );
		ttfmake_remove_section( 'banner' );
		ttfmake_remove_section( 'blank' );

		ttfmake_remove_section( 'wsuwpsingle' );
		ttfmake_remove_section( 'wsuwphalves' );
		ttfmake_remove_section( 'wsuwpsidebarleft' );
		ttfmake_remove_section( 'wsuwpsidebarright' );

		ttfmake_remove_section( 'wsuwpthirds' );
		ttfmake_remove_section( 'wsuwpquarters' );
		ttfmake_remove_section( 'wsuwpheader' );
		/*ttfmake_remove_section( 'banner' );*/
	}

	/**
	 * Add the custom sections used in our implementation of the page builder.
	 * removes the partent spine for the matching blocks using the newer gridding
	 */
	public function add_builder_sections() {

		ttfmake_add_section(
			'faiswsuwpsingle',
			'Single',
			get_template_directory_uri() . '/inc/builder/sections/css/images/blank.png',
			'A single column layout.',
			array( $this, 'save_columns' ),
			'admin/columns',
			'front-end/columns',
			200,
			get_stylesheet_directory() . '/builder-templates/'
		);

		ttfmake_add_section(
			'faiswsuwphalves',
			'Halves',
			get_template_directory_uri() . '/inc/builder-custom/images/halves.png',
			'Two column layout with equal size columns.',
			array( $this, 'save_columns' ),
			'admin/columns',
			'front-end/columns',
			500,
			get_stylesheet_directory() . '/builder-templates/'
		);

		ttfmake_add_section(
			'faiswsuwpsidebarleft',
			'Sidebar Left',
			get_template_directory_uri() . '/inc/builder-custom/images/side-left.png',
			'Two column layout with the right side larger than the left.',
			array( $this, 'save_columns' ),
			'admin/columns',
			'front-end/columns',
			300,
			get_stylesheet_directory() . '/builder-templates/'
		);

		ttfmake_add_section(
			'faiswsuwpsidebarright',
			'Sidebar Right',
			get_template_directory_uri() . '/inc/builder-custom/images/side-right.png',
			'Two column layout with the left side larger than the right.',
			array( $this, 'save_columns' ),
			'admin/columns',
			'front-end/columns',
			400,
			get_stylesheet_directory() . '/builder-templates/'
		);

		ttfmake_add_section(
			'faiswsuwpthirds',
			'Thirds',
			get_template_directory_uri() . '/inc/builder-custom/images/thirds.png',
			'Three column layout, choose between thirds and triptych.',
			array( $this, 'save_columns' ),
			'admin/columns',
			'front-end/columns',
			600,
			get_stylesheet_directory() . '/builder-templates'
		);

		ttfmake_add_section(
			'faiswsuwpquarters',
			'Quarters',
			get_template_directory_uri() . '/inc/builder-custom/images/quarters.png',
			'Four column layout, all equal sizes.',
			array( $this, 'save_columns' ),
			'admin/columns',
			'front-end/columns',
			700,
			get_stylesheet_directory() . '/builder-templates'
		);

		ttfmake_add_section(
			'faiswsuwpheader',
			'Header',
			get_template_directory_uri() . '/inc/builder-custom/images/h1.png',
			'A header element to provide a page title or other top level header.',
			array( $this, 'save_header' ),
			'admin/h1-header',
			'front-end/h1-header',
			100,
			get_stylesheet_directory() . '/builder-templates'
		);

		ttfmake_add_section(
			'banner',
			_x( 'banner', 'section name', 'make' ),
			get_template_directory_uri() . '/inc/builder/sections/css/images/banner.png',
			__( 'Display multiple types of content in a banner or a slider.', 'make' ),
			array( $this, 'save_banner' ),
			'admin/banner',
			'front-end/banner',
			800,
			get_stylesheet_directory() . '/builder-templates'
		);/**/

	}

	/**
	 * Sanitizes a string to only return numbers.
	 *
	 * @since  1.0.0.
	 *
	 * @param  string    $id    The section ID.
	 * @return string           The sanitized ID.
	 */
	public static function clean_section_id( $id ) {
		return preg_replace( '/[^0-9]/', '', $id );
	}
	/**
	 * Clean a passed input value of arbitrary classes.
	 *
	 * @param string $classes A string of arbitrary classes from a text input.
	 *
	 * @return string Clean, space delimited classes for output.
	 */
	public function clean_classes( $classes ) {
		$classes = explode( ' ', trim( $classes ) );
		$classes = array_map( 'sanitize_key', $classes );
		$classes = implode( ' ', $classes );

		return $classes;
	}

	/**
	 * Clean a passed input value of arbitrary classes.
	 *
	 * @param string $classes A string of arbitrary classes from a text input.
	 *
	 * @return string Clean, space delimited classes for output.
	 */
	public function clean_type( $classes ) {
		$classes = explode( ' ', trim( $classes ) );
		$classes = array_map( 'sanitize_key', $classes );
		$classes = implode( ' ', $classes );

		return $classes;
	}


	/**
	 * Clean a header element against an allowed list.
	 *
	 * @param string $header_element
	 *
	 * @return string
	 */
	public function clean_header_element( $header_element ) {
		if ( in_array( $header_element, array( 'h1', 'h2', 'h3', 'h4' ), true ) ) {
			return $header_element;
		}

		return 'h2';
	}

	/**
	 * Clean a attribute set for tags.
	 *
	 * @param string $attr_str
	 *
	 * @return string
	 */
	public function clean_attr( $attr_str ) {
		return addslashes( $attr_str );
	}


	/**
	 * Allow phrasing tags to be added in title areas via the kses allowed HTML filter.
	 *
	 * @return array List of tags and attributes allowed.
	 */
	public function allow_phrasing_in_titles() {
		$phrasing_tags = array(
			'b',
			'big',
			'i',
			'small',
			'tt',
			'abbr',
			'acronym',
			'cite',
			'code',
			'dfn',
			'em',
			'kbd',
			'strong',
			'samp',
			'var',
			'a',
			'bdo',
			'br',
			'q',
			'span',
			'sub',
			'sup',
			'label',
			'wbr',
			'del',
			'ins',
		);

		$tags = array();

		foreach ( $phrasing_tags as $tag ) {
			$tags[ $tag ]['class'] = true;
			$tags[ $tag ]['id'] = true;
		}

		return $tags;
	}

	/**
	 * Clean the data being passed from the title input field to ensure it is ready
	 * for input into the database as part of the template.
	 *
	 * @param array $data Array of data inputs being passed.
	 *
	 * @return array Clean data.
	 */
	public function save_header( $data ) {
		$clean_data = array();

		// The title_save_pre filter applies wp_filter_kses() to the title.
		if ( isset( $data['title'] ) ) {
			add_filter( 'wp_kses_allowed_html', array( $this, 'allow_phrasing_in_titles' ) );
			$clean_data['title'] = $clean_data['label'] = apply_filters( 'title_save_pre', $data['title'] );
			remove_filter( 'wp_kses_allowed_html', array( $this, 'allow_phrasing_in_titles' ) );
		}

		if ( isset( $data['section-classes'] ) ) {
			$clean_data['section-classes'] = $this->clean_classes( $data['section-classes'] );
		}

		if ( isset( $data['section-flextype'] ) ) {
			$clean_data['section-flextype'] = $this->clean_classes( $data['section-flextype'] );
		}

		if ( isset( $data['section-position'] ) ) {
			$clean_data['section-position'] = $this->clean_classes( $data['section-position'] );
		}

		if ( isset( $data['section-attr'] ) ) {
			$clean_data['section-attr'] = $this->clean_attr( $data['section-attr'] );
		}

		if ( isset( $data['column-classes'] ) ) {
			$clean_data['column-classes'] = $this->clean_classes( $data['column-classes'] );
		}

		if ( isset( $data['header-level'] ) ) {
			$clean_data['header-level'] = $this->clean_header_element( $data['header-level'] );
		}

		if ( isset( $data['column-background-image'] ) ) {
			$clean_data['column-background-image'] = esc_url_raw( $data['column-background-image'] );
		}

		if ( isset( $data['label'] ) ) {
			$clean_data['label'] = sanitize_text_field( $data['label'] );
		}

		if ( isset( $data['background-img'] ) ) {
			$clean_data['background-img'] = esc_url_raw( $data['background-img'] );
		}

		if ( isset( $data['background-mobile-img'] ) ) {
			$clean_data['background-mobile-img'] = esc_url_raw( $data['background-mobile-img'] );
		}

		$clean_data = apply_filters( 'spine_builder_save_header', $clean_data, $data );

		return $clean_data;
	}

	/**
	 * Clean the data being passed from the save of a columns layout.
	 *
	 * @param array $data Array of data inputs being passed.
	 *
	 * @return array Clean data.
	 */
	public function save_columns( $data ) {
		$clean_data = array();

		if ( isset( $data['columns-number'] ) ) {
			if ( in_array( $data['columns-number'], range( 1, 4 ), true ) ) {
				$clean_data['columns-number'] = $data['columns-number'];
			}
		}

		if ( isset( $data['columns-order'] ) ) {
			$clean_data['columns-order'] = array_map( array( $this, 'clean_section_id' ), explode( ',', $data['columns-order'] ) );
		}

		if ( isset( $data['columns'] ) && is_array( $data['columns'] ) ) {
			$i = 1;
			foreach ( $data['columns'] as $id => $item ) {
				if ( isset( $item['title'] ) ) {
					add_filter( 'wp_kses_allowed_html', array( $this, 'allow_phrasing_in_titles' ) );
					$clean_data['columns'][ $id ]['title'] = apply_filters( 'title_save_pre', $item['title'] );
					remove_filter( 'wp_kses_allowed_html', array( $this, 'allow_phrasing_in_titles' ) );

					// The first title serves as the section title
					if ( 1 === $i ) {
						add_filter( 'wp_kses_allowed_html', array( $this, 'allow_phrasing_in_titles' ) );
						$clean_data['label'] = apply_filters( 'title_save_pre', $item['title'] );
						remove_filter( 'wp_kses_allowed_html', array( $this, 'allow_phrasing_in_titles' ) );
					}
				}

				if ( isset( $item['image-link'] ) ) {
					$clean_data['columns'][ $id ]['image-link'] = esc_url_raw( $item['image-link'] );
				}

				if ( isset( $item['image-id'] ) ) {
					$clean_data['columns'][ $id ]['image-id'] = absint( $item['image-id'] );
				}

				if ( isset( $item['content'] ) ) {
					$clean_data['columns'][ $id ]['content'] = sanitize_post_field( 'post_content', $item['content'], get_the_ID(), 'db' );
				}

				if ( isset( $item['toggle'] ) ) {
					if ( in_array( $item['toggle'], array( 'visible', 'invisible' ), true ) ) {
						$clean_data['columns'][ $id ]['toggle'] = $item['toggle'];
					}
				}

				if ( isset( $item['column-type'] ) ) {
					$clean_data['columns'][ $id ]['column-type'] = $this->clean_type( $item['column-type'] );
				}

				if ( isset( $item['column-classes'] ) ) {
					$clean_data['columns'][ $id ]['column-classes'] = $this->clean_classes( $item['column-classes'] );
				}

				if ( isset( $item['header-level'] ) ) {
					$clean_data['columns'][ $id ]['header-level'] = $this->clean_header_element( $item['header-level'] );
				}

				if ( isset( $item['column-background-image'] ) ) {
					$clean_data['columns'][ $id ]['column-background-image'] = esc_url_raw( $item['column-background-image'] );
				}
				$i++;
			}
		}

		if ( isset( $data['section-classes'] ) ) {
			$clean_data['section-classes'] = $this->clean_classes( $data['section-classes'] );
		}

		if ( isset( $data['section-flextype'] ) ) {
			$clean_data['section-flextype'] = $this->clean_classes( $data['section-flextype'] );
		}

		if ( isset( $data['section-position'] ) ) {
			$clean_data['section-position'] = $this->clean_classes( $data['section-position'] );
		}

		if ( isset( $data['section-attr'] ) ) {
			$clean_data['section-attr'] = $this->clean_attr( $data['section-attr'] );
		}

		if ( isset( $data['label'] ) ) {
			$clean_data['label'] = sanitize_text_field( $data['label'] );
		}

		if ( isset( $data['background-img'] ) ) {
			$clean_data['background-img'] = esc_url_raw( $data['background-img'] );
		}

		if ( isset( $data['background-mobile-img'] ) ) {
			$clean_data['background-mobile-img'] = esc_url_raw( $data['background-mobile-img'] );
		}

		$clean_data = apply_filters( 'spine_builder_save_columns', $clean_data, $data );

		return $clean_data;
	}

	/**
	 * Clean the data being passed when saving the Banner layout.
	 *
	 * @param array $data Array of data inputs being passed.
	 *
	 * @return array Clean data.
	 */
	public function save_banner( $data ) {
		$clean_data = array();

		$clean_data['title']       = $clean_data['label'] = ( isset( $data['title'] ) ) ? apply_filters( 'title_save_pre', $data['title'] ) : '';
		$clean_data['hide-arrows'] = ( isset( $data['hide-arrows'] ) && 1 === (int) $data['hide-arrows'] ) ? 1 : 0;
		$clean_data['hide-dots']   = ( isset( $data['hide-dots'] ) && 1 === (int) $data['hide-dots'] ) ? 1 : 0;
		$clean_data['autoplay']    = ( isset( $data['autoplay'] ) && 1 === (int) $data['autoplay'] ) ? 1 : 0;

		if ( isset( $data['transition'] ) && in_array( $data['transition'], array( 'fade', 'scrollHorz', 'none' ), true ) ) {
			$clean_data['transition'] = $data['transition'];
		}

		if ( isset( $data['delay'] ) ) {
			$clean_data['delay'] = absint( $data['delay'] );
		}

		if ( isset( $data['height'] ) ) {
			$clean_data['height'] = absint( $data['height'] );
		}

		if ( isset( $data['responsive'] ) && in_array( $data['responsive'], array( 'aspect', 'balanced' ), true ) ) {
			$clean_data['responsive'] = $data['responsive'];
		}

		if ( isset( $data['banner-slide-order'] ) ) {
			$clean_data['banner-slide-order'] = array_map( array( $this, 'clean_section_id' ), explode( ',', $data['banner-slide-order'] ) );
		}

		if ( isset( $data['banner-slides'] ) && is_array( $data['banner-slides'] ) ) {
			foreach ( $data['banner-slides'] as $id => $slide ) {

				if ( isset( $slide['content'] ) ) {
					$clean_data['banner-slides'][ $id ]['content'] = sanitize_post_field( 'post_content', $slide['content'], ( get_post() ) ? get_the_ID() : 0, 'db' );
				}

				if ( isset( $slide['background-color'] ) ) {
					$clean_data['banner-slides'][ $id ]['background-color'] = maybe_hash_hex_color( $slide['background-color'] );
				}

				$clean_data['banner-slides'][ $id ]['darken'] = ( isset( $slide['darken'] ) && 1 === (int) $slide['darken'] ) ? 1 : 0;

				if ( isset( $slide['image-id'] ) ) {
					$clean_data['banner-slides'][ $id ]['image-id'] = ttfmake_sanitize_image_id( $slide['image-id'] );
				}

				$clean_data['banner-slides'][ $id ]['alignment'] = ( isset( $slide['alignment'] ) && in_array( $slide['alignment'], array( 'none', 'left', 'right' ), true ) ) ? $slide['alignment'] : 'none';

				if ( isset( $slide['state'] ) ) {
					$clean_data['banner-slides'][ $id ]['state'] = ( in_array( $slide['state'], array( 'open', 'closed' ), true ) ) ? $slide['state'] : 'open';
				}

				if ( isset( $slide['spine_slide_url'] ) ) {
					$clean_data['banner-slides'][ $id ]['slide-url'] = esc_url_raw( $slide['spine_slide_url'] );
				}

				if ( isset( $slide['spine_slide_title'] ) ) {
					$clean_data['banner-slides'][ $id ]['slide-title'] = sanitize_text_field( $slide['spine_slide_title'] );
				}
			}
		}

		if ( isset( $data['section-flextype'] ) ) {
			$clean_data['section-flextype'] = $this->clean_classes( $data['section-flextype'] );
		}

		if ( isset( $data['section-position'] ) ) {
			$clean_data['section-position'] = $this->clean_classes( $data['section-position'] );
		}

		if ( isset( $data['section-attr'] ) ) {
			$clean_data['section-attr'] = $this->clean_attr( $data['section-attr'] );
		}

		if ( isset( $data['section-classes'] ) ) {
			$clean_data['section-classes'] = $this->clean_classes( $data['section-classes'] );
		}

		if ( isset( $data['column-classes'] ) ) {
			$clean_data['column-classes'] = $this->clean_classes( $data['column-classes'] );
		}

		if ( isset( $data['label'] ) ) {
			$clean_data['label'] = sanitize_text_field( $data['label'] );
		}

		if ( isset( $data['background-img'] ) ) {
			$clean_data['background-img'] = esc_url_raw( $data['background-img'] );
		}

		if ( isset( $data['background-mobile-img'] ) ) {
			$clean_data['background-mobile-img'] = esc_url_raw( $data['background-mobile-img'] );
		}

		$clean_data = apply_filters( 'spine_builder_save_banner', $clean_data, $data );

		return $clean_data;
	}

	public function build_flexwork_sectional_inputs( $field_name, $section_class_str = '' ) {
		//'flex-row wrap-reverse justify-start content-start items-start pad-airy-TB round-wide-L round-no-at-414'
		$setion_flex_options = [
			'area_type' => [ 'flex-row' => 'flex-row', 'flex-column' => 'flex-column', 'row-reverse' => 'row-reverse', 'column-reverse' => 'column-reverse' ],
			'wrapping' => [ 'wrap' => 'wrap', 'nowrap' => 'nowrap', 'wrap-reverse' => 'wrap-reverse' ],
			'content_justification' => [ 'justify-start' => 'justify-start', 'justify-end' => 'justify-end', 'justify-center' => 'justify-center', 'justify-between' => 'justify-between', 'justify-around' => 'justify-around' ],
			'content_alignment' => [ 'content-start' => 'content-start', 'content-end' => 'content-end', 'content-center' => 'content-center', 'content-between' => 'content-between', 'content-around' => 'content-around', 'content-stretch' => 'content-stretch' ],
			'items_positioning' => [ 'items-start' => 'items-start', 'items-end' => 'items-end', 'items-center' => 'items-center', 'items-baseline' => 'items-baseline', 'items-stretch' => 'items-stretch' ],
			'pad' => [
				'type' => [ '_' => 'inherited', 'airy' => 'airy (2em)', 'tight' => 'tight (1em)', 'no' => 'remove (0em)' ],
				'position' => [ '_' => 'All', 'L' => 'Left', 'R' => 'Right', 'T' => 'Top', 'B' => 'Bottom', 'LR' => 'Flanks/Sides', 'TB' => 'Ends/Head-foot' ],
			],
			'round' => [
				'type' => [ '_' => 'inherited', 'wide' => 'wide (2em)', 'tight' => 'tight (1em)', 'mini' => 'mini (0.5em)', 'no' => 'remove (0em)' ],
				'position' => [ '_' => 'All', 'L' => 'Left Side', 'R' => 'Right Side', 'T' => 'Top', 'B' => 'Bottom', 'TL' => 'Top Left', 'BL' => 'Bottom Left', 'TR' => 'Top Right', 'BR' => 'Bottom Right' ],
			],
		];
		$at_sizes = [ '320' => '320', '360' => '360', '375' => '375', '384' => '384', '390' => '390', '400' => '400', '414' => '414', '480' => '480', '568' => '568', '600' => '600', '640' => '640', '667' => '667', '695' => '695', '720' => '720', '736' => '736', '768' => '768', '800' => '800', '960' => '960', '1024' => '1024', '1280' => '1280', '1366' => '1366', '1440' => '1440' ];

		$flex_used = [
			'area_type' => '',
			'wrapping' => '',
			'content_justification' => '',
			'content_alignment' => '',
			'items_positioning' => '',
			'pad' => '',
			'round' => '',
			'at-sizes' => [],
		 ];
		$section_classes = explode( ' ', $section_class_str );
		//for now just get them in the right spot
		foreach ( $section_classes as $k => $class ) { // loop over class list
			foreach ( $setion_flex_options as $key => $item ) { // loop through options
				foreach ( $item as $subkey => $subitem ) { // loop though options values for match
					if ( 0 === strpos( $class, $key ) ) { // if the key matches first then kick into the sub arrays
						foreach ( $subitem as $partkey => $partoptions ) {
							if ( false !== strpos( $class, $partkey ) ) {
								if ( false !== strpos( $class, '-at-' ) ) {
									$flex_used['at-sizes'][] = $class;
									break 3;
								} else {
									$flex_used[ $key ] = $class;
									break 3;
								}
							}
						}
					} elseif ( ! is_array( $subitem ) ) {
						if ( false !== strpos( $class, $subitem ) ) {
							if ( false !== strpos( $class, '-at-' ) ) {
								$flex_used['at-sizes'][] = $class;
								break 2;
							} else {
								$flex_used[ $key ] = $class;
								break 2;
							}
						}
					}
				}
			}
		}

		//var_dump( $flex_used );

		?>
		<style>
		.flex-attr-area{
			display:inline-block;
			float:left;
		}
		#start_add_fw_class{
			float:right;
		}
		</style>
		<h3>Flexwork class builder <button class="start_add_fw_class">Add New Class</button></br></h3>

			<div class="fw-builder">
				<div class="flexwork-type flex-attr-area">type:<br/>
					<select class="flexwork-type-select flex-builder-selector fb-type-chooser">
						<option value="">Select</option>
						<?php
						foreach ( $setion_flex_options as $key => $option ) {
							echo '<option value="' . $key . '"">' . $key . '</option>';
						}
						?>
					</select>
				</div>
				<div  class="flexwork-area_type flex-attr-area">area type:<br/>
					<select class="flexwork-area_type-select flex-builder-selector fb-type">
						<option value="">Select</option>
						<?php
						foreach ( $setion_flex_options['area_type'] as $key => $option ) {
							echo '<option value="' . $key . '"">' . $option . '</option>';
						}
						?>
					</select>
				</div>
				<div class="flexwork-wrapping flex-attr-area">wrapping:<br/>
					<select class="flexwork-wrapping-select flex-builder-selector fb-type">
						<option value="">Select</option>
						<?php
						foreach ( $setion_flex_options['wrapping'] as $key => $option ) {
							echo '<option value="' . $key . '"">' . $option . '</option>';
						}
						?>
					</select>
				</div>
				<div class="flexwork-content_justification flex-attr-area">content justification:<br/>
					<select class="flexwork-content_justification-select flex-builder-selector fb-type">
						<option value="">Select</option>
						<?php
						foreach ( $setion_flex_options['content_justification'] as $key => $option ) {
							echo '<option value="' . $key . '"">' . $option . '</option>';
						}
						?>
					</select>
				</div>
				<div class="flexwork-content_alignment flex-attr-area">content alignment:<br/>
					<select class="flexwork-content_alignment-select flex-builder-selector fb-type">
						<option value="">Select</option>
						<?php
						foreach ( $setion_flex_options['content_alignment'] as $key => $option ) {
							echo '<option value="' . $key . '"">' . $option . '</option>';
						}
						?>
					</select>
				</div>
				<div class="flexwork-items_positioning flex-attr-area">items position:<br/>
					<select class="flexwork-items_positioning-select flex-builder-selector fb-type">
						<option value="">Select</option>
						<?php
						foreach ( $setion_flex_options['items_positioning'] as $key => $option ) {
							echo '<option value="' . $key . '"">' . $option . '</option>';
						}
						?>
					</select>
				</div>
				<div class="flexwork-pad flex-attr-area">padding:<br/>
					<select class="flexwork-pad-type-selectflex-builder-selector fb-type fb-with-child">
						<option value="">Select</option>
						<?php
						foreach ( $setion_flex_options['pad']['type'] as $key => $option ) {
							echo '<option value="' . $key . '"">' . $option . '</option>';
						}
						?>
					</select>
					<select class="flexwork-pad-position-select flex-builder-selector fb-type fb-with-child">
						<option value="">Select</option>
						<?php
						foreach ( $setion_flex_options['pad']['position'] as $key => $option ) {
							echo '<option value="' . $key . '"">' . $option . '</option>';
						}
						?>
					</select>
				</div>
				<div class="flexwork-round flex-attr-area">rounding:<br/>
					<select class="flexwork-round-type-select flex-builder-selector fb-type fb-with-child">
						<option value="">Select</option>
						<?php
						foreach ( $setion_flex_options['round']['type'] as $key => $option ) {
							echo '<option value="' . $key . '"">' . $option . '</option>';
						}
						?>
					</select>
					<select class="flexwork-round-position-select flex-builder-selector fb-type fb-with-child">
						<option value="">Select</option>
						<?php
						foreach ( $setion_flex_options['round']['position'] as $key => $option ) {
							echo '<option value="' . $key . '"">' . $option . '</option>';
						}
						?>
					</select>
				</div>
				<button class="fw_class_at">@</button>

				<div  class="flexwork-at-sizes flex-attr-area">at size:<br/>
					<select class="flexwork-at-sizes-select flex-builder-selector fb-type fb-with-child">
						<option value="">Select</option>
						<?php
						foreach ( $at_sizes as $key => $option ) {
							echo '<option value="at-' . $key . '"">' . $option . '</option>';
						}
						?>
					</select>
				</div>
				<button class="fw_add_class">+ add</button>
			</div>

		<?php
		return "<input type='text' name='" . $field_name . "' class='fexwork-classes full-width' value='" . $section_class_str . "'/>";
	}
	public function build_flexwork_column_inputs( $field_name, $column_class_str = '' ) {
		//'flex-row wrap-reverse justify-start content-start items-start pad-airy-TB round-wide-L round-no-at-414'
		$setion_flex_options = [
			'width' => [
				'thirds' => [ '1' => '1', '2' => '2', '3' => '3' ],
				'fourths' => [ '1' => '1', '2' => '2', '3' => '3', '4' => '4' ],
				'fifths' => [ '1' => '1', '2' => '2', '3' => '3', '4' => '4', '5' => '5' ],
				'sixths' => [ '1' => '1', '2' => '2', '3' => '3', '4' => '4', '5' => '5', '6' => '6' ],
				'eigths' => [ '1' => '1', '2' => '2', '3' => '3', '4' => '4', '5' => '5', '6' => '6', '7' => '7', '8' => '8' ],
				'ninths' => [ '1' => '1', '2' => '2', '3' => '3', '4' => '4', '5' => '5', '6' => '6', '7' => '7', '8' => '8', '9' => '9' ],
				'tenths' => [ '1' => '1', '2' => '2', '3' => '3', '4' => '4', '5' => '5', '6' => '6', '7' => '7', '8' => '8', '9' => '9', '10' => '10' ],
				'twelfths' => [ '1' => '1', '2' => '2', '3' => '3', '4' => '4', '5' => '5', '6' => '6', '7' => '7', '8' => '8', '9' => '9', '10' => '10', '11','11', '12' => '12' ],
			],
			'order' => [ '1' => '1', '2' => '2', '3' => '3', '4' => '4', '5' => '5', '6' => '6', '7' => '7', '8' => '8', '9' => '9', '10' => '10', '11','11', '12' => '12' ],
			'pad' => [
				'type' => [ '_' => 'inherited', 'airy' => 'airy (2em)', 'tight' => 'tight (1em)', 'no' => 'remove (0em)' ],
				'position' => [ '_' => 'All', 'L' => 'Left', 'R' => 'Right', 'T' => 'Top', 'B' => 'Bottom', 'LR' => 'Flanks/Sides', 'TB' => 'Ends/Head-foot' ],
			],
			'round' => [
				'type' => [ '_' => 'inherited', 'wide' => 'wide (2em)', 'tight' => 'tight (1em)', 'mini' => 'mini (0.5em)', 'no' => 'remove (0em)' ],
				'position' => [ '_' => 'All', 'L' => 'Left Side', 'R' => 'Right Side', 'T' => 'Top', 'B' => 'Bottom', 'TL' => 'Top Left', 'BL' => 'Bottom Left', 'TR' => 'Top Right', 'BR' => 'Bottom Right' ],
			],
		];
		$at_sizes = [ '320' => '320', '360' => '360', '375' => '375', '384' => '384', '390' => '390', '400' => '400', '414' => '414', '480' => '480', '568' => '568', '600' => '600', '640' => '640', '667' => '667', '695' => '695', '720' => '720', '736' => '736', '768' => '768', '800' => '800', '960' => '960', '1024' => '1024', '1280' => '1280', '1366' => '1366', '1440' => '1440' ];

		$flex_used = [
			'order' => '',
			'width' => '',
			'pad' => '',
			'round' => '',
			'at-sizes' => [],
		 ];
		$column_classes = explode( ' ', $column_class_str );

		//for now just get them in the right spot
		foreach ( $column_classes as $k => $class ) { // loop over class list
			foreach ( $setion_flex_options as $key => $item ) { // loop through options
				foreach ( $item as $subkey => $subitem ) { // loop though options values for match
					if ( is_array( $subitem ) && 0 === strpos( $class, $key ) ) { // if the key matches first then kick into the sub arrays
						foreach ( $subitem as $partkey => $partoptions ) {
							if ( false !== strpos( $class, $partkey ) ) {
								if ( false !== strpos( $class, '-at-' ) ) {
									$flex_used['at-sizes'][] = $class;
									break 3;
								} else {
									$flex_used[ $key ] = $class;
									break 3;
								}
							}
						}
					} elseif ( ! is_array( $subitem ) ) {
						if ( false !== strpos( $class, $subitem ) ) {
							if ( false !== strpos( $class, '-at-' ) ) {
								$flex_used['at-sizes'][] = $class;
								break 2;
							} else {
								$flex_used[ $key ] = $class;
								break 2;
							}
						}
					}
				}
			}
		}

		//var_dump( $flex_used );

		?>
		<style>
		.flex-attr-area{
			display:inline-block;
			float:left;
		}
		#start_add_fw_class{
			float:right;
		}
		</style>
		<h3>Flexwork class builder <button class="start_add_fw_class">Add New Class</button></br></h3>
			<div class="fw-builder">
				<div class="flexwork-type flex-attr-area">type:<br/>
					<select class="flexwork-type-select flex-builder-selector fb-type-chooser">
						<option value="">Select</option>
						<?php
						foreach ( $setion_flex_options as $key => $option ) {
							echo '<option value="' . $key . '"">' . $key . '</option>';
						}
						?>
					</select>
				</div>
				<div class="flexwork-width flex-attr-area">Width:<br/>
					<select class="flexwork-width-select flex-builder-selector fb-type">
						<option value="">Select</option>
						<?php
						foreach ( $setion_flex_options['width'] as $key => $option ) {
							echo '<option value="' . $key . '"">' . $key . '</option>';
						}
						?>
					</select>
				</div>
				<?php
				foreach ( $setion_flex_options['width'] as $key => $options ) {
					?>
					<div class="flexwork-<?php echo $key?> flex-attr-area"><?php echo $key?>:<br/>
						<select class="flexwork-<?php echo $key?>-select flex-builder-selector fb-type  match-width">
							<option value="">Select</option>
							<?php
							foreach ( $options as $subkey => $option ) {
								echo '<option value="' . $subkey . '"">' . $option . '</option>';
							}
							?>
						</select>
					</div>
				<?php
				}
				?>
				<div class="flexwork-order flex-attr-area">Order:<br/>
					<select class="flexwork-order-select flex-builder-selector fb-type">
						<option value="">Select</option>
						<?php
						foreach ( $setion_flex_options['order'] as $key => $option ) {
							echo '<option value="order-' . $key . '"">' . $key . '</option>';
						}
						?>
					</select>
				</div>
				<div class="flexwork-pad flex-attr-area">padding:<br/>
					<select class="flexwork-pad-type-select flex-builder-selector fb-type fb-with-child">
						<option value="">Select</option>
						<?php
						foreach ( $setion_flex_options['pad']['type'] as $key => $option ) {
							echo '<option value="' . $key . '"">' . $option . '</option>';
						}
						?>
					</select>
					<select class="flexwork-pad-position-select flex-builder-selector fb-type fb-with-child">
						<option value="">Select</option>
						<?php
						foreach ( $setion_flex_options['pad']['position'] as $key => $option ) {
							echo '<option value="' . $key . '"">' . $option . '</option>';
						}
						?>
					</select>
				</div>
				<div class="flexwork-round flex-attr-area">rounding:<br/>
					<select class="flexwork-round-type-select flex-builder-selector fb-type fb-with-child">
						<option value="">Select</option>
						<?php
						foreach ( $setion_flex_options['round']['type'] as $key => $option ) {
							echo '<option value="' . $key . '"">' . $option . '</option>';
						}
						?>
					</select>
					<select class="flexwork-round-position-select flex-builder-selector fb-type fb-with-child">
						<option value="">Select</option>
						<?php
						foreach ( $setion_flex_options['round']['position'] as $key => $option ) {
							echo '<option value="' . $key . '"">' . $option . '</option>';
						}
						?>
					</select>
				</div>
				<button class="fw_class_at">@</button>

				<div class="flexwork-at-sizes flex-attr-area">at size:<br/>
					<select class="flexwork-at-sizes-select flex-builder-selector fb-type fb-with-child">
						<option value="">Select</option>
						<?php
						foreach ( $at_sizes as $key => $option ) {
							echo '<option value="at-' . $key . '"">' . $option . '</option>';
						}
						?>
					</select>
				</div>
				<button class="fw_add_class">+ add</button>
			</div>

		<?php
		return "<input type='text' name='" . $field_name . "' class='fexwork-classes full-width' value='" . $column_class_str . "'/>";
	}
}

new Fais_Spine_Builder_Custom();


/**
 * Retrieve data for display in a column format for use in any front end
 * template.
 *
 * @param array $section_data   Data to be prepped for column output.
 * @param int   $columns_number Number of columns to retrieve.
 *
 * @return array Prepped data.
 */
function fais_spine_get_column_data( $section_data, $columns_number = 2 ) {
	$columns_order = array();
	if ( isset( $section_data['columns-order'] ) ) {
		$columns_order = $section_data['columns-order'];
	}

	$columns_data = array();
	if ( isset( $section_data['columns'] ) ) {
		$columns_data = $section_data['columns'];
	}

	$columns_array = array();
	if ( ! empty( $columns_order ) && ! empty( $columns_data ) ) {
		$count = 0;
		foreach ( $columns_order as $order => $key ) {
			$columns_array[ $order ] = $columns_data[ $key ];
			$count++;
			if ( $count >= $columns_number ) {
				break;
			}
		}
	}

	return $columns_array;
}


/**
 * Output the input field for section classes that is shared amongst admin templates.
 *
 * @param string $section_name         Current section being displayed.
 * @param array  $ttfmake_section_data Data associated with the section.
 */
function fais_spine_output_builder_section_classes( $section_name, $ttfmake_section_data ) {
	$section_classes = ( isset( $ttfmake_section_data['data']['section-classes'] ) ) ? $ttfmake_section_data['data']['section-classes'] : '';
	if( false !== strpos( trim($section_classes), 'gutter pad-top') ){
		$section_classes = implode('',explode('gutter pad-top',$section_classes));
	}
	?>
	<div class="wsuwp-builder-meta">
		<label for="<?php echo $section_name; ?>[section-classes]">Section Classes:</label><input type="text" id="<?php echo $section_name; ?>[section-classes]" class="wsuwp-builder-section-classes widefat" name="<?php echo $section_name; ?>[section-classes]" value="<?php echo esc_attr( $section_classes ); ?>" />
		<p class="description">Enter space delimited class names here to apply them to the <code>section</code> element represented by this builder area.</p>
	</div>
	<?php
}

/**
 * Output the input field for section label shared amongst admin templates. This label helps
 * to identify sections when minimized without requiring the entry of a title for the front-end.
 *
 * @param $section_name
 * @param $ttfmake_section_data
 */
function fais_spine_output_builder_section_label( $section_name, $ttfmake_section_data ) {
	$section_label = ( isset( $ttfmake_section_data['data']['label'] ) ) ? $ttfmake_section_data['data']['label'] : '';
	?>
	<div class="wsuwp-builder-meta">
		<label for="<?php echo $section_name; ?>[label]">Section Label:</label>
		<input type="text" id="<?php echo $section_name; ?>[label]" class="wsuwp-builder-section-label widefat" name="<?php echo $section_name; ?>[label]" value="<?php echo esc_attr( $section_label ); ?>" />
		<p class="description">Enter a label to use to identify sections without titles.</p>
	</div>
	<?php
}

/**
 * Output the input field for column classes and header levels used in column configuration.
 *
 * @param string $column_name
 * @param array $section_data
 * @param int $column
 */
function fais_spine_output_builder_column_classes( $column_name, $section_data, $column = false ) {
	$header_level_default = ( 'faiswsuwpheader' === $section_data['section']['id'] ) ? 'h1' : 'h2';
	if ( $column ) {
		$column_classes = ( isset( $section_data['data']['columns'][ $column ]['column-classes'] ) ) ? $section_data['data']['columns'][ $column ]['column-classes'] : '';
		$header_level   = ( isset( $section_data['data']['columns'][ $column ]['header-level'] ) ) ? $section_data['data']['columns'][ $column ]['header-level'] : $header_level_default;
		$column_background = ( isset( $section_data['data']['columns'][ $column ]['column-background-image'] ) ) ? $section_data['data']['columns'][ $column ]['column-background-image'] : '';
	} else {
		$column_classes = ( isset( $section_data['data']['column-classes'] ) ) ? $section_data['data']['column-classes'] : '';
		$header_level   = ( isset( $section_data['data']['header-level'] ) ) ? $section_data['data']['header-level'] : $header_level_default;
		$column_background = ( isset( $section_data['data']['column-background-image'] ) ) ? $section_data['data']['column-background-image'] : '';
	}

	?>
	<div class="wsuwp-builder-meta">
		<label for="<?php echo $column_name; ?>[column-classes]">Column Classes</label>
		<input type="text"
		       id="<?php echo $column_name; ?>[column-classes]"
		       name="<?php echo $column_name; ?>[column-classes]"
		       class="spine-builder-column-classes widefat"
		       value="<?php echo esc_attr( $column_classes ); ?>"/>
		<p class="description">Enter space delimited class names here to apply them to the <code>div.column</code>
			element represented by this builder area.</p>
	</div>
	<div class="wsuwp-builder-meta">
		<label for="<?php echo $column_name; ?>[header-level]">Header Level</label>
		<select id="<?php echo $column_name; ?>[header-level]"
		        name="<?php echo $column_name; ?>[header-level]"
		        class="">
		    <?php if ( 'wsuwpheader' === $section_data['section']['id'] ) : ?>
		    <option value="h1" <?php selected( esc_attr( $header_level ), 'h1' ); ?>>H1</option>
		    <?php endif; ?>
			<option value="h2" <?php selected( esc_attr( $header_level ), 'h2' ); ?>>H2</option>
			<option value="h3" <?php selected( esc_attr( $header_level ), 'h3' ); ?>>H3</option>
			<option value="h4" <?php selected( esc_attr( $header_level ), 'h4' ); ?>>H4</option>
		</select>
		<p class="description">This header will wrap the column title. <?php echo strtoupper( $header_level_default ); ?> by default.</p>
	</div>
	<div class="wsuwp-builder-meta">
		<label for="<?php echo $column_name; ?>[column-background-image]">Background Image</label>
		<input type="text"
		       id="<?php echo $column_name; ?>[column-background-image]"
		       name="<?php echo $column_name; ?>[column-background-image]"
		       class="spine-builder-column-classes widefat"
		       value="<?php echo esc_attr( $column_background ); ?>" />
		<p class="description">Enter the URL of an image to apply it as this column's background.</p>
	</div>
	<?php
}

/**
 * Output the input field for column classes and header levels used in column configuration.
 *
 * @param string $column_name
 * @param array $section_data
 * @param int $column
 */
function fais_spine_output_builder_column_type( $column_name, $section_data, $column = false, $column_order = false ) {
	$section_type = false;
	if ( isset( $section_data['data']['section-type'] ) ) {
		$section_type = $section_data['data']['section-type'];
	}

	$column_type_default = 'flex-column  grid-part ';

	if ( 'faiswsuwphalves' === $section_type ) {
		$column_size_defaults = [ 1 => 'fourths-2', 2 => ' fourths-2' ];
	} elseif ( 'faiswsuwpsidebarright' === $section_type ) {
		$column_size_defaults = [ 1 => 'fifths-3', 2 => 'fifths-2' ];
	} elseif ( 'faiswsuwpsidebarleft' === $section_type ) {
		$column_size_defaults = [ 1 => 'fifths-2', 2 => 'fifths-3' ];
	} elseif ( 'faiswsuwpthirds' === $section_type ) {
		$column_size_defaults = [ 1 => 'thirds-1',2 => 'thirds-1', 3 => 'thirds-1' ];
	} elseif ( 'faiswsuwpquarters' === $section_type ) {
		$column_size_defaults = [ 1 => 'fourths-1', 2 => 'fourths-1', 3 => 'fourths-1', 4 => 'fourths-1' ];
	} else {
		$column_size_defaults = [ 1 => 'fourths-4' ];
	}

	if ( false !== $column && false !== $column_order && isset( $column_size_defaults[ $column_order ] ) ) {
		$column_type_default .= $column_size_defaults[ $column_order ] .'  order-' . $column_order;
	}

	if ( $column ) {
		$column_type = ( isset( $section_data['data']['columns'][ $column ]['column-type'] ) && '' !== $section_data['data']['columns'][ $column ]['column-type'] ) ? $section_data['data']['columns'][ $column ]['column-type'] : $column_type_default;
	} else {
		$column_type = ( isset( $section_data['data']['column-type'] ) && '' !== $section_data['data']['column-type'] ) ? $section_data['data']['column-type'] : $column_type_default;
	}

	?>
	<div class="wsuwp-builder-meta">
		<?php echo Fais_Spine_Builder_Custom::build_flexwork_column_inputs( $column_name.'[column-type]', $column_type ); ?>
	</div>
	<?php
}


/**
 * Output the input field for selection flex type and header levels used in column configuration.
 *
 * @param string $column_name
 * @param array $section_data
 * @param int $column
 */
function fais_spine_output_builder_section_flextree( $section_name, $ttfmake_section_data ) {
	if ( isset( $ttfmake_section_data['data']['section-flextype'] ) && '' !== $ttfmake_section_data['data']['section-flextype'] ) {
		$current = $ttfmake_section_data['data']['section-flextype'];
	} else {
		$current = 'flex-row items-start';
	}

	if ( isset( $ttfmake_section_data['data']['section-attr'] ) && '' !== $ttfmake_section_data['data']['section-attr'] ) {
		$current_attr = $ttfmake_section_data['data']['section-attr'];
	} else {
		$current_attr = '';
	}

	if ( isset( $ttfmake_section_data['data']['section-position'] ) && '' !== $ttfmake_section_data['data']['section-position'] ) {
		$current_position = $ttfmake_section_data['data']['section-position'];
	} else {
		$current_position = 'content';
	}

	?>


	<div class="wsuwp-builder-meta">
		<label for="<?php echo $section_name; ?>[section-position]">Section Bins</label>
		<select id="<?php echo $section_name; ?>[section-position]"
		        name="<?php echo $section_name; ?>[section-position]"
		        class="">
		    <option value="before" <?php selected( esc_attr( $current_position ), 'before' ); ?>>Before Content</option>
			<option value="content" <?php selected( esc_attr( $current_position ), 'content' ); ?>>Content</option>
			<option value="after" <?php selected( esc_attr( $current_position ), 'after' ); ?>>After Content</option>
		</select>
		<p class="description">Set the bins to put the section in. `<?php echo strtoupper( 'content' ); ?>` by default.  It will still output in the order set, but only in the bin it is set to.</p>
	</div>
	<div class="wsuwp-builder-meta">
		<?php echo Fais_Spine_Builder_Custom::build_flexwork_sectional_inputs( $section_name.'[section-flextype]', $current ); ?>
		<p><b>Note:</b> Editing this edit by hand with out the builder is only advised if you are familar with css and the framework of Flexwork</p>
	</div>
	<div class="wsuwp-builder-meta">
	<h3>Section Attribute area</h3>
		<textarea name="<?php echo $section_name?>[section-attr]" placeholder="use this with great caution" style="margin-top: 0px;margin-bottom: 0px;height: 30px;width: 100%;"><?php echo stripslashes( $current_attr ) ?></textarea>

		<p><b>Note:</b> You're adding attributes like <code>data-FOO="bar"</code> to the block's html tag.</p>
	</div>


	<?php
}





/**
 * Output an input field to capture background images.
 *
 * @param $section_name
 * @param $ttfmake_section_data
 */
function fais_spine_output_builder_section_background( $section_name, $ttfmake_section_data ) {
	$section_background        = ( isset( $ttfmake_section_data['data']['background-img'] ) ) ? $ttfmake_section_data['data']['background-img'] : '';
	$section_mobile_background = ( isset( $ttfmake_section_data['data']['background-img'] ) ) ? $ttfmake_section_data['data']['background-mobile-img'] : '';

	?>
	<div class="wsuwp-builder-meta">
		<label for="<?php echo $section_name; ?>[background-img]">Background Image</label>
		<input type="text"
		       class="wsuwp-builder-section-image widefat"
		       id="<?php echo $section_name; ?>[background-img]"
		       name="<?php echo $section_name; ?>[background-img]"
		       value="<?php echo $section_background; ?>"/>
		<br/>
		<label for="<?php echo $section_name; ?>[background-mobile-img]">Mobile Background Image</label>
		<input type="text"
		       class="wsuwp-builder-section-image widefat"
		       id="<?php echo $section_name; ?>[background-mobile-img]"
		       name="<?php echo $section_name; ?>[background-mobile-img]"
		       value="<?php echo $section_mobile_background; ?>"/>
		<p class="description">Mobile background images are used for display widths narrower than 792px.</p>
		<p class="description">Background images on sections are an in progress feature. :)</p>
	</div>
	<?php
}


if ( is_admin() ) {
class Custom_TTFMAKE_Builder_Save extends TTFMAKE_Builder_Save {

	/**
	 * Initiate actions.
	 *
	 * @since  1.0.0.
	 *
	 * @return TTFMAKE_Builder_Save
	 */
	public function __construct() {
		// Only add filters when the builder is being saved
		if ( isset( $_POST['ttfmake-builder-nonce'] ) && wp_verify_nonce( $_POST['ttfmake-builder-nonce'], 'save' ) && isset( $_POST['ttfmake-section-order'] ) ) {
			remove_filter( 'wp_insert_post_data', 'wp_insert_post_data', 31 );
			// Combine the input into the post's content
			add_filter( 'wp_insert_post_data', array( $this, 'custom_wp_insert_post_data' ), 32, 2 );
		}
	}


	public function print_sections( $data ) {
		// For each sections, render it using the template
		foreach ( $data as $section ) {
			global $ttfmake_section_data, $ttfmake_sections;
			$ttfmake_section_data = $section;
			$ttfmake_sections     = $data;

			// Get the registered sections
			$registered_sections = ttfmake_get_sections();

			// Get the template for the section
			ttfmake_load_section_template(
				$registered_sections[ $section['section-type'] ]['display_template'],
				$registered_sections[ $section['section-type'] ]['path']
			);

			// Cleanup the global
			unset( $GLOBALS['ttfmake_section_data'] );
		}

	}

	/**
	 * On post save, use a theme template to generate content from metadata.
	 *
	 * @since  1.0.0.
	 *
	 * @param  array    $data       The processed post data.
	 * @param  array    $postarr    The raw post data.
	 * @return array                Modified post data.
	 */
	public function custom_wp_insert_post_data( $data, $postarr ) {
		if ( ! ttfmake_will_be_builder_page() || ! isset( $_POST['ttfmake-builder-nonce'] ) || ! wp_verify_nonce( $_POST['ttfmake-builder-nonce'], 'save' ) ) {
			return $data;
		}

		// Don't do anything during autosave
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return $data;
		}

		// Only check permissions for pages since it can only run on pages
		if ( ! current_user_can( 'edit_page', get_the_ID() ) ) {
			return $data;
		}

		/**
		 * Filter the section data.
		 *
		 * @since 1.2.3.
		 *
		 * @param array    $data   The sanitized data.
		 */
		$sanitized_sections = apply_filters( 'make_insert_post_data_sections', $this->get_sanitized_sections() );

		// The data has been deleted and can be removed
		if ( empty( $sanitized_sections ) ) {
			$data['post_content'] = '';
			return $data;
		}

		// Generate the post content
		$post_content = $this->set_generate_post_content( $sanitized_sections );

		// Sanitize and set the content
		kses_remove_filters();
		$data['post_content'] = sanitize_post_field( 'post_content', $post_content, get_the_ID(), 'db' );
		kses_init_filters();

		return $data;
	}

	/**
	 * Based on section data, generate a post's post_content.
	 *
	 * @since  1.0.4.
	 *
	 * @param  array     $data    Data for sections used to comprise a page's post_content.
	 * @return string             The post content.
	 */
	public function set_generate_post_content( $data ) {

		//var_dump( $data );die();
		// Run wpautop when saving the data
		add_filter( 'make_the_builder_content', 'wpautop' );

		// Handle oEmbeds correctly
		add_filter( 'make_the_builder_content', array( $this, 'embed_handling' ), 8 );
		add_filter( 'embed_handler_html', array( $this, 'embed_handler_html' ) , 10, 3 );
		add_filter( 'embed_oembed_html', array( $this, 'embed_oembed_html' ) , 10, 4 );

		// Remove editor image constraints while rendering section data.
		add_filter( 'editor_max_image_size', array( &$this, 'remove_image_constraints' ) );

		$before_content_area = [];
		$content_area = [];
		$after_content_area = [];
		// For each sections, render it using the template
		foreach ( $data as $id => $section ) {
			if ( isset( $section['section-position'] ) ) {
				if ( 'before' === $section['section-position'] ) {
					$before_content_area[ $id ] = $section;
				} else if ( 'after' === $section['section-position'] ) {
					$after_content_area[ $id ] = $section;
				} else {
					$content_area[ $id ] = $section;
				}
			} else {
				$content_area[ $id ] = $section;
			}
		}

		// Start the output buffer to collect the contents of the templates
		ob_start();

		$this->print_sections( $before_content_area );
		echo '<div id="content_area" class="flex-column">';
		$this->print_sections( $content_area );
		echo '</div>';
		$this->print_sections( $after_content_area );

		// Get the rendered templates from the output buffer
		$post_content = ob_get_clean();

		// Allow constraints again after builder data processing is complete.
		remove_filter( 'editor_max_image_size', array( &$this, 'remove_image_constraints' ) );

		/**
		 * Filter the generated post content.
		 *
		 * This content is the full HTML version of the content that will be saved as "post_content".
		 *
		 * @since 1.2.3.
		 *
		 * @param string    $post_content    The fully generated post content.
		 * @param array     $data            The data used to generate the content.
		 */
		return apply_filters( 'make_generate_post_content', $post_content, $data );
	}
}

new Custom_TTFMAKE_Builder_Save();

}


