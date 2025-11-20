<?php

namespace Modules\Admin\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
Use Modules\Core\Http\Controllers\BackendController;
use Modules\Events\Event\EventDispatcher;

class LoginController extends BackendController
{
    /**
     * Show login form (index)
     */
    public function index(Request $request)
    {
        if (\Auth::guard('admin')->check()) {
            return redirect()->route('admin.admin.listing');
        }
        $layout = $this->layout();
        $layout->template('admin::components.admin.layout.blank');
        $content = $layout->child('content');

        $loginForm = new \Modules\Admin\View\Components\Admin\Login\Form();
        $content->child('login_form', $loginForm);

        return $layout->render();
    }

    /**
     * Handle login POST
     */
    public function post(Request $request,EventDispatcher $eventDispatcher)
    {
        $email = $request->input('email');
        $password = $request->input('password');
        
        if (Auth::guard('admin')->attempt(['email' => $email, 'password' => $password])) {
            $request->session()->regenerate();
            $eventDispatcher->dispatch('login.log',['email'=>$email]);
            return redirect()->route('admin.system.admin.listing');
        }
    
        return back()->withErrors([
            'email' => 'Invalid email or password.',
        ]);
    }
    /**
     * Logout user
     */
    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }
}

