<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * App\Models\Admin
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string $role
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @method static \Illuminate\Database\Eloquent\Builder|Admin newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Admin newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Admin query()
 * @method static \Illuminate\Database\Eloquent\Builder|Admin whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Admin whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Admin whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Admin whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Admin wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Admin whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Admin whereUpdatedAt($value)
 */
	class Admin extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Attendance
 *
 * @property int $id
 * @property int $customer_id
 * @property int|null $order_id
 * @property string|null $program
 * @property string|null $location
 * @property string $check_in_type
 * @property int|null $schedule_id
 * @property \Illuminate\Support\Carbon|null $check_in_at
 * @property \Illuminate\Support\Carbon|null $check_in_time
 * @property \Illuminate\Support\Carbon|null $check_out_at
 * @property \Illuminate\Support\Carbon|null $auto_checkout_at Waktu auto-checkout (check_in + 60 menit)
 * @property int|null $duration_minutes Durasi latihan dalam menit, dihitung saat check-out
 * @property string|null $checkout_type Tipe checkout: manual (oleh staff), auto (sistem auto 60 menit), system (force by admin)
 * @property string $attendance_status
 * @property int $quota_deducted
 * @property string|null $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Customer $customer
 * @property-read int|null $elapsed_minutes
 * @property-read bool $is_active
 * @property-read bool $is_auto_checkout_due
 * @property-read \App\Models\Order|null $order
 * @property-read \App\Models\Schedule|null $schedule
 * @method static \Illuminate\Database\Eloquent\Builder|Attendance active()
 * @method static \Illuminate\Database\Eloquent\Builder|Attendance checkedOut()
 * @method static \Illuminate\Database\Eloquent\Builder|Attendance forCustomer($customerId)
 * @method static \Illuminate\Database\Eloquent\Builder|Attendance newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Attendance newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Attendance qRCheckin()
 * @method static \Illuminate\Database\Eloquent\Builder|Attendance query()
 * @method static \Illuminate\Database\Eloquent\Builder|Attendance today()
 * @method static \Illuminate\Database\Eloquent\Builder|Attendance whereAttendanceStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Attendance whereAutoCheckoutAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Attendance whereCheckInAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Attendance whereCheckInTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Attendance whereCheckInType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Attendance whereCheckOutAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Attendance whereCheckoutType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Attendance whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Attendance whereCustomerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Attendance whereDurationMinutes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Attendance whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Attendance whereLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Attendance whereOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Attendance whereProgram($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Attendance whereQuotaDeducted($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Attendance whereScheduleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Attendance whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Attendance whereUpdatedAt($value)
 */
	class Attendance extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Booking
 *
 * @property int $id
 * @property int $customer_id
 * @property string|null $program
 * @property string $schedule_date
 * @property string $schedule_time
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Customer $customer
 * @method static \Illuminate\Database\Eloquent\Builder|Booking newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Booking newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Booking query()
 * @method static \Illuminate\Database\Eloquent\Builder|Booking whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Booking whereCustomerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Booking whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Booking whereProgram($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Booking whereScheduleDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Booking whereScheduleTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Booking whereUpdatedAt($value)
 */
	class Booking extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\CheckIn
 *
 * @property-read \App\Models\Customer|null $customer
 * @method static \Illuminate\Database\Eloquent\Builder|CheckIn newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CheckIn newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CheckIn query()
 */
	class CheckIn extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\ClassGroup
 *
 * @property int $id
 * @property string $name
 * @property string|null $level
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ClassModel> $classes
 * @property-read int|null $classes_count
 * @method static \Illuminate\Database\Eloquent\Builder|ClassGroup newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ClassGroup newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ClassGroup query()
 * @method static \Illuminate\Database\Eloquent\Builder|ClassGroup whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ClassGroup whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ClassGroup whereLevel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ClassGroup whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ClassGroup whereUpdatedAt($value)
 */
	class ClassGroup extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\ClassModel
 *
 * @property int $id
 * @property string $class_name
 * @property string|null $group_name
 * @property string|null $type
 * @property string|null $level
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $class_group_id
 * @property-read mixed $full_name
 * @property-read \App\Models\ClassGroup|null $group
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Schedule> $schedules
 * @property-read int|null $schedules_count
 * @method static \Illuminate\Database\Eloquent\Builder|ClassModel newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ClassModel newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ClassModel query()
 * @method static \Illuminate\Database\Eloquent\Builder|ClassModel whereClassGroupId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ClassModel whereClassName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ClassModel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ClassModel whereGroupName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ClassModel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ClassModel whereLevel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ClassModel whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ClassModel whereUpdatedAt($value)
 */
	class ClassModel extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Customer
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string|null $qr_token
 * @property \Illuminate\Support\Carbon|null $qr_generated_at
 * @property bool $qr_active
 * @property string|null $phone_number
 * @property \Illuminate\Support\Carbon|null $birth_date
 * @property string|null $goals
 * @property string|null $kondisi_khusus
 * @property string|null $referensi
 * @property string|null $pengalaman
 * @property string $is_muslim
 * @property string|null $voucher_code
 * @property int|null $program_id
 * @property int|null $package_id
 * @property int|null $class_id
 * @property string|null $program
 * @property int $quota
 * @property string|null $password
 * @property int $is_login_active
 * @property bool $is_verified
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Attendance> $attendances
 * @property-read int|null $attendances_count
 * @property-read int|null $age
 * @property-read bool $has_package
 * @property-read bool $has_quota
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Order> $orders
 * @property-read int|null $orders_count
 * @property-read \App\Models\Package|null $package
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Schedule> $schedules
 * @property-read int|null $schedules_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Transaction> $transactions
 * @property-read int|null $transactions_count
 * @method static \Illuminate\Database\Eloquent\Builder|Customer hasPackage()
 * @method static \Illuminate\Database\Eloquent\Builder|Customer hasQuota()
 * @method static \Illuminate\Database\Eloquent\Builder|Customer membership(string $membership)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Customer newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Customer noPackage()
 * @method static \Illuminate\Database\Eloquent\Builder|Customer query()
 * @method static \Illuminate\Database\Eloquent\Builder|Customer quotaEmpty()
 * @method static \Illuminate\Database\Eloquent\Builder|Customer unverified()
 * @method static \Illuminate\Database\Eloquent\Builder|Customer verified()
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereBirthDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereClassId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereGoals($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereIsLoginActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereIsMuslim($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereIsVerified($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereKondisiKhusus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer wherePackageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer wherePengalaman($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer wherePhoneNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereProgram($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereProgramId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereQrActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereQrGeneratedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereQrToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereQuota($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereReferensi($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Customer whereVoucherCode($value)
 */
	class Customer extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\CustomerSchedule
 *
 * @property int $id
 * @property int $customer_id
 * @property int $schedule_id
 * @property int|null $order_id
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $joined_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Customer|null $customer
 * @property-read \App\Models\Order|null $order
 * @property-read \App\Models\Schedule|null $schedule
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerSchedule confirmed()
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerSchedule forCustomer($customerId)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerSchedule newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerSchedule newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerSchedule query()
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerSchedule whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerSchedule whereCustomerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerSchedule whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerSchedule whereJoinedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerSchedule whereOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerSchedule whereScheduleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerSchedule whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CustomerSchedule whereUpdatedAt($value)
 */
	class CustomerSchedule extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Feedback
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $subject
 * @property string $message
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Feedback newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Feedback newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Feedback query()
 * @method static \Illuminate\Database\Eloquent\Builder|Feedback whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Feedback whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Feedback whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Feedback whereMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Feedback whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Feedback whereSubject($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Feedback whereUpdatedAt($value)
 */
	class Feedback extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\GymSession
 *
 * @method static \Illuminate\Database\Eloquent\Builder|GymSession newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|GymSession newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|GymSession query()
 */
	class GymSession extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Membership
 *
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Customer> $customers
 * @property-read int|null $customers_count
 * @method static \Illuminate\Database\Eloquent\Builder|Membership newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Membership newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Membership query()
 */
	class Membership extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\MembershipCustomer
 *
 * @property-read \App\Models\Customer|null $customer
 * @property-read \App\Models\Membership|null $membership
 * @method static \Illuminate\Database\Eloquent\Builder|MembershipCustomer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MembershipCustomer newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|MembershipCustomer query()
 */
	class MembershipCustomer extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Order
 *
 * @property int $id
 * @property int $customer_id
 * @property string|null $customer_name
 * @property int $package_id
 * @property string|null $selected_class_id
 * @property string $order_code
 * @property int $amount
 * @property int $discount
 * @property bool $quota_applied
 * @property string|null $payment_type
 * @property string|null $transaction_id
 * @property string|null $voucher_code
 * @property array|null $schedule_ids
 * @property string $status
 * @property \Carbon\Carbon|null $expired_at
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property int|null $remaining_quota
 * @property string|null $quota_applied_at
 * @property string|null $schedule_applied_at
 * @property int $whatsapp_notification_sent
 * @property string|null $whatsapp_notification_sent_at
 * @property int|null $remaining_classes Sisa kelas yang bisa di-book (tidak berkurang saat check-in)
 * @property-read \App\Models\Customer $customer
 * @property-read \App\Models\Package $package
 * @property-read \App\Models\Transaction|null $transaction
 * @method static \Illuminate\Database\Eloquent\Builder|Order active()
 * @method static \Illuminate\Database\Eloquent\Builder|Order byCustomer($customerId)
 * @method static \Illuminate\Database\Eloquent\Builder|Order expired()
 * @method static \Illuminate\Database\Eloquent\Builder|Order newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Order newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Order query()
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereCustomerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereCustomerName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereDiscount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereExpiredAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereOrderCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order wherePackageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order wherePaymentType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereQuotaApplied($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereQuotaAppliedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereRemainingClasses($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereRemainingQuota($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereScheduleAppliedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereScheduleIds($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereSelectedClassId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereTransactionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereVoucherCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereWhatsappNotificationSent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Order whereWhatsappNotificationSentAt($value)
 */
	class Order extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\OrderItem
 *
 * @property int $id
 * @property int $order_id
 * @property int $package_id
 * @property int $qty
 * @property int $price
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Order $order
 * @property-read \App\Models\Package $package
 * @method static \Illuminate\Database\Eloquent\Builder|OrderItem newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OrderItem newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|OrderItem query()
 * @method static \Illuminate\Database\Eloquent\Builder|OrderItem whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderItem whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderItem whereOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderItem wherePackageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderItem wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderItem whereQty($value)
 * @method static \Illuminate\Database\Eloquent\Builder|OrderItem whereUpdatedAt($value)
 */
	class OrderItem extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Package
 *
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property float $price
 * @property int $quota
 * @property int|null $class_id
 * @property bool $is_exclusive
 * @property bool $requires_schedule
 * @property int|null $duration_days
 * @property string|null $description
 * @property string|null $duration
 * @property string|null $schedule_mode
 * @property int|null $default_schedule_id
 * @property bool $auto_apply
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $schedule_label
 * @property string|null $type
 * @property int $is_active
 * @property int|null $requires_booking
 * @property string|null $booking_type
 * @property-read \App\Models\ClassModel|null $class
 * @property-read \App\Models\ClassModel|null $classModel
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\ClassModel> $classes
 * @property-read int|null $classes_count
 * @property-read \App\Models\Schedule|null $defaultSchedule
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Order> $orders
 * @property-read int|null $orders_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Schedule> $schedules
 * @property-read int|null $schedules_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Voucher> $vouchers
 * @property-read int|null $vouchers_count
 * @method static \Illuminate\Database\Eloquent\Builder|Package newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Package newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Package query()
 * @method static \Illuminate\Database\Eloquent\Builder|Package whereAutoApply($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Package whereBookingType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Package whereClassId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Package whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Package whereDefaultScheduleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Package whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Package whereDurationDays($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Package whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Package whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Package whereIsExclusive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Package whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Package wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Package whereQuota($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Package whereRequiresBooking($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Package whereRequiresSchedule($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Package whereScheduleLabel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Package whereScheduleMode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Package whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Package whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Package whereUpdatedAt($value)
 */
	class Package extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\PackageSchedule
 *
 * @property int $id
 * @property int $package_id
 * @property int $schedule_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Package $package
 * @property-read \App\Models\Schedule $schedule
 * @method static \Illuminate\Database\Eloquent\Builder|PackageSchedule newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PackageSchedule newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PackageSchedule query()
 * @method static \Illuminate\Database\Eloquent\Builder|PackageSchedule whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PackageSchedule whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PackageSchedule wherePackageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PackageSchedule whereScheduleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PackageSchedule whereUpdatedAt($value)
 */
	class PackageSchedule extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Payment
 *
 * @property-read \App\Models\Customer|null $customer
 * @method static \Illuminate\Database\Eloquent\Builder|Payment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Payment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Payment query()
 */
	class Payment extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Program
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Program newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Program newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Program query()
 */
	class Program extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Schedule
 *
 * @property int $id
 * @property int|null $package_id (deprecated - use packages pivot)
 * @property int|null $class_id
 * @property string|null $schedule_label (required - unique label for schedule)
 * @property string|null $day
 * @property date|null $schedule_date
 * @property string|null $class_time
 * @property string|null $instructor
 * @property bool|null $show_on_landing
 * @property-read \Illuminate\Database\Eloquent\Collection $packages (via pivot)
 * @property-read \Illuminate\Database\Eloquent\Collection $customers (via pivot)
 * @property-read ClassModel $classModel
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read int|null $customers_count
 * @property-read mixed $class_name
 * @property-read mixed $class_name_display
 * @property-read mixed $package_names
 * @property-read mixed $package_summary
 * @property-read mixed $schedule_date_formatted
 * @property-read \App\Models\Package|null $package
 * @property-read int|null $packages_count
 * @method static \Illuminate\Database\Eloquent\Builder|Schedule byClass($classId)
 * @method static \Illuminate\Database\Eloquent\Builder|Schedule byDay($day)
 * @method static \Illuminate\Database\Eloquent\Builder|Schedule newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Schedule newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Schedule query()
 * @method static \Illuminate\Database\Eloquent\Builder|Schedule visible()
 * @method static \Illuminate\Database\Eloquent\Builder|Schedule whereClassId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Schedule whereClassTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Schedule whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Schedule whereDay($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Schedule whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Schedule whereInstructor($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Schedule wherePackageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Schedule whereScheduleDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Schedule whereScheduleLabel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Schedule whereShowOnLanding($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Schedule whereUpdatedAt($value)
 */
	class Schedule extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Transaction
 *
 * @property int $id
 * @property int $order_id
 * @property int $customer_id
 * @property string|null $customer_name
 * @property int $package_id
 * @property int $amount
 * @property string|null $description
 * @property string $status
 * @property string|null $payment_type
 * @property string|null $transaction_id
 * @property string|null $midtrans_transaction_id
 * @property string|null $fraud_status
 * @property string|null $signature_key
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\Models\Customer $customer
 * @property-read \App\Models\Order|null $order
 * @property-read \App\Models\Package|null $package
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction query()
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereCustomerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereCustomerName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereFraudStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereMidtransTransactionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction wherePackageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction wherePaymentType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereSignatureKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereTransactionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereUpdatedAt($value)
 */
	class Transaction extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\User
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $role
 * @property string|null $phone
 * @property int $is_verified
 * @property string $credit_balance
 * @property string|null $email_verified_at
 * @property string $password
 * @property int $is_admin
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $is_approved
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreditBalance($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereIsAdmin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereIsApproved($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereIsVerified($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 */
	class User extends \Eloquent {}
}

namespace App\Models{
/**
 * App\Models\Voucher
 *
 * @property int $id
 * @property string $code
 * @property string $type (percent|nominal)
 * @property float $value
 * @property float|null $max_discount
 * @property int|null $usage_limit
 * @property int $used_count
 * @property \Carbon\Carbon|null $valid_from
 * @property \Carbon\Carbon|null $valid_until
 * @property bool $active
 * @property string $applicable_to (all|specific)
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Order> $orders
 * @property-read int|null $orders_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Package> $packages
 * @property-read int|null $packages_count
 * @method static \Illuminate\Database\Eloquent\Builder|Voucher active()
 * @method static \Illuminate\Database\Eloquent\Builder|Voucher byCode(string $code)
 * @method static \Illuminate\Database\Eloquent\Builder|Voucher currentlyValid()
 * @method static \Illuminate\Database\Eloquent\Builder|Voucher newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Voucher newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Voucher notExhausted()
 * @method static \Illuminate\Database\Eloquent\Builder|Voucher query()
 * @method static \Illuminate\Database\Eloquent\Builder|Voucher whereActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Voucher whereApplicableTo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Voucher whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Voucher whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Voucher whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Voucher whereMaxDiscount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Voucher whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Voucher whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Voucher whereUsageLimit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Voucher whereUsedCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Voucher whereValidFrom($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Voucher whereValidUntil($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Voucher whereValue($value)
 */
	class Voucher extends \Eloquent {}
}

