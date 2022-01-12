<?php

namespace App\Services\RestApi;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\TransferStats;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Generic Guzzle Http Client Request Service
 * provides logging & retries
 *
 * Class ApiClient
 * @package App\Services
 */
class ApiClientRequestService
{
    protected $logger;
    protected $contentType = 'application/json';
    protected $acceptType = 'application/json';

    public function __construct()
    {
        $this->logger = app("log");
    }

    /**
     *
     * Send Client Api Request
     *
     * @param string $method
     * @param $uri
     * @param array $data
     * @param array $headers
     * @param array $options
     * @param int $retries
     * @return mixed
     * @throws GuzzleException
     */
    public function send(
        string $method,
               $uri,
        array  $data = [],
        array  $headers = [],
        array  $options = [],
        int    $retries = 2
    )
    {
        try {

            $headers = array_merge([
                'Content-Type' => $this->contentType,
                'Accept-Type' => $this->acceptType,
            ], $headers);

            if (!empty($data) && is_array($data) && in_array(strtoupper($method), ['POST', 'PUT', 'PATCH'])) {
                if ($headers['Content-Type'] == 'application/x-www-form-urlencoded') {
                    $data = http_build_query($data);
                } else {
                    if ($headers['Content-Type'] == 'application/json') {
                        $data = json_encode($data, JSON_UNESCAPED_SLASHES);
                    }
                }
            }
            if (empty($data)) {
                $data = "";
            }
            $client = new Client;
            $request = new Request(
                $method,
                $uri,
                $headers,
                $data
            );

            $res = $client->send($request,
                array_merge(
                    [
                        'on_stats' => [$this, 'logStats'],
                        'connect_timeout' => '3'
                    ], $options
                )
            );

            $body = (string)$res->getBody();

            return json_decode($body);

        } catch (RequestException $e) {
            switch (get_class($e)) {
                // networking error
                case ConnectException::class:
                    $this->logger->critical(
                        sprintf(
                            'Network error while attempting to connect to %s. Error Message: %s',
                            $uri,
                            $e->getMessage()
                        )
                    );

                    // retry
                    if ($retries) {
                        $this->logger->debug('Trying to call ' . $uri . ' one more time...');
                        return $this->send($method, $uri, $data, $headers, $options, --$retries);
                    }

                    throw new HttpException(503, 'Service Unavailable');
                    break;
                // 400 range of errors
                case ClientException::class:
                    $this->logger->debug(
                        sprintf(
                            'Http Error: %s while attempting to connect to %s. Message: %s',
                            $e->getResponse()->getStatusCode(),
                            $e->getRequest()->getUri(),
                            $e->getMessage()
                        )
                    );
                    throw new HttpException(
                        400, $e->getResponse()->getBody()
                    );
                    break;
                // 500 range of errors
                case ServerException::class:
                    $this->logger->debug(
                        sprintf(
                            'Http Error: %s while attempting to connect to %s. Message: %s',
                            $e->getResponse()->getStatusCode(),
                            $e->getRequest()->getUri(),
                            $e->getMessage()
                        )
                    );
                    throw new HttpException(
                        500, $e->getResponse()->getBody()
                    );
                    break;
                default:
                    $this->logger->debug(
                        sprintf(
                            'Http Error while attempting to connect to %s. Message: %s',
                            $uri,
                            $e->getMessage()
                        )
                    );
            }

            throw $e;
        }
    }

    /**
     * logStats
     *
     * @param TransferStats $stats
     */
    public function logStats(TransferStats $stats)
    {

        $stats = $stats->getHandlerStats();
        $this->logger->info(sprintf(
            'Request: %s Status: %s Connection Time: %s Total Time: %s Primary IP: %s',
            $stats['url'],
            $stats['http_code'],
            $stats['connect_time'],
            $stats['total_time'],
            $stats['primary_ip']
        ));
    }

}
