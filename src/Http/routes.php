<?php

Route::group(['prefix' => config('admin.url')], function () {
    Route::group(['middleware' => ['auth.admin', 'auth.rules']], function () {
        Route::get('mmail/trash', ['uses' => 'MmailsAdminController@index', 'as' => 'admin.mmails.trash']);
        Route::post('mmails/restore/{id}', ['uses' => 'MmailsAdminController@restore', 'as' => 'admin.mmails.restore']);
        Route::resource('mmails', 'MmailsAdminController', [
            'names' => [
                'index' => 'admin.mmails.index',
                'create' => 'admin.mmails.create',
                'store' => 'admin.mmails.store',
                'edit' => 'admin.mmails.edit',
                'update' => 'admin.mmails.update',
                'show' => 'admin.mmails.show',
            ], 'except' => ['destroy']]);
        Route::delete('mmails/destroy', ['uses' => 'MmailsAdminController@destroy', 'as' => 'admin.mmails.destroy']);
    });
});