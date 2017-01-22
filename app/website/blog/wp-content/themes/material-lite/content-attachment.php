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
            <?php material_lite_meta(array('publised_in', 'date', 'attachment'));?>
        </div>
    </header>
    <div class="entry-content">
        <div class="entry-thumb mdl-card__media mdl-shadow--2dp">
            <?php echo wp_get_attachment_image( $post->ID, 'full' ); ?>
        </div>
    </div>
</article>