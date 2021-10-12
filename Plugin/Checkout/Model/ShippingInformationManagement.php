<?php
namespace Magelearn\CustomFee\Plugin\Checkout\Model;


class ShippingInformationManagement
{
    /**
     * @var \Magento\Quote\Model\QuoteRepository
     */
    protected $quoteRepository;

    /**
     * @var \Magelearn\CustomFee\Helper\Data
     */
    protected $dataHelper;

    /**
     * @param \Magento\Quote\Model\QuoteRepository $quoteRepository
     * @param \Magelearn\CustomFee\Helper\Data $dataHelper
     */
    public function __construct(
        \Magento\Quote\Model\QuoteRepository $quoteRepository,
        \Magelearn\CustomFee\Helper\Data $dataHelper
    )
    {
        $this->quoteRepository = $quoteRepository;
        $this->dataHelper = $dataHelper;
    }

    /**
     * @param \Magento\Checkout\Model\ShippingInformationManagement $subject
     * @param $cartId
     * @param \Magento\Checkout\Api\Data\ShippingInformationInterface $addressInformation
     */
    public function beforeSaveAddressInformation(
        \Magento\Checkout\Model\ShippingInformationManagement $subject,
        $cartId,
        \Magento\Checkout\Api\Data\ShippingInformationInterface $addressInformation
    )
    {
        $customFee = $addressInformation->getExtensionAttributes()->getFee();
		$Giftfeecheck = $addressInformation->getExtensionAttributes()->getGiftFeeCheck();
        $quote = $this->quoteRepository->getActive($cartId);
		
        if ($customFee) {
            $fee = $this->dataHelper->getCustomFee();
            $quote->setFee($fee);
        } else {
            $quote->setFee(NULL);
        }
		
		if ($Giftfeecheck) {
            $quote->setGiftFeeCheck($Giftfeecheck);
        } else {
            $quote->setGiftFeeCheck(0);
        }
    }
}