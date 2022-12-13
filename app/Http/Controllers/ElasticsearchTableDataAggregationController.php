<?php

namespace App\Http\Controllers;

use Elastic\Elasticsearch\Exception\ClientResponseException;
use Elastic\Elasticsearch\Exception\ServerResponseException;
use Illuminate\Http\Request;

class ElasticsearchTableDataAggregationController extends ElasticsearchBaseController
{
    /**
     * @throws ServerResponseException
     * @throws ClientResponseException
     */
    public function aggregationTermList(Request $request)
    {
        $query = [
            'index' => $request->input('table'),
            'type' => '_doc',
            'size' => 0,
            'body' => [
                'aggs' => [
                    'custom_name_groupping' => [
                        'terms' => [
                            'field' => 'name',
                            'size' => $request->input('size')
                        ]
                    ]
                ],
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
    public function aggregationMinMaxSumAvg(Request $request)
    {
        $query = [
            'index' => $request->input('table'),
            'type' => '_doc',
            'size' => 100,
            'body' => [
                'query' => [
                    'wildcard' => [
                        'name' => '*'
                    ]
                ],
                'aggs' => [
                    'maximum_age' => [
                        'max' => [
                            'field' => 'age'
                        ]
                    ],
                    'minimum_age' => [
                        'min' => [
                            'field' => 'age'
                        ]
                    ],
                    'total_age' => [
                        'sum' => [
                            'field' => 'age'
                        ]
                    ],
                    'average_age' => [
                        'avg' => [
                            'field' => 'age'
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
    public function aggregationStats(Request $request)
    {
        $query = [
            'index' => $request->input('table'),
            'type' => '_doc',
            'size' => 0,
            'body' => [
                'query' => [
                    'wildcard' => [
                        'name' => '*'
                    ]
                ],
                'aggs' => [
                    'custom_statistics' => [
                        'stats' => [
                            'field' => 'age'
                        ],
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
    public function aggregationExtendedStats(Request $request)
    {
        $query = [
            'index' => $request->input('table'),
            'type' => '_doc',
            'size' => 0,
            'body' => [
                'query' => [
                    'wildcard' => [
                        'name' => '*'
                    ]
                ],
                'aggs' => [
                    'custom_statistics' => [
                        'extended_stats' => [
                            'field' => 'age'
                        ],
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
    public function aggregationFromRange(Request $request)
    {
        $query = [
            'index' => $request->input('table'),
            'type' => '_doc',
            'size' => 0,
            'body' => [
                'query' => [
                    'wildcard' => [
                        'name' => '*'
                    ]
                ],
                'aggs' => [
                    'custom_statistics' => [
                        'range' => [
                            'field' => 'age',
                            'ranges' => [
                                [
                                    'to' => $request->input('to')
                                ],
                                [
                                    'from' => $request->input('from'),
                                    'to' => $request->input('to')
                                ],
                                [
                                    'from' => $request->input('from')
                                ],
                            ]
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
    public function aggregationIPAddressesRange(Request $request)
    {
        $query = [
            'index' => 'ip-address',
            'size' => 0,
            'body' => [
                'aggs' => [
                    'custom_statistics' => [
                        'ip_range' => [
                            'field' => 'ip',
                            'ranges' => [
                                [
                                    'to' => '10.0.0.5'
                                ],
                                [
                                    'from' => '10.0.0.5',
                                    'to' => '10.0.0.100'
                                ],
                                [
                                    'from' => '10.0.0.100'
                                ]
                            ]
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
    public function aggregationIPAddressesMaskRange(Request $request)
    {
        $query = [
            'index' => 'ip-address',
            'size' => 0,
            'body' => [
                'aggs' => [
                    'custom_statistics' => [
                        'ip_range' => [
                            'field' => 'ip',
                            'ranges' => [
                                [
                                    'mask' => '10.0.0.5/24'
                                ],
                                [
                                    'mask' => '10.0.0.100/16'
                                ],
                                [
                                    'mask' => '192.168.0.1/24'
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ];

        return $this->client->search($query);
    }
}
