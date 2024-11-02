<?php
 
/**
 *  PHP Mikrotik Billing (https://freeispradius.com/)
 *  by https://t.me/freeispradius
 **/
 
_auth();
$action = $routes['1'];
$user = User::_info();
$ui->assign('_user', $user);
 
 
 $router_id=$user['router_id'];
 
 
 
 
switch ($action) {
    case 'voucher':
        $ui->assign('_system_menu', 'voucher');
        $ui->assign('_title', Lang::T('Order Voucher'));
        run_hook('customer_view_order'); #HOOK
        $ui->display('user-order.tpl');
        break;
    case 'history':
        $ui->assign('_system_menu', 'history');
        $paginator = Paginator::build(ORM::for_table('tbl_payment_gateway'), ['username' => $user['username']]);
        $d = ORM::for_table('tbl_payment_gateway')
            ->where('username', $user['username'])
            ->order_by_desc('id')
            ->offset($paginator['startpoint'])->limit($paginator['limit'])
            ->find_many();
        $ui->assign('paginator', $paginator);
        $ui->assign('d', $d);
        $ui->assign('_title', Lang::T('Order History'));
        run_hook('customer_view_order_history'); #HOOK
        $ui->display('user-orderHistory.tpl');
        break;
        case 'balance':
            if (strpos($user['email'], '@') === false) {
                r2(U . 'accounts/profile', 'e', Lang::T("Please enter your email address"));
            }
            $ui->assign('_title', 'Top Up');
            $ui->assign('_system_menu', 'balance');
            $plans_balance = ORM::for_table('tbl_plans')->where('enabled', '1')->where('type', 'Balance')->where('allow_purchase', 'yes')->find_many();
            $ui->assign('plans_balance', $plans_balance);
            $ui->display('user-orderBalance.tpl');
            break;
            case 'package':
                // Ensure the user is logged in and fetch their info
                if (strpos($user['email'], '@') === false) {
                    error_log("User email is invalid: " . $user['email']); // Log invalid email
                    r2(U . 'accounts/profile', 'e', Lang::T("Please enter your email address"));
                }
 
                // Fetch routers associated with the user's router_id if needed
$routers = ORM::for_table('tbl_routers')
->where('id', $user['router_id']) // Fetching based on the user's router_id
->find_many();
error_log("Number of Routers Found: " . count($routers)); // Log number of routers found
 
// Check if router details are assigned properly
if (!empty($routers)) {
$ui->assign('routers', $routers);
$ui->assign('router_id', $user['router_id']); // Ensure router_id is assigned
}
 
 
                $ui->assign('_title', 'Order Plan');
                $ui->assign('_system_menu', 'package');
 
                // Log user info to ensure correct user data is being accessed
                error_log("User ID: " . $user['id']);
                error_log("Username: " . $user['username']);
                error_log("Router ID: " . $user['router_id']);
 
                // Fetch the active plan for the logged-in user using their customer_id
                $active_plan = ORM::for_table('tbl_user_recharges')
                    ->where('customer_id', $user['id']) // Ensure 'id' matches the customer_id from tbl_user_recharges
                    ->find_one();
 
                if ($active_plan) {
                    error_log("Active Plan Found: Plan ID - " . $active_plan->plan_id . ", Name - " . $active_plan->namebp); // Log active plan details
 
                    // Retrieve detailed plan information from tbl_plans using plan_id
                    $plan_details = ORM::for_table('tbl_plans')
                        ->where('enabled', '1')
                        ->where('id', $active_plan->plan_id) // Matching plan_id from tbl_user_recharges
                        ->find_one();
 
                    if ($plan_details) {
                        error_log("Plan Details Found: Plan Name - " . $plan_details->name_plan); // Log detailed plan info
 
                        // Assign the plan details to the UI for display in the template
                        $ui->assign('active_plan', $plan_details->as_array());
 
                        // Fetch routers associated with the user's router_id if needed
                        $routers = ORM::for_table('tbl_routers')
                            ->where('id', $user['router_id']) // Fetching based on the user's router_id
                            ->find_many();
                        error_log("Number of Routers Found: " . count($routers)); // Log number of routers found
                        $ui->assign('routers', $routers);
 
                        // Run any additional hooks and display the template
                        run_hook('customer_view_order_plan'); #HOOK
                        $ui->display('user-orderPlan.tpl'); // Template should handle displaying the 'active_plan' variable
                    } else {
                        // Handle case where plan details are not found
                        error_log("No plan details found for Plan ID: " . $active_plan->plan_id);
                        r2(U . "home", 'e', Lang::T("Plan details not found"));
                    }
                } else {
                    // Handle case where no active plan is found for the user
                    error_log("No active plan found for Customer ID: " . $user['id']);
                    r2(U . "home", 'e', Lang::T("No active plan found for your account"));
                }
 
                break;
 
 
 
 
 
    case 'unpaid':
        $d = ORM::for_table('tbl_payment_gateway')
            ->where('username', $user['username'])
            ->where('status', 1)
            ->find_one();
        run_hook('custome
        r_find_unpaid'); #HOOK
        if ($d) {
            if (empty($d['pg_url_payment'])) {
                r2(U . "order/buy/" . $trx['routers_id'] . '/' . $trx['plan_id'], 'w', Lang::T("Checking payment"));
            } else {
                r2(U . "order/view/" . $d['id'] . '/check/', 's', Lang::T("You have unpaid transaction"));
            }
        } else {
            r2(U . "order/package/", 's', Lang::T("You have no unpaid transaction"));
        }
        break;
        case 'view':
            $trxid = $routes['2'];
            $trx = ORM::for_table('tbl_payment_gateway')
                ->where('username', $user['username'])
                ->find_one($trxid);
            run_hook('customer_view_payment'); #HOOK
 
            // jika tidak ditemukan, berarti punya orang lain
            if (empty($trx)) {
                r2(U . "order/package", 'w', Lang::T("Payment not found"));
            }
 
            // jika url kosong, balikin ke buy, kecuali cancel
            if (empty($trx['pg_url_payment']) && $routes['3'] != 'cancel') {
                r2(U . "order/buy/" . (($trx['routers_id'] == 0) ? $trx['routers'] : $trx['routers_id']) . '/' . $trx['plan_id'], 'w', Lang::T("Checking payment"));
            }
        if ($routes['3'] == 'check') {
            if (!file_exists($PAYMENTGATEWAY_PATH . DIRECTORY_SEPARATOR . $trx['gateway'] . '.php')) {
                r2(U . 'order/view/' . $trxid, 'e', Lang::T("No Payment Gateway Available"));
            }
            run_hook('customer_check_payment_status'); #HOOK
            include $PAYMENTGATEWAY_PATH . DIRECTORY_SEPARATOR . $trx['gateway'] . '.php';
            call_user_func($trx['gateway'] . '_validate_config');
            call_user_func($config['payment_gateway'] . '_get_status', $trx, $user);
        } else if ($routes['3'] == 'cancel') {
            run_hook('customer_cancel_payment'); #HOOK
            $trx->pg_paid_response = '{}';
            $trx->status = 4;
            $trx->paid_date = date('Y-m-d H:i:s');
            $trx->save();
            $trx = ORM::for_table('tbl_payment_gateway')
                ->where('username', $user['username'])
                ->find_one($trxid);
            if ('midtrans' == $trx['gateway']) {
                //Hapus invoice link
            }
        }
        if (empty($trx)) {
            r2(U . "order/package", 'e', Lang::T("Transaction Not found"));
        }
        $router = ORM::for_table('tbl_routers')->find_one($trx['routers_id']);
        $plan = ORM::for_table('tbl_plans')->find_one($trx['plan_id']);
        $bandw = ORM::for_table('tbl_bandwidth')->find_one($plan['id_bw']);
        $ui->assign('trx', $trx);
        $ui->assign('router', $router);
        $ui->assign('plan', $plan);
        $ui->assign('bandw', $bandw);
        $ui->assign('_title', 'TRX #' . $trxid);
        $ui->display('user-orderView.tpl');
        break;
    case 'pay':
        if ($config['enable_balance'] != 'yes') {
            r2(U . "order/package", 'e', Lang::T("Balance not enabled"));
        }
        $plan = ORM::for_table('tbl_plans')->where('enabled', '1')->find_one($routes['3']);
        if (empty($plan)) {
            r2(U . "order/package", 'e', Lang::T("Plan Not found"));
        }
 
//added commit
 
if(!$plan['enabled']){
    r2(U . "home", 'e', 'Plan is not exists');
}
if($plan['allow_purchase'] != 'yes'){
    r2(U . "home", 'e', 'Cannot recharge this plan');
}
 
//end of commit
 
        if ($routes['2'] == 'radius') {
            $router_name = 'radius';
        } else {
            $router_name = $plan['routers'];
        }
        if ($plan && $plan['enabled'] && $user['balance'] >= $plan['price']) {
            if (Package::rechargeUser($user['id'], $router_name, $plan['id'], 'Customer', 'Balance')) {
                // if success, then get the balance
                Balance::min($user['id'], $plan['price']);
                r2(U . "home", 's', Lang::T("Success to buy package"));
            } else {
                r2(U . "order/package", 'e', Lang::T("Failed to buy package"));
                Message::sendTelegram("Buy Package with Balance Failed\n\n#u$c[username] #buy \n" . $plan['name_plan'] .
                    "\nRouter: " . $router_name .
                    "\nPrice: " . $p['price']);
            }
        } else {
            //added commit
            r2(U . "home", 'e', 'Plan is not exists');
 
//end of commit
        }
        break;
    case 'send':
        if ($config['enable_balance'] != 'yes') {
            r2(U . "order/package", 'e', Lang::T("Balance not enabled"));
        }
        $ui->assign('_title', Lang::T('Buy for friend'));
        $ui->assign('_system_menu', 'package');
        $plan = ORM::for_table('tbl_plans')->find_one($routes['3']);
        if (empty($plan)) {
            r2(U . "order/package", 'e', Lang::T("Plan Not found"));
        }
 
        //added commit
        if(!$plan['enabled']){
            r2(U . "home", 'e', 'Plan is not exists');
        }
        if($plan['allow_purchase'] != 'yes'){
            r2(U . "home", 'e', 'Cannot recharge this plan');
        }
 
        //end of commit
 
        if ($routes['2'] == 'radius') {
            $router_name = 'radius';
        } else {
            $router_name = $plan['routers'];
        }
        if (isset($_POST['send']) && $_POST['send'] == 'plan') {
            $target = ORM::for_table('tbl_customers')->where('username', _post('username'))->find_one();
            if (!$target) {
                r2(U . 'home', 'd', Lang::T('Username not found'));
            }
            if ($user['balance'] < $plan['price']) {
                r2(U . 'home', 'd', Lang::T('insufficient balance'));
            }
            if ($user['username'] == $target['username']) {
                r2(U . "order/pay/$routes[2]/$routes[3]", 's', '^_^ v');
            }
            $active = ORM::for_table('tbl_user_recharges')
                ->where('username', _post('username'))
                ->where('status', 'on')
                ->find_one();
 
            if ($active && $active['plan_id'] != $plan['id']) {
                r2(U . "order/package", 'e', Lang::T("Target has active plan, different with current plant.") . " [ <b>$active[namebp]</b> ]");
            }
            if (Package::rechargeUser($target['id'], $router_name, $plan['id'], $user['fullname'], 'Balance')) {
                // if success, then get the balance
                Balance::min($user['id'], $plan['price']);
                //sender
                $d = ORM::for_table('tbl_payment_gateway')->create();
                $d->username = $user['username'];
                $d->gateway = $target['username'];
                $d->plan_id = $plan['id'];
                $d->plan_name = $plan['name_plan'];
                $d->routers_id = $routes['2'];
                $d->routers = $router_name;
                $d->price = $plan['price'];
                $d->payment_method = "Balance";
                $d->payment_channel = "Send Plan";
                $d->created_date = date('Y-m-d H:i:s');
                $d->paid_date = date('Y-m-d H:i:s');
                $d->expired_date = date('Y-m-d H:i:s');
                $d->pg_url_payment = 'balance';
                $d->status = 2;
                $d->save();
                $trx_id = $d->id();
                //receiver
                $d = ORM::for_table('tbl_payment_gateway')->create();
                $d->username = $target['username'];
                $d->gateway = $user['username'];
                $d->plan_id = $plan['id'];
                $d->plan_name = $plan['name_plan'];
                $d->routers_id = $routes['2'];
                $d->routers = $router_name;
                $d->price = $plan['price'];
                $d->payment_method = "Balance";
                $d->payment_channel = "Received Plan";
                $d->created_date = date('Y-m-d H:i:s');
                $d->paid_date = date('Y-m-d H:i:s');
                $d->expired_date = date('Y-m-d H:i:s');
                $d->pg_url_payment = 'balance';
                $d->status = 2;
                $d->save();
                r2(U . "order/view/$trx_id", 's', Lang::T("Success to send package"));
            } else {
                r2(U . "order/package", 'e', Lang::T("Failed to Send package"));
                Message::sendTelegram("Send Package with Balance Failed\n\n#u$user[username] #send \n" . $plan['name_plan'] .
                    "\nRouter: " . $router_name .
                    "\nPrice: " . $plan['price']);
            }
        }
        $ui->assign('username', $_GET['u']);
        $ui->assign('router', $router_name);
        $ui->assign('plan', $plan);
        $ui->display('user-sendPlan.tpl');
        break;
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
 
// Updated buy case
case 'buy':
    file_put_contents('order.log', "Buy action triggered with route2: " . $routes[2] . " and route3: " . $routes[3] . "\n", FILE_APPEND);
 
    if (strpos($user['email'], '@') === false) {
        file_put_contents('order.log', "User email invalid: " . $user['email'] . "\n", FILE_APPEND);
        r2(U . 'accounts/profile', 'e', Lang::T("Please enter your email address"));
    }
 
    // Fetch the payment gateway from tbl_appconfig
    $payment_gateway_config = ORM::for_table('tbl_appconfig')->where('setting', 'payment_gateway')->find_one();
    if (!$payment_gateway_config) {
        file_put_contents('order.log', "Payment gateway configuration not found.\n", FILE_APPEND);
        r2(U . 'home', 'e', Lang::T("No Payment Gateway Available"));
    }
    $config['payment_gateway'] = $payment_gateway_config->value;
 
    if (!file_exists($PAYMENTGATEWAY_PATH . DIRECTORY_SEPARATOR . $config['payment_gateway'] . '.php')) {
        file_put_contents('order.log', "Payment gateway file not found: " . $config['payment_gateway'] . "\n", FILE_APPEND);
        r2(U . 'home', 'e', Lang::T("No Payment Gateway Available"));
    }
 
    require_once $PAYMENTGATEWAY_PATH . DIRECTORY_SEPARATOR . $config['payment_gateway'] . '.php';
 
    $pgs = [$config['payment_gateway']];
 
    file_put_contents('order.log', "Available payment gateway: " . $config['payment_gateway'] . "\n", FILE_APPEND);
 
    // Fetch the plan details to display
    $plan = ORM::for_table('tbl_plans')->where('id', $routes[3])->find_one();
    if (!$plan) {
        file_put_contents('order.log', "Plan not found with ID: " . $routes[3] . "\n", FILE_APPEND);
        r2(U . 'home', 'e', Lang::T("Plan Not found"));
    }
 
    $ui->assign('pgs', $pgs);
    $ui->assign('route2', $routes[2]);
    $ui->assign('route3', $routes[3]);
    $ui->assign('plan', $plan->as_array());
 
    file_put_contents('order.log', "Selected Plan ID: " . $plan['id'] . ", Plan Name: " . $plan['name_plan'] . "\n", FILE_APPEND);
 
    $ui->display('user-selectGateway.tpl');
    break;
 
    case 'pay_now':
        $gateway = $_POST['gateway'];
        file_put_contents('order.log', "Pay Now action triggered with gateway: " . $gateway . "\n", FILE_APPEND);
 
        $router_id = $_POST['router_id'];
        $plan_id = $_POST['plan_id'];
 
        file_put_contents('order.log', "Received Router ID: " . $router_id . ", Plan ID: " . $plan_id . "\n", FILE_APPEND);
 
        if ($gateway == 'none') {
            file_put_contents('order.log', "No payment gateway selected.\n", FILE_APPEND);
            r2(U . 'order/buy/' . $router_id . '/' . $plan_id, 'e', Lang::T("No Payment Gateway Selected"));
        }
 
        run_hook('customer_buy_plan'); #HOOK
        include $PAYMENTGATEWAY_PATH . DIRECTORY_SEPARATOR . $gateway . '.php';
        call_user_func($gateway . '_validate_config');
 
        $router = ORM::for_table('tbl_routers')->where('enabled', '1')->find_one($router_id);
        if (!$router) {
            $router['id'] = 0;
            $router['name'] = 'balance';
        }
 
        file_put_contents('order.log', "Router selected: ID = " . $router['id'] . ", Name = " . $router['name'] . "\n", FILE_APPEND);
 
        // Fetch the correct plan details
        file_put_contents('order.log', "Attempting to fetch Plan ID: " . $plan_id . "\n", FILE_APPEND);
        $plan = ORM::for_table('tbl_plans')->where('enabled', '1')->where('id', $plan_id)->find_one();
        if (empty($router) || empty($plan)) {
            file_put_contents('order.log', "Router or Plan not found. Router ID: " . $router_id . ", Plan ID: " . $plan_id . "\n", FILE_APPEND);
            r2(U . "order/package", 'e', Lang::T("Plan Not found"));
        }
 
        file_put_contents('order.log', "Plan ID in pay_now: " . $plan['id'] . ", Plan Name: " . $plan['name_plan'] . "\n", FILE_APPEND);
 
        $d = ORM::for_table('tbl_payment_gateway')
            ->where('username', $user['username'])
            ->where('status', 1)
            ->find_one();
 
        if ($d) {
            if ($d['pg_url_payment']) {
                file_put_contents('order.log', "Unpaid transaction found. Redirecting to payment page.\n", FILE_APPEND);
                r2(U . "order/view/" . $d['id'], 'w', Lang::T("You already have unpaid transaction, cancel it or pay it."));
            } else {
                if ($config['payment_gateway'] == $d['gateway']) {
                    $id = $d['id'];
                } else {
                    $d->status = 4;
                    $d->save();
                }
            }
        }
 
        if (empty($id)) {
            $d = ORM::for_table('tbl_payment_gateway')->create();
            $d->username = $user['username'];
            $d->gateway = $config['payment_gateway'];
            $d->plan_id = $plan['id'];
            $d->plan_name = $plan['name_plan'];
            $d->routers_id = $router['id'];
            $d->routers = $router['name'];
            $d->price = $plan['price'];
            $d->created_date = date('Y-m-d H:i:s');
            $d->status = 1;
            $d->save();
            $id = $d->id();
        } else {
            $d->username = $user['username'];
            $d->gateway = $config['payment_gateway'];
            $d->plan_id = $plan['id'];
            $d->plan_name = $plan['name_plan'];
            $d->routers_id = $router['id'];
            $d->routers = $router['name'];
            $d->price = $plan['price'];
            $d->created_date = date('Y-m-d H:i:s');
            $d->status = 1;
            $d->save();
        }
 
        if (!$id) {
            file_put_contents('order.log', "Failed to create transaction.\n", FILE_APPEND);
            r2(U . "order/package/" . $d['id'], 'e', Lang::T("Failed to create Transaction.."));
        } else {
            file_put_contents('order.log', "Transaction created with ID: " . $id . "\n", FILE_APPEND);
            file_put_contents('order.log', "Transaction details: \n Plan ID: " . $plan['id'] . "\n Plan Name: " . $plan['name_plan'] . "\n Router ID: " . $router['id'] . "\n Router Name: " . $router['name'] . "\n Price: " . $plan['price'] . "\n", FILE_APPEND);
            call_user_func($config['payment_gateway'] . '_create_transaction', $d, $user);
        }
        break;
 
    default:
        r2(U . "order/package/", 's', '');
    }