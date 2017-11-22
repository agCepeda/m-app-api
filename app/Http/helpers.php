<?php

if (! function_exists('generateRandomString')) {
    function generateRandomString($length = 10)
    {
        $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789_';

        $output = '';

        for ($i = 0; $i < $length; $i++) {
            $output .= $alphabet[rand() % strlen($alphabet)];
        }

        return $output;
    }
}

if (!function_exists('extractKeysOfArray')) {
    /**
     * Regresa un arreglo con las claves pasadas en el arreglo $keys
     * extraidas del arreglo $data.
     *
     * La variable $incluideNonExistenKeys indica si en el arreglo
     * que se retorna contentra las claves no encontradas en el
     * arreglo $data, por defaault es false.
     *
     * @author Agustin Cepeda.
     * @param array $keys
     * @param array $data
     * @param bool $includeNonExistentKeys
     * @return array
     */
    function extractKeysOfArray(array $keys, array $data, $includeNonExistentKeys = false)
    {
        $outputArray = [];
        foreach ($keys as $key) {
            if (array_key_exists($key, $data)) {
                $outputArray[$key] = $data[$key];
            } else {
                if ($includeNonExistentKeys) {
                    $outputArray[$key] = null;
                }
            }
        }
        return $outputArray;
    }
}
