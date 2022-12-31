<?php

declare(strict_types=1);

/*
 * This file is part of the ixno/php-json-schema-validator project.
 *
 * (c) Björn Hempel <https://www.hempel.li/>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

namespace Ixnode\PhpJsonSchemaValidator;

use Ixnode\PhpContainer\File;
use Ixnode\PhpContainer\Json;
use Ixnode\PhpException\Function\FunctionJsonEncodeException;
use Ixnode\PhpException\Type\TypeInvalidException;
use JsonException;

/**
 * Class ValidatorDebugger
 *
 * @author Björn Hempel <bjoern@hempel.li>
 * @version 0.1.0 (2022-12-19)
 * @since 0.1.0 (2022-12-19) First version.
 */
class ValidatorDebugger
{
    private const LINE_BREAK = "\n";

    private const SEPARATOR = '----- %s -----';

    private const TYPE_ERRORS = 'ERRORS';

    private const TYPE_RESPONSE_JSON = 'RESPONSE JSON';

    private const TYPE_SCHEMA_FILE_PATH = 'SCHEMA FILE PATH';

    private const TYPE_SCHEMA_JSON = 'SCHEMA JSON';

    /**
     * ValidatorDebugger constructor.
     *
     * @param Validator $validator
     */
    public function __construct(protected Validator $validator)
    {
    }

    /**
     * Validates the given JSON files.
     *
     * @return bool
     * @throws JsonException
     * @throws FunctionJsonEncodeException
     * @throws TypeInvalidException
     */
    public function validate(): bool
    {
        $valid = $this->validator->validate();

        if ($valid) {
            return true;
        }

        print self::LINE_BREAK.self::LINE_BREAK;

        $this->printSchema();
        $this->printResponse();
        $this->printErrors();

        return false;
    }

    /**
     * Prints the given content.
     *
     * @param string $type
     * @param string $content
     * @return void
     */
    private function print(string $type, string $content): void
    {
        print self::LINE_BREAK;
        print sprintf(self::SEPARATOR, $type).self::LINE_BREAK;

        print trim($content).self::LINE_BREAK;

        print sprintf(self::SEPARATOR, $type).self::LINE_BREAK;
        print self::LINE_BREAK;
    }

    /**
     * Returns the response that was checked.
     *
     * @return void
     * @throws FunctionJsonEncodeException
     * @throws TypeInvalidException
     * @throws JsonException
     */
    private function printResponse(): void
    {
        $schema = $this->validator->getSchema();

        $response = $this->validator->getDataAsJson()->getArray();

        if ($schema instanceof File) {
            $response = [
                '$schema' => basename($schema->getPath()),
                ...$response
            ];
        }

        $this->print(self::TYPE_RESPONSE_JSON, (new Json($response))->getJsonStringFormatted());
    }

    /**
     * Prints the last validation errors.
     *
     * @return void
     */
    private function printErrors(): void
    {
        $errors = '';

        foreach ($this->validator->getLastErrors() as $path => $value) {
            $errors .= sprintf('%-20s %s', sprintf('path "%s":', $path), strval($value)).self::LINE_BREAK;
        }

        $this->print(self::TYPE_ERRORS, $errors);
    }

    /**
     * Prints the schema (File or Json).
     *
     * @return void
     * @throws FunctionJsonEncodeException
     */
    private function printSchema(): void
    {
        $schema = $this->validator->getSchema();

        match (true) {
            $schema instanceof File => $this->printSchemaFilePath($schema),
            $schema instanceof Json => $this->printSchemaJson($schema),
        };
    }

    /**
     * Prints the schema (File).
     *
     * @param File $file
     * @return void
     */
    private function printSchemaFilePath(File $file): void
    {
        $this->print(self::TYPE_SCHEMA_FILE_PATH, $file->getPath());
    }

    /**
     * @param Json $json
     * @return void
     * @throws FunctionJsonEncodeException
     */
    private function printSchemaJson(Json $json): void
    {
        $this->print(self::TYPE_SCHEMA_JSON, $json->getJsonStringFormatted());
    }
}
