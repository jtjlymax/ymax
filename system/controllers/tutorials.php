<?php

_admin();
$ui->assign('_title', Lang::T('Tutorials'));
$ui->assign('_system_menu', 'tutorials');

$action = $routes['1'];

if (!in_array($admin['user_type'], ['SuperAdmin', 'Admin'])) {
    _alert(Lang::T('You do not have permission to access this page'),'danger', "dashboard");
}

switch ($action) {
    case 'list':
        $tutorials = [
            [
                'title' => 'How to Change Wifi Password',
                'description' => 'Change Wifi Password',
                'video_url' => ''
            ]
            
        ];
        $ui->assign('tutorials', $tutorials);
        $ui->display('tutorial.tpl');
        break;

    default:
        r2(U . 'dashboard', 'e', 'Invalid Action');
        break;
}
?>
