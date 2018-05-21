<?php
namespace app\common\validate;

use think\Validate;

class ClassManage extends Validate
{
    protected $rule = [
        "schoolName|学区名称" => "require",
        "class|学级" => "require",
        "className|班级单位" => "require",
        "classTeacher|班主任" => "require",
    ];
}
