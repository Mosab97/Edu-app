<?php
return [
    'sandbox_client_id' => env('PAYPAL_SANDBOX_CLIENT_ID', ''),
    'sandbox_secret' => env('PAYPAL_SANDBOX_SECRET', ''),
    'live_client_id' => env('PAYPAL_LIVE_CLIENT_ID', 'AWvOegNpawtcH_h5uXtm3zNSlu0koRkAObyWZp-opMAzADD9hj-V5I4sGahL3RQpL5Ce32au9qmtKTb7'),
    'live_secret' => env('PAYPAL_LIVE_SECRET', 'EJnUj5aHieSENUlj1Qus1Zvlzej_PiYuB0dw6OVcPwjQBeUsoL5lDG2Nc5ZGLPupvsP8VPuxJPxaYXtE'),

    'settings' => array(
        'mode' => env('PAYPAL_MODE', 'LIVE'),
        'http.ConnectionTimeOut' => 30, // S
        'log.LogEnabled' => true,
        'log.FileName' => storage_path() . '/logs/paypal.log',
        'log.LogLevel' => 'DEBUG'
    ),
];
