<?php
/**
 * Multi Level Marketing
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}

if ( ! class_exists( 'FS_Affiliates_Multi_Level_Marketing' ) ) {

    /**
     * Class FS_Affiliates_Multi_Level_Marketing
     */
    class FS_Affiliates_Multi_Level_Marketing extends FS_Affiliates_Modules {
        /*
         * Data
         */

        protected $data = array(
            'enabled'             => 'no',
            'referrals_count'     => '',
            'rules'               => array(),
            'display_data_enable' => 'no',
            'data_display'        => array(),
            'commission_type'     => 'percentage_commission',
            'commission_value'    => '',
                ) ;

        /**
         * Class Constructor
         */
        public function __construct() {
            $this->id    = 'multi_level_marketing' ;
            $this->title = __( 'Multi Level Marketing', FS_AFFILIATES_LOCALE ) ;

            parent::__construct() ;
        }

        /*
         * Get settings link
         */

        public function settings_link() {
            return add_query_arg( array( 'page' => 'fs_affiliates', 'tab' => 'modules', 'section' => $this->id ), admin_url( 'admin.php' ) ) ;
        }

        /*
         * Get settings options array
         */

        public function settings_options_array() {
            return array(
                array(
                    'type'  => 'title',
                    'title' => __( 'Multi Level Marketing', FS_AFFILIATES_LOCALE ),
                    'desc'  => sprintf( __( '%s - Use this shortcode to display the MLM Tree for Affiliates', FS_AFFILIATES_LOCALE ), '[fs_affiliates_mlm_tree]' ),
                    'id'    => 'multi_level_marketing_options',
                ),
                array(
                    'title'   => __( 'Number of Direct Referrals', FS_AFFILIATES_LOCALE ),
                    'desc'    => __( 'This option controls the number of referrals for which an affiliate can earn a commission. Once the limit is reached, the affiliate can only earn commissions through their child affiliates.', FS_AFFILIATES_LOCALE ),
                    'id'      => $this->plugin_slug . '_' . $this->id . '_referrals_count',
                    'type'    => 'number',
                    'default' => '',
                ),
                array(
                    'type' => 'sectionend',
                    'id'   => 'multi_level_marketing_options',
                ),
                array(
                    'type'  => 'title',
                    'title' => __( 'Affiliate Level Depth', FS_AFFILIATES_LOCALE ),
                    'id'    => 'multi_level_marketing_affiliate_level',
                ),
                array(
                    'id'      => $this->plugin_slug . '_' . $this->id . '_rules',
                    'type'    => 'mlm_rules',
                    'default' => $this->default_rules(),
                ),
                array(
                    'type' => 'sectionend',
                    'id'   => 'multi_level_marketing_affiliate_level',
                ),
                array(
                    'type'  => 'title',
                    'title' => __( 'MLM Graph Settings', FS_AFFILIATES_LOCALE ),
                    'id'    => 'multi_level_marketing_graph_settings',
                ),
                array(
                    'title'   => 'Display Affiliate Details in the MLM Graph',
                    'id'      => $this->plugin_slug . '_' . $this->id . '_display_data_enable',
                    'type'    => 'checkbox',
                    'default' => 'no',
                ),
                array(
                    'title'   => __( 'Details to Display', FS_AFFILIATES_LOCALE ),
                    'id'      => $this->plugin_slug . '_' . $this->id . '_data_display',
                    'type'    => 'multiselect',
                    'class'   => 'fs_affiliates_select2 fs_affiliates_node_link_display',
                    'default' => array( 'first_name', 'last_name', 'total_commission_earned' ),
                    'options' => array(
                        'first_name'       => __( 'First Name', FS_AFFILIATES_LOCALE ),
                        'last_name'        => __( 'Last Name', FS_AFFILIATES_LOCALE ),
                        'user_name'        => __( 'Username', FS_AFFILIATES_LOCALE ),
                        'email'            => __( 'Email', FS_AFFILIATES_LOCALE ),
                        'country'          => __( 'Country', FS_AFFILIATES_LOCALE ),
                        'website'          => __( 'Website', FS_AFFILIATES_LOCALE ),
                        'phone_number'     => __( 'Phone Number', FS_AFFILIATES_LOCALE ),
                        'commission_value' => __( 'Total Commission Earned', FS_AFFILIATES_LOCALE ),
                    ),
                ), array(
                    'title'   => __( 'Total Commission Earned Label', FS_AFFILIATES_LOCALE ),
                    'id'      => $this->plugin_slug . '_' . $this->id . '_commission_value',
                    'class'   => 'fs_affiliates_node_link_display',
                    'type'    => 'text',
                    'default' => '',
                ),
                array(
                    'type' => 'sectionend',
                    'id'   => 'multi_level_marketing_graph_settings',
                ),
                    ) ;
        }

        /*
         * Admin action
         */

        public function admin_action() {
            add_action( $this->plugin_slug . '_admin_field_mlm_rules', array( $this, 'display_rules' ) ) ;
        }

        /*
         * Default Rules
         */

        public function default_rules() {
            return array( '1' => array( 'commission_type' => 'percentage_commission', 'commission_value' => '10' ) ) ;
        }

        public function check_direct_referral_threshold( $bool, $affiliate_id ) {

            if ( empty( $this->referrals_count ) )
                return $bool ;

            $count = fs_affiliates_get_referrals_count( $affiliate_id ) ;

            if ( $this->referrals_count < $count )
                return false ;

            return $bool ;
        }

        /*
         * Get Rules
         */

        public function get_rules() {
            if ( fs_affiliates_check_is_array( $this->rules ) )
                return $this->rules ;

            return $this->default_rules() ;
        }

        /*
         * Display Rules
         */

        public function display_rules() {
            ?>
            <input type="button" class="fs_affiliates_add_mlm_rule" value="<?php _e( 'Add Level', FS_AFFILIATES_LOCALE ) ; ?>"/>
            <table class="widefat fs_affiliates_mlm_rules_table">
                <thead>
                    <tr>
                        <th><?php _e( 'Depth Level', FS_AFFILIATES_LOCALE ) ?></th>
                        <th><?php _e( 'Commission Type', FS_AFFILIATES_LOCALE ) ?></th>
                        <th><?php _e( 'Commission ', FS_AFFILIATES_LOCALE ) ?></th>
                        <th><?php _e( 'Remove Level', FS_AFFILIATES_LOCALE ) ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $rules = $this->get_rules() ;
                    if ( fs_affiliates_check_is_array( $rules ) ) {
                        foreach ( $rules as $key => $rule ) {
                            $name = $this->plugin_slug . '_' . $this->id . '_rules[' . $key . ']' ;
                            ?>
                            <tr>
                                <td>
                                    <input type="hidden" id="fs_affiliates_mlm_rule_id" value="<?php echo $key ; ?>"/>
                                    <span><?php echo sprintf( __( 'Level %s', FS_AFFILIATES_LOCALE ), $key ) ; ?></span>
                                </td>
                                <td>
                                    <select name="<?php echo $name ; ?>[commission_type]" class='fs_affiliates_commission_type'>
                                        <option value="percentage_commission" <?php isset( $rule[ 'commission_type' ] ) ? selected( $rule[ 'commission_type' ], 'percentage_commission' ) : '' ; ?>><?php echo esc_html__( 'Percentage Commission', FS_AFFILIATES_LOCALE ) ; ?></option>
                                        <option value="fixed_commission" <?php isset( $rule[ 'commission_type' ] ) ? selected( $rule[ 'commission_type' ], 'fixed_commission' ) : '' ; ?>><?php echo esc_html__( 'Fixed Commission', FS_AFFILIATES_LOCALE ) ; ?></option>
                                    </select>   
                                </td>
                                <td>
                                    <input type="text" name="<?php echo $name ; ?>[commission_value]" class ='fs_affiliates_input_price' value="<?php echo $rule[ 'commission_value' ] ?>" />
                                </td>

                                <td>
                                    <p class="fs_affiliates_remove_mlm_rule"> <img src="<?php echo FS_AFFILIATES_PLUGIN_URL . '/assets/images/x-mark-3-24.png' ; ?>"></img></p>
                                </td>
                            </tr>
                            <?php
                        }
                    }
                    ?>
                </tbody>
            </table>
            <?php
        }

        /*
         * is affs data enable
         */

        public function is_affs_data_diplay( $bool ) {

            if ( $this->display_data_enable == 'yes' ) {
                return true ;
            }

            return $bool ;
        }

        /*
         * Save
         */

        public function save() {
            if ( ! isset( $_POST[ $this->plugin_slug . '_' . $this->id . '_rules' ] ) )
                return ;

            $rules = $_POST[ $this->plugin_slug . '_' . $this->id . '_rules' ] ;

            if ( ! fs_affiliates_check_is_array( $rules ) )
                return ;

            $saving_rules = array() ;
            $key          = 1 ;
            foreach ( $rules as $rule ) {
                $saving_rules[ $key ] = $rule ;
                $key ++ ;
            }

            $this->rules = $saving_rules ;
            $this->update_option( 'rules', $saving_rules ) ;
        }

        public function get_affs_content() {
            if ( isset( $_GET[ 'get_affs_content' ] ) ) {

                $fields = fs_affiliates_get_form_fields() ;

                $affs_id       = $_GET[ 'affs_id' ] ;
                $affilate_data = new FS_Affiliates_Data( $affs_id ) ;

                if ( ! fs_affiliates_check_is_array( $this->data_display ) ) {
                    return ;
                }
                $datas_to_display = $this->data_display ;
                $formated_value   = ' - ' ;
                ?>
                <style>
                    .fs_affiliates_mlm_table{
                        border:1px solid #000;
                        border-collapse:collapse;
                    }
                    .fs_affiliates_mlm_table tr th{
                        background:#093f77;
                        color:#fff;
                        padding:20px 10px 20px 40px;
                        text-align:left;
                        border-bottom:1px solid #fff;
                    }
                    .fs_affiliates_mlm_table tr td{
                        padding:20px 10px 20px 40px;
                        text-align:left;
                        border-bottom:1px solid #000;
                    }
                </style>
                <table width="600px" class="fs_affiliates_mlm_table" >
                    <?php
                    foreach ( $datas_to_display as $each_data ) {

                        $formated_value = '' ;
                        //Head Labels
                        if ( isset( $fields[ $each_data ][ 'field_key' ] ) && $fields[ $each_data ][ 'field_key' ] == $each_data ) {
                            $field_label = $fields[ $each_data ][ 'field_name' ] ;
                        } else {
                            if ( $each_data == 'phone_number' ) {
                                $field_label = $fields[ 'phonenumber' ][ 'field_name' ] ;
                            }
                            if ( $each_data == 'commission_value' ) {
                                $field_label = $this->$each_data ;
                            }
                        }

                        //TD Values
                        if ( $each_data == 'commission_value' ) {
                            $formated_value = $affilate_data->get_paid_commission() ;
                        } else {
                            if ( isset( $affilate_data->$each_data ) && ! empty( $affilate_data->$each_data ) ) {
                                $formated_value = $affilate_data->$each_data ;
                            }
                        }
                        ?>
                        <tr align="center">
                            <th><?php echo $field_label ; ?></th>
                            <td><?php echo $formated_value ?></td>
                        </tr>
                    <?php } ?>
                </table>
                <?php
                exit() ;
            }
        }

        /**
         * Actions
         */
        public function actions() {
            add_filter( 'fs_affiliates_check_direct_referral_threshold', array( $this, 'check_direct_referral_threshold' ), 10, 2 ) ;
            add_action( 'fs_affiliates_create_referrals', array( $this, 'insert_commission_based_on_mlm' ), 10, 3 ) ;
            add_action( 'init', array( $this, 'get_affs_content' ) ) ;
            add_filter( 'fs_affiliates_is_affs_data_diplay', array( $this, 'is_affs_data_diplay' ) ) ;
        }

        public function insert_commission_based_on_mlm( $affiliate_id, $referral_args, $post_args ) {

            if ( isset( $referral_args[ 'is_mlm' ] ) && $referral_args[ 'is_mlm' ] == 'no' ) {
                return ;
            }

            $i                       = 1 ;
            $referral_ids            = array() ;
            $prepare_rules           = $this->get_level( $affiliate_id ) ;
            $amount                  = isset( $referral_args[ 'original_price' ] ) ? $referral_args[ 'original_price' ] : $referral_args[ 'amount' ] ;
            $referral_description    = $referral_args[ 'description' ] ;
            $description             = get_option( 'fs_affiliates_referral_desc_mlm_label', 'MLM Level {affiliate_level} Commission for {referral_actions}' ) ;
            $default_desc_shortcodes = array( '{affiliate_level}', '{referral_actions}' ) ;

            foreach ( $prepare_rules as $parent_affiliate_id => $rule ) {
                $referral_args[ 'amount' ]            = (isset( $rule[ 'commission_type' ] ) && 'fixed_commission' === $rule[ 'commission_type' ]) ? $rule[ 'commission_value' ] : ((( float ) $amount * ( float ) $rule[ 'commission_value' ]) / 100) ;
                $referral_args[ 'description' ]       = str_replace( $default_desc_shortcodes, array( $i, $referral_description ), $description ) ;
                $ReferralObj                          = new FS_Affiliates_Referrals() ;
                $post_args[ 'post_author' ]           = $parent_affiliate_id ;
                $referral_ids[ $parent_affiliate_id ] = $ReferralObj->create( $referral_args, $post_args ) ;
                do_action( 'fs_affiliates_new_mlm_referral', $referral_ids[ $parent_affiliate_id ], $parent_affiliate_id ) ;
                $i ++ ;
            }

            return $referral_ids ;
        }

        public function get_level( $affiliate_id ) {
            $level_values = array() ;

            if ( ! fs_affiliates_check_is_array( $this->rules ) )
                return $level_values ;

            foreach ( $this->rules as $key => $rule ) {
                $affiliates_object = new FS_Affiliates_Data( $affiliate_id ) ;
                $affiliate_id      = $affiliates_object->parent ;

                if ( ! $affiliate_id )
                    break ;

                $level_values[ $affiliate_id ] = $rule ;
            }

            return $level_values ;
        }

    }

}
