<?php
/**
 * @version    1.0
 * @package    Material Lite
 * @author     Ghuwad Team <contact@ghuwad.com>
 * @license    GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 *
 * Websites: http://www.ghuwad.com
 */
?>
<article id="post-<?php the_ID(); ?>" <?php post_class('section--center mdl-card mdl-shadow--2dp'); ?>>
    <header>
        <h1><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></h1>
        <div class="meta bordered">
            <?php material_lite_meta();?>
        </div>
    </header>
	<?php if ( has_post_thumbnail() && !post_password_required()) : ?>
	<div class="entry-thumb mdl-card__media mdl-shadow--2dp">
		<?php the_post_thumbnail('full'); ?>
	</div>
	<?php endif; ?>

    <div class="entry-content">
    	<?php the_content(); ?>
        <?php wp_link_pages( array( 'before' => '<div class="page-links"><span class="page-links-title">' . __('Pages:', 'material-lite') . '</span>', 'after' => '</div>', 'link_before' => '<span>', 'link_after' => '</span>' ) ); ?>
    </div>
</article>