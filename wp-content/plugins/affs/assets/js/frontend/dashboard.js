/* global fs_affiliates_dashboard_params */

jQuery( function ( $ ) {

    //File Upload
    if ( $( '.fs_affiliates_file_upload' ).length ) {
        $( '.fs_affiliates_file_upload' ).each( function ( e ) {

            var data = [ {
                    name : 'action' ,
                    value : 'fs_affiliates_file_upload' ,
                } ,
                {
                    name : 'key' ,
                    value : $( this ).attr( 'name' )
                } ] ;
            $( this ).fileupload( {
                url : fs_affiliates_dashboard_params.ajax_url ,
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

                        $( this ).closest( 'div' ).find( '.fs_affiliates_display_file_names' ).append( html ) ;
                    } else {
                        $( '.fs_affiliates_display_file_names' ).html( '<span class="fs_affiliates_error_msg_for_upload" style="color:red;">' + data.result.data.content + '</span>' ) ;
                        $( '.fs_affiliates_error_msg_for_upload' ).delay( 3000 ).fadeOut( ) ;
                    }
                }
            } ) ;
        } ) ;
    }

    var FS_Dashboard = {
        init : function () {
            this.trigger_on_page_load() ;
            
            $( document ).on( 'change' , '#aff_change_slug' , this.modifiy_slug ) ;
            $( document ).on( 'change' , '#fs_affiliates_payment_method' , this.payment_method_change ) ;
            $( document ).on( 'click' , '#fs_affiliates_form_save' , this.affiliates_form_save ) ;
            $( document ).on( 'click' , '#fs_affiliates_form_send_mail' , this.affiliates_form_send_mail ) ;
            $( document ).on( 'click' , '.fs_affiliates_delete_uploaded_file' , this.remove_uploaded_file ) ;
            $( document ).on( 'click' , '.fs_affiliates_delete_table_uploaded_file' , this.remove_table_uploaded_file ) ;
            $( document ).on( 'click' , '.fs_request_unpaid_commission' , this.get_unpaid_commissions ) ;
            $( document ).on( 'focusout', 'input[name=fs_new_campaign]', this.validate_campaign_name);
        } , trigger_on_page_load : function () {
            FS_Dashboard.aff_payment_method_common( '#fs_affiliates_payment_method' ) ;
            this.show_or_hide_for_modifiy_slug() ;

        } , payment_method_change : function ( event ) {
            event.preventDefault() ;
            var $this = $( event.currentTarget ) ;
            FS_Dashboard.aff_payment_method_common( $this ) ;
        } , aff_payment_method_common : function ( $this ) {
            $( '.fs_affiliates_validation_error' ).hide() ;
            $( '.affiliate-pay' ).hide() ;
            var selected_val = $( $this ).val() ;
            if ( selected_val == 'paypal' ) {
                $( '.affiliate-paypal-pay' ).show() ;
            } else if ( selected_val == 'direct' ) {
                $( '.affiliate-direct-pay' ).show() ;
            } else if ( selected_val == 'wallet' ) {
                $( '.affiliate-wallet-pay' ).show() ;
            }
        } , remove_uploaded_file : function ( event ) {
            event.preventDefault() ;
            var $this = $( event.currentTarget ) ;

            var data = {
                action : 'fs_affiliates_remove_uploaded_file' ,
                key : $( $this ).closest( 'div' ).find( '.fs_affiliates_uploaded_file_key' ).val() ,
                file_name : $( $this ).find( '.fs_affiliates_remove_file' ).val() ,
            } ;
            $.post( fs_affiliates_dashboard_params.ajax_url , data , function ( response ) {
                if ( true === response.success ) {
                    $( $this ).closest( 'p.fs_affiliates_uploaded_file_name' ).remove() ;
                } else {
                    window.alert( response.data.error ) ;
                }
            } ) ;
        } ,
        remove_table_uploaded_file : function ( event ) {
            event.preventDefault() ;
            var $this = $( event.currentTarget ) ;

            var data = {
                action : 'fs_affiliates_remove_uploaded_file' ,
                key : $( $this ).find( '.fs_affiliates_uploaded_file_key' ).val() ,
                file_name : $( $this ).find( '.fs_affiliates_remove_file' ).val() ,
            } ;
            $.post( fs_affiliates_dashboard_params.ajax_url , data , function ( response ) {
                if ( true === response.success ) {
                    $( $this ).closest( 'tr' ).remove() ;
                } else {
                    window.alert( response.data.error ) ;
                }
            } ) ;
        } , affiliates_form_save : function ( event ) {
            var affilate_id = $( '#fs_affiliates_current_id' ).val() ;
            var pay_method = $( '#fs_affiliates_payment_method' ).val() ;
            var paypal_email = $( '#fs_affiliates_paypal_email' ).val() ;
            var paypal_bank = $( '#fs_affiliates_bank_details' ).val() ;
            var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/ ;

            $( '.fs_affiliates_validation_error' ).hide() ;

            if ( pay_method == 'direct' ) {
                if ( paypal_bank === '' ) {
                    $( '.fs_affiliates_detail_validate' ).show() ;
                    return false ;
                }
            } else if ( pay_method == 'paypal' ) {
                if ( paypal_email == '' || ! emailReg.test( paypal_email ) ) {
                    $( '.fs_affiliates_email_validate' ).show() ;
                    return false ;
                }
            }

            var data = {
                action : "fs_affiliates_pay_method_change" ,
                fs_affiliate_current_id : affilate_id ,
                fs_affiliates_payment_method : pay_method ,
                fs_affiliates_paypal_email : paypal_email ,
                fs_affiliates_bank_details : paypal_bank ,
                fs_security : fs_affiliates_dashboard_params.pay_save_nonce ,

            }
            jQuery.post( fs_affiliates_dashboard_params.ajax_url , data ,
                    function ( response ) {
                        if ( true === response.success ) {
                            if ( response.data.content == 'success' ) {
                                $( '#fs_affiliates_msg_success' ).remove() ;
                                $( '#fs_affiliates_pay_msg_wraper' ).append( '<span id="fs_affiliates_msg_success" class="fs_affiliates_msg_success"><i class="fa fa-check"></i>Success!!!</span>' ) ;
                                $( '#fs_affiliates_pay_msg_wraper' ).show() ;
                            }
                        } else {
                            window.alert( response.data.error ) ;
                        }
                    }
            ) ;
        } ,
        modifiy_slug : function () {
            FS_Dashboard.show_or_hide_for_modifiy_slug() ;
        } ,
        show_or_hide_for_modifiy_slug : function () {
            if ( jQuery( '#aff_change_slug' ).is( ':checked' ) == true ) {
                jQuery( '#aff_new_slug' ).closest( 'p' ).show() ;
            } else {
                jQuery( '#aff_new_slug' ).closest( 'p' ).hide() ;
            }
        } , affiliates_form_send_mail : function ( event ) {

            var refer_mails = $( '#fs_affiliates_refer_mails' ).val() ;
            var refer_mail_subject = $( '#fs_affiliates_refer_mail_subject' ).val() ;
            var refer_mail_content = $( '#fs_affiliates_refer_mail_content' ).val() ;
            var hidden_id = $( '#fs_affiliates_hidden_id' ).val() ;

            var splited = refer_mails.split( '|' ) ;
            var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/ ;

            $( '.fs_affiliates_msg_success' ).hide() ;

            $( '.fs_affiliates_msg_fails' ).hide() ;

            $( '.fs_affiliates_refer_friend_validation_error' ).hide() ;

            if ( refer_mails == '' ) {
                $( '.fs_affiliates_refer_mail_validate' ).show() ;
                return false ;
            } else {
                $.each( splited , function ( key , value ) {
                    if ( ! emailReg.test( value ) ) {
                        $( '.fs_affiliates_refer_mail_validate' ).show() ;
                        return false ;
                    }
                } ) ;
            }


            if ( refer_mail_subject === '' ) {
                $( '.fs_affiliates_refer_subject_validate' ).show() ;
                return false ;
            }

            if ( refer_mail_content === '' ) {
                $( '.fs_affiliates_refer_content_validate' ).show() ;
                return false ;
            }



            var data = {
                action : "fs_affiliates_referafriend_mails" ,
                refer_mails : refer_mails ,
                refer_mail_subject : refer_mail_subject ,
                refer_mail_content : refer_mail_content ,
                hidden_id : hidden_id ,
                fs_security : fs_affiliates_dashboard_params.pay_save_nonce ,

            }
            jQuery.post( fs_affiliates_dashboard_params.ajax_url , data ,
                    function ( response ) {
                        var trimmed_response = Number( jQuery.trim( response ) ) ;
                        $( '.fs_affiliates_refer_friend_validation_error' ).hide() ;
                        if ( trimmed_response > 0 ) {
                            $( '.fs_affiliates_refer_mail_success' ).show() ;
                        } else {
                            $( '.fs_affiliates_refer_mail_fails' ).show() ;
                        }
                    }
            ) ;
        } , get_unpaid_commissions : function () {
            if ( confirm( fs_affiliates_dashboard_params.request_submit_confirm ) ) {
                var data = {
                    action : "fsunpaidcommission" ,
                    affiliateid : $( this ).attr( 'data-affiliateid' ) ,
                    fs_security : fs_affiliates_dashboard_params.unpaid_commission ,

                }
                $.post( fs_affiliates_dashboard_params.ajax_url , data , function ( response ) {
                    if ( true === response.success ) {
                        $( "<div><span class='fs_affiliates_msg_success'><i class='fa fa-check'> " + response.data.content + " !!!</i></span></div>" ).insertBefore( '.fs_affiliates_form' ) ;
                    } else {
                        $( "<div><span class='fs_affiliates_msg_fails_post'><i class='fa fa-exclamation-triangle'> " + response.data.error + "</i></span></div>" ).insertBefore( '.fs_affiliates_form' ) ;
                    }
                } ) ;
            }
            return false ;
        } ,
        
        validate_campaign_name: function (event) {
            event.preventDefault();
            var $this = $(event.currentTarget);
            
            if ($this.val().indexOf(" ") !== -1){
                var $without_whitespace = $this.val().replace(/\s/g,'');
                $this.val($without_whitespace);
            }
        },
    } ;
    FS_Dashboard.init() ;

    if ( $( '.fs_affiliates_frontend_dashboard' ).length ) {

        $( 'ul.submenu' ).hide() ;
        $( 'ul > li, ul.submenu > li' ).hover( function () {
            if ( $( '> ul.submenu' , this ).length > 0 ) {
                $( '> ul.submenu' , this ).stop().slideDown( 'slow' ) ;
            }
        } , function () {
            if ( $( '> ul.submenu' , this ).length > 0 ) {
                $( '> ul.submenu' , this ).stop().slideUp( 'slow' ) ;
            }
        } ) ;
    }

} ) ;