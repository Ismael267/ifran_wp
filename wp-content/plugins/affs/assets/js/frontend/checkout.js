/* global fs_affiliates_checkout_params */

jQuery ( function ( $ ) {

    var FS_Checkout = {
        init : function () {
            this.trigger_on_page_load () ;
            $ ( document ).on ( 'change' , 'input[type=radio][name=affiliate_referrer_radio]' , this.checkout_affiliate_affs_selection ) ;
        } ,
        trigger_on_page_load : function () {
            FS_Checkout.affs_checkout_on_load () ;
        } ,

        affs_checkout_on_load : function () {

            if ( fs_affiliates_checkout_params.affs_selection == 3 ) {
                FS_Checkout.affiliate_referrer_user_select('input[type=radio][name=affiliate_referrer_radio]') ;
            }

        } ,
        checkout_affiliate_affs_selection : function ( event ) {
            event.preventDefault ( ) ;
            var $this = $ ( event.currentTarget ) ;
            FS_Checkout.affiliate_referrer_user_select ( $this ) ;
        } ,

        affiliate_referrer_user_select : function ( $this ) {
            
            if ( $ ( $this ).val ( ) == 1 ) {
                $ ( '#affiliate_referrer_fields' ).show () ;
            } else {
                $ ( '#affiliate_referrer_fields' ).hide () ;
            }

        } ,

    } ;
    FS_Checkout.init () ;

} ) ;