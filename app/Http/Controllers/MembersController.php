<?php

namespace App\Http\Controllers;

use App\Models\Member;
use Elastic\Elasticsearch\Exception\ClientResponseException;
use Elastic\Elasticsearch\Exception\ServerResponseException;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

/**
 * Class MembersController
 * @package App\Http\Controllers
 */
class MembersController extends ElasticsearchBaseController
{
    public function insertDataFromFilesToMysql()
    {
        Artisan::call('migrate:fresh');
        $files = File::files('json');

        foreach ($files as $file) {
            $members = json_decode($file->getContents(), true);

            $this->addData($members);

            if (!$members) {
                dd("something wrong", $members, $file->getContents());
            }

            Member::insert($members);

        }

        dd("done!");
    }

    private function addData($items)
    {
        $itemsTemp = [];

        if (count($items) > 1000) {
            $itemsTemp = array_slice($items, 1000);
            $items = array_slice($items, 0, 1000);
        }

        if (DB::table('members')->insert($items)) {
            if ($itemsTemp) {
                $this->addData($itemsTemp);
            }
        } else {
            return false;
        }
        return true;


    }

    /**
     * @return Application|Factory|View
     */
    public function membersPage(): View|Factory|Application
    {
        return view('members');
    }

    /**
     * @param Request $request
     * @return false|string
     * @throws ClientResponseException
     * @throws ServerResponseException
     */
    public function term(Request $request)
    {
        $durum = array();
        $tablo = $request->all();

        $baslangic_zamani = time();

        $sorgu = [
            'index' => 'uyeler',
            'type' => '_doc',
            'size' => $tablo["adet"],
            'body' => [
                'query' => [
                    'term' => [
                        'isim' => $tablo["terim"]
                    ]
                ]
            ]
        ];

        $sonuc = $this->client->search($sorgu)->asArray();

        $durum["durum"] = true;
        $durum["mesaj"] = "Islem Basarili";
        $durum["arama_suresi"] = time() - $baslangic_zamani;
        $durum["elasticsearch"] = $sonuc;

        return json_encode($durum);
    }

    /**
     * @param Request $request
     * @return false|string
     * @throws ClientResponseException
     * @throws ServerResponseException
     */
    public function terms(Request $request): bool|string
    {
        $durum = array();
        $tablo = $request->all();

        $baslangic_zamani = time();

        if (!str_contains($tablo["terimler"], ',')) {
            $durum["durum"] = false;
            $durum["mesaj"] = "Islem Başarısız, Lütfen isimler arasına virgün koyunuz çoklu arama yapabilmek için";
            $durum["elasticsearch"] = [];

            return json_encode($durum);
        }

        $sorgu = [
            'index' => 'uyeler',
            'type' => '_doc',
            'size' => $tablo["adet"],
            'body' => [
                'query' => [
                    'terms' => [
                        'isim' => explode(',', $tablo["terimler"])
                    ]
                ]
            ]
        ];

        $sonuc = $this->client->search($sorgu)->asArray();

        $durum["durum"] = true;
        $durum["mesaj"] = "Islem Basarili";
        $durum["arama_suresi"] = time() - $baslangic_zamani;
        $durum["elasticsearch"] = $sonuc;

        return json_encode($durum);
    }

    /**
     * @param Request $request
     * @return false|string
     * @throws ClientResponseException
     * @throws ServerResponseException
     */
    public function wildcard(Request $request): bool|string
    {
        $durum = array();
        $tablo = $request->all();

        $baslangic_zamani = time();

        $sorgu = [
            'index' => 'uyeler',
            'type' => '_doc',
            'size' => $tablo["adet"],
            'body' => [
                'query' => [
                    'wildcard' => [
                        'isim' => $tablo["wildcard"]
                    ]
                ]
            ]
        ];

        $sonuc = $this->client->search($sorgu)->asArray();

        $durum["durum"] = true;
        $durum["mesaj"] = "Islem Basarili";
        $durum["arama_suresi"] = time() - $baslangic_zamani;
        $durum["elasticsearch"] = $sonuc;

        return json_encode($durum);
    }

    public function createMembersIndexToElasticsearch()
    {
        dd("heree!", Artisan::call('elasticsearch:create-members-index-to-elasticsearch'));
    }

    public function addMembersDataToElasticsearch()
    {
        dd("here", Artisan::call('elasticsearch:add-members-data-to-elasticsearch'));
    }
}
