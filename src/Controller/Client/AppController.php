<?php
/**
 * Created by PhpStorm.
 * User: aan
 * Date: 13/08/15
 * Time: 11:05
 */
namespace App\Controller\Client;

use Cake\Controller\Controller;
use Cake\Event\Event;

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
    public $components = [
        'RequestHandler'
    ];

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
        //$this->loadComponent();
        $request = $this->request;
        $response = $this->response->cors($request, '*');
    }

    public $limit = 25;

    public function beforeFilter(Event $event)
    {
        //parent::beforeFilter($event);
        //$this->Auth->allow(['view', 'index', 'checkExistence', 'edit', 'delete', 'add']);
        //$this->Auth->allow(['token']);
    }
}