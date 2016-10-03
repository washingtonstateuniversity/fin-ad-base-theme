<?php
/**
 * Multi Color Picker Customizer Control
 *
 * This control allows registering a group of color settings to a single control,
 * and it uses a custom version of the stock WP color picker that supports RGBa
 * color values and includes an opacity slider.
 *
 * This Multi Color Picker is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this Multi Color Picker. If not, see <http://www.gnu.org/licenses/>.
 */
class Customize_Multi_Color_Control extends WP_Customize_Control {

	/**
	 * Official control name.
	 */
	public $type = 'multi-color';

	/**
	 * The array of color data.
	 */
	public $color_data;

	/**
	 * The label for this control group.
	 */
	public $label;

	/**
	 * Enqueue scripts and styles.
	 *
	 * Ideally these would get registered and given proper paths before this control object
	 * gets initialized, then we could simply enqueue them here, but for completeness as a
	 * stand alone class we'll register and enqueue them here.
	 */
	public function enqueue() {
		wp_enqueue_script( 'multi-color-picker', get_stylesheet_directory_uri() . '/includes/assets/multi-color-picker/multi-color-picker.js', array( 'jquery', 'wp-color-picker' ), '1.0.0', true );
		wp_enqueue_style( 'multi-color-picker', get_stylesheet_directory_uri() . '/includes/assets/multi-color-picker/multi-color-picker.css', array( 'wp-color-picker' ), '1.0.0' );
	}

	/**
	 * Render the control.
	 */
	public function render_content() {

		if ( isset( $this->label ) && '' !== $this->label ) {
			esc_html_e( '<span class="customize-control-title">' . sanitize_text_field( $this->label ) . '</span>' );
		}

		if ( isset( $this->description ) && '' !== $this->description ) {
			esc_html_e( '<span class="description customize-control-description">' . sanitize_text_field( $this->description ) . '</span>' );
		}

		// Output the div that will wrap our picker triggers.
		esc_html_e(  '<style>

.customize-control-multi-color {
    position: relative;
}
.wp-picker-container.open {
    position: absolute;
    top: 0;
    left: -10px;
    background: #dadada;
    padding: 5px 10px;
    border: 1px solid #696969;
}
.wp-picker-container.open .close:before {
    content: "\f00d";
    width: 46px;
    height: 17px;
    z-index: 9999;
    position: absolute;
    top: 0;
    right: 0px;
}
.wp-picker-container.open .close {
    background: #696969;
    border: 1px solid #696969;
    display: inline-block;
    font: normal normal normal 14px/1 FontAwesome;
    font-size: inherit;
    text-rendering: auto;
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
    width: 46px;
    height: 17px;
    z-index: 9999;
    position: absolute;
    top: 0;
    right: 0px;
    display: block;
    color: #ddd;
    text-align: center;
    margin: 2px;
    padding-top: 3px;
    cursor: pointer;
}
ul.grouplist li.multi-color-picker-trigger {
    float: none;
    display: block;
    width: 100%;
    background: none;
    box-shadow: none;
}
ul.grouplist li.multi-color-picker-trigger a.wp-color-result {
    width: 12px;
    box-shadow: 0 0 5px rgba(0,0,0,0.4) inset;
    margin-right: 9px;
}

.multi-color-picker-triggers ul.grouplist li.multi-color-picker-trigger:hover {
    border: 1px solid #868686;
}
.multi-color-picker-triggers ul.grouplist li.multi-color-picker-trigger {
    border: 1px solid #000;
    color: #555;
    border-color: #ccc;
    background: #f7f7f7;
    -webkit-box-shadow: 0 1px 0 #ccc;
    box-shadow: 0 1px 0 #ccc;
    vertical-align: top;
    line-height: 24px;
    cursor:pointer;
}


</style><div class="multi-color-picker-triggers"></div>');

		// Output the div that will wrap our picker triggers.
		//echo '<ul class="color_group">';

		// Loop over the color settings to output the actual inputs.
		foreach ( $this->settings as $key => $setting ) {

			// Store color data associated with the current setting.
			$color_data = $this->color_data[ $setting->id ];

			// Process the palette. Default to 'true' if no palette was passed in.
			$palette = ( isset( $color_data['palette'] ) ) ? $color_data['palette'] : true;
			if ( is_array( $palette ) ) {
				$palette = implode( '|', $palette );
			} elseif ( true === $palette || 'true' === $palette ) {
				$palette = 'true';
			} else {
				$palette = 'false';
			}

			// Grab the rest of the setting data. Define defaults to make everything except 'label' optional.
			$label = $color_data['label'];
			$default = ( isset( $color_data['default'] ) ) ? $color_data['default'] : '#000000';
			$show_opacity = ( isset( $color_data['show_opacity'] ) ) ? $color_data['show_opacity'] : 'true';

			?>
			<input type="text" class="multi-color-control" data-show-opacity="<?php echo esc_attr( $show_opacity ); ?>" data-palette="<?php echo esc_attr( $palette ) ?>" data-title="<?php echo esc_attr( $label ); ?>" data-default-color="<?php echo esc_attr( $default ); ?>" value="<?phpecho esc_attr( this->settings[ $key ]->value()); ?>" <?php echo esc_html( $this->get_link( $key ) ); ?> />
			<?php
		}
// Output the div that will wrap our picker triggers.
		//echo '</ul>';
	}
}
