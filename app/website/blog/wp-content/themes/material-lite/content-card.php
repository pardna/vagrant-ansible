<?php
/**
 * @version    1.0
 * @package    Material Lite
 * @author     Ghuwad Team <contact@ghuwad.com>
 * @license    GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 *
 * Websites: http://www.ghuwad.com
 */

$bClass = 'mdl-cell--12-col-desktop mdl-cell--12-col-tablet mdl-cell--12-col-phone';
?>
<article id="post-<?php the_ID(); ?>" <?php post_class('section--center gm-card mdl-card mdl-grid mdl-grid--no-spacing mdl-shadow--2dp'); ?>>
	<?php if (is_sticky()) {
		echo '<i id="sticky-posts'.get_the_ID().'" class="material-icons sticky-post-icon icon material-icons">attach_file</i><div class="mdl-tooltip" for="sticky-posts'.get_the_ID().'">',__('Sticky Post', 'material-lite').'</div>';
	} ?>
	<?php if ( has_post_thumbnail() && !post_password_required() && !is_attachment() ) :
	$bClass = 'mdl-cell--8-col-desktop mdl-cell--5-col-tablet mdl-cell--4-col-phone';
	?>

	<header class="mdl-cell mdl-cell--4-col-desktop mdl-cell--3-col-tablet mdl-cell--4-col-phone mdl-color--grey-300 mdl-color-text--white">
		<div class="entry-thumbnail">
			<?php the_post_thumbnail('cthumb'); ?>
		</div>
	</header>
	<?php endif; ?>
	<div class="mdl-card mdl-cell <?php echo $bClass;?>">
		<div class="mdl-card__supporting-text">
            <h1><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></h1>
            <div class="entry-summary">
            	<?php the_excerpt(); ?>
            </div>
        </div>
    	<div class="meta mdl-card__actions">
            <?php material_lite_meta(array('author', 'date', 'read_more'));?>
            <?php if ( comments_open() && ! is_single() ) : ?>
				<?php comments_popup_link( '<button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon mdl-color--white mdl-color-text--grey-900"><i class="material-icons">mode_comment</i></button>', '1 <button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon mdl-color--white mdl-color-text--grey-900"><i class="material-icons">mode_comment</i></button>', '% <button class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon mdl-color--white mdl-color-text--grey-900"><i class="material-icons">mode_comment</i></button>'); ?>
			<?php endif;?>
        </div>
	</div>
</article>
