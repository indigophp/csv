<?php

/*
 * This file is part of the Indigo Csv package.
 *
 * (c) Indigo Development Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Indigo\Csv;

use UnexpectedValueException;
use Exception;

/**
 * Csv Writer class
 *
 * Write data to CSV files
 *
 * @author Márk Sági-Kazár <mark.sagikazar@gmail.com>
 */
class Write extends Csv
{
    /**
     * If frozen settings cannot be modified
     *
     * @var boolean
     */
    protected $frozen;

    protected static $fileMode = 'w+';

    public function setOptions(array $options)
    {
        if ($this->frozen) {
            throw new Exception('Frozen object');
        }

        return parent::setOptions($options);
    }

    /**
     * Check whether Writer is frozen
     *
     * @return boolean
     */
    public function isFrozen()
    {
        return $this->frozen;
    }

    /**
     * Reset Writer
     *
     * @return boolean
     */
    public function reset()
    {
        $return = parent::reset();

        // Unfreeze object
        $this->frozen = false;

        return $return;
    }

    public function writeHeader(array $header)
    {
        if ($this->frozen) {
            throw new Exception('Header should be written first');
        }

        return $this->writeLine($header);
    }

    public function writeLine($line)
    {
        $this->frozen = true;

        if ($this->checkRowConsistency($line) === false) {
            throw new UnexpectedValueException('Given line is inconsistent with the document.');
        }

        $this->file->fputcsv($line, $this->delimiter, $this->enclosure);

        return $this;
    }

    public function writeLines($lines)
    {
        foreach ($lines as $line) {
            $this->writeLine($line);
        }
    }
}
