<?php

namespace App\Http\Controllers;

use Elastic\Elasticsearch\Exception\ClientResponseException;
use Elastic\Elasticsearch\Exception\MissingParameterException;
use Elastic\Elasticsearch\Exception\ServerResponseException;
use Elastic\Elasticsearch\Response\Elasticsearch;
use Http\Promise\Promise;
use Illuminate\Http\Request;

class ElasticsearchTableDataController extends ElasticsearchBaseController
{
    /**
     * @param Request $request
     * @return Elasticsearch|Promise
     * @throws ClientResponseException
     * @throws MissingParameterException
     * @throws ServerResponseException
     */
    public function addDataToTable(Request $request)
    {
        $params = [
            'index' => $request->input('table'),
            'type' => '_doc',
            'id' => $request->input('id'),
            'body' => [
                'name' => $request->input('name'),
                'age' => $request->input('age'),
                'message' => $request->input('message'),
            ]
        ];

        return $this->client->index($params);
    }

    /**
     * @param Request $request
     * @return Elasticsearch|Promise
     * @throws ClientResponseException
     * @throws MissingParameterException
     * @throws ServerResponseException
     */
    public function updateDataToTable(Request $request)
    {
        $params = [
            'index' => $request->input('table'),
            'type' => '_doc',
            'id' => $request->input('id'),
            'body' => [
                'doc' => [
                    'name' => $request->input('name'),
                    'age' => $request->input('age'),
                    'message' => $request->input('message'),
                ]
            ]
        ];

        return $this->client->update($params);
    }

    /**
     * @throws ServerResponseException
     * @throws ClientResponseException
     * @throws MissingParameterException
     */
    public function getDataToTable(Request $request)
    {
        $params = [
            'index' => $request->input('table'),
            'type' => '_doc',
            'id' => $request->input('id')
        ];

        return $this->client->get($params);
    }

    /**
     * @param Request $request
     * @return Elasticsearch|Promise
     * @throws ClientResponseException
     * @throws ServerResponseException
     */
    public function getManyDataToTable(Request $request)
    {
        $params = [
            'index' => $request->input('table'),
            'type' => '_doc',
            'body' => [
                'ids' => $request->input('ids')
            ]
        ];

        return $this->client->mget($params);
    }

    /**
     * @throws ServerResponseException
     * @throws ClientResponseException
     * @throws MissingParameterException
     */
    public function deleteDataToTable(Request $request)
    {
        $params = [
            'index' => $request->input('table'),
            'type' => '_doc',
            'id' => $request->input('id')
        ];

        return $this->client->delete($params);
    }

    /**
     * @throws ServerResponseException
     * @throws ClientResponseException
     * @throws MissingParameterException
     */
    public function deleteManyDataToTable(Request $request)
    {
        $params = [
            'index' => $request->input('table'),
            'type' => '_doc',
            'body' => [
                'query' => [
                    'match' => [
                        'message' => $request->input('message')
                    ]
                ]
            ]
        ];

        return $this->client->deleteByQuery($params);
    }

    /**
     * @throws ClientResponseException
     * @throws ServerResponseException
     */
    public function bulkDataToTable(Request $request)
    {
        /* İşlem-1 Ekleme */
        $query['body'][] = [
            'index' => [
                '_index' => $request->input('table'),
                '_id' => 1
            ]
        ];
        $query['body'][] = [
            'name' => 'Cesur',
            'date' => '27-11-2022',
            'message' => 'Elasticsearch dersleri'
        ];

        /* İşlem-2 Ekleme */
        $query['body'][] = [
            'index' => [
                '_index' => $request->input('table'),
                '_id' => 2
            ]
        ];
        $query['body'][] = [
            'name' => 'Aziz',
            'date' => '27-11-202',
            'message' => 'Elasticsearch dersleri'
        ];

//        /* İşlem-3 Ekleme */
        $query['body'][] = [
            'index' => [
                '_index' => $request->input('table'),
                '_id' => 3
            ]
        ];
        $query['body'][] = [
            'name' => 'Ümit',
            'date' => '27-11-2022',
            'message' => 'Elasticsearch dersleri'
        ];

//        /* İşlem-4 Silme */
        $query['body'][] = [
            'delete' => [
                '_index' => $request->input('table'),
                '_id' => 2
            ]
        ];

//        /* İşlem-5 Düzenleme */
        $query['body'][] = [
            'update' => [
                '_index' => $request->input('table'),
                '_id' => 1
            ]
        ];

        $query['body'][] = [
            'doc' => [
                'name' => 'Kenan'
            ]
        ];

        return $this->client->bulk($query);
    }
}
