<?php
/*
Plugin Name: Subtitle
Plugin URI: http://horttcore.de
Description: Subtitle
Version: 0.1
Author: Ralf Hortt
Author URI: http://horttcore.de
License: GPL2
*/



//avoid direct calls to this file, because now WP core and framework has been used
if ( !function_exists('add_action') ) :

	header('Status: 403 Forbidden');
	header('HTTP/1.1 403 Forbidden');
	exit();

else :
	
	// plugin definitions
	define('HC_ST_BASE_DIR', '/'.PLUGINDIR.'/hc-subtitle');
	define('HC_ST_CSS', HC_ST_BASE_DIR.'/css');
	define('HC_ST_JS', HC_ST_BASE_DIR.'/javascript');
	
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
		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );
		add_action( 'save_post', array( $this, 'save_post' ) );
		add_action( 'init', array( $this, 'init' ) );

		add_action( 'admin_print_scripts-post-new.php', array( $this, 'enqueue_scripts' ) );
		add_action( 'admin_print_styles-post-new.php', array( $this, 'enqueue_styles' ) );
		add_action( 'admin_print_scripts-post.php', array( $this, 'enqueue_scripts' ) );
		add_action( 'admin_print_styles-post.php', array( $this, 'enqueue_styles' ) );

		register_uninstall_hook( __FILE__, array( $this, 'uninstall' ) );
	}



	/**
	 * Add meta boxes
	 *
	 * @access public
	 * @return void
	 * @author Ralf Hortt
	 **/
	public function add_meta_boxes()
	{
		$post_types = get_post_types();
		
		if ( $post_types ) :
		
			foreach ( $post_types as $post_type ) :
			
				if ( post_type_supports( $post_type, 'subtitle' ) ) :
				
					add_meta_box( 'subtitle-metabox', __( 'Subtitle', 'hc-subtitle' ), array( $this, 'subtitle_metabox' ), $post_type );
				
				endif;
			
			endforeach;
		
		endif;
	}



	/**
	 * Enqueue Scripts
	 *
	 * @access public
	 * @return void
	 * @author Ralf Hortt
	 **/
	public function enqueue_scripts()
	{
		wp_enqueue_script( 'hc-subtitle', HC_ST_JS . '/hc-subtitle.js' );
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
		wp_enqueue_style( 'hc-subtitle', HC_ST_CSS . '/hc-subtitle.css' );
	}


	/**
	 * Init
	 *
	 * @access public
	 * @return void
	 * @author Ralf Hortt
	 **/
	public function init()
	{
		add_post_type_support( 'page', 'subtitle' );
		load_plugin_textdomain( 'hc-subtitle' , false, 'hc-subtitle/language' );
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

		if ( !wp_verify_nonce( $_POST['save-subtitle'], plugin_basename( __FILE__ ) ) )
			return;

		if ( '' != $_POST['hc-subtitle'] ) :
			update_post_meta( $post_id, '_subtitle', $_POST['hc-subtitle'] );
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
	public function subtitle_metabox( $post )
	{
		$subtitle = $this->get_subtitle( $post->ID );
		?>
		<div id="subtitlewrap">
			<input type="text" autocomplete="off" id="hc-subtitle" value="<?php echo $subtitle ?>" name="hc-subtitle" placeholder="<?php _e( 'Subtitle', 'hc-subtitle' ); ?>">
		</div>
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



	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	static function uninstall()
	{
		$wpdb->query( "DELETE FROM " . $wpdb->postmeta . " WHERE meta_key = '_subtitle'" );
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
 * Template Tag: Display Subtitle
 *
 * @return void
 * @author Ralf Hortt
 */
function the_subtitle()
{
	echo Subtitle::get_subtitle();
}