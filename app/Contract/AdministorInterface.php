<?php
/**
 * Created by PhpStorm.
 * User: lenovo
 * Date: 2016/12/1
 * Time: 15:14
 */

namespace App\Contract;


interface AdministorInterface
{
    public function getReportPost();

    public function getReportUser();
}