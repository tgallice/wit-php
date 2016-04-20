<?php

namespace Tgallice\Wit;

class Helper
{
    /**
     * @param $entityName
     * @param array $entities
     *
     * @return mixed
     */
    public static function getFirstEntityValue($entityName, array $entities)
    {
        if (!isset($entities[$entityName][0]['value'])) {
            return null;
        }

        $val = $entities[$entityName][0]['value'];

        return is_array($val) && isset($val['value']) ? $val['value'] : $val;
    }
}
