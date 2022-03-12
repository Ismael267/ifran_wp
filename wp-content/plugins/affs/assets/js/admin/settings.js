jQuery ( function ( $ ) {

    // select image for creative
    var file_frame ;
    $ ( 'body' ).on ( 'click' , '.fs_creative_upload_image_button' , function ( e ) {

        e.preventDefault ( ) ;
        var $button = $ ( this ) ;
        var formfield = $ ( this ).prev ( ) ;
        // If the media frame already exists, reopen it.
        if ( file_frame ) {
            file_frame.open ( ) ;
            return ;
        }

        // Create the media frame.
        file_frame = wp.media.frames.file_frame = wp.media ( {
            frame : 'select' ,
            title : $button.data ( 'title' ) ,
            multiple : false ,
            library : {
                type : 'image'
            } ,
            button : {
                text : $button.data ( 'button' )
            }
        } ) ;
        // When an image is selected, run a callback.
        file_frame.on ( 'select' , function ( ) {
            var attachment = file_frame.state ( ).get ( 'selection' ).first ( ).toJSON ( ) ;
            formfield.val ( attachment.url ) ;
            var img = $ ( '<img />' ) ;
            img.attr ( 'src' , attachment.url ) ;
            // replace previous image with new one if selected
            $ ( '#fs_creative_preview_image' ).empty ( ).append ( img ) ;
        } ) ;
        // Finally, open the modal
        file_frame.open ( ) ;
    } ) ;

    $ ( 'body' ).on ( 'click' , '.fs_affiliates_payout_statements_image_url_btn' , function ( e ) {

        e.preventDefault ( ) ;
        var $button = $ ( this ) ;
        var formfield = $ ( this ).prev ( ) ;
        // If the media frame already exists, reopen it.
        if ( file_frame ) {
            file_frame.open ( ) ;
            return ;
        }

        // Create the media frame.
        file_frame = wp.media.frames.file_frame = wp.media ( {
            frame : 'select' ,
            title : $button.data ( 'title' ) ,
            multiple : false ,
            library : {
                type : 'image'
            } ,
            button : {
                text : $button.data ( 'button' )
            }
        } ) ;
        // When an image is selected, run a callback.
        file_frame.on ( 'select' , function ( ) {
            var attachment = file_frame.state ( ).get ( 'selection' ).first ( ).toJSON ( ) ;
            formfield.val ( attachment.url ) ;
            var img = $ ( '<img />' ) ;
            img.attr ( 'src' , attachment.url ) ;
        } ) ;
        // Finally, open the modal
        file_frame.open ( ) ;
    } ) ;

    //File Upload
    if ( $ ( '.fs_affiliates_file_upload' ).length ) {
        $ ( '.fs_affiliates_file_upload' ).each ( function ( e ) {
            var data = [ {
                    name : 'action' ,
                    value : 'fs_affiliates_file_upload' ,
                } ,
                {
                    name : 'key' ,
                    value : $ ( this ).attr ( 'name' )
                } ] ;
            $ ( this ).fileupload ( {
                url : ajaxurl ,
                type : 'POST' ,
                async : false ,
                formData : function ( form ) {
                    return data ;
                } ,
                dataType : 'json' ,
                done : function ( e , data ) {
                    if ( data.result.success === true ) {
                        var html ;
                        html = '<p class="fs_affiliates_uploaded_file_name"><b>' + data.files[0].name + '</b>' ;
                        html += '<span class="fs_affiliates_delete_uploaded_file" style="color:red;margin-left:10px;cursor: pointer;">[x]' ;
                        html += '<input type="hidden" class="fs_affiliates_remove_file" value=' + data.files[0].name + ' /></span></p>' ;

                        $ ( this ).closest ( 'div' ).find ( '.fs_affiliates_display_file_names' ).append ( html ) ;
                    } else {
                        $ ( '.fs_affiliates_display_file_names' ).html ( '<span class="fs_affiliates_error_msg_for_upload" style="color:red;">' + data.result.data.content + '</span>' ) ;
                        $ ( '.fs_affiliates_error_msg_for_upload' ).delay ( 3000 ).fadeOut ( ) ;
                    }
                }
            } ) ;
        } ) ;
    }


    $ ( document ).click ( function ( e ) {
        if ( ! $ ( e.target ).is ( ".fs_affilaites_colorpicker, .iris-picker, .iris-picker-inner" ) ) {
            $ ( '.fs_affilaites_colorpicker' ).iris ( 'hide' ) ;
        }
    } ) ;
    $ ( '.fs_affilaites_colorpicker' ).click ( function ( event ) {
        $ ( '.fs_affilaites_colorpicker' ).iris ( 'hide' ) ;
        $ ( this ).iris ( 'show' ) ;
    } ) ;
    var FS_Affiliates_Settings = {
        init : function ( ) {

            this.trigger_on_page_load ( ) ;
            $ ( document ).on ( 'click' , '.fs_affiliates_delete' , this.delete_affiliates ) ;
            $ ( document ).on ( 'click' , '.fs_affiliates_bulk_update_affiliates' , this.bulk_update_affiliates ) ;
            $ ( document ).on ( 'click' , '.fs_affiliates_delete_uploaded_file' , this.remove_uploaded_file ) ;
            $ ( document ).on ( 'click' , '.fs_affiliates_delete_table_uploaded_file' , this.remove_table_uploaded_file ) ;
            $ ( document ).on ( 'click' , '.fs_affiliates_settings_color_mode' , this.settings_color_mode ) ;
            $ ( document ).on ( 'click' , '.fs_affiliates_module_settings_color_mode' , this.module_settings_color_mode ) ;
            $ ( document ).on ( 'change' , '#fs_affiliates_user_selection_type' , this.toggle_user_selection_type ) ;
            $ ( document ).on ( 'change' , '#fs_affiliates_registration_method' , this.toggle_registration_method ) ;
            $ ( document ).on ( 'change' , '#fs_affiliates_commission_type' , this.toggle_commission_type ) ;
            $ ( document ).on ( 'change' , '.fs_affiliates_modules_enabled' , this.toggle_modules_enabled ) ;
            $ ( document ).on ( 'change' , '.fs_affiliates_integrations_enabled' , this.toggle_integrations_enabled ) ;
            $ ( document ).on ( 'change' , '.fs_affiliates_notifications_enabled' , this.toggle_notifications_enabled ) ;
            $ ( document ).on ( 'change' , '#fs_affiliates_affs_email_opt_in_email_service' , this.toggle_email_opt_in_services ) ;
            $ ( document ).on ( 'change' , '#fs_affiliates_wc_coupon_restrict' , this.toggle_woo_coupon_restriction ) ;
            $ ( document ).on ( 'change' , '#fs_affiliates_referral_link_type' , this.toggle_referral_link_type ) ;
            $ ( document ).on ( 'change' , '#fs_affiliates_payment_method_selection_type' , this.toggle_payment_method_selection_from ) ;
            
        } , trigger_on_page_load : function ( ) {
            this.enhanced_date_picker ( ) ;
            this.sortable_fields ( ) ;
            this.payment_gateways_sortable () ;
            this.enhanced_color_picker ( ) ;
            this.get_registration_method ( '#fs_affiliates_registration_method' ) ;
            this.get_user_selection_type ( '#fs_affiliates_user_selection_type' ) ;
            this.get_commission_type ( '#fs_affiliates_commission_type' ) ;
            this.email_services_change_core ( '#fs_affiliates_affs_email_opt_in_email_service' ) ;
            this.woo_coupon_restriction('#fs_affiliates_wc_coupon_restrict');
            this.referral_link_type('#fs_affiliates_referral_link_type');
            this.payment_method_selection_from('#fs_affiliates_payment_method_selection_type');
        } , enhanced_date_picker : function ( ) {
            //return if class not exists
            if ( ! $ ( '.fs_affilaites_datepicker' ).length )
                return ;
            //datepicker initialization
            $ ( '.fs_affilaites_datepicker' ).each ( function ( ) {
                $ ( this ).datepicker ( {
                    dateFormat : "mm/dd/yy" ,
                    changeMonth : true ,
                    changeYear : true
                } ) ;
            } ) ;
        } , enhanced_color_picker : function ( ) {
            //return if class not exists
            if ( ! $ ( '.fs_affilaites_colorpicker' ).length )
                return ;
            //datepicker initialization
            $ ( '.fs_affilaites_colorpicker' ).each ( function ( ) {
                $ ( this ).iris ( {
                    change : function ( event , ui ) {
                        $ ( this ).css ( { backgroundColor : ui.color.toString ( ) } ) ;
                    } ,
                    hide : true ,
                    border : true
                } ) ;
            } ) ;
        } , toggle_registration_method : function ( event ) {
            event.preventDefault ( ) ;
            var $this = $ ( event.currentTarget ) ;
            FS_Affiliates_Settings.get_registration_method ( $this ) ;
        } , toggle_email_opt_in_services : function ( event ) {
            event.preventDefault () ;
            var $this = $ ( event.currentTarget ) ;
            FS_Affiliates_Settings.email_services_change_core ( $this ) ;
        } , toggle_woo_coupon_restriction : function ( event ) {
            event.preventDefault () ;
            var $this = $ ( event.currentTarget ) ;
            FS_Affiliates_Settings.woo_coupon_restriction ( $this ) ;
        }, toggle_referral_link_type : function (event) {
            event.preventDefault () ;
            var $this = $ ( event.currentTarget ) ;
            FS_Affiliates_Settings.referral_link_type ( $this ) ;
        }, toggle_payment_method_selection_from : function (event) {
            event.preventDefault () ;
            var $this = $ ( event.currentTarget ) ;
            FS_Affiliates_Settings.payment_method_selection_from ( $this ) ;
        },toggle_commission_type : function ( event ) {
            event.preventDefault ( ) ;
            var $this = $ ( event.currentTarget ) ;
            FS_Affiliates_Settings.get_commission_type ( $this ) ;
        } , toggle_user_selection_type : function ( event ) {
            event.preventDefault ( ) ;
            var $this = $ ( event.currentTarget ) ;
            FS_Affiliates_Settings.get_user_selection_type ( $this ) ;
        } , get_user_selection_type : function ( $this ) {
            var option = $ ( $this ).val () ;
            if ( option == '1' ) {
                $ ( '#fs_affiliates_selected_users' ).closest ( 'tr' ).hide () ;
            } else {
                $ ( '#fs_affiliates_selected_users' ).closest ( 'tr' ).show () ;
            }
        } , settings_color_mode : function ( event ) {
            var $this = $ ( event.currentTarget ) ;
            var data = {
                action : 'fs_affiliates_toggle_settings_color_mode' ,
                color_mode : $this.val ( ) ,
                fs_security : fs_affiliates_settings_params.settings_color_mode_nonce
            } ;
            $.post ( ajaxurl , data , function ( res ) {

                if ( res.success === true ) {
                    window.location.reload ( ) ;
                } else {
                    window.alert ( res.data.error ) ;
                }

            } ) ;
        } , module_settings_color_mode : function ( event ) {
            var $this = $ ( event.currentTarget ) ;
            var data = {
                action : 'fs_affiliates_toggle_module_settings_color_mode' ,
                color_mode : $this.val ( ) ,
                fs_security : fs_affiliates_settings_params.settings_color_mode_nonce
            } ;
            $.post ( ajaxurl , data , function ( res ) {

                if ( res.success === true ) {
                    window.location.reload ( ) ;
                } else {
                    window.alert ( res.data.error ) ;
                }

            } ) ;
        } , delete_affiliates : function ( event ) {
            var $this = $ ( event.currentTarget ) ,
                    type = $ ( $this ).data ( 'type' ) ,
                    message ;
            switch ( type ) {
                case 'affiliate':
                    message = fs_affiliates_settings_params.affiliate_delete_message ;
                    break ;
                case 'referral':
                    message = fs_affiliates_settings_params.referral_delete_message ;
                    break ;
                case 'landingcommission':
                    message = fs_affiliates_settings_params.landing_commission_delete_message ;
                    break ;
            }

            if ( confirm ( message ) ) {
                return true ;
            }

            return false ;
        } , remove_uploaded_file : function ( event ) {
            event.preventDefault () ;
            var $this = $ ( event.currentTarget ) ;

            var data = {
                action : 'fs_affiliates_remove_uploaded_file' ,
                key : $ ( $this ).closest ( 'div' ).find ( '.fs_affiliates_uploaded_file_key' ).val () ,
                file_name : $ ( $this ).find ( '.fs_affiliates_remove_file' ).val () ,
            } ;
            $.post ( ajaxurl , data , function ( response ) {
                if ( true === response.success ) {
                    $ ( $this ).closest ( 'p.fs_affiliates_uploaded_file_name' ).remove () ;
                } else {
                    window.alert ( response.data.error ) ;
                }
            } ) ;
        } , remove_table_uploaded_file : function ( event ) {
            event.preventDefault () ;
            var $this = $ ( event.currentTarget ) ;

            var data = {
                action : 'fs_affiliates_remove_uploaded_file' ,
                key : $ ( $this ).find ( '.fs_affiliates_uploaded_file_key' ).val () ,
                file_name : $ ( $this ).find ( '.fs_affiliates_remove_file' ).val () ,
            } ;
            $.post ( ajaxurl , data , function ( response ) {
                if ( true === response.success ) {
                    $ ( $this ).closest ( 'tr' ).remove () ;
                } else {
                    window.alert ( response.data.error ) ;
                }
            } ) ;
        } , email_services_change_core : function ( $this ) {
            var selected_service = $ ( $this ).val () ;
            if ( selected_service == 'mailchimp' ) {
                $ ( '#fs_affiliates_affs_email_opt_in_url' ).closest ( 'tr' ).hide () ;
            } else {
                $ ( '#fs_affiliates_affs_email_opt_in_url' ).closest ( 'tr' ).show () ;
            }
        } , woo_coupon_restriction : function ( $this ) {
            if( $( $this ).is( ':checked' ) ) {
                $( '#fs_affiliates_wc_coupon_restrict_msg' ).closest( 'tr' ).show() ;
            } else {
                $( '#fs_affiliates_wc_coupon_restrict_msg' ).closest( 'tr' ).hide() ;
            }
        }, referral_link_type : function ( $this ) {
            if( '2' == $( $this ).val() ) {
                $( '.fs_affiliates_default_referral_settings' ).closest( 'tr' ).hide() ;
                $( '.fs_affiliates_static_referral_settings' ).closest( 'tr' ).show() ;
            } else {
                $( '.fs_affiliates_default_referral_settings' ).closest( 'tr' ).show() ;
                $( '.fs_affiliates_static_referral_settings' ).closest( 'tr' ).hide() ;
            }
        }, payment_method_selection_from : function ( $this ) {
            if( '2' == $( $this ).val() ) {
                $('#fs_affiliates_admin_payment_method').closest( 'tr' ).show() ;
                $('#fs_affiliates_payment_settings_table').hide();
            } else {
                $('#fs_affiliates_payment_settings_table').show();
                $('#fs_affiliates_admin_payment_method').closest( 'tr' ).hide() ;
            }
        },toggle_modules_enabled : function ( event ) {
            event.preventDefault ( ) ;
            var $img_url ,
                    $this = $ ( event.currentTarget ) ,
                    type = $ ( $this ).is ( ':checked' ) ,
                    closest = $ ( $this ).closest ( 'div.fs_affiliates_modules_grid' ) ,
                    name = closest.find ( '.fs_affiliates_module_name' ).val ( ) ,
                    grid_inner = closest.find ( '.fs_affiliates_modules_grid_inner' ) ;
            var data = {
                action : 'fs_affiliates_toggle_modules' ,
                enabled : type ,
                module_name : name ,
                fs_security : fs_affiliates_settings_params.module_nonce
            } ;
            $.post ( ajaxurl , data , function ( res ) {

                if ( res.success === true ) {
                    if ( type ) {
                        closest.find ( '.fs_affiliates_settings_link' ).show ( ) ;
                        $img_url = closest.find ( 'img' ).attr ( 'src' ).replace ( '_inactive' , '_active' ) ;
                        grid_inner.removeClass ( 'fs_affiliates_' + name + '_inactive' ).addClass ( 'fs_affiliates_' + name + '_active' ) ;
                    } else {
                        $img_url = closest.find ( 'img' ).attr ( 'src' ).replace ( '_active' , '_inactive' ) ;
                        closest.find ( '.fs_affiliates_settings_link' ).hide ( ) ;
                        grid_inner.removeClass ( 'fs_affiliates_' + name + '_active' ).addClass ( 'fs_affiliates_' + name + '_inactive' ) ;
                    }

                    closest.find ( 'img' ).attr ( 'src' , $img_url ) ;
                } else {
                    window.alert ( res.data.error ) ;
                }

            } ) ;
        } , toggle_integrations_enabled : function ( event ) {
            event.preventDefault ( ) ;
            var $this = $ ( event.currentTarget ) ,
                    type = $ ( $this ).is ( ':checked' ) ,
                    closest = $ ( $this ).closest ( 'div.fs_affiliates_integrations_grid' ) ,
                    name = closest.find ( '.fs_affiliates_integration_name' ).val ( ) ,
                    grid_inner = closest.find ( '.fs_affiliates_integrations_grid_inner' ) ;
            var data = {
                action : 'fs_affiliates_toggle_integrations' ,
                enabled : type ,
                integration_name : name ,
                fs_security : fs_affiliates_settings_params.integration_nonce
            } ;
            $.post ( ajaxurl , data , function ( res ) {

                if ( res.success === true ) {
                    if ( type ) {
                        closest.find ( '.fs_affiliates_settings_link' ).show ( ) ;
                        grid_inner.removeClass ( 'fs_affiliates_integration_inactive' ).addClass ( 'fs_affiliates_integration_active' )
                    } else {
                        closest.find ( '.fs_affiliates_settings_link' ).hide ( ) ;
                        grid_inner.removeClass ( 'fs_affiliates_integration_active' ).addClass ( 'fs_affiliates_integration_inactive' ) ;
                    }

                } else {
                    window.alert ( res.data.error ) ;
                }

            } ) ;
        } ,
        toggle_notifications_enabled : function ( event ) {
            event.preventDefault ( ) ;
            var $this = $ ( event.currentTarget ) ,
                    type = $ ( $this ).is ( ':checked' ) ,
                    closest = $ ( $this ).closest ( 'div.fs_affiliates_notifications_grid' ) ,
                    name = closest.find ( '.fs_affiliates_notification_name' ).val ( ) ,
                    grid_inner = closest.find ( '.fs_affiliates_notifications_grid_inner' ) ;
            var data = {
                action : 'fs_affiliates_toggle_notifications' ,
                enabled : type ,
                notification_name : name ,
                fs_security : fs_affiliates_settings_params.notification_nonce
            } ;
            $.post ( ajaxurl , data , function ( res ) {

                if ( res.success === true ) {
                    if ( type ) {
                        closest.find ( '.fs_affiliates_settings_link' ).show ( ) ;
                        grid_inner.removeClass ( 'fs_affiliates_notification_inactive' ).addClass ( 'fs_affiliates_notification_active' )
                    } else {
                        closest.find ( '.fs_affiliates_settings_link' ).hide ( ) ;
                        grid_inner.removeClass ( 'fs_affiliates_notification_active' ).addClass ( 'fs_affiliates_notification_inactive' )
                    }

                } else {
                    window.alert ( res.data.error ) ;
                }

            } ) ;
        } ,
        get_registration_method : function ( $this ) {
            var method = $ ( $this ).val ( ) ;
            if ( method == 'advanced' ) {
                $ ( '#fs_affiliates_dashboard_page_id' ).closest ( 'tr' ).find('label').text(fs_affiliates_settings_params.advance_dashboard_label);
                $ ( '#fs_affiliates_dashboard_page_id' ).next ( 'p' ).text(fs_affiliates_settings_params.advance_dashboard_description);
                $ ( '.fs_affiliates_advanced_registration' ).closest ( 'tr' ).show ( ) ;
            } else {
                $ ( '#fs_affiliates_dashboard_page_id' ).closest ( 'tr' ).find('label').text(fs_affiliates_settings_params.basic_dashboard_label);
                $ ( '#fs_affiliates_dashboard_page_id' ).next ( 'p' ).text(fs_affiliates_settings_params.basic_dashboard_description);                
                $ ( '.fs_affiliates_advanced_registration' ).closest ( 'tr' ).hide ( ) ;
            }
        } , get_commission_type : function ( $this ) {
            var type = $ ( $this ).val ( ) ;
            if ( type == 'fixed' ) {
                $ ( '#fs_affiliates_percentage_commission_value' ).closest ( 'tr' ).hide ( ) ;
                $ ( '#fs_affiliates_fixed_commission_value' ).closest ( 'tr' ).show ( ) ;
            } else {
                $ ( '#fs_affiliates_percentage_commission_value' ).closest ( 'tr' ).show ( ) ;
                $ ( '#fs_affiliates_fixed_commission_value' ).closest ( 'tr' ).hide ( ) ;
            }
        } ,
        sortable_fields : function ( ) {
            var listtable = $ ( 'table.wp-list-table #the-list' ) ;
            listtable.sortable ( {
                items : 'tr' ,
                handle : '.fs_affiliates_fields_sort_handle' ,
                axis : 'y' ,
                containment : listtable ,
                update : function ( event , ui ) {
                    var sort_order = [ ] ;
                    listtable.find ( '.fs_affiliates_sortable' ).each ( function ( e ) {
                        sort_order.push ( $ ( this ).val ( ) ) ;
                    } ) ;
                    $.post ( ajaxurl , {
                        action : 'fs_affiliates_sort_fields' ,
                        sort_order : sort_order ,
                        fs_security : fs_affiliates_settings_params.field_sort_nonce
                    } ) ;
                }
            } ) ;
            //dashboard tabs
            var dashboard_table = $ ( 'table.fs_affiliates_dashboard_additional_tabs_table #fs_affiliates_list' )
            dashboard_table.sortable ( {
                items : 'tr' ,
                handle : '.fs_affiliates_tabs_sort_handle' ,
                axis : 'y' ,
                containment : dashboard_table ,
                update : function ( event , ui ) {
                    var sort_order = [ ] ;
                    dashboard_table.find ( '.fs_affiliates_sortable' ).each ( function ( e ) {
                        sort_order.push ( $ ( this ).val ( ) ) ;
                    } ) ;
                    $.post ( ajaxurl , {
                        action : 'fs_affiliates_sort_dashboard_tabs' ,
                        sort_order : sort_order ,
                        fs_security : fs_affiliates_settings_params.field_sort_nonce
                    } ) ;
                }
            } ) ;
        } , payment_gateways_sortable : function () {
            $ ( 'table #fs_affiliates_payment_settings_table' ).sortable ( {
                items : 'tr' ,
                handle : '.fs_affiliates_payments_sort_handle' ,
                axis : 'y' ,
                containment : jQuery ( 'table #fs_affiliates_payment_settings_table' ).closest ( 'table' ) ,
            } ) ;
        } , bulk_update_affiliates : function ( event ) {
            event.preventDefault () ;
            if ( ! confirm ( fs_affiliates_settings_params.bulk_update_confirm_message ) )
                return ;

            var $this = $ ( event.currentTarget ) ,
                    block = $ ( $this ).closest ( 'table' ) ;

            FS_Affiliates_Settings.block ( block ) ;

            var dataparam = ( {
                action : 'fs_affiliates_get_bulk_update_affiliate_ids' ,
                user_type : $ ( '#fs_affiliates_user_selection_type' ).val () ,
                selected_users : $ ( '#fs_affiliates_selected_users' ).val () ,
                fs_security : fs_affiliates_settings_params.bulk_update_nonce

            } ) ;

            $.post ( ajaxurl , dataparam ,
                    function ( response ) {
                        if ( response.success === true ) {
                            var i , j , temparray , chunk = 50 ;
                            for ( i = 0 , j = response.data.user_ids.length ; i < j ; i += chunk ) {
                                temparray = response.data.user_ids.slice ( i , i + chunk ) ;
                                FS_Affiliates_Settings.change_user_as_affiliate ( temparray ) ;
                            }

                            $.when ( FS_Affiliates_Settings.change_user_as_affiliate ( '' ) ).done ( function (  ) {
                                FS_Affiliates_Settings.unblock ( block ) ;
                            } ) ;
                        } else {
                            window.alert ( response.data.error ) ;
                            FS_Affiliates_Settings.unblock ( block ) ;
                        }
                    } , 'json' ) ;
        } , change_user_as_affiliate : function ( user_ids ) {
            return $.ajax ( {
                type : 'POST' ,
                url : ajaxurl ,
                data : ( {
                    action : 'fs_affiliates_change_user_as_affiliate' ,
                    user_ids : user_ids ,
                    fs_security : fs_affiliates_settings_params.bulk_update_nonce
                } ) ,
                success : function ( response ) {
                    if ( response.success !== true ) {
                        window.alert ( response.data.error ) ;
                        location.reload () ;
                    }
                } ,
                dataType : 'json' ,
                async : false
            } ) ;
        } , block : function ( id ) {
            $ ( id ).block ( {
                message : null ,
                overlayCSS : {
                    background : '#fff' ,
                    opacity : 0.6
                }
            } ) ;
        } ,
        unblock : function ( id ) {
            $ ( id ).unblock () ;
        } ,
    } ;
    FS_Affiliates_Settings.init ( ) ;
} ) ;