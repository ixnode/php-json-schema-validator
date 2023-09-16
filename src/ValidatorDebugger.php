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

namespace Ixnode\PhpJsonSchemaValidator;

use Ixnode\PhpContainer\File;
use Ixnode\PhpContainer\Json;
use Ixnode\PhpException\File\FileNotFoundException;
use Ixnode\PhpException\File\FileNotReadableException;
use Ixnode\PhpException\Function\FunctionJsonEncodeException;
use Ixnode\PhpException\Type\TypeInvalidException;
use JsonException;

/**
 * Class ValidatorDebugger
 *
 * @author Björn Hempel <bjoern@hempel.li>
 * @version 0.1.1 (2023-09-16)
 * @since 0.1.1 (2023-09-16) Updated version with file name and line.
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

    private const TYPE_FILE_AND_LINE = 'FILE AND LINE';

    private const TYPE_ADDITIONAL_INFORMATION = 'ADDITIONAL INFORMATION';

    /**
     * @param Validator $validator
     * @param string $file
     * @param int $line
     * @param string|null $additionalInformation
     * @SuppressWarnings(PHPMD.LongVariable)
     */
    public function __construct(protected Validator $validator, protected string $file, protected int $line, protected  ?string $additionalInformation = null)
    {
    }

    /**
     * Validates the given JSON files.
     *
     * @return bool
     * @throws FileNotFoundException
     * @throws FunctionJsonEncodeException
     * @throws JsonException
     * @throws TypeInvalidException
     * @throws FileNotReadableException
     */
    public function validate(): bool
    {
        $valid = $this->validator->validate();

        if ($valid) {
            return true;
        }

        print self::LINE_BREAK.self::LINE_BREAK;

        $this->printFileAndLine();
        $this->printSchema();
        $this->printResponse();
        $this->printErrors();
        $this->printAdditionalInformation();

        print self::LINE_BREAK;
        print self::LINE_BREAK;

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
     * @throws FileNotFoundException
     * @throws FunctionJsonEncodeException
     * @throws JsonException
     * @throws TypeInvalidException
     * @throws FileNotReadableException
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

        foreach ($this->validator->getLastErrorsArray() as $path => $value) {
            $errors .= sprintf('%-20s %s', sprintf('path "%s":', $path), strval($value)).self::LINE_BREAK;
        }

        $this->print(self::TYPE_ERRORS, $errors);
    }

    /**
     * Prints additional information.
     *
     * @return void
     */
    private function printAdditionalInformation(): void
    {
        if (!is_string($this->additionalInformation)) {
            return;
        }

        $this->print(self::TYPE_ADDITIONAL_INFORMATION, $this->additionalInformation);
    }

    /**
     * Prints the file and line.
     *
     * @return void
     */
    private function printFileAndLine(): void
    {
        $this->print(self::TYPE_FILE_AND_LINE, sprintf('%s:%d', $this->file, $this->line));
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
