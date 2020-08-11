<?php

/**
 * This file is part of ramsey/collection
 *
 * ramsey/collection is open source software: you can distribute
 * it and/or modify it under the terms of the MIT License
 * (the "License"). You may not use this file except in
 * compliance with the License.
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or
 * implied. See the License for the specific language governing
 * permissions and limitations under the License.
 *
 * @copyright Copyright (c) Ben Ramsey <ben@benramsey.com>
 * @license https://opensource.org/licenses/MIT MIT License
 */

declare(strict_types=1);

namespace Ramsey\Console;

use Psy\Configuration;
use Psy\Shell;

/**
 * A REPL (read-eval-print loop) for use with development
 *
 * This REPL uses PsySH. To use it, enter `./bin/repl` at your command prompt.
 *
 * @link https://psysh.org PsySH
 */
class Repl
{
    public static function start(): void
    {
        static::loadEnvironment();

        $config = new Configuration([
            'startupMessage' => '<info>'
                . 'Welcome to the REPL for ramsey/collection!'
                . '</info>',
            'colorMode' => Configuration::COLOR_MODE_FORCED,
            'updateCheck' => 'never',
            'useBracketedPaste' => true,
        ]);

        $shell = new Shell($config);
        $shell->setScopeVariables(self::getScopeVariables());
        $shell->run();
    }

    private static function loadEnvironment(): void
    {
        // Set up any configuration or bootstrapping of the environment here.
    }

    /**
     * @return mixed[]
     */
    private static function getScopeVariables(): array
    {
        return [];
    }
}
