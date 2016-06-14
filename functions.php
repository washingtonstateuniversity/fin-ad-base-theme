<?php
//PSR-1/2 -ish 

class WSU_FinAd_BaseTheme
{
    /**
     * @var WSU_FinAd_BaseTheme
     */
    private static $instance;

    /**
     * Maintain and return the one instance and initiate hooks when
     * called the first time.
     *
     * @return \WSU_FinAd_BaseTheme
     */
    public static function getInstance() {
        if ( ! isset( self::$instance ) ) {
            self::$instance = new WSU_FinAd_BaseTheme;
        }
        return self::$instance;
    }
}
add_action( 'after_setup_theme', 'finAdBaseTheme' );
/**
 * Start things up.
 *
 * @return \WSU_FinAd_BaseTheme
 */
function finAdBaseTheme() {
    return WSU_FinAd_BaseTheme::getInstance();
}