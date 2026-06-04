<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\CategoryController;
use App\Http\Controllers\Api\V1\ContactController;
use App\Http\Controllers\Api\V1\LoginHistoryController;
use App\Http\Controllers\Api\V1\Settings\BrandingController;
use App\Http\Controllers\Api\V1\Settings\EmailTemplateController;
use App\Http\Controllers\Api\V1\Settings\SmtpController;
use App\Http\Controllers\Api\V1\Settings\SocialMediaController;
use App\Http\Controllers\Api\V1\Settings\WhatsappSettingController;
use App\Http\Controllers\Api\V1\SubCategoryController;
use App\Http\Controllers\Api\V1\ProductController;
use App\Http\Controllers\Api\V1\TestinomialController;
use App\Http\Controllers\Api\V1\UserController;
use App\Http\Controllers\Api\V1\HeroController;
use App\Http\Controllers\Api\V1\ServicesController;
use App\Http\Controllers\Api\V1\BlogCategoryController;
use App\Http\Controllers\Api\V1\BlogController;
use App\Http\Controllers\Api\V1\SubscriberController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*|----------------------------------------------------------------
| API Routes    
|--------------------------------------------------------------------------
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
*/

Route::get('/test', function () {
    return response()->json(['message' => 'API is working']);
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('v1')->group(function () {
    Route::get('/test', function () {
        return response()->json(['message' => 'API v1 is working']);
    });

    Route::prefix('frontend')->group(function () {
        Route::get('/categories', [CategoryController::class, 'activeCategories']);
        Route::get('/sub-categories', [SubCategoryController::class, 'activeSubCategories']);
        Route::get('/products', [ProductController::class, 'getActiveProducts']);
        Route::get('/product/{slug}', [ProductController::class, 'getBySlug']);
        Route::get('/testimonials', [TestinomialController::class, 'getActiveTestimonials']);
        Route::get('/heroes', [HeroController::class, 'activeHeroes']);
        Route::get('/services', [ServicesController::class, 'activeServices']);
        Route::get('/blog-categories', [BlogCategoryController::class, 'activeBlogCategories']);
        Route::get('/blogs', [BlogController::class, 'activeBlogs']);
        Route::get('/blog/{slug}', [BlogController::class, 'getBySlug']);
        Route::post('/contact', [ContactController::class, 'store']);
        Route::post('/enquiry', [ContactController::class, 'store']);
        Route::post('/subscribe', [SubscriberController::class, 'store']);
        Route::get('/whatsapp-widget', [WhatsappSettingController::class, 'getWidgetConfig']);
    });

    Route::prefix('auth')->group(function () {
        Route::post('/login', [AuthController::class, 'login']);
        Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
        Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
        Route::post('/reset-password', [AuthController::class, 'resetPassword']);
    });

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/users', [UserController::class, 'list']);
        Route::post('/users/status', [UserController::class, 'status']);
        Route::post('/users/export', [UserController::class, 'export']);
        Route::get('/users/{id}', [UserController::class, 'show']);
        Route::post('/users', [UserController::class, 'store']);
        Route::delete('/users/{id}', [UserController::class, 'destroy']);

        Route::get('/profile', [UserController::class, 'profile']);
        Route::post('/profile', [UserController::class, 'updateProfile']);
        Route::post('/change-password', [UserController::class, 'changePassword']);

        Route::get('login-history', [LoginHistoryController::class, 'list']);
        Route::post('login-history/export', [LoginHistoryController::class, 'export']);

        Route::prefix('contact')->group(function () {
            Route::get('/', [ContactController::class, 'list']);
            Route::post('/export', [ContactController::class, 'export']);
            Route::post('/status-change', [ContactController::class, 'statusChange']);
            Route::get('/{id}', [ContactController::class, 'show']);
            Route::delete('/{id}', [ContactController::class, 'destroy']);
        });

        Route::get('/testinomial', [TestinomialController::class, 'list']);
        Route::post('/testinomial/status', [TestinomialController::class, 'status']);
        Route::post('/testinomial/export', [TestinomialController::class, 'export']);
        Route::get('/testinomial/{id}', [TestinomialController::class, 'show']);
        Route::post('/testinomial', [TestinomialController::class, 'store']);
        Route::delete('/testinomial/{id}', [TestinomialController::class, 'destroy']);

        Route::prefix('categories')->group(function () {
            Route::get('/', [CategoryController::class, 'list']);
            Route::post('/', [CategoryController::class, 'store']);
            Route::post('/status-change', [CategoryController::class, 'statusChange']);
            Route::post('/change-is-comming-status', [CategoryController::class, 'changeIsCommingStatus']);
            Route::get('/{id}', [CategoryController::class, 'show']);
            Route::delete('/{id}', [CategoryController::class, 'destroy']);
        });

        Route::prefix('sub-categories')->group(function () {
            Route::get('/', [SubCategoryController::class, 'list']);
            Route::post('/status', [SubCategoryController::class, 'statusChange']);
            Route::post('/', [SubCategoryController::class, 'store']);
            Route::get('/{id}', [SubCategoryController::class, 'show']);
            Route::delete('/{id}', [SubCategoryController::class, 'destroy']);
        });

        Route::prefix('blog-categories')->group(function () {
            Route::get('/', [BlogCategoryController::class, 'list']);
            Route::post('/status-change', [BlogCategoryController::class, 'statusChange']);
            Route::post('/', [BlogCategoryController::class, 'store']);
            Route::get('/{id}', [BlogCategoryController::class, 'show']);
            Route::delete('/{id}', [BlogCategoryController::class, 'destroy']);
        });

        Route::prefix('blogs')->group(function () {
            Route::get('/', [BlogController::class, 'list']);
            Route::post('/status-change', [BlogController::class, 'statusChange']);
            Route::post('/', [BlogController::class, 'store']);
            Route::get('/{id}', [BlogController::class, 'show']);
            Route::delete('/{id}', [BlogController::class, 'destroy']);
        });

        Route::prefix('products')->group(function () {
            Route::get('/', [ProductController::class, 'list']);
            Route::post('/status-change', [ProductController::class, 'statusChange']);
            Route::post('/', [ProductController::class, 'store']);
            Route::get('/{id}', [ProductController::class, 'show']);
            Route::delete('/{id}', [ProductController::class, 'destroy']);
        });

        Route::prefix('heroes')->group(function () {
            Route::get('/', [HeroController::class, 'list']);
            Route::post('/status-change', [HeroController::class, 'statusChange']);
            Route::post('/', [HeroController::class, 'store']);
            Route::get('/{id}', [HeroController::class, 'show']);
            Route::delete('/{id}', [HeroController::class, 'destroy']);
        });

        Route::prefix('services')->group(function () {
            Route::get('/', [ServicesController::class, 'list']);
            Route::post('/status', [ServicesController::class, 'status']);
            Route::post('/', [ServicesController::class, 'store']);
            Route::get('/{id}', [ServicesController::class, 'show']);
            Route::delete('/{id}', [ServicesController::class, 'destroy']);
        });

        Route::prefix('subscribers')->group(function () {
            Route::get('/', [SubscriberController::class, 'list']);
            Route::post('/status', [SubscriberController::class, 'status']);
            Route::post('/export', [SubscriberController::class, 'export']);
            Route::post('/', [SubscriberController::class, 'store']);
            Route::get('/{id}', [SubscriberController::class, 'show']);
            Route::delete('/{id}', [SubscriberController::class, 'destroy']);
        });

        Route::prefix('settings')->group(function () {
            Route::get('/smtp', [SmtpController::class, 'list']);
            Route::post('/smtp', [SmtpController::class, 'store']);
            Route::post('/smtp/test', [SmtpController::class, 'testMail']);
            Route::get('/smtp/{id}', [SmtpController::class, 'show']);

            Route::get('/email-templates', [EmailTemplateController::class, 'list']);
            Route::post('/email-templates/status', [EmailTemplateController::class, 'status']);
            Route::post('/email-templates/export', [EmailTemplateController::class, 'export']);
            Route::post('/email-templates', [EmailTemplateController::class, 'store']);
            Route::get('/email-templates/{id}', [EmailTemplateController::class, 'show']);
            Route::delete('/email-templates/{id}', [EmailTemplateController::class, 'destroy']);

            Route::get('/branding', [BrandingController::class, 'list']);
            Route::post('/branding', [BrandingController::class, 'store']);
            Route::get('/branding/{id}', [BrandingController::class, 'show']);

            Route::get('/social-media', [SocialMediaController::class, 'list']);
            Route::post('/social-media', [SocialMediaController::class, 'store']);
            Route::get('/social-media/{id}', [SocialMediaController::class, 'show']);

            Route::get('/whatsapp', [WhatsappSettingController::class, 'list']);
            Route::post('/whatsapp', [WhatsappSettingController::class, 'store']);
            Route::get('/whatsapp/{id}', [WhatsappSettingController::class, 'show']);
        });
    });
});
