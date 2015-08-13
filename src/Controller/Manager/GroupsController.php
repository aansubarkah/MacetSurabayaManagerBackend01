<?php
namespace App\Controller\Manager;

use App\Controller\Manager\AppController;

/**
 * Groups Controller
 *
 * @property \App\Model\Table\GroupsTable $Groups
 */
class GroupsController extends AppController
{

    /**
     * Index method
     *
     * @return void
     */
    public function index()
    {
        $groups = $this->Groups->find('all', [
            'conditions' => ['Groups.active' => true],
            'order' => ['Groups.name' => 'ASC']
        ]);
        $this->set([
            'groups' => $groups,
            '_serialize' => ['groups']
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
        $group = $this->Groups->get($id);
        $this->set([
            'group' => $group,
            '_serialize' => ['group']
        ]);
    }

    /**
     * Add method
     *
     * @return void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        if(isset($this->request->data['group']['active'])) unset($this->request->data['group']['active']);

        $group = $this->Groups->newEntity($this->request->data['group']);
        if ($this->request->is('post')) {
            if ($this->Groups->save($group)) {
                $message = 'Saved';
            } else {
                $message = 'Error';
            }
        }
        $this->set([
            'group' => $message,
            '_serialize' => ['group']
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
        $group = $this->Groups->get($id);
        if ($this->request->is(['patch', 'post', 'put'])) {
            if(isset($this->request->data['group']['active'])) unset($this->request->data['group']['active']);

            $group = $this->Groups->patchEntity($group, $this->request->data['group']);
            if ($this->Groups->save($group)) {
                $message = 'Saved';
            } else {
                $message = 'Error';
            }
        }
        $this->set([
            'group' => $message,
            '_serialize' => ['group']
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
        $group = $this->Groups->get($id);
        if ($this->request->is(['delete'])) {
            $group->active = false;
            if ($this->Groups->save($group)) {
                $message = 'Deleted';
            } else {
                $message = 'Error';
            }
        }
        $this->set([
            'group' => $message,
            '_serialize' => ['group']
        ]);
    }
}
