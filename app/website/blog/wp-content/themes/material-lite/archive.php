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
<header class="archive-header mdl-color--grey-400 mdl-color-text--grey-900">
	<h1 class="archive-title"><?php
		if ( is_category() ) :
			single_cat_title();
		elseif ( is_tag() ) :
			single_tag_title();
		elseif ( is_author() ) :
			printf( __( 'Author: %s', 'material-lite' ), '<span class="vcard">' . get_the_author() . '</span>' );
		elseif ( is_day() ) :
			printf( __( 'Day: %s', 'material-lite' ), '<span>' . get_the_date() . '</span>' );
		elseif ( is_month() ) :
			printf( __( 'Month: %s', 'material-lite' ), '<span>' . get_the_date( _x( 'F Y', 'monthly archives date format', 'material-lite' ) ) . '</span>' );
		elseif ( is_year() ) :
			printf( __( 'Year: %s', 'material-lite' ), '<span>' . get_the_date( _x( 'Y', 'yearly archives date format', 'material-lite' ) ) . '</span>' );
		else :
			_e( 'Archives', 'material-lite' );
		endif;
	?></h1>
</header>
<div class="mdl-grid site-width">
	<div id="primary" class="content-area mdl-cell mdl-cell--<?php echo material_lite__tx('site_layout', 'one-column') || !is_active_sidebar( 'primary-sidebar' ) ? '12':'9'; ?>-col-desktop mdl-cell--9-col-tablet mdl-cell--4-col-phone">
		<div id="content" class="site-content" role="main">
		<?php if ( have_posts() ) : ?>

			<?php while ( have_posts() ) : the_post(); ?>
				<?php get_template_part( 'content-card', get_post_format() ); ?>
			<?php endwhile; ?>

			<?php material_lite_post_nav(); ?>

		<?php else : ?>
			<?php get_template_part( 'content', 'none' ); ?>
		<?php endif; ?>

		</div>
	</div>
<?php get_sidebar(); ?>
</div>
<?php get_footer(); ?>