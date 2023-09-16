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

use Exception;
use Ixnode\PhpChecker\CheckerClass;
use Ixnode\PhpContainer\File;
use Ixnode\PhpContainer\Json;
use Ixnode\PhpException\File\FileNotFoundException;
use Ixnode\PhpException\Function\FunctionJsonEncodeException;
use Ixnode\PhpException\Type\TypeInvalidException;
use JsonException;
use LogicException;
use Opis\JsonSchema\Errors\ErrorFormatter;
use Opis\JsonSchema\Errors\ValidationError;
use Opis\JsonSchema\Exceptions\InvalidKeywordException;
use Opis\JsonSchema\Resolvers\SchemaResolver;
use Opis\JsonSchema\Validator as OpisJsonSchemaValidator;
use stdClass;

/**
 * Class Validator
 *
 * @author Björn Hempel <bjoern@hempel.li>
 * @version 0.1.0 (2022-12-31)
 * @since 0.1.0 (2022-12-31) First version.
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class Validator
{
    /** @var ValidationError|array<int|string, string>|null $lastErrors */
    protected ValidationError|array|null $lastErrors = null;

    protected bool $isValidated = false;

    protected const CONST_MAX_ERRORS = 9999;

    /**
     * @param File|Json $data
     * @param File|Json $schema
     * @param string|null $pathRoot
     */
    public function __construct(protected File|Json $data, protected File|Json $schema, protected ?string $pathRoot = null)
    {
    }

    /**
     * Returns the data as JSON representation.
     *
     * @return Json
     * @throws FunctionJsonEncodeException
     * @throws JsonException
     * @throws TypeInvalidException
     */
    public function getDataAsJson(): Json
    {
        return match (true) {
            $this->data instanceof File => $this->data->getContentAsJson(),
            $this->data instanceof Json => $this->data,
        };
    }

    /**
     * Returns the
     *
     * @return File|Json
     */
    public function getSchema(): Json|File
    {
        return $this->schema;
    }

    /**
     * Returns an array of given path.
     *
     * @param string $json
     * @return stdClass
     * @throws TypeInvalidException
     * @throws JsonException
     */
    protected function getJsonDecoded(string $json): stdClass
    {
        $object = (object) json_decode($json, null, 512, JSON_THROW_ON_ERROR);

        return (new CheckerClass($object))->checkStdClass();
    }

    /**
     * Validates the given JSON files.
     *
     * @return bool
     * @throws FileNotFoundException
     * @throws FunctionJsonEncodeException
     * @throws JsonException
     * @throws TypeInvalidException
     */
    public function validate(): bool
    {
        $this->isValidated = true;

        $validator = new OpisJsonSchemaValidator();
        $validator->setMaxErrors(self::CONST_MAX_ERRORS);

        $resolver = $validator->resolver();

        if (!$resolver instanceof SchemaResolver) {
            throw new LogicException(sprintf('Unable to get SchemaResolver (%s:%d).', __FILE__, __LINE__));
        }

        match (true) {
            $this->schema instanceof File => $resolver->registerFile(Constants::ID_JSON_SCHEMA_GENERAL, $this->schema->getPathReal()),
            $this->schema instanceof Json => $resolver->registerRaw($this->schema->getJsonStringFormatted(), Constants::ID_JSON_SCHEMA_GENERAL)
        };

        $resolver->registerFile(Constants::URL_JSON_SCHEMA_DRAFT_07, (new File(Constants::PATH_SCHEMA_DRAFT_07, $this->pathRoot ?: $this->getRootPath()))->getPathReal());

        $data = match (true) {
            $this->data instanceof File => $this->getJsonDecoded($this->data->getContentAsJson()->getJsonStringFormatted()),
            $this->data instanceof Json => $this->getJsonDecoded($this->data->getJsonStringFormatted())
        };

        try {
            $result = $validator->validate($data, Constants::ID_JSON_SCHEMA_GENERAL);
        } catch (InvalidKeywordException $e) {
            $this->lastErrors = [
                '/general' => $e->getMessage(),
            ];

            return false;
        }

        if ($result->isValid()) {
            return true;
        }

        $this->lastErrors = $result->error();
        return false;
    }

    /**
     * Get last errors as array.
     *
     * @return array<int|string, string>
     */
    public function getLastErrorsArray(): array
    {
        if (is_null($this->lastErrors)) {
            return [];
        }

        if (is_array($this->lastErrors)) {
            return $this->lastErrors;
        }

        $formatter = new ErrorFormatter();

        return $formatter->format($this->lastErrors, false);
    }

    /**
     * Get last errors as string representation.
     *
     * @return string
     */
    public function getLastErrorsString(): string
    {
        $errors = [];

        foreach ($this->getLastErrorsArray() as $name => $value) {
            $errors[] = sprintf('%-12s %s', strval($name).':', strval($value));
        }

        return "\n\n".implode("\n", $errors)."\n";
    }

    /**
     * Get last errors as json.
     *
     * @return string
     * @throws Exception
     */
    public function getLastErrorsJson(): string
    {
        return (new Json($this->getLastErrorsArray()))->getJsonStringFormatted();
    }

    /**
     * Returns the status of validation as array.
     *
     * @return array<string, mixed>
     * @throws Exception
     */
    public function getStatusArray(): array
    {
        if (!$this->isValidated) {
            throw new Exception(sprintf('Please execute the validate method before (%s:%d).', __FILE__, __LINE__));
        }

        if (is_null($this->lastErrors)) {
            return [
                'valid' => true,
                'message' => 'The supplied JSON validates against the schema.',
            ];
        }

        return [
            'valid' => false,
            'message' => 'The supplied JSON does not validate against the schema.',
            'error' => $this->getLastErrorsArray()
        ];
    }

    /**
     * Returns the status of validation as json.
     *
     * @return string
     * @throws Exception
     */
    public function getStatusJson(): string
    {
        return (new Json($this->getStatusArray()))->getJsonStringFormatted();
    }

    /**
     * Returns the root path of this class.
     *
     * @return string
     */
    private function getRootPath(): string
    {
        return dirname(__FILE__, 2);
    }
}
