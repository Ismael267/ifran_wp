/* global ajaxurl, fs_affiliates_generate_payout_params */

jQuery(function ($) {

    var $generatePayoutWrapper = $('.fs_affiliates_affiliates_new').closest('div');

    var generatePayout = {
        init: function () {

            this.trigger_on_page_load( );

            $(document).on('change', 'select[name="referral[payout_method]"]', this.toggleReferralStatus);
            $(document).on('change', 'select[name="referral[affiliate_select_type]"]', this.toggleAffiliate_select);
            $(document).on('change', 'select[name="referral[referral_status]"]', this.togglePaidStatus);
            $(document).on('click', '.fs_affiliates-exporter-button', this.payoutExporter.export);
        }, trigger_on_page_load: function ( ) {
            this.getReferralStatus('select[name="referral[payout_method]"]');
            this.getAffiliate_select('select[name="referral[affiliate_select_type]"]');
        },
        toggleReferralStatus: function (event) {
            event.preventDefault( );
            var $this = $(event.currentTarget);
            generatePayout.getReferralStatus($this);

        },
        togglePaidStatus: function (event) {
            event.preventDefault( );
            var $this = $(event.currentTarget);
            generatePayout.getPaidStatus($this);
        },
        toggleAffiliate_select: function (event) {
            event.preventDefault( );
            var $this = $(event.currentTarget);
            generatePayout.getAffiliate_select($this);
        },
        getReferralStatus: function ($this) {

            if ('direct' === $($this).val() || ('paypal' === $($this).val() && '0' === fs_affiliates_generate_payout_params.is_paypal_payouts_enabled)) {
                $('select[name="referral[referral_status]"]').closest('tr').show();
                generatePayout.getPaidStatus('select[name="referral[referral_status]"]');
            } else {
                $('select[name="referral[referral_status]"]').closest('tr').hide();
                $('input[name="referral[paid_status]"]').closest('tr').hide();
            }
        },
        getPaidStatus: function ($this) {

            if ('fs_unpaid' === $($this).val()) {
                $('input[name="referral[paid_status]"]').closest('tr').show();
            } else {
                $('input[name="referral[paid_status]"]').closest('tr').hide();
            }
        },
        getAffiliate_select: function ($this) {
            if ('include' === $($this).val() || 'exclude' === $($this).val()) {
                $('select[name="referral[selected_affiliate][]"]').closest('tr').show();
            } else {
                $('select[name="referral[selected_affiliate][]"]').closest('tr').hide();
            }
        },
        payoutExporter: {
            export: function () {
                if ($('select[name="referral[payout_method]"]').val() === '') {
                    alert(fs_affiliates_generate_payout_params.payment_select_error_msg);
                } else if (('include' === $('select[name="referral[affiliate_select_type]"]').val() || 'exclude' === $('select[name="referral[affiliate_select_type]"]').val())
                        && $('select[name="referral[selected_affiliate][]"]').val() === null) {
                    alert(fs_affiliates_generate_payout_params.affiliate_select_error_msg);
                } else {
                    $.blockUI.defaults.overlayCSS.cursor = 'wait';
                    generatePayout.block($generatePayoutWrapper);

                    $.ajax({
                        type: 'POST',
                        url: ajaxurl,
                        data: {
                            action: 'fs_affiliates_export_payouts_data',
                            security: fs_affiliates_generate_payout_params.generate_payout_nonce,
                            payoutData: $generatePayoutWrapper.find('table tbody :input').serialize(),
                        },
                        success: function (response) {
                            if ('done' === response.export) {
                                window.location = response.redirect_url;
                            } else if ('processing' === response.export) {
                                var i, j = 1, chunkedData, chunk = 10, step = 0;

                                for (i = 0, j = response.referrals.length; i < j; i += chunk) {
                                    chunkedData = response.referrals.slice(i, i + chunk);
                                    step += chunkedData.length;
                                    generatePayout.payoutExporter.processExport(response.referrals, chunkedData, step);
                                }
                            } else {
                                window.location = response.redirect_url;
                            }
                        },
                        complete: function () {
                            generatePayout.unblock($generatePayoutWrapper);
                        }
                    });
                }
            },
            processExport: function (originalData, chunkedData, step) {

                $.ajax({
                    type: 'POST',
                    url: ajaxurl,
                    data: {
                        action: 'fs_affiliates_process_payouts_data_export',
                        security: fs_affiliates_generate_payout_params.generate_payout_nonce,
                        payoutData: $generatePayoutWrapper.find('table tbody :input').serialize(),
                        originalData: originalData,
                        chunkedData: chunkedData,
                        step: step,
                        generated_data: $('p').find('#exported_data').val(),
                    },
                    success: function (response) {
                        if ('done' === response.export) {
                            window.location = response.redirect_url;
                        } else if ('processing' === response.export) {
                            $('p').find('#exported_data').val(response.generated_data);
                        } else {
                            window.location = response.redirect_url;
                        }
                    }
                });
            },
        },
        block: function (attr) {
            $(attr).block({
                message: null,
                overlayCSS: {
                    background: '#fff',
                    opacity: 0.6
                }
            });
        },
        unblock: function (attr) {
            $(attr).unblock();
        },
    };

    generatePayout.init();
});
