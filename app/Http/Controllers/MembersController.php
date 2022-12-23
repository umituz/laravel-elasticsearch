<?php

namespace App\Http\Controllers;

use App\Models\Member;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class MembersController extends Controller
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

//        foreach ($items as $item) {
//
//            $insert[] = [
//                'userid' => User::current()->id,
//                'lati' => $item[0],
//                'long' => $item[1],
//                'streetNumber' => $item[2],
//                'streetName' => $item[3],
//                'country' => $item[6],
//                'state' => $item[5],
//                'pcode' => $item[7],
//                'suburb' => $suburb,
//                'created_at' => new DateTime,
//                'updated_at' => new DateTime
//
//            ];
//
//        }

        if (DB::table('members')->insert($items)) {
            if ($itemsTemp) {
                $this->addData($itemsTemp);
            }
        } else {
            return false;
        }
        return true;


    }
}
