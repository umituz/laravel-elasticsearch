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

    public function membersSearch()
    {


        $durum = array();
        $client = $this->client;

        /* bağlantı var mı, yok mu kontrol ediyoruz */
        if (isset($_POST)) {

            $tablo = array();
            $tablo["adet"] = (int)@$_POST["adet"];
            $tablo["terim"] = @$_POST["terim"];
            $tablo["wildcard"] = @$_POST["wildcard"];
            $tablo["terimler"] = @explode(",", $_POST["terimler"]);

            call_user_func($_POST["islem"], $tablo);

        } else {
            $durum["sonuc"] = false;
            $durum["mesaj"] = "Gerçersiz Post";
        }

        echo json_encode($durum);
    }

    /**
     * @param Request $request
     * @return false|string
     * @throws ClientResponseException
     * @throws ServerResponseException
     */
    public function term(Request $request): bool|string
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

        $sonuc = $this->client->search($sorgu);

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

        $sorgu = [
            'index' => 'uyeler',
            'type' => '_doc',
            'size' => $tablo["adet"],
            'body' => [
                'query' => [
                    'terms' => [
                        'isim' => $tablo["terimler"]
                    ]
                ]
            ]
        ];

        $sonuc = $this->client->search($sorgu);

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

        $sonuc = $this->client->search($sorgu);

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

    public function addMembersToElasticsearch()
    {
        dd("heree!", Artisan::call('elasticsearch:create-members-index-to-elasticsearch'));
        dd("here", Artisan::call('elasticsearch:add-members-to-elasticsearch'));
    }
}
