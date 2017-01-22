<?php
/**
 * @version    1.0
 * @package    Material Lite
 * @author     Ghuwad Team <contact@ghuwad.com>
 * @license    GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 *
 * Websites: http://www.ghuwad.com
 */

get_header(); ?>
<div class="mdl-grid site-width">
	<div id="primary" class="content-area mdl-cell mdl-cell--<?php echo material_lite__tx('site_layout', 'one-column') || !is_active_sidebar( 'primary-sidebar' ) ? '12':'9'; ?>-col-desktop mdl-cell--9-col-tablet mdl-cell--4-col-phone">
		<div id="content" class="site-content" role="main">

			<?php while ( have_posts() ) : the_post(); ?>
				<?php get_template_part( 'content', 'attachment' ); ?>
				<?php material_lite_post_nav(); ?>
				<?php comments_template(); ?>

			<?php endwhile; ?>

		</div>
	</div>
	<?php get_sidebar(); ?>
</div>
<?php get_footer(); ?>