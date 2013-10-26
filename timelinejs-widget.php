<?php
/*
Plugin Name: Timeline Widget
Plugin URI: 
Description: It can make your wordpress have a timeline widget.
Author: Jeonghwan Kim
Version: 1.0
Author URI: https://www.facebook.com/jeonghwan.kim1
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
		// 기본 입력값 
		$defaults = array(
			'title' => 'Timeline', 
			'google_docs_url' => '',
			'height' => '600'
			);

		// instance 배열에 새로운 배열($default) 추가
		// 중복된것은 $instance 데이터로한다.
		$instance = wp_parse_args((array)$instance, $defaults); 

		// 입력값 불러오기
		$title = strip_tags($instance['title']);
		$google_docs_url = strip_tags($instance['google_docs_url']);
		$height = strip_tags($instance['height']);
		?>

		<!-- 폼에 입력값을 넣는다. -->
		<p><?php _e('Title')?>:
		<input class="widefat" name="<?php echo $this->get_field_name('title');?>" 
		type="text" value="<?php echo $title; ?>" /></p>
		
		<p><?php _e('Google docs url')?>:
		<input class="widefat" name="<?php echo $this->get_field_name('google_docs_url');?>"
		type="text" value="<?php echo $google_docs_url; ?>" /></p>

		<p><?php _e('Height(px) - 600 is recommended.')?>:
		<input class="widefat" name="<?php echo $this->get_field_name('height');?>"
		type="text" value="<?php echo $height; ?>" /></p>

		<?php 
	}

	public function update($new_instance, $old_instance){
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['google_docs_url'] = strip_tags($new_instance['google_docs_url']); 
		$instance['height'] = strip_tags($new_instance['height']); 
		
		return $instance;
	}

	public function widget( $args, $instance ) {
		extract($args);
		
		echo $before_widget;
		echo $before_title . $instance['title'] . $after_title;
		?>

		<input type="hidden" id="google_docs_url" value="<?php echo $instance['google_docs_url'];?>" />
		<input type="hidden" id="height" value="<?php echo $instance['height'];?>" />

		<!-- Timeline : start -->
		<!-- <div id="timeline-embed" style="height:600;width:100%;"></div> -->
		<div id="timeline-embed" style="width:100%;"></div>
		<script>
			// 설정값을 불러온다.
			var google_docs_url = document.getElementById("google_docs_url").value;
			var height = document.getElementById("height").value;
	    
	    // 설정값 세팅
	    var timeline_config = {
				width: "100%",
				height: height,
				source: google_docs_url,
				embed_id: "timeline-embed",
				start_at_end:  false,
				start_at_slide: null,
				start_zoom_adjust: null,
				hash_bookmark: 'false',
				font: '',
				// debug: 'false',
				lang: 'en',
				maptype: 'toner'
	    }
		</script>
		<!-- 스크립트 삽입  -->
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