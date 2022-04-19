<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://www.linkedin.com/in/hamzahabibdev/
 * @since             1.0.0
 * @package           Streamwall
 *
 * @wordpress-plugin
 * Plugin Name:       STREAMWALL
 * Plugin URI:        https://github.com/hamzahabib2000/
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            Hamza
 * Author URI:        https://www.linkedin.com/in/hamzahabibdev/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       streamwall
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'STREAMWALL_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-streamwall-activator.php
 */
function activate_streamwall() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-streamwall-activator.php';
	Streamwall_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-streamwall-deactivator.php
 */
function deactivate_streamwall() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-streamwall-deactivator.php';
	Streamwall_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_streamwall' );
register_deactivation_hook( __FILE__, 'deactivate_streamwall' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-streamwall.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_streamwall() {

	$plugin = new Streamwall();
	$plugin->run();

}
run_streamwall();

/**
 * Registers a new post type
 * @uses $wp_post_types Inserts new post type object into the list
 *
 * @param string  Post type key, must not exceed 20 characters
 * @param array|string  See optional args description above.
 * @return object|WP_Error the registered post type object, or an error object
 */
function hh_stream() {
	$labels = array(
		'name'               => __( 'Streams', 'text-domain' ),
		'singular_name'      => __( 'Stream', 'text-domain' ),
		'add_new'            => _x( 'Add New Stream', 'text-domain', 'text-domain' ),
		'add_new_item'       => __( 'Add New Stream', 'text-domain' ),
		'edit_item'          => __( 'Edit Stream', 'text-domain' ),
		'new_item'           => __( 'New Stream', 'text-domain' ),
		'view_item'          => __( 'View Stream', 'text-domain' ),
		'search_items'       => __( 'Search Streams', 'text-domain' ),
		'not_found'          => __( 'No Streams found', 'text-domain' ),
		'not_found_in_trash' => __( 'No Streams found in Trash', 'text-domain' ),
		'parent_item_colon'  => __( 'Parent Stream:', 'text-domain' ),
		'menu_name'          => __( 'Streams', 'text-domain' ),
	);

	$args = array(
		'labels'              => $labels,
		'hierarchical'        => false,
		'description'         => 'description',
		'taxonomies'          => array(),
		'public'              => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'show_in_admin_bar'   => true,
		'menu_position'       => null,
		'menu_icon'           => null,
		'show_in_nav_menus'   => true,
		'publicly_queryable'  => true,
		'exclude_from_search' => false,
		'has_archive'         => true,
		'query_var'           => true,
		'can_export'          => true,
		'rewrite'             => true,
		'capability_type'     => 'post',
		'supports'            => array(
			'title',
			// 'editor',
			// 'author',
			'thumbnail',
			// 'excerpt',
			// 'custom-fields',
			// 'trackbacks',
			// 'comments',
			// 'revisions',
			// 'page-attributes',
			// 'post-formats',
		),
	);

	register_post_type( 'stream', $args );
}

add_action( 'init', 'hh_stream' );


// hh_stream

class hh_Meta_Box {

	public function __construct() {

		if ( is_admin() ) {
			add_action( 'load-post.php',     array( $this, 'init_metabox' ) );
			add_action( 'load-post-new.php', array( $this, 'init_metabox' ) );
		}

	}

	public function init_metabox() {

		add_action( 'add_meta_boxes', array( $this, 'add_metabox'  )        );
		add_action( 'save_post',      array( $this, 'save_metabox' ), 10, 2 );

	}

	public function add_metabox() {

		add_meta_box(
			'hh_streamMetaBox',
			__( 'Stream Info', 'text_domain' ),
			array( $this, 'render_metabox' ),
			'stream',
			'advanced',
			'default'
		);

	}

	public function render_metabox( $post ) {

		// Add nonce for security and authentication.
		wp_nonce_field( 'car_nonce_action', 'car_nonce' );
		// Source, Platform, City, State, Type, View, Link, Status, Notes, Title, Embed, Link
		// Retrieve an existing value from the database.
		$streamSource = get_post_meta( $post->ID, '_streamSource', true );
		$streamPlatform = get_post_meta( $post->ID, '_streamPlatform', true );
		$streamCity = get_post_meta( $post->ID, '_streamCity', true );
		$streamState = get_post_meta( $post->ID, '_streamState', true );
		$streamType = get_post_meta( $post->ID, '_streamType', true );
		$streamView = get_post_meta( $post->ID, '_streamView', true );
		$streamLink = get_post_meta( $post->ID, '_streamLink', true );
		$streamStatus = get_post_meta( $post->ID, '_streamStatus', true );
		$streamNotes = get_post_meta( $post->ID, '_streamNotes', true );
		$streamTitle = get_post_meta( $post->ID, '_streamTitle', true );
		$streamEmbedLink = get_post_meta( $post->ID, '_streamEmbedLink', true );

		// Set default values.
		if( empty( $streamSource ) ) $streamSource = '';
		if( empty( $streamPlatform ) ) $streamPlatform = '';
		if( empty( $streamCity ) ) $streamCity = '';
		if( empty( $streamState ) ) $streamState = '';
		if( empty( $streamType ) ) $streamType = '';
		if( empty( $streamView ) ) $streamView = '';
		if( empty( $streamLink ) ) $streamLink = '';
		if( empty( $streamStatus ) ) $streamStatus = '';
		if( empty( $streamNotes ) ) $streamNotes = '';
		if( empty( $streamTitle ) ) $streamTitle = '';
		if( empty( $streamEmbedLink ) ) $streamEmbedLink = '';

		// Form fields.
		echo '<table class="form-table">';
		echo '	<tr>';
		echo '		<th><label for="streamSource" class="streamSource_label">' . __( 'Source', 'text_domain' ) . '</label></th>';
		echo '		<td>';
		echo '			<input type="text" id="streamSource" name="streamSource" class="streamSource_field" placeholder="' . esc_attr__( '', 'text_domain' ) . '" value="' . esc_attr__( $streamSource ) . '" style="width:100%;">';
		echo '			<p class="description">' . __( '', 'text_domain' ) . '</p>';
		echo '		</td>';
		echo '	</tr>';
		echo '	<tr>';
		echo '		<th><label for="streamPlatform" class="streamPlatform_label">' . __( 'Platform', 'text_domain' ) . '</label></th>';
		echo '		<td>';
		// echo '			<input type="text" id="streamPlatform" name="streamPlatform" class="streamPlatform_field" placeholder="' . esc_attr__( '', 'text_domain' ) . '" value="' . esc_attr__( $streamPlatform ) . '">';
		echo '			<select id="streamPlatform" name="streamPlatform" class="streamPlatform_field" >';
		echo '			<option value="facebook" '.(esc_attr__( $streamPlatform ) == 'facebook'?"Selected":"").'>Facebook</option>';
		echo '			<option value="youtube" '.(esc_attr__( $streamPlatform ) == 'youtube'?"Selected":"").'>Youtube</option>';
		echo '			<option value="twitch" '.(esc_attr__( $streamPlatform ) == 'twitch'?"Selected":"").'>Twitch</option>';
		echo '			</select>';
		echo '			<p class="description">' . __( '', 'text_domain' ) . '</p>';
		echo '		</td>';
		echo '	</tr>';
		echo '	<tr>';
		echo '		<th><label for="streamCity" class="streamCity_label">' . __( 'City', 'text_domain' ) . '</label></th>';
		echo '		<td>';
		echo '			<input type="text" id="streamCity" name="streamCity" class="streamCity_field" placeholder="' . esc_attr__( '', 'text_domain' ) . '" value="' . esc_attr__( $streamCity ) . '" style="width:100%;">';
		echo '			<p class="description">' . __( '', 'text_domain' ) . '</p>';
		echo '		</td>';
		echo '	</tr>';
		echo '	<tr>';
		echo '		<th><label for="streamState" class="streamState_label">' . __( 'State', 'text_domain' ) . '</label></th>';
		echo '		<td>';
		echo '			<input type="text" id="streamState" name="streamState" class="streamState_field" placeholder="' . esc_attr__( '', 'text_domain' ) . '" value="' . esc_attr__( $streamState ) . '" style="width:100%;">';
		echo '			<p class="description">' . __( '', 'text_domain' ) . '</p>';
		echo '		</td>';
		echo '	</tr>';
		echo '	<tr>';
		echo '		<th><label for="streamType" class="streamType_label">' . __( 'Type', 'text_domain' ) . '</label></th>';
		echo '		<td>';
		echo '			<input type="text" id="streamType" name="streamType" class="streamType_field" placeholder="' . esc_attr__( '', 'text_domain' ) . '" value="' . esc_attr__( $streamType ) . '" style="width:100%;">';
		echo '			<p class="description">' . __( '', 'text_domain' ) . '</p>';
		echo '		</td>';
		echo '	</tr>';
		echo '	<tr>';
		echo '		<th><label for="streamView" class="streamView_label">' . __( 'View', 'text_domain' ) . '</label></th>';
		echo '		<td>';
		echo '			<input type="text" id="streamView" name="streamView" class="streamView_field" placeholder="' . esc_attr__( '', 'text_domain' ) . '" value="' . esc_attr__( $streamView ) . '" style="width:100%;">';
		echo '			<p class="description">' . __( '', 'text_domain' ) . '</p>';
		echo '		</td>';
		echo '	</tr>';
		echo '	<tr>';
		echo '		<th><label for="streamLink" class="streamLink_label">' . __( 'Link', 'text_domain' ) . '</label></th>';
		echo '		<td>';
		echo '			<input type="text" id="streamLink" name="streamLink" class="streamLink_field" placeholder="' . esc_attr__( '', 'text_domain' ) . '" value="' . esc_attr__( $streamLink ) . '" style="width:100%;">';
		echo '			<p class="description">' . __( '', 'text_domain' ) . '</p>';
		echo '		</td>';
		echo '	</tr>';
		echo '	<tr>';
		echo '		<th><label for="streamStatus" class="streamStatus_label">' . __( 'Status', 'text_domain' ) . '</label></th>';
		echo '		<td>';
		// echo '			<input type="text" id="streamStatus" name="streamStatus" class="streamStatus_field" placeholder="' . esc_attr__( '', 'text_domain' ) . '" value="' . esc_attr__( $streamStatus ) . '" style="width:100%;">';
		echo '		<select name="streamStatus" id="streamStatus" class="form-control streamStatus_field">';
		echo '			<option value="">Select</option>';
		echo '			<option value="live" '.(esc_attr__( $streamStatus ) == 'live' ? "selected":"" ).'>Live</option>';
		echo '			<option value="offline" '.(esc_attr__( $streamStatus ) == 'offline' ? "selected":"" ).'>Offline</option>';
		echo '		</select>';
		echo '			<p class="description">' . __( '', 'text_domain' ) . '</p>';
		echo '		</td>';
		echo '	</tr>';
		echo '	<tr>';
		echo '		<th><label for="streamNotes" class="streamNotes_label">' . __( 'Notes', 'text_domain' ) . '</label></th>';
		echo '		<td>';
		echo '			<textarea id="streamNotes" name="streamNotes" class="streamNotes_field" placeholder="' . esc_attr__( '', 'text_domain' ) . '" style="width:100%;">'.esc_attr__( $streamNotes ).'</textarea>';
		echo '			<p class="description">' . __( '', 'text_domain' ) . '</p>';
		echo '		</td>';
		echo '	</tr>';
		// echo '	<tr>';
		// echo '		<th><label for="streamTitle" class="streamTitle_label">' . __( 'Title', 'text_domain' ) . '</label></th>';
		// echo '		<td>';
		// echo '			<input type="text" id="streamTitle" name="streamTitle" class="streamTitle_field" placeholder="' . esc_attr__( '', 'text_domain' ) . '" value="' . esc_attr__( $streamTitle ) . '">';
		// echo '			<p class="description">' . __( 'Seller full name.', 'text_domain' ) . '</p>';
		// echo '		</td>';
		// echo '	</tr>';

		echo '	<tr>';
		echo '		<th><label for="streamEmbedLink" class="streamEmbedLink_label">' . __( 'Embed Link', 'text_domain' ) . '</label></th>';
		echo '		<td>';
		echo '			<input type="text" id="streamEmbedLink" name="streamEmbedLink" class="streamEmbedLink_field" placeholder="' . esc_attr__( '', 'text_domain' ) . '" value="' . esc_attr__( $streamEmbedLink ) . '" style="width:100%;">';
		echo '			<p class="description">' . __( 'URL\'s Must Must follow as' , 'text_domain' ) . '</p>';
		echo '			<p class="description">' . __( 'YOUTUBE: https://www.youtube.com/embed/[VideoID]' , 'text_domain' ) . '</p>';
		echo '			<p class="description">' . __( 'Twitch: https://player.twitch.tv/?channel=[Channel ID]' , 'text_domain' ) . '</p>';
		echo '			<p class="description">' . __( 'Facebook: https://www.facebook.com/PlayStation/videos/10155554431506803' , 'text_domain' ) . '</p>';
		echo '		</td>';
		echo '	</tr>';

		echo '</table>';

	}

	public function save_metabox( $post_id, $post ) {

		// Add nonce for security and authentication.
		$nonce_name   = $_POST['car_nonce'];
		$nonce_action = 'car_nonce_action';

		// Check if a nonce is set.
		if ( ! isset( $nonce_name ) )
			return;

		// Check if a nonce is valid.
		if ( ! wp_verify_nonce( $nonce_name, $nonce_action ) )
			return;

		// Check if the user has permissions to save data.
		if ( ! current_user_can( 'edit_post', $post_id ) )
			return;

		// Check if it's not an autosave.
		if ( wp_is_post_autosave( $post_id ) )
			return;

		// Check if it's not a revision.
		if ( wp_is_post_revision( $post_id ) )
			return;

		// Sanitize user input.
		$streamSource = isset( $_POST[ 'streamSource' ] ) ? sanitize_text_field( $_POST[ 'streamSource' ] ) : '';
		$streamPlatform = isset( $_POST[ 'streamPlatform' ] ) ? sanitize_text_field( $_POST[ 'streamPlatform' ] ) : '';
		$streamCity = isset( $_POST[ 'streamCity' ] ) ? sanitize_text_field( $_POST[ 'streamCity' ] ) : '';
		$streamState = isset( $_POST[ 'streamState' ] ) ? sanitize_text_field( $_POST[ 'streamState' ] ) : '';
		$streamType = isset( $_POST[ 'streamType' ] ) ? sanitize_text_field( $_POST[ 'streamType' ] ) : '';
		$streamView = isset( $_POST[ 'streamView' ] ) ? sanitize_text_field( $_POST[ 'streamView' ] ) : '';
		$streamLink = isset( $_POST[ 'streamLink' ] ) ? sanitize_text_field( $_POST[ 'streamLink' ] ) : '';
		$streamStatus = isset( $_POST[ 'streamStatus' ] ) ? sanitize_text_field( $_POST[ 'streamStatus' ] ) : '';
		$streamNotes = isset( $_POST[ 'streamNotes' ] ) ? sanitize_text_field( $_POST[ 'streamNotes' ] ) : '';
		$streamTitle = isset( $_POST[ 'streamTitle' ] ) ? sanitize_text_field( $_POST[ 'streamTitle' ] ) : '';
		$streamEmbedLink = isset( $_POST[ 'streamEmbedLink' ] ) ? sanitize_text_field( $_POST[ 'streamEmbedLink' ] ) : '';

		// Update the meta field in the database.
		update_post_meta( $post_id, '_streamSource', $streamSource );
		update_post_meta( $post_id, '_streamPlatform', $streamPlatform );
		update_post_meta( $post_id, '_streamCity', $streamCity );
		update_post_meta( $post_id, '_streamState', $streamState );
		update_post_meta( $post_id, '_streamType', $streamType );
		update_post_meta( $post_id, '_streamView', $streamView );
		update_post_meta( $post_id, '_streamLink', $streamLink );
		update_post_meta( $post_id, '_streamStatus', $streamStatus );
		update_post_meta( $post_id, '_streamNotes', $streamNotes );
		update_post_meta( $post_id, '_streamTitle', $streamTitle );
		update_post_meta( $post_id, '_streamEmbedLink', $streamEmbedLink );

	}

}

new hh_Meta_Box;

add_shortcode( 'StreamForm', function ()
{
if( 'POST' == $_SERVER['REQUEST_METHOD'] && !empty( $_POST['insert_hh_stream'] )) { //check that our form was submitted
	$title =  $_POST['streamTitle'];  //set our title
	$post = array( //our wp_insert_post args
		'post_title'	=> wp_strip_all_tags($title),
		'post_status'	=> 'draft',
		'post_type' 	=> 'stream'
	);
	$postID = wp_insert_post($post); //send our post, save the resulting ID
	// $current_user = wp_get_current_user(); //check who is logged in
	if (is_wp_error( $postID )) {
		echo $postID->get_error_message(  );
	}else{
		update_post_meta($postID, '_streamSource', $_POST['streamSource']); //add custom meta data, after the post is inserted	
		update_post_meta($postID, '_streamPlatform', $_POST['streamPlatform']); //add custom meta data, after the post is inserted	
		update_post_meta($postID, '_streamCity', $_POST['streamCity']); //add custom meta data, after the post is inserted	
		update_post_meta($postID, '_streamState', $_POST['streamState']); //add custom meta data, after the post is inserted	
		update_post_meta($postID, '_streamType', $_POST['streamType']); //add custom meta data, after the post is inserted	
		update_post_meta($postID, '_streamView', $_POST['streamView']); //add custom meta data, after the post is inserted	
		update_post_meta($postID, '_streamLink', $_POST['streamLink']); //add custom meta data, after the post is inserted	
		update_post_meta($postID, '_streamStatus', $_POST['streamStatus']); //add custom meta data, after the post is inserted	
		update_post_meta($postID, '_streamNotes', $_POST['streamNotes']); //add custom meta data, after the post is inserted	
		update_post_meta($postID, '_streamTitle', $_POST['streamTitle']); //add custom meta data, after the post is inserted	
		update_post_meta($postID, '_streamEmbedLink', $_POST['streamEmbedLink']); //add custom meta data, after the post is inserted	
	}
	// wp_redirect( get_permalink($my_post_id) ); //send the user along to their newly created post	 
} else {
	 ?>
	<div id="postbox">
		<form id="new_hh_stream" name="new_hh_stream" method="post" action="">
			<div class="form-group">
				<label>Stream Title</label>
				<input type="text" id="streamTitle" name="streamTitle" class="form-control" placeholder="" />
			</div>
			<div class="form-group">
				<label>Source</label>
				<input type="text" id="streamSource" name="streamSource" placeholder="e.g: WREX, Nycdsa, Oreoexpress" />
			</div>
			<!-- <input type="text" id="streamPlatform" value="" name="streamPlatform" placeholder="Platform" /> -->
			<div class="form-group">
				<label>Platform</label>
				<select name="streamPlatform" id="streamPlatform" >
					<option value="facebook">Facebook</option>
					<option value="youtube">Youtube</option>
					<option value="twitch">Twitch</option>
				</select>
			</div>
			<div class="form-group">
				<label>City</label>
				<input type="text" id="streamCity" name="streamCity" placeholder="e.g: Washington, Chicago etc" />
			</div>
			<div class="form-group">
				<label>State</label>
				<input type="text" id="streamState" name="streamState" placeholder="e.g: DC, NJ, NY etc" />
			</div>
			<div class="form-group">
				<label>Type</label>
				<input type="text" id="streamType" name="streamType" placeholder="e.g: Mixed" />
			</div>
			<div class="form-group">
				<label>View</label>
				<input type="text" id="streamView" name="streamView" placeholder="e.g: Aggregate" />
			</div>
			<div class="form-group">
				<label>Link</label>
				<input type="url" id="streamLink" name="streamLink" placeholder="e.g: http://youtube.com/embed/kAsaDh" />
			</div>
			<!-- <input type="text" id="streamStatus" name="streamStatus" placeholder="e.g: " /> -->
			<div class="form-group">
				<label>Status</label>
				<select name="streamStatus" id="streamStatus" class="form-control">
					<option value="">Select</option>
					<option value="live">Live</option>
					<option value="offline">Offline</option>
				</select>
			</div>
			<div class="form-group">
				<label>Notes</label>
				<textarea id="streamNotes" name="streamNotes" placeholder="" ></textarea>
			</div>
			<!-- <input type="text" id="streamTitle" value="" name="streamTitle" placeholder="Thread Title" /> -->
			<div class="form-group">
				<label>Embed Link</label>
				<input type="url" id="streamEmbedLink" name="streamEmbedLink" placeholder="e.g: http://youtube.com/embed/aEsoaXE" />
			</div>
			<input type="submit" value="Add Stream" id="thread_submit" name="thread_submit" class="thread-button" />
			<input type="hidden" name="insert_hh_stream" value="post" />
		</form>
	</div>
	<?php  
}
} );


add_filter( 'manage_stream_posts_columns', 'smashing_stream_columns' );
function smashing_stream_columns( $columns ) {  
    $columns = array(
      'cb' => $columns['cb'],
      // 'image' => __( 'Image' ),
      'title' => __( 'Title' ),
      'platform' => __( 'Platform' ),
      'state' => __( 'State' ),
      'city' => __( 'City' ),
      'status' => __( 'Status' ),
      'source' => __( 'Source', 'smashing' ),
      'changeStatus' => ('Turn On/Off'),
      'date' => __( 'Date', 'smashing' ),
      // 'area' => __( 'Area', 'smashing' ),
    );
  return $columns;
}

add_action( 'manage_stream_posts_custom_column', 'smashing_stream_column', 10, 2);
function smashing_stream_column( $column, $post_id ) {
  // Image column
  // if ( 'image' === $column ) {
  //   echo get_the_post_thumbnail( $post_id, array(80, 80) );
  // }

  // State column
  if ( 'state' === $column ) {
    $streamState = get_post_meta( $post_id, '_streamState', true );
    echo $streamState;
  }
  if ( 'city' === $column ) {
    $streamCity = get_post_meta( $post_id, '_streamCity', true );
    echo $streamCity;
  }
  if ( 'source' === $column ) {
    $streamSource = get_post_meta( $post_id, '_streamSource', true );
    echo $streamSource;
  }
  if ( 'platform' === $column ) {
    $streamPlatform = get_post_meta( $post_id, '_streamPlatform', true );
    echo $streamPlatform;
  }
  if ( 'status' === $column ) {
    $streamStatus = get_post_meta( $post_id, '_streamStatus', true );
    echo $streamStatus;
  }
	if ( 'changeStatus' === $column ) {
		$streamStatus = get_post_meta( $post_id, '_streamStatus', true );
    $btnText = ($streamStatus == 'live') ? 'offline' : 'live';
    echo "<a href=\"".admin_url( 'admin-ajax.php' )."?action=update_status&postID=".$post_id."&status=".$btnText."&_wp_http_referer=".urlencode(esc_attr( wp_unslash( $_SERVER['REQUEST_URI'] ) ))."\">Turn $btnText</a>";
		
	}
}
add_filter( 'manage_edit-stream_sortable_columns', 'my_sortable_stream_column' );
function my_sortable_stream_column( $columns ) {
    $columns['platform'] = array('platform',1);
    $columns['state'] = array('state',1);
    $columns['city'] = array('city',1);
    $columns['status'] = array('status',1);
    $columns['source'] = array('source',1);
 
    //To make a column 'un-sortable' remove it from the array
    //unset($columns['date']);
 
    return $columns;
}

/*Begin Stream Source Filter Field*/
add_action('restrict_manage_posts', 'source_add_extra_tablenav');
function source_add_extra_tablenav($post_type){
    
    global $wpdb;
    
    if($post_type != 'stream')
        return;
    
    $query = $wpdb->prepare('
        SELECT DISTINCT pm.meta_value FROM %1$s pm
        LEFT JOIN %2$s p ON p.ID = pm.post_id
        WHERE pm.meta_key = "%3$s" 
        AND p.post_status = "%4$s" 
        AND p.post_type = "%5$s"
        ORDER BY "%6$s"',
        $wpdb->postmeta,
        $wpdb->posts,
        '_streamSource', // Your meta key - change as required
        'publish',          // Post status - change as required
        $post_type,
        '_streamSource'
    );
    $results = $wpdb->get_col($query);
    
    if(empty($results))
        return;

    if (isset( $_GET['stream-source'] ) && $_GET['stream-source'] != '') {
        $selectedName = $_GET['stream-source'];
    } else {
        $selectedName = -1;
    }
    
    $options[] = sprintf('<option value="-1">%1$s</option>', __('All Sources', 'your-text-domain'));
    foreach($results as $result) :
        if ($result == $selectedName) {
            $options[] = sprintf('<option value="%1$s" selected>%2$s</option>', esc_attr($result), $result);
        } else {
            $options[] = sprintf('<option value="%1$s">%2$s</option>', esc_attr($result), $result);
        }
    endforeach;

    echo '<select class="" id="stream-source" name="stream-source">';
    echo join("\n", $options);
    echo '</select>';
}
/*End Stream Source Filter Field*/
/*Begin Stream Platform Filter Field*/
add_action('restrict_manage_posts', 'platform_add_extra_tablenav');
function platform_add_extra_tablenav($post_type){
    
    global $wpdb;
    
    if($post_type != 'stream')
        return;
    
    $query = $wpdb->prepare('
        SELECT DISTINCT pm.meta_value FROM %1$s pm
        LEFT JOIN %2$s p ON p.ID = pm.post_id
        WHERE pm.meta_key = "%3$s" 
        AND p.post_status = "%4$s" 
        AND p.post_type = "%5$s"
        ORDER BY "%6$s"',
        $wpdb->postmeta,
        $wpdb->posts,
        '_streamPlatform', // Your meta key - change as required
        'publish',          // Post status - change as required
        $post_type,
        '_streamPlatform'
    );
    $results = $wpdb->get_col($query);
    
    if(empty($results))
        return;

    if (isset( $_GET['stream-platform'] ) && $_GET['stream-platform'] != '') {
        $selectedName = $_GET['stream-platform'];
    } else {
        $selectedName = -1;
    }
    
    $options[] = sprintf('<option value="-1">%1$s</option>', __('All Platforms', 'your-text-domain'));
    foreach($results as $result) :
        if ($result == $selectedName) {
            $options[] = sprintf('<option value="%1$s" selected>%2$s</option>', esc_attr($result), $result);
        } else {
            $options[] = sprintf('<option value="%1$s">%2$s</option>', esc_attr($result), $result);
        }
    endforeach;

    echo '<select class="" id="stream-platform" name="stream-platform">';
    echo join("\n", $options);
    echo '</select>';
}
/*End Stream Platform Filter Field*/
/*Begin Stream City Filter Field*/
add_action('restrict_manage_posts', 'city_add_extra_tablenav');
function city_add_extra_tablenav($post_type){
    
    global $wpdb;
    
    if($post_type != 'stream')
        return;
    
    $query = $wpdb->prepare('
        SELECT DISTINCT pm.meta_value FROM %1$s pm
        LEFT JOIN %2$s p ON p.ID = pm.post_id
        WHERE pm.meta_key = "%3$s" 
        AND p.post_status = "%4$s" 
        AND p.post_type = "%5$s"
        ORDER BY "%6$s"',
        $wpdb->postmeta,
        $wpdb->posts,
        '_streamCity', // Your meta key - change as required
        'publish',          // Post status - change as required
        $post_type,
        '_streamCity'
    );
    $results = $wpdb->get_col($query);
    
    if(empty($results))
        return;

    if (isset( $_GET['stream-city'] ) && $_GET['stream-city'] != '') {
        $selectedName = $_GET['stream-city'];
    } else {
        $selectedName = -1;
    }
    
    $options[] = sprintf('<option value="-1">%1$s</option>', __('All Cities', 'your-text-domain'));
    foreach($results as $result) :
        if ($result == $selectedName) {
            $options[] = sprintf('<option value="%1$s" selected>%2$s</option>', esc_attr($result), $result);
        } else {
            $options[] = sprintf('<option value="%1$s">%2$s</option>', esc_attr($result), $result);
        }
    endforeach;

    echo '<select class="" id="stream-city" name="stream-city">';
    echo join("\n", $options);
    echo '</select>';
}
/*End Stream City Filter Field*/
/*Begin Stream State Filter Field*/
add_action('restrict_manage_posts', 'state_add_extra_tablenav');
function state_add_extra_tablenav($post_type){
    
    global $wpdb;
    
    if($post_type != 'stream')
        return;
    
    $query = $wpdb->prepare('
        SELECT DISTINCT pm.meta_value FROM %1$s pm
        LEFT JOIN %2$s p ON p.ID = pm.post_id
        WHERE pm.meta_key = "%3$s" 
        AND p.post_status = "%4$s" 
        AND p.post_type = "%5$s"
        ORDER BY "%6$s"',
        $wpdb->postmeta,
        $wpdb->posts,
        '_streamstate', // Your meta key - change as required
        'publish',          // Post status - change as required
        $post_type,
        '_streamstate'
    );
    $results = $wpdb->get_col($query);
    
    if(empty($results))
        return;

    if (isset( $_GET['stream-state'] ) && $_GET['stream-state'] != '') {
        $selectedName = $_GET['stream-state'];
    } else {
        $selectedName = -1;
    }
    
    $options[] = sprintf('<option value="-1">%1$s</option>', __('All States', 'your-text-domain'));
    foreach($results as $result) :
        if ($result == $selectedName) {
            $options[] = sprintf('<option value="%1$s" selected>%2$s</option>', esc_attr($result), $result);
        } else {
            $options[] = sprintf('<option value="%1$s">%2$s</option>', esc_attr($result), $result);
        }
    endforeach;

    echo '<select class="" id="stream-state" name="stream-state">';
    echo join("\n", $options);
    echo '</select>';
}
/*End Stream State Filter Field*/
/*Begin Stream Status Filter Field*/
add_action('restrict_manage_posts', 'status_add_extra_tablenav');
function status_add_extra_tablenav($post_type){
    
    global $wpdb;
    
    if($post_type != 'stream')
        return;
    
    $query = $wpdb->prepare('
        SELECT DISTINCT pm.meta_value FROM %1$s pm
        LEFT JOIN %2$s p ON p.ID = pm.post_id
        WHERE pm.meta_key = "%3$s" 
        AND p.post_status = "%4$s" 
        AND p.post_type = "%5$s"
        ORDER BY "%6$s"',
        $wpdb->postmeta,
        $wpdb->posts,
        '_streamStatus', // Your meta key - change as required
        'publish',          // Post status - change as required
        $post_type,
        '_streamStatus'
    );
    $results = $wpdb->get_col($query);
    
    if(empty($results))
        return;

    if (isset( $_GET['stream-status'] ) && $_GET['stream-status'] != '') {
        $selectedName = $_GET['stream-status'];
    } else {
        $selectedName = -1;
    }
    
    $options[] = sprintf('<option value="-1">%1$s</option>', __('All Statuses', 'your-text-domain'));
    foreach($results as $result) :
        if ($result == $selectedName) {
            $options[] = sprintf('<option value="%1$s" selected>%2$s</option>', esc_attr($result), $result);
        } else {
            $options[] = sprintf('<option value="%1$s">%2$s</option>', esc_attr($result), $result);
        }
    endforeach;

    echo '<select class="" id="stream-status" name="stream-status">';
    echo join("\n", $options);
    echo '</select>';
}

/*End Stream Status Filter Field*/

add_filter( 'parse_query', 'prefix_parse_filter' );
function  prefix_parse_filter($query) {
   global $pagenow;
   $current_page = isset( $_GET['post_type'] ) ? $_GET['post_type'] : '';
	$meta_query = array();
   if ( is_admin() && 'stream' == $current_page && 'edit.php' == $pagenow ) {
   	if (isset( $_GET['stream-city'] ) && 
      $_GET['stream-city'] != '' &&
      $_GET['stream-city'] != '-1') {
   		$meta_query[] = array(
	          'key'     => '_streamCity',
	           'compare' => '=',
	           'value'   => $_GET['stream-city'],
	           // 'type'    => 'numeric',
	      );
   	}
   	if (isset( $_GET['stream-state'] ) && 
      $_GET['stream-state'] != '' &&
      $_GET['stream-state'] != '-1') {
   		$meta_query[] = array(
	          'key'     => '_streamState',
	           'compare' => '=',
	           'value'   => $_GET['stream-state'],
	           // 'type'    => 'numeric',
	      );
   	}
   	if (isset( $_GET['stream-platform'] ) && 
      $_GET['stream-platform'] != '' &&
      $_GET['stream-platform'] != '-1') {
   		$meta_query[] = array(
	          'key'     => '_streamPlatform',
	           'compare' => '=',
	           'value'   => $_GET['stream-platform'],
	           // 'type'    => 'numeric',
	      );
   	}
   	if (isset( $_GET['stream-status'] ) && 
      $_GET['stream-status'] != '' &&
      $_GET['stream-status'] != '-1') {
   		$meta_query[] = array(
	          'key'     => '_streamStatus',
	           'compare' => '=',
	           'value'   => $_GET['stream-status'],
	           // 'type'    => 'numeric',
	      );
   	}
   	if (isset( $_GET['stream-source'] ) && 
      $_GET['stream-source'] != '' &&
      $_GET['stream-source'] != '-1') {
   		$meta_query[] = array(
	          'key'     => '_streamSource',
	           'compare' => '=',
	           'value'   => $_GET['stream-source'],
	           // 'type'    => 'numeric',
	      );
   	}
   	
    // $competition_name                  = $_GET['stream-city'];
    // $query->query_vars['meta_key']     = '_streamCity';
    // $query->query_vars['meta_value']   = $competition_name;
    // $query->query_vars['meta_compare'] = '=';
  }
  if ((isset( $_GET['stream-city'] ) && 
        $_GET['stream-city'] != '' &&
        $_GET['stream-city'] != '-1') || 
  	(isset( $_GET['stream-state'] ) && 
        $_GET['stream-state'] != '' &&
        $_GET['stream-state'] != '-1') || 
  	(isset( $_GET['stream-platform'] ) && 
        $_GET['stream-platform'] != '' &&
        $_GET['stream-platform'] != '-1') || 
  	(isset( $_GET['stream-status'] ) && 
        $_GET['stream-status'] != '' &&
        $_GET['stream-status'] != '-1') || 
  	(isset( $_GET['stream-source'] ) && 
        $_GET['stream-source'] != '' &&
        $_GET['stream-source'] != '-1')
    ) {
  	$query->set( 'meta_query', $meta_query );       
  }
}

add_action( 'wp_head', function()
{
	echo "<style>.grid-container {display: grid;grid-template-columns: auto auto auto;background-color: #222;width:100vw;max-width:100%;}.grid-item {background-color: rgba(255, 255, 255, 0.8);border: 0px solid rgba(0, 0, 0, 0.8);font-size: 15px;text-align: center;}@media only screen and (max-device-width: 480px) {.grid-container {grid-template-columns: auto !important;}}</style>";
}, 10 );

/**
 * Returns the parsed shortcode.
 *
 * @param array   {
 *     Attributes of the shortcode.
 *
 *     @type string $id ID of...
 * }
 * @param string  Shortcode content.
 *
 * @return string HTML content to display the shortcode.
 */
function funStreamWall( $atts = array(), $content = '' ) {
	$atts = shortcode_atts( array(
		'col' => '3',
		'limit' => 9999,
		'platform' => null,
		'status' => 'live',
	), $atts, 'STREAMWALL' );
	echo "<div class=\"grid-container\">";
	echo '<div class="col-md-5 col-md-offset-6" id="streamLoader" style="margin-left:50%;">
	<svg version="1.1" id="L1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 100 100" enable-background="new 0 0 100 100" xml:space="preserve">
    <circle fill="none" stroke="#fff" stroke-width="6" stroke-miterlimit="15" stroke-dasharray="14.2472,14.2472" cx="50" cy="50" r="47" >
      <animateTransform 
         attributeName="transform" 
         attributeType="XML" 
         type="rotate"
         dur="5s" 
         from="0 50 50"
         to="360 50 50" 
         repeatCount="indefinite" />
  </circle>
  <circle fill="none" stroke="#fff" stroke-width="1" stroke-miterlimit="10" stroke-dasharray="10,10" cx="50" cy="50" r="39">
      <animateTransform 
         attributeName="transform" 
         attributeType="XML" 
         type="rotate"
         dur="5s" 
         from="0 50 50"
         to="-360 50 50" 
         repeatCount="indefinite" />
  </circle>
  <g fill="#fff">
  <rect x="30" y="35" width="5" height="30">
    <animateTransform 
       attributeName="transform" 
       dur="1s" 
       type="translate" 
       values="0 5 ; 0 -5; 0 5" 
       repeatCount="indefinite" 
       begin="0.1"/>
  </rect>
  <rect x="40" y="35" width="5" height="30" >
    <animateTransform 
       attributeName="transform" 
       dur="1s" 
       type="translate" 
       values="0 5 ; 0 -5; 0 5" 
       repeatCount="indefinite" 
       begin="0.2"/>
  </rect>
  <rect x="50" y="35" width="5" height="30" >
    <animateTransform 
       attributeName="transform" 
       dur="1s" 
       type="translate" 
       values="0 5 ; 0 -5; 0 5" 
       repeatCount="indefinite" 
       begin="0.3"/>
  </rect>
  <rect x="60" y="35" width="5" height="30" >
    <animateTransform 
       attributeName="transform" 
       dur="1s" 
       type="translate" 
       values="0 5 ; 0 -5; 0 5"  
       repeatCount="indefinite" 
       begin="0.4"/>
  </rect>
  <rect x="70" y="35" width="5" height="30" >
    <animateTransform 
       attributeName="transform" 
       dur="1s" 
       type="translate" 
       values="0 5 ; 0 -5; 0 5" 
       repeatCount="indefinite" 
       begin="0.5"/>
  </rect>
  </g>
</svg></div>';
	echo "</div>";
	?>
	<script type="text/javascript">
		function streamwall() {
			var ajax = new XMLHttpRequest();
			ajax.onreadystatechange = function() {
				if (ajax.readyState == 4 && ajax.status == 200) {
					var response = ajax.responseText;
					var adata = JSON.parse(response);
					if (adata.success) {
						var i = 0;
						adata.data.forEach( function(e, i) {
							var streamID = "stream-"+e.postID;
							if (!document.body.contains(document.getElementById(streamID)) && e.status == "live") {
								// e.status == "live"
// 								i += 5000;
								setTimeout(function () {
								    if (!document.body.contains(document.getElementById(streamID)) && e.status == "live") {
									var d = document.createElement("div");
									d.className = "grid-item "+streamID;
									d.id = streamID;
									d.style = "height:33vh;";
									d.style.position = "relative";
									var iframe = document.createElement("iframe");
									iframe.src=e.link;
									iframe.style="width:100%;height:100%;";
									iframe.setAttribute("allowfullscreen", "true"); 
									d.appendChild(iframe);
									var f = document.createElement("div");
									f.style.position = "absolute";
									f.style.top = "5px";
									f.style.left = "25px";
									f.style.color = "white";
									// f.style.display = "none";
									f.innerHTML = e.title;
									d.appendChild(f);
									document.getElementsByClassName('grid-container')[0].appendChild(d);
								    }
								}, i*1000);
							}else{
								if (document.body.contains(document.getElementById(streamID)) && e.status == 'offline') {
									// console.log(e.status);
									document.getElementById(streamID).remove();
								}
							}
						});
						setTimeout(()=>{
							if (document.body.contains(document.getElementById("streamLoader"))) {
								document.getElementById("streamLoader").remove();
							}
						}, 5000);
					}
				}
			};
			ajax.open('POST', "<?php echo admin_url( 'admin-ajax.php' ); ?>?action=call_stream", true);
			ajax.setRequestHeader("Content-type", "application/json");
			ajax.send();
		}
		document.addEventListener('DOMContentLoaded', function(){
			streamwall();
		});
		setInterval( streamwall , 15000);
	</script>
	<?php
}
add_shortcode( 'STREAMWALL', 'funStreamWall' );

add_action( "wp_ajax_call_stream", 'call_stream', 10 );
add_action( "wp_ajax_nopriv_call_stream", 'call_stream', 10 );

function call_stream()
{
	ob_clean();
	$meta_query = array();
	if (array_key_exists('status', $atts) && $atts['status'] !== null){
		$meta_query[] = array(
			'key'     => '_streamStatus',
			'value'   => $atts['status'],
			'type'    => 'CHAR',
			'compare' => '=',
		);
	}/*else{
		$meta_query[] = array(
			'key'     => '_streamStatus',
			'value'   => 'live',
			'type'    => 'CHAR',
			'compare' => '=',
		);
	}*/

	if (array_key_exists('platform', $atts) && $atts['platform'] !== null){
		$meta_query[] = array(
			'key'     => '_streamPlatform',
			'value'   => $atts['platform'],
			'type'    => 'CHAR',
			'compare' => '=',
		);
	}
	/*
	 * The WordPress Query class.
	 *
	 * @link http://codex.wordpress.org/Function_Reference/WP_Query
	 */
	$args = array(
		// Choose ^ 'any' or from below, since 'any' cannot be in an array
		'post_type' => array('stream'),
		'post_status' => array('publish'),
		// Pagination Parameters
		'posts_per_page'         => 9999,
	);
	if (!empty($meta_query)) {
		$args['meta_query'] = $meta_query;
	}

	$query = new WP_Query( $args );
	$qry = array();
	if ( $query->have_posts() ) :
	    while ( $query->have_posts() ) {
	    	$query->the_post();
	    	// echo "<div class=\"grid-item\">";
	    	$post_metas = get_post_meta(get_the_ID());
			$post_metas = array_combine(array_keys($post_metas), array_column($post_metas, '0'));
			if (array_key_exists('_streamEmbedLink', $post_metas) && !empty($post_metas['_streamEmbedLink'])){
				switch ($post_metas['_streamPlatform']) {
					case 'twitch':
						$qry[] = array(
							"title"=>get_the_title(),
							"postID"=>get_the_ID(),
							"link"=>$post_metas['_streamEmbedLink']."&parent=leftistmediagroup.com",
							"platform"=>$post_metas['_streamPlatform'],
							"status"=>$post_metas['_streamStatus']
							);
						break;
					case 'facebook':
						$qry[] = array(
							"title"=>get_the_title(),
							"postID"=>get_the_ID(),
							"link"=>"https://www.facebook.com/plugins/post.php?href=".urlencode($post_metas['_streamEmbedLink']),
							"platform"=>$post_metas['_streamPlatform'],
							"status"=>$post_metas['_streamStatus']
							);
						break;
					default:
						$qry[] = array(
							"title"=>get_the_title(),
							"postID"=>get_the_ID(),
							"link"=>$post_metas['_streamEmbedLink'],
							"platform"=>$post_metas['_streamPlatform'],
							"status"=>$post_metas['_streamStatus']
							);
						break;
				}
				/*switch ($post_metas['_streamPlatform']) {
				 	case 'youtube':
				 		// $qry[] = "<iframe 
				 		echo "<iframe 
					 		id=\"\" class=\"\"
					 		src=\" ".$post_metas['_streamEmbedLink']."\" ></iframe>";
				 		break;
				 	case 'twitch':
				 		// $qry[] = "<iframe  
				 		echo "<iframe  
				 		id=\"\" class=\"\"
				 		src=\"".$post_metas['_streamEmbedLink']."&parent=localhost\" 
				 		frameborder=\"0\" allowfullscreen=\"true\" scrolling=\"no\" ></iframe>";
				 		break;
				 	case 'vimeo':
				 		// $qry[] = "<iframe src=\"".$post_metas['_streamEmbedLink']."\" 
				 		echo "<iframe src=\"".$post_metas['_streamEmbedLink']."\" 
				 		frameborder=\"0\" allowfullscreen=\"true\" 
				 		scrolling=\"no\"></iframe>";
				 		break;
				 	case 'facebook':
				 		// echo "<iframe src=\"https://www.facebook.com/video/embed?video_id=754295507939962\"></iframe>";
				 		// $qry[] = "<iframe src=\"".$post_metas['_streamEmbedLink']."\"></iframe>";
				 		echo "<iframe src=\"".$post_metas['_streamEmbedLink']."\"></iframe>";
				 		break;
				 	default:
				 		break;
				}*/
			}
	    }
	    wp_send_json_success( $qry, 200 );
	else :
		// echo "<p>No More post exists.</p>";
		wp_send_json_error( array("message"=>"No Video Exist...!"), $status_code );
	endif;
	wp_reset_postdata();
	wp_die(  );
}

add_action( "wp_ajax_update_status", 'update_status', 10 );
add_action( "wp_ajax_nopriv_update_status", 'update_status', 10 );
function update_status()
{
	$postID=$_GET['postID'];
	$status=$_GET['status'];
	if (!empty($postID) && !empty($postID))
		update_post_meta( $postID, '_streamStatus', $status );
	echo "<script>";
	echo "window.location=\" ".str_ireplace('&amp;', '&', urldecode($_GET['_wp_http_referer']))." \";";
	echo "</script>";
}

add_action( 'views_edit-stream', 'remove_edit_post_views' );
function remove_edit_post_views( $views ) {
    $views['pre'] = '<a href="'.admin_url().'edit.php?post_type=stream&stream_status=live">Live</a>';
    return $views;
}

add_action('pre_get_posts', 'live_post_lists');

function live_post_lists( $q ) {
  $scr = get_current_screen();
  if ( is_admin() && ( $scr->base === 'edit' ) && $q->is_main_query() ) {
    // To target only a post type uncomment following line and adjust post type name
    // if ( $scr->post_type !== 'post' ) return;
    // if you change the link in function above adjust next line accordingly
    $status = filter_input(INPUT_GET, 'stream_status', FILTER_SANITIZE_STRING);
    if ( isset($status) && !empty($status) ) {
      // adjust meta query to fit your needs
      $meta_query = array( 'key' => '_streamStatus', 'value' => $status, );
      $q->set( 'meta_query', array($meta_query) );
    }
  }
}