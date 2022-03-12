/* global fs_affiliates_params */

jQuery(function ($) {

    var FS_Affiliates = {
        init: function () {
            this.trigger_on_page_load();

            $( document ).on( 'focusout' , '.fs_affiliates_user_name' , this.validate_user_name ) ;
            $( document ).on( 'focusout' , '.fs_affiliates_user_email' , this.validate_user_email ) ;
            $( document ).on( 'change' , '.user_selection_type' , this.toggle_user_selection_type ) ;
            $( document ).on( 'change' , '.fs_affiliates_commission_type' , this.toggle_commission_type ) ;
            $( document ).on( 'click' , '#fs_add_product_rates' , this.append_rule_for_product_rate ) ;
            $( document ).on( 'click' , '.fs_remove_product_rates' , this.fs_remove_rule_for_product_rate ) ;
            $( document ).on( 'change' , '.fs_referral_status' , this.toggle_referral_status ) ;
            $( document ).on( 'click' , '.fs_referral_reject' , this.prompt_referral_reject_reason ) ;

        }, trigger_on_page_load: function () {
            this.get_commission_type('.fs_affiliates_commission_type');
            this.get_user_selection_type('.user_selection_type');
            this.referral_status('.fs_referral_status');
        }, toggle_commission_type: function (event) {
            event.preventDefault();
            var $this = $(event.currentTarget);
            FS_Affiliates.get_commission_type($this);
        }, toggle_user_selection_type: function (event) {
            event.preventDefault();
            var $this = $(event.currentTarget);
            FS_Affiliates.get_user_selection_type($this);
        }, toggle_referral_status: function (event) {
            event.preventDefault();
            var $this = $(event.currentTarget);
            FS_Affiliates.referral_status($this);
        }, get_commission_type: function ($this) {
            var type = $($this).val();
            if (type == 'default') {
                $('.fs_affiliates_commission_value').closest('tr').hide();
            } else {
                $('.fs_affiliates_commission_value').closest('tr').show();
            }
        }, get_user_selection_type: function ($this) {
            var type = $($this).val();
            if (type == 'new') {
                $('.new_user_selection').closest('tr').show();
                $('.existing_user_selection').closest('tr').hide();
            } else {
                $('.new_user_selection').closest('tr').hide();
                $('.existing_user_selection').closest('tr').show();
            }
        }, referral_status: function ($this) {
            var type = $($this).val();
            if ( 'fs_rejected' == type ) {
                $('.fs_referral_rejected_reason').closest('tr').show();
            } else {
                $('.fs_referral_rejected_reason').closest('tr').hide();
            }
        }, prompt_referral_reject_reason: function (event) {
            event.preventDefault();
            var $this  = $(event.currentTarget);
            var reason = prompt(fs_affiliates_params.referral_reject_reason_label);

            var data = {
                action: 'fs_referral_rejected_reason',
                reason: reason,
                referral_id: $this.data('referral_id'),
                fs_security: fs_affiliates_params.referral_rejected_nonce
            };
            $.post(ajaxurl, data, function (response) {
                if (true === response.success) {
                    window.location = $this.attr('href');
                } else {
                    window.alert(response.data.error);
                }
                FS_Affiliates.unblock($($this).closest('td'));
            });
        }, validate_user_name: function (event) {
            event.preventDefault();
            var $this = $(event.currentTarget);
            var $tr = $($this).closest('tr');
            $($tr).find('.fs_affiliates_notice').remove();
            if ($('.user_selection_type').val() == 'existing') { //validate only new user
                return false;
            }

            var html = '<div class="fs_affiliates_notice">%s</div>';
            if ($($this).val() == '') {
                $($this).after(html.replace(/%s/g, fs_affiliates_params.username_validation_msg));
                return false;
            }

            FS_Affiliates.block($($this).closest('td'));

            var data = {
                action: 'fs_affiliates_username_validation',
                name: $($this).val(),
                fs_security: fs_affiliates_params.username_nonce
            };
            $.post(ajaxurl, data, function (response) {
                if (true === response.success) {
                    $($this).after(html.replace(/%s/g, response.data.content));
                } else {
                    window.alert(response.data.error);
                }
                FS_Affiliates.unblock($($this).closest('td'));
            });
            return true;
        }, validate_user_email: function (event) {
            event.preventDefault();
            var $this = $(event.currentTarget);
            var $tr = $($this).closest('tr');
            $($tr).find('.fs_affiliates_notice').remove();
            if ($('.user_selection_type').val() == 'existing') { //validate only new user
                return false;
            }

            var html = '<div class="fs_affiliates_notice">%s</div>';
            if ($($this).val() == '') {
                $($this).after(html.replace(/%s/g, fs_affiliates_params.useremail_validation_msg));
                return false;
            }

            FS_Affiliates.block($($this).closest('td'));

            var data = {
                action: 'fs_affiliates_useremail_validation',
                email: $($this).val(),
                fs_security: fs_affiliates_params.useremail_nonce
            };
            $.post(ajaxurl, data, function (response) {
                if (true === response.success) {
                    $($this).after(html.replace(/%s/g, response.data.content));
                } else {
                    window.alert(response.data.error);
                }
                FS_Affiliates.unblock( $( $this ).closest( 'td' ) ) ;
            } ) ;
            return true ;
        } , append_rule_for_product_rate : function ( event ) {
            event.preventDefault() ;
            FS_Affiliates.block( '.fs_affiliates_block' ) ;
            var count = Math.round( new Date().getTime() + ( Math.random() * 100 ) ) ;
            var data = {
                action : 'fs_add_rule_for_product_rate' ,
                count : count ,
                fs_security : fs_affiliates_params.product_rate
            } ;
            $.post( ajaxurl , data , function ( response ) {
                if ( true === response.success ) {
                    $( '.fs_append_rule_for_product_rate' ).after( response.data.content ) ;
                    FS_Affiliates.unblock( '.fs_affiliates_block' ) ;
                    jQuery( 'body' ).trigger( 'fs-affiliates-select-init' ) ;
                } else {
                    window.alert( response.data.error ) ;
                    FS_Affiliates.unblock( '.fs_affiliates_block' ) ;
                }
            } ) ;
        } , fs_remove_rule_for_product_rate : function ( event ) {
            event.preventDefault() ;
            var $this = $( event.currentTarget ) ;
            $( $this ).parent().parent().remove() ;
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
            $( id ).unblock() ;
        } ,
    } ;
    FS_Affiliates.init() ;
} ) ;