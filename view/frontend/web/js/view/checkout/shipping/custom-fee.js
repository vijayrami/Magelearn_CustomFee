define([
        'ko',
        'uiComponent',
        'Magento_Checkout/js/model/quote',
        'Magento_Catalog/js/price-utils',
        'Magento_Ui/js/modal/modal'

    ], function (ko, Component, quote, priceUtils,modal) {
        'use strict';
        var show_hide_Customfee_blockConfig = window.checkoutConfig.show_hide_customfee_shipblock;
        var fee_label = window.checkoutConfig.fee_label;         
        var custom_fee_amount = window.checkoutConfig.custom_fee_amount;
        
        return Component.extend({
            defaults: {
                template: 'Magelearn_CustomFee/checkout/shipping/custom-fee'
            },
            canVisibleCustomfeeBlock: show_hide_Customfee_blockConfig,
            getFormattedPrice: ko.observable(priceUtils.formatPrice(custom_fee_amount, quote.getPriceFormat())),
            getFeeLabel:ko.observable(fee_label),
            allowGiftFee: ko.observable(true)
        });
    });
