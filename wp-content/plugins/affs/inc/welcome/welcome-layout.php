<script>
    jQuery( document ).ready( function () {
        jQuery( '.tab_1' ).click( function () {
            jQuery( '.tab_1' ).addClass( 'active_welcome' ) ;
            jQuery( '.tab_2' ).removeClass( 'active_welcome' ) ;
            jQuery( '.tab_3' ).removeClass( 'active_welcome' ) ;
            jQuery( '.con_1' ).show() ;
            jQuery( '.con_2' ).hide() ;
            jQuery( '.con_3' ).hide() ;
        } ) ;
        jQuery( '.tab_2' ).click( function () {
            jQuery( '.tab_2' ).addClass( 'active_welcome' ) ;
            jQuery( '.tab_1' ).removeClass( 'active_welcome' ) ;
            jQuery( '.tab_3' ).removeClass( 'active_welcome' ) ;
            jQuery( '.con_1' ).hide() ;
            jQuery( '.con_2' ).show() ;
            jQuery( '.con_3' ).hide() ;
        } ) ;
        jQuery( '.tab_3' ).click( function () {
            jQuery( '.tab_3' ).addClass( 'active_welcome' ) ;
            jQuery( '.tab_1' ).removeClass( 'active_welcome' ) ;
            jQuery( '.tab_2' ).removeClass( 'active_welcome' ) ;
            jQuery( '.con_1' ).hide() ;
            jQuery( '.con_2' ).hide() ;
            jQuery( '.con_3' ).show() ;
        } ) ;
    } ) ;
</script>
<div class=" welcome_page">
    <div class="welcome_header" >
        <div class="welcome_title" >
            <h1>Welcome to <strong>SUMO Affiliates Pro </strong></h1>
        </div>
        <div class="branding_logo" >
            <a href="http://fantasticplugins.com/" target="_blank" ><img src="<?php echo FS_AFFILIATES_PLUGIN_URL ; ?>/assets/images/welcome-page/Fantastic-Plugins-final-Logo.png" alt="" /></a>
        </div>
    </div>

    <p>
        Thanks for installing SUMO Affiliates Pro...
    </p>

    <div class="welcomepage_tab">
        <ul>
            <li><a href="#about" class="tab_1 active_welcome">About Plugin</a></li>
            <li><a href="#compatibl-plugins" class="tab_2">Compatible Plugins</a></li>
            <li><a href="#other-plugins" class="tab_3">Our Other Plugins</a></li>
        </ul>
        <a href="<?php echo admin_url( 'admin.php?page=fs_affiliates' ) ; ?>" class="admin_btn" >Go to Settings</a>
        <a href="http://fantasticplugins.com/support/" class="support_btn" target="_blank" >Contact Support</a>
    </div>

    <!--            about SUMO tab content      -->

    <div class="con_1">
        <div class="section_1">
            <div class='section_a1'>
                <h3>Features Offered By SUMO Affiliates Pro</h3>
                <ul>
                    <li>Affiliate system for WordPress</li>
                    <li>Advanced integration with WooCommerce</li>
                    <li>Affiliate commission for form submission through
                        <ul>
                            <li>- Contact Form7</li>
                            <li>- Formidable Forms</li>
                            <li>- WP Forms</li>
                        </ul>
                    </li>
                    <li>Affiliate commission for email subscription through
                          <ul>
                            <li>- MailChimp</li>
                            <li>- ActiveCampaign</li>
                           </ul>   
                    </li>
                     <li>Affiliate commission for accessing individual pages/posts which has landing commission shortcodes</li>
                    <li>Affiliate registration form for users</li>
                    <li>Option for the site admin to place restrictions on who can become as an affiliate</li>
                    <li>Users can attach documents while submitting the affiliate registration form</li>
                    <li>Option for the user to directly become as an affiliate while creating an account through WooCommerce</li>
                    <li>Users with an existing account on the site can also become as an affiliate</li>
                    <li>When a logged in user tries to become an affiliate, site admin has the option to
                        <ul>
                            <li>- Manage the affiliate account within the existing account</li>
                            <li>- Create a separate account for affiliate promotion</li>
                            <li>- Let the user decide</li>
                        </ul>
                    </li>
                    <li>Option for the site admin to charge a one-time fee/recurring fee for being an affiliate</li>
                    <li>Option for the site admin to award one-time bonus commission for registering as an affiliate</li>
                    <li>Option for the site admin to notify and get notified about the affiliate activities via SMS and email</li>
                    <li>A separate table for the site admin to manage the affiliates</li>
                    <li>Option for the site admin to automatically approve all the affiliate applications / approve after review</li>
                    <li>Separate dashboard for the affiliate to manage the affiliate promotion</li>
                    <li>Option for the site admin to create unlimited additional tabs in the affiliate dashboard</li>
                    <li>Affiliates can generate unlimited affiliate links.</li>
                    <li>QR code can be generated for affiliate links and can be downloaded as an image</li>
                    <li>Affiliate link validity can be restricted to the product for which the link was generated</li>
                    <li>Refer a friend form for affiliates.</li>
                    <li>Option for the site admin to create unique landing pages for affiliates</li>
                    <li>The validity of affiliate links can be customized by the site admin</li>
                    <li>Option for the site admin to identify the affiliate based on
                        <ul>
                            <li>- Affiliate ID</li>
                            <li> - Affiliate Name</li>
                           </ul> 
                    </li>
                    <li>Option for the site admin to allow their affiliates to customize their affiliate slug</li>
                    <li>Option for the site admin to allow affiliates to generate readable affiliate links(Pretty affiliate links)</li>
                    <li>Your affiliates can promote the products on your site without using an affiliate link</li>
                    <li>Social buttons for affiliate promotion</li>
                    <li>MLM system for affiliates</li>
                    <li>The number of direct referrals, number of levels to award MLM commission and commission rate for each level can be customized</li>
                    <li>Account Signup Affiliate Commission</li>
                    <li>Affiliate Signup Commission</li>
                    <li>WooCommerce Product Purchase Commission</li>
                    <li>Option for the site admin to set commission rate for individual products at the affiliate level</li>
                    <li>Affiliates can receive referral commission when their referrals use the WooCommerce coupons which are linked to them</li>
                    <li>Option for the site admin to award lifetime commission to the affiliates for the purchases made by their referrals</li>
                    <li>Separate table to capture the URLs that were accessed using an affiliate link</li>
                    <li>The Conversion status of the affiliate links will be captured</li>
                    <li>Separate table to capture the referral actions which got converted.</li>
                    <li>Option for the site admin to approve the referrals automatically/ approve the referrals after review.</li>
                    <li>Referral commissions can be restricted for specific products/categories.</li>
                    <li>Option for the site admin to stop awarding the commission to the affiliate if
                         <ul>
                            <li>- The referred user has exceeded the number of orders specified</li>
                            <li>- The referred user has exceeded the amount to be spent on the site</li>
                            <li>- The referred user has exceeded the amount to be spent on one order</li>
                        </ul>
                    </li>
                    <li>Option for the site admin to allow their users to select an affiliate during checkout. So that, the affiliate commission for that purchase will be awarded to that affiliate</li>
                    <li>Option for the site admin to allow their affiliates to view the order details of their referrals</li>
                    <li>Option for the site admin to earn commissions for the purchases made using their own affiliate links</li>
                    <li>When a user uses multiple affiliate links to complete a referral action, site admin has the option to
                        <ul>
                            <li>- Award commission for the first affiliate</li>
                            <li>- Award commission for the most recent affiliate </li>
                           </ul> 
                    </li>
                    <li> Option for the site admin to set a maximum commission amount which can be allowed for any referral action</li>
                    <li>Site admin can process referral payment for their affiliates using any one of the payment methods listed below
                        <ul>
                            <li>- PayPal Payouts</li>
                            <li>- Bank Transfer  </li>
                            <li>- Affiliate Wallet </li>
                            <li>- Reward Points(Requires SUMO Reward Points)</li>
                        </ul>
                    </li>
                    <li>Option for the site admin to attach files in the emails sent to the affiliates</li>
                    <li>Option for the site admin to automatically generate and send payout statements as a PDF file in the payout emails</li>
                    <li>Option for the site admin and affiliate to be notified via pushover notification for referral actions</li>
                    <li>Option for the site admin to display a leaderboard of the affiliates</li>
                    <li>Option for the site admin and the affiliate to view detailed reports about affiliate promotion on the site</li>
                    <li>Option for the site admin to send periodic reports via email to their affiliates</li>
                    <li>Option for the site admin to export the following data as CSV
                        <ul>
                            <li>- Affiliates</li>
                            <li>- Visits</li>
                            <li>- Referrals</li>
                            <li>- Payouts</li>
                        </ul>
                    </li>
                    <li>Option for the site admin to create promotional banners which can be used by affiliates for promoting the site</li>
                    <li> SUMO Reward Points
                        <ul>
                            <li>Affiliate commissions can be awarded as Reward Points(Requires SUMO Reward Points Plugin)</li>
                        </ul>
                    </li>
                    <li>SUMO Subscriptions
                    <ul>
                            <li>Option for the site admin to award affiliate commission for</li>
                            <li>- Only initial payments</li>
                            <li>- Both initial and renewal payments</li>
                        </ul>
                    </li>
                    <li>SUMO Payment Plans
                        <ul>
                            <li>Payment plan product's affiliate commission will be awarded once the final payment for the product has been received</li>
                        </ul>
                    </li>
                    <li>SUMO Pre-Orders
                        <ul>
                            <li>Option for the site admin to award commission for </li>
                            <li>- Pay Upfront products</li>
                            <li>- Pay on Release products</li>
                        </ul>
                    </li>
                    <li>WooCommerce Subscriptions
                        <ul>
                            <li>Option for the site admin to award affiliate commission for</li>
                            <li>- Only initial payments</li>
                            <li>- Both initial and renewal payments</li>
                        </ul>
                    </li>
                    <li>WooCommerce Recover Abandoned Cart
                         <ul>
                            <li>When an order is recovered, the affiliate associated with that order will be awarded commission</li>
                        </ul>
                    </li>
                    <li>Fraud Protection Tools</li>
                    <li>Highly customizable</li>
                    <li>An Extensive list of shortcodes</li>
                    <li>Translation ready</li>
                    <li> And More</li>
                </ul>
            </div>
        </div>
    </div>


    <!-- compatiblity plugins  -->

    <div class="con_2">
        <div class="con2_title">
            <h2>SUMO Affiliates Pro is Compatible with</h2>
        </div>
        <div class="feature">
            <div class="two_fet_img">
                <a href="https://codecanyon.net/item/sumo-reward-points-woocommerce-reward-system/7791451?ref=FantasticPlugins" target="_blank" >
                    <div class="Brand_1">
                        <img src="<?php echo FS_AFFILIATES_PLUGIN_URL ; ?>/assets/images/welcome-page/Sumo_Reward_Points.png" alt=""/>
                        <div class="hide">
                            <h4>SUMO Reward points</h4>
                        </div>
                        <div class="description_adv">
                            <p><strong>SUMO Reward points</strong> is a WooCommerce Loyalty Reward points System. Using <b>SUMO Reward points</b>, you can offer Reward points to your customers for Account Sign Up, Product Purchases, Writing Reviews etc.</p>
                        </div>
                        <div class="buy_now">
                            <button type="button">Buy Now</button>
                        </div>
                    </div>
                </a>
                <a href="https://codecanyon.net/item/sumo-subscriptions-woocommerce-subscription-system/16486054?ref=FantasticPlugins" target="_blank" >
                    <div class="Brand_1">
                        <img src="<?php echo FS_AFFILIATES_PLUGIN_URL ; ?>/assets/images/welcome-page/sumo_subscription.png" alt=""/>
                        <div class="hide">
                            <h4>SUMO Subscriptions</h4>
                        </div>
                        <div class="description_adv">
                            <p><strong>SUMO Subscriptions</strong> is a subscription extension for WooCommerce. Using <b>SUMO Subscriptions</b>, you can create and sell subscription products in your existing WooCommerce shop.</p>
                        </div>
                        <div class="buy_now">
                            <button type="button">Buy Now</button>
                        </div>
                    </div>
                </a>
            </div>

            <div class="two_fet_img">
                <a href="https://codecanyon.net/item/sumo-woocommerce-payment-plans-deposits-down-payments-installments-variable-payments-etc/21244868?ref=FantasticPlugins" target="_blank" >
                    <div class="Brand_1">
                        <img src="<?php echo FS_AFFILIATES_PLUGIN_URL ; ?>/assets/images/welcome-page/sumo-payment-plans.png" alt=""/>
                        <div class="hide">
                            <h4>SUMO Payment Plans</h4>
                        </div>
                        <div class="description_adv">
                            <p><strong>SUMO Payment Plans</strong> is a WooCommerce Payment Plan plugin using which you can configure multiple Payment Plans like Deposits with Balance Payment, Fixed Amount Installments, Variable Amount Installments, Down Payments with Installments, etc in your WooCommerce Shop. </p>
                        </div>
                        <div class="buy_now">
                            <button type="button">Buy Now</button>
                        </div>
                    </div>
                </a>
                <a href="https://codecanyon.net/item/sumo-woocommerce-preorders/21607535?ref=FantasticPlugins" target="_blank" >
                    <div class="Brand_1">
                        <img src="<?php echo FS_AFFILIATES_PLUGIN_URL ; ?>/assets/images/welcome-page/sumo-pre-orders.png" alt=""/>
                        <div class="hide">
                            <h4>SUMO Pre-Orders</h4>
                        </div>
                        <div class="description_adv">
                            <p><strong>SUMO Pre-Orders</strong> is a WooCommerce Extension. Using this plugin, you can allow your customers to pre-order the products on your WooCommerce shop before they are available.</p>
                        </div>
                        <div class="buy_now">
                            <button type="button">Buy Now</button>
                        </div>
                    </div>
                </a>
            </div>
        </div>
        
        <div class="feature">
            <div class="two_fet_img">
                <a href="https://codecanyon.net/item/woocommerce-recover-abandoned-cart/7715167?ref=FantasticPlugins" target="_blank" >
                    <div class="Brand_1">
                        <img src="<?php echo FS_AFFILIATES_PLUGIN_URL ; ?>/assets/images/welcome-page/Recover_abandoned_cart.png" alt=""/>
                        <div class="hide">
                            <h4>Recover Abandoned Cart</h4>
                        </div>
                        <div class="description_adv">
                            <p><strong>Recover Abandoned Cart</strong> is a WooCommerce Extension. Using <b>Recover Abandoned Cart</b>, you can send follow up emails with Purchase links to users who have Abandoned their Purchase.</p>
                        </div>
                        <div class="buy_now">
                            <button type="button">Buy Now</button>
                        </div>
                    </div>
                </a>
            </div>
        </div>
        
        
    </div>


    <!--            our other plugins tab content      -->

    <div class="con_3">
        <div class="con2_title">
            <h2>Our Other WooCommerce Plugins</h2>
        </div>
        <div class="feature">
            <div class="two_fet_img">


                
                <a href="https://codecanyon.net/item/sumo-affiliates-woocommerce-affiliate-system/18273930?ref=FantasticPlugins" target="_blank" >
                    <div class="Brand_1">
                        <img src="<?php echo FS_AFFILIATES_PLUGIN_URL ; ?>/assets/images/welcome-page/sumo_affiliates.png" alt=""/>
                        <div class="hide">
                            <h4>SUMO Affiliates</h4>
                        </div>
                        <div class="description_adv">
                            <p><strong>SUMO Affiliates</strong> is a Affiliate System for WooCommerce. Using <b>SUMO Affiliates</b> you can run Affiliate Promotions in your existing WooCommerce Shop.</p>
                        </div>
                        <div class="buy_now">
                            <button type="button">Buy Now</button>
                        </div>
                    </div>
                </a>
                <a href="https://codecanyon.net/item/woocommerce-pay-your-price/7000238?ref=FantasticPlugins" target="_blank" >
                    <div class="Brand_1">
                        <img src="<?php echo FS_AFFILIATES_PLUGIN_URL ; ?>/assets/images/welcome-page/pay_your_price.png" alt=""/>
                        <div class="hide">
                            <h4>Pay Your Price</h4>
                        </div>
                        <div class="description_adv">
                            <p><strong>Pay Your Price</strong> is a WooCommerce Extension. Using <b>Pay Your Price</b>, Users can pay their own price for the Products. </p>
                        </div>
                        <div class="buy_now">
                            <button type="button">Buy Now</button>
                        </div>
                    </div>
                </a>
            </div>

            <div class="two_fet_img">
                
                <a href="https://codecanyon.net/item/sumo-donations-woocommerce-donation-system/12283878?ref=FantasticPlugins" target="_blank" >
                    <div class="Brand_1">
                        <img src="<?php echo FS_AFFILIATES_PLUGIN_URL ; ?>/assets/images/welcome-page/Sumo_Donation.png" alt=""/>
                        <div class="hide">
                            <h4>SUMO Donations</h4>
                        </div>
                        <div class="description_adv">
                            <p><strong>SUMO Donations</strong> is a complete WooCommerce Donation System. Using <b>SUMO Donations</b>, you can provide options for your users to make donations to your site.</p>
                        </div>
                        <div class="buy_now">
                            <button type="button">Buy Now</button>
                        </div>
                    </div>
                </a>
                <a href="https://codecanyon.net/item/sumo-coupons-woocommerce-coupon-system/16082070?ref=FantasticPlugins" target="_blank" >
                    <div class="Brand_1">
                        <img src="<?php echo FS_AFFILIATES_PLUGIN_URL ; ?>/assets/images/welcome-page/sumo_coupons.png" alt=""/>
                        <div class="hide">
                            <h4>SUMO Coupons</h4>
                        </div>
                        <div class="description_adv">
                            <p><strong>SUMO Coupons</strong>  is a WooCommerce Loyalty Coupon System. Using <b>SUMO Coupons</b> you can offer coupons to your customers for Account Sign Up, Product Purchases, Writing Reviews etc. </p>
                        </div>
                        <div class="buy_now">
                            <button type="button">Buy Now</button>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        <div class="feature">

            <div class="two_fet_img">
                
                <a href="https://codecanyon.net/item/woocommerce-paypal-payouts/21338878?ref=FantasticPlugins" target="_blank" >
                    <div class="Brand_1">
                        <img src="<?php echo FS_AFFILIATES_PLUGIN_URL ; ?>/assets/images/welcome-page/woocommerce-paypal-payouts.png" alt=""/>
                        <div class="hide">
                            <h4>WooCommerce PayPal Payouts</h4>
                        </div>
                        <div class="description_adv">
                            <p><strong>WooCommerce PayPal Payouts</strong> is a WooCommerce Extension. Using this plugin, the Payments made by the user can be split between multiple PayPal Accounts(Maximum of 500 Receivers). The main(Admin) PayPal Account should be a Business account to use WooCommerce PayPal Payouts.</p>
                        </div>
                        <div class="buy_now">
                            <button type="button">Buy Now</button>
                        </div>
                    </div>
                </a>
                <a href="https://codecanyon.net/item/sumo-memberships-woocommerce-membership-system/16642362?ref=FantasticPlugins" target="_blank" >
                    <div class="Brand_1">
                        <img src="<?php echo FS_AFFILIATES_PLUGIN_URL ; ?>/assets/images/welcome-page/sumo_membership.png" alt=""/>
                        <div class="hide">
                            <h4>SUMO Memberships </h4>
                        </div>
                        <div class="description_adv">
                            <p><strong>SUMO Memberships </strong>is a membership extension for WooCommerce. Using <b>SUMO Memberships</b>, you can restrict/provide access to specific Pages, Posts, Products, URL.</p>
                        </div>
                        <div class="buy_now">
                            <button type="button">Buy Now</button>
                        </div>
                    </div>
                </a>
            </div>
            <div class="two_fet_img">
                
                <a href="https://codecanyon.net/item/sumo-discounts-advanced-pricing-woocommerce-discount-system/17116628?ref=FantasticPlugins" target="_blank" >
                    <div class="Brand_1">
                        <img src="<?php echo FS_AFFILIATES_PLUGIN_URL ; ?>/assets/images/welcome-page/Sumo-dynamic-pricing-discounts.png" alt=""/>
                        <div class="hide">
                            <h4>SUMO Dynamic Pricing Discounts</h4>
                        </div>
                        <div class="description_adv">
                            <p><strong>SUMO Discounts</strong> is a WooCommerce Extension Plugin. Using <b>SUMO Discounts</b> plugin you can provide discounts to your users in multiple ways.</p>
                        </div>
                        <div class="buy_now">
                            <button type="button">Buy Now</button>
                        </div>
                    </div>
                </a>
                <a href="https://codecanyon.net/item/sumo-woocommerce-bookings-appointments-reservations-events-google-calendar-etc/21522378?ref=FantasticPlugins" target="_blank" >
                    <div class="Brand_1">
                        <img src="<?php echo FS_AFFILIATES_PLUGIN_URL ; ?>/assets/images/welcome-page/sumo-woocommerce-bookings.png" alt=""/>
                        <div class="hide">
                            <h4>SUMO Bookings</h4>
                        </div>
                        <div class="description_adv">
                            <p><strong>SUMO Bookings</strong>   is a Comprehensive Woocommerce Booking System which helps you to Configure Bookings, Reservations, Appoinments, Events in your Existing Woocommerce Shop.</p>
                        </div>
                        <div class="buy_now">
                            <button type="button">Buy Now</button>
                        </div>
                    </div>
                </a>     
            </div>
        </div>

        <div class="feature">

            <div class="two_fet_img">
                
                <a href="https://codecanyon.net/item/sumo-woocommerce-measurement-price-calculator/21637332?ref=FantasticPlugins" target="_blank" >
                    <div class="Brand_1">
                        <img src="<?php echo FS_AFFILIATES_PLUGIN_URL ; ?>/assets/images/welcome-page/sumo-woocommerce-measurement-price-calculator.png" alt=""/>
                        <div class="hide">
                            <h4>SUMO WooCommerce Measurement Price Calculator</h4>
                        </div>
                        <div class="description_adv">
                            <p><strong>SUMO WooCommerce Measurement Price Calculator</strong> is a WooCommerce Extension. Using this plugin, the Price/Quantity of the Product can be calculated based on the Measurement(s) provided by the user.</p>
                        </div>
                        <div class="buy_now">
                            <button type="button">Buy Now</button>
                        </div>
                    </div>
                </a>
                
                <a href="https://codecanyon.net/item/woocommerce-paypal-website-payments-pro-hosted-solution/21683615?ref=FantasticPlugins" target="_blank" >
                    <div class="Brand_1">
                        <img src="<?php echo FS_AFFILIATES_PLUGIN_URL ; ?>/assets/images/welcome-page/woocommerce-paypal-website-payments-pro-hosted-solutions.png" alt=""/>
                        <div class="hide">
                            <h4>WooCommerce PayPal Website Payments Pro Hosted Solutions</h4>
                        </div>
                        <div class="description_adv">
                            <p><strong>WooCommerce PayPal Website Payments Pro Hosted Solutions</strong>  allows WooCommerce Shop Owners to securely accept credit and debit cards or PayPal payments without capturing or storing card information on your site.</p>
                        </div>
                        <div class="buy_now">
                            <button type="button">Buy Now</button>
                        </div>
                    </div>
                </a>
            </div>
            <div class="two_fet_img">
                
                <a href="https://codecanyon.net/item/woocommerce-paypal-express-checkout-and-paypal-credit/21739537?ref=FantasticPlugins" target="_blank" >
                    <div class="Brand_1">
                        <img src="<?php echo FS_AFFILIATES_PLUGIN_URL ; ?>/assets/images/welcome-page/woocommerce-paypal-express-checkout.png" alt=""/>
                        <div class="hide">
                            <h4>WooCommerce PayPal Express Checkout</h4>
                        </div>
                        <div class="description_adv" style="min-height:180px !important">
                            <p><strong>WooCommerce PayPal Express Checkout</strong> provides flexibility for customers to checkout directly from Product, Cart, Checkout Pages without leaving the site.</p>
                        </div>
                        <div class="buy_now">
                            <button type="button">Buy Now</button>
                        </div>
                    </div>
                </a>
                <a href="https://codecanyon.net/item/woocommerce-square-payment-gateway/21826955?ref=FantasticPlugins" target="_blank" >
                    <div class="Brand_1">
                        <img src="<?php echo FS_AFFILIATES_PLUGIN_URL ; ?>/assets/images/welcome-page/wc-square-payment-gateway.png" alt=""/>
                        <div class="hide">
                            <h4>WooCommerce Square Payment Gateway</h4>
                        </div>
                        <div class="description_adv">
                            <p><strong>WooCommerce Square Payment Gateway</strong>  allows customers to make payment for their Orders using their Credit Card without leaving the site.</p>
                        </div>
                        <div class="buy_now">
                            <button type="button">Buy Now</button>
                        </div>
                    </div>
                </a>
            </div>
        </div>
        <div class="feature">

            <div class="two_fet_img">
                
                <a href="https://codecanyon.net/item/gdpr-compliance-suite-wordpress-plugin/21886762?ref=FantasticPlugins" target="_blank" >
                    <div class="Brand_1">
                        <img src="<?php echo FS_AFFILIATES_PLUGIN_URL ; ?>/assets/images/welcome-page/GDPR-Compliance.png" alt=""/>
                        <div class="hide">
                            <h4>WP GDPR Compliance Suite</h4>
                        </div>
                        <div class="description_adv">
                            <p><strong>WP GDPR Compliance Suite</strong>  is a comprehensive WordPress Plugin that provides tools to make your WordPress Site compliant with GDPR(General Data Protection Regulation).</p>
                        </div>
                        <div class="buy_now">
                            <button type="button">Buy Now</button>
                        </div>
                    </div>
                </a>
                <a href="https://codecanyon.net/item/woocommerce-paypal-braintree/22045342?ref=FantasticPlugins" target="_blank" >
                    <div class="Brand_1">
                        <img src="<?php echo FS_AFFILIATES_PLUGIN_URL ; ?>/assets/images/welcome-page/wc_paypal_braintree.png" alt=""/>
                        <div class="hide">
                            <h4>WooCommerce PayPal Braintree</h4>
                        </div>
                        <div class="description_adv">
                            <p><strong>WooCommerce PayPal Braintree</strong> allows you to accept Credit Cards and PayPal Payments via Braintree..</p>
                        </div>
                        <div class="buy_now">
                            <button type="button">Buy Now</button>
                        </div>
                    </div>
                </a>
            </div>
            <div class="two_fet_img">
                
                <a href="https://codecanyon.net/item/woocommerce-product-image-watermark/22108847?ref=FantasticPlugins" target="_blank" >
                    <div class="Brand_1">
                        <img src="<?php echo FS_AFFILIATES_PLUGIN_URL ; ?>/assets/images/welcome-page/wc-product-image-watermark-feature.png" alt=""/>
                        <div class="hide">
                            <h4>WooCommerce Product Image Watermark</h4>
                        </div>
                        <div class="description_adv">
                            <p><strong>WooCommerce Product Image Watermark</strong> is a WooCommerce Extension which allows you to add Text/Image Watermark for WooCommerce Product Images.</p>
                        </div>
                        <div class="buy_now">
                            <button type="button">Buy Now</button>
                        </div>
                    </div>
                </a>
                <a href="https://codecanyon.net/item/woocommerce-square/22136956?ref=FantasticPlugins" target="_blank" >
                    <div class="Brand_1">
                        <img src="<?php echo FS_AFFILIATES_PLUGIN_URL ; ?>/assets/images/welcome-page/woocommerce-square-feature-image.png" alt=""/>
                        <div class="hide">
                            <h4>WooCommerce Square Full Integration</h4>
                        </div>
                        <div class="description_adv">
                            <p><strong>WooCommerce Square Full Integration</strong>  allows you to Automatically/Manually synchronize your Products, Product Images, Product Categories and Inventory between your WooCommerce Shop and Square Account.</p>
                        </div>
                        <div class="buy_now">
                            <button type="button">Buy Now</button>
                        </div>
                    </div>
                </a>
            </div>
        </div>
        <div class="feature">
            <div class="two_fet_img">
                
                <a href="https://codecanyon.net/item/sumo-woocommerce-waitlist/22224846?ref=FantasticPlugins" target="_blank" >
                    <div class="Brand_1">
                        <img src="<?php echo FS_AFFILIATES_PLUGIN_URL ; ?>/assets/images/welcome-page/sumo-waitlist-feature-image.png" alt=""/>
                        <div class="hide">
                            <h4>SUMO Waitlist</h4>
                        </div>
                        <div class="description_adv">
                            <p><strong>SUMO Waitlist</strong> is a WooCommerce Plugin which allows users to subscribe to a waitlist for Out of Stock Products. The Users will be notified by email when the Product is again Back In Stock for Purchase..</p>
                        </div>
                        <div class="buy_now">
                            <button type="button">Buy Now</button>
                        </div>
                    </div>
                </a>
                <a href="https://codecanyon.net/item/sumo-woocommerce-custom-registration-fields/22320683?ref=FantasticPlugins" target="_blank" >
                    <div class="Brand_1">
                        <img src="<?php echo FS_AFFILIATES_PLUGIN_URL ; ?>/assets/images/welcome-page/sumo-woocommerce-custom-registration-fields-feature-image.png" alt=""/>
                        <div class="hide">
                            <h4>SUMO WooCommerce Custom Registration Fields</h4>
                        </div>
                        <div class="description_adv">
                            <p>  This plugin allows you to add additional fields on WooCommerce Registration page. Users can also register on your site using 15+ social networks.</p>
                        </div>
                        <div class="buy_now">
                            <button type="button">Buy Now</button>
                        </div>
                    </div>
                </a>
            </div>
            <div class="two_fet_img">
                
                <a href="https://codecanyon.net/item/sumo-woocommerce-currency-switcher/22541679?ref=FantasticPlugins" target="_blank" >
                    <div class="Brand_1">
                        <img src="<?php echo FS_AFFILIATES_PLUGIN_URL ; ?>/assets/images/welcome-page/sumo-wc-currency-switcher-feature-image.png" alt=""/>
                        <div class="hide">
                            <h4>SUMO WooCommerce Currency Switcher</h4>
                        </div>
                        <div class="description_adv">
                            <p>SUMO WooCommerce Currency Switcher allows your customers to view and pay on your shop using their preferred currency.</p>
                        </div>
                        <div class="buy_now">
                            <button type="button">Buy Now</button>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>
