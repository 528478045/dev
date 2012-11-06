<?php
!defined('IN_PTF') && exit('ILLEGAL EXECUTION');
/**
 * @author  ryan <cumt.xiaochi@gmail.com>
 */

list(
    $name,
    $product_no,
    $order_no,
    $type,
    $time_start,
    $time_end,
    $state) = _get(
        'name',
        'product_no',
        'order_no',
        'type',
        'time_start',
        'time_end',
        'state');

if (!isset($_GET['state']))
    $state = $target;
if ($state === 'all')
    $state = '';

$conds = compact(
    'name',
    'product_no',
    'order_no',
    'time_start',
    'time_end',
    'type',
    'state');

$types = Product::types();
$state_map = $config['order_states'];
$next_action_map = $config['order_next_action'];
$next_button_map = $config['next_button_map'];

switch ($user_type) {
    case 'Customer':
        $customer = $customer->id;
        break;

    case 'Admin':
    case 'SuperAdmin':

        $factories = Factory::names();

        if ($by_ajax) {
            switch ($action) {
                case 'change_factory':
                    $factory_id = _get('factory_id');
                    $order_id = _get('order_id');
                    $order = new Order($order_id);
                    $order->edit('factory', $factory_id);
                    echo $factories[$factory_id];
                    exit;

                case 'get_price_div':
                    if (is_numeric($target)) {
                        $title = _get('title');
                        $type = _get('type');

                        $order_id = $target;
                        $order = new Order($order_id);
                        
                        $div_name = 'order-change';
                        $view_name = 'order.change';
                        include smart_view('append.div');
                        exit;
                    } else {
                        throw new Exception("unknown to get: $target");
                    }
                    break;

                case 'get_pay_div':
                    $order_id = $target;
                    $order = new Order($order_id);
                    $cus = $order->customer(); // danger! we rewrite
                    $account = $cus->account();

                    $should_pay = $order->priceData('customer')->finalPrice();
                    $paid = $order->paid;
                    $unpaid = $should_pay - $paid;

                    $div_name = 'order-pay';
                    $view_name = 'order.pay';
                    include smart_view('append.div');
                    exit;
                    
                    break;

                default:
                    $id = _get('id');
                    $action = $admin->__call($action . 'Order', new Order($id));
                    echo json_encode(array(
                        'action' => $next_button_map[$action],
                        'caption' => $next_button_map[$action]));
                    break;
            }
            exit;
        }

        if ($action) {
            if (!is_numeric($target))
                throw new Exception("target not numeric: $target");
        }
        switch ($action) {
            case 'change_price':

                if (!$by_post) {
                    throw new Exception("not by post");
                }
                
                $order = new Order($target);

                $type = _post('type');
                $gold_weight = _post('gold_weight');
                $wear_tear = _post('wear_tear');
                $gold_price = _post('gold_price');
                $labor_expense = _post('labor_expense');
                $small_stone = _post('small_stone');
                $st_expense = _post('st_expense');
                $st_price = _post('st_price');
                $st_weight = _post('st_weight');
                $model_expense = _post('model_expense');
                $risk_expense = _post('risk_expense');

                $factory_st = _post('factory_st');
                $factory_st_weigth = _post('factory_st_weigth');

                $order->changePrice(
                    $type, 
                    compact(
                        'gold_weight',
                        'wear_tear',
                        'gold_price',
                        'labor_expense',
                        'small_stone',
                        'st_expense',
                        'st_price',
                        'st_weight',
                        'model_expense',
                        'risk_expense'));

                $order->edit(compact(
                    'factory_st',
                    'factory_st_weight'));

                redirect('order/all');
                break;

            case 'deduct':
                if (!$by_post)
                    throw new Exception('not by post');
                $deduct = _post('deduct');
                $remark = _post('remark');
                $order = new Order($target);
                $customer = $order->customer();
                $account = $customer->account();
                $admin->deductAccountForOrder($account, $order, $deduct, $remark);
                $order->edit('paid', $order->paid + $deduct);

                redirect('order/all');
                break;
            
            default:
                # code...
                break;
        }

        list(
            $customer,
            $username,
            $factory) = _get(
                'customer',
                'username',
                'factory');
        $conds = array_merge(
            $conds,
            compact(
                'username',
                'factory'));

        $page['append_divs']['factory-select'] = 'factory.select';
        break;
    
    default:
        throw new Exception("unkown user type: $user_type");
        break;
}
$conds['customer'] = $customer;

$per_page = 50;
$total = Order::count($conds);
$paging = new Paginate($per_page, $total);
$paging->setCurPage(_get('p') ?: 1);
$orders = Order::listOrder(array_merge(
    array(
    'limit' => $per_page,
    'offset' => $paging->offset()),
    $conds));

// we don't need this two lines
if (empty($orders)) 
    $orders = array();

$matter = $view;
$view = 'board?master';
