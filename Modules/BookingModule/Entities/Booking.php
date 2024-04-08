<?php

namespace Modules\BookingModule\Entities;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Modules\BookingModule\Http\Traits\BookingTrait;
use Modules\BookingModule\Http\Traits\BookingScopes;
use Modules\CategoryManagement\Entities\Category;
use Modules\ProviderManagement\Entities\Provider;
use Modules\UserManagement\Entities\Serviceman;
use Modules\UserManagement\Entities\User;
use Modules\UserManagement\Entities\UserAddress;
use Modules\ZoneManagement\Entities\Zone;

class Booking extends Model
{
    use HasFactory, HasUuid, BookingTrait, BookingScopes;

    protected $casts = [
        'readable_id' => 'integer',
        'is_paid' => 'integer',
        'is_verified' => 'integer',
        'total_booking_amount' => 'float',
        'total_tax_amount' => 'float',
        'total_discount_amount' => 'float',
        'total_campaign_discount_amount' => 'float',
        'total_coupon_discount_amount' => 'float',
        'is_checked' => 'integer',
        'additional_charge' => 'float',
        'additional_tax_amount' => 'float',
        'additional_discount_amount' => 'float',
        'additional_campaign_discount_amount' => 'float',
        'evidence_photos' => 'array',
        'extra_fee' => 'float',
    ];

    protected $fillable = [
        'id',
        'readable_id',
        'customer_id',
        'provider_id',
        'zone_id',
        'booking_status',
        'is_paid',
        'payment_method',
        'transaction_id',
        'total_booking_amount',
        'total_tax_amount',
        'total_discount_amount',
        'service_schedule',
        'service_address_id',
        'created_at',
        'updated_at',
        'category_id',
        'sub_category_id',
        'serviceman_id',
        'total_campaign_discount_amount',
        'total_coupon_discount_amount',
        'coupon_code',
        'is_checked',
        'additional_charge',
        'additional_tax_amount',
        'additional_discount_amount',
        'additional_campaign_discount_amount',
        'evidence_photos',
        'booking_otp',
        'is_verified'
    ];

    public function service_address(): BelongsTo
    {
        return $this->belongsTo(UserAddress::class, 'service_address_id');
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function subCategory(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'sub_category_id', 'id');
    }

    public function provider(): BelongsTo
    {
        return $this->belongsTo(Provider::class, 'provider_id');
    }

    public function zone(): BelongsTo
    {
        return $this->belongsTo(Zone::class, 'zone_id');
    }

    public function serviceman(): BelongsTo
    {
        return $this->belongsTo(Serviceman::class, 'serviceman_id');
    }

    public function detail(): HasMany
    {
        return $this->hasMany(BookingDetail::class);
    }

    public function booking_partial_payments(): HasMany
    {
        return $this->hasMany(BookingPartialPayment::class)->latest();
    }

    public function booking_details_amounts(): hasOne
    {
        return $this->hasOne(BookingDetailsAmount::class);
    }

    public function bookingDeniedNote(): hasOne
    {
        return $this->hasOne(BookingAdditionalInformation::class, 'booking_id')->where('key', 'booking_deny_note');
    }

    public function details_amounts(): hasMany
    {
        return $this->hasMany(BookingDetailsAmount::class);
    }

    public function schedule_histories(): HasMany
    {
        return $this->hasMany(BookingScheduleHistory::class);
    }

    public function status_histories(): HasMany
    {
        return $this->hasMany(BookingStatusHistory::class);
    }

    public function booking_offline_payments(): HasMany
    {
        return $this->hasMany(BookingOfflinePayment::class, 'booking_id');
    }

    public static function boot()
    {
        parent::boot();

        self::creating(function ($model) {
            $model->readable_id = $model->count() + 100000;
        });

        self::created(function ($model) {
        });

        self::updating(function ($model) {
            $booking_notification_status = business_config('booking', 'notification_settings')->live_values;

            if ($model->isDirty('booking_status')) {
                $key = null;
                if ($model->booking_status == 'pending') {
                    $notifications[] = [
                        'key' => 'booking_place',
                        'settings_type' => 'customer_notification'
                    ];
                } elseif ($model->booking_status == 'ongoing') {
                    $notifications[] = [
                        'key' => 'booking_ongoing',
                        'settings_type' => 'customer_notification'
                    ];
                    $notifications[] = [
                        'key' => 'ongoing_booking',
                        'settings_type' => 'provider_notification'
                    ];
                    $notifications[] = [
                        'key' => 'ongoing_booking',
                        'settings_type' => 'serviceman_notification'
                    ];
                } elseif ($model->booking_status == 'accepted') {
                    $notifications[] = [
                        'key' => 'booking_accepted',
                        'settings_type' => 'customer_notification'
                    ];
                    $notifications[] = [
                        'key' => 'booking_accepted',
                        'settings_type' => 'provider_notification'
                    ];
                } elseif ($model->booking_status == 'completed') {
                    $notifications[] = [
                        'key' => 'booking_complete',
                        'settings_type' => 'customer_notification'
                    ];
                    $notifications[] = [
                        'key' => 'booking_complete',
                        'settings_type' => 'provider_notification'
                    ];
                    $notifications[] = [
                        'key' => 'booking_complete',
                        'settings_type' => 'serviceman_notification'
                    ];
                    $model->is_paid = 1;

                    $provider = $model->provider;

                    if ($provider) {
                        $model->update_admin_commission($model, $model->total_booking_amount, $model->provider_id);
                    }


                    if (!$model->is_guest && $model?->customer) {
                        $model->referral_earning_calculation($model->customer_id);

                        $model->loyaltyPointCalculation($model->customer_id, $model->total_booking_amount);
                    }

                    //================ Transactions for Booking ================

                    if ($model?->provider) {
                        if ($model->booking_partial_payments->isNotEmpty()) {
                            if ($model['payment_method'] == 'cash_after_service') {
                                $booking_partial_payment = new BookingPartialPayment;
                                $booking_partial_payment->booking_id = $model->id;
                                $booking_partial_payment->paid_with = 'cash_after_service';
                                $booking_partial_payment->paid_amount = $model->booking_partial_payments->first()?->due_amount;
                                $booking_partial_payment->due_amount = 0;
                                $booking_partial_payment->save();

                                completeBookingTransactionForPartialCas($model);
                            } elseif ($model['payment_method'] != 'wallet_payment') {
                                completeBookingTransactionForPartialDigital($model);
                            }

                        } elseif ($model->payment_method == 'cash_after_service') {
                            completeBookingTransactionForCashAfterService($model);
                        } else {
                            if ($model->additional_charge == 0) {
                                completeBookingTransactionForDigitalPayment($model);
                            }

                            if ($model->additional_charge > 0) {
                                completeBookingTransactionForDigitalPaymentAndExtraService($model);
                            }
                        }

                        $limit_status = provider_warning_amount_calculate($provider->owner->account->account_payable, $provider->owner->account->account_receivable);

                        if ($limit_status == '100_percent' && business_config('suspend_on_exceed_cash_limit_provider', 'provider_config')->live_values) {
                            $provider->is_suspended = 1;
                            $provider->save();

                            $title = get_push_notification_message('provider_suspend', 'provider_notification', $provider?->owner?->current_language_key);
                            if ($provider?->owner?->fcm_token && $title) {
                                device_notification($provider?->owner?->fcm_token, $title, null, null, $model->id, 'suspend', null, $provider->id);
                            }
                        }
                    }

                } elseif ($model->booking_status == 'canceled') {
                    $notifications[] = [
                        'key' => 'booking_cancel',
                        'settings_type' => 'customer_notification'
                    ];
                    $notifications[] = [
                        'key' => 'booking_cancel',
                        'settings_type' => 'provider_notification'
                    ];
                    $notifications[] = [
                        'key' => 'booking_cancel',
                        'settings_type' => 'serviceman_notification'
                    ];

                    if ($model?->customer) {
                        refundTransactionForCanceledBooking($model);
                    }

                } elseif ($model->booking_status == 'refund_request') {
                    $notifications[] = [
                        [
                            'key' => 'refund',
                            'settings_type' => 'customer_notification'
                        ]
                    ];
                }


                if (isset($booking_notification_status) && $booking_notification_status['push_notification_booking']) {
                    foreach ($notifications ?? [] as $notification) {
                        $key = $notification['key'];
                        $settingsType = $notification['settings_type'];

                        if ($settingsType == 'customer_notification') {
                            $user = $model?->customer;
                            $title = get_push_notification_message($key, $settingsType, $user?->current_language_key);
                            if ($user?->fcm_token && $user?->is_active && $title) {
                                device_notification($user?->fcm_token, $title, null, null, $model->id, 'booking');
                            }
                        }

                        if ($settingsType == 'provider_notification') {
                            if ((!business_config('suspend_on_exceed_cash_limit_provider', 'provider_config')->live_values || $model?->provider?->is_suspended == 0) && $model->booking_status == 'pending') {
                                $provider = $model?->provider?->owner;
                                $title = get_push_notification_message($key, $settingsType, $provider?->current_language_key);
                                if ($provider?->fcm_token && $title) {
                                    device_notification($provider?->fcm_token, $title, null, null, $model->id, 'booking');
                                }
                            } else {
                                $provider = $model?->provider?->owner;
                                $title = get_push_notification_message($key, $settingsType, $provider?->current_language_key);
                                if ($provider?->fcm_token && $title) {
                                    device_notification($provider?->fcm_token, $title, null, null, $model->id, 'booking');
                                }
                            }
                        }

                        if ($settingsType == 'serviceman_notification') {
                            $serviceman = $model?->serviceman?->user;
                            $title = get_push_notification_message($key, $settingsType, $serviceman?->current_language_key);
                            if ($serviceman?->fcm_token && $title) {
                                device_notification($serviceman?->fcm_token, $title, null, null, $model->id, 'booking');
                            }
                        }
                    }
                }
            }
        });

        self::updated(function ($model) {
            $notifications = [];
            $booking_notification_status = business_config('booking', 'notification_settings')->live_values;

            if ($model->isDirty('serviceman_id')) {
                $notifications[] = [
                    'key' => 'serviceman_assign',
                    'settings_type' => 'customer_notification'
                ];
                $notifications[] = [
                    'key' => 'serviceman_assign',
                    'settings_type' => 'provider_notification'
                ];
                $notifications[] = [
                    'key' => 'serviceman_assign',
                    'settings_type' => 'serviceman_notification'
                ];
            }

            if ($model->isDirty('service_schedule')) {
                $notifications[] = [
                    'key' => 'booking_schedule_time_change',
                    'settings_type' => 'customer_notification'
                ];
                $notifications[] = [
                    'key' => 'booking_schedule_time_change',
                    'settings_type' => 'provider_notification'
                ];
                $notifications[] = [
                    'key' => 'booking_schedule_time_change',
                    'settings_type' => 'serviceman_notification'
                ];
            }

            if (isset($booking_notification_status) && $booking_notification_status['push_notification_booking']) {
                foreach ($notifications ?? [] as $notification) {
                    $key = $notification['key'];
                    $settingsType = $notification['settings_type'];

                    if ($settingsType == 'customer_notification') {
                        $user = $model?->customer;
                        $title = get_push_notification_message($key, $settingsType, $user?->current_language_key);
                        if ($user?->fcm_token && $title) {
                            device_notification($user?->fcm_token, $title, null, null, $model->id, 'booking');
                        }
                    }

                    if ($settingsType == 'provider_notification') {
                        if ((!business_config('suspend_on_exceed_cash_limit_provider', 'provider_config')->live_values || $model?->provider?->is_suspended == 0) && $model->booking_status == 'pending') {
                            $provider = $model?->provider?->owner;
                            $title = get_push_notification_message($key, $settingsType, $provider?->current_language_key);
                            if ($provider?->fcm_token && $title) {
                                device_notification($provider?->fcm_token, $title, null, null, $model->id, 'booking');
                            }
                        } else {
                            $provider = $model?->provider?->owner;
                            $title = get_push_notification_message($key, $settingsType, $provider?->current_language_key);
                            if ($provider?->fcm_token && $title) {
                                device_notification($provider?->fcm_token, $title, null, null, $model->id, 'booking');
                            }
                        }
                    }

                    if ($settingsType == 'serviceman_notification') {
                        $serviceman = $model?->serviceman?->user;
                        $title = get_push_notification_message($key, $settingsType, $serviceman?->current_language_key);
                        if ($serviceman?->fcm_token && $title) {
                            device_notification($serviceman?->fcm_token, $title, null, null, $model->id, 'booking');
                        }
                    }
                }
            }
        });


        self::deleting(function ($model) {

        });

        self::deleted(function ($model) {

        });
    }
}
