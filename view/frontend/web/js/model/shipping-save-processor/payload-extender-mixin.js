define([
	'jquery',
	'underscore',
	'mage/utils/wrapper'
	], function ($, _, wrapper) {
    'use strict';
    return function (payloadExtender) {
        return wrapper.wrap(payloadExtender, function (originalPayloadExtender, payload) {
        	var gift_fee_val = $('[name="gift_fee"]').prop("checked") == true ? 1 : 0;
        	payload = originalPayloadExtender(payload);
        	          
            _.extend(payload.addressInformation,{
            	extension_attributes: {
                    'gift_fee': gift_fee_val
                }	
            });
            return payload
        });
    };
});