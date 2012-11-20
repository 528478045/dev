<?php
!defined('IN_PTF') && exit('ILLEGAL EXECUTION');
/**
 * @author  ryan <cumt.xiaochi@gmail.com>
 */
?>
<?php include smart_view('account.search'); ?>
<div>
    <span>工厂名称：</span>
    <span><?= $factory->name ?></span>
    <span>联系人：</span>
    <span><?= $factory->contact ?></span>
    <span>未结清总额：</span>
    <span><?= $factory->undone ?></span>
    <button class="recharge-btn" data-id="<?= $target ?>">工厂充值</button>
</div>
<?php include smart_view('paging'); ?>
<table>
    <tr>
        <td>订单号</td>
        <td>下单时间</td>
        <td>客户名</td>
        <td>订单金额</td>
        <td>订单状态</td>
        <td>已付金额</td>
        <td>未结清</td>
        <td>打款</td>
        <td>操作记录</td>
    </tr>
    <?php foreach ($orders as $order): ?>
        <?php 
        $final_price = $order->priceDate('factory')->final();
        $paid = $order->factory_paid;
        ?>
        <tr>
            <td><a href="<?= ROOT . 'order?order_no=' . $order->order_no ?>"><?= $order->order_no ?></a></td>
            <td><?= $order->submit_time ?></td>
            <td><?= $order->customer()->user()->realname ?></td>
            <td><?= $final_price ?></td>
            <td><?= $order->state ?></td>
            <td><?= $paid ?></td>
            <td><?= $final_price - $paid ?></td>
            <td><span class="pay-factory-btn">打款</span></td>
            <td><span class="view-order-facotry-btn">查看</span></td>
        </tr>
    <?php endforeach ?>
</table>