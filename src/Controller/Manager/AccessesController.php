<?php
namespace App\Controller\Manager;

use App\Controller\Manager\AppController;

/**
 * Accesses Controller
 *
 * @property \App\Model\Table\AccessesTable $Accesses
 */
class AccessesController extends AppController
{

    /**
     * Index method
     *
     * @return void
     */
    public function index()
    {
        $accesses = $this->Accesses->find('all', [
            'conditions' => ['Accesses.active' => true],
            'order' => ['Accesses.created' => 'DESC']
        ]);
        $this->set([
            'accesses' => $accesses,
            '_serialize' => ['accesses']
        ]);
    }

    /**
     * View method
     *
     * @param string|null $id Weather id.
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function view($id = null)
    {
        $access = $this->Accesses->get($id);
        $this->set([
            'access' => $access,
            '_serialize' => ['access']
        ]);
    }

    /**
     * Add method
     *
     * @return void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        if(isset($this->request->data['access']['active'])) unset($this->request->data['access']['active']);

        $access = $this->Accesses->newEntity($this->request->data['access']);
        if ($this->request->is('post')) {
            if ($this->Accesses->save($access)) {
                $message = 'Saved';
            } else {
                $message = 'Error';
            }
        }
        $this->set([
            'access' => $message,
            '_serialize' => ['access']
        ]);
    }

    /**
     * Edit method
     *
     * @param string|null $id Weather id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $access = $this->Accesses->get($id);
        if ($this->request->is(['patch', 'post', 'put'])) {
            if(isset($this->request->data['access']['active'])) unset($this->request->data['access']['active']);

            $access = $this->Accesses->patchEntity($access, $this->request->data['access']);
            if ($this->Accesses->save($access)) {
                $message = 'Saved';
            } else {
                $message = 'Error';
            }
        }
        $this->set([
            'access' => $message,
            '_serialize' => ['access']
        ]);
    }

    /**
     * Delete method
     *
     * @param string|null $id Weather id.
     * @return void Redirects to index.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $access = $this->Accesses->get($id);
        if ($this->request->is(['delete'])) {
            $access->active = false;
            if ($this->Accesses->save($access)) {
                $message = 'Deleted';
            } else {
                $message = 'Error';
            }
        }
        $this->set([
            'access' => $message,
            '_serialize' => ['access']
        ]);
    }
}
