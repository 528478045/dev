<?php
!defined('IN_PTF') && exit('ILLEGAL EXECUTION');
/**
 * @author  ryan <cumt.xiaochi@gmail.com>
 */
?>
<h4>审核用户</h4>
<form action="<?= ROOT . '/user/' . $user_id ?>" method="post">
    <div>
        <label for="username">帐号：</label>
        <input name="username" id="username" value="<?= $user_->username ?>">
        <label for="password">修改密码：</label>
        <input name="password" id="password">
    </div>
    <div>
        <label for="realname">姓名：</label>
        <input name="realname" id="realname" value="<?= $cus->realname ?>">
        <label for="phone">电话：</label>
        <input name="phone" id="phone" value="<?= $user_->phone ?>">
    </div>
    <div>
        <label for="qq">QQ：</label>
        <input name="qq" id="qq" value="<?= $user_->qq ?>">
        <label for="email">邮箱：</label>
        <input name="email" id="email" value="<?= $user_->email ?>">
    </div>
    <div>
        <label for="address">地址：</label>
        <input name="address" id="address" value="<?= $cus->address()->detail ?>">
    </div>
    <div>
        <label for="remark">备注：</label>
        <input name="remark" id="remark" value="<?= $user_->remark ?>">
        <span>客户不可见</span>
    </div>
    <div>
        <label for="">状态：</label>
        <select name="state">
            <?php foreach ($states as $key => $value): ?>
                <option value="<?= $key ?>" <?= $cus->state === $key ? 'select' : '' ?> ><?= $value ?></option>
            <?php endforeach ?>
        </select>
    </div>
    <input type="submit" value="确定">
</form>
