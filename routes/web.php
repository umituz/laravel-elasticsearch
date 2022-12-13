<?php

use App\Http\Controllers\ElasticsearchTableController;
use App\Http\Controllers\ElasticsearchTableDataAggregationController;
use App\Http\Controllers\ElasticsearchTableDataController;
use App\Http\Controllers\ElasticsearchTableDataSearchController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('elasticsearch')->group(function () {

    Route::controller(ElasticsearchTableController::class)->group(function () {
        Route::post('/create-table', 'createTable');
        Route::delete('/delete-table', 'deleteTable');
        Route::get('/get-tables-detail', 'getTablesDetail');
        Route::get('/get-all-tables', 'getAllTables');
    });

    Route::controller(ElasticsearchTableDataController::class)->group(function () {
        Route::post('/add-data-to-table', 'addDataToTable');
        Route::put('/update-data-to-table', 'updateDataToTable');
        Route::get('/get-data-to-table', 'getDataToTable');
        Route::get('/get-many-data-to-table', 'getManyDataToTable');
        Route::delete('/delete-data-to-table', 'deleteDataToTable');
        Route::delete('/delete-many-data-to-table', 'deleteManyDataToTable');
        Route::post('/bulk-data-to-table', 'bulkDataToTable');
    });

    Route::controller(ElasticsearchTableDataSearchController::class)->group(function () {
        Route::post('/term-search-to-table', 'termSearchToTable');
        Route::post('/terms-search-to-table', 'termsSearchToTable');
        Route::post('/wildcard-to-table', 'wildcardToTable');
        Route::post('/range-to-table', 'rangeToTable');
        Route::post('/sort-to-table', 'sortToTable');
        Route::post('/from-size-to-table', 'fromSizeToTable');
        Route::post('/min-score-to-table', 'minScoreToTable');
        Route::post('/validate-query', 'validateQuery');
        Route::get('/get-query-count', 'getQueryCount');
    });

    Route::controller(ElasticsearchTableDataAggregationController::class)->group(function () {
        Route::get('/aggregation-term-list', 'aggregationTermList');
        Route::get('/aggregation-min-max-sum-avg', 'aggregationMinMaxSumAvg');
        Route::get('/aggregation-stats', 'aggregationStats');
        Route::get('/aggregation-extended-stats', 'aggregationExtendedStats');
        Route::get('/aggregation-from-range', 'aggregationFromRange');
        Route::get('/aggregation-ip-addresses-range', 'aggregationIPAddressesRange');
        Route::get('/aggregation-ip-addresses-mask-range', 'aggregationIPAddressesMaskRange');
    });

});
