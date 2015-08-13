<?php
namespace App\Controller;

use App\Controller\AppController;

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
        $this->set('accesses', $this->paginate($this->Accesses));
        $this->set('_serialize', ['accesses']);
    }

    /**
     * View method
     *
     * @param string|null $id Access id.
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function view($id = null)
    {
        $access = $this->Accesses->get($id, [
            'contain' => []
        ]);
        $this->set('access', $access);
        $this->set('_serialize', ['access']);
    }

    /**
     * Add method
     *
     * @return void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $access = $this->Accesses->newEntity();
        if ($this->request->is('post')) {
            $access = $this->Accesses->patchEntity($access, $this->request->data);
            if ($this->Accesses->save($access)) {
                $this->Flash->success(__('The access has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The access could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('access'));
        $this->set('_serialize', ['access']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Access id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $access = $this->Accesses->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $access = $this->Accesses->patchEntity($access, $this->request->data);
            if ($this->Accesses->save($access)) {
                $this->Flash->success(__('The access has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The access could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('access'));
        $this->set('_serialize', ['access']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Access id.
     * @return void Redirects to index.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $access = $this->Accesses->get($id);
        if ($this->Accesses->delete($access)) {
            $this->Flash->success(__('The access has been deleted.'));
        } else {
            $this->Flash->error(__('The access could not be deleted. Please, try again.'));
        }
        return $this->redirect(['action' => 'index']);
    }
}
