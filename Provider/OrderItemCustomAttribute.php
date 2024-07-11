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

namespace Magento\AepCustomAttributes\Model\Provider;

use Magento\Framework\Serialize\Serializer\Json;

class OrderItemCustomAttribute
{
    /**
     * @var Json
     */
    private Json $jsonSerializer;

    /**
     * @var string
     */
    private string $usingField = '';

    /**
     * @param Json $jsonSerializer
     * @param string $usingField
     */
    public function __construct(
        Json $jsonSerializer,
        string $usingField
    ) {
        $this->jsonSerializer = $jsonSerializer;
        $this->usingField = $usingField;
    }

    /**
     * Getting additional attributes data.
     *
     * @param array $values
     * @return array
     */
    public function get(array $values): array
    {
        $output = [];
        $values = $this->flatten($values);

        foreach ($values as $row) {
            $info = \is_string($row['additionalInformation']) ? $row['additionalInformation'] : '{}';
            $unserializedData = $this->jsonSerializer->unserialize($info) ?? [];
            $unserializedData['product_brand'] = implode(',', ['label 1', 'label 2']);

            $additionalInformation = [];
            foreach ($unserializedData as $name => $value) {
                $additionalInformation[] = [
                    'name' => $name,
                    'value' => \is_string($value) ? $value : $this->jsonSerializer->serialize($value)
                ];
            }
            foreach ($additionalInformation as $information) {
                $output[] = [
                    'additionalInformation' => $information,
                    $this->usingField => $row[$this->usingField],
                ];
            }
        }
        return $output;
    }

    /**
     * @param $values
     * @return array
     */
    private function flatten($values): array
    {
        if (isset(current($values)[0])) {
            return array_merge([], ...array_values($values));
        }
        return $values;
    }
}
