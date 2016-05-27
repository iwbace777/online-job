<?php

Route::pattern('id', '[0-9]+');
Route::pattern('id2', '[0-9]+');
Route::pattern('id3', '[0-9]+');
Route::pattern('num', '[0-9]+');
Route::pattern('slug', '[a-zA-Z0-9-]+');
/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::get('/', function(){
    return Redirect::route('home.index');
});

Route::get('how-it-works',         ['as' => 'page.howItWorks',           'uses' => 'Frontend\PageController@howItWorks']);

Route::get('login',                ['as' => 'user.login',                'uses' => 'Frontend\UserController@login']);
Route::post('doLogin',             ['as' => 'user.doLogin',              'uses' => 'Frontend\UserController@doLogin']);
Route::get('logout',               ['as' => 'user.logout',               'uses' => 'Frontend\UserController@logout']);
Route::get('signup',               ['as' => 'user.signup',               'uses' => 'Frontend\UserController@signup']);
Route::post('doSignup',            ['as' => 'user.doSignup',             'uses' => 'Frontend\UserController@doSignup']);
Route::get('profile',              ['as' => 'user.profile',              'uses' => 'Frontend\UserController@profile']);
Route::post('updateProfile',       ['as' => 'user.updateProfile',        'uses' => 'Frontend\UserController@updateProfile']);
Route::get('user/detail/{slug}',   ['as' => 'user.detail',               'uses' => 'Frontend\UserController@detail']);
Route::get('active/{slug}',        ['as' => 'user.active',               'uses' => 'Frontend\UserController@active']);
Route::get('reviews',              ['as' => 'user.reviews',              'uses' => 'Frontend\UserController@reviews']);
Route::any('user/search',          ['as' => 'user.search',               'uses' => 'Frontend\UserController@search']);
Route::get('forgotPassword',       ['as' => 'user.forgotPassword',       'uses' => 'Frontend\UserController@forgotPassword']);
Route::get('reset/{slug}',         ['as' => 'user.resetPassword',        'uses' => 'Frontend\UserController@resetPassword']);
Route::post('doReset/{slug}',      ['as' => 'user.doResetPassword',      'uses' => 'Frontend\UserController@doResetPassword']);
Route::post('sendForgotPasswordEmail', ['as' => 'user.sendForgotPasswordEmail', 'uses' => 'Frontend\UserController@sendForgotPasswordEmail']);

Route::post('async/user/doSubscriber', ['as' => 'async.user.doSubscriber',   'uses' => 'Frontend\UserController@asyncDoSubscriber']);
Route::post('async/user/active',       ['as' => 'async.user.active',         'uses' => 'Frontend\UserController@asyncActiveEmail']);
Route::post('async/user/loadBusiness', ['as' => 'async.user.loadBusiness',   'uses' => 'Frontend\UserController@asyncLoadBusiness']);

Route::get('dashboard',            ['as' => 'job.dashboard',             'uses' => 'Frontend\JobController@dashboard']);

Route::get('home/{slug?}',         ['as' => 'home.index',                'uses' => 'Frontend\JobController@home']);
Route::any('search/{slug?}',       ['as' => 'job.search',                'uses' => 'Frontend\JobController@search']);

Route::get('job/post',             ['as' => 'job.post',                  'uses' => 'Frontend\JobController@post']);
Route::post('job/doPost',          ['as' => 'job.doPost',                'uses' => 'Frontend\JobController@doPost']);
Route::post('job/doBid',           ['as' => 'job.doBid',                 'uses' => 'Frontend\JobController@doBid']);
Route::post('job/giveFeedback',    ['as' => 'job.giveFeedback',          'uses' => 'Frontend\JobController@giveFeedback']);
Route::get('job/detail/{slug}',    ['as' => 'job.detail',                'uses' => 'Frontend\JobController@detail']);
Route::post('job/hire',            ['as' => 'job.hire',                  'uses' => 'Frontend\JobController@hire']);
Route::get('job/posts',            ['as' => 'job.posts',                 'uses' => 'Frontend\JobController@posts']);
Route::get('job/bids',             ['as' => 'job.bids',                  'uses' => 'Frontend\JobController@bids']);
Route::get('job/center/{slug?}',   ['as' => 'job.center',                'uses' => 'Frontend\JobController@center']);
Route::get('job/complete/{id}',    ['as' => 'job.complete',              'uses' => 'Frontend\JobController@complete']);
Route::post('async/job/doBid',     ['as' => 'async.job.doBid',           'uses' => 'Frontend\JobController@asyncDoBid']);

Route::post('async/feed/doRead',   ['as' => 'async.feed.doRead',         'uses' => 'Frontend\FeedController@doRead']);

Route::post('async/load/subCategories', ['as' => 'async.load.subCategories',   'uses' => 'Frontend\CategoryController@asyncSubCategories']);
Route::post('async/load/questions',     ['as' => 'async.load.questions',       'uses' => 'Frontend\CategoryController@asyncQuestions']);

Route::get('message/history',                         ['as' => 'message.history',                 'uses' => 'Frontend\MessageController@history']);
Route::get('message/detail/{id}/{id2}/{id3}',         ['as' => 'message.detail',                  'uses' => 'Frontend\MessageController@detail']);
Route::post('message/send/{id}/{id2}/{id3}',          ['as' => 'message.send',                    'uses' => 'Frontend\MessageController@send']);
Route::post('async/message/send',                     ['as' => 'async.message.send',              'uses' => 'Frontend\MessageController@asyncSend']);
Route::post('async/email/send',                       ['as' => 'async.email.send',                'uses' => 'Frontend\MessageController@asyncEmail']);

Route::get('language-chooser/{slug}',                 ['as' => 'language-chooser',                'uses' => 'Frontend\LanguageController@chooser']);

Route::group(['prefix' => 'connection'], function () {
    
    Route::get('purchase',              ['as' => 'connection.purchase',              'uses' => 'Frontend\ConnectionController@purchase']);
    Route::any('purchase/ipn',          ['as' => 'connection.purchase.ipn',          'uses' => 'Frontend\ConnectionController@purchaseIPN']);
    Route::any('purchase/success',      ['as' => 'connection.purchase.success',      'uses' => 'Frontend\ConnectionController@purchaseSuccess']);
    Route::any('purchase/failed',       ['as' => 'connection.purchase.failed',       'uses' => 'Frontend\ConnectionController@purchaseFailed']);

    Route::post('subscribe/create/{slug}', ['as' => 'connection.subscribe.create',      'uses' => 'Frontend\ConnectionController@createSubscribe']);
    Route::get('subscribe/cancel',      ['as' => 'connection.subscribe.cancel',      'uses' => 'Frontend\ConnectionController@cancelSubscribe']);
    Route::any('subscribe/webhook',     ['as' => 'connection.subscribe.webhook',     'uses' => 'Frontend\ConnectionController@webhook']);

    
    Route::post('buy/do',               ['as' => 'connection.buy.do',                'uses' => 'Frontend\ConnectionController@doBuy']);
        
    Route::post('async/purchase',       ['as' => 'async.connection.purchase',        'uses' => 'Frontend\ConnectionController@asyncPurchase']);
});

Route::group(['prefix' => 'backend'], function () {
    Route::get('/',         ['as' => 'backend.auth',         'uses' => 'Backend\AuthController@index']);
    Route::get('login',     ['as' => 'backend.auth.login',   'uses' => 'Backend\AuthController@login']);
    Route::post('doLogin',  ['as' => 'backend.auth.doLogin', 'uses' => 'Backend\AuthController@doLogin']);
    Route::get('logout',    ['as' => 'backend.auth.logout',  'uses' => 'Backend\AuthController@logout']);
    
    Route::any('dashboard', ['as' => 'backend.dashboard',    'uses' => 'Backend\DashboardController@index']);
    
    Route::group(['prefix' => 'email'], function () {
        Route::get('/',           ['as' => 'backend.email',         'uses' => 'Backend\EmailController@index']);
        Route::get('edit/{id}',   ['as' => 'backend.email.edit',    'uses' => 'Backend\EmailController@edit']);
        Route::post('store',      ['as' => 'backend.email.store',   'uses' => 'Backend\EmailController@store']);
        
        Route::post('resend',      ['as' => 'backend.email.resend',  'uses' => 'Backend\EmailController@resend']);
        Route::post('resubscribe', ['as' => 'backend.email.resubscribe', 'uses' => 'Backend\EmailController@resubscribe']);
    });
    
    Route::group(['prefix' => 'category'], function () {
        Route::get('/',           ['as' => 'backend.category',         'uses' => 'Backend\CategoryController@index']);
        Route::get('create',      ['as' => 'backend.category.create',  'uses' => 'Backend\CategoryController@create']);
        Route::get('edit/{id}',   ['as' => 'backend.category.edit',    'uses' => 'Backend\CategoryController@edit']);
        Route::post('store',      ['as' => 'backend.category.store',   'uses' => 'Backend\CategoryController@store']);
        Route::get('delete/{id}', ['as' => 'backend.category.delete',  'uses' => 'Backend\CategoryController@delete']);
        
        Route::group(['prefix' => 'sub'], function () {
            Route::get('/',           ['as' => 'backend.category.sub',              'uses' => 'Backend\SubCategoryController@index']);
            Route::get('create/{id}', ['as' => 'backend.category.sub.create',       'uses' => 'Backend\SubCategoryController@create']);
            Route::get('edit/{id}',   ['as' => 'backend.category.sub.edit',         'uses' => 'Backend\SubCategoryController@edit']);
            Route::post('store',      ['as' => 'backend.category.sub.store',        'uses' => 'Backend\SubCategoryController@store']);
            Route::get('delete/{id}', ['as' => 'backend.category.sub.delete',       'uses' => 'Backend\SubCategoryController@delete']);
        });
        
        Route::group(['prefix' => 'question'], function () {
            Route::get('/',           ['as' => 'backend.category.question',         'uses' => 'Backend\QuestionController@index']);
            Route::get('create/{id}', ['as' => 'backend.category.question.create',  'uses' => 'Backend\QuestionController@create']);
            Route::get('edit/{id}',   ['as' => 'backend.category.question.edit',    'uses' => 'Backend\QuestionController@edit']);
            Route::post('store',      ['as' => 'backend.category.question.store',   'uses' => 'Backend\QuestionController@store']);
            Route::get('delete/{id}', ['as' => 'backend.category.question.delete',  'uses' => 'Backend\QuestionController@delete']);            
        });
        
        Route::group(['prefix' => 'answer'], function () {
            Route::get('create/{id}', ['as' => 'backend.category.answer.create',    'uses' => 'Backend\AnswerController@create']);
            Route::get('edit/{id}',   ['as' => 'backend.category.answer.edit',      'uses' => 'Backend\AnswerController@edit']);
            Route::post('store',      ['as' => 'backend.category.answer.store',     'uses' => 'Backend\AnswerController@store']);
            Route::get('delete/{id}', ['as' => 'backend.category.answer.delete',    'uses' => 'Backend\AnswerController@delete']);
        });        
    });
    
    Route::group(['prefix' => 'user'], function () {
        Route::get('/',           ['as' => 'backend.user',         'uses' => 'Backend\UserController@index']);
        Route::get('create',      ['as' => 'backend.user.create',  'uses' => 'Backend\UserController@create']);
        Route::get('edit/{id}',   ['as' => 'backend.user.edit',    'uses' => 'Backend\UserController@edit']);
        Route::post('store',      ['as' => 'backend.user.store',   'uses' => 'Backend\UserController@store']);
        Route::get('delete/{id}', ['as' => 'backend.user.delete',  'uses' => 'Backend\UserController@delete']);
        Route::post('doNote',     ['as' => 'backend.user.doNote',  'uses' => 'Backend\UserController@doNote']);
        Route::post('addConnection', ['as' => 'backend.user.addConnection', 'uses' => 'Backend\UserController@addConnection']);
        
    });
    
    Route::group(['prefix' => 'job'], function () {
        Route::get('/',           ['as' => 'backend.job',         'uses' => 'Backend\JobController@index']);
        Route::get('detail/{id}', ['as' => 'backend.job.detail',  'uses' => 'Backend\JobController@detail']);
        Route::get('delete/{id}', ['as' => 'backend.job.delete',  'uses' => 'Backend\JobController@delete']);
        Route::post('doBid',      ['as' => 'backend.job.doBid',   'uses' => 'Backend\JobController@doBid']);
    });
    
    Route::group(['prefix' => 'connection-require'], function () {
        Route::get('/',           ['as' => 'backend.connection-require',         'uses' => 'Backend\ConnectionRequireController@index']);
        Route::get('create',      ['as' => 'backend.connection-require.create',  'uses' => 'Backend\ConnectionRequireController@create']);
        Route::get('edit/{id}',   ['as' => 'backend.connection-require.edit',    'uses' => 'Backend\ConnectionRequireController@edit']);
        Route::post('store',      ['as' => 'backend.connection-require.store',   'uses' => 'Backend\ConnectionRequireController@store']);
        Route::get('delete/{id}', ['as' => 'backend.connection-require.delete',  'uses' => 'Backend\ConnectionRequireController@delete']);        
    });    
    
    Route::group(['prefix' => 'business'], function () {
        Route::get('/',           ['as' => 'backend.business',         'uses' => 'Backend\BusinessController@index']);
        Route::get('create',      ['as' => 'backend.business.create',  'uses' => 'Backend\BusinessController@create']);
        Route::get('edit/{id}',   ['as' => 'backend.business.edit',    'uses' => 'Backend\BusinessController@edit']);
        Route::post('store',      ['as' => 'backend.business.store',   'uses' => 'Backend\BusinessController@store']);
        Route::get('delete/{id}', ['as' => 'backend.business.delete',  'uses' => 'Backend\BusinessController@delete']);
        Route::get('import',      ['as' => 'backend.business.import',  'uses' => 'Backend\BusinessController@import']);
        Route::post('doImport',   ['as' => 'backend.business.doImport','uses' => 'Backend\BusinessController@doImport']);
        Route::post('doImport2',  ['as' => 'backend.business.doImport2','uses' => 'Backend\BusinessController@doImport2']);
    });    
    
    Route::group(['prefix' => 'city'], function () {
        Route::get('/',           ['as' => 'backend.city',         'uses' => 'Backend\CityController@index']);
        Route::get('create',      ['as' => 'backend.city.create',  'uses' => 'Backend\CityController@create']);
        Route::get('edit/{id}',   ['as' => 'backend.city.edit',    'uses' => 'Backend\CityController@edit']);
        Route::post('store',      ['as' => 'backend.city.store',   'uses' => 'Backend\CityController@store']);
        Route::get('delete/{id}', ['as' => 'backend.city.delete',  'uses' => 'Backend\CityController@delete']);
    });
    
    Route::group(['prefix' => 'district'], function () {
        Route::get('create/{id}', ['as' => 'backend.district.create',  'uses' => 'Backend\DistrictController@create']);
        Route::get('edit/{id}',   ['as' => 'backend.district.edit',    'uses' => 'Backend\DistrictController@edit']);
        Route::post('store',      ['as' => 'backend.district.store',   'uses' => 'Backend\DistrictController@store']);
        Route::get('delete/{id}', ['as' => 'backend.district.delete',  'uses' => 'Backend\DistrictController@delete']);
    });
    
    Route::group(['prefix' => 'package'], function () {
        Route::get('/',           ['as' => 'backend.package',         'uses' => 'Backend\PackageController@index']);
        Route::get('create',      ['as' => 'backend.package.create',  'uses' => 'Backend\PackageController@create']);
        Route::get('edit/{id}',   ['as' => 'backend.package.edit',    'uses' => 'Backend\PackageController@edit']);
        Route::post('store',      ['as' => 'backend.package.store',   'uses' => 'Backend\PackageController@store']);
        Route::get('delete/{id}', ['as' => 'backend.package.delete',  'uses' => 'Backend\PackageController@delete']);
    });
    
    Route::group(['prefix' => 'plan'], function () {
        Route::get('/',           ['as' => 'backend.plan',            'uses' => 'Backend\PlanController@index']);
        Route::get('create',      ['as' => 'backend.plan.create',     'uses' => 'Backend\PlanController@create']);
        Route::get('edit/{id}',   ['as' => 'backend.plan.edit',       'uses' => 'Backend\PlanController@edit']);
        Route::post('store',      ['as' => 'backend.plan.store',      'uses' => 'Backend\PlanController@store']);
        Route::get('delete/{id}', ['as' => 'backend.plan.delete',     'uses' => 'Backend\PlanController@delete']);
    });
    
    Route::group(['prefix' => 'newsletter'], function () {
        Route::get('/',           ['as' => 'backend.newsletter',        'uses' => 'Backend\NewsletterController@index']);
        Route::get('/send',       ['as' => 'backend.newsletter.send',   'uses' => 'Backend\NewsletterController@send']);
        Route::post('doSend',     ['as' => 'backend.newsletter.doSend', 'uses' => 'Backend\NewsletterController@doSend']);
        Route::get('delete/{id}', ['as' => 'backend.newsletter.delete', 'uses' => 'Backend\NewsletterController@delete']);
    });    
    
    Route::group(['prefix' => 'setting'], function () {
        Route::get('/',           ['as' => 'backend.setting',         'uses' => 'Backend\SettingController@index']);
        Route::post('store',      ['as' => 'backend.setting.store',   'uses' => 'Backend\SettingController@store']);
    });
    
    Route::get('/purchase/history',    ['as' => 'backend.purchase.history',    'uses' => 'Backend\ConnectionController@purchaseHistory']);
    Route::get('/subscribe/history',   ['as' => 'backend.subscribe.history',   'uses' => 'Backend\ConnectionController@subscribeHistory']);
    
    Route::get('/buy/history',         ['as' => 'backend.buy.history',         'uses' => 'Backend\ConnectionController@buyHistory']);
    Route::get('/buy/delete/{id}',     ['as' => 'backend.buy.delete',          'uses' => 'Backend\ConnectionController@delete']);
    Route::get('/buy/paid/{id}',       ['as' => 'backend.buy.paid',            'uses' => 'Backend\ConnectionController@paid']);
    Route::get('/buy/sent/{id}',       ['as' => 'backend.buy.sent',            'uses' => 'Backend\ConnectionController@sent']);
    Route::post('/buy/add',            ['as' => 'backend.buy.add',             'uses' => 'Backend\ConnectionController@add']);
    
    Route::post('async/buy/update/due-at', ['as' => 'async.buy.update.due-at', 'uses' => 'Backend\ConnectionController@asyncUpdateDueAt']);
});

Route::group(['prefix' => 'batch'], function () {
    Route::get('emailHistory/checkRead/{num?}',           ['as' => 'batch.emailHistory.checkRead',  'uses' => 'Batch\EmailHistoryController@checkRead']);
    Route::get('emailBusiness/checkRead/{num?}',          ['as' => 'batch.emailBusiness.checkRead', 'uses' => 'Batch\EmailBusinessController@checkRead']);
    Route::get('invoiceReminder',                         ['as' => 'batch.invoiceReminder',         'uses' => 'Batch\InvoiceReminderController@index']);
    Route::get('emailDebt',                               ['as' => 'batch.emailDebt',               'uses' => 'Batch\EmailDebtController@index']);
    Route::get('hireReminder',                            ['as' => 'batch.hireReminder',            'uses' => 'Batch\HireReminderController@index']);
});

App::missing(function($exception) {
    return Redirect::route('home.index');
});