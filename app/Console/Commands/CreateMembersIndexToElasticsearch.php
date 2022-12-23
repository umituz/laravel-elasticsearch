<?php

namespace App\Console\Commands;

use App\Services\Elasticsearch\ElasticsearchService;
use Elastic\Elasticsearch\Client;
use Elastic\Elasticsearch\Exception\AuthenticationException;
use Elastic\Elasticsearch\Exception\ClientResponseException;
use Elastic\Elasticsearch\Exception\MissingParameterException;
use Elastic\Elasticsearch\Exception\ServerResponseException;
use Exception;
use Illuminate\Console\Command;
use const Grpc\STATUS_OK;

/**
 * Class CreateMembersIndexToElasticsearch
 * @package App\Console\Commands
 */
class CreateMembersIndexToElasticsearch extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'elasticsearch:create-members-index-to-elasticsearch';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create members index to elasticsearch';

    private Client $client;

    /**
     * @throws AuthenticationException
     */
    public function __construct(ElasticsearchService $elasticsearchService)
    {
        parent::__construct();

        $this->client = $elasticsearchService->getClient();
    }

    /**
     * Execute the console command.
     *
     * @return int
     * @throws ClientResponseException
     * @throws MissingParameterException
     * @throws ServerResponseException
     */
    public function handle()
    {
        $indexName = 'uyeler';

        if ($this->client->indices()->exists(["index" => $indexName])->getStatusCode() === 404) {

            try {
                $params = [
                    'index' => $indexName,
                    'body' => [
                        'settings' => [
                            'number_of_shards' => 3,
                            'number_of_replicas' => 2,
//                        'max_terms_count' => 655360,
                        ],
                        'mappings' => [
                            '_source' => [
                                'enabled' => true
                            ],
                            'properties' => [
                                'id' => [
                                    'type' => 'long'
                                ],
                                'isim' => [
                                    'type' => 'keyword'
                                ],
                                'soyad' => [
                                    'type' => 'keyword'
                                ],
                                'cinsiyet' => [
                                    'type' => 'keyword'
                                ],
                                'ulke' => [
                                    'type' => 'keyword'
                                ],
                                'ip_address' => [
                                    'type' => 'ip'
                                ],
                                'konum' => [
                                    'type' => 'geo_point'
                                ],
                                'dogum_tarihi' => [
                                    'type' => 'short'
                                ],
                                'yas' => [
                                    'type' => 'short'
                                ],
                                'ekleme_tarihi' => [
                                    'type' => 'long'
                                ],

                            ]
                        ]
                    ]
                ];

                $result = $this->client->indices()->create($params);

                if ($result->getStatusCode() === 200) {
                    echo "{$indexName} created successfully!";
                }

            } catch (Exception $exception) {
                echo $exception->getMessage();
            }
        }

        echo "{$indexName} already exists";
    }
}

