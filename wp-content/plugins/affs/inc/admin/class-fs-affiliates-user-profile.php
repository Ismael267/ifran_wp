<?php
/*
 * User Profile
 */

if ( !defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}
if ( !class_exists( 'FS_Affiliates_User_Profile' ) ) {

    /**
     * FS_Affiliates_User_Profile Class
     */
    class FS_Affiliates_User_Profile {

        /**
         * Class initialization
         */
        public static function init() {

            add_action( 'user_new_form' , array ( __CLASS__ , 'add_custom_register_data' ) ) ;
            add_action( 'show_user_profile' , array ( __CLASS__ , 'add_custom_affiliate_data' ) ) ;
            add_action( 'edit_user_profile' , array ( __CLASS__ , 'add_custom_affiliate_data' ) ) ;

            add_action( 'user_register' , array ( __CLASS__ , 'register_affiliate' ) ) ;
            add_action( 'deleted_user' , array ( __CLASS__ , 'delete_affiliate' ) , 10 , 2 ) ;
        }

        /**
         * Add custom register data
         */
        public static function add_custom_register_data( $operation ) {
            if ( 'add-new-user' !== $operation ) {
                return ;
            }

            $create_affiliate  = (isset( $_POST[ 'fs_affiliates_create_affiliate' ] )) ? 'checked="checked"' : '' ;
            $send_notification = (isset( $_POST[ 'fs_affiliates_send_notification' ] )) ? 'checked="checked"' : '' ;
            ?>
            <table class="form-table">
                <tr>
                    <th>
                        <label><?php esc_html_e( 'Register as Affiliate' , FS_AFFILIATES_LOCALE ) ; ?></label>
                    </th>
                    <td>
                        <input type="checkbox" name="fs_affiliates_create_affiliate" <?php echo $create_affiliate ; ?>/>
                    </td>
                </tr>
                <tr>
                    <th>
                        <label><?php esc_html_e( 'Notify Affiliate' , FS_AFFILIATES_LOCALE ) ; ?></label>
                    </th>
                    <td>
                        <input type="checkbox" name="fs_affiliates_send_notification" <?php echo $send_notification ; ?>/>
                    </td>
                </tr>
            </table>
            <?php
        }

        /**
         * Add custom Affiliate data
         */
        public static function add_custom_affiliate_data( $user ) {

            $affiliate_id = self::parse_postdata( $user->ID ) ;

            if ( $affiliate_id ) {
                $status = fs_affiliates_get_status_display( get_post_status( $affiliate_id ) ) ;

                $delete_url = remove_query_arg( 'fs_affiliates_register' , add_query_arg( array ( 'fs_affiliates_delete' => 'yes' ) ) ) ;
                $edit_url   = add_query_arg( array ( 'id' => $affiliate_id ) , admin_url( 'admin.php?page=fs_affiliates&tab=affiliates&section=edit' ) ) ;

                $button = '<a class="button" href="' . $delete_url . '" title="' . __( 'Delete Affiliate' , FS_AFFILIATES_LOCALE ) . '">' . __( 'Delete' ) . '</a>' ;
                $button .= '<a class="button" href="' . $edit_url . '" title="' . __( 'Edit Affiliate' , FS_AFFILIATES_LOCALE ) . '">' . __( 'Edit' ) . '</a>' ;
            } else {
                $status = __( 'Not Registered' , FS_AFFILIATES_LOCALE ) ;

                $register_url = remove_query_arg( 'fs_affiliates_delete' , add_query_arg( array ( 'fs_affiliates_register' => 'yes' ) ) ) ;
                $button       = '<a class="button" href="' . $register_url . '" title="' . __( 'Register this user as Affiliate' , FS_AFFILIATES_LOCALE ) . '">' . __( 'Register' ) . '</a>' ;
            }
            ?> <h2><?php _e( 'SUMO Affiliates Pro' , FS_AFFILIATES_LOCALE ) ; ?></h2>
            <table class="form-table">
                <tr>
                    <th><?php _e( 'Affiliate Status' , FS_AFFILIATES_LOCALE ) ?></th>
                    <td><?php echo $status ; ?></td>
                </tr>
                <tr>
                    <th><?php _e( 'Actions' , FS_AFFILIATES_LOCALE ) ?></th>
                    <td><?php echo $button ; ?></td>
                </tr>
            </table>
            <?php
        }

        /**
         *  Parse post data
         */
        public static function parse_postdata( $user_id ) {

            if ( isset( $_GET[ 'fs_affiliates_register' ] ) && 'yes' == $_GET[ 'fs_affiliates_register' ] ) {
                $affiliate_id = fs_affiliates_change_user_as_affiliate( $user_id ) ;
            } elseif ( isset( $_GET[ 'fs_affiliates_delete' ] ) && 'yes' == $_GET[ 'fs_affiliates_delete' ] ) {
                self::delete_affiliate( $user_id , false ) ;

                $affiliate_id = false ;
            } else {
                $affiliate_id = fs_affiliates_is_user_having_affiliate( $user_id ) ;
            }

            return $affiliate_id ;
        }

        /**
         *  Register a affiliate if needed
         */
        public static function register_affiliate( $user_id ) {
            if ( !isset( $_POST[ 'fs_affiliates_create_affiliate' ] ) )
                return ;

            fs_affiliates_change_user_as_affiliate( $user_id ) ;
        }
     
        /**
         *  Delete a affiliate if needed
         */
        public static function delete_affiliate( $user_id , $reassign ) {

            if ( !($affiliate_id = fs_affiliates_is_user_having_affiliate( $user_id ) ) )
                return ;

            fs_affiliates_delete_affiliate( $affiliate_id , false ) ;
        }

    }

    FS_Affiliates_User_Profile::init() ;
}