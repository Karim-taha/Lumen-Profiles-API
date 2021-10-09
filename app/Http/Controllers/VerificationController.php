<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\VerifiesEmails;
use Illuminate\Auth\Access\AuthorizationException;
use phpDocumentor\Reflection\Types\Void_;

class VerificationController extends Controller
{
    // use VerifiesEmails;

    /**
    * Where to redirect users after verification.
    *
    * @var string
    */

    protected $redirectTo = '/';

    /**
     * Create a new controller instance
     *
     * @return void
     */

    public function __construct()
    {
        // $this->middleware('auth');
        // $this->middleware('signed')->only('verify');
        // $this->middleware('throttle:6.1')->only('verify', 'resend');

        // $this->middleware(['auth','verified']);

    }

    /**
     * Resend the email verification notification.
     *
     * @param \Illuminate\Http\Request
     * @param \Illuminate\Http\Response
     */

    public function resend(Request $request)
    {
        if($request->user()->hasVerifiedEmail())
        {
            return response(['message' => 'Already verified']);
        }
        $request->user()->sendEmailVerificationNotification();
        return ['status' => 'verification-link-sent'];

        // if($request->wantsJson())
        // {
        //     return response(['message' => 'Email Sent']);
        // }
        // return back()->with('resend', true);


    }

    /**
     * Mark the authenticated user's email address as verified.
     *
     * @param \Illuminate\Http\Request
     * @param \Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */

     public function verify(Request $request)
     {
         auth()->loginUsingId($request->route('id'));

         if($request->route('id') != $request->user()->getkey())
         {
            throw new AuthorizationException;
         }

         if($request->user()->hasVerifiedEmail())
         {
            return response(['message' => 'Already verified']);
         }

         if($request->user()->markEmailAsVerified())
         {
            event(new Verified($request->user()));
         }
         return response(['message' => 'Successfully verified']);
     }


}
