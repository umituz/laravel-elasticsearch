<?php

namespace App\Http\Controllers;

use Elastic\Elasticsearch\Exception\ClientResponseException;
use Elastic\Elasticsearch\Exception\MissingParameterException;
use Elastic\Elasticsearch\Exception\ServerResponseException;
use Illuminate\Http\Request;

class ElasticsearchTableController extends ElasticsearchBaseController
{
    /**
     * @throws ServerResponseException
     * @throws ClientResponseException
     * @throws MissingParameterException
     */
    public function createTable(Request $request)
    {
        $params = [
            'index' => $request->input('table'),
            'body' => [
                'settings' => [
                    'number_of_shards' => 3,
                    'number_of_replicas' => 2
                ],
                'mappings' => [
                    'properties' => [
                        'name' => [
                            'type' => 'keyword'
                        ],
                        'message' => [
                            'type' => 'text',
                            'analyzer' => $request->input('analyzer'),
                        ],
                        'age' => [
                            'type' => 'integer'
                        ]
                    ]
                ]
            ]
        ];

        return $this->client->indices()->create($params);
    }

    /**
     * @throws ServerResponseException
     * @throws ClientResponseException
     * @throws MissingParameterException
     */
    public function deleteTable(Request $request)
    {
        $params = [
            'index' => $request->input('table'),
        ];

        return $this->client->indices()->delete($params);
    }

    /**
     * @throws ServerResponseException
     * @throws ClientResponseException
     * @throws MissingParameterException
     */
    public function deleteTables(Request $request)
    {
        $params = [
            'index' => explode(',', $request->input('table'))
        ];

        return $this->client->indices()->delete($params);
    }

    /**
     * @throws ServerResponseException
     * @throws ClientResponseException
     */
    public function getTablesDetail()
    {
        $params = [];

        return $this->client->indices()->getSettings($params);
    }

    /**
     * @throws ServerResponseException
     * @throws ClientResponseException
     */
    public function getAllTables()
    {
        $params = [
            'index' => '*'
        ];

        return $this->client->cat()->indices($params);
    }
}
