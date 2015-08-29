<?php
namespace App\Controller\Manager;

use App\Controller\Manager\AppController;
use Cake\Event\Event;
use Cake\Network\Exception\UnauthorizedException;
use Cake\Utility\Security;

/**
 * Users Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 */
class UsersController extends AppController
{

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        $this->Auth->allow(['add', 'token']);
        //$this->Auth->allow(['token']);
    }

    /**
     * Index method
     *
     * @return void
     */
    public function index()
    {
        $users = $this->Users->find('all', [
            'conditions' => ['Users.active' => true],
            'order' => ['Users.username' => 'ASC']
        ]);
        $this->set([
            'users' => $users,
            '_serialize' => ['users']
        ]);
    }

    /**
     * View method
     *
     * @param string|null $id User id.
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function view($id = null)
    {
        $user = $this->Users->get($id);
        $this->set([
            'user' => $user,
            '_serialize' => ['user']
        ]);
    }

    /**
     * Add method
     *
     * @return void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        //$this->request->data['active'] = 1;
        if (isset($this->request->data['user']['active'])) unset($this->request->data['user']['active']);
        $this->request->data['user']['active'] = 1;

        $user = $this->Users->newEntity($this->request->data['user']);
        if ($this->Users->save($user)) {
            $data = [
                'id' => $user->id,
                'user' => $user->username,
                'token' => $token = \JWT::encode(
                    [
                        'id' => $user->id,
                        'username' => $user->username,
                        'email' => $user->email,
                        'exp' => time() + 604800
                    ],
                    Security::salt())
            ];
        } else {
            $data = 'error!';
        }

        $this->set([
            'user' => $data,
            '_serialize' => ['user']
        ]);
        /*if (isset($this->request->data['user']['active'])) unset($this->request->data['user']['active']);

        $user = $this->Users->newEntity($this->request->data['user']);
        if ($this->request->is('post')) {
            if ($this->Users->save($user)) {
                $message = 'Saved';
            } else {
                $message = 'Error';
            }
        }
        $this->set([
            'user' => $message,
            '_serialize' => ['user']
        ]);*/
    }

    public function token()
    {
        $user = $this->request->data;
        $user = $this->Auth->identify();
        if (!$user) {
            throw new UnauthorizedException('Invalid username or password');
        }

        $this->set([
            'token' => $token = \JWT::encode([
                'id' => $user['id'],
                'username' => $user['username'],
                'email' => $user['email'],
                'exp' => time() + 604800
            ],
                Security::salt()
            ),
            //'username' => $user['username'],
            //'_serialize' => ['token','username']
            '_serialize' => ['token']
        ]);
    }

    /**
     * Edit method
     *
     * @param string|null $id User id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $user = $this->Users->get($id);
        if ($this->request->is(['patch', 'post', 'put'])) {
            if (isset($this->request->data['user']['active'])) unset($this->request->data['user']['active']);

            $user = $this->Users->patchEntity($user, $this->request->data['user']);
            if ($this->Users->save($user)) {
                $message = 'Saved';
            } else {
                $message = 'Error';
            }
        }
        $this->set([
            'user' => $message,
            '_serialize' => ['user']
        ]);
    }

    /**
     * Delete method
     *
     * @param string|null $id User id.
     * @return void Redirects to index.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $user = $this->Users->get($id);
        if ($this->request->is(['delete'])) {
            $user->active = false;
            if ($this->Users->save($user)) {
                $message = 'Deleted';
            } else {
                $message = 'Error';
            }
        }
        $this->set([
            'user' => $message,
            '_serialize' => ['user']
        ]);
    }
}
