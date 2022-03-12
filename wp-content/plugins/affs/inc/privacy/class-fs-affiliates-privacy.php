<?php
/*
 * GDPR Compliance
 */
if ( !defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly
}

if ( !class_exists( 'FS_Affiliates_Privacy' ) ) :

    /**
     * FS_Affiliates_Privacy class
     */
    class FS_Affiliates_Privacy {

        /**
         * FS_Affiliates_Privacy constructor.
         */
        public function __construct() {
            $this->init_hooks() ;
        }

        /**
         * Register Custom Register Fields plugin
         */
        public function init_hooks() {
            // This hook registers Custom Register Fields data exporters.
            add_action( 'admin_init' , array ( __CLASS__ , 'register_privacy_content' ) , 20 ) ;
        }

        /**
         * Register Custom Register Fields Privacy Content
         */
        public static function register_privacy_content() {
            if ( !function_exists( 'wp_add_privacy_policy_content' ) ) {
                return ;
            }

            $content = self::get_privacy_message() ;
            if ( $content ) {
                wp_add_privacy_policy_content( __( 'Affiliates Pro' , FS_AFFILIATES_LOCALE ) , $content ) ;
            }
        }

        /**
         * Prepare Privacy Content
         */
        public static function get_privacy_message() {

            $content = self::get_privacy_message_html() ;
            return $content ;
        }

        /**
         * Get Privacy Content
         */
        public static function get_privacy_message_html() {
            ob_start() ;
            ?>
            <p><?php echo __( 'This includes the basics of what personal data your store may be collecting, storing and sharing. Depending on what settings are enabled and which additional plugins are used, the specific information shared by your store will vary.' , FS_AFFILIATES_LOCALE ) ?></p>
            <h2><?php echo __( 'What the Plugin does?' , FS_AFFILIATES_LOCALE ) ; ?></h2>
            <p><?php echo __( '- Both Members(registered users) and guests can become Affiliates and promote the site.' , FS_AFFILIATES_LOCALE ) ; ?> </p>
            <p><?php echo __( '- The Affiliates will start to earn commissions whenever a user performs an action such as account signup, product purchase, form submission, etc.' , FS_AFFILIATES_LOCALE ) ; ?> </p>
            <p><?php echo __( '- Emails can be sent to the Affiliates for actions such as Affiliate Registration Request, Affiliate Approval, Affiliate Referral Earnings, Profile Updation, etc.' , FS_AFFILIATES_LOCALE ) ; ?> </p>
            <h2><?php echo __( 'What we collect and store?' , FS_AFFILIATES_LOCALE ) ; ?></h2>
            <h4><?php echo __( '- User ID' , FS_AFFILIATES_LOCALE ) ; ?></h4>
            <ul>
                <li>
                    <?php echo __( 'The User id is used for storing the commissions earned by the Affiliate.' , FS_AFFILIATES_LOCALE ) ; ?>
                </li>
            </ul>
            <h4><?php echo __( '- Payment Information' , FS_AFFILIATES_LOCALE ) ; ?></h4>
            <ul>
                <li>
                    <?php echo __( "The user's PayPal Email id/Payment details are collected for processing the affiliate payments." , FS_AFFILIATES_LOCALE ) ; ?>
                </li>
            </ul>
            <h4><?php echo __( '- Cookies' , FS_AFFILIATES_LOCALE ) ; ?></h4>
            <ul>
                <li>
                    <?php echo __( 'We use cookies to identify the actions which was completed using affiliate links.' , FS_AFFILIATES_LOCALE ) ; ?>
                </li>
                <li>
                    <h4><?php echo __( '- IP Address' , FS_AFFILIATES_LOCALE ) ; ?></h4>
                    <ul>
                        <li><?php echo __( 'We record the IP Address of the users who access the site using Affiliate Links. With the Help of IP Address, One Affiliate visit can be differentiated from the other.' , FS_AFFILIATES_LOCALE ) ; ?></li>
                    </ul>

                </li>
            </ul>
            <?php
            $contents = ob_get_contents() ;
            ob_end_clean() ;
            return $contents ;
        }

    }

    new FS_Affiliates_Privacy() ;

endif;
