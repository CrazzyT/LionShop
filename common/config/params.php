<?php
return [
    'adminEmail' => 'admin@example.com',
    'supportEmail' => 'support@example.com',
    'user.passwordResetTokenExpire' => 3600,

    'qiniu' =>[
        'accessKey' =>'Lv7YeLfUC5_L5oXrzPtRQrJqDVrDO0himf8yOxfc',
        'secretKey' =>'eLUFkdKCWB1W5Kc6OmaRr0KNOtXkz2-FRNBwr8e1',
        'bucket'    =>'lionshop',
        'basePath'  =>'/uploads/',
        'domain'    =>'http://owm1errtg.bkt.clouddn.com/',
        'imageView' =>[
            'middle' => 'imageView2/1/w/433/h/325/q/75|imageslim',
            'mini' => 'imageView2/1/w/67/h/60/q/75|imageslim',
            'recommend' => 'imageView2/1/w/246/h/186/q/75|imageslim',
            'thumb' => 'imageView2/1/w/194/h/143/q/75|imageslim',
            'catbest' => 'imageView2/1/w/73/h/73/q/75|imageslim'
        ]
    ],



];
