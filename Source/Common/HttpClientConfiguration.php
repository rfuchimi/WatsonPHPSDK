<?php
/**
 * Copyright 2017 IBM Corp. All Rights Reserved.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *      http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace WatsonSDK\Common;

class HttpClientConfiguration {

    // Constants
    const METHOD_GET = 'GET';
    const METHOD_POST = 'POST';
    const METHOD_PUT = 'PUT';
    const METHOD_DELETE = 'DELETE';
    const METHOD_OPTION = 'OPTION';
    const METHOD_HEAD = 'HEAD';
    const METHOD_PATCH = 'PATCH';

    const DATA_TYPE_JSON = 'json';
    const DATA_TYPE_FORM = 'form_params';
    const DATA_TYPE_MULTIPART = 'multipart';

    // Request method
    private $_method;
    // Request URL
    private $_url;

    // The data type of request body
    private $_type;
    // The json option is used to easily upload JSON encoded data as the body of a request
    // A Content-Type header of application/json will be added if no Content-Type header is already present on the message
    // 
    // or
    // 
    // Used to send an application/x-www-form-urlencoded POST request
    private $_data;
    // Associative array of query string values or query string to add to the request.
    private $_query;
    // Associative array of headers to add to the request. 
    // Each key is the name of a header, and each value is a string or array of strings representing the header field values
    private $_header;
    // Float describing the timeout of the request in seconds. Use 0 to wait indefinitely (the default behavior)
    private $_timeout;
    // Pass an array of HTTP authentication parameters to use with the request. 
    // The array must contain the username in index [0], the password in index [1], and you can optionally provide a built-in authentication type in index [2]. 
    // Pass null to disable authentication for a request.
    private $_credentials;

    /**
     * Constructor
     * @param $url
     * @param $method
     * @param $query
     * @param $data
     * @param $type
     * @param $header
     * @param $timeout
     */
    function __construct($url = NULL, $method = self::METHOD_GET, $query = [], $data = [], $type = null, $header = [], $timeout = 0, $credentials = NULL) {

        $this->setURL($url);
        $this->setMethod($method);
        $this->setData($data);
        $this->setQuery($query);
        $this->setType($type);
        $this->setHeader($header);
        $this->setTimeout($timeout);
        $this->setCredentials($credentials);
    }

    /**
     * Convert configurations to Guzzle options (For developer to change HTTP client)
     * 
     * @return array
     */
    public function toOptions() {

        $options = array();
        $type = $this->getType();

        // Set request body
        if(is_array($this->getData()) && count($this->getData()) > 0) {
            $options[$type] = $this->getData();
        }

        // Set query
        if(is_array($this->getQuery()) && count($this->getQuery()) > 0) {
            $options['query'] = $this->getQuery();
        }
        // Set header
        if(is_array($this->getHeader()) && count($this->getHeader()) > 0) {
            $options['headers'] = $this->getHeader();
        }

        $options['auth'] = $this->getCredentials();

        // Set response timeout
        if($this->getTimeout() > 0) {
            $options['timeout'] = $this->getTimeout();
        }

        // TODO: Set connection timeout

        return $options;
    }

    /**
     * Get URL
     * @return string
     */
    public function getURL() {
        return $this->_url;
    }

    /**
     * Set URL
     * @param $val string
     */
    public function setURL($val) {
        $this->_url = $val;
    }

    /**
     * Get method
     * return string
     */
    public function getMethod() {
        return $this->_method;
    }

    /**
     * Set HTTP method
     * @param $val string
     */
    public function setMethod($val) {
        $this->_method = $val;
    }

    /**
     * GET data type
     * return string
     */
    public function getType() {
        return $this->_type;
    }

    /**
     * Set data type
     * @param $val string
     */
    public function setType($val) {
        $this->_type = $val;
    }

    /**
     * Get request data
     * @return array
     */
    public function getData() {
        return $this->_data;
    }

    /**
     * Set request data
     * @param $val array
     */
    public function setData($val) {
        $this->_data = $val;
    }

    /**
     * Append request data
     * @param $val array
     */
    public function appendData($val) {
        $this->_data = array_merge($this->_data, $val);
    }

    /**
     * Update request data
     * @param $key string
     * @param $val mix
     */
    public function updateData($key, $val) {
        $this->_data[$key] = $val;
    }

    /**
     * Get request query
     * @return array
     */
    public function getQuery() {
        return $this->_query;
    }

    /**
     * Set request query
     * @param $val array
     */
    public function setQuery($val) {
        $this->_query = $val;
    }

    /**
     * Append request query
     * @param $val array
     */
    public function appendQuery($val) {
        $this->_query = array_merge($this->_query, $val);
    }

    /**
     * Get request header
     * @return array
     */
    public function getHeader() {
        return $this->_header;
    }

    /**
     * Set request header
     * @param $val array
     */
    public function setHeader($val) {
        $this->_header = $val;
    }

    /**
     * Append request header
     * @param $val array
     */
    public function appendHeader($val) {
        $this->_header = array_merge($this->_header, $val);;
    }

    /**
     * Update request header
     * @param $key string
     * @param $val mix
     */
    public function updateHeader($key, $val) {
        $this->_header[$key] = $val;
    }

    /**
     * Get resonse timeout
     * @return Float
     */
    public function getTimeout() {
        return $this->_timeout;
    }

    /**
     * Set response timeout 
     * @param $val Float
     */
    public function setTimeout($val) {
        $this->_timeout = $val;
    }

    /**
     * Get credentials
     * @return array
     */
    private function getCredentials() {
        return $this->_credentials;
    }

    /**
     * Set credentials
     * @param $val array
     */
    public function setCredentials($val) {
        $this->_credentials = $val;
    }
}