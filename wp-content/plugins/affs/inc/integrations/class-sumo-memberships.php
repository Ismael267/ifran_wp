<?php
/**
 * SUMO Reward Points
 */
if ( ! defined ( 'ABSPATH' ) ) {
    exit ; // Exit if accessed directly.
}

if ( ! class_exists ( 'FS_Affiliates_SUMO_Memberships' ) ) {

    /**
     * Class FS_Affiliates_SUMO_Memberships
     */
    class FS_Affiliates_SUMO_Memberships extends FS_Affiliates_Integrations {
        /*
         * Data
         */

        protected $data = array (
            'enabled'                 => 'no' ,
            'eligibility'             => 'affs_meets' ,
            'reset_commision'         => 'no' ,
            'reset_commision_rate_on' => '' ,
            'plan_priority'           => '' ,
                ) ;

        /**
         * Class Constructor
         */
        public function __construct() {
            $this->id    = 'sumo_memberships' ;
            $this->title = __ ( 'SUMO Memberships' , FS_AFFILIATES_LOCALE ) ;

            parent::__construct () ;
        }

        /*
         * is enabled
         */

        public function is_enabled() {
            return $this->is_plugin_enabled () && 'yes' === $this->enabled ;
        }

        /*
         * Plugin enabled
         */

        public function is_plugin_enabled() {
            return fs_affiliates_check_if_sumo_memberships_is_active () ;
        }

        /*
         * Get settings link
         */

        public function settings_link() {
            return add_query_arg ( array ( 'page' => 'fs_affiliates' , 'tab' => 'integration' , 'section' => $this->id ) , admin_url ( 'admin.php' ) ) ;
        }

        /*
         * Get settings options array
         */

        public function settings_options_array() {

            $eligibility = array (
                'affs_meets'      => 'Affiliates Should Meet the Criteria' ,
                'refs_meets'      => 'Referrals Should Meet the Criteria' ,
                'affs_refs_meets' => 'Affiliate/Referrals Should Meet the Criteria' ,
                    ) ;

            $plan_priority = array ( 'first' => 'First Match Rule' , 'last' => 'Last Match Rule' ) ;

            $date_listing = array (
                1 => 1 , 2  => 2 , 3  => 3 , 4  => 4 , 5  => 5 , 6  => 6 ,
                7  => 7 , 8  => 8 , 9  => 9 , 10  => 10 , 11  => 11 , 12  => 12 ,
                13  => 13 , 14  => 14 , 15  => 15  , 16  => 16 , 17  => 17 , 18  => 18 ,
                19  => 19 , 20  => 20 , 21  => 21 , 22  => 22 , 23  => 23 , 24  => 24 ,
                25  => 25 , 26  => 26 , 27  => 27 , 28  => 28
                    ) ;

            return array (
                array (
                    'type'  => 'title' ,
                    'title' => __ ( 'SUMO Membership Compatibility' , FS_AFFILIATES_LOCALE ) ,
                    'id'    => 'sumo_memberships_options' ,
                ) ,
                array (
                    'title'   => __ ( 'Eligibility' , FS_AFFILIATES_LOCALE ) ,
                    'id'      => $this->plugin_slug . '_' . $this->id . '_eligibility' ,
                    'type'    => 'select' ,
                    'default' => 'affs_meets' ,
                    'options' => $eligibility ,
                ) ,
                array (
                    'title'   => 'Reset Commission Rate' ,
                    'id'      => $this->plugin_slug . '_' . $this->id . '_reset_commision' ,
                    'type'    => 'checkbox' ,
                    'default' => 'no' ,
                    'desc'    => __ ( 'By selecting this checkbox, affiliate/referred person should spend the amount again set in the corresponding membership plan from a specified day of every month' , FS_AFFILIATES_LOCALE ) ,
                ) ,
                array (
                    'title'   => __ ( 'Reset Commission Rate on' , FS_AFFILIATES_LOCALE ) ,
                    'desc'    => __ ( 'Of Every Month' , FS_AFFILIATES_LOCALE ) ,
                    'id'      => $this->plugin_slug . '_' . $this->id . '_reset_commision_rate' ,
                    'type'    => 'select' ,
                    'options' => $date_listing ,
                    'default' => array ( 01 ) ,
                ) ,
                array (
                    'title'   => __ ( 'Plan Selection Priority' , FS_AFFILIATES_LOCALE ) ,
                    'desc'    => __ ( 'If the membership plan and amount matching in more than one rule, then the commission will be taken based on this option' , FS_AFFILIATES_LOCALE ) ,
                    'id'      => $this->plugin_slug . '_' . $this->id . '_plan_priority' ,
                    'type'    => 'select' ,
                    'default' => array ( 'first' ) ,
                    'options' => $plan_priority ,
                ) ,
                array (
                    'id'   => 'fs_affiliates_opt_in_form_fields' ,
                    'type' => 'output_plans_list'
                ) ,
                array (
                    'type' => 'sectionend' ,
                    'id'   => 'sumo_memberships_options' ,
                ) ,
                    ) ;
        }

        /*
         * Admin Actions
         */

        public function admin_action() {
            add_action ( $this->plugin_slug . '_admin_field_output_plans_list' , array ( $this , 'display_plans_list' ) ) ;
        }

        /*
         * Frontend Actions
         */

        public function frontend_action() {
            
        }

        /*
         * Both Front End and Back End Action
         */

        public function actions() {
            add_filter ( 'is_membership_commision_value_available' , array ( $this , 'is_membership_commision_value_available' ) , 10 , 2 ) ;
        }

        public function is_membership_commision_value_available( $commision_value , $affiliate_id ) {
            // $affiliate_id , $commision_value
            $affs_data = new FS_Affiliates_Data ( $affiliate_id ) ;
            $user_id   = isset ( $affs_data->user_id ) ? $affs_data->user_id : '' ;

            if ( empty ( $user_id ) ) {
                return $commision_value ;
            }

            $member_post_id = sumo_get_member_post_id ( $user_id ) ;
            
            if( empty ( $member_post_id ) ) {
                return $commision_value ;
            }

            if ( sumo_is_member_purchased_any_plan ( $member_post_id ) ) {
                $active_plans = (  function_exists ( 'sumo_get_member_purchased_plans_list' ) ) ? sumo_get_member_purchased_plans_list ( $member_post_id ) : '' ;
            }

            if ( ! fs_affiliates_check_is_array ( $active_plans ) ) {
                return $commision_value ;
            }

            $saved_plans = get_option ( 'sumo_memberships_plans_amount_commission' ) ;

            foreach ( $active_plans as $each_plan_id ) {
                $amount_spent                        = $saved_plans[ $each_plan_id ][ 'amount_spent' ] ;
                $amount_spent_array[ $each_plan_id ] = $amount_spent ;
            }

            $amount_spent = ( $this->plan_priority == 'last' ) ? end ( $amount_spent_array ) : reset ( $amount_spent_array ) ;

            if ( empty ( $amount_spent ) ) {
                return $commision_value ;
            }

            $plan_id_array = array_keys ( $amount_spent_array , $amount_spent ) ;
            
            $eligible_plan_id = isset ( $plan_id_array[ 0 ] ) ? $plan_id_array[ 0 ] : '' ;

            $commission_rate = $saved_plans[ $eligible_plan_id ][ 'commision_rate' ] ;

            if ( empty ( $commission_rate ) ) {
                return $commision_value ;
            }
            
            $commision_eligibility = $this->eligibility ;
            
            if ( $commision_eligibility == 'affs_meets' ) {
                $affs_order_total = fs_affiliates_user_spent_order_total ( $user_id ) ;

                if ( $amount_spent <= $affs_order_total ) {
                    return $commission_rate ;
                }
            } else if ( $commision_eligibility == 'refs_meets' ) {
                $referal_order_total = fs_affiliates_refferals_spent_order_total ( $affiliate_id ) ;
                
                if ( $amount_spent <= $referal_order_total ) {
                    return $commission_rate ;
                }
                
            } else if ( $commision_eligibility == 'affs_refs_meets' ) {
                $affs_order_total    = fs_affiliates_user_spent_order_total ( $user_id ) ;
                $referal_order_total = fs_affiliates_refferals_spent_order_total ( $affiliate_id ) ;

                if ( $amount_spent <= $affs_order_total || $amount_spent <= $referal_order_total ) {
                    return $commission_rate ;
                }
            }

            return $commision_value ;
        }

        public function display_plans_list() {
            $memberships_plans = sumo_get_membership_plans () ;

            if ( isset ( $_POST[ 'plans' ] ) ) {
                update_option ( 'sumo_memberships_plans_amount_commission' , $_POST[ 'plans' ] ) ;
            }

            $saved_plans = get_option ( 'sumo_memberships_plans_amount_commission' ) ;
            ?>
            <table class="widefat fs_affiliates_mlm_rules_table">
                <thead>
                    <tr>
                        <th><?php _e ( 'Membership Plan' , FS_AFFILIATES_LOCALE ) ?></th>
                        <th><?php _e ( 'Amount to be Spent' , FS_AFFILIATES_LOCALE ) ?></th>
                        <th><?php _e ( 'Commission Rate' , FS_AFFILIATES_LOCALE ) ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ( $memberships_plans as $eachplan_id ) {

                        $amount_spent   = isset ( $saved_plans[ $eachplan_id ][ 'amount_spent' ] ) ? $saved_plans[ $eachplan_id ][ 'amount_spent' ] : '' ;
                        $commision_rate = isset ( $saved_plans[ $eachplan_id ][ 'commision_rate' ] ) ? $saved_plans[ $eachplan_id ][ 'commision_rate' ] : '' ;
                        ?>
                        <tr>
                            <td><?php echo get_post_meta ( $eachplan_id , 'sumomemberships_plan_name' , true ) ; ?></td>
                            <td><input name="plans[<?php echo $eachplan_id ; ?>][amount_spent]" type="text" value="<?php echo $amount_spent ; ?>" class="fs_affiliates_input_price"></td>
                            <td><input name="plans[<?php echo $eachplan_id ; ?>][commision_rate]" type="text" value="<?php echo $commision_rate ; ?>" class="fs_affiliates_input_price"></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
            <?php
        }

    }

}
    
