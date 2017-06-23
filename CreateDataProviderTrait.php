<?php
/**
 * Copyright (C) 2017  Potherca
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */
namespace Potherca\PhpUnit;

/**
 * Creates a "key => [value]" pair from a given one-dimensional array of values,
 * which is meant to be returned from a data-provider method in a TestCase.
 *
 * The keys are created from the array values. The returned array is sorted by
 * key name. Key names that are longer than 25 characters are truncated.
 *
 * The trait provides two setter methods to change this behaviour:
 *
 * - CreateDataProviderTrait::setDataProviderMaximumKeyLength(int $maxLength)
 * - CreateDataProviderTrait::setDataProviderSortByKey(bool $sort)
 *
 * Example usage:
 *
 *    class ExampleTest extends PHPUnit\Framework\TestCase
 *    {
 *        use \Potherca\PhpUnit\CreateDataProviderTrait;
 *
 *        public function provideValidValues()
 *        {
 *            return $this->createDataProvider([
 *                -1,
 *                0,
 *                1,
 *                true,
 *                false,
 *                null,
 *                [null],
 *                [],
 *                new \stdClass(),
 *            ]);
 *        }
 *    }
 *
 *    var_export((new ExampleTest)->provideValidValues());
 *
 * Output would be:
 *
 *    array (
 *        'NULL' => [NULL],
 *        'array: 1 (count: 1)' => [
 *            [NULL]
 *        ],
 *        'array: 2 (count: 0)' => [
 *            []
 *        ],
 *        'boolean: false' => [false],
 *        'boolean: true' => [true],
 *        'integer: -1' => [-1],
 *        'integer: 0' => [0],
 *        'integer: 1' => [1],
 *        'object: stdClass' => [new stdClass(array())],
 *    );
 */
trait CreateDataProviderTrait
{
    ////////////////////////////// TRAIT PROPERTIES \\\\\\\\\\\\\\\\\\\\\\\\\\\\
    /** @var int */
    private $maximumKeyLength = 25;
    /** @var bool */
    private $sortByKey = true;

    //////////////////////////// SETTERS AND GETTERS \\\\\\\\\\\\\\\\\\\\\\\\\\\
    /** @param int $length */
    final public function setDataProviderMaximumKeyLength($length)
    {
        $this->maximumKeyLength = (int) $length;
    }

    /** @param bool $sort */
    final public function setDataProviderSortByKey($sort)
    {
        $this->sortByKey = (bool) $sort;
    }

    //////////////////////////////// PUBLIC API \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
    /**
     * @param array $subject
     *
     * @return array[]
     */
    final public function createDataProvider(array $subject)
    {
        return $this->expandArray(
            $this->addKeys(
                $subject
            )
        );
    }

    ////////////////////////////// UTILITY METHODS \\\\\\\\\\\\\\\\\\\\\\\\\\\\\
    /**
     * @param array $subjects
     *
     * @return array
     */
    private function expandArray(array $subjects)
    {
        array_walk($subjects, function (&$subject) {
            $subject = [$subject];
        });

        return $subjects;
    }

    /**
     * @param $subjects
     *
     * @return array
     */
    private function addKeys($subjects)
    {
        $subjects = array_values($subjects);

        $subjectWithKeys = [];

        $counter = [
            'array' => 0,
            'object' => 0,
            'resource' => 0,
            'unknown type' => 0,
        ];

        foreach ($subjects as $index => $subject) {
            $value = $subject;

            if (is_numeric($index) === false) {
                $key = $index;
            } else {
                $type = gettype($value);
                $key = null;

                switch ($type) {
                    case 'boolean':
                        $value = $value?'true':'false';
                        break;

                    case 'integer':
                    case 'double':
                        break;

                    case 'string':
                        if (mb_strlen($value) > $this->maximumKeyLength) {
                            $value = sprintf('"%s…"', mb_substr($value, 0, $this->maximumKeyLength-1));
                        }
                        break;

                    case 'NULL':
                        $key = 'NULL';
                        break;

                    case 'resource':
                        $counter[$type]++;
                        $value = $counter[$type];
                        $type = get_resource_type($subject);
                        break;

                    case 'object':
                        $value = get_class($value);
                        break;

                    case 'array':
                        $counter[$type]++;
                        $value = sprintf(
                            '%s (count: %d)',
                            $counter[$type],
                            count($value)
                        );
                        break;

                    case 'unknown type':
                    default:
                        $type = 'unknown type';
                        $counter[$type]++;
                        $value = $counter[$type];
                        break;
                }

                if ($key === null) {
                    $key = sprintf('%s: %s', $type, $value);
                }
            }

            $subjectWithKeys[$key] = $subject;
        }

        if ($this->sortByKey === true) {
            array_multisort(array_keys($subjectWithKeys), SORT_NATURAL/*| SORT_FLAG_CASE*/, $subjectWithKeys);
        }

        return $subjectWithKeys;
    }
}

/*EOF*/
