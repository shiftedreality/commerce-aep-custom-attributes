<?php
/**
 * ADOBE CONFIDENTIAL
 *
 * Copyright 2024 Adobe
 * All Rights Reserved.
 *
 * NOTICE: All information contained herein is, and remains
 * the property of Adobe and its suppliers, if any. The intellectual
 * and technical concepts contained herein are proprietary to Adobe
 * and its suppliers and are protected by all applicable intellectual
 * property laws, including trade secret and copyright laws.
 * Dissemination of this information or reproduction of this material
 * is strictly forbidden unless prior written permission is obtained
 * from Adobe.
 */
declare(strict_types=1);

namespace Magento\AepCustomAttributes\Plugin\Model;

use Magento\Catalog\Model\Product;
use Magento\DataServices\Model\ProductContext as Subject;
use Magento\Framework\App\ResourceConnection;

class ProductContext
{
    private ?array $brandCache = [];

    /**
     * @param ResourceConnection $resourceConnection
     */
    public function __construct(
        private ResourceConnection $resourceConnection
    ) {
    }

    /**
     * @param Subject $subject
     * @param array $result
     * @param Product $product
     * @return array
     */
    public function afterGetContextData(
        Subject $subject,
        array $result,
        Product $product
    ) {
        $brand = $product->getCustomAttribute('brand');
        if (!empty($brand) && $brand->getValue()) {
            $result['brands'] = ['brand_label_1', 'brand_label_2'];
        }
        return $result;
    }
}
