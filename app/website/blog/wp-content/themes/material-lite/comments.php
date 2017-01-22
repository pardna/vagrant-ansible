<?php
/**
 * @version    1.0
 * @package    Material Lite
 * @author     Ghuwad Team <contact@ghuwad.com>
 * @license    GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 *
 * Websites: http://www.ghuwad.com
 */

if ( post_password_required() )
	return;
?>

<div id="comments" class="comments-area section--center mdl-card mdl-shadow--2dp">
	<?php if ( ! comments_open() && !get_comments_number() ) : ?>
	<div class="no-comments alert__warning"><i class="material-icons">warning</i><?php _e( 'Comments are closed.', 'material-lite' ); ?></div>
	<?php endif; ?>
	<?php if ( have_comments() ) : ?>
		<h2 class="comments-title">
			<?php
			$num_comments = get_comments_number();
			if ( $num_comments > 1 ) {
				_e('Comments', 'material-lite');
			} else {
				_e('Comment', 'material-lite');
			}
			echo '<button class="mdl-button counter mdl-button--fab" disabled>'.$num_comments.'</button>';
			?>
		</h2>

		<ol class="comment-list">
			<?php
				wp_list_comments( array(
					'style'    => 'ol',
					'callback' => 'material_lite_list_comments',
				) );
			?>
			<?php
				if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) :
			?>
			<li class="comment-nav mdl-color--grey-100">
				<nav class="comment-navigation  mdl-color-text--grey-50 mdl-cell mdl-cell--12-col">
		            <div class="previous"><?php previous_comments_link( '<button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon mdl-color--grey mdl-color-text--white"> <i class="material-icons">'.__('arrow_back', 'material-lite').'</i></button> '.__('Older Comments', 'material-lite')); ?>
		            </div>
		            <div class="section-spacer"></div>
		            <div class="next">
		            <?php next_comments_link( __('Newer Comments', 'material-lite').' <button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon mdl-color--grey mdl-color-text--white"> <i class="material-icons">'.__('arrow_forward', 'material-lite').'</i></button> ' );
		            ?>
		            </div>
		        </nav>
			</li>
			<?php endif; ?>
		</ol>


		<?php if ( ! comments_open() && get_comments_number() ) : ?>
		<div class="no-comments alert__warning"><i class="material-icons">warning</i><?php _e( 'Comments are closed.', 'material-lite' ); ?></div>
		<?php endif; ?>

	<?php endif; ?>

	<?php
	$commenter = wp_get_current_commenter();
	$req = get_option( 'require_name_email' );
	$aria_req = ( $req ? " aria-required='true'" : '' );

	$args = array(
		'fields' => array(
			'author' => '<div class="comment-form-author mdl-textfield mdl-js-textfield mdl-textfield--floating-label"><input class="mdl-textfield__input" type="text" name="author" id="author" value="' . esc_attr( $commenter['comment_author'] ) . '"' . $aria_req . ' /><label class="mdl-textfield__label" for="author">'.__( 'Name', 'material-lite' ).( $req ? ' <span class="required">*</span>' : '' ).'</label></div>',
			'email' => '<div class="comment-form-email mdl-textfield mdl-js-textfield mdl-textfield--floating-label"><input class="mdl-textfield__input" type="text" name="email" id="email" value="' . esc_attr( $commenter['comment_author_email'] ) . '"' . $aria_req . ' /><label class="mdl-textfield__label" for="email">'.__( 'Email', 'material-lite' ).( $req ? ' <span class="required">*</span>' : '' ).'</label></div>',
			'url' => '<div class="comment-form-url mdl-textfield mdl-js-textfield mdl-textfield--floating-label"><input class="mdl-textfield__input" type="text" name="url" id="url" value="' . esc_attr( $commenter['comment_author_url'] ) . '" /><label class="mdl-textfield__label" for="author">'.__( 'Website', 'material-lite' ).'</label></div>'
		),
		'comment_field' => '<div class="comment-form-comment mdl-textfield mdl-js-textfield mdl-textfield--floating-label"><textarea class="mdl-textfield__input" id="comment" name="comment" type="text" rows="3"></textarea><label class="mdl-textfield__label" for="comment">'. __( 'Write your comment', 'material-lite' ).'</label></div>',
		'class_submit' => 'hide',
		'comment_notes_after' => '<p class="form-allowed-tags">' . sprintf(__( 'You may use these <abbr title="HyperText Markup Language">HTML</abbr> tags and attributes: %s', 'material-lite'), ' <code>' . allowed_tags() . '</code>' ) . '</p><button type="submit" class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect mdl-button--accent">'.__('Post Comment', 'material-lite').'</button>'

	);
	comment_form($args);
	?>
</div>