<?php
namespace app\common\validate;

use think\Validate;

class UserManage extends Validate
{
    protected $rule = [
        "userName|用户姓名" => "require",
        "iphone|电话号码" => "require",
    ];
}
