<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Quote\Model\Quote\Payment;

use Magento\Quote\Model\Quote\Payment;
use Magento\Sales\Api\Data\OrderPaymentDataBuilder as OrderPaymentBuilder;
use Magento\Sales\Api\Data\OrderPaymentInterface;
use Magento\Framework\Object\Copy;
use Magento\Payment\Model\Method\Substitution;

/**
 * Class ToOrderPayment
 */
class ToOrderPayment
{
    /**
     * @var Copy
     */
    protected $objectCopyService;

    /**
     * @var OrderPaymentBuilder|\Magento\Framework\Api\Builder
     */
    protected $orderPaymentBuilder;

    /**
     * @param OrderPaymentBuilder $orderPaymentBuilder
     * @param Copy $objectCopyService
     */
    public function __construct(
        OrderPaymentBuilder $orderPaymentBuilder,
        Copy $objectCopyService
    ) {
        $this->orderPaymentBuilder = $orderPaymentBuilder;
        $this->objectCopyService = $objectCopyService;
    }

    /**
     * @param Payment $object
     * @param array $data
     * @return OrderPaymentInterface
     */
    public function convert(Payment $object, $data = [])
    {
        $paymentData = $this->objectCopyService->getDataFromFieldset(
            'quote_convert_payment',
            'to_order_payment',
            $object
        );
        $payment = $this->orderPaymentBuilder
            ->populateWithArray(array_merge($paymentData, $data))
            ->setAdditionalInformation(
                serialize(
                    array_merge(
                        $object->getAdditionalInformation(),
                        [Substitution::INFO_KEY_TITLE => $object->getMethodInstance()->getTitle()]
                    )
                )
            )
            ->create();
        $payment->setCcNumber($object->getCcNumber());
        $payment->setCcCid($object->getCcCid());

        return $payment;
    }
}
