<?php
/**
 * page by page Customizer Control
 *
 * something somthing
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this Multi Color Picker. If not, see <http://www.gnu.org/licenses/>.
 */
class Customize_Page_by_Page_Control extends WP_Customize_Control {

	/**
	 * Official control name.
	 */
	public $type = 'page-by-page';

	/**
	 * The array of color data.
	 */
	public $pages_data;

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
		wp_enqueue_script( 'page-by-page', get_stylesheet_directory_uri() . '/includes/assets/page-by-page/control.js', array( 'jquery', 'wp-color-picker' ), '1.0.0', true );
		wp_enqueue_style( 'page-by-page', get_stylesheet_directory_uri() . '/includes/assets/page-by-page/style.css', array( 'wp-color-picker' ), '1.0.0' );
	}

	/**
	 * Render the control.
	 */
	public function render_content() {

		if ( isset( $this->label ) && '' !== $this->label ) {
			echo '<span class="customize-control-title">' . sanitize_text_field( $this->label ) . '</span>';
		}

		if ( isset( $this->description ) && '' !== $this->description ) {
			echo '<span class="description customize-control-description">' . sanitize_text_field( $this->description ) . '</span>';
		}

		// Output the div that will wrap our picker triggers.
		echo '<style></style><div class="multi-s-picker-triggers"></div>';

		// Loop over settings to output the actual inputs.
		foreach ( $this->settings as $key => $setting ) {

			// Store color data associated with the current setting.
			$page_data = $this->page_data[ $setting->id ];

			// Process the palette. Default to 'true' if no palette was passed in.
			$palette = ( isset( $page_data['palette'] ) ) ? $page_data['palette'] : true;
			if ( is_array( $palette ) ) {
				$palette = implode( '|', $palette );
			} elseif ( true === $palette || 'true' === $palette ) {
				$palette = 'true';
			} else {
				$palette = 'false';
			}

			// Grab the rest of the setting data. Define defaults to make everything except 'label' optional.
			$label = $page_data['label'];
			$default = ( isset( $page_data['default'] ) ) ? $page_data['default'] : '#000000';
			$show_opacity = ( isset( $page_data['show_opacity'] ) ) ? $page_data['show_opacity'] : 'true';

			?>
			<input type="text" class="multi-color-control" data-show-opacity="<?php echo esc_attr( $show_opacity ); ?>" data-palette="<?php echo $palette ?>" data-title="<?php echo esc_attr( $label ); ?>" data-default-color="<?php echo esc_attr( $default ); ?>" value="<?php echo $this->settings[ $key ]->value(); ?>" <?php echo $this->get_link( $key ); ?> />
			<?php
		}

	}
}
