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

/**
 * Class Constants
 *
 * @author Björn Hempel <bjoern@hempel.li>
 * @version 0.1.0 (2022-12-31)
 * @since 0.1.0 (2022-12-31) First version.
 */
class Constants
{
    final public const PATH_SCHEMA_DRAFT_07 = 'data/json/schema/draft-07.json';

    final public const PATH_SCHEMA_SIMPLE = 'data/json/schema/simple.json';

    final public const PATH_SCHEMA_COMPLEX = 'data/json/schema/complex.json';


    final public const PATH_DATA_SIMPLE = 'data/json/raw/simple.json';

    final public const PATH_DATA_COMPLEX = 'data/json/raw/complex.json';


    final public const URL_JSON_SCHEMA_DRAFT_07 = 'http://json-schema.org/draft-07/schema#';

    final public const ID_JSON_SCHEMA_GENERAL = 'http://api.example.tld/schema.json';

    final public const SCHEMA_SIMPLE_OBJECT = [
        "type" => "object"
    ];
}
