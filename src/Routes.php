<?php declare(strict_types = 1);

return [
    // Pages
    ['GET', '/', ['App\Controllers\Homepage', 'show']],
    ['GET', '/about', ['App\Controllers\Homepage', 'about']],
    ['GET', '/contact', ['App\Controllers\Homepage', 'contact']],
    ['GET', '/terms', ['App\Controllers\Homepage', 'terms']],
    ['GET', '/privacy', ['App\Controllers\Homepage', 'privacy']],
    ['GET', '/login', ['App\Controllers\Account\Login', 'show']],
    ['GET', '/register', ['App\Controllers\Acount\Register', 'show']],
  
    /** Callback route
     * 
     *  Handle all callback
    */ 
        // Login
        ['GET', '/callback/login/default', ['App\Controllers\Account\Login', 'callback'] ],
        ['GET', '/callback/login/fb', ['App\Controllers\Account\Login', 'fbCallback'] ],
        ['GET', '/callback/login/tw', ['App\Controllers\Account\Login', 'twCallback'] ],
        // Registration
        ['GET', '/callback/register/default', ['App\Controllers\Account\Register', 'callback'] ],
        ['GET', '/callback/register/fb', ['App\Controllers\Account\Register', 'fbCallback'] ],
        ['GET', '/callback/register/tw', ['App\Controllers\Account\Register', 'twCallback'] ],
        // Others
        ['GET', '/manage', ['App\Controllers\Backend\Admin', 'show'] ],
];