<?php
!defined('IN_PTF') && exit('ILLEGAL EXECUTION');

// c is controller
// a is action
$controller = _req('c', 'index');
$action = _req('a');
$target = _req('target');

$by_ajax = i($_REQUEST['is_ajax']) || (strtolower(i($_SERVER['HTTP_X_REQUESTED_WITH'])) == strtolower('XMLHttpRequest'));
$by_post = strtolower(i($_SERVER['REQUEST_METHOD'])) == 'post';

$page = array(
    'title'   => $config['site']['name'],
    'head'    => array(), // 在head里面的语句
    'scripts' => array(), // 页面底部的script
    'styles'  => array(), // head里面的css
    'append_divs' => array(), // 附加的对话框
); // 关于这个页面的变量
