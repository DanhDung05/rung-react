<?php

use App\Http\Controllers\Admin\CategoryController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DestinationController;
use App\Http\Controllers\Admin\TourController;
use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\Admin\TagController;
use App\Http\Controllers\Admin\SliderController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\FaqController;
use App\Http\Controllers\Admin\MenuController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\ContactController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ReviewController;
use App\Http\Controllers\Admin\MediaController;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->name('admin.')->middleware('admin')->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    // ============================================================
    // CATEGORY ROUTES
    // ============================================================

    Route::get('danh-muc/thung-rac', [CategoryController::class, 'trash'])
        ->name('categories.trash');

    Route::patch('danh-muc/{id}/khoi-phuc', [CategoryController::class, 'restore'])
        ->name('categories.restore');

    Route::delete('danh-muc/{id}/xoa-cung', [CategoryController::class, 'forceDestroy'])
        ->name('categories.force-destroy');

    Route::resource('danh-muc', CategoryController::class)
        ->parameters(['danh-muc' => 'category'])
        ->names([
            'index'   => 'categories.index',
            'create'  => 'categories.create',
            'store'   => 'categories.store',
            'show'    => 'categories.show',
            'edit'    => 'categories.edit',
            'update'  => 'categories.update',
            'destroy' => 'categories.destroy',
        ]);

    // ============================================================
    // DESTINATION ROUTES
    // ============================================================

    Route::get('diem-den/thung-rac', [DestinationController::class, 'trash'])
        ->name('destinations.trash');

    Route::patch('diem-den/{id}/khoi-phuc', [DestinationController::class, 'restore'])
        ->name('destinations.restore');

    Route::delete('diem-den/{id}/xoa-cung', [DestinationController::class, 'forceDestroy'])
        ->name('destinations.force-destroy');

    Route::resource('diem-den', DestinationController::class)
        ->parameters(['diem-den' => 'destination'])
        ->names([
            'index'   => 'destinations.index',
            'create'  => 'destinations.create',
            'store'   => 'destinations.store',
            'show'    => 'destinations.show',
            'edit'    => 'destinations.edit',
            'update'  => 'destinations.update',
            'destroy' => 'destinations.destroy',
        ]);

    // ============================================================
    // TOUR ROUTES
    // ============================================================

    Route::get('tour/thung-rac', [TourController::class, 'trash'])
        ->name('tours.trash');

    Route::patch('tour/{id}/khoi-phuc', [TourController::class, 'restore'])
        ->name('tours.restore');

    Route::delete('tour/{id}/xoa-cung', [TourController::class, 'forceDestroy'])
        ->name('tours.force-destroy');

    Route::resource('tour', TourController::class)
        ->parameters(['tour' => 'tour'])
        ->names([
            'index'   => 'tours.index',
            'create'  => 'tours.create',
            'store'   => 'tours.store',
            'show'    => 'tours.show',
            'edit'    => 'tours.edit',
            'update'  => 'tours.update',
            'destroy' => 'tours.destroy',
        ]);

    // ============================================================
    // POST ROUTES
    // ============================================================

    Route::get('bai-viet/thung-rac', [PostController::class, 'trash'])
        ->name('posts.trash');

    Route::patch('bai-viet/{id}/khoi-phuc', [PostController::class, 'restore'])
        ->name('posts.restore');

    Route::delete('bai-viet/{id}/xoa-cung', [PostController::class, 'forceDestroy'])
        ->name('posts.force-destroy');

    Route::resource('bai-viet', PostController::class)
        ->parameters(['bai-viet' => 'post'])
        ->names([
            'index'   => 'posts.index',
            'create'  => 'posts.create',
            'store'   => 'posts.store',
            'show'    => 'posts.show',
            'edit'    => 'posts.edit',
            'update'  => 'posts.update',
            'destroy' => 'posts.destroy',
        ]);

    // ============================================================
    // TAG ROUTES
    // ============================================================

    Route::get('tag/thung-rac', [TagController::class, 'trash'])
        ->name('tags.trash');

    Route::patch('tag/{id}/khoi-phuc', [TagController::class, 'restore'])
        ->name('tags.restore');

    Route::delete('tag/{id}/xoa-cung', [TagController::class, 'forceDestroy'])
        ->name('tags.force-destroy');

    Route::resource('tag', TagController::class)
        ->parameters(['tag' => 'tag'])
        ->names([
            'index'   => 'tags.index',
            'create'  => 'tags.create',
            'store'   => 'tags.store',
            'show'    => 'tags.show',
            'edit'    => 'tags.edit',
            'update'  => 'tags.update',
            'destroy' => 'tags.destroy',
        ]);

    // ============================================================
    // SLIDER ROUTES
    // ============================================================

    Route::get('slider/thung-rac', [SliderController::class, 'trash'])
        ->name('sliders.trash');

    Route::patch('slider/{id}/khoi-phuc', [SliderController::class, 'restore'])
        ->name('sliders.restore');

    Route::delete('slider/{id}/xoa-cung', [SliderController::class, 'forceDestroy'])
        ->name('sliders.force-destroy');

    Route::resource('slider', SliderController::class)
        ->parameters(['slider' => 'slider'])
        ->names([
            'index'   => 'sliders.index',
            'create'  => 'sliders.create',
            'store'   => 'sliders.store',
            'show'    => 'sliders.show',
            'edit'    => 'sliders.edit',
            'update'  => 'sliders.update',
            'destroy' => 'sliders.destroy',
        ]);

    // ============================================================
    // PAGE ROUTES
    // ============================================================

    Route::get('trang-tinh/thung-rac', [PageController::class, 'trash'])
        ->name('pages.trash');

    Route::patch('trang-tinh/{id}/khoi-phuc', [PageController::class, 'restore'])
        ->name('pages.restore');

    Route::delete('trang-tinh/{id}/xoa-cung', [PageController::class, 'forceDestroy'])
        ->name('pages.force-destroy');

    Route::resource('trang-tinh', PageController::class)
        ->parameters(['trang-tinh' => 'page'])
        ->names([
            'index'   => 'pages.index',
            'create'  => 'pages.create',
            'store'   => 'pages.store',
            'show'    => 'pages.show',
            'edit'    => 'pages.edit',
            'update'  => 'pages.update',
            'destroy' => 'pages.destroy',
        ]);

    // ============================================================
    // FAQ ROUTES
    // ============================================================

    Route::get('faq/thung-rac', [FaqController::class, 'trash'])
        ->name('faqs.trash');

    Route::patch('faq/{id}/khoi-phuc', [FaqController::class, 'restore'])
        ->name('faqs.restore');

    Route::delete('faq/{id}/xoa-cung', [FaqController::class, 'forceDestroy'])
        ->name('faqs.force-destroy');

    Route::resource('faq', FaqController::class)
        ->parameters(['faq' => 'faq'])
        ->names([
            'index'   => 'faqs.index',
            'create'  => 'faqs.create',
            'store'   => 'faqs.store',
            'show'    => 'faqs.show',
            'edit'    => 'faqs.edit',
            'update'  => 'faqs.update',
            'destroy' => 'faqs.destroy',
        ]);

    // ============================================================
    // MENU ROUTES
    // ============================================================

    Route::get('menu/thung-rac', [MenuController::class, 'trash'])
        ->name('menus.trash');

    Route::patch('menu/{id}/khoi-phuc', [MenuController::class, 'restore'])
        ->name('menus.restore');

    Route::delete('menu/{id}/xoa-cung', [MenuController::class, 'forceDestroy'])
        ->name('menus.force-destroy');

    Route::get('menu/{menu}/items', [MenuController::class, 'items'])
        ->name('menus.items');

    Route::put('menu/{menu}/items', [MenuController::class, 'saveItems'])
        ->name('menus.items.save');

    Route::resource('menu', MenuController::class)
        ->parameters(['menu' => 'menu'])
        ->names([
            'index'   => 'menus.index',
            'create'  => 'menus.create',
            'store'   => 'menus.store',
            'show'    => 'menus.show',
            'edit'    => 'menus.edit',
            'update'  => 'menus.update',
            'destroy' => 'menus.destroy',
        ]);

    // ============================================================
    // USER ROUTES
    // ============================================================

    Route::resource('tai-khoan', UserController::class)
        ->parameters(['tai-khoan' => 'user'])
        ->except(['destroy'])
        ->names([
            'index'   => 'users.index',
            'create'  => 'users.create',
            'store'   => 'users.store',
            'show'    => 'users.show',
            'edit'    => 'users.edit',
            'update'  => 'users.update',
        ]);

    Route::patch('tai-khoan/{user}/toggle-active', [UserController::class, 'toggleActive'])
        ->name('users.toggle-active');

    // ============================================================
    // SETTING ROUTES
    // ============================================================

    Route::get('cau-hinh', [SettingController::class, 'index'])
        ->name('settings.index');

    Route::get('cau-hinh/{group}', [SettingController::class, 'edit'])
        ->name('settings.edit');

    Route::put('cau-hinh/{group}', [SettingController::class, 'update'])
        ->name('settings.update');

    // ============================================================
    // CONTACT ROUTES
    // ============================================================

    Route::get('lien-he', [ContactController::class, 'index'])
        ->name('contacts.index');

    Route::get('lien-he/{contact}', [ContactController::class, 'show'])
        ->name('contacts.show');

    Route::patch('lien-he/{contact}/status', [ContactController::class, 'updateStatus'])
        ->name('contacts.update-status');

     // ============================================================
    // REVIEW ROUTES
    // ============================================================

    Route::get('danh-gia/thung-rac', [ReviewController::class, 'trash'])
        ->name('reviews.trash');

    Route::patch('danh-gia/{id}/khoi-phuc', [ReviewController::class, 'restore'])
        ->name('reviews.restore');

    Route::delete('danh-gia/{id}/xoa-cung', [ReviewController::class, 'forceDestroy'])
        ->name('reviews.force-destroy');

    Route::get('danh-gia', [ReviewController::class, 'index'])
        ->name('reviews.index');

    Route::patch('danh-gia/{review}/duyet', [ReviewController::class, 'approve'])
        ->name('reviews.approve');

    Route::patch('danh-gia/{review}/tu-choi', [ReviewController::class, 'reject'])
        ->name('reviews.reject');

    Route::delete('danh-gia/{review}', [ReviewController::class, 'destroy'])
        ->name('reviews.destroy');

    
        Route::get('/dashboard/stats', [DashboardController::class, 'stats'])
        ->name('dashboard.stats');

    // ============================================================
    // UPLOAD ẢNH CHO TINYMCE (tạm thời — closure)
    // ============================================================
    Route::post('upload/image', function (\Illuminate\Http\Request $request) {
        $request->validate([
            'file' => ['required', 'image', 'mimes:jpg,jpeg,png,webp,gif', 'max:10240'],
        ], [
            'file.required' => 'Chưa chọn file.',
            'file.image'    => 'File phải là ảnh.',
            'file.mimes'    => 'Ảnh phải có định dạng JPG, JPEG, PNG, WEBP hoặc GIF.',
            'file.max'      => 'Ảnh không vượt quá 10MB.',
        ]);

        $path = app(\App\Services\ImageService::class)
            ->upload($request->file('file'), 'content', 0);

        return response()->json([
            'location' => \Illuminate\Support\Facades\Storage::disk('public')->url($path),
        ]);
    })->name('upload.image');

        // ============================================================
    // MEDIA ROUTES
    // ============================================================
    Route::get('media', [MediaController::class, 'index'])
        ->name('media.index');

    Route::get('media/thung-rac', [MediaController::class, 'trash'])
        ->name('media.trash');

    Route::delete('media/{media}', [MediaController::class, 'destroy'])
        ->name('media.destroy');

    Route::patch('media/{id}/khoi-phuc', [MediaController::class, 'restore'])
        ->name('media.restore');

    Route::delete('media/{id}/xoa-cung', [MediaController::class, 'forceDestroy'])
        ->name('media.force-destroy');

});