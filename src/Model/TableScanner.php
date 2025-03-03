<?php
declare(strict_types=1);

/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @since         2.0.0
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
namespace MixerApi\Core\Model;

use Cake\Datasource\ConnectionInterface;
use RuntimeException;

/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @since         2.0.0
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 *
 * Borrowed from cakephp/bake
 */
class TableScanner
{
    /**
     * Constructor
     *
     * @param \Cake\Datasource\ConnectionInterface $connection The connection name in ConnectionManager
     * @param string[]|null $ignore List of tables or regex pattern to ignore. If null, the default ignore
     *   list will be used.
     */
    public function __construct(private ConnectionInterface $connection, private ?array $ignore = null)
    {
        if ($ignore === null) {
            $ignore = ['i18n', 'cake_sessions', 'sessions', '/phinxlog/'];
        }
        $this->ignore = $ignore;
    }

    /**
     * Get all tables in the connection without applying ignores.
     *
     * @return string[]
     */
    public function listAll(): array
    {
        $schema = $this->connection->getSchemaCollection();
        $tables = $schema->listTables();
        if (empty($tables)) {
            throw new RuntimeException('Your database does not have any tables.');
        }
        sort($tables);

        return array_combine($tables, $tables);
    }

    /**
     * Get all tables in the connection that aren't ignored.
     *
     * @return string[]
     */
    public function listUnskipped(): array
    {
        $tables = $this->listAll();

        foreach ($tables as $key => $table) {
            if ($this->shouldSkip($table)) {
                unset($tables[$key]);
            }
        }

        return $tables;
    }

    /**
     * @param string $table Table name.
     * @return bool
     */
    protected function shouldSkip(string $table): bool
    {
        return in_array($table, $this->ignore);
    }
}
