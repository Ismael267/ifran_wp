jQuery( function ( $ ) {
    // Field validation error tips
    $( document.body )

            .on( 'fs_affiliates_add_error_tip' , function ( e , element , error_type ) {
                var offset = element.position() ;

                if ( element.parent().find( '.fs_affiliates_error_tip' ).length === 0 ) {
                    element.after( '<div class="fs_affiliates_error_tip ' + error_type + '">' + fs_affiliates_admin_params[error_type] + '</div>' ) ;
                    element.parent().find( '.fs_affiliates_error_tip' )
                            .css( 'left' , offset.left + element.width() - ( element.width() / 2 ) - ( $( '.fs_affiliates_error_tip' ).width() / 2 ) )
                            .css( 'top' , offset.top + element.height() )
                            .fadeIn( '200' ) ;
                }
            } )

            .on( 'fs_affiliates_remove_error_tip' , function ( e , element , error_type ) {
                element.parent().find( '.fs_affiliates_error_tip.' + error_type ).fadeOut( '100' , function () {
                    $( this ).remove() ;
                } ) ;
            } )

            .on( 'click' , function () {
                $( '.fs_affiliates_error_tip' ).fadeOut( '100' , function () {
                    $( this ).remove() ;
                } ) ;
            } )

            .on( 'keyup' , '.fs_affiliates_input_price[type=text]' , function () {
                var regex , error ;

                regex = new RegExp( '[^\-0-9\%\\' + fs_affiliates_admin_params.mon_decimal_point + ']+' , 'gi' ) ;
                error = 'non_decimal_error' ;

                var value = $( this ).val() ;
                var newvalue = value.replace( regex , '' ) ;

                if ( value !== newvalue ) {
                    $( document.body ).triggerHandler( 'fs_affiliates_add_error_tip' , [ $( this ) , error ] ) ;
                } else {
                    $( document.body ).triggerHandler( 'fs_affiliates_remove_error_tip' , [ $( this ) , error ] ) ;
                }
            } )

            .on( 'change' , '.fs_affiliates_input_price[type=text]' , function () {
                var regex ;

                if ( $( this ).is( '.fs_affiliates_input_price' ) ) {
                    regex = new RegExp( '[^\-0-9\%\\' + fs_affiliates_admin_params.mon_decimal_point + ']+' , 'gi' ) ;
                }

                var value = $( this ).val() ;
                var newvalue = value.replace( regex , '' ) ;

                if ( value !== newvalue ) {
                    $( this ).val( newvalue ) ;
                }
            } )

} ) ;