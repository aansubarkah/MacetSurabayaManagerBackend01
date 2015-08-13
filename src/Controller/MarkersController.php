<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Markers Controller
 *
 * @property \App\Model\Table\MarkersTable $Markers
 */
class MarkersController extends AppController
{

    /**
     * Index method
     *
     * @return void
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Categories', 'Users', 'Respondents', 'Wheaters']
        ];
        $this->set('markers', $this->paginate($this->Markers));
        $this->set('_serialize', ['markers']);
    }

    /**
     * View method
     *
     * @param string|null $id Marker id.
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function view($id = null)
    {
        $marker = $this->Markers->get($id, [
            'contain' => ['Categories', 'Users', 'Respondents', 'Wheaters']
        ]);
        $this->set('marker', $marker);
        $this->set('_serialize', ['marker']);
    }

    /**
     * Add method
     *
     * @return void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $marker = $this->Markers->newEntity();
        if ($this->request->is('post')) {
            $marker = $this->Markers->patchEntity($marker, $this->request->data);
            if ($this->Markers->save($marker)) {
                $this->Flash->success(__('The marker has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The marker could not be saved. Please, try again.'));
            }
        }
        $categories = $this->Markers->Categories->find('list', ['limit' => 200]);
        $users = $this->Markers->Users->find('list', ['limit' => 200]);
        $respondents = $this->Markers->Respondents->find('list', ['limit' => 200]);
        $wheaters = $this->Markers->Wheaters->find('list', ['limit' => 200]);
        $this->set(compact('marker', 'categories', 'users', 'respondents', 'wheaters'));
        $this->set('_serialize', ['marker']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Marker id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $marker = $this->Markers->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $marker = $this->Markers->patchEntity($marker, $this->request->data);
            if ($this->Markers->save($marker)) {
                $this->Flash->success(__('The marker has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The marker could not be saved. Please, try again.'));
            }
        }
        $categories = $this->Markers->Categories->find('list', ['limit' => 200]);
        $users = $this->Markers->Users->find('list', ['limit' => 200]);
        $respondents = $this->Markers->Respondents->find('list', ['limit' => 200]);
        $wheaters = $this->Markers->Wheaters->find('list', ['limit' => 200]);
        $this->set(compact('marker', 'categories', 'users', 'respondents', 'wheaters'));
        $this->set('_serialize', ['marker']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Marker id.
     * @return void Redirects to index.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $marker = $this->Markers->get($id);
        if ($this->Markers->delete($marker)) {
            $this->Flash->success(__('The marker has been deleted.'));
        } else {
            $this->Flash->error(__('The marker could not be deleted. Please, try again.'));
        }
        return $this->redirect(['action' => 'index']);
    }
}
