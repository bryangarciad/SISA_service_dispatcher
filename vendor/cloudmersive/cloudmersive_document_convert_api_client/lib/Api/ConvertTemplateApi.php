<?php
/**
 * ConvertTemplateApi
 * PHP version 5
 *
 * @category Class
 * @package  Swagger\Client
 * @author   Swagger Codegen team
 * @link     https://github.com/swagger-api/swagger-codegen
 */

/**
 * convertapi
 *
 * Convert API lets you effortlessly convert file formats and types.
 *
 * OpenAPI spec version: v1
 * 
 * Generated by: https://github.com/swagger-api/swagger-codegen.git
 * Swagger Codegen version: 2.3.1
 */

/**
 * NOTE: This class is auto generated by the swagger code generator program.
 * https://github.com/swagger-api/swagger-codegen
 * Do not edit the class manually.
 */

namespace Swagger\Client\Api;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\MultipartStream;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\RequestOptions;
use Swagger\Client\ApiException;
use Swagger\Client\Configuration;
use Swagger\Client\HeaderSelector;
use Swagger\Client\ObjectSerializer;

/**
 * ConvertTemplateApi Class Doc Comment
 *
 * @category Class
 * @package  Swagger\Client
 * @author   Swagger Codegen team
 * @link     https://github.com/swagger-api/swagger-codegen
 */
class ConvertTemplateApi
{
    /**
     * @var ClientInterface
     */
    protected $client;

    /**
     * @var Configuration
     */
    protected $config;

    /**
     * @param ClientInterface $client
     * @param Configuration   $config
     * @param HeaderSelector  $selector
     */
    public function __construct(
        ClientInterface $client = null,
        Configuration $config = null,
        HeaderSelector $selector = null
    ) {
        $this->client = $client ?: new Client();
        $this->config = $config ?: new Configuration();
        $this->headerSelector = $selector ?: new HeaderSelector();
    }

    /**
     * @return Configuration
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * Operation convertTemplateApplyDocxTemplate
     *
     * Apply Word DOCX template
     *
     * @param  \SplFileObject $input_file Input file to perform the operation on. (required)
     * @param  string $template_definition Template definition for the document, including what values to replace (optional)
     *
     * @throws \Swagger\Client\ApiException on non-2xx response
     * @throws \InvalidArgumentException
     * @return string
     */
    public function convertTemplateApplyDocxTemplate($input_file, $template_definition = null)
    {
        list($response) = $this->convertTemplateApplyDocxTemplateWithHttpInfo($input_file, $template_definition);
        return $response;
    }

    /**
     * Operation convertTemplateApplyDocxTemplateWithHttpInfo
     *
     * Apply Word DOCX template
     *
     * @param  \SplFileObject $input_file Input file to perform the operation on. (required)
     * @param  string $template_definition Template definition for the document, including what values to replace (optional)
     *
     * @throws \Swagger\Client\ApiException on non-2xx response
     * @throws \InvalidArgumentException
     * @return array of string, HTTP status code, HTTP response headers (array of strings)
     */
    public function convertTemplateApplyDocxTemplateWithHttpInfo($input_file, $template_definition = null)
    {
        $returnType = 'string';
        $request = $this->convertTemplateApplyDocxTemplateRequest($input_file, $template_definition);

        try {
            $options = $this->createHttpClientOption();
            try {
                $response = $this->client->send($request, $options);
            } catch (RequestException $e) {
                throw new ApiException(
                    "[{$e->getCode()}] {$e->getMessage()}",
                    $e->getCode(),
                    $e->getResponse() ? $e->getResponse()->getHeaders() : null,
                    $e->getResponse() ? $e->getResponse()->getBody()->getContents() : null
                );
            }

            $statusCode = $response->getStatusCode();

            if ($statusCode < 200 || $statusCode > 299) {
                throw new ApiException(
                    sprintf(
                        '[%d] Error connecting to the API (%s)',
                        $statusCode,
                        $request->getUri()
                    ),
                    $statusCode,
                    $response->getHeaders(),
                    $response->getBody()
                );
            }

            $responseBody = $response->getBody();
            if ($returnType === '\SplFileObject') {
                $content = $responseBody; //stream goes to serializer
            } else {
                $content = $responseBody->getContents();
                if ($returnType !== 'string') {
                    $content = json_decode($content);
                }
            }

            return [
                ObjectSerializer::deserialize($content, $returnType, []),
                $response->getStatusCode(),
                $response->getHeaders()
            ];

        } catch (ApiException $e) {
            switch ($e->getCode()) {
                case 200:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        'string',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    break;
            }
            throw $e;
        }
    }

    /**
     * Operation convertTemplateApplyDocxTemplateAsync
     *
     * Apply Word DOCX template
     *
     * @param  \SplFileObject $input_file Input file to perform the operation on. (required)
     * @param  string $template_definition Template definition for the document, including what values to replace (optional)
     *
     * @throws \InvalidArgumentException
     * @return \GuzzleHttp\Promise\PromiseInterface
     */
    public function convertTemplateApplyDocxTemplateAsync($input_file, $template_definition = null)
    {
        return $this->convertTemplateApplyDocxTemplateAsyncWithHttpInfo($input_file, $template_definition)
            ->then(
                function ($response) {
                    return $response[0];
                }
            );
    }

    /**
     * Operation convertTemplateApplyDocxTemplateAsyncWithHttpInfo
     *
     * Apply Word DOCX template
     *
     * @param  \SplFileObject $input_file Input file to perform the operation on. (required)
     * @param  string $template_definition Template definition for the document, including what values to replace (optional)
     *
     * @throws \InvalidArgumentException
     * @return \GuzzleHttp\Promise\PromiseInterface
     */
    public function convertTemplateApplyDocxTemplateAsyncWithHttpInfo($input_file, $template_definition = null)
    {
        $returnType = 'string';
        $request = $this->convertTemplateApplyDocxTemplateRequest($input_file, $template_definition);

        return $this->client
            ->sendAsync($request, $this->createHttpClientOption())
            ->then(
                function ($response) use ($returnType) {
                    $responseBody = $response->getBody();
                    if ($returnType === '\SplFileObject') {
                        $content = $responseBody; //stream goes to serializer
                    } else {
                        $content = $responseBody->getContents();
                        if ($returnType !== 'string') {
                            $content = json_decode($content);
                        }
                    }

                    return [
                        ObjectSerializer::deserialize($content, $returnType, []),
                        $response->getStatusCode(),
                        $response->getHeaders()
                    ];
                },
                function ($exception) {
                    $response = $exception->getResponse();
                    $statusCode = $response->getStatusCode();
                    throw new ApiException(
                        sprintf(
                            '[%d] Error connecting to the API (%s)',
                            $statusCode,
                            $exception->getRequest()->getUri()
                        ),
                        $statusCode,
                        $response->getHeaders(),
                        $response->getBody()
                    );
                }
            );
    }

    /**
     * Create request for operation 'convertTemplateApplyDocxTemplate'
     *
     * @param  \SplFileObject $input_file Input file to perform the operation on. (required)
     * @param  string $template_definition Template definition for the document, including what values to replace (optional)
     *
     * @throws \InvalidArgumentException
     * @return \GuzzleHttp\Psr7\Request
     */
    protected function convertTemplateApplyDocxTemplateRequest($input_file, $template_definition = null)
    {
        // verify the required parameter 'input_file' is set
        if ($input_file === null) {
            throw new \InvalidArgumentException(
                'Missing the required parameter $input_file when calling convertTemplateApplyDocxTemplate'
            );
        }

        $resourcePath = '/convert/template/docx/apply';
        $formParams = [];
        $queryParams = [];
        $headerParams = [];
        $httpBody = '';
        $multipart = false;

        // header params
        if ($template_definition !== null) {
            $headerParams['templateDefinition'] = ObjectSerializer::toHeaderValue($template_definition);
        }


        // form params
        if ($input_file !== null) {
            $multipart = true;
            $formParams['inputFile'] = \GuzzleHttp\Psr7\try_fopen(ObjectSerializer::toFormValue($input_file), 'rb');
        }
        // body params
        $_tempBody = null;

        if ($multipart) {
            $headers = $this->headerSelector->selectHeadersForMultipart(
                ['application/octet-stream']
            );
        } else {
            $headers = $this->headerSelector->selectHeaders(
                ['application/octet-stream'],
                ['multipart/form-data']
            );
        }

        // for model (json/xml)
        if (isset($_tempBody)) {
            // $_tempBody is the method argument, if present
            $httpBody = $_tempBody;
            // \stdClass has no __toString(), so we should encode it manually
            if ($httpBody instanceof \stdClass && $headers['Content-Type'] === 'application/json') {
                $httpBody = \GuzzleHttp\json_encode($httpBody);
            }
        } elseif (count($formParams) > 0) {
            if ($multipart) {
                $multipartContents = [];
                foreach ($formParams as $formParamName => $formParamValue) {
                    $multipartContents[] = [
                        'name' => $formParamName,
                        'contents' => $formParamValue
                    ];
                }
                // for HTTP post (form)
                $httpBody = new MultipartStream($multipartContents);

            } elseif ($headers['Content-Type'] === 'application/json') {
                $httpBody = \GuzzleHttp\json_encode($formParams);

            } else {
                // for HTTP post (form)
                $httpBody = \GuzzleHttp\Psr7\build_query($formParams);
            }
        }

        // this endpoint requires API key authentication
        $apiKey = $this->config->getApiKeyWithPrefix('Apikey');
        if ($apiKey !== null) {
            $headers['Apikey'] = $apiKey;
        }

        $defaultHeaders = [];
        if ($this->config->getUserAgent()) {
            $defaultHeaders['User-Agent'] = $this->config->getUserAgent();
        }

        $headers = array_merge(
            $defaultHeaders,
            $headerParams,
            $headers
        );

        $query = \GuzzleHttp\Psr7\build_query($queryParams);
        return new Request(
            'POST',
            $this->config->getHost() . $resourcePath . ($query ? "?{$query}" : ''),
            $headers,
            $httpBody
        );
    }

    /**
     * Operation convertTemplateApplyHtmlTemplate
     *
     * Apply HTML template
     *
     * @param  \Swagger\Client\Model\HtmlTemplateApplicationRequest $value Operations to apply to template (required)
     *
     * @throws \Swagger\Client\ApiException on non-2xx response
     * @throws \InvalidArgumentException
     * @return \Swagger\Client\Model\HtmlTemplateApplicationResponse
     */
    public function convertTemplateApplyHtmlTemplate($value)
    {
        list($response) = $this->convertTemplateApplyHtmlTemplateWithHttpInfo($value);
        return $response;
    }

    /**
     * Operation convertTemplateApplyHtmlTemplateWithHttpInfo
     *
     * Apply HTML template
     *
     * @param  \Swagger\Client\Model\HtmlTemplateApplicationRequest $value Operations to apply to template (required)
     *
     * @throws \Swagger\Client\ApiException on non-2xx response
     * @throws \InvalidArgumentException
     * @return array of \Swagger\Client\Model\HtmlTemplateApplicationResponse, HTTP status code, HTTP response headers (array of strings)
     */
    public function convertTemplateApplyHtmlTemplateWithHttpInfo($value)
    {
        $returnType = '\Swagger\Client\Model\HtmlTemplateApplicationResponse';
        $request = $this->convertTemplateApplyHtmlTemplateRequest($value);

        try {
            $options = $this->createHttpClientOption();
            try {
                $response = $this->client->send($request, $options);
            } catch (RequestException $e) {
                throw new ApiException(
                    "[{$e->getCode()}] {$e->getMessage()}",
                    $e->getCode(),
                    $e->getResponse() ? $e->getResponse()->getHeaders() : null,
                    $e->getResponse() ? $e->getResponse()->getBody()->getContents() : null
                );
            }

            $statusCode = $response->getStatusCode();

            if ($statusCode < 200 || $statusCode > 299) {
                throw new ApiException(
                    sprintf(
                        '[%d] Error connecting to the API (%s)',
                        $statusCode,
                        $request->getUri()
                    ),
                    $statusCode,
                    $response->getHeaders(),
                    $response->getBody()
                );
            }

            $responseBody = $response->getBody();
            if ($returnType === '\SplFileObject') {
                $content = $responseBody; //stream goes to serializer
            } else {
                $content = $responseBody->getContents();
                if ($returnType !== 'string') {
                    $content = json_decode($content);
                }
            }

            return [
                ObjectSerializer::deserialize($content, $returnType, []),
                $response->getStatusCode(),
                $response->getHeaders()
            ];

        } catch (ApiException $e) {
            switch ($e->getCode()) {
                case 200:
                    $data = ObjectSerializer::deserialize(
                        $e->getResponseBody(),
                        '\Swagger\Client\Model\HtmlTemplateApplicationResponse',
                        $e->getResponseHeaders()
                    );
                    $e->setResponseObject($data);
                    break;
            }
            throw $e;
        }
    }

    /**
     * Operation convertTemplateApplyHtmlTemplateAsync
     *
     * Apply HTML template
     *
     * @param  \Swagger\Client\Model\HtmlTemplateApplicationRequest $value Operations to apply to template (required)
     *
     * @throws \InvalidArgumentException
     * @return \GuzzleHttp\Promise\PromiseInterface
     */
    public function convertTemplateApplyHtmlTemplateAsync($value)
    {
        return $this->convertTemplateApplyHtmlTemplateAsyncWithHttpInfo($value)
            ->then(
                function ($response) {
                    return $response[0];
                }
            );
    }

    /**
     * Operation convertTemplateApplyHtmlTemplateAsyncWithHttpInfo
     *
     * Apply HTML template
     *
     * @param  \Swagger\Client\Model\HtmlTemplateApplicationRequest $value Operations to apply to template (required)
     *
     * @throws \InvalidArgumentException
     * @return \GuzzleHttp\Promise\PromiseInterface
     */
    public function convertTemplateApplyHtmlTemplateAsyncWithHttpInfo($value)
    {
        $returnType = '\Swagger\Client\Model\HtmlTemplateApplicationResponse';
        $request = $this->convertTemplateApplyHtmlTemplateRequest($value);

        return $this->client
            ->sendAsync($request, $this->createHttpClientOption())
            ->then(
                function ($response) use ($returnType) {
                    $responseBody = $response->getBody();
                    if ($returnType === '\SplFileObject') {
                        $content = $responseBody; //stream goes to serializer
                    } else {
                        $content = $responseBody->getContents();
                        if ($returnType !== 'string') {
                            $content = json_decode($content);
                        }
                    }

                    return [
                        ObjectSerializer::deserialize($content, $returnType, []),
                        $response->getStatusCode(),
                        $response->getHeaders()
                    ];
                },
                function ($exception) {
                    $response = $exception->getResponse();
                    $statusCode = $response->getStatusCode();
                    throw new ApiException(
                        sprintf(
                            '[%d] Error connecting to the API (%s)',
                            $statusCode,
                            $exception->getRequest()->getUri()
                        ),
                        $statusCode,
                        $response->getHeaders(),
                        $response->getBody()
                    );
                }
            );
    }

    /**
     * Create request for operation 'convertTemplateApplyHtmlTemplate'
     *
     * @param  \Swagger\Client\Model\HtmlTemplateApplicationRequest $value Operations to apply to template (required)
     *
     * @throws \InvalidArgumentException
     * @return \GuzzleHttp\Psr7\Request
     */
    protected function convertTemplateApplyHtmlTemplateRequest($value)
    {
        // verify the required parameter 'value' is set
        if ($value === null) {
            throw new \InvalidArgumentException(
                'Missing the required parameter $value when calling convertTemplateApplyHtmlTemplate'
            );
        }

        $resourcePath = '/convert/template/html/apply';
        $formParams = [];
        $queryParams = [];
        $headerParams = [];
        $httpBody = '';
        $multipart = false;



        // body params
        $_tempBody = null;
        if (isset($value)) {
            $_tempBody = $value;
        }

        if ($multipart) {
            $headers = $this->headerSelector->selectHeadersForMultipart(
                ['application/json', 'text/json', 'application/xml', 'text/xml']
            );
        } else {
            $headers = $this->headerSelector->selectHeaders(
                ['application/json', 'text/json', 'application/xml', 'text/xml'],
                ['application/json', 'text/json', 'application/xml', 'text/xml', 'application/x-www-form-urlencoded']
            );
        }

        // for model (json/xml)
        if (isset($_tempBody)) {
            // $_tempBody is the method argument, if present
            $httpBody = $_tempBody;
            // \stdClass has no __toString(), so we should encode it manually
            if ($httpBody instanceof \stdClass && $headers['Content-Type'] === 'application/json') {
                $httpBody = \GuzzleHttp\json_encode($httpBody);
            }
        } elseif (count($formParams) > 0) {
            if ($multipart) {
                $multipartContents = [];
                foreach ($formParams as $formParamName => $formParamValue) {
                    $multipartContents[] = [
                        'name' => $formParamName,
                        'contents' => $formParamValue
                    ];
                }
                // for HTTP post (form)
                $httpBody = new MultipartStream($multipartContents);

            } elseif ($headers['Content-Type'] === 'application/json') {
                $httpBody = \GuzzleHttp\json_encode($formParams);

            } else {
                // for HTTP post (form)
                $httpBody = \GuzzleHttp\Psr7\build_query($formParams);
            }
        }

        // this endpoint requires API key authentication
        $apiKey = $this->config->getApiKeyWithPrefix('Apikey');
        if ($apiKey !== null) {
            $headers['Apikey'] = $apiKey;
        }

        $defaultHeaders = [];
        if ($this->config->getUserAgent()) {
            $defaultHeaders['User-Agent'] = $this->config->getUserAgent();
        }

        $headers = array_merge(
            $defaultHeaders,
            $headerParams,
            $headers
        );

        $query = \GuzzleHttp\Psr7\build_query($queryParams);
        return new Request(
            'POST',
            $this->config->getHost() . $resourcePath . ($query ? "?{$query}" : ''),
            $headers,
            $httpBody
        );
    }

    /**
     * Create http client option
     *
     * @throws \RuntimeException on file opening failure
     * @return array of http client options
     */
    protected function createHttpClientOption()
    {
        $options = [];
        if ($this->config->getDebug()) {
            $options[RequestOptions::DEBUG] = fopen($this->config->getDebugFile(), 'a');
            if (!$options[RequestOptions::DEBUG]) {
                throw new \RuntimeException('Failed to open the debug file: ' . $this->config->getDebugFile());
            }
        }

        return $options;
    }
}
