<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Places Controller
 *
 * @property \App\Model\Table\PlacesTable $Places
 */
class PlacesController extends AppController
{
    public function beforeFilter(Event $event)
    {
        //parent::beforeFilter($event);
        //$this->Auth->allow(['add', 'token']);
        //$this->Auth->allow(['token']);
    }

    public $limit = 25;

    public $paginate = [
        'fields' => ['Places.id', 'Places.name', 'Places.active'],
        'limit' => 25,
        'page' => 0,
        'order' => [
            'Places.name' => 'asc'
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
        $limit = $this->limit;
        if (isset($this->request->query['limit'])) {
            if (is_numeric($this->request->query['limit'])) {
                $limit = $this->request->query['limit'];
            }
        }

        if (isset($this->request->query['searchName'])) {
            $searchName = trim($this->request->query['searchName']);
            $this->checkExistence($searchName, $limit);
        } else {
            $page = 1;
            $offset = 0;
            if (isset($this->request->query['page'])) {
                if (is_numeric($this->request->query['page'])) {
                    $page = (int)$this->request->query['page'];
                    $offset = ($page - 1) * $limit;
                }
            }

            $query = '';
            if (isset($this->request->query['query'])) {
                if (!empty(trim($this->request->query['query']))) {
                    $query = trim($this->request->query['query']);
                }
            }

            $conditions = ['Places.active' => true];

            if (!empty(trim($query))) {
                $conditions['LOWER(Places.name) LIKE'] = '%' . strtolower($query) . '%';
            }

            $places = $this->Places->find()
                ->where($conditions)
                ->order(['Places.name' => 'ASC'])
                ->limit($limit)->page($page)->offset($offset)
                ->toArray();

            $allPlaces = $this->Places->find()->where($conditions);
            $total = $allPlaces->count();

            $meta = [
                'total' => $total
            ];
            $this->set([
                'places' => $places,
                'meta' => $meta,
                '_serialize' => ['places', 'meta']
            ]);
        }
    }

    public function checkExistence($name = null, $limit = 25)
    {
        $data = [
            [
                'id' => 0,
                'name' => '',
                'active' => 0
            ]
        ];

        $fetchDataOptions = [
            'order' => ['Places.name' => 'ASC'],
            'limit' => $limit
        ];

        $query = trim(strtolower($name));

        if (!empty($query)) {
            $fetchDataOptions['conditions']['LOWER(Places.name) LIKE'] = '%' . $query . '%';
        }

        $place = $this->Places->find('all', $fetchDataOptions);

        if ($place->count() > 0) {
            $data = $place;
        }

        $this->set([
            'place' => $data,
            '_serialize' => ['place']
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
        $place = $this->Places->get($id, [
            'contain' => []
        ]);
        $this->set('place', $place);
        $this->set('_serialize', ['place']);
    }

    /**
     * Add method
     *
     * @return void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $place = $this->Places->newEntity();
        if ($this->request->is('post')) {
            $place = $this->Places->patchEntity($place, $this->request->data);
            if ($this->Places->save($place)) {
                $this->Flash->success(__('The place has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The place could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('place'));
        $this->set('_serialize', ['place']);
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
        $place = $this->Places->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $place = $this->Places->patchEntity($place, $this->request->data);
            if ($this->Places->save($place)) {
                $this->Flash->success(__('The place has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The place could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('place'));
        $this->set('_serialize', ['place']);
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
            'place' => $place,
            '_serialize' => ['place']
        ]);
    }
}
