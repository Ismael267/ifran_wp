<?php

/*
 * Default functions
 */

if ( ! defined( 'ABSPATH' ) )
    exit ; // Exit if accessed directly.

if ( ! function_exists( 'get_fs_affiliates_currencies' ) ) {

    /**
     * Get full list of currency codes.
     */
    function get_fs_affiliates_currencies() {
        static $currencies ;

        if ( ! isset( $currencies ) ) {
            $currencies = array_unique(
                    apply_filters(
                            'fs_affilates_currencies' , array(
                'AED' => __( 'United Arab Emirates dirham' , FS_AFFILIATES_LOCALE ) ,
                'AFN' => __( 'Afghan afghani' , FS_AFFILIATES_LOCALE ) ,
                'ALL' => __( 'Albanian lek' , FS_AFFILIATES_LOCALE ) ,
                'AMD' => __( 'Armenian dram' , FS_AFFILIATES_LOCALE ) ,
                'ANG' => __( 'Netherlands Antillean guilder' , FS_AFFILIATES_LOCALE ) ,
                'AOA' => __( 'Angolan kwanza' , FS_AFFILIATES_LOCALE ) ,
                'ARS' => __( 'Argentine peso' , FS_AFFILIATES_LOCALE ) ,
                'AUD' => __( 'Australian dollar' , FS_AFFILIATES_LOCALE ) ,
                'AWG' => __( 'Aruban florin' , FS_AFFILIATES_LOCALE ) ,
                'AZN' => __( 'Azerbaijani manat' , FS_AFFILIATES_LOCALE ) ,
                'BAM' => __( 'Bosnia and Herzegovina convertible mark' , FS_AFFILIATES_LOCALE ) ,
                'BBD' => __( 'Barbadian dollar' , FS_AFFILIATES_LOCALE ) ,
                'BDT' => __( 'Bangladeshi taka' , FS_AFFILIATES_LOCALE ) ,
                'BGN' => __( 'Bulgarian lev' , FS_AFFILIATES_LOCALE ) ,
                'BHD' => __( 'Bahraini dinar' , FS_AFFILIATES_LOCALE ) ,
                'BIF' => __( 'Burundian franc' , FS_AFFILIATES_LOCALE ) ,
                'BMD' => __( 'Bermudian dollar' , FS_AFFILIATES_LOCALE ) ,
                'BND' => __( 'Brunei dollar' , FS_AFFILIATES_LOCALE ) ,
                'BOB' => __( 'Bolivian boliviano' , FS_AFFILIATES_LOCALE ) ,
                'BRL' => __( 'Brazilian real' , FS_AFFILIATES_LOCALE ) ,
                'BSD' => __( 'Bahamian dollar' , FS_AFFILIATES_LOCALE ) ,
                'BTC' => __( 'Bitcoin' , FS_AFFILIATES_LOCALE ) ,
                'BTN' => __( 'Bhutanese ngultrum' , FS_AFFILIATES_LOCALE ) ,
                'BWP' => __( 'Botswana pula' , FS_AFFILIATES_LOCALE ) ,
                'BYR' => __( 'Belarusian ruble (old)' , FS_AFFILIATES_LOCALE ) ,
                'BYN' => __( 'Belarusian ruble' , FS_AFFILIATES_LOCALE ) ,
                'BZD' => __( 'Belize dollar' , FS_AFFILIATES_LOCALE ) ,
                'CAD' => __( 'Canadian dollar' , FS_AFFILIATES_LOCALE ) ,
                'CDF' => __( 'Congolese franc' , FS_AFFILIATES_LOCALE ) ,
                'CHF' => __( 'Swiss franc' , FS_AFFILIATES_LOCALE ) ,
                'CLP' => __( 'Chilean peso' , FS_AFFILIATES_LOCALE ) ,
                'CNY' => __( 'Chinese yuan' , FS_AFFILIATES_LOCALE ) ,
                'COP' => __( 'Colombian peso' , FS_AFFILIATES_LOCALE ) ,
                'CRC' => __( 'Costa Rican col&oacute;n' , FS_AFFILIATES_LOCALE ) ,
                'CUC' => __( 'Cuban convertible peso' , FS_AFFILIATES_LOCALE ) ,
                'CUP' => __( 'Cuban peso' , FS_AFFILIATES_LOCALE ) ,
                'CVE' => __( 'Cape Verdean escudo' , FS_AFFILIATES_LOCALE ) ,
                'CZK' => __( 'Czech koruna' , FS_AFFILIATES_LOCALE ) ,
                'DJF' => __( 'Djiboutian franc' , FS_AFFILIATES_LOCALE ) ,
                'DKK' => __( 'Danish krone' , FS_AFFILIATES_LOCALE ) ,
                'DOP' => __( 'Dominican peso' , FS_AFFILIATES_LOCALE ) ,
                'DZD' => __( 'Algerian dinar' , FS_AFFILIATES_LOCALE ) ,
                'EGP' => __( 'Egyptian pound' , FS_AFFILIATES_LOCALE ) ,
                'ERN' => __( 'Eritrean nakfa' , FS_AFFILIATES_LOCALE ) ,
                'ETB' => __( 'Ethiopian birr' , FS_AFFILIATES_LOCALE ) ,
                'EUR' => __( 'Euro' , FS_AFFILIATES_LOCALE ) ,
                'FJD' => __( 'Fijian dollar' , FS_AFFILIATES_LOCALE ) ,
                'FKP' => __( 'Falkland Islands pound' , FS_AFFILIATES_LOCALE ) ,
                'GBP' => __( 'Pound sterling' , FS_AFFILIATES_LOCALE ) ,
                'GEL' => __( 'Georgian lari' , FS_AFFILIATES_LOCALE ) ,
                'GGP' => __( 'Guernsey pound' , FS_AFFILIATES_LOCALE ) ,
                'GHS' => __( 'Ghana cedi' , FS_AFFILIATES_LOCALE ) ,
                'GIP' => __( 'Gibraltar pound' , FS_AFFILIATES_LOCALE ) ,
                'GMD' => __( 'Gambian dalasi' , FS_AFFILIATES_LOCALE ) ,
                'GNF' => __( 'Guinean franc' , FS_AFFILIATES_LOCALE ) ,
                'GTQ' => __( 'Guatemalan quetzal' , FS_AFFILIATES_LOCALE ) ,
                'GYD' => __( 'Guyanese dollar' , FS_AFFILIATES_LOCALE ) ,
                'HKD' => __( 'Hong Kong dollar' , FS_AFFILIATES_LOCALE ) ,
                'HNL' => __( 'Honduran lempira' , FS_AFFILIATES_LOCALE ) ,
                'HRK' => __( 'Croatian kuna' , FS_AFFILIATES_LOCALE ) ,
                'HTG' => __( 'Haitian gourde' , FS_AFFILIATES_LOCALE ) ,
                'HUF' => __( 'Hungarian forint' , FS_AFFILIATES_LOCALE ) ,
                'IDR' => __( 'Indonesian rupiah' , FS_AFFILIATES_LOCALE ) ,
                'ILS' => __( 'Israeli new shekel' , FS_AFFILIATES_LOCALE ) ,
                'IMP' => __( 'Manx pound' , FS_AFFILIATES_LOCALE ) ,
                'INR' => __( 'Indian rupee' , FS_AFFILIATES_LOCALE ) ,
                'IQD' => __( 'Iraqi dinar' , FS_AFFILIATES_LOCALE ) ,
                'IRR' => __( 'Iranian rial' , FS_AFFILIATES_LOCALE ) ,
                'IRT' => __( 'Iranian toman' , FS_AFFILIATES_LOCALE ) ,
                'ISK' => __( 'Icelandic kr&oacute;na' , FS_AFFILIATES_LOCALE ) ,
                'JEP' => __( 'Jersey pound' , FS_AFFILIATES_LOCALE ) ,
                'JMD' => __( 'Jamaican dollar' , FS_AFFILIATES_LOCALE ) ,
                'JOD' => __( 'Jordanian dinar' , FS_AFFILIATES_LOCALE ) ,
                'JPY' => __( 'Japanese yen' , FS_AFFILIATES_LOCALE ) ,
                'KES' => __( 'Kenyan shilling' , FS_AFFILIATES_LOCALE ) ,
                'KGS' => __( 'Kyrgyzstani som' , FS_AFFILIATES_LOCALE ) ,
                'KHR' => __( 'Cambodian riel' , FS_AFFILIATES_LOCALE ) ,
                'KMF' => __( 'Comorian franc' , FS_AFFILIATES_LOCALE ) ,
                'KPW' => __( 'North Korean won' , FS_AFFILIATES_LOCALE ) ,
                'KRW' => __( 'South Korean won' , FS_AFFILIATES_LOCALE ) ,
                'KWD' => __( 'Kuwaiti dinar' , FS_AFFILIATES_LOCALE ) ,
                'KYD' => __( 'Cayman Islands dollar' , FS_AFFILIATES_LOCALE ) ,
                'KZT' => __( 'Kazakhstani tenge' , FS_AFFILIATES_LOCALE ) ,
                'LAK' => __( 'Lao kip' , FS_AFFILIATES_LOCALE ) ,
                'LBP' => __( 'Lebanese pound' , FS_AFFILIATES_LOCALE ) ,
                'LKR' => __( 'Sri Lankan rupee' , FS_AFFILIATES_LOCALE ) ,
                'LRD' => __( 'Liberian dollar' , FS_AFFILIATES_LOCALE ) ,
                'LSL' => __( 'Lesotho loti' , FS_AFFILIATES_LOCALE ) ,
                'LYD' => __( 'Libyan dinar' , FS_AFFILIATES_LOCALE ) ,
                'MAD' => __( 'Moroccan dirham' , FS_AFFILIATES_LOCALE ) ,
                'MDL' => __( 'Moldovan leu' , FS_AFFILIATES_LOCALE ) ,
                'MGA' => __( 'Malagasy ariary' , FS_AFFILIATES_LOCALE ) ,
                'MKD' => __( 'Macedonian denar' , FS_AFFILIATES_LOCALE ) ,
                'MMK' => __( 'Burmese kyat' , FS_AFFILIATES_LOCALE ) ,
                'MNT' => __( 'Mongolian t&ouml;gr&ouml;g' , FS_AFFILIATES_LOCALE ) ,
                'MOP' => __( 'Macanese pataca' , FS_AFFILIATES_LOCALE ) ,
                'MRO' => __( 'Mauritanian ouguiya' , FS_AFFILIATES_LOCALE ) ,
                'MUR' => __( 'Mauritian rupee' , FS_AFFILIATES_LOCALE ) ,
                'MVR' => __( 'Maldivian rufiyaa' , FS_AFFILIATES_LOCALE ) ,
                'MWK' => __( 'Malawian kwacha' , FS_AFFILIATES_LOCALE ) ,
                'MXN' => __( 'Mexican peso' , FS_AFFILIATES_LOCALE ) ,
                'MYR' => __( 'Malaysian ringgit' , FS_AFFILIATES_LOCALE ) ,
                'MZN' => __( 'Mozambican metical' , FS_AFFILIATES_LOCALE ) ,
                'NAD' => __( 'Namibian dollar' , FS_AFFILIATES_LOCALE ) ,
                'NGN' => __( 'Nigerian naira' , FS_AFFILIATES_LOCALE ) ,
                'NIO' => __( 'Nicaraguan c&oacute;rdoba' , FS_AFFILIATES_LOCALE ) ,
                'NOK' => __( 'Norwegian krone' , FS_AFFILIATES_LOCALE ) ,
                'NPR' => __( 'Nepalese rupee' , FS_AFFILIATES_LOCALE ) ,
                'NZD' => __( 'New Zealand dollar' , FS_AFFILIATES_LOCALE ) ,
                'OMR' => __( 'Omani rial' , FS_AFFILIATES_LOCALE ) ,
                'PAB' => __( 'Panamanian balboa' , FS_AFFILIATES_LOCALE ) ,
                'PEN' => __( 'Peruvian nuevo sol' , FS_AFFILIATES_LOCALE ) ,
                'PGK' => __( 'Papua New Guinean kina' , FS_AFFILIATES_LOCALE ) ,
                'PHP' => __( 'Philippine peso' , FS_AFFILIATES_LOCALE ) ,
                'PKR' => __( 'Pakistani rupee' , FS_AFFILIATES_LOCALE ) ,
                'PLN' => __( 'Polish z&#x142;oty' , FS_AFFILIATES_LOCALE ) ,
                'PRB' => __( 'Transnistrian ruble' , FS_AFFILIATES_LOCALE ) ,
                'PYG' => __( 'Paraguayan guaran&iacute;' , FS_AFFILIATES_LOCALE ) ,
                'QAR' => __( 'Qatari riyal' , FS_AFFILIATES_LOCALE ) ,
                'RON' => __( 'Romanian leu' , FS_AFFILIATES_LOCALE ) ,
                'RSD' => __( 'Serbian dinar' , FS_AFFILIATES_LOCALE ) ,
                'RUB' => __( 'Russian ruble' , FS_AFFILIATES_LOCALE ) ,
                'RWF' => __( 'Rwandan franc' , FS_AFFILIATES_LOCALE ) ,
                'SAR' => __( 'Saudi riyal' , FS_AFFILIATES_LOCALE ) ,
                'SBD' => __( 'Solomon Islands dollar' , FS_AFFILIATES_LOCALE ) ,
                'SCR' => __( 'Seychellois rupee' , FS_AFFILIATES_LOCALE ) ,
                'SDG' => __( 'Sudanese pound' , FS_AFFILIATES_LOCALE ) ,
                'SEK' => __( 'Swedish krona' , FS_AFFILIATES_LOCALE ) ,
                'SGD' => __( 'Singapore dollar' , FS_AFFILIATES_LOCALE ) ,
                'SHP' => __( 'Saint Helena pound' , FS_AFFILIATES_LOCALE ) ,
                'SLL' => __( 'Sierra Leonean leone' , FS_AFFILIATES_LOCALE ) ,
                'SOS' => __( 'Somali shilling' , FS_AFFILIATES_LOCALE ) ,
                'SRD' => __( 'Surinamese dollar' , FS_AFFILIATES_LOCALE ) ,
                'SSP' => __( 'South Sudanese pound' , FS_AFFILIATES_LOCALE ) ,
                'STD' => __( 'S&atilde;o Tom&eacute; and Pr&iacute;ncipe dobra' , FS_AFFILIATES_LOCALE ) ,
                'SYP' => __( 'Syrian pound' , FS_AFFILIATES_LOCALE ) ,
                'SZL' => __( 'Swazi lilangeni' , FS_AFFILIATES_LOCALE ) ,
                'THB' => __( 'Thai baht' , FS_AFFILIATES_LOCALE ) ,
                'TJS' => __( 'Tajikistani somoni' , FS_AFFILIATES_LOCALE ) ,
                'TMT' => __( 'Turkmenistan manat' , FS_AFFILIATES_LOCALE ) ,
                'TND' => __( 'Tunisian dinar' , FS_AFFILIATES_LOCALE ) ,
                'TOP' => __( 'Tongan pa&#x2bb;anga' , FS_AFFILIATES_LOCALE ) ,
                'TRY' => __( 'Turkish lira' , FS_AFFILIATES_LOCALE ) ,
                'TTD' => __( 'Trinidad and Tobago dollar' , FS_AFFILIATES_LOCALE ) ,
                'TWD' => __( 'New Taiwan dollar' , FS_AFFILIATES_LOCALE ) ,
                'TZS' => __( 'Tanzanian shilling' , FS_AFFILIATES_LOCALE ) ,
                'UAH' => __( 'Ukrainian hryvnia' , FS_AFFILIATES_LOCALE ) ,
                'UGX' => __( 'Ugandan shilling' , FS_AFFILIATES_LOCALE ) ,
                'USD' => __( 'United States (US) dollar' , FS_AFFILIATES_LOCALE ) ,
                'UYU' => __( 'Uruguayan peso' , FS_AFFILIATES_LOCALE ) ,
                'UZS' => __( 'Uzbekistani som' , FS_AFFILIATES_LOCALE ) ,
                'VEF' => __( 'Venezuelan bol&iacute;var' , FS_AFFILIATES_LOCALE ) ,
                'VND' => __( 'Vietnamese &#x111;&#x1ed3;ng' , FS_AFFILIATES_LOCALE ) ,
                'VUV' => __( 'Vanuatu vatu' , FS_AFFILIATES_LOCALE ) ,
                'WST' => __( 'Samoan t&#x101;l&#x101;' , FS_AFFILIATES_LOCALE ) ,
                'XAF' => __( 'Central African CFA franc' , FS_AFFILIATES_LOCALE ) ,
                'XCD' => __( 'East Caribbean dollar' , FS_AFFILIATES_LOCALE ) ,
                'XOF' => __( 'West African CFA franc' , FS_AFFILIATES_LOCALE ) ,
                'XPF' => __( 'CFP franc' , FS_AFFILIATES_LOCALE ) ,
                'YER' => __( 'Yemeni rial' , FS_AFFILIATES_LOCALE ) ,
                'ZAR' => __( 'South African rand' , FS_AFFILIATES_LOCALE ) ,
                'ZMW' => __( 'Zambian kwacha' , FS_AFFILIATES_LOCALE ) ,
                            )
                    )
                    ) ;
        }

        return $currencies ;
    }

}

if ( ! function_exists( 'get_fs_affiliates_currency_symbol' ) ) {

    function get_fs_affiliates_currency_symbol( $currency = '' ) {
        if ( ! $currency ) {
            $currency = get_option( 'fs_affiliates_currency' , 'USD' ) ;
        }

        $symbols         = apply_filters(
                'fs_affiliates_currency_symbols' , array(
            'AED' => '&#x62f;.&#x625;' ,
            'AFN' => '&#x60b;' ,
            'ALL' => 'L' ,
            'AMD' => 'AMD' ,
            'ANG' => '&fnof;' ,
            'AOA' => 'Kz' ,
            'ARS' => '&#36;' ,
            'AUD' => '&#36;' ,
            'AWG' => 'Afl.' ,
            'AZN' => 'AZN' ,
            'BAM' => 'KM' ,
            'BBD' => '&#36;' ,
            'BDT' => '&#2547;&nbsp;' ,
            'BGN' => '&#1083;&#1074;.' ,
            'BHD' => '.&#x62f;.&#x628;' ,
            'BIF' => 'Fr' ,
            'BMD' => '&#36;' ,
            'BND' => '&#36;' ,
            'BOB' => 'Bs.' ,
            'BRL' => '&#82;&#36;' ,
            'BSD' => '&#36;' ,
            'BTC' => '&#3647;' ,
            'BTN' => 'Nu.' ,
            'BWP' => 'P' ,
            'BYR' => 'Br' ,
            'BYN' => 'Br' ,
            'BZD' => '&#36;' ,
            'CAD' => '&#36;' ,
            'CDF' => 'Fr' ,
            'CHF' => '&#67;&#72;&#70;' ,
            'CLP' => '&#36;' ,
            'CNY' => '&yen;' ,
            'COP' => '&#36;' ,
            'CRC' => '&#x20a1;' ,
            'CUC' => '&#36;' ,
            'CUP' => '&#36;' ,
            'CVE' => '&#36;' ,
            'CZK' => '&#75;&#269;' ,
            'DJF' => 'Fr' ,
            'DKK' => 'DKK' ,
            'DOP' => 'RD&#36;' ,
            'DZD' => '&#x62f;.&#x62c;' ,
            'EGP' => 'EGP' ,
            'ERN' => 'Nfk' ,
            'ETB' => 'Br' ,
            'EUR' => '&euro;' ,
            'FJD' => '&#36;' ,
            'FKP' => '&pound;' ,
            'GBP' => '&pound;' ,
            'GEL' => '&#x20be;' ,
            'GGP' => '&pound;' ,
            'GHS' => '&#x20b5;' ,
            'GIP' => '&pound;' ,
            'GMD' => 'D' ,
            'GNF' => 'Fr' ,
            'GTQ' => 'Q' ,
            'GYD' => '&#36;' ,
            'HKD' => '&#36;' ,
            'HNL' => 'L' ,
            'HRK' => 'Kn' ,
            'HTG' => 'G' ,
            'HUF' => '&#70;&#116;' ,
            'IDR' => 'Rp' ,
            'ILS' => '&#8362;' ,
            'IMP' => '&pound;' ,
            'INR' => '&#8377;' ,
            'IQD' => '&#x639;.&#x62f;' ,
            'IRR' => '&#xfdfc;' ,
            'IRT' => '&#x062A;&#x0648;&#x0645;&#x0627;&#x0646;' ,
            'ISK' => 'kr.' ,
            'JEP' => '&pound;' ,
            'JMD' => '&#36;' ,
            'JOD' => '&#x62f;.&#x627;' ,
            'JPY' => '&yen;' ,
            'KES' => 'KSh' ,
            'KGS' => '&#x441;&#x43e;&#x43c;' ,
            'KHR' => '&#x17db;' ,
            'KMF' => 'Fr' ,
            'KPW' => '&#x20a9;' ,
            'KRW' => '&#8361;' ,
            'KWD' => '&#x62f;.&#x643;' ,
            'KYD' => '&#36;' ,
            'KZT' => 'KZT' ,
            'LAK' => '&#8365;' ,
            'LBP' => '&#x644;.&#x644;' ,
            'LKR' => '&#xdbb;&#xdd4;' ,
            'LRD' => '&#36;' ,
            'LSL' => 'L' ,
            'LYD' => '&#x644;.&#x62f;' ,
            'MAD' => '&#x62f;.&#x645;.' ,
            'MDL' => 'MDL' ,
            'MGA' => 'Ar' ,
            'MKD' => '&#x434;&#x435;&#x43d;' ,
            'MMK' => 'Ks' ,
            'MNT' => '&#x20ae;' ,
            'MOP' => 'P' ,
            'MRO' => 'UM' ,
            'MUR' => '&#x20a8;' ,
            'MVR' => '.&#x783;' ,
            'MWK' => 'MK' ,
            'MXN' => '&#36;' ,
            'MYR' => '&#82;&#77;' ,
            'MZN' => 'MT' ,
            'NAD' => '&#36;' ,
            'NGN' => '&#8358;' ,
            'NIO' => 'C&#36;' ,
            'NOK' => '&#107;&#114;' ,
            'NPR' => '&#8360;' ,
            'NZD' => '&#36;' ,
            'OMR' => '&#x631;.&#x639;.' ,
            'PAB' => 'B/.' ,
            'PEN' => 'S/.' ,
            'PGK' => 'K' ,
            'PHP' => '&#8369;' ,
            'PKR' => '&#8360;' ,
            'PLN' => '&#122;&#322;' ,
            'PRB' => '&#x440;.' ,
            'PYG' => '&#8370;' ,
            'QAR' => '&#x631;.&#x642;' ,
            'RMB' => '&yen;' ,
            'RON' => 'lei' ,
            'RSD' => '&#x434;&#x438;&#x43d;.' ,
            'RUB' => '&#8381;' ,
            'RWF' => 'Fr' ,
            'SAR' => '&#x631;.&#x633;' ,
            'SBD' => '&#36;' ,
            'SCR' => '&#x20a8;' ,
            'SDG' => '&#x62c;.&#x633;.' ,
            'SEK' => '&#107;&#114;' ,
            'SGD' => '&#36;' ,
            'SHP' => '&pound;' ,
            'SLL' => 'Le' ,
            'SOS' => 'Sh' ,
            'SRD' => '&#36;' ,
            'SSP' => '&pound;' ,
            'STD' => 'Db' ,
            'SYP' => '&#x644;.&#x633;' ,
            'SZL' => 'L' ,
            'THB' => '&#3647;' ,
            'TJS' => '&#x405;&#x41c;' ,
            'TMT' => 'm' ,
            'TND' => '&#x62f;.&#x62a;' ,
            'TOP' => 'T&#36;' ,
            'TRY' => '&#8378;' ,
            'TTD' => '&#36;' ,
            'TWD' => '&#78;&#84;&#36;' ,
            'TZS' => 'Sh' ,
            'UAH' => '&#8372;' ,
            'UGX' => 'UGX' ,
            'USD' => '&#36;' ,
            'UYU' => '&#36;' ,
            'UZS' => 'UZS' ,
            'VEF' => 'Bs F' ,
            'VND' => '&#8363;' ,
            'VUV' => 'Vt' ,
            'WST' => 'T' ,
            'XAF' => 'CFA' ,
            'XCD' => '&#36;' ,
            'XOF' => 'CFA' ,
            'XPF' => 'Fr' ,
            'YER' => '&#xfdfc;' ,
            'ZAR' => '&#82;' ,
            'ZMW' => 'ZK' ,
                )
                ) ;
        $currency_symbol = isset( $symbols[ $currency ] ) ? $symbols[ $currency ] : '' ;

        return apply_filters( 'fs_affiliates_currency_symbol' , $currency_symbol , $currency ) ;
    }

}


if ( ! function_exists( 'get_fs_affiliates_countries' ) ) {

    /**
     * Get full list of Countries.
     */
    function get_fs_affiliates_countries() {
        static $countries ;

        if ( ! isset( $countries ) ) {
            $countries = array_unique(
                    apply_filters(
                            'fs_affilates_countries' , array(
                'AF' => __( 'Afghanistan' , FS_AFFILIATES_LOCALE ) ,
                'AX' => __( '&#197;land Islands' , FS_AFFILIATES_LOCALE ) ,
                'AL' => __( 'Albania' , FS_AFFILIATES_LOCALE ) ,
                'DZ' => __( 'Algeria' , FS_AFFILIATES_LOCALE ) ,
                'AS' => __( 'American Samoa' , FS_AFFILIATES_LOCALE ) ,
                'AD' => __( 'Andorra' , FS_AFFILIATES_LOCALE ) ,
                'AO' => __( 'Angola' , FS_AFFILIATES_LOCALE ) ,
                'AI' => __( 'Anguilla' , FS_AFFILIATES_LOCALE ) ,
                'AQ' => __( 'Antarctica' , FS_AFFILIATES_LOCALE ) ,
                'AG' => __( 'Antigua and Barbuda' , FS_AFFILIATES_LOCALE ) ,
                'AR' => __( 'Argentina' , FS_AFFILIATES_LOCALE ) ,
                'AM' => __( 'Armenia' , FS_AFFILIATES_LOCALE ) ,
                'AW' => __( 'Aruba' , FS_AFFILIATES_LOCALE ) ,
                'AU' => __( 'Australia' , FS_AFFILIATES_LOCALE ) ,
                'AT' => __( 'Austria' , FS_AFFILIATES_LOCALE ) ,
                'AZ' => __( 'Azerbaijan' , FS_AFFILIATES_LOCALE ) ,
                'BS' => __( 'Bahamas' , FS_AFFILIATES_LOCALE ) ,
                'BH' => __( 'Bahrain' , FS_AFFILIATES_LOCALE ) ,
                'BD' => __( 'Bangladesh' , FS_AFFILIATES_LOCALE ) ,
                'BB' => __( 'Barbados' , FS_AFFILIATES_LOCALE ) ,
                'BY' => __( 'Belarus' , FS_AFFILIATES_LOCALE ) ,
                'BE' => __( 'Belgium' , FS_AFFILIATES_LOCALE ) ,
                'PW' => __( 'Belau' , FS_AFFILIATES_LOCALE ) ,
                'BZ' => __( 'Belize' , FS_AFFILIATES_LOCALE ) ,
                'BJ' => __( 'Benin' , FS_AFFILIATES_LOCALE ) ,
                'BM' => __( 'Bermuda' , FS_AFFILIATES_LOCALE ) ,
                'BT' => __( 'Bhutan' , FS_AFFILIATES_LOCALE ) ,
                'BO' => __( 'Bolivia' , FS_AFFILIATES_LOCALE ) ,
                'BQ' => __( 'Bonaire, Saint Eustatius and Saba' , FS_AFFILIATES_LOCALE ) ,
                'BA' => __( 'Bosnia and Herzegovina' , FS_AFFILIATES_LOCALE ) ,
                'BW' => __( 'Botswana' , FS_AFFILIATES_LOCALE ) ,
                'BV' => __( 'Bouvet Island' , FS_AFFILIATES_LOCALE ) ,
                'BR' => __( 'Brazil' , FS_AFFILIATES_LOCALE ) ,
                'IO' => __( 'British Indian Ocean Territory' , FS_AFFILIATES_LOCALE ) ,
                'VG' => __( 'British Virgin Islands' , FS_AFFILIATES_LOCALE ) ,
                'BN' => __( 'Brunei' , FS_AFFILIATES_LOCALE ) ,
                'BG' => __( 'Bulgaria' , FS_AFFILIATES_LOCALE ) ,
                'BF' => __( 'Burkina Faso' , FS_AFFILIATES_LOCALE ) ,
                'BI' => __( 'Burundi' , FS_AFFILIATES_LOCALE ) ,
                'KH' => __( 'Cambodia' , FS_AFFILIATES_LOCALE ) ,
                'CM' => __( 'Cameroon' , FS_AFFILIATES_LOCALE ) ,
                'CA' => __( 'Canada' , FS_AFFILIATES_LOCALE ) ,
                'CV' => __( 'Cape Verde' , FS_AFFILIATES_LOCALE ) ,
                'KY' => __( 'Cayman Islands' , FS_AFFILIATES_LOCALE ) ,
                'CF' => __( 'Central African Republic' , FS_AFFILIATES_LOCALE ) ,
                'TD' => __( 'Chad' , FS_AFFILIATES_LOCALE ) ,
                'CL' => __( 'Chile' , FS_AFFILIATES_LOCALE ) ,
                'CN' => __( 'China' , FS_AFFILIATES_LOCALE ) ,
                'CX' => __( 'Christmas Island' , FS_AFFILIATES_LOCALE ) ,
                'CC' => __( 'Cocos (Keeling) Islands' , FS_AFFILIATES_LOCALE ) ,
                'CO' => __( 'Colombia' , FS_AFFILIATES_LOCALE ) ,
                'KM' => __( 'Comoros' , FS_AFFILIATES_LOCALE ) ,
                'CG' => __( 'Congo (Brazzaville)' , FS_AFFILIATES_LOCALE ) ,
                'CD' => __( 'Congo (Kinshasa)' , FS_AFFILIATES_LOCALE ) ,
                'CK' => __( 'Cook Islands' , FS_AFFILIATES_LOCALE ) ,
                'CR' => __( 'Costa Rica' , FS_AFFILIATES_LOCALE ) ,
                'HR' => __( 'Croatia' , FS_AFFILIATES_LOCALE ) ,
                'CU' => __( 'Cuba' , FS_AFFILIATES_LOCALE ) ,
                'CW' => __( 'Cura&ccedil;ao' , FS_AFFILIATES_LOCALE ) ,
                'CY' => __( 'Cyprus' , FS_AFFILIATES_LOCALE ) ,
                'CZ' => __( 'Czech Republic' , FS_AFFILIATES_LOCALE ) ,
                'DK' => __( 'Denmark' , FS_AFFILIATES_LOCALE ) ,
                'DJ' => __( 'Djibouti' , FS_AFFILIATES_LOCALE ) ,
                'DM' => __( 'Dominica' , FS_AFFILIATES_LOCALE ) ,
                'DO' => __( 'Dominican Republic' , FS_AFFILIATES_LOCALE ) ,
                'EC' => __( 'Ecuador' , FS_AFFILIATES_LOCALE ) ,
                'EG' => __( 'Egypt' , FS_AFFILIATES_LOCALE ) ,
                'SV' => __( 'El Salvador' , FS_AFFILIATES_LOCALE ) ,
                'GQ' => __( 'Equatorial Guinea' , FS_AFFILIATES_LOCALE ) ,
                'ER' => __( 'Eritrea' , FS_AFFILIATES_LOCALE ) ,
                'EE' => __( 'Estonia' , FS_AFFILIATES_LOCALE ) ,
                'ET' => __( 'Ethiopia' , FS_AFFILIATES_LOCALE ) ,
                'FK' => __( 'Falkland Islands' , FS_AFFILIATES_LOCALE ) ,
                'FO' => __( 'Faroe Islands' , FS_AFFILIATES_LOCALE ) ,
                'FJ' => __( 'Fiji' , FS_AFFILIATES_LOCALE ) ,
                'FI' => __( 'Finland' , FS_AFFILIATES_LOCALE ) ,
                'FR' => __( 'France' , FS_AFFILIATES_LOCALE ) ,
                'GF' => __( 'French Guiana' , FS_AFFILIATES_LOCALE ) ,
                'PF' => __( 'French Polynesia' , FS_AFFILIATES_LOCALE ) ,
                'TF' => __( 'French Southern Territories' , FS_AFFILIATES_LOCALE ) ,
                'GA' => __( 'Gabon' , FS_AFFILIATES_LOCALE ) ,
                'GM' => __( 'Gambia' , FS_AFFILIATES_LOCALE ) ,
                'GE' => __( 'Georgia' , FS_AFFILIATES_LOCALE ) ,
                'DE' => __( 'Germany' , FS_AFFILIATES_LOCALE ) ,
                'GH' => __( 'Ghana' , FS_AFFILIATES_LOCALE ) ,
                'GI' => __( 'Gibraltar' , FS_AFFILIATES_LOCALE ) ,
                'GR' => __( 'Greece' , FS_AFFILIATES_LOCALE ) ,
                'GL' => __( 'Greenland' , FS_AFFILIATES_LOCALE ) ,
                'GD' => __( 'Grenada' , FS_AFFILIATES_LOCALE ) ,
                'GP' => __( 'Guadeloupe' , FS_AFFILIATES_LOCALE ) ,
                'GU' => __( 'Guam' , FS_AFFILIATES_LOCALE ) ,
                'GT' => __( 'Guatemala' , FS_AFFILIATES_LOCALE ) ,
                'GG' => __( 'Guernsey' , FS_AFFILIATES_LOCALE ) ,
                'GN' => __( 'Guinea' , FS_AFFILIATES_LOCALE ) ,
                'GW' => __( 'Guinea-Bissau' , FS_AFFILIATES_LOCALE ) ,
                'GY' => __( 'Guyana' , FS_AFFILIATES_LOCALE ) ,
                'HT' => __( 'Haiti' , FS_AFFILIATES_LOCALE ) ,
                'HM' => __( 'Heard Island and McDonald Islands' , FS_AFFILIATES_LOCALE ) ,
                'HN' => __( 'Honduras' , FS_AFFILIATES_LOCALE ) ,
                'HK' => __( 'Hong Kong' , FS_AFFILIATES_LOCALE ) ,
                'HU' => __( 'Hungary' , FS_AFFILIATES_LOCALE ) ,
                'IS' => __( 'Iceland' , FS_AFFILIATES_LOCALE ) ,
                'IN' => __( 'India' , FS_AFFILIATES_LOCALE ) ,
                'ID' => __( 'Indonesia' , FS_AFFILIATES_LOCALE ) ,
                'IR' => __( 'Iran' , FS_AFFILIATES_LOCALE ) ,
                'IQ' => __( 'Iraq' , FS_AFFILIATES_LOCALE ) ,
                'IE' => __( 'Ireland' , FS_AFFILIATES_LOCALE ) ,
                'IM' => __( 'Isle of Man' , FS_AFFILIATES_LOCALE ) ,
                'IL' => __( 'Israel' , FS_AFFILIATES_LOCALE ) ,
                'IT' => __( 'Italy' , FS_AFFILIATES_LOCALE ) ,
                'CI' => __( 'Ivory Coast' , FS_AFFILIATES_LOCALE ) ,
                'JM' => __( 'Jamaica' , FS_AFFILIATES_LOCALE ) ,
                'JP' => __( 'Japan' , FS_AFFILIATES_LOCALE ) ,
                'JE' => __( 'Jersey' , FS_AFFILIATES_LOCALE ) ,
                'JO' => __( 'Jordan' , FS_AFFILIATES_LOCALE ) ,
                'KZ' => __( 'Kazakhstan' , FS_AFFILIATES_LOCALE ) ,
                'KE' => __( 'Kenya' , FS_AFFILIATES_LOCALE ) ,
                'KI' => __( 'Kiribati' , FS_AFFILIATES_LOCALE ) ,
                'KW' => __( 'Kuwait' , FS_AFFILIATES_LOCALE ) ,
                'KG' => __( 'Kyrgyzstan' , FS_AFFILIATES_LOCALE ) ,
                'LA' => __( 'Laos' , FS_AFFILIATES_LOCALE ) ,
                'LV' => __( 'Latvia' , FS_AFFILIATES_LOCALE ) ,
                'LB' => __( 'Lebanon' , FS_AFFILIATES_LOCALE ) ,
                'LS' => __( 'Lesotho' , FS_AFFILIATES_LOCALE ) ,
                'LR' => __( 'Liberia' , FS_AFFILIATES_LOCALE ) ,
                'LY' => __( 'Libya' , FS_AFFILIATES_LOCALE ) ,
                'LI' => __( 'Liechtenstein' , FS_AFFILIATES_LOCALE ) ,
                'LT' => __( 'Lithuania' , FS_AFFILIATES_LOCALE ) ,
                'LU' => __( 'Luxembourg' , FS_AFFILIATES_LOCALE ) ,
                'MO' => __( 'Macao S.A.R., China' , FS_AFFILIATES_LOCALE ) ,
                'MK' => __( 'Macedonia' , FS_AFFILIATES_LOCALE ) ,
                'MG' => __( 'Madagascar' , FS_AFFILIATES_LOCALE ) ,
                'MW' => __( 'Malawi' , FS_AFFILIATES_LOCALE ) ,
                'MY' => __( 'Malaysia' , FS_AFFILIATES_LOCALE ) ,
                'MV' => __( 'Maldives' , FS_AFFILIATES_LOCALE ) ,
                'ML' => __( 'Mali' , FS_AFFILIATES_LOCALE ) ,
                'MT' => __( 'Malta' , FS_AFFILIATES_LOCALE ) ,
                'MH' => __( 'Marshall Islands' , FS_AFFILIATES_LOCALE ) ,
                'MQ' => __( 'Martinique' , FS_AFFILIATES_LOCALE ) ,
                'MR' => __( 'Mauritania' , FS_AFFILIATES_LOCALE ) ,
                'MU' => __( 'Mauritius' , FS_AFFILIATES_LOCALE ) ,
                'YT' => __( 'Mayotte' , FS_AFFILIATES_LOCALE ) ,
                'MX' => __( 'Mexico' , FS_AFFILIATES_LOCALE ) ,
                'FM' => __( 'Micronesia' , FS_AFFILIATES_LOCALE ) ,
                'MD' => __( 'Moldova' , FS_AFFILIATES_LOCALE ) ,
                'MC' => __( 'Monaco' , FS_AFFILIATES_LOCALE ) ,
                'MN' => __( 'Mongolia' , FS_AFFILIATES_LOCALE ) ,
                'ME' => __( 'Montenegro' , FS_AFFILIATES_LOCALE ) ,
                'MS' => __( 'Montserrat' , FS_AFFILIATES_LOCALE ) ,
                'MA' => __( 'Morocco' , FS_AFFILIATES_LOCALE ) ,
                'MZ' => __( 'Mozambique' , FS_AFFILIATES_LOCALE ) ,
                'MM' => __( 'Myanmar' , FS_AFFILIATES_LOCALE ) ,
                'NA' => __( 'Namibia' , FS_AFFILIATES_LOCALE ) ,
                'NR' => __( 'Nauru' , FS_AFFILIATES_LOCALE ) ,
                'NP' => __( 'Nepal' , FS_AFFILIATES_LOCALE ) ,
                'NL' => __( 'Netherlands' , FS_AFFILIATES_LOCALE ) ,
                'NC' => __( 'New Caledonia' , FS_AFFILIATES_LOCALE ) ,
                'NZ' => __( 'New Zealand' , FS_AFFILIATES_LOCALE ) ,
                'NI' => __( 'Nicaragua' , FS_AFFILIATES_LOCALE ) ,
                'NE' => __( 'Niger' , FS_AFFILIATES_LOCALE ) ,
                'NG' => __( 'Nigeria' , FS_AFFILIATES_LOCALE ) ,
                'NU' => __( 'Niue' , FS_AFFILIATES_LOCALE ) ,
                'NF' => __( 'Norfolk Island' , FS_AFFILIATES_LOCALE ) ,
                'MP' => __( 'Northern Mariana Islands' , FS_AFFILIATES_LOCALE ) ,
                'KP' => __( 'North Korea' , FS_AFFILIATES_LOCALE ) ,
                'NO' => __( 'Norway' , FS_AFFILIATES_LOCALE ) ,
                'OM' => __( 'Oman' , FS_AFFILIATES_LOCALE ) ,
                'PK' => __( 'Pakistan' , FS_AFFILIATES_LOCALE ) ,
                'PS' => __( 'Palestinian Territory' , FS_AFFILIATES_LOCALE ) ,
                'PA' => __( 'Panama' , FS_AFFILIATES_LOCALE ) ,
                'PG' => __( 'Papua New Guinea' , FS_AFFILIATES_LOCALE ) ,
                'PY' => __( 'Paraguay' , FS_AFFILIATES_LOCALE ) ,
                'PE' => __( 'Peru' , FS_AFFILIATES_LOCALE ) ,
                'PH' => __( 'Philippines' , FS_AFFILIATES_LOCALE ) ,
                'PN' => __( 'Pitcairn' , FS_AFFILIATES_LOCALE ) ,
                'PL' => __( 'Poland' , FS_AFFILIATES_LOCALE ) ,
                'PT' => __( 'Portugal' , FS_AFFILIATES_LOCALE ) ,
                'PR' => __( 'Puerto Rico' , FS_AFFILIATES_LOCALE ) ,
                'QA' => __( 'Qatar' , FS_AFFILIATES_LOCALE ) ,
                'RE' => __( 'Reunion' , FS_AFFILIATES_LOCALE ) ,
                'RO' => __( 'Romania' , FS_AFFILIATES_LOCALE ) ,
                'RU' => __( 'Russia' , FS_AFFILIATES_LOCALE ) ,
                'RW' => __( 'Rwanda' , FS_AFFILIATES_LOCALE ) ,
                'BL' => __( 'Saint Barth&eacute;lemy' , FS_AFFILIATES_LOCALE ) ,
                'SH' => __( 'Saint Helena' , FS_AFFILIATES_LOCALE ) ,
                'KN' => __( 'Saint Kitts and Nevis' , FS_AFFILIATES_LOCALE ) ,
                'LC' => __( 'Saint Lucia' , FS_AFFILIATES_LOCALE ) ,
                'MF' => __( 'Saint Martin (French part)' , FS_AFFILIATES_LOCALE ) ,
                'SX' => __( 'Saint Martin (Dutch part)' , FS_AFFILIATES_LOCALE ) ,
                'PM' => __( 'Saint Pierre and Miquelon' , FS_AFFILIATES_LOCALE ) ,
                'VC' => __( 'Saint Vincent and the Grenadines' , FS_AFFILIATES_LOCALE ) ,
                'SM' => __( 'San Marino' , FS_AFFILIATES_LOCALE ) ,
                'ST' => __( 'S&atilde;o Tom&eacute; and Pr&iacute;ncipe' , FS_AFFILIATES_LOCALE ) ,
                'SA' => __( 'Saudi Arabia' , FS_AFFILIATES_LOCALE ) ,
                'SN' => __( 'Senegal' , FS_AFFILIATES_LOCALE ) ,
                'RS' => __( 'Serbia' , FS_AFFILIATES_LOCALE ) ,
                'SC' => __( 'Seychelles' , FS_AFFILIATES_LOCALE ) ,
                'SL' => __( 'Sierra Leone' , FS_AFFILIATES_LOCALE ) ,
                'SG' => __( 'Singapore' , FS_AFFILIATES_LOCALE ) ,
                'SK' => __( 'Slovakia' , FS_AFFILIATES_LOCALE ) ,
                'SI' => __( 'Slovenia' , FS_AFFILIATES_LOCALE ) ,
                'SB' => __( 'Solomon Islands' , FS_AFFILIATES_LOCALE ) ,
                'SO' => __( 'Somalia' , FS_AFFILIATES_LOCALE ) ,
                'ZA' => __( 'South Africa' , FS_AFFILIATES_LOCALE ) ,
                'GS' => __( 'South Georgia/Sandwich Islands' , FS_AFFILIATES_LOCALE ) ,
                'KR' => __( 'South Korea' , FS_AFFILIATES_LOCALE ) ,
                'SS' => __( 'South Sudan' , FS_AFFILIATES_LOCALE ) ,
                'ES' => __( 'Spain' , FS_AFFILIATES_LOCALE ) ,
                'LK' => __( 'Sri Lanka' , FS_AFFILIATES_LOCALE ) ,
                'SD' => __( 'Sudan' , FS_AFFILIATES_LOCALE ) ,
                'SR' => __( 'Suriname' , FS_AFFILIATES_LOCALE ) ,
                'SJ' => __( 'Svalbard and Jan Mayen' , FS_AFFILIATES_LOCALE ) ,
                'SZ' => __( 'Swaziland' , FS_AFFILIATES_LOCALE ) ,
                'SE' => __( 'Sweden' , FS_AFFILIATES_LOCALE ) ,
                'CH' => __( 'Switzerland' , FS_AFFILIATES_LOCALE ) ,
                'SY' => __( 'Syria' , FS_AFFILIATES_LOCALE ) ,
                'TW' => __( 'Taiwan' , FS_AFFILIATES_LOCALE ) ,
                'TJ' => __( 'Tajikistan' , FS_AFFILIATES_LOCALE ) ,
                'TZ' => __( 'Tanzania' , FS_AFFILIATES_LOCALE ) ,
                'TH' => __( 'Thailand' , FS_AFFILIATES_LOCALE ) ,
                'TL' => __( 'Timor-Leste' , FS_AFFILIATES_LOCALE ) ,
                'TG' => __( 'Togo' , FS_AFFILIATES_LOCALE ) ,
                'TK' => __( 'Tokelau' , FS_AFFILIATES_LOCALE ) ,
                'TO' => __( 'Tonga' , FS_AFFILIATES_LOCALE ) ,
                'TT' => __( 'Trinidad and Tobago' , FS_AFFILIATES_LOCALE ) ,
                'TN' => __( 'Tunisia' , FS_AFFILIATES_LOCALE ) ,
                'TR' => __( 'Turkey' , FS_AFFILIATES_LOCALE ) ,
                'TM' => __( 'Turkmenistan' , FS_AFFILIATES_LOCALE ) ,
                'TC' => __( 'Turks and Caicos Islands' , FS_AFFILIATES_LOCALE ) ,
                'TV' => __( 'Tuvalu' , FS_AFFILIATES_LOCALE ) ,
                'UG' => __( 'Uganda' , FS_AFFILIATES_LOCALE ) ,
                'UA' => __( 'Ukraine' , FS_AFFILIATES_LOCALE ) ,
                'AE' => __( 'United Arab Emirates' , FS_AFFILIATES_LOCALE ) ,
                'GB' => __( 'United Kingdom (UK)' , FS_AFFILIATES_LOCALE ) ,
                'US' => __( 'United States (US)' , FS_AFFILIATES_LOCALE ) ,
                'UM' => __( 'United States (US) Minor Outlying Islands' , FS_AFFILIATES_LOCALE ) ,
                'VI' => __( 'United States (US) Virgin Islands' , FS_AFFILIATES_LOCALE ) ,
                'UY' => __( 'Uruguay' , FS_AFFILIATES_LOCALE ) ,
                'UZ' => __( 'Uzbekistan' , FS_AFFILIATES_LOCALE ) ,
                'VU' => __( 'Vanuatu' , FS_AFFILIATES_LOCALE ) ,
                'VA' => __( 'Vatican' , FS_AFFILIATES_LOCALE ) ,
                'VE' => __( 'Venezuela' , FS_AFFILIATES_LOCALE ) ,
                'VN' => __( 'Vietnam' , FS_AFFILIATES_LOCALE ) ,
                'WF' => __( 'Wallis and Futuna' , FS_AFFILIATES_LOCALE ) ,
                'EH' => __( 'Western Sahara' , FS_AFFILIATES_LOCALE ) ,
                'WS' => __( 'Samoa' , FS_AFFILIATES_LOCALE ) ,
                'YE' => __( 'Yemen' , FS_AFFILIATES_LOCALE ) ,
                'ZM' => __( 'Zambia' , FS_AFFILIATES_LOCALE ) ,
                'ZW' => __( 'Zimbabwe' , FS_AFFILIATES_LOCALE ) ,
                            )
                    )
                    ) ;
        }

        return $countries ;
    }

}


if ( ! function_exists( 'fs_affiliates_get_default_form_fields' ) ) {

    /**
     * Prepare form default fields
     */
    function fs_affiliates_get_default_form_fields() {
        static $form_fields ;

        if ( ! isset( $form_fields ) ) {
            $form_fields = array(
                'first_name'        => array(
                    'field_name'        => 'First Name' ,
                    'field_key'         => 'first_name' ,
                    'field_status'      => 'enabled' ,
                    'field_required'    => 'optional' ,
                    'field_placeholder' => '' ,
                    'field_description' => '' ,
                    ''
                ) ,
                'last_name'         => array(
                    'field_name'        => 'Last Name' ,
                    'field_key'         => 'last_name' ,
                    'field_status'      => 'enabled' ,
                    'field_required'    => 'optional' ,
                    'field_placeholder' => '' ,
                    'field_description' => ''
                ) ,
                'user_name'         => array(
                    'field_name'        => 'Username' ,
                    'field_key'         => 'user_name' ,
                    'field_status'      => 'enabled' ,
                    'field_required'    => 'mandatory' ,
                    'field_placeholder' => '' ,
                    'field_description' => ''
                ) ,
                'email'             => array(
                    'field_name'        => 'Email' ,
                    'field_key'         => 'email' ,
                    'field_status'      => 'enabled' ,
                    'field_required'    => 'mandatory' ,
                    'field_placeholder' => '' ,
                    'field_description' => ''
                ) ,
                'phonenumber'       => array(
                    'field_name'        => 'Phone Number' ,
                    'field_key'         => 'phonenumber' ,
                    'field_status'      => 'enabled' ,
                    'field_required'    => 'optional' ,
                    'field_placeholder' => '' ,
                    'field_description' => ''
                ) ,
                'country'           => array(
                    'field_name'        => 'Country' ,
                    'field_key'         => 'country' ,
                    'field_status'      => 'enabled' ,
                    'field_required'    => 'optional' ,
                    'field_placeholder' => '' ,
                    'field_description' => ''
                ) ,
                'website'           => array(
                    'field_name'        => 'Website' ,
                    'field_key'         => 'website' ,
                    'field_status'      => 'enabled' ,
                    'field_required'    => 'mandatory' ,
                    'field_placeholder' => '' ,
                    'field_description' => ''
                ) ,
                'promotion'         => array(
                    'field_name'        => 'Promotion Methods' ,
                    'field_key'         => 'promotion' ,
                    'field_status'      => 'enabled' ,
                    'field_required'    => 'mandatory' ,
                    'field_placeholder' => '' ,
                    'field_description' => ''
                ) ,
                'password'          => array(
                    'field_name'        => 'Password' ,
                    'field_key'         => 'password' ,
                    'field_status'      => 'enabled' ,
                    'field_required'    => 'mandatory' ,
                    'field_placeholder' => '' ,
                    'field_description' => ''
                ) ,
                'repeated_password' => array(
                    'field_name'        => 'Repeat Password' ,
                    'field_key'         => 'repeated_password' ,
                    'field_status'      => 'enabled' ,
                    'field_required'    => 'mandatory' ,
                    'field_placeholder' => '' ,
                    'field_description' => ''
                ) ,
                'file_upload'       => array(
                    'field_name'        => 'Upload Documents' ,
                    'field_key'         => 'file_upload' ,
                    'field_status'      => 'enabled' ,
                    'field_required'    => 'optional' ,
                    'field_placeholder' => '' ,
                    'field_description' => ''
                ) ,
                    ) ;
        }
        return $form_fields ;
    }

}


if ( ! function_exists( 'fs_affiliates_get_default_additional_dashboard_tab_settings' ) ) {

    /**
     * Prepare additional dashboard tabs
     */
    function fs_affiliates_get_default_additional_dashboard_tab_settings() {
        static $additional_dashboard_tab ;

        if ( ! isset( $additional_dashboard_tab ) ) {
            $additional_dashboard_tab = array(
                'overview'               => array(
                    'tile' => 'Overview' ,
                    'key'  => 'overview' ,
                    'hide' => 'no' ,
                ) ,
                'affiliate_tools'        => array(
                    'tile'             => 'Affiliate Tools' ,
                    'key'              => 'affiliate_tools' ,
                    'hide'             => 'no' ,
                    'submenu'          => array( 'campaigns'      => get_option( 'fs_affiliates_dashboard_customization_campaigns_label' ) ,
                        'affiliate_link' => get_option( 'fs_affiliates_dashboard_customization_links_label' ) ,
                        'creatives'      => get_option( 'fs_affiliates_dashboard_customization_creatives_label' ) ,
                        'referafriend'   => get_option( 'fs_affiliates_dashboard_customization_friend_form_label' )
                    ) ,
                    'selected_submenu' => array() ,
                ) ,
                'referrals'              => array(
                    'tile' => 'Referrals' ,
                    'key'  => 'referrals' ,
                    'hide' => 'no' ,
                ) ,
                'visits'                 => array(
                    'tile' => 'Visits' ,
                    'key'  => 'visits' ,
                    'hide' => 'no' ,
                ) ,
                'payouts'                => array(
                    'tile' => 'Payments' ,
                    'key'  => 'payouts' ,
                    'hide' => 'no' ,
                ) ,
                'wallet'                 => array(
                    'tile' => 'Wallet' ,
                    'key'  => 'wallet' ,
                    'hide' => 'no' ,
                ) ,
                'leaderboard'            => array(
                    'tile' => 'Leaderboard' ,
                    'key'  => 'leaderboard' ,
                    'hide' => 'no' ,
                ) ,
                'pushover_notifications' => array(
                    'tile' => 'Pushover Notifications' ,
                    'key'  => 'pushover_notifications' ,
                    'hide' => 'no' ,
                ) ,
                'wc_coupon_linking'      => array(
                    'tile' => 'WooCommerce Coupon Linking' ,
                    'key'  => 'wc_coupon_linking' ,
                    'hide' => 'no' ,
                ) ,
                'url_masking'            => array(
                    'tile' => 'URL Masking' ,
                    'key'  => 'url_masking' ,
                    'hide' => 'no' ,
                ) ,
                'payout_request'         => array(
                    'tile' => 'Payout Request' ,
                    'key'  => 'payout_request' ,
                    'hide' => 'no' ,
                ) ,
                'profile'                => array(
                    'tile'             => 'Profile' ,
                    'key'              => 'profile' ,
                    'hide'             => 'no' ,
                    'submenu'          => array( 'basic_details'      => get_option( 'fs_affiliates_dashboard_customization_basic_details_label' ) ,
                        'account_management' => get_option( 'fs_affiliates_dashboard_customization_acc_mgmt_label' ) ,
                        'payment_management' => get_option( 'fs_affiliates_dashboard_customization_payment_mgmt_label' ) ,
                        'payout_statements'  => get_option( 'fs_affiliates_payout_statements_dashboard_menu_label' )
                    ) ,
                    'selected_submenu' => array() ,
                ) ,
                'logout'                 => array(
                    'tile' => 'Logout' ,
                    'key'  => 'logout' ,
                    'hide' => 'no' ,
                ) ,
                    ) ;
        }

        return $additional_dashboard_tab ;
    }

}
if ( ! function_exists( 'fs_affiliates_get_pushover_sound_notifications' ) ) {

    /**
     * Prepare pushover sound notifications
     */
    function fs_affiliates_get_pushover_sound_notifications() {
        static $pushover_sound_notifications ;

        if ( ! isset( $pushover_sound_notifications ) ) {
            $pushover_sound_notifications = array(
                'pushover'     => __( 'Pushover' , FS_AFFILIATES_LOCALE ) ,
                'bike'         => __( 'Bike' , FS_AFFILIATES_LOCALE ) ,
                'bugle'        => __( 'Bugle' , FS_AFFILIATES_LOCALE ) ,
                'cashregister' => __( 'Cash Register' , FS_AFFILIATES_LOCALE ) ,
                'classical'    => __( 'Classical' , FS_AFFILIATES_LOCALE ) ,
                'cosmic'       => __( 'Cosmic' , FS_AFFILIATES_LOCALE ) ,
                'falling'      => __( 'Falling' , FS_AFFILIATES_LOCALE ) ,
                'gamelan'      => __( 'Gamelan' , FS_AFFILIATES_LOCALE ) ,
                'incoming'     => __( 'Incoming' , FS_AFFILIATES_LOCALE ) ,
                'intermission' => __( 'Intermission' , FS_AFFILIATES_LOCALE ) ,
                'magic'        => __( 'Magic' , FS_AFFILIATES_LOCALE ) ,
                'mechanical'   => __( 'Mechanical' , FS_AFFILIATES_LOCALE ) ,
                'pianobar'     => __( 'Piano Bar' , FS_AFFILIATES_LOCALE ) ,
                'siren'        => __( 'Siren' , FS_AFFILIATES_LOCALE ) ,
                'spacealarm'   => __( 'Space Alarm' , FS_AFFILIATES_LOCALE ) ,
                'tugboat'      => __( 'Tug Boat' , FS_AFFILIATES_LOCALE ) ,
                'alien'        => __( 'Alien Alarm (long)' , FS_AFFILIATES_LOCALE ) ,
                'climb'        => __( 'Climb (long)' , FS_AFFILIATES_LOCALE ) ,
                'persistent'   => __( 'Persistent (long)' , FS_AFFILIATES_LOCALE ) ,
                'echo'         => __( 'Pushover Echo (long) ' , FS_AFFILIATES_LOCALE ) ,
                'updown'       => __( 'Up Down (long)' , FS_AFFILIATES_LOCALE ) ,
                'none'         => __( 'None (silent)' , FS_AFFILIATES_LOCALE ) ,
                    ) ;
        }

        return $pushover_sound_notifications ;
    }

}


if ( ! function_exists( 'fs_affiliates_get_status_display' ) ) {

    function fs_affiliates_get_status_display( $status = 'fs_pending' , $html = true ) {

        switch ( $status ) {
            case 'fs_paid':
                $class_name  = 'fs_affiliates_paid_status' ;
                $status_name = __( 'Paid' , FS_AFFILIATES_LOCALE ) ;
                break ;
            case 'fs_inactive':
                $class_name  = 'fs_affiliates_inactive_status' ;
                $status_name = __( 'Inactive' , FS_AFFILIATES_LOCALE ) ;
                break ;
            case 'fs_unpaid':
                $class_name  = 'fs_affiliates_unpaid_status' ;
                $status_name = __( 'Unpaid' , FS_AFFILIATES_LOCALE ) ;
                break ;
            case 'fs_rejected':
                $class_name  = 'fs_affiliates_rejected_status' ;
                $status_name = __( 'Rejected' , FS_AFFILIATES_LOCALE ) ;
                break ;
            case 'fs_suspended':
                $class_name  = 'fs_affiliates_suspended_status' ;
                $status_name = __( 'Suspended' , FS_AFFILIATES_LOCALE ) ;
                break ;
            case 'fs_pending_approval':
                $class_name  = 'fs_affiliates_pending_approval_status' ;
                $status_name = __( 'Pending Approval' , FS_AFFILIATES_LOCALE ) ;
                break ;
            case 'fs_acknowledged':
                $class_name  = 'fs_affiliates_acknowledged_status' ;
                $status_name = __( 'Acknowledged' , FS_AFFILIATES_LOCALE ) ;
                break ;
            case 'fs_denied':
                $class_name  = 'fs_affiliates_denied_status' ;
                $status_name = __( 'Denied' , FS_AFFILIATES_LOCALE ) ;
                break ;
            case 'fs_pending':
                $class_name  = 'fs_affiliates_pending_status' ;
                $status_name = __( 'Pending' , FS_AFFILIATES_LOCALE ) ;
                break ;
            case 'fs_processing':
                $class_name  = 'fs_affiliates_processing_status' ;
                $status_name = __( 'Processing' , FS_AFFILIATES_LOCALE ) ;
                break ;
            case 'fs_success':
                $class_name  = 'fs_affiliates_success_status' ;
                $status_name = __( 'Success' , FS_AFFILIATES_LOCALE ) ;
                break ;
            case 'fs_new':
                $class_name  = 'fs_affiliates_new_status' ;
                $status_name = __( 'New' , FS_AFFILIATES_LOCALE ) ;
                break ;
            case 'fs_cancelled':
                $class_name  = 'fs_affiliates_cancelled_status' ;
                $status_name = __( 'Cancelled' , FS_AFFILIATES_LOCALE ) ;
                break ;
            case 'fs_hold':
                $class_name  = 'fs_affiliates_hold_status' ;
                $status_name = __( 'On-Hold' , FS_AFFILIATES_LOCALE ) ;
                break ;
            case 'fs_notconverted':
                $class_name  = 'fs_affiliates_notconverted_status' ;
                $status_name = __( 'Not Converted' , FS_AFFILIATES_LOCALE ) ;
                break ;
            case 'fs_converted':
                $class_name  = 'fs_affiliates_converted_status' ;
                $status_name = __( 'Converted' , FS_AFFILIATES_LOCALE ) ;
                break ;
            case 'fs_in_progress':
                $class_name  = 'fs_affiliates_converted_status' ;
                $status_name = __( 'In-Progress' , FS_AFFILIATES_LOCALE ) ;
                break ;
            case 'fs_pending_payment':
                $class_name  = 'fs_affiliates_pending_status' ;
                $status_name = __( 'Pending Payment' , FS_AFFILIATES_LOCALE ) ;
                break ;
            default :
                $class_name  = 'fs_affiliates_active_status' ;
                $status_name = __( 'Active' , FS_AFFILIATES_LOCALE ) ;
                break ;
        }

        return $html ? '<span class="' . $class_name . '">' . $status_name . '</span>' : $status_name ;
    }

}

if ( ! function_exists( 'fs_affiliates_get_action_display' ) ) {

    function fs_affiliates_get_action_display( $status , $referral_id , $current_url ) {
        switch ( $status ) {
            case 'fs_link':
                $status_name = __( 'Link' , FS_AFFILIATES_LOCALE ) ;
                break ;
            case 'fs_unlink':
                $status_name = __( 'Unlink' , FS_AFFILIATES_LOCALE ) ;
                break ;
            case 'fs_paid':
                $status_name = __( 'Mark as Paid' , FS_AFFILIATES_LOCALE ) ;
                break ;
            case 'fs_unpaid':
                $status_name = __( 'Mark as Unpaid' , FS_AFFILIATES_LOCALE ) ;
                break ;
            case 'fs_rejected':
                $status_name = __( 'Reject' , FS_AFFILIATES_LOCALE ) ;
                break ;
            case 'pay-via-wallet':
                $status_name = __( 'Pay as Wallet Balance' , FS_AFFILIATES_LOCALE ) ;
                break ;
            case 'paypal':
                $status_name = __( 'Pay via PayPal' , FS_AFFILIATES_LOCALE ) ;
                break ;
            case 'pay-via-reward_points':
                $status_name = __( 'Pay as Reward Points' , FS_AFFILIATES_LOCALE ) ;
                break ;
            case 'edit':
                $status_name = __( 'Edit' , FS_AFFILIATES_LOCALE ) ;
                break ;
            default :
                $status_name = __( 'Delete Permanantly' , FS_AFFILIATES_LOCALE ) ;
                break ;
        }
        if ( $status == 'edit' ) {
            return '<a href="' . esc_url_raw( add_query_arg( array( 'section' => $status , 'id' => $referral_id ) , $current_url ) ) . '">' . $status_name . '</a>' ;
        } elseif ( $status == 'delete' ) {
            return '<a class="fs_affiliates_delete" data-type="referral" style="color:red !important;" href="' . esc_url_raw( add_query_arg( array( 'action' => $status , 'id' => $referral_id ) , $current_url ) ) . '">' . $status_name . '</a>' ;
        } elseif ( 'fs_rejected' == $status ) {
            return '<a class="fs_referral_reject" data-type="referral" data-referral_id= "' . $referral_id . '" href="' . esc_url_raw( add_query_arg( array( 'action' => $status , 'id' => $referral_id ) , $current_url ) ) . '">' . $status_name . '</a>' ;
        } else {
            return '<a href="' . esc_url_raw( add_query_arg( array( 'action' => $status , 'id' => $referral_id ) , $current_url ) ) . '">' . $status_name . '</a>' ;
        }
    }

}

if ( ! function_exists( 'fs_affiliates_get_report_based_on' ) ) {

    function fs_affiliates_get_report_based_on( $key = '' ) {

        $filters_array = array(
            'all'     => __( 'All Time' , FS_AFFILIATES_LOCALE ) ,
            '0 DAY'   => __( 'Today' , FS_AFFILIATES_LOCALE ) ,
            '1 DAY'   => __( 'Yesterday' , FS_AFFILIATES_LOCALE ) ,
            '0 WEEK'  => __( 'This Week' , FS_AFFILIATES_LOCALE ) ,
            '1 WEEK'  => __( 'Last Week' , FS_AFFILIATES_LOCALE ) ,
            '0 MONTH' => __( 'This Month' , FS_AFFILIATES_LOCALE ) ,
            '1 MONTH' => __( 'Last Month' , FS_AFFILIATES_LOCALE ) ,
            '0 YEAR'  => __( 'This Year' , FS_AFFILIATES_LOCALE ) ,
            '1 YEAR'  => __( 'Last Year' , FS_AFFILIATES_LOCALE ) ,
                ) ;

        if ( $key != '' ) {
            return $filters_array[ $key ] ;
        }

        return $filters_array ;
    }

}


if ( ! function_exists( 'fs_affiliates_get_table_name' ) ) {

    function fs_affiliates_get_table_name( $type ) {

        global $wpdb ;

        $table_prefix = $wpdb->prefix ;

        return $type == 'posts' ? $table_prefix . 'posts' : $table_prefix . 'postmeta' ;
    }

}

if ( ! function_exists( 'fs_affiliates_get_default_opt_in_form_fields' ) ) {

    /**
     * Prepare form default fields
     */
    function fs_affiliates_get_default_opt_in_form_fields() {
        static $form_fields ;

        if ( ! isset( $form_fields ) ) {
            $form_fields = array(
                'first_name' => array(
                    'field_name'        => 'First Name' ,
                    'field_key'         => 'first_name' ,
                    'field_status'      => 'enabled' ,
                    'field_required'    => 'optional' ,
                    'field_placeholder' => '' ,
                    'field_description' => '' ,
                    ''
                ) ,
                'last_name'  => array(
                    'field_name'        => 'Last Name' ,
                    'field_key'         => 'last_name' ,
                    'field_status'      => 'enabled' ,
                    'field_required'    => 'optional' ,
                    'field_placeholder' => '' ,
                    'field_description' => ''
                ) ,
                'email'      => array(
                    'field_name'        => 'Email' ,
                    'field_key'         => 'email' ,
                    'field_status'      => 'enabled' ,
                    'field_required'    => 'mandatory' ,
                    'field_placeholder' => '' ,
                    'field_description' => ''
                ) ,
                    ) ;
        }
        return $form_fields ;
    }

}

if ( ! function_exists( 'fs_affiliates_dashboard_menu' ) ) {

    /**
     * sorting dashboard menu
     */
    function fs_affiliates_dashboard_menu( $menus ) {

        $rules = fs_affiliates_get_default_additional_dashboard_tab_settings() ;
        $rules = apply_filters( 'fs_affiliate_get_additional_dashboard_tab_settings' , $rules ) ;

        if ( ! fs_affiliates_check_is_array( $rules ) )
            return $menus ;

        $sort_menus = array() ;

        foreach ( $rules as $tab_key => $tab ) {

            if ( ! isset( $menus[ $tab_key ] ) )
                continue ;

            if ( isset( $menus[ $tab_key ] ) ) {
                $menu = $menus[ $tab_key ] ;
            }

            $sort_menus[ $tab_key ] = $menu ;
        }

        return $sort_menus ;
    }

}
