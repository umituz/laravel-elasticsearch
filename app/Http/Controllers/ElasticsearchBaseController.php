<?php

namespace App\Http\Controllers;

use App\Services\Elasticsearch\ElasticsearchService;
use Elastic\Elasticsearch\Client;
use Elastic\Elasticsearch\Exception\AuthenticationException;

/**
 * Class ElasticsearchBaseController
 * @package App\Http\Controllers
 */
class ElasticsearchBaseController extends Controller
{
    protected ElasticsearchService $elasticsearchService;
    protected Client $client;

    /**
     * @param ElasticsearchService $elasticsearchService
     * @throws AuthenticationException
     */
    public function __construct(ElasticsearchService $elasticsearchService)
    {
        $this->elasticsearchService = $elasticsearchService;
        $this->client = $this->elasticsearchService->getClient();
        $this->elasticsearchService->enableErrorLogConfigurations();
    }
}
