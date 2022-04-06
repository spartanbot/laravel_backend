<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserAuth;
use App\Http\Controllers\ResourseController;
use App\Http\Controllers\BasicInformationController;
use App\Http\Controllers\InstructorDashboardController;
use App\Http\Controllers\SellerDashboardController;
use App\Http\Controllers\UserDashboardController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\VerifyRegisterUserController;
use App\Http\Controllers\ResetPasswordController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\AdminController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

// // Here admin route
 


Route::post('/user_register', [UserAuth::class,'register']);
Route::post('/user_login', [UserAuth::class,'login']);

Route::get('auth/google', [UserAuth::class, 'redirectToGoogle']);
Route::get('callback/google', [UserAuth::class, 'handleCallback']);

//Route::get('/user/verify/{token}', [UserAuth::class,'verify_user'])->name('verify');

// Route::get('/user/verify/{token}', [VerifyRegisterUserController::class,'verifyUser']);

// Route::post('forgot-password', [ResetPasswordController::class, 'forgot_password']);
// Route::post('reset-password/{token}', [ResetPasswordController::class, 'reset_assword']);

Route::post('/forgot_password', [UserAuth::class, 'forgot_password']);
Route::post('/reset_password', [UserAuth::class, 'reset_assword']);

Route::group(['middleware' => 'auth:api'], function () {

Route::get('/logout', [UserAuth::class, 'logout']);



/******************************************************************
****************************************************
route basic information related****************************************
**********************************************************************************/

Route::post('/basic_create', 
    [BasicInformationController::class,'basicCreate']);
Route::post('/basic_edit/{id}', 
    [BasicInformationController::class,'basicEdit']);
Route::post('/basic_update', 
    [BasicInformationController::class,'basicUpdate']);
Route::post('/basic_delete/{id}', 
    [BasicInformationController::class,'basicDelete']);
Route::get('/all_info', 
    [BasicInformationController::class,'allInfo']);

 /******************************************************************************
 ****************************************route Category info related************
 *********************************************************************************
 *****************************/
Route::post('/create_category', 
    [CategoryController::class,'createCategory']);
Route::post('/edit_category',  
    [CategoryController::class,'editCategory']);
Route::post('/update_category',  
    [CategoryController::class,'updateCategory']);
Route::get('/all_category',  
    [CategoryController::class,'fetchAllCategory']);
    
         /******************************************************************************
 ****************************************route Language info related************
 *********************************************************************************
 *****************************/
Route::post('/add_language', 
[LanguageController::class,'addLanguage']);
Route::post('/edit_language',  
[LanguageController::class,'editLanguage']);
Route::post('/update_language',  
[LanguageController::class,'updateLanguage']);
Route::get('/all_language',  
[LanguageController::class,'fetchAllLanguage']);

/******************************************************************************
 ****************************************route course info related************
 *********************************************************************************
 *****************************/
Route::post('/create_course', 
[CourseController::class,'createCourse']);
Route::post('/edit_course', 
[CourseController::class,'editCourse']);
Route::post('/update_course', 
[CourseController::class,'updateCourse']);
Route::post('/remove_content', 
[CourseController::class,'removeContent']);
Route::get('/all_course', 
[CourseController::class,'fetchAllCourse']);
Route::get('/unpublish_course', 
[CourseController::class,'fetchUnpublishCourse']);
Route::post('/update_course_publish_status', 
[CourseController::class,'updatePublishStatus']);
Route::get('/all_resourse', 
[CourseController::class,'fetchAllResourse']);

/******************************************************************************
 ****************************************route cart info related************
 *********************************************************************************
 *****************************/
Route::post('/add_to_cart', 
[CartController::class,'cartAdd']);
Route::get('/cart_items', 
[CartController::class,'cartItem']);
Route::post('/cart_item_delete', 
[CartController::class,'cartDelete']);
Route::post('/checkout', 
[CartController::class,'checkoutOrder']);

/******************************************************************************
 ****************************************route cart info related************
 *********************************************************************************
 *****************************/
Route::post('/add_bank', 
[SellerDashboardController::class,'addBankAccount']);

/******************************************************************************
 ****************************************route admin info related************
 *********************************************************************************
 *****************************/
Route::get('/admin/fetch_all_order', 
[AdminController::class,'fetchAllOrders']);
Route::get('/admin/fetch_all_products', 
[AdminController::class,'fetchAllproduct']);
Route::get('/admin/single_product', 
[AdminController::class,'singleProductPage']);
Route::get('/admin/all_buyer', 
[AdminController::class,'fetchBuyers']);
Route::get('/admin/view_profile', 
[AdminController::class,'viewUserProfile']);
Route::get('/admin/user_order_history', 
[AdminController::class,'userOrderHistory']);
Route::get('/admin/fetch_user_orderItems', 
[AdminController::class,'userOrderItem']);
Route::get('/admin/all_seller', 
[AdminController::class,'fetchSellers']);
Route::get('/admin/seller_profile', 
[AdminController::class,'viewSellerProfile']);
Route::get('/admin/seller_products', 
[AdminController::class,'sellerProducts']);
Route::get('/admin/all_users', 
[AdminController::class,'all_users']);

 /******************************************************************************
 ****************************************route user basic info related************
 *********************************************************************************
 *****************************/
 
 
 Route::post('/userinfo_create', 
    [UserDashboardController::class,'createUserinfo']);
Route::post('/userinfo_update', 
    [UserDashboardController::class,'updateUserinfo']);
Route::post('/userinfo_edit/{id}', 
    [UserDashboardController::class,'editUserinfo']);
Route::post('/userinfo_delete/{id}', 
    [UserDashboardController::class,'deleteUserinfo']);
Route::get('/all_userinfo', 
    [UserDashboardController::class,'getAllUserinfo']);

/*****************************************************************************************
*****************************route Payment deatils****************************************
**********************************************************************************/

Route::post('/payment_create', 
    [PaymentController::class,'createPayment']);
Route::post('/payment_update', 
    [PaymentController::class,'updatePayment']);
Route::post('/payment_edit/{id}', 
    [PaymentController::class,'editPayment']);
Route::post('/payment_delete/{id}', 
    [PaymentController::class,'deletePayment']);
Route::get('/all_payment', 
    [PaymentController::class,'allPaymentDetails']);                                                                   
});




 // Route::get('/products/{id}', [CategorieController::class,'index']);