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
<header class="page-header mdl-color--grey-400 mdl-color-text--grey-900">
	<h1 class="page-title"><?php _e( 'Not Found', 'material-lite' ); ?></h1>
</header>
<div class="mdl-grid site-width">
	<div id="primary" class="content-area mdl-card mdl-shadow--2dp mdl-cell mdl-cell--12-col-desktop mdl-cell--9-col-tablet mdl-cell--4-col-phone text-center">
		<div id="content" class="site-content " role="main">
			<div class="page-wrapper">
				<div class="page-content">
					<h2><?php _e( 'The page cannot be found', 'material-lite' ); ?></h2>
					<p><?php
					_e( 'The page you are looking for might have been removed, had its name changed or is temporarily unavailable', 'material-lite');
					echo '<br/>';
					_e('Can\'t find what you need? Take a moment and do a search below!', 'material-lite' ); ?></p>
					<?php get_search_form(); ?>
				</div>
			</div>
		</div>
	</div>
</div>
<?php get_footer(); ?>