<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Auth\LoginController;
use App\Models\TenderRespond;
use Carbon\Carbon;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;



Route::get('/', function () {
    return redirect('/login');
});

Auth::routes(['register' => false]);



Route::post('/subscribe', [LoginController::class, 'subscribe'])->name('subscribe');


// Route::get('/dashboard', [HomeController::class, 'index']);

Route::group(['middleware' => ['auth']], function () {

    Route::get('/search-positions', [PositionController::class, 'searchPositions']);
    Route::post('/create-position', [PositionController::class, 'createPosition']);


    Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/data', [HomeController::class, 'getDashboardData'])->name('dashboard.data');

    Route::resource('/roles', RoleController::class);
    Route::get('/users/search', 'UserController@search');

    Route::resource('/users', UserController::class);
    Route::put('/users/password', 'UserController@password');

    Route::put('/userprofile/passwordnew/{id}', 'UserProfileController@password')->name('userprofile.password');
    Route::resource('/userprofile', UserProfileController::class);


    //1 done
    Route::post('/groups/create_ajax', 'GroupController@create_ajax');
    Route::get('/groups/search', 'GroupController@getGroups'); 
    Route::resource('/groups', GroupController::class);

    Route::post('/positions/create_ajax', 'PositionController@create_ajax');
    Route::get('/positions/search', 'PositionController@getPositions');
    Route::resource('/positions', PositionController::class);

    Route::post('/cities/create_ajax', 'CityController@create_ajax');
    Route::get('/cities/search', 'CityController@getCities');
    Route::resource('/cities', CityController::class);

    Route::post('/tender-types/create_ajax', 'TenderTypeController@create_ajax');
    Route::get('/tender-types/search', 'TenderTypeController@getTenderTypes');
    Route::resource('/tender-types', TenderTypeController::class);

    //client
    Route::post('/post-client-comments', 'ClientController@post_client_comments');
    Route::get('/client-comments/{id}', 'ClientController@comments');
    Route::get('/client-comment-delete/{id}', 'ClientController@delete_comment');

    Route::post('/post-client-responds', 'ClientController@post_client_responds');
    Route::get('/client-responds/{id}', 'ClientController@responds');
    Route::get('/client-respond-delete/{id}', 'ClientController@delete_respond');

    Route::post('/post-client-files', 'ClientController@post_client_files');
    Route::get('/client-files/{id}', 'ClientController@files');
    Route::get('/client-file-delete/{id}', 'ClientController@delete_file');


    Route::post('/clients/create/person', 'ClientController@store_person');
    Route::get('/clients/edit/{id}/person', 'ClientController@edit_person');
    Route::post('/clients/update/{id}/person', 'ClientController@update_person');
    Route::get('/clients/delete/{id}/person', 'ClientController@delete_person');

    Route::resource('/clients', ClientController::class);
    Route::get('/clients-by-city', 'ClientController@clientsByCity')->name('clients-by-city');
    Route::get('/client-details/{id}', 'ClientController@getClientDetails')->name('client-details');

    //tender

    Route::get('/calendar', [TenderController::class, 'showCalendar']);
    Route::get('/calendar/tenders', [TenderController::class, 'showCalendarTenders']);
    Route::get('/calendar/future-clients', [TenderController::class, 'showCalendarFutureClients']);
    Route::get('/calendar/clients', [TenderController::class, 'showCalendarClients']);
    Route::get('/get-closing-dates', [TenderController::class, 'getClosingDates']);


    Route::post('/post-tender-comments', 'TenderController@post_tender_comments');
    Route::get('/tender-comments/{id}', 'TenderController@comments');
    Route::get('/tender-comment-delete/{id}', 'TenderController@delete_comment');

    Route::post('/post-tender-responds', 'TenderController@post_tender_responds');
    Route::get('/tender-responds/{id}', 'TenderController@responds');
    Route::get('/tender-respond-delete/{id}', 'TenderController@delete_respond');

    Route::post('/post-tender-files', 'TenderController@post_tender_files');
    Route::get('/tender-files/{id}', 'TenderController@files');
    Route::get('/tender-file-delete/{id}', 'TenderController@delete_file');

    Route::get('/mark-as-read', [TenderController::class, 'markAsRead'])->name('mark-as-read');

    Route::resource('/tenders', TenderController::class);
    Route::get('/tenders-by-city', 'TenderController@tendersByCity')->name('tenders-by-city');
    Route::get('/tender-details/{id}', 'TenderController@getTenderDetails')->name('tender-details');


    //futre client
    Route::post('/post-future-client-comments', 'FutureClientController@post_future_client_comments');
    Route::get('/future-client-comments/{id}', 'FutureClientController@comments');
    Route::get('/future-client-comment-delete/{id}', 'FutureClientController@delete_comment');

    Route::post('/post-future-client-responds', 'FutureClientController@post_future_client_responds');
    Route::get('/future-client-responds/{id}', 'FutureClientController@responds');
    Route::get('/future-client-respond-delete/{id}', 'FutureClientController@delete_respond');

    Route::post('/post-future-client-files', 'FutureClientController@post_future_client_files');
    Route::get('/future-client-files/{id}', 'FutureClientController@files');
    Route::get('/future-client-file-delete/{id}', 'FutureClientController@delete_file');

    Route::get('/future-client-mark-as-read', [FutureClientController::class, 'markAsRead'])->name('future-client-mark-as-read');

    Route::get('/future-clients-by-city', 'FutureClientController@futureClientsByCity')->name('future-clients-by-city');
    Route::get('/future-client-details/{id}', 'FutureClientController@getFutureClientDetails')->name('future-client-details');
    Route::resource('/future-clients', FutureClientController::class);
});
