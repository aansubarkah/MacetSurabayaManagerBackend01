<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Weathers Controller
 *
 * @property \App\Model\Table\WeathersTable $Weathers
 */
class WeathersController extends AppController
{

    /**
     * Index method
     *
     * @return void
     */
    public function index()
    {
        $this->set('weathers', $this->paginate($this->Weathers));
        $this->set('_serialize', ['weathers']);
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
        $weather = $this->Weathers->get($id, [
            'contain' => []
        ]);
        $this->set('weather', $weather);
        $this->set('_serialize', ['weather']);
    }

    /**
     * Add method
     *
     * @return void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $weather = $this->Weathers->newEntity();
        if ($this->request->is('post')) {
            $weather = $this->Weathers->patchEntity($weather, $this->request->data);
            if ($this->Weathers->save($weather)) {
                $this->Flash->success(__('The weather has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The weather could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('weather'));
        $this->set('_serialize', ['weather']);
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
        $weather = $this->Weathers->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $weather = $this->Weathers->patchEntity($weather, $this->request->data);
            if ($this->Weathers->save($weather)) {
                $this->Flash->success(__('The weather has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The weather could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('weather'));
        $this->set('_serialize', ['weather']);
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
        $this->request->allowMethod(['post', 'delete']);
        $weather = $this->Weathers->get($id);
        if ($this->Weathers->delete($weather)) {
            $this->Flash->success(__('The weather has been deleted.'));
        } else {
            $this->Flash->error(__('The weather could not be deleted. Please, try again.'));
        }
        return $this->redirect(['action' => 'index']);
    }
}
