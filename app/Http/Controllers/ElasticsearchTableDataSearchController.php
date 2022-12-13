<?php

namespace App\Http\Controllers;

use Elastic\Elasticsearch\Exception\ClientResponseException;
use Elastic\Elasticsearch\Exception\ServerResponseException;
use Illuminate\Http\Request;

class ElasticsearchTableDataSearchController extends ElasticsearchBaseController
{
    /**
     * @throws ClientResponseException
     * @throws ServerResponseException
     */
    public function termSearchToTable(Request $request)
    {
        $query = [
            'index' => $request->input('table'),
            'type' => '_doc',
            'body' => [
                'query' => [
                    'term' => [
                        'name' => $request->input('name')
                    ]
                ]
            ]
        ];

        return $this->client->search($query);
    }

    /**
     * @throws ClientResponseException
     * @throws ServerResponseException
     */
    public function termsSearchToTable(Request $request)
    {
        $query = [
            'index' => $request->input('table'),
            'type' => '_doc',
            'body' => [
                'query' => [
                    'terms' => [
                        'name' => $request->input('name')
                    ]
                ]
            ]
        ];

        return $this->client->search($query);
    }

    /**
     * @throws ServerResponseException
     * @throws ClientResponseException
     */
    public function wildcardToTable(Request $request)
    {
        $query = [
            'index' => $request->input('table'),
            'type' => '_doc',
            'body' => [
                'query' => [
                    'wildcard' => [
                        'name' => [
                            'value' => $request->input('search'),
                            'boost' => 1.0 // benzerlik oranı
                        ]
                    ]
                ]
            ]
        ];

        return $this->client->search($query);
    }

    /**
     * @throws ClientResponseException
     * @throws ServerResponseException
     */
    public function rangeToTable(Request $request)
    {
        /*
         * gt 20 = 20 den büyük
         * gte 20 = 20 ve 20den büyük
         *
         * lt 20 = 20 den küçük
         * lte 20 = 20 ve 20den küçük
         *
         * */

        $query = [
            'index' => $request->input('table'),
            'type' => '_doc',
            'body' => [
                'query' => [
                    'range' => [
                        'age' => [
                            'gte' => $request->input('gte'),
                            'lte' => $request->input('lte')
                        ]
                    ]
                ]
            ]
        ];

        return $this->client->search($query);
    }

    /**
     * @throws ServerResponseException
     * @throws ClientResponseException
     */
    public function sortToTable(Request $request)
    {
        $query = [
            'index' => $request->input('table'),
            'type' => '_doc',
            'body' => [
                'query' => [
                    'wildcard' => [
                        'name' => '*'
                    ]
                ],
                'sort' => [
                    'age' => [
                        'order' => $request->input('order')
                    ]
                ]
            ]
        ];

        return $this->client->search($query);
    }

    /**
     * @throws ServerResponseException
     * @throws ClientResponseException
     */
    public function fromSizeToTable(Request $request)
    {
        $query = [
            'index' => $request->input('table'),
            'type' => '_doc',
            'from' => $request->input('from'),
            'size' => $request->input('size'),
            'body' => [
                'query' => [
                    'wildcard' => [
                        'name' => '*'
                    ]
                ]
            ]
        ];

        return $this->client->search($query);
    }

    /**
     * @throws ServerResponseException
     * @throws ClientResponseException
     */
    public function minScoreToTable(Request $request)
    {
        $query = [
            'index' => $request->input('table'),
            'type' => '_doc',
            'body' => [
                'min_score' => $request->input('min_score'),
                'explain' => $request->input('explain'),
                'query' => [
                    'term' => [
                        'name' => $request->input('name')
                    ]
                ]
            ]
        ];

        return $this->client->search($query);
    }

    /**
     * @throws ServerResponseException
     * @throws ClientResponseException
     */
    public function validateQuery(Request $request)
    {
        $query = [
            'index' => $request->input('table'),
            'type' => '_doc',
            'body' => [
                'query' => [
                    'terms' => [
                        'name' => $request->input('name')
                    ]
                ]
            ]
        ];

        return $this->client->indices()->validateQuery($query);
    }

    /**
     * @throws ServerResponseException
     * @throws ClientResponseException
     */
    public function getQueryCount(Request $request)
    {
        $query = [
            'index' => $request->input('table'),
            'type' => '_doc',
            'body' => [
                'query' => [
                    'term' => [
                        'name' => $request->input('name')
                    ]
                ]
            ]
        ];

        return $this->client->count($query);
    }
}


