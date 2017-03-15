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

	</div>
    <footer id="colophon" class="site-footer mdl-mega-footer" role="contentinfo">
        <?php
        $footerColumnStatus = array(1=>true, true, true, true);
        $totalEnableColumn = 0;
        foreach ($footerColumnStatus as $column => $status) {
            if ($status && is_active_sidebar('footer-column-'.$column)) {
                $totalEnableColumn++;
            }
        }
        if ($totalEnableColumn > 0) :
        ?>
        <div class="mdl-grid footer-top footer-con full">
            <?php
            switch ($totalEnableColumn) {
                case 4:
                    $footerColumnClass = 'mdl-cell--3-col-desktop mdl-cell--9-col-tablet mdl-cell--4-col-phone';
                    break;
                case 3:
                    $footerColumnClass = 'mdl-cell--4-col-desktop mdl-cell--9-col-tablet mdl-cell--4-col-phone';
                    break;
                case 2:
                    $footerColumnClass = 'mdl-cell--6-col-desktop mdl-cell--9-col-tablet mdl-cell--4-col-phone';
                    break;
                default:
                    $footerColumnClass = 'mdl-cell--12-col-desktop mdl-cell--9-col-tablet mdl-cell--4-col-phone';
                    break;
            }
            foreach ($footerColumnStatus as $column => $status) {
                if ($status) {
                    ?>
                    <div class="footer-col mdl-cell <?php echo $footerColumnClass;?>">
                        <?php dynamic_sidebar( 'footer-column-'.$column ); ?>
                    </div>
                    <?php
                }
            }
            ?>
        </div>
        <?php endif; ?>
        <?php
        $socials = material_lite__var('social_fields');
        $social_icons = '';
        foreach ($socials as $social) {
            $v = material_lite__t('social_'.$social['field']);
            if ($v != '') {
                $social_icons .= '<a href="'.esc_url($v).'" class="mdl-mini-footer--social-btn social-btn" target="_blank"></a>';
            }
        }
        if($social_icons != '' or 1) {
        ?>
        <div class="mdl-color--grey-900 social-icons text-center">
            <?php echo $social_icons;?>
        </div>
        <?php }?>
        <div class="mdl-color--black">
            <div class="site-info full footer-con text-center">
                <a href="<?php echo esc_url( __( 'http://wordpress.org/', 'material-lite' ) ); ?>" ><?php _e('Proudly powered by WordPress', 'material-lite' ); ?></a><br/><a href="<?php echo esc_url('http://ghuwad.com'); ?>"><?php _e('Developed with <i class="material-icons mdl-color-text--red">favorite</i> by Ghuwad.com', 'material-lite'); ?></a>
            </div>
        </div>
    </footer>
</div>
<?php wp_footer(); ?>
</body>
</html>