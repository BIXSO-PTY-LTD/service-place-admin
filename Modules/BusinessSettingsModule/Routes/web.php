<?php

use Illuminate\Support\Facades\Route;
use Modules\BusinessSettingsModule\Http\Controllers\Web\Admin\BusinessInformationController;
use Modules\BusinessSettingsModule\Http\Controllers\Web\Admin\LanguageController;
use Modules\BusinessSettingsModule\Http\Controllers\Web\Provider\BusinessInformationController as ProviderBusinessInformationController;
use Modules\BusinessSettingsModule\Http\Controllers\Web\Admin\ConfigurationController;

Route::group(['namespace' => 'Api\V1\Admin'], function () {
    Route::get('file-manager', 'FileManagerController@index');
});


Route::group(['prefix' => 'admin', 'as' => 'admin.', 'namespace' => 'Web\Admin', 'middleware' => ['admin','mpc:system_management']], function () {

    Route::get('lang/{locale}', 'LanguageController@lang')->name('lang');

    Route::group(['prefix' => 'business-settings', 'as' => 'business-settings.'], function () {
        Route::get('get-business-information', 'BusinessInformationController@businessInformationGet')->name('get-business-information');
        Route::put('set-business-information', 'BusinessInformationController@businessInformationSet')->name('set-business-information');

        Route::put('set-otp-login-information', [BusinessInformationController::class, 'otpLoginInformationSet'])->name('set-otp-login-information');

        Route::put('set-bidding-system', 'BusinessInformationController@setBiddingSystem')->name('set-bidding-system');

        Route::put('update-action-status', 'BusinessInformationController@updateActionStatus')->name('update-action-status');
        Route::put('set-promotion-setup', 'BusinessInformationController@promotionSetupSet')->name('set-promotion-setup');
        Route::put('set-customer-setup', 'BusinessInformationController@customerSetup')->name('set-customer-setup');
        Route::put('set-provider-setup', 'BusinessInformationController@providerSetup')->name('set-provider-setup');
        Route::put('set-service-setup', 'BusinessInformationController@serviceSetup')->name('set-service-setup');
        Route::put('set-servicemen', 'BusinessInformationController@servicemen')->name('set-servicemen');

        Route::put('set-booking-setup', 'BusinessInformationController@bookingSetupSet')->name('set-booking-setup');

        Route::get('get-pages-setup', 'BusinessInformationController@pagesSetupGet')->name('get-pages-setup');
        Route::post('set-pages-setup', 'BusinessInformationController@pagesSetupSet')->name('set-pages-setup');

        //Gallery
        Route::get('get-gallery-setup/{folder_path?}', [BusinessInformationController::class, 'gallerySetupGet'])->name('get-gallery-setup');
        Route::post('/image-upload', [BusinessInformationController::class, 'galleryImageUpload'])->name('upload-gallery-image');
        Route::get('/image-download/{file_name}', [BusinessInformationController::class, 'galleryImageDownload'])->name('download-gallery-image');
        Route::delete('/delete/{file_path}', [BusinessInformationController::class, 'galleryImageRemove'])->name('remove-gallery-image');
        Route::get('download/public', [BusinessInformationController::class, 'downloadPublicDirectory'])->name('download.public');

        //database backup
        Route::get('get-database-backup', [BusinessInformationController::class, 'getDatabaseBackup'])->name('get-database-backup');
        Route::get('backup-database-backup', [BusinessInformationController::class, 'backupDatabase'])->name('backup-database-backup');
        Route::get('delete-database-backup/{file_name}', [BusinessInformationController::class, 'deleteDatabaseBackup'])->name('delete-database-backup');
        Route::get('restore-database-backup/{file_name}', [BusinessInformationController::class, 'restoreDatabaseBackup'])->name('restore-database-backup');
        Route::get('download-database-backup/{file_name}', [BusinessInformationController::class, 'download'])->name('download-database-backup');

        Route::get('get-landing-information', 'LandingPageController@getLandingInformation')->name('get-landing-information');
        Route::put('set-landing-information', 'LandingPageController@setLandingInformation')->name('set-landing-information');
        Route::put('set-landing-feature', 'LandingPageController@setLandingFeature')->name('set-landing-feature');
        Route::put('set-landing-speciality', 'LandingPageController@setLandingSpeciality')->name('set-landing-speciality');
        Route::put('set-landing-testimonial', 'LandingPageController@setLandingTestimonial')->name('set-landing-testimonial');
        Route::delete('delete-landing-information/{page}/{id}', 'LandingPageController@deleteLandingInformation')->name('delete-landing-information');
        Route::delete('delete-landing-feature/{id}', 'LandingPageController@deleteLandingFeature')->name('delete-landing-feature');
        Route::delete('delete-landing-speciality/{id}', 'LandingPageController@deleteLandingSpeciality')->name('delete-landing-speciality');
        Route::delete('delete-landing-testimonial/{id}', 'LandingPageController@deleteLandingTestimonial')->name('delete-landing-testimonial');
    });

    Route::group(['prefix' => 'language', 'as' => 'language.'], function () {
        Route::post('store', [LanguageController::class, 'store'])->name('store');
        Route::get('update-status', [LanguageController::class, 'updateStatus'])->name('update-status');
        Route::get('update-default-status', [LanguageController::class, 'updateDefaultStatus'])->name('update-default-status');
        Route::post('update', [LanguageController::class, 'update'])->name('update');
        Route::get('translate/{lang}', [LanguageController::class, 'translate'])->name('translate');
        Route::post('translate-submit/{lang}', [LanguageController::class, 'translateSubmit'])->name('translate-submit');
        Route::post('remove-key/{lang}', [LanguageController::class, 'translateKeyRemove'])->name('remove-key');
        Route::delete('delete/{lang}', [LanguageController::class, 'delete'])->name('delete');
        Route::any('auto-translate/{lang}', [LanguageController::class, 'autoTranslate'])->name('auto-translate');

    });

    Route::group(['prefix' => 'configuration', 'as' => 'configuration.'], function () {
        Route::get('get-notification-setting', 'ConfigurationController@notificationSettingsGet')->name('get-notification-setting');
        Route::put('set-notification-setting', 'ConfigurationController@notificationSettingsSet')->name('set-notification-setting');
        Route::any('set-message-setting', 'ConfigurationController@messageSettingsSet')->name('set-message-setting');

        Route::get('get-email-config', 'ConfigurationController@emailConfigGet')->name('get-email-config');
        Route::put('set-email-config', 'ConfigurationController@emailConfigSet')->name('set-email-config');
        Route::get('test-send-email', 'ConfigurationController@sendMail')->name('send-mail');

        Route::get('get-third-party-config', 'ConfigurationController@thirdPartyConfigGet')->name('get-third-party-config');
        Route::put('set-third-party-config', 'ConfigurationController@thirdPartyConfigSet')->name('set-third-party-config');

        Route::get('get-app-settings', 'ConfigurationController@appSettingsConfigGet')->name('get-app-settings');
        Route::put('set-app-settings', 'ConfigurationController@appSettingsConfigSet')->name('set-app-settings');

        Route::get('language-setup', 'ConfigurationController@languageSetup')->name('language_setup');

        Route::put('social-login-config-set', [ConfigurationController::class, 'setSocialLoginConfig'])->name('social-login-config-set');
    });

    Route::group(['prefix' => 'customer', 'as' => 'customer.'], function () {
        Route::get('settings', [ConfigurationController::class, 'getCustomerSettings'])->name('settings');
        Route::put('settings', [ConfigurationController::class, 'setCustomerSettings']);
    });
});

Route::group(['prefix' => 'provider', 'as' => 'provider.', 'namespace' => 'Web\Provider', 'middleware' => ['provider']], function () {
    Route::group(['prefix' => 'business-settings', 'as' => 'business-settings.'], function () {
        Route::get('get-business-information', [ProviderBusinessInformationController::class, 'businessInformationGet'])->name('get-business-information');
        Route::put('set-business-information', [ProviderBusinessInformationController::class, 'businessInformationSet'])->name('set-business-information');
        Route::put('availability-status', [ProviderBusinessInformationController::class, 'availabilityStatus'])->name('availability-status');
        Route::put('availability-schedule', [ProviderBusinessInformationController::class, 'availabilitySchedule'])->name('availability-schedule');
    });
});
