<?php

use App\Jobs\SendSms;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CartController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AboutController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\SocialController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\SupportController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\MessagesController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\EventProgramController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\CustomerReviewController;
use App\Http\Controllers\ResolutionCenterController;
use App\Http\Controllers\Auth\ResetPasswordController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

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

Route::get('/', [HomeController::class, 'index'])->name('welcome');
Route::get('/login', [HomeController::class, 'index']);
Route::post('/login', [LoginController::class, 'sendLoginResponse'])->name('login');
Route::post('/register', [RegisterController::class, 'register'])->name('register');
Route::get('/register', [HomeController::class, 'index'])->name('register');
Route::get('/home', [HomeController::class, 'index'])->middleware(['auth', 'auth.session']);
Route::post('/search', [HomeController::class, 'searchServices'])->name('home.search');
Route::post('/search-event', [HomeController::class, 'searchEvents'])->name('home.event.search');
Route::get('/search-results/all', [HomeController::class, 'showSearchResults'])->name('search.show');
Route::post('/search-results', [HomeController::class, 'search'])->name('global.search');
Route::get('/auth/facebook', [SocialController::class, 'facebookRedirect'])->name('facebook.login');
Route::get('/auth/facebook/callback', [SocialController::class, 'facebookLogin']);
Route::get('/auth/google', [SocialController::class, 'redirectToGoogle'])->name('google.login');
Route::get('/auth/google/callback', [SocialController::class, 'handleGoogleCallback']);
Route::get('/forgot-password', [ResetPasswordController::class, 'enterEmail'])->name('password.request')->middleware('guest');
// Route::post('/forgot-password', [ResetPasswordController::class, 'confirmEmail'])->middleware('guest')->name('password.email');
Route::post('/forgot-password', [ResetPasswordController::class, 'confirmEmail'])->middleware('guest')->name('password.email.confirm');
Route::get('/reset-password/{token}', [ResetPasswordController::class, 'resetPassword'])->middleware('guest')->name('password.reset');
Route::post('/reset-password', [ResetPasswordController::class, 'passwordUpdate'])->middleware('guest')->name('password.update');
Route::post('/dispute', [HomeController::class, 'fileDispute'])->name('dispute');
Route::resource('/resolution-center', ResolutionCenterController::class);
Route::post('/resolution-center/response', [ResolutionCenterController::class, 'issueResponse'])->middleware(['auth'])->name('resolution-center.response');

Route::get('/set-credentials', [UserController::class, 'setCredentials'])->name('set-credentials')->middleware('auth');
Route::post('/submit-credentials', [UserController::class, 'submitCredentials'])->name('submit-credentials')->middleware('auth');

Route::get('/set/phonenumber', [UserController::class, 'setPhoneNumber'])->name('set.phone');
Route::post('/submit/phonenumber', [UserController::class, 'submitPhoneNumber'])->name('submit.phone')->middleware('auth');
Route::get('/verify/phonenumber', [UserController::class, 'verifyPhoneForm'])->name('verify.phone');
Route::post('/phone/verify', [UserController::class, 'verifyPhoneNumber'])->name('submit.verify.phone')->middleware('auth');

// Route::get('/event/user/{email}/{eventId}/{role}/register', [HomeController::class, 'eventUserRegister'])->name('register.event.user');
Route::get('/event/{eventId}/user/{email}', [HomeController::class, 'eventUserRegister'])->name('register.event.user');

Route::get('/event/ticket/{guest}', [HomeController::class, 'ticket'])->name('event.ticket.show');
Route::post('/ticket/{ticket}/attended/guests', [EventController::class, 'eventTicketUpdateGuestsAttended'])->middleware(['auth', 'auth.session'])->name('event.guests.attended');
Route::post('/ticket/{ticket}/payment', [EventController::class, 'ticketPayment'])->name('ticket.payment');
Route::get('/event/guest/invite/{guest}', [HomeController::class, 'guestTicket']);

Auth::routes(['verify' => true, 'login' => false, 'register' => false]);

Route::view('/email/verify', 'verify')->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
   $request->fulfill();
   return redirect('/');
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::get('/resend/code', function() {
   SendSms::dispatch(Auth::user()->phone_number, 'Your verification code is '.Auth::user()->phone_verification_code);
   return redirect()->back()->with('success', 'Verification code sent');
});

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [AboutController::class, 'index'])->name('about');
Route::get('/faq', [SupportController::class, 'index'])->name('faq');
Route::get('vendor/view/{id}', [HomeController::class, 'viewVendor'])->name('vendor.view');
Route::get('user/profile/edit', [UserController::class, 'editProfile'])->name('user.profile.edit');
Route::post('user/profile/update', [UserController::class, 'updateProfile'])->name('user.profile.update');

Route::post('/customer-review', [CustomerReviewController::class, 'review'])->name('customer-review');

Route::group(['prefix' => 'events', 'as' => 'events.'], function () {
   Route::get('/', [EventController::class, 'index'])->name('index')->middleware(['auth', 'auth.session']);
   Route::get('/create', [EventController::class, 'create'])->name('create');
   Route::post('/store', [EventController::class, 'store'])->name('store');
   Route::post('/{id}/update', [EventController::class, 'update'])->name('update');
   Route::get('/{id}/show', [EventController::class, 'show'])->name('show')->middleware(['auth', 'auth.session']);
   Route::get('/{id}/edit', [EventController::class, 'edit'])->name('edit')->middleware(['auth', 'auth.session']);
   Route::get('/{id}/delete', [EventController::class, 'destroy'])->name('destroy')->middleware(['auth', 'auth.session']);
});

Route::get('/pricing/{id}', [CartController::class, 'getPricingPrice']);

Route::get('/messages', [MessagesController::class, 'index'])->name('messages');
Route::get('/messages/{orderID}', [MessagesController::class, 'chats'])->name('message.chat');
Route::post('/message/send', [MessagesController::class, 'store'])->name('message.send');
Route::post('/messages/name', [MessagesController::class, 'getUserName'])->name('message.user.name');

Route::group([
    'prefix' => 'client/',
    'as' => 'client.'
],function () {
   Route::get('/favorites', [HomeController::class, 'favorites'])->name('favorites');
    Route::post('create/event', [EventController::class, 'createEvent'])->name('create.event');
    Route::get('create/program', [HomeController::class, 'createProgram'])->name('create.program');
    Route::post('submit/program',  [HomeController::class, 'submitProgram'])->name('submit.program');
    Route::get('services/{category?}', [ServiceController::class, 'clientServices'])->name('services.all');
    Route::post('services', [ServiceController::class, 'clientServicesFiltered'])->name('services.filtered');
    Route::get('service/{service}', [ServiceController::class, 'clientService'])->name('service.one');
    Route::get('add-to-cart/{service}/{pricing?}', [ServiceController::class, 'addToCart'])->name('add-to-cart');
    Route::delete('remove-from-cart', [ServiceController::class, 'removeFromCart'])->name('remove-from-cart');
    Route::get('user/cart', [ServiceController::class, 'viewCart'])->name('user.cart');
    Route::get('event/{event}/tasks', [EventController::class, 'eventTasks'])->name('event.tasks')->middleware(['auth', 'auth.session']);
    Route::get('event/{event}/orders', [EventController::class, 'eventOrders'])->name('event.orders')->middleware(['auth', 'auth.session']);
    Route::get('/order/{order}', [OrderController::class, 'order'])->name('orders.order')->middleware(['auth', 'auth.session']);
    Route::post('event/order/delete', [EventController::class, 'deleteEventOrder'])->name('event.order.delete')->middleware(['auth', 'auth.session']);
    Route::post('event/tasks/add', [EventController::class, 'addEventTask'])->name('event.tasks.add')->middleware(['auth', 'auth.session']);
    Route::post('event/tasks/edit', [EventController::class, 'editEventTask'])->name('event.tasks.edit')->middleware(['auth', 'auth.session']);
    Route::get('event/{event}/gifts', [EventController::class, 'eventGifts'])->name('event.gifts');
    Route::post('event/{event}/gifts/gift/add', [EventController::class, 'addEventGift'])->name('event.gift.add')->middleware(['auth', 'auth.session']);
    Route::post('event/{gift}/gifts/gift/update', [EventController::class, 'updateEventGift'])->name('event.gift.update')->middleware(['auth', 'auth.session']);
    Route::get('event/{event}/guest/list', [EventController::class, 'eventGuestList'])->name('event.guests')->middleware(['auth', 'auth.session']);
    Route::get('/event/{id}/guest/add/form', [EventController::class, 'addGuestForm'])->name('event.guest.add.form')->middleware(['auth', 'auth.session']);
    Route::get('/event/{event_id}/guest/{guest_id}/edit', [EventController::class, 'editGuestForm'])->name('event.guest.edit.form')->middleware(['auth', 'auth.session']);
    Route::post('event/{event}/guests/add', [EventController::class, 'addEventGuest'])->name('event.guest.add')->middleware(['auth', 'auth.session']);
    Route::post('event/{event}/guests/add/upload', [EventController::class, 'importGuestList'])->name('event.guest.add.upload')->middleware(['auth', 'auth.session']);
    Route::get('event/{event}/guest/list/download', [EventController::class, 'exportGuestList'])->name('event.guest.download')->middleware(['auth', 'auth.session']);
    Route::post('/event/guests/invite/send', [EventController::class, 'sendInviteToGuests'])->name('event.guest.invite.send')->middleware(['auth', 'auth.session']);
    Route::get('event/guest-list/sample/download', [EventController::class, 'downloadSampleGuestList'])->name('event.guest-list.sample');
    Route::post('event/{eventGuestDetail}/guests/edit', [EventController::class, 'editEventGuest'])->name('event.guest.edit')->middleware(['auth', 'auth.session']);
    Route::post('event/guests/attended', [EventController::class, 'markGuestsAsAttended'])->name('event.guests.attended')->middleware(['auth', 'auth.session']);
    Route::get('event/{event}/users/list', [EventController::class, 'eventUsersList'])->name('event.users')->middleware(['auth', 'auth.session']);
    Route::post('event/user/add', [EventController::class, 'addEventUser'])->name('event.user.add')->middleware(['auth', 'auth.session']);
    Route::post('event/guests/edit', [EventController::class, 'editEventUser'])->name('event.user.edit')->middleware(['auth', 'auth.session']);
    Route::get('cart', [CartController::class, 'cart'])->name('cart')->middleware(['auth', 'auth.session']);
    Route::post('checkout', [CartController::class, 'checkout'])->name('checkout')->middleware(['auth', 'auth.session']);
    Route::get('orders', [CartController::class, 'showOrders'])->name('orders')->middleware(['auth', 'auth.session']);
    Route::get('order/{orderDetails}/view', [EventController::class, 'viewEventOrder'])->name('event.order.view')->middleware(['auth', 'auth.session']);
    Route::get('order/{order}/pay', [OrderController::class, 'orderPay'])->name('order.pay.link')->middleware(['auth', 'auth.session']);
    Route::get('order/{order}/delivered', [OrderController::class, 'markOrderAsDelivered'])->name('order.delivered')->middleware(['auth', 'auth.session']);
    Route::get('order/{order}/cancel', [OrderController::class, 'cancelOrder'])->name('order.cancel')->middleware(['auth', 'auth.session']);
    Route::get('order/{order}/dispute/resolve', [OrderController::class, 'resolveDispute'])->name('order.dispute.resolve')->middleware(['auth', 'auth.session']);
    Route::get('order/{order}/delete', [OrderController::class, 'deleteOrder'])->name('order.delete')->middleware(['auth', 'auth.session']);
    Route::post('order/dispute', [OrderController::class, 'fileDispute'])->name('order.dispute')->middleware(['auth', 'auth.session']);
    Route::post('order/link/event', [OrderController::class, 'addEventToOrder'])->name('order.link.event')->middleware(['auth', 'auth.session']);
    Route::post('reviews/store', [ReviewController::class, 'storeReview'])->name('reviews.store')->middleware(['auth', 'auth.session']);
    Route::get('event/{event}/budget', [EventController::class, 'eventBudget'])->name('event.budget')->middleware(['auth', 'auth.session']);
    Route::post('event/budget/add', [EventController::class, 'eventAddBudget'])->name('event.budget.add')->middleware(['auth', 'auth.session']);
    Route::post('event/budget/initial/add', [EventController::class, 'addInitialBudget'])->name('event.budget.initial.add')->middleware(['auth', 'auth.session']);
    Route::post('event/budget/edit', [EventController::class, 'editEventBudget'])->name('event.budget.edit')->middleware(['auth', 'auth.session']);
    Route::post('event/budget/delete', [EventController::class, 'deleteEventBudget'])->name('event.budget.delete')->middleware(['auth']);
    Route::get('event/{event}/budget/{budget}/transactions', [EventController::class, 'eventBudgetTransactions'])->name('event.budget.transactions')->middleware(['auth', 'auth.session']);
    Route::get('event/{event}/budget/{budget}/transaction/add', [EventController::class, 'showAddBudgetTransaction'])->name('event.budget.transaction.form.show')->middleware(['auth', 'auth.session']);
    Route::post('event/budget/transactions/add', [EventController::class, 'addEventBudgetTransaction'])->name('event.budget.transaction.add')->middleware(['auth', 'auth.session']);
    Route::post('event/budget/transactions/edit', [EventController::class, 'editEventBudgetTransaction'])->name('event.budget.transaction.edit')->middleware(['auth', 'auth.session']);
    Route::post('event/budget/transactions/delete', [EventController::class, 'deleteEventBudgetTransaction'])->name('event.budget.transaction.delete')->middleware(['auth', 'auth.session']);
    Route::get('event/{event}/registration', [EventController::class, 'eventRegistration'])->name('event.registration')->middleware(['auth', 'auth.session']);
    Route::post('event/register', [EventController::class, 'eventRegisterGuest'])->name('event.guest.register')->middleware(['auth', 'auth.session']);
    Route::post('event/register/edit', [EventController::class, 'eventEditRegisterGuest'])->name('event.guest.registration.edit')->middleware(['auth', 'auth.session']);
    Route::get('event/{event}/tickets', [EventController::class, 'eventTickets'])->name('event.tickets')->middleware(['auth', 'auth.session']);
    Route::post('event/tickets/add', [EventController::class, 'addEventTicket'])->name('event.ticket.add')->middleware(['auth', 'auth.session']);
    Route::post('event/tickets/edit', [EventController::class, 'editEventTicket'])->name('event.ticket.edit')->middleware(['auth', 'auth.session']);
    Route::get('event/tickets/{id}/delete', [EventController::class, 'deleteEventTicket'])->name('event.ticket.delete')->middleware(['auth', 'auth.session']);
    Route::get('event/tickets/{id}/download', [EventController::class, 'downloadEventTicket'])->name('event.ticket.download')->middleware(['auth', 'auth.session']);
    Route::get('event/tickets/{id}/send', [EventController::class, 'sendEventTicket'])->name('event.ticket.send')->middleware(['auth', 'auth.session']);
    Route::get('event/tickets/{id}/map/send', [EventController::class, 'sendEventTicketWithMap'])->name('event.ticket.map.send')->middleware(['auth', 'auth.session']);
    Route::get('event/roles/all', [EventController::class, 'eventsRoles'])->name('events.roles')->middleware(['auth', 'auth.session']);
   //  Route::get('event/role/{eventId}/{userId}/{roleId}/delete', [EventController::class, 'eventsRoleDelete'])->name('events.role.delete')->middleware(['auth']);
    Route::post('event/role/delete', [EventController::class, 'eventsRoleDelete'])->name('events.role.delete')->middleware(['auth', 'auth.session']);
    Route::get('program/index', [EventProgramController::class, 'index'])->name('programs.index')->middleware(['auth', 'auth.session']);
    Route::get('program/show/{eventProgram}', [EventProgramController::class, 'show'])->name('program.show')->middleware(['auth', 'auth.session']);
    Route::post('program/store', [EventProgramController::class, 'store'])->name('program.store')->middleware(['auth', 'auth.session']);
    Route::get('program/create/{id?}', [EventProgramController::class, 'create'])->name('program.create')->middleware(['auth', 'auth.session']);
    Route::get('program/edit/{eventProgram}', [EventProgramController::class, 'edit'])->name('program.edit')->middleware(['auth', 'auth.session']);
    Route::post('program/update/{eventProgram}', [EventProgramController::class, 'update'])->name('program.update')->middleware(['auth', 'auth.session']);
    Route::post('program/delete', [EventProgramController::class, 'delete'])->name('program.destroy')->middleware(['auth', 'auth.session']);
    Route::get('program/download/{eventProgram}', [EventProgramController::class, 'pdf'])->name('program.pdf')->middleware(['auth', 'auth.session']);
    Route::post('program/status', [EventProgramController::class, 'getStatus'])->name('program.status')->middleware(['auth', 'auth.session']);
    Route::post('program/pay', [EventProgramController::class, 'pay'])->name('program.pay')->middleware(['auth', 'auth.session']);
    Route::post('program/share', [EventProgramController::class, 'share'])->name('program.share')->middleware(['auth', 'auth.session']);

    Route::get('/event/{id}/service/order', [EventController::class, 'eventServiceOrder'])->name('event.service.order')->middleware(['auth', 'auth.session']);

    Route::get('/event/task/{id}/reminder/send', [EventController::class, 'sendTaskReminder'])->name('task.reminder.send');
});

// Checkout and payments routes
Route::post('/order/checkout', [CheckoutController::class, 'orderCheckout'])->name('order.checkout')->middleware(['auth', 'auth.session']);
Route::post('/orders/checkout', [CheckoutController::class, 'ordersCheckout'])->name('orders.checkout')->middleware(['auth', 'auth.session']);
Route::post('/orders/pay', [CheckoutController::class, 'orderPayment'])->name('orders.pay')->middleware(['auth', 'auth.session']);

Route::get('/switch_profile', [UserController::class, 'switch_profile'])->name('switch_profile')->middleware(['auth', 'auth.session']);

Route::get('/vendors', [VendorController::class, 'get_vendors'])->name('vendors');
Route::group([
    'prefix' => 'vendor/',
    'as' => 'vendor.',
    'middleware' => ['auth', 'auth.session']
],function () {
   Route::get('complete', [VendorController::class, 'complete'])->name('complete');
   Route::get('skip', [VendorController::class, 'noCompany'])->name('skip.company');
   Route::get('profile', [VendorController::class, 'profile'])->name('profile');
   Route::post('profile/edit', [VendorController::class, 'submitProfile'])->name('profile.edit');
   Route::get('dashboard', [VendorController::class, 'dashboard'])->name('dashboard');
   Route::get('orders', [VendorController::class, 'orders'])->name('orders.all');
   Route::get('orders/archived', [OrderController::class, 'viewArchivedOrders'])->name('orders.archived');
   Route::get('services', [ServiceController::class, 'vendorServices'])->name('services.all');
   Route::get('services/service/{service}', [ServiceController::class, 'vendorService'])->name('services.one');
   Route::get('service/add', [ServiceController::class, 'addService'])->name('service.store');
   Route::get('services/pause', [ServiceController::class, 'pauseAllServices'])->name('services.pause');
   Route::post('service/pause', [ServiceController::class, 'pauseService'])->name('service.pause');
   Route::post('service/delete/permanent', [ServiceController::class, 'deleteServicePermanently'])->name('service.delete.permanent');
   Route::post('service/resume', [ServiceController::class, 'resumeService'])->name('service.resume');
   Route::get('service/resume/all', [ServiceController::class, 'resumeAllServices'])->name('services.resume.all');
   Route::post('services/service/add', [ServiceController::class, 'submitService'])->name('service.add');
   Route::get('services/service/edit/view/{service}', [ServiceController::class, 'editService'])->name('service.edit.view');
   Route::post('services/service/edit', [ServiceController::class, 'submitEditService'])->name('service.edit');
   Route::post('services/service/pricing/add', [ServiceController::class, 'submitServicePricing'])->name('service.pricing.add');
   Route::post('services/service/images/add', [ServiceController::class, 'submitServiceImages'])->name('service.images.add');
   Route::post('service/image/delete', [ServiceController::class,'deleteServiceImage'])->name('service.image.delete');
   Route::post('services/service/pricing/update', [ServiceController::class, 'submitServicePricingUpdate'])->name('service.pricing.update');
   Route::get('orders/order/{id}', [VendorController::class, 'order'])->name('orders.one');
   Route::post('complete', [VendorController::class, 'createCompany'])->name('create');
   Route::get('delete/{id}', [VendorController::class, 'deleteVendor'])->name('delete');
   Route::post('order/quote/custom', [VendorController::class, 'addCustomQuote'])->name('order.custom.quote');
   Route::post('order/cancel', [VendorController::class, 'cancelOrder'])->name('order.cancel');
   Route::get('order/{order}/delete', [VendorController::class, 'deleteOrder'])->name('order.delete');
   Route::get('/reviews', [VendorController::class, 'reviews'])->name('reviews');
});

Route::get('/guest/{id}/ticket', [EventController::class, 'sendEventTicketWithMap']);
