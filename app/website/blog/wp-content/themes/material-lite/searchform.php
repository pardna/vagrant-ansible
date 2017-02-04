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
 <form role="search" method="get" id="searchform" class="searchform mdl-color--white mdl-shadow--2dp" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<div class="mdl-textfield mdl-js-textfield mdl-color-text--primary">
	    <input class="mdl-textfield__input" name="s" value="<?php echo get_search_query(); ?>" type="text" required1 id="search" />
	    <label class="mdl-textfield__label" for="search">Search...</label>
	    <span class="mdl-textfield__error">Please enter text!</span>
	</div>
	<button type="submit" class="mdl-button mdl-js-button mdl-button--fab mdl-button--mini-fab mdl-button--colored mdl-js-ripple-effect">
  		<i class="material-icons">search</i>
	</button>
</form>