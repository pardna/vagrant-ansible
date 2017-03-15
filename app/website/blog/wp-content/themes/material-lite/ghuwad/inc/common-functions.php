<?php

if ( ! function_exists( 'material_lite_list_comments' ) ) :
function material_lite_list_comments( $comment, $args, $depth ) {
	switch ( $comment->comment_type ) :

		case 'pingback'  :
		case 'trackback' :
			?>
			<li <?php comment_class(); ?> id="comment-<?php comment_ID(); ?>">
				<p>
					<?php
					_e( 'Pingback: ', 'material-lite' );
					comment_author_link();
					$link = get_edit_comment_link();
                	if ($link) {
                		echo '<div class="comment__actions">';
                		echo '<a href="'.$link.'" class="comment-edit-link mdl-button mdl-js-button mdl-button--with-icon mdl-button--raised mdl-js-ripple-effect mdl-button--accent"><i class="material-icons">edit</i> '.__( 'Edit', 'material-lite' ).'</a>';
                		echo '</div>';
                	}
					?>
				</p>
			<?php
		break;

		default :
			global $post;
			?>
			<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
				<div id="comment-<?php comment_ID(); ?>" class="comment mdl-color-text--grey-700">
	                <header class="comment__header">
						<?php echo get_avatar( $comment, 48 ); ?>
						<div class="comment__author">
						    <strong><?php echo get_comment_author_link();?></strong>
						    <span><?php echo
							'<a href="'.get_comment_link( $comment->comment_ID ).'"><time>'.get_comment_date().' '.get_comment_time().'</time></a>';?></span>
						</div>
	                </header>
                	<div class="comment__text"><?php
						comment_text();
						?></div>
	                <nav class="comment__actions">
	                	<?php
	                	$link = get_edit_comment_link();
	                	if ($link) {
	                		echo '<a href="'.$link.'" class="comment-edit-link mdl-button mdl-js-button mdl-button--with-icon mdl-button--raised mdl-js-ripple-effect mdl-button--accent"><i class="material-icons">edit</i> '.__( 'Edit', 'material-lite' ).'</a>';
	                	}
						echo preg_replace( '/comment-reply-link/', 'comment-reply-link mdl-button mdl-js-button mdl-button--with-icon mdl-button--raised mdl-js-ripple-effect mdl-button--accent', get_comment_reply_link(
							array_merge(
								$args,
								array(
									'reply_text' => '<i class="material-icons">reply</i> '.__( 'Reply', 'material-lite' ),
									'depth'      => $depth,
									'max_depth'  => $args['max_depth'],
								)
							)
						) );
						?>
	                </nav>
              	</div>
			<?php
		break;
	endswitch;
}
endif;

if ( ! function_exists( 'material_lite_meta' ) ) :
function material_lite_meta($opt = array('author', 'date', 'category', 'tag'), $echo = 1) {
	$ret = '';
	if (in_array('author', $opt)) {
		$ret = '<span class="author vcard"><i class="material-icons">person</i> <a href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a></span>';
	}
	if (in_array('category', $opt)) {
		$categories = get_the_category_list( __(', ', 'material-lite') );
		if ( $categories ) :
			$ret .= '<span class="cat-links">';
			 	$ret .= '<i class="material-icons">folder_open</i>'. $categories ;
			$ret .= '</span>';
		endif;
	}
	if (in_array('tag', $opt)) {
		$tags = get_the_tag_list( '', __(', ', 'material-lite') );
		if ( $tags ) :
			$ret .= '<span class="tags-links">';
			 	$ret .= '<i class="material-icons">local_offer</i>'. $tags ;
			$ret .= '</span>';
		endif;
	}
	if (in_array('date', $opt)) {
		$ret .= '<span class="date"><i class="material-icons">access_time</i> <a href="'.esc_url(get_permalink()).'">'.get_the_date().'</a></span>';
	}
	if (in_array('read_more', $opt)) {
		$ret .= '<span class="read_more"><i class="material-icons">launch</i> <a href="'.esc_url(get_permalink()).'">'.__('Read More', 'material-lite').'</a></span>';
	}
	if (in_array('attachment', $opt)) {
		$metadata = wp_get_attachment_metadata();
		$ret .= sprintf( '<span class="attachment-meta full-size-link"><i class="material-icons">zoom_in</i> <a href="%1$s" title="%2$s">%3$s (%4$s &times; %5$s)</a></span>',
				esc_url( wp_get_attachment_url() ),
				esc_attr__( 'Link to full-size image', 'material-lite' ),
				__( 'Full resolution', 'material-lite' ),
				$metadata['width'],
				$metadata['height']
			);
	}
	if (in_array('publised_in', $opt)) {
		global $post;
        $ret .= '<span class="publish-in"><i class="material-icons">publish</i> '.__('Published in', 'material-lite').' <a href="'.esc_url(get_permalink( $post->post_parent )).'">'.esc_html(get_the_title($post->post_parent)).'</a></span>';
    }
    $elink = get_edit_post_link();
	if ($elink) {
		$ret .= '<span class="edit-link"><i class="material-icons">edit</i> <a href="'.esc_url(get_edit_post_link()).'">'.__( 'Edit', 'material-lite' ).'</a></span>';
	}
	if ($echo) {
		echo $ret;
	}
	return $ret;
}
endif;

if ( ! function_exists( 'material_lite_strip_shortcode' ) ) :
function  material_lite_strip_shortcode( $content, $remove_shortcode ) {
	return;
    preg_match_all( '/'. get_shortcode_regex() .'/s', $content, $matches, PREG_SET_ORDER );
    if ( ! empty( $matches ) ) {
        foreach ( $matches as $shortcode ) {
            if ( $remove_shortcode === $shortcode[2] ) {
                $pos = strpos( $content, $shortcode[0] );
                if ($pos !== false)
                    return substr_replace( $content, '', $pos, strlen($shortcode[0]) );
            }
        }
    }
    return $content;
}
endif;

if ( ! function_exists( 'material_lite__t' ) ) :
function material_lite__t ($option, $echo = false) {
	if ($echo) {
		echo Material_Lite_Theme::get( $option, 'settings' );
	} else {
		return Material_Lite_Theme::get( $option, 'settings' );
	}
}
endif;


if ( ! function_exists( 'material_lite__var' ) ) :
function material_lite__var ($var, $key = '', $echo = false) {
	if ($echo) {
		echo Material_Lite_Theme::get( $var, $key );
	} else {
		return Material_Lite_Theme::get( $var, $key );
	}
}
endif;

if ( ! function_exists( 'material_lite__tx' ) ) :
function material_lite__tx($option, $value = '', $op = '=') {
	switch ($op) {
		case "=":  return material_lite__t($option) == $value;
        case "!=": return material_lite__t($option) != $value;
        case ">=": return material_lite__t($option) >= $value;
        case "<=": return material_lite__t($option) <= $value;
        case ">":  return material_lite__t($option) >  $value;
        case "<":  return material_lite__t($option) <  $value;
	}
}
endif;

if ( ! function_exists( 'material_lite_post_nav' ) ) :
	function material_lite_post_nav() {
		if (is_singular()) {
		?>
        <nav class="post-navigation mdl-color-text--grey-50 mdl-cell mdl-cell--12-col">
            <?php previous_post_link( '<div class="previous">%link</div>', '<button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon mdl-color--grey mdl-color-text--white"> <i class="material-icons">'.__('arrow_back', 'material-lite').'</i></button> '.__('Older', 'material-lite') ); ?>
            <div class="section-spacer"></div>
            <?php next_post_link( '<div class="next">%link</div>', __('Newer', 'material-lite').' <button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon mdl-color--grey mdl-color-text--white"> <i class="material-icons">'.__('arrow_forward', 'material-lite').'</i></button> ' );
            ?>
        </nav>
		<?php
		} else {
			global $wp_query, $wp_rewrite;
			if ( $GLOBALS['wp_query']->max_num_pages < 2 ) {
				return;
			}
			$query        = $wp_query;
			$max          = $query->max_num_pages;
			$current_page = max( 1, get_query_var( 'paged' ) );
			$big          = 999999;
		?>
			<nav class="page-nav" role="navigation">
				<?php
				$plinks = paginate_links(
					array(
						'base'      => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
						'format'    => '?paged=%#%',
						'current'   => $current_page,
						'total'     => $max,
						'type'      => 'array',
						'prev_text' => '<i class="material-icons">'.__( 'arrow_back', 'material-lite' ).'</i>',
						'next_text' => '<i class="material-icons">'.__( 'arrow_forward', 'material-lite' ).'</i>'
					)
				);
				foreach ($plinks as $link) {
					echo str_replace('page-numbers', 'page-numbers mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon mdl-color--grey mdl-color-text--white', $link);
				}
				?>
			</nav>
			<?php
		}
	}
endif;

if ( ! function_exists( 'material_lite_sanitize_site_width' ) ) :
function material_lite_sanitize_site_width($value) {
    preg_match("/(\d*)(px|%)?/", $value, $matches);
    if (strlen($matches[1]) < 1) {
        $value = '1080px';
    } else if (empty($matches[2])) {
        $value = $matches[1].'px';
    }
    return $value;
}
endif;

if ( ! function_exists( 'material_lite_sanitize_site_layout' ) ) :
function material_lite_sanitize_site_layout($value) {
    if (!in_array($value, array( 'one-column', 'two-column'))) {
        $value = 'two-column';
    }
    return $value;
}
endif;

if ( ! function_exists( 'material_lite_sanitize_display_favicon' ) ) :
function material_lite_sanitize_display_favicon($value) {
    if (empty($value) || !in_array($value, array(1, 0))) {
        $value = 0;
    }
    return $value;
}
endif;

if ( ! function_exists( 'material_lite_sanitize_c_header_size' ) ) :
function material_lite_sanitize_c_header_size($value) {
    if (!in_array($value, array( 'small', 'big'))) {
        $value = 'big';
    }
    return $value;
}
endif;

if ( !function_exists('material_lite_pr') ):
	function material_lite_pr($obj, $val=null) {
	    echo "<pre>";
	    echo str_repeat("-",10)."Start".str_repeat("-",10)."<br>";
	        print_r($obj);
	    echo "<br>".str_repeat("-",10)."End".str_repeat("-",10);
	    echo "</pre>";
	    if($val)
	        exit;
	}
endif;

if ( !function_exists('material_lite_theme_favicon') ):
	function material_lite_theme_favicon() {
        $favicon_support = version_compare(get_bloginfo('version'), 4.3, '>=');
        if (!$favicon_support &&  material_lite__t('display_favicon')) {
            echo '<link rel="shortcut icon" id="favicon" href="'.esc_url(material_lite__t('favicon')),'" />';
        }
	}
endif;