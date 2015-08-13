<?php
/**
 * Created by PhpStorm.
 * User: aan
 * Date: 13/08/15
 * Time: 11:05
 */
namespace App\Controller\Manager;

use Cake\Controller\Controller;

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @link http://book.cakephp.org/3.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller
{

    /**
     * Initialization hook method.
     *
     * Use this method to add common initialization code like loading components.
     *
     * @return void
     */
    public function initialize()
    {
        parent::initialize();
        //$this->loadComponent('Flash');
        $this->loadComponent('RequestHandler');
    }
}