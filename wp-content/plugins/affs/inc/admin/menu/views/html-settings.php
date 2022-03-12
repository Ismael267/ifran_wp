<?php
/* Admin HTML Settings */

if ( ! defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}
?>

<div class = "wrap <?php echo self::$plugin_slug ; ?>_wrapper_cover">
    <h2></h2>
    <div class = "<?php echo self::$plugin_slug ; ?>_header">
        <div class = "<?php echo self::$plugin_slug ; ?>_title"><h2><?php _e( 'SUMO Affiliates Pro' , FS_AFFILIATES_LOCALE ) ; ?></h2></div>
        <div class="<?php echo self::$plugin_slug ; ?>_darklight_btn">
            <div class="<?php echo self::$plugin_slug ; ?>_darklight_content">
                <div class="<?php echo self::$plugin_slug ; ?>_switch_one">
                    <input class="fs_affiliates_settings_color_mode" type="radio" name="dark_btn" value="1" id="one" <?php checked( $color_mode , '1' ) ; ?>>
                    <label for="one"><?php _e( 'Dark Theme' , FS_AFFILIATES_LOCALE ) ; ?></label>
                </div>
                <div class="<?php echo self::$plugin_slug ; ?>_switch_two">
                    <input class="fs_affiliates_settings_color_mode" type="radio" name="dark_btn" value="2" id="two" <?php checked( $color_mode , '2' ) ; ?>>
                    <label for="two"><?php _e( 'Light Theme' , FS_AFFILIATES_LOCALE ) ; ?></label>
                </div>
            </div>
        </div>
        <div class = "<?php echo self::$plugin_slug ; ?>_logo"><img src = "<?php echo FS_AFFILIATES_PLUGIN_URL . '/assets/images/tab/logo.png' ?>"></div>
    </div>
    <form method = "post" action = "" enctype = "multipart/form-data">
        <div class = "<?php echo self::$plugin_slug ; ?>_wrapper">
            <ul class = "nav-tab-wrapper <?php echo self::$plugin_slug ; ?>_tab_ul">
                <?php
                foreach ( $tabs as $name => $label ) {
                    $img_name = ($current_tab == $name) ? $name . '_active' : $name ;
                    $img_name = ($color_mode == '2') ? 'light/' . $img_name : $img_name ;
                    ?>
                    <li class="<?php echo self::$plugin_slug ; ?>_tab_li <?php echo $name . '_li' ; ?>">
                        <a href="<?php echo admin_url( 'admin.php?page=' . self::$plugin_slug . '&tab=' . $name ) ; ?>" class="nav-tab <?php echo self::$plugin_slug ; ?>_tab_a <?php echo $name . '_a ' . ( $current_tab == $name ? 'nav-tab-active' : '' ) ?>">
                            <img src="<?php echo FS_AFFILIATES_PLUGIN_URL . '/assets/images/tab/' . $img_name . '.png' ?>" target=""/>
                            <span><?php echo $label ; ?></span>
                        </a>
                    </li>
                    <?php
                }
                ?>
            </ul>
            <div class="<?php echo self::$plugin_slug ; ?>_tab_content">
                <?php
                /* Display Sections */
                do_action( self::$plugin_slug . '_sections_' . $current_tab ) ;
                ?>
                <div class="<?php echo self::$plugin_slug ; ?>_tab_inner_content">
                    <?php
                    /* Display Error or Warning Messages */
                    self::show_messages() ;

                    /* Display Tab Content */
                    do_action( self::$plugin_slug . '_settings_' . $current_tab ) ;

                    /* Display Reset and Save Button */
                    do_action( self::$plugin_slug . '_settings_buttons_' . $current_tab ) ;

                    /* Extra fields after setting button */
                    do_action( self::$plugin_slug . '_after_setting_buttons_' . $current_tab ) ;
                    ?>
                </div>
            </div>
        </div>
    </form>
</div>
<?php
do_action( self::$plugin_slug . '_settings_end' ) ;
