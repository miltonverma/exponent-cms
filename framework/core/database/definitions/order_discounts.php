<?php

/**
 * This file is part of Exponent Content Management System
 *
 * Exponent is free software; you can redistribute
 * it and/or modify it under the terms of the GNU
 * General Public License as published by the Free
 * Software Foundation; either version 2 of the
 * License, or (at your option) any later version.
 *
 * @category   Exponent CMS
 * @copyright  2004-2011 OIC Group, Inc.
 * @license    GPL: http://www.gnu.org/licenses/gpl.txt
 * @link       http://www.exponent-docs.org/
 */

if (!defined('EXPONENT')) exit('');

return array(
    "id"=>array(
        DB_FIELD_TYPE=>DB_DEF_ID,
        DB_PRIMARY=>true,
        DB_INCREMENT=>true),
    "orders_id"=>array(
        DB_FIELD_TYPE=>DB_DEF_ID),
    "discount_id"=>array(
        DB_FIELD_TYPE=>DB_DEF_ID,
        DB_INDEX=>10),
    "carts_id"=>array(
        DB_FIELD_TYPE=>DB_DEF_ID,
        DB_INDEX=>10),    
    'title'=>array(
        DB_FIELD_TYPE=>DB_DEF_STRING,
        DB_FIELD_LEN=>200),
    'body'=>array(
        DB_FIELD_TYPE=>DB_DEF_STRING,
        DB_FIELD_LEN=>100000),
    "coupon_code"=>array(
        DB_FIELD_TYPE=>DB_DEF_STRING,
        DB_FIELD_LEN=>200),     
    'discount_total'=>array(
        DB_FIELD_TYPE=>DB_DEF_DECIMAL,
        FORM_FIELD_FILTER=>MONEY),    
    "created_at"=>array(
        DB_FIELD_TYPE=>DB_DEF_TIMESTAMP),
    "edited_at"=>array(
        DB_FIELD_TYPE=>DB_DEF_TIMESTAMP),
);

?>