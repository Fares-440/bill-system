<?php

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

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth.login');
    // return view('welcome');
});

// Auth::routes();
Auth::routes(['register' => false]);

// Route::get('/home', 'HomeController@index')->name('home');
// 'middleware' => 'auth',
Route::group(['middleware' => 'userstate'], function () {
    Route::get('/home', 'HomeController@index')->name('home');
    // Route::get('invoices', 'InvoicesController@index')->name('invoices');
    Route::resource('roles', 'RoleController');
    Route::resource('users', 'UserController');
    Route::delete('usersdelete', 'UserController@delete')->name('usersdelete');
    Route::delete('roledelete', 'RoleController@delete')->name('roledelete');
    Route::resource('invoices', 'InvoicesController');
    Route::get('/section/{id}', 'InvoicesController@getproducts');
    Route::get('edit_invoice/{id}', 'InvoicesController@edit')->name('edit_invoice');
    Route::get('/Status_show/{id}', 'InvoicesController@show')->name('Status_show');
    Route::post('/Status_Update/{id}', 'InvoicesController@Status_Update')->name('Status_Update');
    Route::get('الفواتير_المدفوعة', 'InvoicesController@Invoice_Paid');
    Route::get('الفواتير_الغي_مدفوعة', 'InvoicesController@Invoice_UnPaid');
    Route::get('الفواتير_المدفوعة_جزئياً', 'InvoicesController@Invoice_Partial');
    Route::resource('Archive', 'InvoiceAchiveController');
    Route::get('Print_invoice/{id}', 'InvoicesDetailsController@Print_invoice');
    Route::get('invoices_export', 'InvoicesController@export');
    // invoicesDetails
    Route::get('/InvoicesDetails/{id}', 'InvoicesDetailsController@edit');
    Route::get('download/{invoice_number}/{file_name}', 'InvoicesDetailsController@get_file');
    Route::get('View_file/{invoice_number}/{file_name}', 'InvoicesDetailsController@open_file');
    Route::post('delete_file', 'InvoicesDetailsController@destroy')->name('delete_file');
    // sections
    Route::get('sections', 'SectionsController@index')->name('sections');
    Route::post('sections', 'SectionsController@store');
    Route::put('sections', 'SectionsController@update');
    Route::delete('sections', 'SectionsController@delete');
    // products
    Route::get('products', 'ProductsController@index')->name('products');
    Route::post('products', 'ProductsController@store');
    Route::put('products', 'ProductsController@update');
    Route::delete('products', 'ProductsController@destroy');

    // invocieattachments
    Route::post('InvoiceAttachments', 'InvoiceAttachmentsController@store')->name('InvoiceAttachments');

    // reports
    Route::get('invoices_report', 'Invoices_Report@index');
    Route::post('Search_invoices', 'Invoices_Report@Search_invoices');
    Route::get('customers_report', 'Customers_Report@index')->name("customers_report");
    Route::post('Search_customers', 'Customers_Report@Search_customers')->name('Search_customers');


    // Notification
    Route::get('markup', 'InvoicesController@notificationallclear')->name('markup');

    Route::get('/{page}', 'AdminController@index');
});
