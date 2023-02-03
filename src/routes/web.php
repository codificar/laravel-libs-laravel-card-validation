<?php


// Rotas do painel 

Route::group(array('namespace' => 'Codificar\CardValidation\Http\Controllers'), function () {
    //Rota de add cartão via painel
    Route::group(['prefix' => 'admin', 'middleware' => 'auth.admin'], function () {
        Route::post('/user/libs/finance/payment/add_credit_card', array('as' => 'userAddCreditCardPanel', 'uses' => 'CardControler@addCreditCard'));
    });
});

Route::group(array('namespace' => 'Codificar\Finance\Http\Controllers'), function () {
    // Rotas do app provider
    Route::group(['prefix' => 'libs/validator/card/provider', 'middleware' => 'auth.provider_api:api'], function () {
        Route::post('/add_credit_card', 'CardControler@addCreditCardProvider');
    });
    // Rotas do app user
    Route::group(['prefix' => 'libs/validator/card/user', 'middleware' => 'auth.user_api:api'], function () {
        Route::post('/add_credit_card', 'CardControler@addCreditCardUser');
    });
});


/**
 * Rota para permitir utilizar arquivos de traducao do laravel (dessa lib) no vue js
 */
Route::get('/libs/card-validation/lang.trans/{file}', function () {
    $fileNames = explode(',', Request::segment(4));
    $lang = config('app.locale');
    $files = array();
    foreach ($fileNames as $fileName) {
        array_push($files, __DIR__.'/../resources/lang/' . $lang . '/' . $fileName . '.php');
    }
    $strings = [];
    foreach ($files as $file) {
        $name = basename($file, '.php');
        $strings[$name] = require $file;
    }

    header('Content-Type: text/javascript');
    return ('window.lang = ' . json_encode($strings) . ';');
    exit();
})->name('assets.lang');