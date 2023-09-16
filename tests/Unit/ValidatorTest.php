<?php

/*
 * This file is part of the ixno/php-json-schema-validator project.
 *
 * (c) Björn Hempel <https://www.hempel.li/>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Ixnode\PhpJsonSchemaValidator\Tests\Unit;

use Ixnode\PhpContainer\File;
use Ixnode\PhpContainer\Json;
use Ixnode\PhpException\File\FileNotFoundException;
use Ixnode\PhpException\File\FileNotReadableException;
use Ixnode\PhpException\Function\FunctionJsonEncodeException;
use Ixnode\PhpException\Type\TypeInvalidException;
use Ixnode\PhpJsonSchemaValidator\Constants;
use Ixnode\PhpJsonSchemaValidator\Validator;
use JsonException;
use PHPUnit\Framework\TestCase;

/**
 * Class ValidatorTest
 *
 * @author Björn Hempel <bjoern@hempel.li>
 * @version 0.1.0 (2022-12-31)
 * @since 0.1.0 (2022-12-31) First version.
 * @link Validator
 */
final class ValidatorTest extends TestCase
{
    /**
     * Test wrapper (Validator::validate).
     *
     * @dataProvider dataProviderIsJson
     *
     * @test
     * @testdox $number) Test Json::isJson
     * @param int $number
     * @param Json|File $data
     * @param Json|File $schema
     * @param bool $expected
     * @param array<int|string, mixed> $expectedError
     * @throws FunctionJsonEncodeException
     * @throws TypeInvalidException
     * @throws JsonException
     * @throws FileNotFoundException
     */
    public function wrapper(int $number, Json|File $data, Json|File $schema, bool $expected, array $expectedError): void
    {
        /* Arrange */
        $validator = new Validator($data, $schema);

        /* Act */
        $valid = $validator->validate();

        /* Assert */
        $this->assertIsNumeric($number); // To avoid phpmd warning.
        $this->assertEquals($expected, $valid);
        $this->assertEquals($expectedError, $validator->getLastErrorsArray());
    }

    /**
     * Data provider (Json::isJson).
     *
     * @return array<int, array{int, Json|File, Json|File, bool, array<int|string, mixed>}>
     * @throws FileNotFoundException
     * @throws FunctionJsonEncodeException
     * @throws JsonException
     * @throws TypeInvalidException
     * @throws FileNotReadableException
     */
    public function dataProviderIsJson(): array
    {
        $number = 0;

        return [
            /* Valid */
            [++$number, new Json('{}'), new Json('{}'), true, [], ],
            [++$number, new Json('{}'), new Json(Constants::SCHEMA_SIMPLE_OBJECT), true, [], ],
            [++$number, new Json('[]'), new Json('{}'), true, [], ],
            [++$number, new Json('[]'), new Json(Constants::SCHEMA_SIMPLE_OBJECT), true, [], ],
            [++$number, new Json('[1, 2, 3]'), new Json('{}'), true, [], ],
            [++$number, new Json('[1, 2, 3]'), new Json(Constants::SCHEMA_SIMPLE_OBJECT), true, [], ],
            [++$number, new File(Constants::PATH_DATA_COMPLEX), new Json('{}'), true, [], ],
            [++$number, new File(Constants::PATH_DATA_SIMPLE), new File(Constants::PATH_SCHEMA_SIMPLE), true, [], ],
            [++$number, new File(Constants::PATH_DATA_COMPLEX), new File(Constants::PATH_SCHEMA_COMPLEX), true, [], ],
            [++$number, new Json((new File(Constants::PATH_DATA_COMPLEX))->getContentAsJson()), new File(Constants::PATH_SCHEMA_COMPLEX), true, [], ],

            /* Invalid (schema does not match) */
            [++$number, new File(Constants::PATH_DATA_SIMPLE), new File(Constants::PATH_SCHEMA_COMPLEX), false, ['/' => 'The required properties (type, forms) are missing', ], ],
            [++$number, new File(Constants::PATH_SCHEMA_COMPLEX), new File(Constants::PATH_SCHEMA_COMPLEX), false, ['/' => 'The required properties (version, forms) are missing', ], ],
            [++$number, new File(Constants::PATH_DATA_COMPLEX), new File(Constants::PATH_SCHEMA_DRAFT_07), false, ['/%24schema' => 'The data must match the \'uri\' format', '/type' => 'The data should match one item from enum', ], ],

            /* Invalid (invalid schema format) */
            [++$number, new File(Constants::PATH_DATA_COMPLEX), new File(Constants::PATH_DATA_COMPLEX), false, ['/general' => 'type contains invalid json type: apiEndpoint', ], ],
        ];
    }
}
