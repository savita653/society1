<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Thomasjohnkane\Snooze\Traits\SnoozeNotifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;
use Laravel\Cashier\Billable;
use App\Helpers\Helper;
use App\Notifications\AdminAccountCredential;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\UserAddress;
use App\UserMeta;
use App\Event;
use Spatie\Activitylog\Traits\LogsActivity;
use App\Notifications\Auth\ResetPassword;
use App\Notifications\Auth\VerifyEmail;

/**
 * App\User
 *
 * @property int $id
 * @property string $name
 * @property string|null $last_name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $profile_photo_path
 * @property int $is_active
 * @property int $can_contact Would you like to be contacted by interested recruiters?
 * @property string|null $timezone
 * @property string|null $profile_status Profile status for managing status of Presenters (Approved/Declined)
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $stripe_id
 * @property string|null $card_brand
 * @property string|null $card_last_four
 * @property string|null $trial_ends_at
 * @property-read \Illuminate\Database\Eloquent\Collection|UserAddress[] $addresses
 * @property-read int|null $addresses_count
 * @property-read \Illuminate\Database\Eloquent\Collection|Event[] $events
 * @property-read int|null $events_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Permission\Models\Permission[] $permissions
 * @property-read int|null $permissions_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Permission\Models\Role[] $roles
 * @property-read int|null $roles_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\Laravel\Cashier\Subscription[] $subscriptions
 * @property-read int|null $subscriptions_count
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Query\Builder|User onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|User permission($permissions)
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User role($roles, $guard = null)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCanContact($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCardBrand($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCardLastFour($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereLastName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereProfilePhotoPath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereProfileStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereStripeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereTimezone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereTrialEndsAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|User withTrashed()
 * @method static \Illuminate\Database\Query\Builder|User withoutTrashed()
 * @mixin \Eloquent
 */
class User extends Authenticatable implements MustVerifyEmail
{
	use Notifiable, HasRoles, Billable, SoftDeletes, SnoozeNotifiable, LogsActivity;

	const SUBSCRIBE_NEWSLETTER = 'newsletter';
	const EMAIL_NOTIFICATION = 'email_notification';
	
	protected static $logOnlyDirty = true;
	protected static $logFillable = true;
	protected $guarded = [];

	/**
	 * Send the password reset notification.
	 *
	 * @param  string  $token
	 * @return void
	 */

	public function house()
	{
		
		return $this->hasone(House::class);
	}

	public function sendPasswordResetNotification($token)
	{
		$this->notify(new ResetPassword($token));
	}

	public function sendEmailVerificationNotification()
	{
		$this->notify(new VerifyEmail());
	}

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'name', 'last_name', 'email', 'password', 'mobile', 'house_id', 'isOwner', 'DOA', 'DOD'
	];

	/**
	 * The attributes that should be hidden for arrays.
	 *
	 * @var array
	 */
	protected $hidden = [
		'password', 'remember_token',
	];

	/**
	 * The attributes that should be cast to native types.
	 *
	 * @var array
	 */
	protected $casts = [
		'email_verified_at' => 'datetime',
	];

	public function fullName()
	{
		return $this->name . " "  . $this->last_name ?? "";
	}

	public function roleName()
	{
		$roleName = $this->roles->pluck('name')->first();
		return Helper::humanFriendlyText($roleName);
	}

	public function profileImage()
	{
		if ($this->profile_photo_path) {
            return asset("uploads/profile_pic/web/" . $this->profile_photo_path);
        } else {
            return asset('images/avatars/profile.png');
        }
	}

	public function statusHtml()
	{
		if($this->is_active) {
			$class = "success";
			$text = "Active";
		} else {
			$class = "danger";
			$text = "Inactive";
		}
		return "<span class='badge badge-pill badge-$class'>$text</span>";
	}

	public function events()
	{
		return $this->belongsToMany(Event::class);
	}

	public function sendCredentials($password)
	{
		$this->notify(new AdminAccountCredential($password));
	}

	public function updateOrCreateMeta($key, $value)
	{
		UserMeta::updateOrCreate(
			['meta_key' => $key, 'user_id' => $this->id],
			['meta_key' => $key, 'meta_value' => $value, 'user_id' => $this->id]
		);
	}

	public function updateOrCreateAddress($addressType, $address)
	{
		UserAddress::updateOrCreate(
			['address_type' => $addressType, 'user_id' => $this->id],
			array_merge($address, ['user_id' => $this->id, 'address_type' => $addressType])      
		);
	}

	public function getMeta($key)
	{
		return UserMeta::where([
			'meta_key' => $key,
			'user_id' => $this->id,
		])->first()->meta_value ?? "";
	}

	public function notificationEnabled()
	{
		return $this->getMeta(self::EMAIL_NOTIFICATION) == 1 ? true : false;
	}

	public function addresses()
	{
		return $this->hasMany('App\UserAddress');
	}

	public function address($type)
	{
		$address = $this->addresses()->where('address_type', $type)->first();
		if($address) {
			return $address->toArray();
		} else {
			return [
				'street_name' => '',
				'city' => '',
				'state' => '',
				'postal_code' => '',
				'country' => ''
			];
		}
	}

	public function approved()
	{
		return $this->profile_status == "approved";
	}

	public static function areaOfInterest($areasOfInterest)
	{
		$areasOfInterest = json_decode($areasOfInterest, true);
		return $areasOfInterest;
	}

	public function getPriceId()
	{
		// if academic email, return academic student plan
		if( Helper::isAcademicAddress($this->email) ) {
			return config('plan.academic_student.price_id');
		}

		$level1 = json_decode($this->getMeta('membership_level_1'));
		$level2 = json_decode($this->getMeta('membership_level_2'));

		// Coroporate Professional
		if($level1->id == 1 && $level2->id == 10) {
			return config('plan.corporate_professional.price_id');
		}

		// Academic Professional
		if($level1->id == 1 && $level2->id != 10) {
			return config('plan.academic_professional.price_id');
		}

		// Academic Postdoctoral
		if($level1->id == 13) {
			return config('plan.academic_postdoctoral.price_id');
		}

		// Academic Student
		if($level1->id == 14) {
			return config('plan.academic_student.price_id');
		}

		return false;

	}

	public function institutionAddress($format = 'simple')
	{	
		
		$address = $this->address('institution_address');

        switch ($format) {
            case 'simple':
                $address_str = [];
                if (!empty( $address['city'] )) {
                    $address_str[] = $address['city'];
                }
                if (!empty($address['state'])) {
                    $address_str[] = $address['state'];
                }
                if (!empty($address['country'])) {
                    $address_str[] = $address['country'];
                }
                return implode(", ", $address_str);

                break;

            default:
                $address_str = [];
				if (!empty( $address['street_name'] )) {
                    $address_str[] = $address['street_name'];
                }
                if (!empty( $address['city'] )) {
                    $address_str[] = $address['city'];
                }
                if (!empty($address['state'])) {
                    $address_str[] = $address['state'];
                }
				if (!empty($address['postal_code'])) {
                    $address_str[] = $address['postal_code'];
                }
                if (!empty($address['country'])) {
                    $address_str[] = $address['country'];
                }
                return implode(", ", $address_str);

                break;
        }
	}
}
