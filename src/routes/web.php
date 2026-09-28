<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Models\Basic;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

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

Route::get('/', [App\Http\Controllers\MainController::class, 'home'])->name('home');

//Route::get('/dash', [App\Http\Controllers\DashController::class, 'dashView'])->name('dashView');
//Route::get('/dash2', [App\Http\Controllers\DashController::class, 'dash2View'])->name('dash2View');

Route::middleware('auth2')->group(function () {
    Route::get('/basic/img/{fileName}', function (Request $request, $fileName) {
        $basic = Basic::where('firstImagePathName', $fileName)->orWhere('secondImagePathName', $fileName)->first();
        if (!$basic) {
            return null;
        }

        $imageName = $basic->firstImagePathName == $fileName ? $basic->firstImageName : $basic->secondImageName;

        $disk = Storage::disk('local')->directories('public');
        $exist = false;
        if (count($disk) == 1) {
            $exist = Storage::disk('local')->exists($disk[0].'/'.$fileName);
        }

        if ($exist) {
            $storagePath = Storage::disk('local')->getDriver()->getAdapter()->getPathPrefix();
            $path = $storagePath.$disk[0].'/'.$fileName;

            return response()->download($path, $imageName);
        }

        return null;
    })->name('basicImage');

    Route::get('/basic', [App\Http\Controllers\DashController::class, 'basicView'])->name('basicView');

    Route::get('/dash', [App\Http\Controllers\DashController::class, 'dashView'])->name('dashView');
    Route::get('/dash2', [App\Http\Controllers\DashController::class, 'dash2View'])->name('dash2View');
    Route::get('/dash3', [App\Http\Controllers\DashController::class, 'dash3View'])->name('dash3View');
    Route::get('/sat1', [App\Http\Controllers\DashController::class, 'sat1View'])->name('sat1View');
    Route::get('/sat2', [App\Http\Controllers\DashController::class, 'sat2View'])->name('sat2View');
    Route::get('/sat3', [App\Http\Controllers\DashController::class, 'sat3View'])->name('sat3View');
    Route::get('/sat4', [App\Http\Controllers\DashController::class, 'sat4View'])->name('sat4View');
    Route::get('/sat5', [App\Http\Controllers\DashController::class, 'sat5View'])->name('sat5View');
    Route::get('/sat6', [App\Http\Controllers\DashController::class, 'sat6View'])->name('sat6View');
    Route::get('/sat7', [App\Http\Controllers\DashController::class, 'sat7View'])->name('sat7View');
    Route::get('/sat8', [App\Http\Controllers\DashController::class, 'sat8View'])->name('sat8View');
    Route::get('/sat9', [App\Http\Controllers\DashController::class, 'sat9View'])->name('sat9View');
    Route::get('/sat0', [App\Http\Controllers\SecondController::class, 'sat0View'])->name('sat0View');
    Route::get('/sat0/{imagePathName}', [App\Http\Controllers\SecondController::class, 'sat0Image'])->name('sat0Image');
    Route::get('/result1', [App\Http\Controllers\DashController::class, 'result1View'])->name('result1View');
    Route::get('/result2', [App\Http\Controllers\DashController::class, 'result2View'])->name('result2View');
    Route::get('/result3', [App\Http\Controllers\DashController::class, 'result3View'])->name('result3View');
    Route::get('/result4', [App\Http\Controllers\DashController::class, 'result4View'])->name('result4View');
    Route::get('/result5', [App\Http\Controllers\SecondController::class, 'result5View'])->name('result5View');
    Route::get('/result5/{imagePathName}', [App\Http\Controllers\SecondController::class, 'result5Image'])->name('result5Image');
    Route::get('/result6', [App\Http\Controllers\SecondController::class, 'result6View'])->name('result6View');
    Route::get('/result6/{imagePathName}', [App\Http\Controllers\SecondController::class, 'result6Image'])->name('result6Image');






    Route::get('/test', [App\Http\Controllers\OperationController::class, 'dashView'])->name('test');
});

Route::middleware('guest')->group(function () {
    Route::post('/postLogin', [App\Http\Controllers\Auth\LoginController::class, 'authenticate'])->name('guest.login');
});

/////////////////////////////admin
Route::middleware('admin_guest')->group(function () {
    Route::get('/admin', [App\Http\Controllers\AdminController::class, 'loginView']);
    Route::get('/admin/login', [App\Http\Controllers\AdminController::class, 'loginView'])->name('admin.loginView');
    Route::post('/admin/login', [App\Http\Controllers\AdminController::class, 'login'])->name('admin.login');
});

Route::middleware('admin')->group(function () {
    Route::get('/admin/member', [App\Http\Controllers\AdminController::class, 'memberView'])->name('admin.memberView');
    Route::get('/admin/member/new', [App\Http\Controllers\AdminController::class, 'memberNewView'])->name('admin.memberNewView');
    Route::post('/admin/member/new', [App\Http\Controllers\AdminController::class, 'memberNew'])->name('admin.memberNew');
    Route::get('/admin/member/edit/{userId}', [App\Http\Controllers\AdminController::class, 'memberEditView'])->name('admin.memberEditView');
    Route::post('/admin/member/edit', [App\Http\Controllers\AdminController::class, 'memberEdit'])->name('admin.memberEdit');
    Route::get('/admin/member/delete', [App\Http\Controllers\AdminController::class, 'memberDelete'])->name('admin.memberDelete');
    Route::get('/admin/basic', [App\Http\Controllers\AdminController::class, 'basicView'])->name('admin.basicView');
    Route::post('/admin/basic', [App\Http\Controllers\AdminController::class, 'basic'])->name('admin.basic');
    Route::post('/admin/basic/imageApi', [App\Http\Controllers\AdminController::class, 'basicImageApi'])->name('admin.basicImageApi');
    Route::get('/admin/total1', [App\Http\Controllers\AdminController::class, 'total1View'])->name('admin.total1View');
    Route::post('/admin/total1/excel', [App\Http\Controllers\AdminController::class, 'total1Excel'])->name('admin.total1Excel');
    Route::get('/admin/total2', [App\Http\Controllers\AdminController::class, 'total2View'])->name('admin.total2View');
    Route::post('/admin/total2/excel', [App\Http\Controllers\AdminController::class, 'total2Excel'])->name('admin.total2Excel');
    Route::get('/admin/total3', [App\Http\Controllers\AdminController::class, 'total3View'])->name('admin.total3View');
    Route::post('/admin/total3/excel', [App\Http\Controllers\AdminController::class, 'total3Excel'])->name('admin.total3Excel');
    Route::get('/admin/total4', [App\Http\Controllers\AdminController::class, 'total4View'])->name('admin.total4View');
    Route::post('/admin/total4/excel', [App\Http\Controllers\AdminController::class, 'total4Excel'])->name('admin.total4Excel');
    Route::get('/admin/total5', [App\Http\Controllers\AdminController::class, 'total5View'])->name('admin.total5View');
    Route::get('/admin/total5/write', [App\Http\Controllers\AdminController::class, 'total5WriteView'])->name('admin.total5WriteView');
    Route::post('/admin/total5/write', [App\Http\Controllers\AdminController::class, 'total5Write'])->name('admin.total5Write');
    Route::get('/admin/total5/delete/{shortAnswerId}', [App\Http\Controllers\AdminController::class, 'total5Delete'])->name('admin.total5Delete');
    Route::get('/admin/total5/edit/{shortAnswerId}', [App\Http\Controllers\AdminController::class, 'total5EditView'])->name('admin.total5EditView');
    Route::post('/admin/total5/edit', [App\Http\Controllers\AdminController::class, 'total5Edit'])->name('admin.total5Edit');
    Route::get('/admin/per1', [App\Http\Controllers\AdminController::class, 'per1View'])->name('admin.per1View');
    Route::post('/admin/per1', [App\Http\Controllers\AdminController::class, 'per1Write'])->name('admin.per1Write');
    Route::get('/admin/per2', [App\Http\Controllers\AdminController::class, 'per2View'])->name('admin.per2View');
    Route::get('/admin/per2/fetch', [App\Http\Controllers\AdminController::class, 'per2Fetch'])->name('admin.per2Fetch');
    Route::post('/admin/per2', [App\Http\Controllers\AdminController::class, 'per2Write'])->name('admin.per2Write');
    Route::post('/admin/per2/delete', [App\Http\Controllers\AdminController::class, 'per2Delete'])->name('admin.per2Delete');
    Route::get('/admin/per3', [App\Http\Controllers\AdminController::class, 'per3View'])->name('admin.per3View');
    Route::post('/admin/per3', [App\Http\Controllers\AdminController::class, 'per3Write'])->name('admin.per3Write');
    Route::get('/admin/per4', [App\Http\Controllers\AdminController::class, 'per4View'])->name('admin.per4View');
    Route::post('/admin/per4', [App\Http\Controllers\AdminController::class, 'per4Write'])->name('admin.per4Write');
});

Auth::routes();

//////////////////////////////////////////////////////////////////////////temp
