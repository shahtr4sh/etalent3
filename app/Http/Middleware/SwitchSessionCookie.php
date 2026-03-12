<?php
//
//namespace App\Http\Middleware;
//
//use Closure;
//use Illuminate\Http\Request;
//use Symfony\Component\HttpFoundation\Response;
//
//class SwitchSessionCookie
//{
//    public function handle(Request $request, Closure $next): Response
//    {
//        if ($request->is('admin') || $request->is('admin/*')) {
//            config(['session.cookie' => 'admin_session']);
//        } elseif ($request->is('staff') || $request->is('staff/*')) {
//            config(['session.cookie' => 'staff_session']);
//        } elseif ($request->is('app') || $request->is('app/*')) {
//            config(['session.cookie' => 'app_session']);
//        }
//
//        return $next($request);
//    }
//}
