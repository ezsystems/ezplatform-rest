<?php

/**
 * @copyright Copyright (C) Ibexa AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
return [
    'ContentTypeCreate' => [
        'FieldDefinitions' => [
            'FieldDefinition' => [
                [
                    'validatorConfiguration' => [
                        'StringLengthValidator' => [
                            'minStringLength' => false,
                            'maxStringLength' => 192,
                        ],
                    ],
                ],
            ],
        ],
    ],
];
