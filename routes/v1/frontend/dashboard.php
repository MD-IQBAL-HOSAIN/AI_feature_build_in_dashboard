<?php

use App\Http\Controllers\API\V1\DashboardApiController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware'=>['api','auth:api'], 'prefix'=> 'dashboard/'], function(){
    Route::get('',[DashboardApiController::class,'profileRetrieval']);
});



