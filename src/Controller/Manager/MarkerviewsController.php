<?php
namespace App\Controller\Manager;

use App\Controller\Manager\AppController;

/**
 * Markerviews Controller
 *
 * @property \App\Model\Table\MarkerviewsTable $Markerviews
 */
//@todo refactor this class as a respondent template
class MarkerviewsController extends AppController
{
    public $limit = 25;

    public $paginate = [
        'fields' => ['Markerviews.id', 'Markerviews.name', 'Markerviews.active'],
        'limit' => 25,
        'page' => 0,
        'order' => [
            'Markerviews.name' => 'asc'
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

        $page = 1;
        $offset = 0;
        if (isset($this->request->query['page'])) {
            if (is_numeric($this->request->query['page'])) {
                $page = (int)$this->request->query['page'];
                $offset = ($page - 1) * $limit;
            }
        }

        $lastMinutes = 30;//default is past 30 minutes
        if (isset($this->request->query['lastminutes'])) {
            if (is_numeric($this->request->query['lastminutes'])) {
                $lastMinutes = $this->request->query['lastminutes'];
            }
        }
        $lastMinutesString = '-' . $lastMinutes . ' minutes';
        // query by place_name
        $query = '';
        if (isset($this->request->query['query'])) {
            if (!empty(trim($this->request->query['query']))) {
                $query = trim($this->request->query['query']);
            }
        }

        $conditions = [
            'Markerviews.active' => true,
            'OR' => [
                'Markerviews.created >=' => date('Y-m-d H:i:s', strtotime($lastMinutesString)),
                'AND' => [
                    'Markerviews.active' => true,
                    //    'Markerviews.pinned' => true,
                    //    'Markerviews.cleared' => false,
                ]
            ]
        ];

        if (!empty(trim($query))) {
            $conditions['LOWER(Markerviews.place_name) LIKE'] = '%' . strtolower($query) . '%';
        }

        $markerviews = $this->Markerviews->find()
            ->where($conditions)
            ->order(['Markerviews.created' => 'DESC'])
            ->limit($limit)->page($page)->offset($offset)
            ->toArray();
        $allMarkerviews = $this->Markerviews->find()->where($conditions);
        $total = $allMarkerviews->count();

        /*
         * for now, it disabled
         * $countMarkerviews = count($markerviews);
        for ($i = 0; $i < $countMarkerviews; $i++) {
            $markerviews[$i]['category'] = $markerviews[$i]['category_id'];
        }
        *
        */

        $meta = [
            'total' => $total
        ];
        $this->set([
            'markerviews' => $markerviews,
            'meta' => $meta,
            '_serialize' => ['markerviews', 'meta']
        ]);
    }

    /**
     * View method
     *
     * @param string|null $id Markerviewview id.
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function view($id = null)
    {
        $markerviewview = $this->Markerviews->get($id);
        $this->set([
            'markerviewview' => $markerviewview,
            '_serialize' => ['markerviewview']
        ]);
    }

    /**
     * Add method
     *
     * @return void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        if ($this->request->is('post')) {
            if (isset($this->request->data['markerviewview']['active'])) unset($this->request->data['markerviewview']['active']);
            $this->request->data['markerviewview']['active'] = true;

            $markerviewview = $this->Markerviews->newEntity($this->request->data['markerviewview']);
            $this->Markerviews->save($markerviewview);

            $this->set([
                'markerviewview' => $markerviewview,
                '_serialize' => ['markerviewview']
            ]);
        }
    }

    /**
     * Edit method
     *
     * @param string|null $id Markerviewview id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $markerviewview = $this->Markerviews->get($id);
        if ($this->request->is(['patch', 'post', 'put'])) {
            if (isset($this->request->data['markerviewview']['active'])) unset($this->request->data['markerviewview']['active']);

            $markerviewview = $this->Markerviews->patchEntity($markerviewview, $this->request->data['markerviewview']);
            if ($this->Markerviews->save($markerviewview)) {
                $message = 'Saved';
            } else {
                $message = 'Error';
            }
        }
        $this->set([
            'markerviewview' => $message,
            '_serialize' => ['markerviewview']
        ]);
    }

    /**
     * Delete method
     *
     * @param string|null $id Markerviewview id.
     * @return void Redirects to index.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $markerviewview = $this->Markerviews->get($id);
        if ($this->request->is(['delete'])) {
            $markerviewview->active = false;
            if ($this->Markerviews->save($markerviewview)) {
                $message = 'Deleted';
            } else {
                $message = 'Error';
            }
        }
        $this->set([
            'markerviewview' => $markerviewview,
            '_serialize' => ['markerviewview']
        ]);
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
            'order' => ['Markerviews.name' => 'ASC'],
            'limit' => $limit
        ];

        $query = trim(strtolower($name));

        if (!empty($query)) {
            $fetchDataOptions['conditions']['LOWER(Markerviews.name) LIKE'] = '%' . $query . '%';
        }

        $markerviewview = $this->Markerviews->find('all', $fetchDataOptions);

        if ($markerviewview->count() > 0) {
            $data = $markerviewview;
        }

        $this->set([
            'markerviewview' => $data,
            '_serialize' => ['markerviewview']
        ]);
    }
}
