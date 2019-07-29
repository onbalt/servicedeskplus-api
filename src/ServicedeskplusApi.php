<?php

namespace Onbalt\ServicedeskplusApi;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class ServicedeskplusApi
{

    protected $client;

    protected $config;

    protected $parameters = [];

    private $request_methods = [
        'GET',
        'POST',
        'PUT',
        'DELETE',
    ];

    /**
     * ServicedeskplusApi constructor.
     *
     * @param array $config
     */
    public function __construct($config)
    {
        $this->config = $config;
        $this->client = new Client(['base_uri' => $config['api_base_url']]);
    }

    public function __call($func, $params)
    {
        if (!$params) {
            return;
        }
        $method = strtoupper($func);
        if (in_array($method, $this->request_methods)) {
            $uri = $params[0];
            $this->setDefaultParameters();
            if (isset($params[1])) {
                $this->setQueryString($params[1]);
            }
            if (isset($params[2])) {
                $this->setFormParams($params[2]);
            }
            return call_user_func_array([$this, 'makeMethodRequest'], [$method, $uri]);
        }
    }

    /**
     */
    public function setDefaultParameters()
    {
        $this->parameters = [];
        $this->parameters['timeout'] = $this->config['timeout'] ?: 60;
        $this->parameters['headers'] = [
            'TECHNICIAN_KEY' => $this->config['technician_key'],
        ];
        if ($this->config['api_version'] === '1') {
            $this->parameters['query'] = [
                'format' => $this->config['api_v1_format'],
            ];
        }
        return $this;
    }

    /**
     * @param array $params
     */
    public function setHeaders($params = '')
    {
        if (is_array($params)) {
            if (isset($this->parameters['headers'])) {
                $this->parameters['headers'] = array_merge($this->parameters['headers'], $params);
            } else {
                $this->parameters['headers'] = $params;
            }
        }
        return $this;
    }

    /**
     * @param array|string $params
     */
    public function setQueryString($params = '')
    {
        if (is_array($params)) {
            if (isset($this->parameters['query'])) {
                $this->parameters['query'] = array_merge($this->parameters['query'], $params);
            } else {
                $this->parameters['query'] = $params;
            }
        } else if (!empty($params)) {
            $this->parameters['query'] = $params;
        }
        return $this;
    }

    /**
     * @param array $params
     */
    public function setFormParams($params = [])
    {
        if (is_array($params)) {
            $this->parameters['form_params'] = $params;
        }
        return $this;
    }

    /**
     * @param array $params
     */
    public function prepareInputData($params = [])
    {
        if (is_array($params)) {
            $inputDataKey = 'input_data';
            if ($this->config['api_version'] === '1') {
                $inputDataKey = 'INPUT_DATA';
            }
            return [$inputDataKey => json_encode($params)];
        }
        return;
    }

    public function makeMethodRequest($method, $uri)
    {
        try {
            $response = $this->client->request($method, $uri, $this->parameters);
            return $this->getResponseData($response);
        } catch (\Exception $exception) {
            $message = $exception->getMessage();
            Log::error($message);
            throw new \Exception($message, $code = 0, $exception);
        }
    }

    /**
     * Getting Response Data.
     *
     * @param $response
     *
     * @return bool|mixed
     */
    protected function getResponseData($response)
    {
        $header = explode(';', $response->getHeader('Content-Type')[0]);
        $contentType = $header[0];
        $contents = $response->getBody()->getContents();
        if ($contentType == 'application/json') {
            $contentObject = json_decode($contents);
            if (json_last_error() == JSON_ERROR_NONE) {
                return $contentObject;
            }
            Log::error(json_last_error_msg());
        } else if ($contentType == 'text/xml') {
            libxml_use_internal_errors(true);
            $contentObject = simplexml_load_string($contents);
            if ($contentObject !== false) {
                return $contentObject;
            }
            if ($libxmlError = libxml_get_last_error()) {
                Log::error($libxmlError->message);
            }
        } else {
            Log::warning('Can\'t transform response with Content-Type ' . $contentType);
        }
        return $contents;
    }
}
