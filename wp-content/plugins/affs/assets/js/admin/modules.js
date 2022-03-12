/* global fs_affiliates_modules_params */

jQuery( function ( $ ) {

    var FS_Affiliates_Modules = {
        init : function ( ) {

            this.trigger_on_page_load( ) ;
            //MLM Module
            $( document ).on( 'click' , '.fs_affiliates_add_mlm_rule' , this.add_mlm_rule ) ;
            $( document ).on( 'click' , '.fs_affiliates_remove_mlm_rule' , this.remove_mlm_rule ) ;
            $( document ).on( 'change' , '#fs_affiliates_multi_level_marketing_display_data_enable' , this.multi_level_marketing_display_data_enable ) ;

            //Additional Tab Module
            $( document ).on( 'click' , '.fs_affiliates_add_dashboard_tab_rule' , this.add_dashboard_tab_rule ) ;
            $( document ).on( 'click' , '.fs_affiliates_remove_dashboard_tab_rule' , this.remove_dashboard_tab_rule ) ;

            //pushover notification Module
            $( document ).on( 'click' , '.fs_affiliates_test_pushover_notification' , this.test_pushover_notification ) ;

            //Leader board module
            $( document ).on( 'change' , '#fs_affiliates_leaderboard_display_method' , this.leaderboard_display_method ) ;

            //Additional Dashoard tab module
            $( document ).on( 'click' , 'div.fs_affiliates_selected_icon' , this.show_popup_icons ) ;
            $( document ).on( 'click' , 'div.fs_affiliates_tabs_toggle' , this.toggle_tabs ) ;
            $( document ).on( 'click' , 'li.fs_affiliates_popup_icon' , this.select_popup_icon ) ;

            //Payout Module
            $( document ).on( 'change' , '#fs_affiliates_paypal_payouts_mode' , this.paypal_payouts_mode ) ;

            //SMS module
            $( document ).on( 'change' , '.fs_affiliates_sms_module_api_method' , this.sms_module_api_method ) ;

            //Export module
            $( document ).on( 'click' , '.fs_affiliates_export_data' , this.validate_export_data ) ;

            //Signup Module
            $( document ).on( 'change' , '#fs_affiliates_signup_commission_woocommerce_signup' , this.woocommerce_signup_toggle ) ;
            $( document ).on( 'change' , '#fs_affiliates_signup_commission_affiliate_signup' , this.affiliate_signup_toggle ) ;
            $( document ).on( 'change' , '.fs_affiliates_allowed_affiliates_method' , this.allowed_affiliates_method ) ;
            $( document ).on( 'change' , '#fs_affiliates_wc_referral_restriction_stop_commission' , this.stop_commission ) ;
            $( document ).on( 'change' , '#fs_affiliates_wc_product_restriction_product_selection' , this.product_restrictions ) ;
            $( document ).on( 'change' , '.fs_affiliates_lifetime_commission_rate' , this.lifetime_affiliate_commission_rate ) ;
            $( document ).on( 'change' , '#fs_affiliates_checkout_affiliate_affs_selection' , this.checkout_affiliate_affs_selection ) ;

            //Social Share Module
            $( document ).on( 'change' , '#fs_affiliates_socialshare_fbshare' , this.fb_app_id ) ;

            //Fraud Protection Module
            $( document ).on( 'change' , '#fs_affiliates_fraud_protection_block_login' , this.block_affiliate_login ) ;
            $( document ).on( 'change' , '#fs_affiliates_fraud_protection_commission_for_same_ip' , this.commission_for_same_ip ) ;
            $( document ).on( 'change' , '#fs_affiliates_fraud_protection_landing_commission_for_same_ip' , this.restrict_landing_commission_referral ) ;

            //Additional dashboard tabs Module
            $( document ).on( 'change' , '.menu_show_or_hide' , this.show_hide_submenu_selector ) ;

            //payoutstatement module
            $( document ).on( 'change' , '#fs_affiliates_payout_statements_payout_name_disp_type' , this.show_hide_payout_name_display_settings ) ;

            //WooCommerce Account Management
            $( document ).on( 'change' , '#fs_affiliates_wc_account_management_allow_users' , this.toggle_non_affiliate_hide_option ) ;

            //Landing Commission
            $( document ).on( 'change' , '.fs_affs_lc_usage_type' , this.toggle_landing_commission_usage_type_option ) ;

            // Referral Code Module
            $( document ).on( 'change' , '#fs_affiliates_referral_code_checkout_page_visible' , this.toggle_checkout_page_referral_code ) ;
            $( document ).on( 'change' , '#fs_affiliates_referral_code_myaccount_page_visible' , this.toggle_myaccount_page_referral_code ) ;
            $( document ).on( 'change' , '#fs_affiliates_referral_code_registration_page_visible' , this.toggle_affiliate_registration_page_referral_code ) ;

            //Woocommerce Cupon Linking module
            $( document ).on( 'change' , '#fs_affiliates_coupon_commission_level' , this.toggle_linking_couon_commission_level ) ;

            // Woocommerce Product Restiction
            if ( fs_affiliates_modules_params.is_woo_product_restriction_module ) {
                $( document ).on( 'click' , '.fs_affiliates_save_btn' , this.validate_product_restriction_inputs ) ;
            }

        } , trigger_on_page_load : function ( ) {

            //SMS module
            FS_Affiliates_Modules.get_sms_module_api_method( '.fs_affiliates_sms_module_api_method' ) ;
            FS_Affiliates_Modules.get_product_restrictions( '#fs_affiliates_wc_product_restriction_product_selection' ) ;
            FS_Affiliates_Modules.get_paypal_payouts_mode( '#fs_affiliates_paypal_payouts_mode' ) ;
            FS_Affiliates_Modules.get_woocommerce_signup( '#fs_affiliates_signup_commission_woocommerce_signup' ) ;
            FS_Affiliates_Modules.get_affiliate_signup( '#fs_affiliates_signup_commission_affiliate_signup' ) ;
            FS_Affiliates_Modules.get_stop_commission_options( '#fs_affiliates_wc_referral_restriction_stop_commission' ) ;
            FS_Affiliates_Modules.get_leaderboard_display_method( '#fs_affiliates_leaderboard_display_method' ) ;
            FS_Affiliates_Modules.get_lifetime_affiliate_commission_rate_options( '.fs_affiliates_lifetime_commission_rate' ) ;
            FS_Affiliates_Modules.get_block_affiliate_login_options( '#fs_affiliates_fraud_protection_block_login' ) ;
            FS_Affiliates_Modules.get_commission_for_same_ip_options( '#fs_affiliates_fraud_protection_commission_for_same_ip' ) ;
            FS_Affiliates_Modules.get_restrict_landing_commission_referral( '#fs_affiliates_fraud_protection_landing_commission_for_same_ip' ) ;
            FS_Affiliates_Modules.get_fb_app_id_options( '#fs_affiliates_socialshare_fbshare' ) ;
            FS_Affiliates_Modules.get_checkout_affiliate_affs_selection( '#fs_affiliates_checkout_affiliate_affs_selection' ) ;
            FS_Affiliates_Modules.multi_level_marketing_display_data_enable_selection( '#fs_affiliates_multi_level_marketing_display_data_enable' ) ;
            //payoutstatement module
            FS_Affiliates_Modules.get_show_hide_payout_name_display_settings( '#fs_affiliates_payout_statements_payout_name_disp_type' ) ;
            //WooCommerce Account Management
            FS_Affiliates_Modules.non_affiliate_hide_option( '#fs_affiliates_wc_account_management_allow_users' ) ;
            FS_Affiliates_Modules.landing_commission_usage_type_option( '.fs_affs_lc_usage_type' ) ;

            // Referral Code Module
            FS_Affiliates_Modules.checkout_page_referral_code( '#fs_affiliates_referral_code_checkout_page_visible' ) ;
            FS_Affiliates_Modules.myaccount_page_referral_code( '#fs_affiliates_referral_code_myaccount_page_visible' ) ;
            FS_Affiliates_Modules.affiliate_registration_page_referral_code( '#fs_affiliates_referral_code_registration_page_visible' ) ;

            // Coupon Linking Module
            FS_Affiliates_Modules.linking_couon_commission_level( '#fs_affiliates_coupon_commission_level' ) ;

            //Additional dashboard tabs Module
            if ( $( '.menu_show_or_hide' ).length > 0 ) {
                $( '.menu_show_or_hide' ).each( function ( ) {
                    FS_Affiliates_Modules.get_show_hide_submenu_selector( this ) ;
                } ) ;
            }

            if ( $( '.fs_affiliates_allowed_affiliates_method' ).length > 0 ) {
                $( '.fs_affiliates_allowed_affiliates_method' ).each( function ( ) {
                    FS_Affiliates_Modules.get_allowed_affiliates_method_options( this ) ;
                } ) ;
            }

            $( document ).click( function ( e ) {
                var container = $( "div.fs_affiliates_custom_drop_down" ) ;

                // if the target of the click isn't the container nor a descendant of the container
                if ( !container.is( e.target ) && container.has( e.target ).length === 0 )
                {
                    $( "div.fs_affiliates_popup_icons" ).css( "display" , "none" ) ;
                }

            } ) ;

            //default hide all tabs
            $( "div.fs_affiliates_cell_one" ).hide() ;
        } , product_restrictions : function ( event ) {
            event.preventDefault( ) ;
            var $this = $( event.currentTarget ) ;
            FS_Affiliates_Modules.get_product_restrictions( $this ) ;
        } , checkout_affiliate_affs_selection : function ( event ) {
            event.preventDefault( ) ;
            var $this = $( event.currentTarget ) ;
            FS_Affiliates_Modules.get_checkout_affiliate_affs_selection( $this ) ;
        } , multi_level_marketing_display_data_enable : function ( event ) {
            event.preventDefault( ) ;
            var $this = $( event.currentTarget ) ;
            FS_Affiliates_Modules.multi_level_marketing_display_data_enable_selection( $this ) ;
        } , sms_module_api_method : function ( event ) {
            event.preventDefault( ) ;
            var $this = $( event.currentTarget ) ;
            FS_Affiliates_Modules.get_sms_module_api_method( $this ) ;
        } , leaderboard_display_method : function ( event ) {
            event.preventDefault( ) ;
            var $this = $( event.currentTarget ) ;
            FS_Affiliates_Modules.get_leaderboard_display_method( $this ) ;
        } , paypal_payouts_mode : function ( event ) {
            event.preventDefault( ) ;
            var $this = $( event.currentTarget ) ;
            FS_Affiliates_Modules.get_paypal_payouts_mode( $this ) ;
        } , woocommerce_signup_toggle : function ( event ) {
            event.preventDefault( ) ;
            var $this = $( event.currentTarget ) ;
            FS_Affiliates_Modules.get_woocommerce_signup( $this ) ;
        } , affiliate_signup_toggle : function ( event ) {
            event.preventDefault( ) ;
            var $this = $( event.currentTarget ) ;
            FS_Affiliates_Modules.get_affiliate_signup( $this ) ;
        } , stop_commission : function ( event ) {
            event.preventDefault( ) ;
            var $this = $( event.currentTarget ) ;
            FS_Affiliates_Modules.get_stop_commission_options( $this ) ;
        } , allowed_affiliates_method : function ( event ) {
            event.preventDefault( ) ;
            var $this = $( event.currentTarget ) ;
            FS_Affiliates_Modules.get_allowed_affiliates_method_options( $this ) ;
        } , block_affiliate_login : function ( event ) {
            event.preventDefault( ) ;
            var $this = $( event.currentTarget ) ;
            FS_Affiliates_Modules.get_block_affiliate_login_options( $this ) ;
        } , commission_for_same_ip : function ( event ) {
            event.preventDefault( ) ;
            var $this = $( event.currentTarget ) ;
            FS_Affiliates_Modules.get_commission_for_same_ip_options( $this ) ;
        } , restrict_landing_commission_referral : function ( event ) {
            event.preventDefault( ) ;
            var $this = $( event.currentTarget ) ;
            FS_Affiliates_Modules.get_restrict_landing_commission_referral( $this ) ;
        } , show_hide_submenu_selector : function ( event ) {
            event.preventDefault( ) ;
            var $this = $( event.currentTarget ) ;
            FS_Affiliates_Modules.get_show_hide_submenu_selector( $this ) ;
        } , lifetime_affiliate_commission_rate : function ( event ) {
            event.preventDefault( ) ;
            var $this = $( event.currentTarget ) ;
            FS_Affiliates_Modules.get_lifetime_affiliate_commission_rate_options( $this ) ;
        } , fb_app_id : function ( event ) {
            event.preventDefault( ) ;
            var $this = $( event.currentTarget ) ;
            FS_Affiliates_Modules.get_fb_app_id_options( $this ) ;
        } , show_hide_payout_name_display_settings : function ( event ) {
            event.preventDefault( ) ;
            var $this = $( event.currentTarget ) ;
            FS_Affiliates_Modules.get_show_hide_payout_name_display_settings( $this ) ;
        } , toggle_non_affiliate_hide_option : function ( event ) {
            event.preventDefault( ) ;
            var $this = $( event.currentTarget ) ;
            FS_Affiliates_Modules.non_affiliate_hide_option( $this ) ;
        } , toggle_landing_commission_usage_type_option : function ( event ) {
            event.preventDefault( ) ;
            var $this = $( event.currentTarget ) ;
            FS_Affiliates_Modules.landing_commission_usage_type_option( $this ) ;
        } , toggle_checkout_page_referral_code : function ( event ) {
            event.preventDefault( ) ;
            var $this = $( event.currentTarget ) ;
            FS_Affiliates_Modules.checkout_page_referral_code( $this ) ;
        } , toggle_myaccount_page_referral_code : function ( event ) {
            event.preventDefault( ) ;
            var $this = $( event.currentTarget ) ;
            FS_Affiliates_Modules.myaccount_page_referral_code( $this ) ;
        } , toggle_affiliate_registration_page_referral_code : function ( event ) {
            event.preventDefault( ) ;
            var $this = $( event.currentTarget ) ;
            FS_Affiliates_Modules.affiliate_registration_page_referral_code( $this ) ;
        } , toggle_linking_couon_commission_level : function ( event ) {
            event.preventDefault( ) ;
            var $this = $( event.currentTarget ) ;
            FS_Affiliates_Modules.linking_couon_commission_level( $this ) ;
        } , checkout_page_referral_code : function ( $this ) {
            if ( $( $this ).is( ':checked' ) == true ) {
                $( '#fs_affiliates_referral_code_checkout_page_visible_type' ).closest( 'tr' ).show( ) ;
            } else {
                $( '#fs_affiliates_referral_code_checkout_page_visible_type' ).closest( 'tr' ).hide( ) ;
            }
        } , myaccount_page_referral_code : function ( $this ) {
            if ( $( $this ).is( ':checked' ) == true ) {
                $( '#fs_affiliates_referral_code_myaccount_page_visible_type' ).closest( 'tr' ).show( ) ;
            } else {
                $( '#fs_affiliates_referral_code_myaccount_page_visible_type' ).closest( 'tr' ).hide( ) ;
            }
        } , affiliate_registration_page_referral_code : function ( $this ) {
            if ( $( $this ).is( ':checked' ) == true ) {
                $( '#fs_affiliates_referral_code_registration_page_visible_type' ).closest( 'tr' ).show( ) ;
            } else {
                $( '#fs_affiliates_referral_code_registration_page_visible_type' ).closest( 'tr' ).hide( ) ;
            }
        } , get_fb_app_id_options : function ( $this ) {
            if ( $( $this ).is( ':checked' ) == true ) {
                $( '#fs_affiliates_socialshare_fbappid' ).closest( 'tr' ).show( ) ;
            } else {
                $( '#fs_affiliates_socialshare_fbappid' ).closest( 'tr' ).hide( ) ;
            }
        } , get_product_restrictions : function ( $this ) {
            if ( $( $this ).val( ) == 'selected_products' ) {
                $( '.fs_affiliates_product_restrictions' ).closest( 'tr' ).show( ) ;
                $( '.fs_affiliates_category_restrictions' ).closest( 'tr' ).hide( ) ;
            } else if ( $( $this ).val( ) == 'selected_categories' ) {
                $( '.fs_affiliates_product_restrictions' ).closest( 'tr' ).hide( ) ;
                $( '.fs_affiliates_category_restrictions' ).closest( 'tr' ).show( ) ;
            } else {
                $( '.fs_affiliates_product_restrictions' ).closest( 'tr' ).hide( ) ;
                $( '.fs_affiliates_category_restrictions' ).closest( 'tr' ).hide( ) ;
            }
        } , get_leaderboard_display_method : function ( $this ) {
            if ( $( $this ).val() == '1' ) {
                $( '#fs_affiliates_leaderboard_predefined_type' ).closest( 'tr' ).show( ) ;
            } else {
                $( '#fs_affiliates_leaderboard_predefined_type' ).closest( 'tr' ).hide( ) ;
            }
        } , get_checkout_affiliate_affs_selection : function ( $this ) {
            if ( $( $this ).val( ) == '3' ) {
                $( '.fs_affiliates_user_selection_fields' ).closest( 'tr' ).show( ) ;
            } else {
                $( '.fs_affiliates_user_selection_fields' ).closest( 'tr' ).hide( ) ;
            }
        } , multi_level_marketing_display_data_enable_selection : function ( $this ) {
            if ( $( $this ).prop( 'checked' ) == true ) {
                $( '.fs_affiliates_node_link_display' ).closest( 'tr' ).show( ) ;
            } else {
                $( '.fs_affiliates_node_link_display' ).closest( 'tr' ).hide( ) ;
            }
        } , get_sms_module_api_method : function ( $this ) {
            if ( $( $this ).val( ) == '2' ) {
                $( '.fs_affiliates_twilio_account_method' ).closest( 'tr' ).hide( ) ;
                $( '.fs_affiliates_nexmo_account_method' ).closest( 'tr' ).show( ) ;
            } else {
                $( '.fs_affiliates_twilio_account_method' ).closest( 'tr' ).show( ) ;
                $( '.fs_affiliates_nexmo_account_method' ).closest( 'tr' ).hide( ) ;
            }
        } , get_paypal_payouts_mode : function ( $this ) {
            if ( $( $this ).prop( 'checked' ) == true ) {
                $( '.fs_affiliates_sandbox_mode' ).closest( 'tr' ).show( ) ;
                $( '.fs_affiliates_live_mode' ).closest( 'tr' ).hide( ) ;
            } else {
                $( '.fs_affiliates_live_mode' ).closest( 'tr' ).show( ) ;
                $( '.fs_affiliates_sandbox_mode' ).closest( 'tr' ).hide( ) ;
            }
        } , get_affiliate_signup : function ( $this ) {
            if ( $( $this ).prop( 'checked' ) == true ) {
                $( '#fs_affiliates_signup_commission_aff_commission_value' ).closest( 'tr' ).show( ) ;
            } else {
                $( '#fs_affiliates_signup_commission_aff_commission_value' ).closest( 'tr' ).hide( ) ;
            }
        } , get_woocommerce_signup : function ( $this ) {
            if ( $( $this ).prop( 'checked' ) == true ) {
                $( '.fs_affiliates_wc_signup_fields' ).closest( 'tr' ).show( ) ;
            } else {
                $( '.fs_affiliates_wc_signup_fields' ).closest( 'tr' ).hide( ) ;
            }
        } , get_stop_commission_options : function ( $this ) {
            if ( $( $this ).val( ) == '3' ) {
                $( '.fs_affiliates_order_count' ).closest( 'tr' ).hide( ) ;
                $( '.fs_affiliates_amount_spent' ).closest( 'tr' ).hide( ) ;
                $( '.fs_affiliates_order_amount' ).closest( 'tr' ).show( ) ;
            } else if ( $( $this ).val( ) == '2' ) {
                $( '.fs_affiliates_order_count' ).closest( 'tr' ).hide( ) ;
                $( '.fs_affiliates_amount_spent' ).closest( 'tr' ).show( ) ;
                $( '.fs_affiliates_order_amount' ).closest( 'tr' ).hide( ) ;
            } else {
                $( '.fs_affiliates_order_count' ).closest( 'tr' ).show( ) ;
                $( '.fs_affiliates_amount_spent' ).closest( 'tr' ).hide( ) ;
                $( '.fs_affiliates_order_amount' ).closest( 'tr' ).hide( ) ;
            }
        } , get_allowed_affiliates_method_options : function ( $this ) {
            var current_tr = $( $this ).closest( 'tr' ) ;

            if ( $( $this ).val( ) == '2' ) {
                current_tr.next().find( '.fs_affiliates_selected_affiliate' ).closest( 'tr' ).show( ) ;
            } else {
                current_tr.next().find( '.fs_affiliates_selected_affiliate' ).closest( 'tr' ).hide( ) ;
            }
        } , get_show_hide_submenu_selector : function ( $this ) {
            if ( $( $this ).is( ':checked' ) == true ) {
                $( $this ).closest( '.fs_affiliates_cell_one' ).find( '.submenu_selector' ).closest( 'p' ).hide( ) ;
            } else {
                $( $this ).closest( '.fs_affiliates_cell_one' ).find( '.submenu_selector' ).closest( 'p' ).show( ) ;
            }
        } ,
        get_lifetime_affiliate_commission_rate_options : function ( $this ) {

            if ( $( $this ).val( ) == '2' ) {
                $( '.fs_affiliates_lifetime_commission_value' ).closest( 'tr' ).show( ) ;
            } else if ( $( $this ).val( ) == '3' ) {
                $( '.fs_affiliates_lifetime_commission_value' ).closest( 'tr' ).show( ) ;
            } else {
                $( '.fs_affiliates_lifetime_commission_value' ).closest( 'tr' ).hide( ) ;
            }
        } , get_block_affiliate_login_options : function ( $this ) {
            if ( $( $this ).is( ':checked' ) == true ) {
                $( '#fs_affiliates_fraud_protection_no_of_attempt' ).closest( 'tr' ).show( ) ;
                $( '#fs_affiliates_fraud_protection_min_duration' ).closest( 'tr' ).show( ) ;
            } else {
                $( '#fs_affiliates_fraud_protection_no_of_attempt' ).closest( 'tr' ).hide( ) ;
                $( '#fs_affiliates_fraud_protection_min_duration' ).closest( 'tr' ).hide( ) ;
            }
        } , get_commission_for_same_ip_options : function ( $this ) {
            if ( $( $this ).is( ':checked' ) == true ) {
                $( '#fs_affiliates_fraud_protection_threshold_duration' ).closest( 'tr' ).show( ) ;
            } else {
                $( '#fs_affiliates_fraud_protection_threshold_duration' ).closest( 'tr' ).hide( ) ;
            }
        } , get_restrict_landing_commission_referral : function ( $this ) {
            if ( $( $this ).is( ':checked' ) == true ) {
                $( '#fs_affiliates_fraud_protection_landing_commission_threshold_duration' ).closest( 'tr' ).show( ) ;
            } else {
                $( '#fs_affiliates_fraud_protection_landing_commission_threshold_duration' ).closest( 'tr' ).hide( ) ;
            }
        } , non_affiliate_hide_option : function ( $this ) {
            if ( $( $this ).is( ':checked' ) == true ) {
                $( '#fs_affiliates_wc_account_management_show_non_affiliates' ).closest( 'tr' ).show( ) ;
            } else {
                $( '#fs_affiliates_wc_account_management_show_non_affiliates' ).closest( 'tr' ).hide( ) ;
            }
        } , landing_commission_usage_type_option : function ( $this ) {
            if ( $( $this ).val( ) == '2' ) {
                $( '.fs_affs_lc_validity_count' ).closest( 'tr' ).show( ) ;
            } else {
                $( '.fs_affs_lc_validity_count' ).closest( 'tr' ).hide( ) ;
            }
        } , linking_couon_commission_level : function ( $this ) {

            if ( '2' == $( $this ).val() ) {
                $( '.fs_affiliates_coupon_commission_level' ).closest( 'tr' ).show( ) ;
            } else {
                $( '.fs_affiliates_coupon_commission_level' ).closest( 'tr' ).hide( ) ;
            }
        } , add_mlm_rule : function ( event ) {
            event.preventDefault( ) ;
            var count = parseInt( $( 'input#fs_affiliates_mlm_rule_id:last' ).val( ) ) ;
            count = count + 1 || 1 ;
            FS_Affiliates_Modules.block( '.fs_affiliates_mlm_rules_table' ) ;
            var data = {
                action : 'fs_affiliates_add_mlm_rule' ,
                count : count ,
                fs_security : fs_affiliates_modules_params.mlm_nonce
            } ;
            $.post( ajaxurl , data , function ( response ) {
                if ( true === response.success ) {
                    $( 'table.fs_affiliates_mlm_rules_table' ).append( response.data.content ) ;
                    FS_Affiliates_Modules.unblock( '.fs_affiliates_mlm_rules_table' ) ;
                } else {
                    window.alert( response.data.error ) ;
                    FS_Affiliates_Modules.unblock( '.fs_affiliates_mlm_rules_table' ) ;
                }
            } ) ;
        } , remove_mlm_rule : function ( event ) {
            event.preventDefault( ) ;
            var $this = $( event.currentTarget ) ;
            if ( $( '.fs_affiliates_remove_mlm_rule' ).length > 1 ) {
                $( $this ).closest( 'tr' ).remove( ) ;
            } else {
                alert( fs_affiliates_modules_params.default_error_msg ) ;
            }

        } , add_dashboard_tab_rule : function ( event ) {
            event.preventDefault( ) ;
            var count = parseInt( $( 'input#fs_affiliates_dashboard_tab_rule_id:last' ).val( ) ) ;
            count = count + 1 || 1 ;
            FS_Affiliates_Modules.block( '.fs_affiliates_dashboard_additional_tabs_tables' ) ;
            var data = {
                action : 'fs_affiliates_add_dashboard_tab_rule' ,
                count : count ,
                fs_security : fs_affiliates_modules_params.dashboard_tab_nonce
            } ;
            $.post( ajaxurl , data , function ( response ) {
                if ( true === response.success ) {
                    $( 'table.fs_affiliates_dashboard_additional_tabs_table #fs_affiliates_list' ).append( response.data.content ) ;
                    FS_Affiliates_Modules.unblock( '.fs_affiliates_dashboard_additional_tabs_table' ) ;
                } else {
                    window.alert( response.data.error ) ;
                    FS_Affiliates_Modules.unblock( '.fs_affiliates_dashboard_additional_tabs_tables' ) ;
                }
            } ) ;
        } , remove_dashboard_tab_rule : function ( event ) {

            var $this = $( event.currentTarget ) ;
            $( $this ).closest( 'tr' ).remove( ) ;
        } , validate_export_data : function ( event ) {

            var $this = $( event.currentTarget ) ;
            var form = $( $this ).closest( 'form' ) ;

            if ( form.find( '.fs_affiliates_allowed_affiliates_method' ).val( ) == '2' && form.find( '#fs_affiliates_selected_affiliates' ).val( ) == null ) {
                event.preventDefault( ) ;
                alert( fs_affiliates_modules_params.export_selected_affiliates_error_msg ) ;
                return false ;
            }

            return true ;

        } , test_pushover_notification : function ( event ) {
            event.preventDefault( ) ;
            var $this = $( event.currentTarget ) ;

            if ( !confirm( fs_affiliates_modules_params.pushover_alert_msg ) )
                return false ;

            FS_Affiliates_Modules.block( $this.closest( 'tr' ) ) ;

            var data = {
                action : 'fs_affiliates_test_pushover_notification' ,
                fs_security : fs_affiliates_modules_params.test_pushover_nonce
            } ;
            $.post( ajaxurl , data , function ( response ) {
                if ( true === response.success ) {
                    window.alert( fs_affiliates_modules_params.pushover_success_msg ) ;
                    FS_Affiliates_Modules.unblock( $this.closest( 'tr' ) ) ;
                } else {
                    window.alert( response.data.error ) ;
                    FS_Affiliates_Modules.unblock( $this.closest( 'tr' ) ) ;
                }
            } ) ;
        } , show_popup_icons : function ( event ) {
            event.preventDefault( ) ;
            var $this = $( event.currentTarget ) ;
            var drop_down = $( $this ).closest( "div.fs_affiliates_custom_drop_down" ) ;

            drop_down.find( "div.fs_affiliates_popup_icons" ).css( "display" , "block" ) ;
        } , toggle_tabs : function ( event ) {
            event.preventDefault( ) ;
            var $this = $( event.currentTarget ) ;
            var tr = $( $this ).closest( "tr" ) ;

            tr.find( "div.fs_affiliates_cell_one" ).toggle() ;
        } , select_popup_icon : function ( event ) {
            event.preventDefault( ) ;
            var $this = $( event.currentTarget ) ;
            var drop_down = $( $this ).closest( "div.fs_affiliates_custom_drop_down" ) ;

            drop_down.find( "div.fs_affiliates_selected_icon" ).html( $( $this ).html() ) ;
            drop_down.find( "input.fs_affiliates_selected_icon_code" ).val( $( $this ).data( 'class' ) ) ;
            drop_down.find( "div.fs_affiliates_popup_icons" ).css( "display" , "none" ) ;
        } , get_show_hide_payout_name_display_settings : function ( event ) {
            if ( $( event ).val() === '1' ) {
                $( '.fs_affiliates_payout_statements_advanced_payout_fields' ).closest( 'tr' ).hide( ) ;
                $( '.fs_affiliates_payout_statements_date_format_payout_fields' ).closest( 'tr' ).show( ) ;
            } else {
                $( '.fs_affiliates_payout_statements_advanced_payout_fields' ).closest( 'tr' ).show( ) ;
                $( '.fs_affiliates_payout_statements_date_format_payout_fields' ).closest( 'tr' ).hide( ) ;
            }
        } ,

        validate_product_restriction_inputs : function ( event ) {
            var restriction_type = $( '#fs_affiliates_wc_product_restriction_product_selection' ).val() ;

            if ( 'selected_products' == restriction_type ) {
                var selected_product = $( '#fs_affiliates_wc_product_restriction_selected_products' ).val() ;

                if ( '' == selected_product || null == selected_product ) {
                    alert( fs_affiliates_modules_params.product_select_msg ) ;
                    return false ;
                }
            } else if ( 'selected_categories' == restriction_type ) {
                var selected_category = $( '#fs_affiliates_wc_product_restriction_selected_categories' ).val() ;

                if ( '' == selected_category || null == selected_category ) {
                    alert( fs_affiliates_modules_params.category_select_msg ) ;
                    return false ;
                }
            }

            return true ;
        } ,
        block : function ( id ) {
            $( id ).block( {
                message : null ,
                overlayCSS : {
                    background : '#fff' ,
                    opacity : 0.6
                }
            } ) ;
        } ,
        unblock : function ( id ) {
            $( id ).unblock( ) ;
        } ,
    } ;
    FS_Affiliates_Modules.init( ) ;
} ) ;
