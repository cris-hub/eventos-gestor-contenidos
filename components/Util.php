<?php

namespace app\components;

use yii\helpers\Html;
use Yii;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Util.php
 *
 * // CLASE PARA FUNCIONES UTILES PARA TODO EL SISTEMA
 *
 * @category  category
 * @author    Alexander Arcila <alexander.arcila@ingeneo.com.co>
 * @copyright 2018 INGENEO S.A.S.
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version   Release: $Id$
 * @link      http://www.ingeneo.com.co
 * 
 */
class Util {
   
    /**
     * Retorna listado de estados
     * 
     * @param string $estado
     * @return string
     * 
     * @author    Alexander Arcila <alexander.arcila@ingeneo.com.co>
     * @copyright 2018 INGENEO S.A.S.
     * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
     * @version   Release: $Id$
     * @link      http://www.ingeneo.com.co
     */
    public static function getStatus($status) {
        $strStatus = "";
        switch ($status) {
            case "active":
                $strStatus = Yii::t('app', 'Active');
                break;            
            default:
                $strStatus = Yii::t('app', 'Inactive');
                break;
        }
        return $strStatus;
    }   
    
    /**
     * Retorna del listado de estados
     * 
     * @return array
     * 
     * @author    Alexander Arcila <alexander.arcila@ingeneo.com.co>
     * @copyright 2018 INGENEO S.A.S.
     * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
     * @version   Release: $Id$
     * @link      http://www.ingeneo.com.co
     */
    public static function getlistStatus(){
        return [ 
            'active' => Yii::t('app', 'Active'), 
            'inactive' => Yii::t('app', 'Inactive')
            ];
    }
}
