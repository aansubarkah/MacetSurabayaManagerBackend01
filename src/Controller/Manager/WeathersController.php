<?php
namespace App\Controller\Manager;

use App\Controller\Manager\AppController;

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
        $weathers = $this->Weathers->find('all', [
            'conditions' => ['Weathers.active' => true],
            'order' => ['Weathers.name' => 'ASC']
        ]);
        $this->set([
            'weathers' => $weathers,
            '_serialize' => ['weathers']
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
        $weather = $this->Weathers->get($id);
        $this->set([
            'weather' => $weather,
            '_serialize' => ['weather']
        ]);
    }

    /**
     * Add method
     *
     * @return void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        if(isset($this->request->data['weather']['active'])) unset($this->request->data['weather']['active']);

        $weather = $this->Weathers->newEntity($this->request->data['weather']);
        if ($this->request->is('post')) {
            if ($this->Weathers->save($weather)) {
                $message = 'Saved';
            } else {
                $message = 'Error';
            }
        }
        $this->set([
            'weather' => $message,
            '_serialize' => ['weather']
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
        $weather = $this->Weathers->get($id);
        if ($this->request->is(['patch', 'post', 'put'])) {
            if(isset($this->request->data['weather']['active'])) unset($this->request->data['weather']['active']);

            $weather = $this->Weathers->patchEntity($weather, $this->request->data['weather']);
            if ($this->Weathers->save($weather)) {
                $message = 'Saved';
            } else {
                $message = 'Error';
            }
        }
        $this->set([
            'weather' => $message,
            '_serialize' => ['weather']
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
        $weather = $this->Weathers->get($id);
        if ($this->request->is(['delete'])) {
            $weather->active = false;
            if ($this->Weathers->save($weather)) {
                $message = 'Deleted';
            } else {
                $message = 'Error';
            }
        }
        $this->set([
            'weather' => $message,
            '_serialize' => ['weather']
        ]);
    }
}
