<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Repondents Controller
 *
 * @property \App\Model\Table\RepondentsTable $Repondents
 */
class RepondentsController extends AppController
{

    /**
     * Index method
     *
     * @return void
     */
    public function index()
    {
        $this->set('repondents', $this->paginate($this->Repondents));
        $this->set('_serialize', ['repondents']);
    }

    /**
     * View method
     *
     * @param string|null $id Repondent id.
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function view($id = null)
    {
        $repondent = $this->Repondents->get($id, [
            'contain' => []
        ]);
        $this->set('repondent', $repondent);
        $this->set('_serialize', ['repondent']);
    }

    /**
     * Add method
     *
     * @return void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $repondent = $this->Repondents->newEntity();
        if ($this->request->is('post')) {
            $repondent = $this->Repondents->patchEntity($repondent, $this->request->data);
            if ($this->Repondents->save($repondent)) {
                $this->Flash->success(__('The repondent has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The repondent could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('repondent'));
        $this->set('_serialize', ['repondent']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Repondent id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $repondent = $this->Repondents->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $repondent = $this->Repondents->patchEntity($repondent, $this->request->data);
            if ($this->Repondents->save($repondent)) {
                $this->Flash->success(__('The repondent has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The repondent could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('repondent'));
        $this->set('_serialize', ['repondent']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Repondent id.
     * @return void Redirects to index.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $repondent = $this->Repondents->get($id);
        if ($this->Repondents->delete($repondent)) {
            $this->Flash->success(__('The repondent has been deleted.'));
        } else {
            $this->Flash->error(__('The repondent could not be deleted. Please, try again.'));
        }
        return $this->redirect(['action' => 'index']);
    }
}
