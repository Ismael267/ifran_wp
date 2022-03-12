/* global ajaxurl */

jQuery( function ( $ ) {

    var Creatives = {
        init : function () {
            this.trigger_on_page_load( ) ;
            $( document ).on( 'change' , '.fs_affiliates_allowed_affiliates' , this.toggleAffiliateSelector ) ;
        } , trigger_on_page_load : function ( ) {
            this.getAffiliateSelector( '.fs_affiliates_allowed_affiliates' ) ;
        } , toggleAffiliateSelector : function ( event ) {
            event.preventDefault( ) ;
            var $this = $( event.currentTarget ) ;
            Creatives.getAffiliateSelector( $this ) ;
        } , getAffiliateSelector : function ( $this ) {
            $( '.fs_affiliates_selection' ).closest( 'tr' ).hide() ;

            if ( '2' === $( $this ).val() ) {
                $( '.fs_affiliates_include_affiliate' ).closest( 'tr' ).show() ;
            } else if ( '3' === $( $this ).val() ) {
                $( '.fs_affiliates_exclude_affiliate' ).closest( 'tr' ).show() ;
            }
        } ,
    } ;

    Creatives.init() ;
} ) ;
