<?php

namespace App\Console\Commands;

use App\Models\Member;
use App\Services\Elasticsearch\ElasticsearchService;
use Elastic\Elasticsearch\Client;
use Elastic\Elasticsearch\Exception\AuthenticationException;
use Elastic\Elasticsearch\Exception\ClientResponseException;
use Elastic\Elasticsearch\Exception\MissingParameterException;
use Elastic\Elasticsearch\Exception\ServerResponseException;
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
    protected $signature = 'elasticsearch:add-members-data-to-elasticsearch';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add members data to elasticsearch';

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
     * @throws ClientResponseException
     * @throws MissingParameterException
     * @throws ServerResponseException
     */
    public function handle()
    {
        $indexName = 'uyeler';

        $members = Member::orderByDesc('id')->take(10)->get();

        foreach ($members as $member) {

            $params = [
                'index' => $indexName,
                'id' => $member->id,
                'timeout' => '5s',
                'client' => [
                    'timeout' => 6,
                    'connect_timeout' => 1
                ],
                'body' => $member->getAttributes()
            ];

            $this->client->index($params);
        }

    }
}

