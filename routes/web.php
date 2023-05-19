<?php

use Modules\GeneralSetting\GeneralSettingController;
use Modules\Complain\ComplainController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/*Route::get('/', function () {
    return view('dashboard');
});*/

Auth::routes();

Route::get('/', 'HomeController@index')->name('dashboard');
Route::get('/logout', 'HomeController@logout');


/*Route::group(['prefix'=>'/module','as'=>'module.'],function (){
    Route::group(['prefix'=>'/vendors','as'=>'vendors.'],function (){
        Route::get('/','Modules\Vendors\VendorsController@index')->name('home');

        Route::get('/add','Modules\Vendors\VendorsController@add')->name('add');
        Route::post('/','Modules\Vendors\VendorsController@create')->name('create');

        Route::get('/{id}','Modules\Vendors\VendorsController@edit')->name('edit');
        Route::put('/','Modules\Vendors\VendorsController@update')->name('update');

        Route::delete('/{id}','Modules\Vendors\VendorsController@delete')->name('delete');
    });
});*/
Route::get('storage/app/{dir}/{filename}', function ($dir,$filename)
{
    $path = storage_path('app/'.$dir.'/' . $filename);
    /*
    if (!File::exists($path)) {
        abort(404);
    }

    $file = File::get($path);
    $type = File::mimeType($path);

    $response = Response::make($file, 200);
    $response->header("Content-Type", $type);
    */
    return response()->file($path);
});

function createRoutes($moduleName,$routePath = ""){
    $routePath = empty($routePath) ? $moduleName : $routePath;
    return Route::group(['prefix'=>'/'.$routePath,'as'=>$moduleName.'.'],function () use($moduleName){
        Route::get('/','Modules\\'.ucfirst($moduleName).'\\'.ucfirst($moduleName).'Controller@index')->name('home');
        Route::get('/get','Modules\\'.ucfirst($moduleName).'\\'.ucfirst($moduleName).'Controller@datatable')->name('datatable');
        Route::get('/add','Modules\\'.ucfirst($moduleName).'\\'.ucfirst($moduleName).'Controller@add')->name('add');
        Route::post('/','Modules\\'.ucfirst($moduleName).'\\'.ucfirst($moduleName).'Controller@create')->name('create');

        Route::get('/{id}','Modules\\'.ucfirst($moduleName).'\\'.ucfirst($moduleName).'Controller@edit')->name('edit');
        Route::get('/show/{id}','Modules\\'.ucfirst($moduleName).'\\'.ucfirst($moduleName).'Controller@show')->name('show');
        Route::put('/','Modules\\'.ucfirst($moduleName).'\\'.ucfirst($moduleName).'Controller@update')->name('update');

        Route::delete('/{id}','Modules\\'.ucfirst($moduleName).'\\'.ucfirst($moduleName).'Controller@delete')->name('delete');
        Route::delete('/{id}/{field}','Modules\\'.ucfirst($moduleName).'\\'.ucfirst($moduleName).'Controller@deleteFile')->name('deleteFile');
        Route::put('/{id}/{field}','Modules\\'.ucfirst($moduleName).'\\'.ucfirst($moduleName).'Controller@status')->name('status');
        Route::put('/{id}/{field}/approve','Modules\\'.ucfirst($moduleName).'\\'.ucfirst($moduleName).'Controller@status')->name('approve');
    });
}
function createOrderRoutes($moduleName,$routePath = ""){
    $routePath = empty($routePath) ? $moduleName : $routePath;
    return Route::group(['prefix'=>'/'.$routePath,'as'=>$moduleName.'.'],function () use($moduleName){
        Route::get('/','Modules\\'.ucfirst($moduleName).'\\'.ucfirst($moduleName).'Controller@index')->name('home');
        Route::get('/get','Modules\\'.ucfirst($moduleName).'\\'.ucfirst($moduleName).'Controller@datatable')->name('datatable');
        Route::get('/add','Modules\\'.ucfirst($moduleName).'\\'.ucfirst($moduleName).'Controller@add')->name('add');
        Route::post('/','Modules\\'.ucfirst($moduleName).'\\'.ucfirst($moduleName).'Controller@create')->name('create');

        Route::get('/{id}','Modules\\'.ucfirst($moduleName).'\\'.ucfirst($moduleName).'Controller@viewOrder')->name('viewOrder');
        Route::get('/invoice/{id}','Modules\\'.ucfirst($moduleName).'\\'.ucfirst($moduleName).'Controller@invoice')->name('invoice');

        Route::delete('/{id}','Modules\\'.ucfirst($moduleName).'\\'.ucfirst($moduleName).'Controller@delete')->name('delete');
        Route::put('/{id}/{field}','Modules\\'.ucfirst($moduleName).'\\'.ucfirst($moduleName).'Controller@status')->name('status');
    });
}

function createReportRoutes($controller,$routePath = ""){
    $moduleName = 'reports';
    $routePath = empty($routePath) ? $controller : $routePath;
    return Route::group(['prefix'=>'/'.$routePath,'as'=>$controller.'.'],function () use($moduleName,$controller){
        Route::get('/','Modules\\'.ucfirst($moduleName).'\\'.$controller.'@index')->name('home');
        Route::get('/get','Modules\\'.ucfirst($moduleName).'\\'.$controller.'@datatable')->name('datatable');
        Route::post('/','Modules\\'.ucfirst($moduleName).'\\'.$controller.'@search')->name('home.search');
    });
}

function createAccountRoutes($moduleName,$routePath = ""){
    $routePath = empty($routePath) ? $moduleName : $routePath;
    return Route::group(['prefix'=>'/'.$routePath,'as'=>$moduleName.'.'],function () use ($moduleName){
        Route::get('/','Modules\\'.ucfirst($moduleName).'\\'.ucfirst($moduleName).'Controller@index')->name('home');
        Route::get('/get','Modules\\'.ucfirst($moduleName).'\\'.ucfirst($moduleName).'Controller@datatable')->name('datatable');
        Route::get('/add','Modules\\'.ucfirst($moduleName).'\\'.ucfirst($moduleName).'Controller@add')->name('add');
        Route::post('/','Modules\\'.ucfirst($moduleName).'\\'.ucfirst($moduleName).'Controller@create')->name('create');
        Route::post('/search','Modules\\'.ucfirst($moduleName).'\\'.ucfirst($moduleName).'Controller@search')->name('home.search');
    });
}

Route::group(['prefix'=>'/','as'=>'module.'],function (){
    createRoutes('profileSettings');
    createRoutes('users');
    createRoutes('clients');
});

Route::group(['prefix'=>'/purchases','as'=>'module.'],function (){
    createOrderRoutes('purchaseOrders');
});

Route::group(['prefix'=>'/services','as'=>'module.'],function (){
    createRoutes('categories');
});

Route::group(['prefix'=>'/services','as'=>'module.'],function (){
    createRoutes('services', 'services');
});

Route::group(['prefix'=>'/reports','as'=>'module.'],function (){
    createReportRoutes('PurchaseOrderReport','Purchase Order Report');
});


Route::controller(ComplainController::class)->prefix('complain')->group(function(){
    Route::get('/status', 'status');
    Route::get('/', 'complainList')->name('complain.index');
});

Route::controller(GeneralSettingController::class)->prefix('general-setting')->group(function(){
    Route::post('/', 'store')->name('general-setting.store');
    Route::get('/', 'create')->name('general-setting.create');
});
