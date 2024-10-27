<?php
/*
Plugin Name: Ambrosite Post Formats Widget
Plugin URI: http://www.ambrosite.com/plugins
Description: A list or dropdown of Post Format archives. Works much the same as the Categories widget.
Version: 1.1
Author: J. Michael Ambrosio
Author URI: http://www.ambrosite.com
License: GPL2
*/

function ambrosite_post_formats_install() {
	if ( !function_exists('get_post_format') ) {
		deactivate_plugins( basename(__FILE__) );
		wp_die( "This plugin requires WordPress version 3.1 or higher.");
	}
}
register_activation_hook( __FILE__, 'ambrosite_post_formats_install');

class Ambrosite_Widget_Post_Formats extends WP_Widget {

	function Ambrosite_Widget_Post_Formats() {
		$widget_ops = array( 'classname' => 'widget_post_formats', 'description' => __( "A list or dropdown of Post Formats" ) );
		$this->WP_Widget('ambrosite-post-formats', __('Post Formats'), $widget_ops);
	}

	function widget( $args, $instance ) {
		extract( $args );

		$title = apply_filters('widget_title', empty( $instance['title'] ) ? __( 'Post Formats' ) : $instance['title'], $instance, $this->id_base);
		$c = $instance['count'] ? '1' : '0';
		$d = $instance['dropdown'] ? '1' : '0';
		$f = $instance['format_id'] ? '1' : '0';

		echo $before_widget;
		if ( $title )
			echo $before_title . $title . $after_title;

		if ( $d ) { ?>

		<select id="post-format-dropdown" class="postform" name="post-format-dropdown">
		<option value="null">Select Post Format</option>
<?php			
		foreach ( get_post_format_strings() as $slug => $string ) {
			if ( get_post_format_link($slug) ) {
				$post_format = get_term_by( 'slug', 'post-format-' . $slug, 'post_format' );
				if ( $post_format->count > 0 ) {
					$count = $c ? ' (' . $post_format->count . ')' : '';
					$format_id = $f ? ' (ID: ' . $post_format->term_id . ')' : '';
					$selected = '';
					if ( is_tax('post_format') && get_post_format() == str_replace('post-format-', '', $post_format->slug ) )
						$selected = 'selected';
					echo '<option '.$selected.' class="level-0" value="' . get_post_format_link($slug) . '">' . $string . $count . $format_id . '</option>';
				}
			}
		} ?>
		</select>

<script type='text/javascript'>
/* <![CDATA[ */
	var pfDropdown = document.getElementById("post-format-dropdown");
	function onFormatChange() {
		if ( pfDropdown.options[pfDropdown.selectedIndex].value != 'null' ) {
			location.href = pfDropdown.options[pfDropdown.selectedIndex].value;
		}
	}
	pfDropdown.onchange = onFormatChange;
/* ]]> */
</script>

<?php
		} else {
?>
		<ul>
<?php
		$tooltip = empty( $instance['tooltip'] ) ? __( 'View all %format posts' ) : esc_attr($instance['tooltip']);
		foreach ( get_post_format_strings() as $slug => $string ) {
			if ( get_post_format_link($slug) ) {
				$post_format = get_term_by( 'slug', 'post-format-' . $slug, 'post_format' );
				if ( $post_format->count > 0 ) {
					$count = $c ? ' (' . $post_format->count . ')' : '';
					$format_id = $f ? ' (ID: ' . $post_format->term_id . ')' : '';
					$class = 'post-format-item';
					if ( is_tax('post_format') && get_post_format() == str_replace('post-format-', '', $post_format->slug) )
						$class .= ' current_format_item';
					echo '<li class="' . $class . '"><a title="' . str_replace('%format', $string, $tooltip) . '" href="' . get_post_format_link($slug) . '">' . $string . '</a>' . $count . $format_id . '</li>';
				}
			}
		}
?>
		</ul>
<?php
		}

		echo $after_widget;
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['tooltip'] = strip_tags($new_instance['tooltip']);
		$instance['count'] = !empty($new_instance['count']) ? 1 : 0;
		$instance['dropdown'] = !empty($new_instance['dropdown']) ? 1 : 0;
		$instance['format_id'] = !empty($new_instance['format_id']) ? 1 : 0;

		return $instance;
	}

	function form( $instance ) {
		//Defaults
		$instance = wp_parse_args( (array) $instance, array( 'title' => '') );
		$title = !empty($instance['title']) ? esc_attr( $instance['title'] ) : 'Post Formats';
		$tooltip = !empty($instance['tooltip']) ? esc_attr( $instance['tooltip'] ) : 'View all %format posts';
		$count = isset($instance['count']) ? (bool) $instance['count'] : false;
		$dropdown = isset( $instance['dropdown'] ) ? (bool) $instance['dropdown'] : false;
		$format_id = isset( $instance['format_id'] ) ? (bool) $instance['format_id'] : false;
?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e( 'Title:' ); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></p>

		<p><label for="<?php echo $this->get_field_id('tooltip'); ?>"><?php _e( 'Tooltip:' ); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('tooltip'); ?>" name="<?php echo $this->get_field_name('tooltip'); ?>" type="text" value="<?php echo $tooltip; ?>" /></p>

		<p><input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('dropdown'); ?>" name="<?php echo $this->get_field_name('dropdown'); ?>"<?php checked( $dropdown ); ?> />
		<label for="<?php echo $this->get_field_id('dropdown'); ?>"><?php _e( 'Display as dropdown' ); ?></label><br />

		<input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('count'); ?>" name="<?php echo $this->get_field_name('count'); ?>"<?php checked( $count ); ?> />
		<label for="<?php echo $this->get_field_id('count'); ?>"><?php _e( 'Show post counts' ); ?></label><br />

		<input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('format_id'); ?>" name="<?php echo $this->get_field_name('format_id'); ?>"<?php checked( $format_id ); ?> />
		<label for="<?php echo $this->get_field_id('format_id'); ?>"><?php _e( 'Show format IDs' ); ?></label></p>
<?php
	}

}

function Ambrosite_Widget_Post_Formats_init() {
	register_widget('Ambrosite_Widget_Post_Formats');
}

add_action('widgets_init', 'Ambrosite_Widget_Post_Formats_init');
?>