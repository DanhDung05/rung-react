<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Inertia\Inertia;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    /**
     * Trả trang lỗi bằng Inertia cho khu vực public (giữ nguyên Admin/API).
     */
    public function render($request, Throwable $e)
    {
        $response = parent::render($request, $e);

        if ($request->expectsJson() || $request->header('X-Inertia')) {
            return $response;
        }

        // Không đổi hành vi khu vực quản trị và API.
        if ($request->is('admin', 'admin/*', 'api', 'api/*')) {
            return $response;
        }

        if (! $response instanceof SymfonyResponse) {
            return $response;
        }

        $status = $response->getStatusCode();

        $pages = [
            403 => 'Errors/Forbidden',
            404 => 'Errors/NotFound',
            419 => 'Errors/Expired',
        ];

        if (isset($pages[$status])) {
            return $this->inertiaError($request, $pages[$status], $status, $response);
        }

        if ($status === 500 && ! config('app.debug')) {
            return $this->inertiaError($request, 'Errors/ServerError', 500, $response);
        }

        return $response;
    }

    /**
     * Render trang lỗi Inertia, fallback về response gốc nếu có sự cố.
     */
    protected function inertiaError($request, string $component, int $status, SymfonyResponse $fallback)
    {
        try {
            return Inertia::render($component, ['status' => $status])
                ->toResponse($request)
                ->setStatusCode($status);
        } catch (Throwable $renderError) {
            report($renderError);

            return $fallback;
        }
    }
}
