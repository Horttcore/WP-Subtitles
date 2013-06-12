<?php
/**
 * Plugin Name: Subtitle
 * Plugin URI: http://horttcore.de
 * Description: Subtitle
 * Version: 1.1
 * Author: Ralf Hortt
 * Author URI: http://horttcore.de
 * License: GPL2
 *
 * @package  WordPress-Subtitle
 * @author   Ralf Hortt
 * @license  GPL-2.0+
 * @link     htt://horttcore.de
 * @version  1.1
 * @since    1.0
*/



//avoid direct calls to this file, because now WP core and framework has been used
if ( !function_exists('add_action') ) :

	header('Status: 403 Forbidden');
	header('HTTP/1.1 403 Forbidden');
	exit();

endif;



/**
* Subtitle class
*/
class Subtitle
{



	/**
	 *
	 * Constructor
	 *
	 */
	function __construct()
	{
		add_action( 'admin_print_styles-post-new.php', array( $this, 'enqueue_styles' ) );
		add_action( 'admin_print_styles-post.php', array( $this, 'enqueue_styles' ) );
		add_action( 'edit_form_after_title', array( $this, 'edit_form_after_title' ) );
		add_action( 'save_post', array( $this, 'save_post' ) );

		add_post_type_support( 'post', 'subtitle' );
		add_post_type_support( 'page', 'subtitle' );

		load_plugin_textdomain( 'hc-subtitle', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
	}



	/**
	 * Add subtitle field
	 *
	 * @access public
	 * @return void
	 * @author Ralf Hortt
	 **/
	public function edit_form_after_title()
	{
		global $post;

		$post_type = ( isset( $_GET['post_type'] ) ) ? sanitize_text_field( $_GET['post_type'] ) : 'post';

		if ( post_type_supports( $post_type, 'subtitle' ) )
			$this->subtitle_field( $post );
	}



	/**
	 * Enqueue Styles
	 *
	 * @access public
	 * @return void
	 * @author Ralf Hortt
	 **/
	public function enqueue_styles()
	{
		wp_enqueue_style( 'hc-subtitle', plugins_url( 'css/hc-subtitle.css', __FILE__ ) );
	}



	/**
	 * Get Subtitle
	 *
	 * @static
	 * @param int $post_id Post ID
	 * @return str Subtitle
	 * @author Ralf Hortt
	 **/
	static function get_subtitle( $post_id = FALSE )
	{
		$post_id = ( FALSE !== $post_id ) ? $post_id : get_the_ID();
		return apply_filters( 'the_subtitle', get_post_meta( $post_id, '_subtitle', TRUE ) );
	}



	/**
	 *
	 * Save subtitle
	 *
	 * @access public
	 * @return void
	 * @author Ralf Hortt
	 */
	public function save_post( $post_id ) {
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
			return;

		if ( !isset( $_POST['save-subtitle'] ) || !wp_verify_nonce( $_POST['save-subtitle'], plugin_basename( __FILE__ ) ) )
			return;

		if ( '' != $_POST['hc-subtitle'] ) :
			update_post_meta( $post_id, '_subtitle', sanitize_text_field( $_POST['hc-subtitle'] ) );
		else :
			delete_post_meta( $post_id, '_subtitle' );
		endif;

	}



	/**
	 * Metabox content
	 *
	 * @access public
	 * @return void
	 * @author Ralf Hortt
	 */
	public function subtitle_field( $post )
	{
		$subtitle = $this->get_subtitle( $post->ID );
		?>
		<input type="text" autocomplete="off" id="hc-subtitle" value="<?php echo esc_attr( $subtitle ) ?>" name="hc-subtitle" placeholder="<?php _e( 'Enter subtitle here', 'hc-subtitle' ); ?>">
		<?php
		wp_nonce_field( plugin_basename( __FILE__ ), 'save-subtitle' );
	}



	/**
	 * Display Subtitle
	 *
	 * @static
	 * @return void
	 * @author Ralf Hortt
	 */
	static function the_subtitle()
	{
		echo get_subtitle( get_the_ID() );
	}



}
new Subtitle();



/**
 * Getter: Subtitle
 *
 * @param int $post_id Post ID
 * @return str Subtitle
 * @author Ralf Hortt
 **/
function get_subtitle( $post_id = FALSE )
{
	return Subtitle::get_subtitle( $post_id );
}



/**
 * Conditional Tag: Subtitle
 *
 * @param int $post_id Post ID
 * @return bool
 * @author Ralf Hortt
 **/
function has_subtitle( $post_id = FALSE )
{
	if ( '' !== Subtitle::get_subtitle( $post_id ) )
		return TRUE;
	else
		return FALSE;
}



/**
 * Template Tag: Display Subtitle
 *
 * @return void
 * @author Ralf Hortt
 */
function the_subtitle()
{
	echo Subtitle::get_subtitle();
}