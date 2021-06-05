<?php

/*
 * This file is part of the leo0315/weather.
 *
 * (c) leo0315 <lizhihang0001@126.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Leo0315\Weather;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Leo0315\Weather\Exceptions\HttpException;
use Leo0315\Weather\Exceptions\InvalidArgumentException;

class Weather
{
    /**
     * @var string
     */
    protected $key;
    /**
     * @var array
     */
    protected $guzzleOptions = [];

    /**
     * Weather constructor.
     * @param string $key
     */
    public function __construct($key)
    {
        $this->key = $key;
    }

    /**
     * @return Client
     */
    public function getHttpClient(): Client
    {
        return new Client($this->guzzleOptions);
    }

    public function setGuzzleOptions(array $options): void
    {
        $this->guzzleOptions = $options;
    }

    /**
     * 返回实时天气
     * @param $city
     * @param string $format
     * @return mixed|string
     * @throws HttpException
     * @throws InvalidArgumentException|GuzzleException
     */
    public function getLiveWeather($city, string $format = 'json')
    {
        return $this->getWeather($city, 'base', $format);
    }

    /**
     * 返回近几天的天气预报
     * @param $city
     * @param string $format
     * @return mixed|string
     * @throws HttpException
     * @throws InvalidArgumentException|GuzzleException
     */
    public function getForecastsWeather($city, string $format = 'json')
    {
        return $this->getWeather($city, 'all', $format);
    }

    /**
     * 获取天气主接口
     * @param $city
     * @param string $type
     * @param string $format
     * @return mixed|string
     * @throws HttpException
     * @throws InvalidArgumentException
     * @throws GuzzleException
     */
    public function getWeather($city, string $type = 'base', string $format = 'json')
    {
        $url = 'https://restapi.amap.com/v3/weather/weatherInfo';
        if (!\in_array(\strtolower($format), ['xml', 'json'])) {
            throw new InvalidArgumentException('Invalid response format: ' . $format);
        }
        if (!\in_array(\strtolower($type), ['base', 'all'])) {
            throw new InvalidArgumentException('Invalid type value(base/all): ' . $type);
        }
        $format = \strtolower($format);
        $type = \strtolower($type);
        //封装 query 参数，并对空值进行过滤。
        $query = array_filter([
            'key'        => $this->key,
            'city'       => $city,
            'output'     => $format,
            'extensions' => $type,
        ]);
        try {
            //调用 getHttpClient 获取实例，并调用该实例的 `get` 方法，
            $response = $this->getHttpClient()->get($url, [
                'query' => $query,
            ])->getBody()->getContents();
            //返回值根据 $format 返回不同的格式，
            //当 $format 为 json 时，返回数组格式，否则为 xml。
            return 'json' === $format ? \json_decode($response, true) : $response;
        } catch (\Exception $e) {
            //当调用出现异常时捕获并抛出，消息为捕获到的异常消息，
            //并将调用异常作为 $previousException 传入。
            throw new HttpException($e->getMessage(), $e->getCode(), $e);
        }
    }
}
