<?php
namespace Magelearn\CustomFee\Observer;

use Magento\Framework\Event\Observer as EventObserver;
use Magento\Framework\Event\ObserverInterface;

class AddFeeToOrderObserver implements ObserverInterface
{
	/**
     * @var \Magento\Framework\DataObject\Copy
     */
    protected $objectCopyService;
	
	/**
     * AddFeeToOrderObserver constructor.
     */
     /**
     * @param \Magento\Framework\DataObject\Copy $objectCopyService
     */
    public function __construct(
        \Magento\Framework\DataObject\Copy $objectCopyService
    ) {
		$this->objectCopyService = $objectCopyService;
    }
	
    /**
     * Set payment fee to order
     *
     * @param EventObserver $observer
     * @return $this
     */
    public function execute(EventObserver $observer)
    {
        /** @var \Magento\Sales\Model\Order $order */
        $order = $observer->getEvent()->getData('order');
        /** @var \Magento\Quote\Model\Quote $quote */
        $quote = $observer->getEvent()->getData('quote');
		
        $CustomFeeFee = $quote->getFee();
		
        if (!$CustomFeeFee) {
            return $this;
        }
        $order->setFee($CustomFeeFee);
		$order->setGiftFee($quote->getGiftFee());
		$this->objectCopyService->copyFieldsetToTarget('sales_convert_quote', 'to_order', $quote, $order);
        return $this;
    }
}