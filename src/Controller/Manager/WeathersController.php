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
    public $paginate = [
        'fields' => ['Weathers.id', 'Weathers.name', 'Weathers.active'],
        'limit' => 25,
        'page' => 0,
        'order' => [
            'Weathers.name' => 'asc'
        ]
    ];

    public function initialize()
    {
        parent::initialize();
        $this->loadComponent('Paginator');
    }

    /**
     * Index method
     *
     * @return void
     */
    public function index()
    {
        $offset = 0;
        if (isset($this->request->query['page'])) {
            if (is_numeric($this->request->query['page'])) {
                $offset = $this->request->query['page'] - 1;
            }
        }

        $limit = 2;
        if (isset($this->request->query['limit'])) {
            if (is_numeric($this->request->query['limit'])) {
                $limit = $this->request->query['limit'];
            }
        }

        $query = '';
        if (isset($this->request->query['query'])) {
            if (!empty(trim($this->request->query['query']))) {
                $query = trim($this->request->query['query']);
            }
        }

        $fetchDataOptions = [
            'conditions' => ['Weathers.active' => true],
            'order' => ['Weathers.name' => 'ASC'],
            'limit' => $limit,
            'page' => $offset
        ];

        if (!empty(trim($query))) {
            $fetchDataOptions['conditions']['LOWER(Weathers.name) LIKE'] = '%' . strtolower($query) . '%';
        }

        $this->paginate = $fetchDataOptions;
        $weathers = $this->paginate('Weathers');

        $allWeathers = $this->Weathers->find('all', $fetchDataOptions);
        $total = $allWeathers->count();

        $meta = [
            'total' => $total
        ];
        $this->set([
            'weathers' => $weathers,
            'meta' => $meta,
            '_serialize' => ['weathers', 'meta']
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
        if (isset($this->request->data['weather']['active'])) unset($this->request->data['weather']['active']);

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
            if (isset($this->request->data['weather']['active'])) unset($this->request->data['weather']['active']);

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
