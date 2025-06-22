<?php

/**
 * Utility class for sanitizing data to prevent XSS attacks
 */
class Sanitizer
{
    /**
     * Recursively sanitize data to prevent XSS attacks
     * 
     * @param mixed $data The data to sanitize
     * @return mixed The sanitized data
     */
    public static function sanitizeData($data)
    {
        if (is_string($data)) {
            return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
        }

        if (is_array($data)) {
            $sanitized = [];
            foreach ($data as $key => $value) {
                $sanitized[$key] = self::sanitizeData($value);
            }
            return $sanitized;
        }

        if (is_object($data)) {
            $sanitized = new stdClass();
            foreach ($data as $key => $value) {
                $sanitized->$key = self::sanitizeData($value);
            }
            return $sanitized;
        }

        return $data;
    }
}