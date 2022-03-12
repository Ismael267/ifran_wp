<?php
/**
 * Paypal payouts
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}

if ( ! class_exists( 'FS_Affiliates_url_masking' ) ) {

    /**
     * Class
     */
    class FS_Affiliates_url_masking extends FS_Affiliates_Modules {
        /*
         * Data
         */

        protected $data = array(
            'enabled'                     => 'no' ,
            'menu_label'                  => '' ,
            'no_of_domains'               => '' ,
            'restricted_domains'          => '' ,
            'hide_on_dashboard'           => '' ,
            'admin_email_notify'          => '' ,
            'admin_mail_subject'          => '' ,
            'domain_success'              => '' ,
            'domain_success_mail_subject' => '' ,
            'domain_success_mail_message' => '' ,
            'domain_fails'                => '' ,
            'domain_fails_mail_subject'   => '' ,
            'domain_fails_mail_message'   => '' ,
                ) ;

        /**
         * Class Constructor
         */
        public function __construct() {

            $this->id    = 'url_masking' ;
            $this->title = __( 'URL Masking' , FS_AFFILIATES_LOCALE ) ;

            parent::__construct() ;
        }

        /*
         * Get settings link
         */

        public function settings_link() {
            return add_query_arg( array( 'page' => 'fs_affiliates' , 'tab' => 'modules' , 'section' => $this->id ) , admin_url( 'admin.php' ) ) ;
        }

        public function settings_options_array() {

            global $current_sub_section ;

            switch ( $current_sub_section ) {

                case 'add_new_domain':
                    return array(
                        array(
                            'id'   => 'fs_affiliates_url_masking_fields' ,
                            'type' => 'output_url_masking_new'
                        ) ,
                            ) ;
                    break ;

                case 'edit':
                    return array(
                        array(
                            'id'   => 'fs_affiliates_url_masking_fields' ,
                            'type' => 'output_url_masking_edit'
                        ) ,
                            ) ;
                    break ;
                default :
                    return $this->url_masking_settings_options_array() ;
                    break ;
            }
        }

        public function url_masking_settings_options_array() {
            return array(
                array(
                    'type'  => 'title' ,
                    'title' => __( 'Affiliate URL Masking' , FS_AFFILIATES_LOCALE ) ,
                    'id'    => 'url_masking_display_options' ,
                ) ,
                array(
                    'title'   => __( 'Affiliate Menu Label' , FS_AFFILIATES_LOCALE ) ,
                    'desc'    => __( 'This label will be used for displaying the URL Masking menu  in the affiliate dashboard.' , FS_AFFILIATES_LOCALE ) ,
                    'id'      => $this->plugin_slug . '_' . $this->id . '_menu_label' ,
                    'type'    => 'text' ,
                    'default' => 'URL Masking' ,
                ) ,
                array(
                    'title'   => __( 'Number of Domains Allowed' , FS_AFFILIATES_LOCALE ) ,
                    'desc'    => __( 'You can restrict the number of domains through which your affiliates can make their promotion. When left empty, unlimited domains are allowed.' , FS_AFFILIATES_LOCALE ) ,
                    'id'      => $this->plugin_slug . '_' . $this->id . '_no_of_domains' ,
                    'type'    => 'text' ,
                    'default' => '' ,
                ) ,
                array(
                    'title'   => __( 'Restricted Domains' , FS_AFFILIATES_LOCALE ) ,
                    'desc'    => __( 'Enter the domains which you want to be excluded for affiliate promotion. Multiple domain names can be separated by a comma.' , FS_AFFILIATES_LOCALE ) ,
                    'id'      => $this->plugin_slug . '_' . $this->id . '_restricted_domains' ,
                    'type'    => 'textarea' ,
                    'default' => '' ,
                ) ,
                array(
                    'title'   => __( 'Hide Affiliate Links Menu on Frontend Affiliate Dashboard' , FS_AFFILIATES_LOCALE ) ,
                    'id'      => $this->plugin_slug . '_' . $this->id . '_hide_on_dashboard' ,
                    'type'    => 'checkbox' ,
                    'default' => 'no' ,
                    'desc'    => __( 'When enabled, the affiliate links menu will be hidden in the frontend affiliate dashboard.' , FS_AFFILIATES_LOCALE ) ,
                ) ,
                array(
                    'type' => 'sectionend' ,
                    'id'   => 'url_masking_display_options' ,
                ) ,
                array(
                    'type'  => 'title' ,
                    'title' => __( 'Admin Notification Settings' , FS_AFFILIATES_LOCALE ) ,
                    'id'    => 'url_masking_notifications_options' ,
                ) ,
                array(
                    'title'   => __( 'Notify Admin by Email for Domain Addition Request' , FS_AFFILIATES_LOCALE ) ,
                    'id'      => $this->plugin_slug . '_' . $this->id . '_admin_email_notify' ,
                    'type'    => 'checkbox' ,
                    'default' => 'yes' ,
                    'desc'    => __( 'When enabled, the site admin will be notified about a new domain addition request which has been submitted on the site.' , FS_AFFILIATES_LOCALE ) ,
                ) ,
                array(
                    'title'   => __( 'Email Subject' , FS_AFFILIATES_LOCALE ) ,
                    'id'      => $this->plugin_slug . '_' . $this->id . '_admin_mail_subject' ,
                    'type'    => 'text' ,
                    'default' => 'Affiliate Domain Addition Request Submitted on {site_name}' ,
                ) ,
                array(
                    'title'   => __( 'Email Message' , FS_AFFILIATES_LOCALE ) ,
                    'id'      => $this->plugin_slug . '_' . $this->id . '_admin_mail_message' ,
                    'type'    => 'wpeditor' ,
                    'default' => 'Hi,
An Affiliate {affiliate_name} has submitted a request to add the domain {domain_name}

To approve the request, click the following link{addition_approval_link}

To reject the request, click the following link{adition_rejection_link}

Thanks.
' ,
                ) ,
                array(
                    'type' => 'sectionend' ,
                    'id'   => 'url_masking_notifications_options' ,
                ) ,
                array(
                    'type'  => 'title' ,
                    'title' => __( 'Affiliate Notification Settings' , FS_AFFILIATES_LOCALE ) ,
                    'id'    => 'affiliate_notifications_options' ,
                ) ,
                array(
                    'title'   => __( 'Affiliate Email Notification' , FS_AFFILIATES_LOCALE ) ,
                    'id'      => $this->plugin_slug . '_' . $this->id . '_domain_success' ,
                    'type'    => 'checkbox' ,
                    'default' => 'no' ,
                    'desc'    => __( 'When enabled, the affiliate will be notified where there is an update on their domain addition request.' , FS_AFFILIATES_LOCALE ) ,
                ) ,
                array(
                    'title'   => __( 'Email Subject' , FS_AFFILIATES_LOCALE ) ,
                    'id'      => $this->plugin_slug . '_' . $this->id . '_domain_success_mail_subject' ,
                    'type'    => 'text' ,
                    'default' => 'Affiliate Domain Addition Request Status on {site_name}' ,
                ) ,
                array(
                    'title'   => __( 'Email Message' , FS_AFFILIATES_LOCALE ) ,
                    'id'      => $this->plugin_slug . '_' . $this->id . '_domain_success_mail_message' ,
                    'type'    => 'wpeditor' ,
                    'default' => 'Hi,
Your request to add the domain {domain_name} has been {domain_addition_status}

Thanks.
' ,
                ) ,
                array(
                    'title'   => __( 'Domain Addition Failure' , FS_AFFILIATES_LOCALE ) ,
                    'id'      => $this->plugin_slug . '_' . $this->id . '_domain_fails' ,
                    'type'    => 'checkbox' ,
                    'default' => 'no' ,
                ) ,
                array(
                    'title'   => __( 'Email Subject' , FS_AFFILIATES_LOCALE ) ,
                    'id'      => $this->plugin_slug . '_' . $this->id . '_domain_fails_mail_subject' ,
                    'type'    => 'text' ,
                    'default' => '' ,
                ) ,
                array(
                    'title'   => __( 'Email Message' , FS_AFFILIATES_LOCALE ) ,
                    'id'      => $this->plugin_slug . '_' . $this->id . '_domain_fails_mail_message' ,
                    'type'    => 'wpeditor' ,
                    'default' => '' ,
                ) ,
                array(
                    'type' => 'sectionend' ,
                    'id'   => 'affiliate_notifications_options' ,
                ) ,
                    ) ;
        }

        /*
         * Actions
         */

        public function actions() {
            add_filter( 'fs_affiliates_check_if_url_masking' , array( $this , 'check_if_url_masking_enabled' ) , 10 , 1 ) ;
        }

        /*
         * Admin actions
         */

        public function admin_action() {
            add_action( $this->plugin_slug . '_admin_field_output_url_masking_new' , array( $this , 'display_output_url_new_masking_page' ) ) ;
            add_action( $this->plugin_slug . '_admin_field_output_url_masking_edit' , array( $this , 'display_output_url_edit_masking_page' ) ) ;
        }

        /*
         * Check If Last referral
         */

        public function check_if_url_masking_enabled( $bool ) {
            return true ;
        }

        /**
         * Frontend Actions
         */
        public function frontend_action() {
            add_filter( 'fs_affiliates_frontend_dashboard_menu' , array( $this , 'url_masking_menu' ) , 15 , 2 ) ;
            add_filter( 'fs_affiliates_check_is_affiliate' , array( $this , 'get_url_masking_affiliate_id' ) , 10 , 1 ) ;
            add_action( 'fs_affiliates_dashboard_content_url_masking' , array( $this , 'url_masking' ) , 10 , 2 ) ;
            add_action( 'fs_affiliates_frontend_dashboard_affiliate_tools_submenus' , array( $this , 'unset_affs_link_from_submenu' ) , 10 , 2 ) ;
        }

        /*
         * URL Masking
         */

        public function url_masking( $user_id , $affilate_id ) {

            if ( isset( $_POST[ 'fs_affs_domain_name' ] ) && ! empty( $_POST[ 'fs_affs_domain_name' ] ) ) {
                try {
                    $error_message = '' ;
                    $domain_name   = isset( $_POST[ 'fs_affs_domain_name' ] ) ? $_POST[ 'fs_affs_domain_name' ] : '' ;

                    $domain_name = fs_affs_get_domain_name( $domain_name ) ;

                    if ( $domain_name == '' ) {
                        $error_message .= __( 'Please enter a valid URL' , FS_AFFILIATES_LOCALE ) . '<br>' ;
                    }

                    $restricted_domains = fs_affiliates_get_restricted_domain_names() ;

                    $AffiliateId = fs_get_affiliate_id_for_user( $user_id ) ;

                    $regisered_domains_count = fs_affiliates_get_reg_domains( $AffiliateId , 'count' ) ;

                    if ( $this->no_of_domains != '' && $regisered_domains_count >= $this->no_of_domains ) {
                        $error_message .= __( 'Your Alloted Domain Count Is Reached' , FS_AFFILIATES_LOCALE ) . '<br>' ;
                    }

                    if ( fs_affiliates_check_is_array( $restricted_domains ) ) {
                        if ( in_array( $domain_name , $restricted_domains ) ) {
                            $error_message .= __( 'Please enter an another domain' , FS_AFFILIATES_LOCALE ) . '<br>' ;
                        }
                    }

                    if ( fs_affiliates_is_domain_already_exists( $domain_name ) > 0 ) {
                        $error_message .= __( 'Domain name already requested.' , FS_AFFILIATES_LOCALE ) . '<br>' ;
                    }

                    if ( $error_message ) {
                        throw new Exception( $error_message ) ;
                    } else {
                        $domain_name = $_POST[ 'fs_affs_domain_name' ] ;

                        $domain_name = fs_affs_get_domain_name( $domain_name ) ;

                        fs_affiliates_insert_url_masking_domain( $AffiliateId , $domain_name , $user_id , 'fs_pending_approval' ) ;
                    }
                    ?><div>
                        <span class="fs_affiliates_msg_success_post"><i class="fa fa-check"></i><?php _e( 'Domain added sucessfully' , FS_AFFILIATES_LOCALE ) ; ?></span>
                    </div><?php
                    do_action( 'fs_affiliates_profile_updated' , $AffiliateId ) ;
                } catch ( Exception $e ) {
                    ?><div>
                        <span class="fs_affiliates_msg_fails_post"><i class="fa fa-exclamation-triangle"></i><?php echo $e->getMessage() ; ?></span>
                    </div><?php
                }
            }
            $AffiliateId            = fs_get_affiliate_id_for_user( $user_id ) ;
            ?>
            <div class="fs_affiliates_form">
                <h2><?php _e( 'Allowed Domains' , FS_AFFILIATES_LOCALE ) ?></h2>
                <form method="post" class="fs_affiliates_form affiliate-form">
                    <p class="affiliate-form-row affiliate-form-row--wide form-row form-row-wide">
                        <label for="fs_affs_domain_name"><?php esc_html_e( 'Domain Name' , FS_AFFILIATES_LOCALE ) ; ?>&nbsp;</label>
                        <input type="url" class="affiliate-Input affiliate-Input--text input-text" name="fs_affs_domain_name" id="fs_affs_domain_name"  />
                    </p>
                    <p class="affiliate-FormRow form-row">
                        <?php wp_nonce_field( 'affiliate-domain' , 'affiliate-domain-nonce' ) ; ?>
                        <button type="submit" class="fs_affiliates_form_save affiliate-Button button" name="aff_domain" value="aff_add_domain"><?php esc_html_e( 'Add Domain' , FS_AFFILIATES_LOCALE ) ; ?></button>
                    </p>
                </form>

                <?php
                $regisered_domains_data = fs_affiliates_get_reg_domains( $AffiliateId ) ;

                if ( ! fs_affiliates_check_is_array( $regisered_domains_data ) ) {
                    ?></div><?php
                return ;
            }
            ?>
            <h2 style="display: block;clear: both;"><?php echo __( 'All Domains' , FS_AFFILIATES_LOCALE ) ; ?></h2>
            <table class="fs_affiliates_domain_table fs_affiliates_frontend_table">
                <thead>
                    <tr>
                        <th class="fs_affiliates_sno fs_affiliates_domain_sno"><?php echo __( 'S.No' , FS_AFFILIATES_LOCALE ) ; ?></th>
                        <th><?php echo __( 'Domain Name' , FS_AFFILIATES_LOCALE ) ; ?></th>
                        <th><?php echo __( 'Domain Status' , FS_AFFILIATES_LOCALE ) ; ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 1 ;
                    foreach ( $regisered_domains_data as $each_domain_id ) {
                        $url_masking_object = new FS_URL_Masking_Data( $each_domain_id ) ;
                        $masking_domain     = $url_masking_object->url_masking_domain ;
                        $domain_status      = $url_masking_object->get_status() ;
                        $formatted_status   = fs_affiliates_get_status_display( $domain_status ) ;
                        ?>
                        <tr>
                            <td data-title="<?php esc_html_e( 'S.No' , FS_AFFILIATES_LOCALE ) ?>" class="fs_affiliates_sno fs_affiliates_domain_sno">
                                <?php echo $i ; ?>
                            </td>
                            <td data-title="<?php esc_html_e( 'Domain Name' , FS_AFFILIATES_LOCALE ) ?>" >
                                <?php echo __( $masking_domain , FS_AFFILIATES_LOCALE ) ; ?>
                            </td>
                            <td data-title="<?php esc_html_e( 'Domain Status' , FS_AFFILIATES_LOCALE ) ?>" >
                                <?php echo __( $formatted_status , FS_AFFILIATES_LOCALE ) ; ?>
                            </td>
                        </tr>
                        <?php
                        $i ++ ;
                    }
                    ?>
                </tbody>
            </table>
            </div>
            <?php
        }

        /*
         * Get Url Masking affiliate ID
         */

        public function get_url_masking_affiliate_id( $affiliateid ) {

            if ( ! $affiliateid ) {
                $url    = isset( $_SERVER[ 'HTTP_REFERER' ] ) ? $_SERVER[ 'HTTP_REFERER' ] : '' ;
                $domain = fs_affs_get_domain_name( $url ) ;

                $args = array(
                    'post_type'   => 'fs-url-masking' ,
                    'numberposts' => -1 ,
                    'post_status' => array( 'fs_active' ) ,
                    'fields'      => 'ids' ,
                    'meta_query'  => array( array( 'key'     => 'url_masking_domain' ,
                            'value'   => $domain ,
                            'compare' => '==' )
                    )
                        ) ;

                $post_data = get_posts( $args ) ;
                $domain_id = isset( $post_data[ 0 ] ) ? $post_data[ 0 ] : '' ;

                if ( empty( $domain_id ) ) {
                    return $affiliateid ;
                }

                $url_masking_object = new FS_URL_Masking_Data( $domain_id ) ;

                $url_masking_object->update_meta( 'domain_visit_count' , ( int ) $url_masking_object->domain_visit_count + 1 ) ;

                $affiliateid = isset( $url_masking_object->affs_id ) ? $url_masking_object->affs_id : $affiliateid ;
            }

            return $affiliateid ;
        }

        /*
         * Custom Payment Preference Option
         */

        public function url_masking_menu( $menus , $user_id ) {

            $profile = $menus[ 'profile' ] ;

            unset( $menus[ 'profile' ] ) ;

            $menus[ 'url_masking' ] = array(
                'label' => $this->menu_label ,
                'code'  => 'fa-eye-slash' ) ;

            $menus[ 'profile' ] = $profile ;

            return $menus ;
        }

        public function unset_affs_link_from_submenu( $menus , $user_id ) {

            if ( $this->hide_on_dashboard == 'yes' ) {
                unset( $menus[ 'affiliate_link' ] ) ;
            }

            return $menus ;
        }

        /**
         * Output the edit frontend form settings
         */
        public function display_output_url_edit_masking_page() {
            if ( ! isset( $_GET[ 'um_key' ] ) )
                return ;

            if ( ! isset( $_REQUEST[ 'subsection' ] ) && $_REQUEST[ 'subsection' ] != 'add_new_domain' ) {
                return ;
            }

            $field_key = $_GET[ 'um_key' ] ;

            $sub_section = $_REQUEST[ 'subsection' ] ;

            $url_masking_object = new FS_URL_Masking_Data( $field_key ) ;

            $affs_id = isset( $url_masking_object->affs_id ) ? $url_masking_object->affs_id : '' ;
            if ( empty( $affs_id ) ) {
                return ;
            }

            $affs_data         = new FS_Affiliates_Data( $url_masking_object->affs_id ) ;
            $affs_name         = $affs_data->first_name . ' ' . $affs_data->last_name ;
            $affs_domain       = $url_masking_object->url_masking_domain ;
            $domain_req_status = $url_masking_object->get_status() ;
            $affs_date         = fs_affiliates_local_datetime( $url_masking_object->date ) ;

            include_once( FS_AFFILIATES_PLUGIN_PATH . '/inc/admin/menu/views/email-url-masking-fields-edit.php' ) ;
        }

        /**
         * Output the edit frontend form settings
         */
        public function display_output_url_new_masking_page() {

            if ( ! isset( $_REQUEST[ 'subsection' ] ) && $_REQUEST[ 'subsection' ] != 'add_new_domain' ) {
                return ;
            }

            $sub_section = $_REQUEST[ 'subsection' ] ;
            include_once( FS_AFFILIATES_PLUGIN_PATH . '/inc/admin/menu/views/email-url-masking-fields-edit.php' ) ;
        }

        /**
         * Output the frontend form settings
         */
        public function extra_fields() {


            global $current_section , $current_sub_section ;

            if ( $current_section == 'url_masking' && ($current_sub_section == 'edit' || $current_sub_section == 'add_new_domain' ) ) {
                return ;
            }

            if ( ! class_exists( 'FS_URL_Masking_Post_Table' ) ) {
                require_once( FS_AFFILIATES_PLUGIN_PATH . '/inc/admin/menu/wp-list-table/class-fs-affiliates-url-masking-table.php' ) ;
            }

            echo '<div class="' . $this->plugin_slug . '_table_wrap">' ;
            echo '<h2 class="wp-heading-inline">' . __( 'Domains' , FS_AFFILIATES_LOCALE ) . '</h2>' ;
            $new_section_url = add_query_arg( array( 'page' => 'fs_affiliates' , 'tab' => 'modules' , 'section' => 'url_masking' , 'subsection' => 'add_new_domain' ) , admin_url( 'admin.php' ) ) ;
            echo '<a class="page-title-action ' . $this->plugin_slug . '_add_btn" href="' . $new_section_url . '">' . __( 'Add New Domain' , FS_AFFILIATES_LOCALE ) . '</a>' ;
            $post_table      = new FS_URL_Masking_Post_Table() ;
            $post_table->prepare_items() ;
            $post_table->views() ;
            $post_table->display() ;
            echo '</div>' ;
        }

        /**
         * Save subsection settings.
         */
        public function before_save() {
            global $current_sub_section ;

            if ( $current_sub_section == '' || ! isset( $_POST[ 'url_masking_fields' ] ) || empty( $_POST[ 'url_masking_fields' ] ) )
                return ;

            $form_field_data = $_POST[ 'form_field' ] ;
            $domain_status   = isset( $form_field_data[ 'field_status' ] ) ? $form_field_data[ 'field_status' ] : '' ;

            if ( $_POST[ 'url_masking_fields' ] == 'add_new' ) {

                $domain_name = $form_field_data [ 'domain_name' ] ;
                $AffiliateId = isset( $_POST [ 'fs_affiliates_add_new_domain' ][ 0 ] ) ? $_POST [ 'fs_affiliates_add_new_domain' ][ 0 ] : '' ;

                $post_id = fs_affiliates_insert_url_masking_domain( $AffiliateId , $domain_name , get_current_user_id() , 'fs_active' ) ;

                $url_masking_object = new FS_URL_Masking_Data( $post_id ) ;

                $notice_message = __( 'Domain details added successfully.' , FS_AFFILIATES_LOCALE ) ;
            } else {

                $form_field_data = $_POST[ 'form_field' ] ;

                if ( empty( $form_field_data[ 'field_key' ] ) || $form_field_data[ 'field_key' ] != $_REQUEST[ 'um_key' ] ) {
                    throw new Exception( __( 'Cannot modify domain id' , FS_AFFILIATES_LOCALE ) ) ;
                }

                $post_id     = $form_field_data[ 'field_key' ] ;
                $domain_name = isset( $form_field_data[ 'domain_name' ] ) ? $form_field_data[ 'domain_name' ] : '' ;

                $meta_data          = array(
                    'url_masking_domain' => $domain_name ,
                    'status'             => $domain_status ,
                    'post_title'         => $domain_name ,
                        ) ;
                $post_args          = array(
                    'post_status' => $domain_status ,
                        ) ;
                $url_masking_object = new FS_URL_Masking_Data( $post_id ) ;

                $url_masking_object->update( $meta_data , $post_args ) ;

                $notice_message = __( 'Domain details updated successfully.' , FS_AFFILIATES_LOCALE ) ;
            }

            $affs_id = isset( $url_masking_object->affs_id ) ? $url_masking_object->affs_id : '' ;

            if ( $affs_id != '' ) {
                $affs_data  = new FS_Affiliates_Data( $affs_id ) ;
                $affs_email = $affs_data->email ;

                if ( $domain_status == 'fs_active' ) {
                    process_url_masking_user_notify_mail( $affs_email , 'success' ) ;
                }

                if ( $domain_status == 'fs_suspended' || $domain_status == 'fs_rejected' ) {
                    process_url_masking_user_notify_mail( $affs_email , 'fails' ) ;
                }

                $admin_email = get_option( 'admin_email' ) ;
                process_url_masking_admin_notify_mail( $admin_email ) ;
            }

            FS_Affiliates_Settings::add_message( $notice_message ) ;
        }

        public function output_buttons() {
            global $current_section , $current_sub_section ;

            if ( $current_section == 'url_masking' && ( $current_sub_section == 'edit' || $current_sub_section == 'add_new_domain' ) ) {
                return ;
            }

            FS_Affiliates_Settings::output_buttons() ;
        }

    }

}
