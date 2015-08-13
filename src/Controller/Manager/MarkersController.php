<?php
namespace App\Controller\Manager;

use App\Controller\Manager\AppController;

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
        $markers = $this->Markers->find('all', [
            'conditions' => ['Markers.active' => true],
            'order' => ['Markers.created' => 'DESC']
        ]);
        $this->set([
            'markers' => $markers,
            '_serialize' => ['markers']
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
        $marker = $this->Markers->get($id);
        $this->set([
            'marker' => $marker,
            '_serialize' => ['marker']
        ]);
    }

    /**
     * Add method
     *
     * @return void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        if(isset($this->request->data['marker']['active'])) unset($this->request->data['marker']['active']);

        $marker = $this->Markers->newEntity($this->request->data['marker']);
        if ($this->request->is('post')) {
            if ($this->Markers->save($marker)) {
                $message = 'Saved';
            } else {
                $message = 'Error';
            }
        }
        $this->set([
            'marker' => $message,
            '_serialize' => ['marker']
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
        $marker = $this->Markers->get($id);
        if ($this->request->is(['patch', 'post', 'put'])) {
            if(isset($this->request->data['marker']['active'])) unset($this->request->data['marker']['active']);
            
            $marker = $this->Markers->patchEntity($marker, $this->request->data['marker']);
            if ($this->Markers->save($marker)) {
                $message = 'Saved';
            } else {
                $message = 'Error';
            }
        }
        $this->set([
            'marker' => $message,
            '_serialize' => ['marker']
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
        $marker = $this->Markers->get($id);
        if ($this->request->is(['delete'])) {
            $marker->active = false;
            if ($this->Markers->save($marker)) {
                $message = 'Deleted';
            } else {
                $message = 'Error';
            }
        }
        $this->set([
            'marker' => $message,
            '_serialize' => ['marker']
        ]);
    }
}
