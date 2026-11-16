<?php

namespace App\Http\Controllers\Concerns;

use Illuminate\Http\Request;

trait HandlesHoneypot
{
    /**
     * Field ẩn "website" có giá trị => bot.
     */
    protected function isHoneypot(Request $request): bool
    {
        return filled($request->input('website'));
    }
}
