---

# Weather
基于 [高德开放平台](https://lbs.amap.com/dev/id/newuser) 的 PHP 天气信息组件

[![Build Status](https://travis-ci.org/zhihanglee/weather.svg?branch=master)](https://travis-ci.org/zhihanglee/weather)
![StyleCI build status](https://github.styleci.io/repos/324499079/shield)
## 需求
php >=7.2.5
## 安装

```sh
$ composer require leo0315/weather -vvv
```

## 前提

在[高德开放平台](https://lbs.amap.com/dev/id/newuser) 注册账号，然后创建应用，获取应用的 API Key。

## 使用

```php
use Leo0315\Weather\Weather;

$key = '获取的key';

$weather = new Weather($key);
```

###  获取实时天气

```php
$response = $weather->getLiveWeather('深圳');
```
返回：

```
{
    "status": "1",
    "count": "1",
    "info": "OK",
    "infocode": "10000",
    "lives": [
        {
            "province": "山西",
            "city": "山西省",
            "adcode": "140000",
            "weather": "晴",
            "temperature": "4",
            "winddirection": "南",
            "windpower": "<=3",
            "humidity": "29",
            "reporttime": "2020-12-26 14:29:38"
        }
    ]
}
```

### 获取近期天气预报

```
$response = $weather->getForecastsWeather('山西', 'all');
```
示例：

```json
{
    "status": "1", 
    "count": "1", 
    "info": "OK", 
    "infocode": "10000", 
    "forecasts": [
        {
            "city": "山西省", 
            "adcode": "140000", 
            "province": "山西", 
            "reporttime": "2020-12-26 14:29:39", 
            "casts": [
                {
                    "date": "2020-12-26", 
                    "week": "6", 
                    "dayweather": "晴", 
                    "nightweather": "晴", 
                    "daytemp": "6", 
                    "nighttemp": "-12", 
                    "daywind": "东南", 
                    "nightwind": "东南", 
                    "daypower": "≤3", 
                    "nightpower": "≤3"
                }, 
                {
                    "date": "2020-12-27", 
                    "week": "7", 
                    "dayweather": "多云", 
                    "nightweather": "阴", 
                    "daytemp": "6", 
                    "nighttemp": "-11", 
                    "daywind": "东", 
                    "nightwind": "东", 
                    "daypower": "≤3", 
                    "nightpower": "≤3"
                }, 
                   {
                   "date": "2020-12-28",
                   "week": "1",
                   "dayweather": "多云",
                   "nightweather": "阴",
                   "daytemp": "6",
                   "nighttemp": "-11",
                   "daywind": "西",
                   "nightwind": "西",
                   "daypower": "4",
                   "nightpower": "4"
                },
                    {
                    "date": "2020-12-29",
                    "week": "2",
                    "dayweather": "晴",
                    "nightweather": "晴",
                    "daytemp": "-5",
                    "nighttemp": "-17",
                    "daywind": "西北",
                    "nightwind": "西北",
                    "daypower": "4",
                    "nightpower": "4"
                }
            ]
        }
    ]
}
```

### 获取 XML 格式返回值

第三个参数为返回值类型，可选 `json` 与 `xml`，默认 `json`：

```php
$response = $weather->getLiveWeather('山西', 'xml');
```

示例：

```xml
<response>
    <status>1</status>
    <count>1</count>
    <info>OK</info>
    <infocode>10000</infocode>
    <lives type="list"><live>
    <province>山西</province>
    <city>山西省</city>
    <adcode>140000</adcode>
    <weather>晴</weather>
    <temperature>4</temperature>
    <winddirection>南</winddirection>
    <windpower>≤3</windpower>
    <humidity>29</humidity>
    <reporttime>2020-12-26 14:29:38</reporttime>
    </live></lives>
</response>
```

### 参数说明

```
array | string   getLiveWeather(string $city, string $format = 'json')
array | string   getForecastsWeather(string $city, string $format = 'json')
```

> - `$city` - 城市名，比如：“深圳”；
> - `$type` - 返回内容类型：`base`: 返回实况天气 / `all`:返回预报天气；
> - `$format`  - 输出的数据格式，默认为 json 格式，当 output 设置为 “`xml`” 时，输出的为 XML 格式的数据。

### 在 Laravel 中使用

在 Laravel 中使用也是同样的安装方式，配置写在 `config/services.php` 中：

```php
	.
	.
	.
	 'weather' => [
		'key' => env('WEATHER_API_KEY'),
    ],
```

然后在 `.env` 中配置 `WEATHER_API_KEY` ：

```env
WEATHER_API_KEY=xxxxxxxxxxxxxxxxxxxxx
```

可以用两种方式来获取 `Leo0315\Weather\Weather` 实例：

#### 方法参数注入

```php
	.
	.
	.
	public function edit(Weather $weather) 
	{
		$response = $weather->getLiveWeather('深圳');
	}
	.
	.
	.
```

#### 服务名访问

```php
	.
	.
	.
	public function edit() 
	{
		$response = app('weather')->getLiveWeather('深圳');
	}
	.
	.
	.

```

## 参考

- [高德开放平台天气接口](https://lbs.amap.com/api/webservice/guide/api/weatherinfo/)

## License

MIT