<?php

namespace SpotifyPublicAPIBundle\Utils\ExtraFunctions;

/**
 * @author Dmitry Shumytskyi <d.shumytskyi@gmail.com>
 */
class QueryBuilder
{
    public function buildObjectsParams($schema, $parameters)
    {
        $uri = [];

        if ((array)$schema['object'] === $schema['object']) {
            foreach ($schema['object'] as $apiValue => $rapidApiValue) {
                if (isset($parameters[$rapidApiValue]) && $parameters[$rapidApiValue] != '') {

                    $uri[$apiValue] = $parameters[$rapidApiValue];

                }
            }
        }

        return $uri;
    }

    public function buildArgsParams($schema, $parameters)
    {
        $query = [];

        if ((array)$schema['args'] === $schema['args']) {
            foreach ($schema['args'] as $apiValue => $rapidApiValue) {
                if (isset($parameters[$rapidApiValue['marketName']]) && $parameters[$rapidApiValue['marketName']] != '') {
                    $query[$apiValue] = call_user_func_array(array($this, $rapidApiValue['type']), [$parameters[$rapidApiValue['marketName']], $rapidApiValue]);
                }
            }
        }

        return $query;
    }

    public function buildHeaders($schema, $parameters)
    {
        if ($schema['object_headers'] !== false) {
            $headers = [];
            $objects = [];
            foreach ($schema['object_headers'] as $apiValue => $rapidApiValue) {
                if (isset($parameters[$rapidApiValue]) && $parameters[$rapidApiValue] != '') {
                    $objects[$apiValue] = $parameters[$rapidApiValue];
                }
            }
            $patterns = array_keys($objects);
            $replacements = array_values($objects);
            foreach ($schema['headers'] as $name => $value) {
                $headers[$name] = str_replace($patterns, $replacements, $value);
            }
            return $headers;
        } else {
            return $schema['headers'];
        }
    }

    public function list($list, $conf)
    {
        return (is_array($list) == false) ? $list : implode($conf['separator'], $list);
    }

    public function string($string, $conf)
    {
        return $string;
    }
}