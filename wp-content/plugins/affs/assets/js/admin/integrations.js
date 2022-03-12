jQuery( function ( $ ) {

    var FS_Affiliates_Integrations = {
        init : function () {

            this.trigger_on_page_load() ;

            $( document ).on( 'change' , '.fs_affiliates_cf_referrals_enable' , this.cf_referrals_enable_global ) ;
            $( document ).on( 'change' , '#wpforms-panel-field-settings-fs_affiliates_wp_referrals_enable' , this.wp_referrals_enable_global ) ;
            $( document ).on( 'change' , '#fs_affiliates_wc_subscriptions_awarded_commission' , this.toggle_award_commission ) ;
            $( document ).on( 'change' , '#fs_affiliates_sumo_subscriptions_awarded_commission' , this.toggle_award_commission ) ;
            $( document ).on( 'change' , '#fs_affiliates_sumo_memberships_reset_commision' , this.reset_memberships_commission ) ;
            
            

        } , trigger_on_page_load : function () {
            FS_Affiliates_Integrations.cf_referrals_enable( '.fs_affiliates_cf_referrals_enable' ) ;
            FS_Affiliates_Integrations.wp_referrals_enable( '#wpforms-panel-field-settings-fs_affiliates_wp_referrals_enable' ) ;
            FS_Affiliates_Integrations.reset_memberships_commission_action( '#fs_affiliates_sumo_memberships_reset_commision' ) ;

            $( '#fs_affiliates_wc_subscriptions_awarded_commission_for_fixed_renewals' ).closest( 'tr' ).hide() ;
            $( '#fs_affiliates_sumo_subscriptions_awarded_commission_for_fixed_renewals' ).closest( 'tr' ).hide() ;

            if ( '2' === $( '#fs_affiliates_wc_subscriptions_awarded_commission' ).val() ) {
                $( '#fs_affiliates_wc_subscriptions_awarded_commission_for_fixed_renewals' ).closest( 'tr' ).show() ;
            }

            if ( '2' === $( '#fs_affiliates_sumo_subscriptions_awarded_commission' ).val() ) {
                $( '#fs_affiliates_sumo_subscriptions_awarded_commission_for_fixed_renewals' ).closest( 'tr' ).show() ;
            }

        } , cf_referrals_enable_global : function( event ) {
            event.preventDefault() ;
            var $this = $( event.currentTarget ) ;
            FS_Affiliates_Integrations.cf_referrals_enable( $this ) ;
        } , wp_referrals_enable_global : function ( event ) {
            event.preventDefault() ;
            var $this = $( event.currentTarget ) ;
            FS_Affiliates_Integrations.wp_referrals_enable( $this ) ;
        } , reset_memberships_commission : function ( event ) {
            event.preventDefault() ;
            var $this = $( event.currentTarget ) ;
            FS_Affiliates_Integrations.reset_memberships_commission_action( $this ) ;
        } , reset_memberships_commission_action : function ( $this ) {
            if ( $( $this ).prop( 'checked' ) == true ) {
                $( '#fs_affiliates_sumo_memberships_reset_commision_rate' ).closest ('tr').show();
            } else {
                $( '#fs_affiliates_sumo_memberships_reset_commision_rate' ).closest ('tr').hide() ;
            }
        } , cf_referrals_enable : function ( $this ) {
            if ( $( $this ).prop( 'checked' ) == true ) {
                $( '.fs_affiliates_cf_referrals_content' ).show() ;
            } else {
                $( '.fs_affiliates_cf_referrals_content' ).hide() ;
            }
        } , wp_referrals_enable : function ( $this ) {
            if ( $( $this ).prop( 'checked' ) == true ) {
                $( '.fs_affiliates_wp_referrals_content' ).show() ;
            } else {
                $( '.fs_affiliates_wp_referrals_content' ).hide() ;
            }
        } ,
        toggle_award_commission : function() {
            $( '#fs_affiliates_wc_subscriptions_awarded_commission_for_fixed_renewals' ).closest( 'tr' ).hide() ;
            $( '#fs_affiliates_sumo_subscriptions_awarded_commission_for_fixed_renewals' ).closest( 'tr' ).hide() ;

            if ( '2' === $( this ).val() ) {
                $( '#fs_affiliates_wc_subscriptions_awarded_commission_for_fixed_renewals' ).closest( 'tr' ).show() ;
                $( '#fs_affiliates_sumo_subscriptions_awarded_commission_for_fixed_renewals' ).closest( 'tr' ).show() ;
            }
        } ,
    } ;
    FS_Affiliates_Integrations.init() ;
} ) ;