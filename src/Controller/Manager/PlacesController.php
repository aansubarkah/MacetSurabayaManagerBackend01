<?php
namespace App\Controller\Manager;

use App\Controller\Manager\AppController;

/**
 * Places Controller
 *
 * @property \App\Model\Table\PlacesTable $Places
 */
class PlacesController extends AppController
{

    /**
     * Index method
     *
     * @return void
     */
    public function index()
    {
        $places = $this->Places->find('all', [
            'conditions' => ['Places.active' => true],
            'order' => ['Places.name' => 'ASC']
        ]);
        $this->set([
            'places' => $places,
            '_serialize' => ['places']
        ]);
    }

    /**
     * View method
     *
     * @param string|null $id Place id.
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function view($id = null)
    {
        $place = $this->Places->get($id);
        $this->set([
            'place' => $place,
            '_serialize' => ['place']
        ]);
    }

    /**
     * Add method
     *
     * @return void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        if(isset($this->request->data['place']['active'])) unset($this->request->data['place']['active']);

        $place = $this->Places->newEntity($this->request->data['place']);
        if ($this->request->is('post')) {
            if ($this->Places->save($place)) {
                $message = 'Saved';
            } else {
                $message = 'Error';
            }
        }
        $this->set([
            'place' => $message,
            '_serialize' => ['place']
        ]);
    }

    /**
     * Edit method
     *
     * @param string|null $id Place id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $place = $this->Places->get($id);
        if ($this->request->is(['patch', 'post', 'put'])) {
            if(isset($this->request->data['place']['active'])) unset($this->request->data['place']['active']);

            $place = $this->Places->patchEntity($place, $this->request->data['place']);
            if ($this->Places->save($place)) {
                $message = 'Saved';
            } else {
                $message = 'Error';
            }
        }
        $this->set([
            'place' => $message,
            '_serialize' => ['place']
        ]);
    }

    /**
     * Delete method
     *
     * @param string|null $id Place id.
     * @return void Redirects to index.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $place = $this->Places->get($id);
        if ($this->request->is(['delete'])) {
            $place->active = false;
            if ($this->Places->save($place)) {
                $message = 'Deleted';
            } else {
                $message = 'Error';
            }
        }
        $this->set([
            'place' => $message,
            '_serialize' => ['place']
        ]);
    }
}
