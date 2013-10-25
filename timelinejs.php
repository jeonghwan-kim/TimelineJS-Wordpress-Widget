<?php
/*
Plugin Name: Timeline Widget
Plugin URI: 
Description: It can make your wordpress have timeline widget.
Author: Jeonghwan Kim
Version: 1.0
Author URI: mailto:ej88ej@gmail.com
*/

define( 'TIMELINE_PLUGIN_PATH', plugin_dir_url(__FILE__) );

class Timeline extends WP_Widget {
	public function __construct() {
		// 스크립트 등록  
		wp_register_script('timeline-embed', plugin_dir_url( __FILE__).'js/storyjs-embed.js', array('jquery'), false, TRUE);

		$widget_ops = array (
			"classname" => "timeline_by_jeonghwan",
			"discription" => __( "Timeline widget" ),
			);
		parent::__construct( "timeline_by_jeonghwan", "Timeline widget", $widget_ops );
	}

	public function form( $instance ) {
		$defaults = array('title'=> 'Timeline', 'google_docs_url' => '');
		$instance = wp_parse_args((array)$instance, $defaults);
		$title = strip_tags($instance['title']);
		$google_docs_url = strip_tags($instance['google_docs_url']);
		// error_log($title); 
		?>
		
		<p><?php _e('Title')?>:
		<input class="widefat" name="<?php echo $this->get_field_name('title');?>" 
		type="text" value="<?php echo esc_attr($title)?>" /></p>
		
		<p><?php _e('Google docs url')?>:
		<input class="widefat" name="<?php echo $this->get_field_name('google_docs_url');?>"
		type="text" value="<?php echo esc_attr($google_docs_url)?>" /></p>

		<?php 
	}

	public function update($new_instance, $old_instance){
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['google_docs_url'] = strip_tags($new_instance['google_docs_url']); 
		return $instance;
	}

	public function widget( $args, $instance ) {
		extract($args);
		
		echo $before_widget;
		echo $before_title . $instance['title'] . $after_title;
		error_log($instance['google_docs_url']);
		?>

		<input type="hidden" id="google_docs_url" value="<?php echo $instance['google_docs_url'];?>" />

		<!-- Timeline : start -->
		<div id="timeline-embed" style="height:600;width:100%;"></div>
		<script>
			var google_docs_url = document.getElementById("google_docs_url").value;
	    var timeline_config = {
				width: "100%",
				height: "600",
				source: google_docs_url,
				embed_id: "timeline-embed",
				start_at_end:  false,
				start_at_slide: null,
				start_zoom_adjust: null,
				hash_bookmark: 'false',
				font: '',
				debug: 'false',
				lang: 'en',
				maptype: 'toner'
	    }
		</script>
		<?php wp_enqueue_script('timeline-embed'); ?>
		<!-- Timeline : end -->

		<?php
		echo $after_widget;
	}
}

add_action("widgets_init", "register_timeline_widgets");
function register_timeline_widgets() {
	register_widget("Timeline");
}
?>