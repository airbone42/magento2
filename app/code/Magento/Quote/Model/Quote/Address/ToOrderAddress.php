<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Magento\Quote\Model\Quote\Address;

use Magento\Framework\Object\Copy;
use Magento\Quote\Model\Quote\Address;
use Magento\Sales\Api\Data\OrderAddressDataBuilder as OrderAddressBuilder;
use Magento\Sales\Api\Data\OrderAddressInterface;

/**
 * Class ToOrderAddress
 */
class ToOrderAddress
{
    /**
     * @var Copy
     */
    protected $objectCopyService;

    /**
     * @var OrderAddressBuilder|\Magento\Framework\Api\Builder
     */
    protected $orderAddressBuilder;

    /**
     * @param OrderAddressBuilder $orderAddressBuilder
     * @param Copy $objectCopyService
     */
    public function __construct(
        OrderAddressBuilder $orderAddressBuilder,
        Copy $objectCopyService
    ) {
        $this->orderAddressBuilder = $orderAddressBuilder;
        $this->objectCopyService = $objectCopyService;
    }

    /**
     * @param Address $object
     * @param array $data
     * @return OrderAddressInterface
     */
    public function convert(Address $object, $data = [])
    {
        $orderAddressData = $this->objectCopyService->getDataFromFieldset(
            'quote_convert_address',
            'to_order_address',
            $object
        );

        return $this->orderAddressBuilder
            ->populateWithArray(array_merge($orderAddressData, $data))
            ->create();
    }
}
