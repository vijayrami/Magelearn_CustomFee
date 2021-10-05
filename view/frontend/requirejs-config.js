var config = {
	map: {
       "*": {
           'Magento_Checkout/js/model/shipping-save-processor/default' : 'Magelearn_CustomFee/js/model/shipping-save-processor/default'
       },
  	},
    config: {
        mixins: {
            'Magento_Checkout/js/model/shipping-save-processor/payload-extender': {
            'Magelearn_CustomFee/js/model/shipping-save-processor/payload-extender-mixin': true
            }
        }
    }
};