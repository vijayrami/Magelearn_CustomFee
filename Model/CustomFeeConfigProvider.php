<?php
namespace Magelearn\CustomFee\Model;

use Magento\Checkout\Model\ConfigProviderInterface;
use Magento\Quote\Model\Quote;

class CustomFeeConfigProvider implements ConfigProviderInterface
{
    /**
     * @var \Magelearn\CustomFee\Helper\Data
     */
    protected $dataHelper;

    /**
     * @var \Magento\Checkout\Model\Session
     */
    protected $checkoutSession;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;
	
	protected $taxHelper;

    /**
     * @param \Magelearn\CustomFee\Helper\Data $dataHelper
     * @param \Magento\Checkout\Model\Session $checkoutSession
     * @param \Psr\Log\LoggerInterface $logger
	 * @param \Magelearn\CustomFee\Helper\Tax $helperTax
     */
    public function __construct(
        \Magelearn\CustomFee\Helper\Data $dataHelper,
        \Magento\Checkout\Model\Session $checkoutSession,
        \Psr\Log\LoggerInterface $logger,
        \Magelearn\CustomFee\Helper\Tax $helperTax
    )
    {
        $this->dataHelper = $dataHelper;
        $this->checkoutSession = $checkoutSession;
        $this->logger = $logger;
		$this->taxHelper = $helperTax;
    }

    /**
     * @return array
     */
    public function getConfig()
    {
        $customFeeConfig = [];
		
        $enabled = $this->dataHelper->isModuleEnabled();
        $minimumOrderAmount = $this->dataHelper->getMinimumOrderAmount();
		
        $customFeeConfig['fee_label'] = $this->dataHelper->getFeeLabel();
		
        $quote = $this->checkoutSession->getQuote();
        $subtotal = $quote->getSubtotal();
		
        $customFeeConfig['custom_fee_amount'] = $this->dataHelper->getCustomFee();
		
		if ($this->taxHelper->isTaxEnabled() && $this->taxHelper->displayInclTax()) {
            $address = $this->_getAddressFromQuote($quote);
            $customFeeConfig['custom_fee_amount'] = $this->dataHelper->getCustomFee() + $address->getFeeTax();
        }
        if ($this->taxHelper->isTaxEnabled() && $this->taxHelper->displayBothTax()) {

            $address = $this->_getAddressFromQuote($quote);
            $customFeeConfig['custom_fee_amount'] = $this->dataHelper->getCustomFee();
            $customFeeConfig['custom_fee_amount_inc'] = $this->dataHelper->getCustomFee() + $address->getFeeTax();

        }
		$customFeeConfig['displayInclTax'] = $this->taxHelper->displayInclTax();
        $customFeeConfig['displayExclTax'] = $this->taxHelper->displayExclTax();
        $customFeeConfig['displayBoth'] = $this->taxHelper->displayBothTax();
        $customFeeConfig['exclTaxPostfix'] = __('Excl. Tax');
        $customFeeConfig['inclTaxPostfix'] = __('Incl. Tax');
        $customFeeConfig['TaxEnabled'] = $this->taxHelper->isTaxEnabled();
		
		
        $customFeeConfig['show_hide_customfee_block'] = ($enabled && ($minimumOrderAmount <= $subtotal) && $quote->getFee()) ? true : false;
        $customFeeConfig['show_hide_customfee_shipblock'] = ($enabled && ($minimumOrderAmount <= $subtotal)) ? true : false;
        return $customFeeConfig;
    }

	protected function _getAddressFromQuote(Quote $quote)
    {
        return $quote->isVirtual() ? $quote->getBillingAddress() : $quote->getShippingAddress();
    }
}