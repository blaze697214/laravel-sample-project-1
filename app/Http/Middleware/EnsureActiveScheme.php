<?php

namespace App\Http\Middleware;

use App\Models\CurriculumYears;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureActiveScheme
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->scheme_id) {
            abort(400, 'Scheme context missing.');
        }

        $activeScheme = CurriculumYears::where('is_active', 1)->value('id');

        if ($request->scheme_id && $request->scheme_id != $activeScheme) {

            return redirect()->back()->withErrors([
                'scheme' => 'The active scheme has changed. Please refresh the page.',
            ]);

        }

        return $next($request);
    }
}
