<?php
/**
 * @version    1.0
 * @package    Material Lite
 * @author     Ghuwad Team <contact@ghuwad.com>
 * @license    GNU/GPL v2 or later http://www.gnu.org/licenses/gpl-2.0.html
 *
 * Websites: http://www.ghuwad.com
 */
if ( material_lite__tx('site_layout', 'two-column') && is_active_sidebar( 'primary-sidebar' ) ) : ?>
	<div id="primary-sidebar" class="sidebar-container mdl-cell mdl-cell--3-col-desktop mdl-cell--9-col-tablet mdl-cell--4-col-phone" role="complementary">
		<div class="sidebar-inner">
			<div class="widget-area">
				<?php dynamic_sidebar( 'primary-sidebar' ); ?>
			</div>
		</div>
	</div>
<?php endif; ?>