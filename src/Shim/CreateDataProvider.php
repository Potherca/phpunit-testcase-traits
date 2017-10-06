<?php

namespace Potherca\PhpUnit\Shim;

/**
 * @method fail($message)
 */
class CreateDataProvider extends AbstractTraitShim
{
    ////////////////////////////// CLASS PROPERTIES \\\\\\\\\\\\\\\\\\\\\\\\\\\\
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
            $subject = array($subject);
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

        $subjectWithKeys = array();

        $counter = array(
            'array' => 0,
            'object' => 0,
            'resource' => 0,
            'unknown type' => 0,
        );

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
            $keys = array_keys($subjectWithKeys);

            /* @NOTE: The constant `SORT_NATURAL` was not introduced until PHP5.4 so a substitute is used */
            defined('SORT_NATURAL') OR define('SORT_NATURAL', SORT_STRING);

            array_multisort($keys, SORT_NATURAL/*| SORT_FLAG_CASE*/, $subjectWithKeys);
        }

        return $subjectWithKeys;
    }
}

/*EOF*/
