<?php

return array (
  'module' => 'admin',
  'menu' => 
  array (
    0 => 'add',
    1 => 'forbid',
    2 => 'resume',
    3 => 'delete',
    4 => 'recyclebin',
    5 => 'saveorder',
    6 => 'echarts',
  ),
  'create_config' => true,
  'controller' => 'SchoolManagement',
  'title' => '学区管理',
  'form' => 
  array (
    0 => 
    array (
      'title' => '序号',
      'name' => 'id',
      'type' => 'text',
      'option' => '',
      'default' => '',
      'search_type' => 'text',
      'validate' => 
      array (
        'datatype' => '',
        'nullmsg' => '',
        'errormsg' => '',
      ),
    ),
    4 => 
    array (
      'title' => '校区名称',
      'name' => 'schoolName',
      'type' => 'text',
      'option' => '',
      'default' => '',
      'sort' => '1',
      'search' => '1',
      'search_type' => 'text',
      'require' => '1',
      'validate' => 
      array (
        'datatype' => '*',
        'nullmsg' => '',
        'errormsg' => '',
      ),
    ),
    3 => 
    array (
      'title' => '校区编号',
      'name' => 'schoolID',
      'type' => 'text',
      'option' => '',
      'default' => '',
      'sort' => '1',
      'search' => '1',
      'search_type' => 'text',
      'require' => '1',
      'validate' => 
      array (
        'datatype' => '*',
        'nullmsg' => '',
        'errormsg' => '',
      ),
    ),
    5 => 
    array (
      'title' => '学区账号',
      'name' => 'schoolAccount',
      'type' => 'text',
      'option' => '',
      'default' => '',
      'sort' => '1',
      'search' => '1',
      'search_type' => 'text',
      'require' => '1',
      'validate' => 
      array (
        'datatype' => '*',
        'nullmsg' => '',
        'errormsg' => '',
      ),
    ),
    6 => 
    array (
      'title' => '密码',
      'name' => 'passWord',
      'type' => 'password',
      'option' => '',
      'default' => '',
      'search_type' => 'text',
      'require' => '1',
      'validate' => 
      array (
        'datatype' => '*',
        'nullmsg' => '',
        'errormsg' => '',
      ),
    ),
    7 => 
    array (
      'title' => '联系电话',
      'name' => 'number',
      'type' => 'text',
      'option' => '',
      'default' => '',
      'search_type' => 'text',
      'validate' => 
      array (
        'datatype' => '',
        'nullmsg' => '',
        'errormsg' => '',
      ),
    ),
    8 => 
    array (
      'title' => '校区地址',
      'name' => 'address',
      'type' => 'text',
      'option' => '',
      'default' => '',
      'search_type' => 'text',
      'validate' => 
      array (
        'datatype' => '',
        'nullmsg' => '',
        'errormsg' => '',
      ),
    ),
  ),
  'create_table' => '1',
  'table_engine' => 'InnoDB',
  'table_name' => '',
  'field' => 
  array (
    1 => 
    array (
      'name' => 'id',
      'type' => 'varchar(255)',
      'default' => 'NULL',
      'not_null' => '1',
      'comment' => '序号',
      'extra' => 'auto_increment',
    ),
    2 => 
    array (
      'name' => 'schoolName',
      'type' => 'varchar(255)',
      'default' => 'NULL',
      'not_null' => '1',
      'key' => '1',
      'comment' => '校区名称',
      'extra' => '',
    ),
    3 => 
    array (
      'name' => 'schoolID',
      'type' => 'varchar(255)',
      'default' => 'NULL',
      'not_null' => '1',
      'key' => '1',
      'comment' => '校区编号',
      'extra' => '',
    ),
    4 => 
    array (
      'name' => 'schoolAccount',
      'type' => 'varchar(255)',
      'default' => 'NULL',
      'not_null' => '1',
      'key' => '1',
      'comment' => '学区账号',
      'extra' => '',
    ),
    5 => 
    array (
      'name' => 'passWord',
      'type' => 'varchar(255)',
      'default' => 'NULL',
      'not_null' => '1',
      'comment' => '密码',
      'extra' => '',
    ),
    6 => 
    array (
      'name' => 'number',
      'type' => 'varchar(255)',
      'default' => 'NULL',
      'comment' => '联系电话',
      'extra' => '',
    ),
    7 => 
    array (
      'name' => 'address',
      'type' => 'varchar(255)',
      'default' => 'NULL',
      'comment' => '校区地址',
      'extra' => '',
    ),
  ),
);
