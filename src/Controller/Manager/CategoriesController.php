<?php
namespace App\Controller\Manager;

use App\Controller\Manager\AppController;

/**
 * Categories Controller
 *
 * @property \App\Model\Table\CategoriesTable $Categories
 */
class CategoriesController extends AppController
{

    /**
     * Index method
     *
     * @return void
     */
    public function index()
    {
        $categories = $this->Categories->find('all', [
            'conditions' => ['Categories.active' => true],
            'order' => ['Categories.name' => 'ASC']
        ]);
        $this->set([
            'categories' => $categories,
            '_serialize' => ['categories']
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
        $category = $this->Categories->get($id);
        $this->set([
            'category' => $category,
            '_serialize' => ['category']
        ]);
    }

    /**
     * Add method
     *
     * @return void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        if(isset($this->request->data['category']['active'])) unset($this->request->data['category']['active']);

        $category = $this->Categories->newEntity($this->request->data['category']);
        if ($this->request->is('post')) {
            if ($this->Categories->save($category)) {
                $message = 'Saved';
            } else {
                $message = 'Error';
            }
        }
        $this->set([
            'category' => $message,
            '_serialize' => ['category']
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
        $category = $this->Categories->get($id);
        if ($this->request->is(['patch', 'post', 'put'])) {
            if(isset($this->request->data['category']['active'])) unset($this->request->data['category']['active']);

            $category = $this->Categories->patchEntity($category, $this->request->data['category']);
            if ($this->Categories->save($category)) {
                $message = 'Saved';
            } else {
                $message = 'Error';
            }
        }
        $this->set([
            'category' => $message,
            '_serialize' => ['category']
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
        $category = $this->Categories->get($id);
        if ($this->request->is(['delete'])) {
            $category->active = false;
            if ($this->Categories->save($category)) {
                $message = 'Deleted';
            } else {
                $message = 'Error';
            }
        }
        $this->set([
            'category' => $message,
            '_serialize' => ['category']
        ]);
    }
}
