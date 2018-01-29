<?php
/**
 * Created by PhpStorm.
 * User: herald
 * Date: 06-Sep-17
 * Time: 14:49
 */

namespace App\Model;


abstract class BaseEntity{

	public abstract function getId();

	public abstract function getName();

}