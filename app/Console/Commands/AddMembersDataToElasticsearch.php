<?php

namespace App\Console\Commands;

use App\Services\Elasticsearch\ElasticsearchService;
use Elastic\Elasticsearch\Client;
use Elastic\Elasticsearch\Exception\AuthenticationException;
use Illuminate\Console\Command;

/**
 * Class AddMembersDataToElasticsearch
 * @package App\Console\Commands
 */
class AddMembersDataToElasticsearch extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'elasticsearch:add-members-to-elasticsearch';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add members to elasticsearch';

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
     */
    public function handle()
    {
        $indexName = 'uyeler';

        $params = [
            'index' => $indexName,
            'body' => [
                'settings' => [
                    'number_of_shards' => 3,
                    'number_of_replicas' => 2
                ],
                'mappings' => [
                    '_source' => [
                        'enabled' => true
                    ],
                    'properties' => [
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

        if (!$this->client->indices()->exists(["index" => $indexName])) {
            $this->client->create($params);
        }
    }
}

