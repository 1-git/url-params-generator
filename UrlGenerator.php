<?php

namespace tests\apicrm\urlParamsGenerator;

/**
 * Simple url combination generator
 *
 * Example:
 *
 * Input:
 * `
 * [
 *      'url_1',
 *      'url_2' => 'param_2=1',
 *      'url_3' => [
 *           'param_1=1',
 *            'param_2=2',
 *      ],
 *      'url_4' => [
 *        'param_1' => 1,
 *        'param_2' => 2,
 *      ],
 *      'url_5' => [
 *          'param_1=2',
 *        'param_1' => 2,
 *        'param_2' => [21, 2],
 *      ],
 *      'url_6' => [
 *          [
 *              'param_1=1',
 *              'param_2=2',
 *          ],
 *          [
 *              'param_1' => 3,
 *              'param_2' => 4,
 *          ],
 *          [
 *              'param_3' => 3,
 *              'param_4' => 4,
 *              'param_5' => [51, 52],
 *          ]
 *      ]
 * ]
 * `
 * Output:
 * `
 * [
 *      'url_1',
 *      'url_2?param_2=1',
 *      'url_3?param_1=1',
 *      'url_3?param_2=2',
 *      'url_4?param_1=1',
 *      'url_4?param_2=2',
 *      'url_5?param_1=2',
 *      'url_5?param_1=2',
 *      'url_5?param_2=21',
 *      'url_5?param_2=2',
 *      'url_6?param_1=1+param_2=2',
 *      'url_6?param_1=3+param_2=4',
 *      'url_6?param_3=3+param_4=4+param_5=51',
 *      'url_6?param_3=3+param_4=4+param_5=52',
 * ;`
 *
 * Class UrlParamsGenerator
 * @package tests\apicrm\urlVariantsGenerator
 */
class UrlParamsGenerator
{
    /**
     * @param array $list
     * @return array
     */
    public static function generate(array $list): array
    {
        $paths = [];
        foreach ($list as $key => $params) {
            if (is_integer($key)) {
                $paths[] = $params;
            } else {
                if (!is_array($params)) {
                    $paths[] = $key . '?' . $params;
                } else {
                    foreach ($params as $key2 => $params2) {
                        if (!is_array($params2)) {
                            if (is_integer($key2)) {
                                $paths[] = $key . '?' . $params2;
                            } else {
                                $paths[] = $key . '?' . $key2 . '=' . $params2;
                            }
                        } else {
                            if (!is_integer($key2)) {
                                foreach ($params2 as $key3 => $params3) {
                                    $paths[] = $key . '?' . $key2 . '=' . $params3;
                                }
                            } else {
                                $urls = [];
                                foreach ($params2 as $key3 => $params3) {
                                    if (!is_array($params3)) {
                                        if (is_integer($key3)) {
                                            $urls = self::addParam($urls, $key, $params3);
                                        } else {
                                            $urls = self::addParam($urls, $key, "{$key3}={$params3}");
                                        }
                                    } else {
                                        $suburls = [];
                                        foreach ($params3 as $params4) {
                                            $suburls = array_merge($suburls, self::addParam($urls, $key, "{$key3}={$params4}"));
                                        }
                                        if (!empty($suburls)) {
                                            $urls = $suburls;
                                        }
                                    }
                                }
                                if (!empty($urls)) {
                                    $paths = array_merge($paths, $urls);
                                }
                            }
                        }
                    }
                }

            }
        }
        return $paths;
    }

    /**
     * @param array $urls
     * @param $path
     * @param $added
     * @return array
     */
    protected static function addParam(array $urls, $path, $added): array
    {
        if (empty($urls)) {
            $urls[] = "{$path}?{$added}";
        } else {
            foreach ($urls as $key => $url) {
                $urls[$key] = "{$url}+{$added}";
            }
        }
        return $urls;
    }
}
