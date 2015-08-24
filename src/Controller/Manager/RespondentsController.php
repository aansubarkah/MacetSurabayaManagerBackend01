<?php
namespace App\Controller\Manager;

use App\Controller\Manager\AppController;

/**
 * Respondents Controller
 *
 * @property \App\Model\Table\RespondentsTable $Respondents
 */
class RespondentsController extends AppController
{
    public $limit = 25;

    public $paginate = [
        'fields' => ['Respondents.id', 'Respondents.name', 'Respondents.active'],
        'limit' => 25,
        'page' => 0,
        'order' => [
            'Respondents.name' => 'asc'
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
            $offset = 0;
            if (isset($this->request->query['page'])) {
                if (is_numeric($this->request->query['page'])) {
                    $offset = $this->request->query['page'] - 1;
                }
            }

            $query = '';
            if (isset($this->request->query['query'])) {
                if (!empty(trim($this->request->query['query']))) {
                    $query = trim($this->request->query['query']);
                }
            }

            $fetchDataOptions = [
                'conditions' => ['Respondents.active' => true],
                'order' => ['Respondents.name' => 'ASC'],
                'limit' => $limit,
                'page' => $offset
            ];

            if (!empty(trim($query))) {
                $fetchDataOptions['conditions']['LOWER(Respondents.name) LIKE'] = '%' . strtolower($query) . '%';
            }

            $this->paginate = $fetchDataOptions;
            $respondents = $this->paginate('Respondents');

            $allRespondents = $this->Respondents->find('all', $fetchDataOptions);
            $total = $allRespondents->count();

            $meta = [
                'total' => $total
            ];
            $this->set([
                'respondents' => $respondents,
                'meta' => $meta,
                '_serialize' => ['respondents', 'meta']
            ]);
        }
    }

    /**
     * View method
     *
     * @param string|null $id Respondent id.
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function view($id = null)
    {
        $respondent = $this->Respondents->get($id);
        $this->set([
            'respondent' => $respondent,
            '_serialize' => ['respondent']
        ]);
    }

    /**
     * Add method
     *
     * @return void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        if(isset($this->request->data['respondent']['active'])) unset($this->request->data['respondent']['active']);

        $respondent = $this->Respondents->newEntity($this->request->data['respondent']);
        if ($this->request->is('post')) {
            if ($this->Respondents->save($respondent)) {
                $message = 'Saved';
            } else {
                $message = 'Error';
            }
        }
        $this->set([
            'respondent' => $message,
            '_serialize' => ['respondent']
        ]);
    }

    /**
     * Edit method
     *
     * @param string|null $id Respondent id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $respondent = $this->Respondents->get($id);
        if ($this->request->is(['patch', 'post', 'put'])) {
            if(isset($this->request->data['respondent']['active'])) unset($this->request->data['respondent']['active']);

            $respondent = $this->Respondents->patchEntity($respondent, $this->request->data['respondent']);
            if ($this->Respondents->save($respondent)) {
                $message = 'Saved';
            } else {
                $message = 'Error';
            }
        }
        $this->set([
            'respondent' => $message,
            '_serialize' => ['respondent']
        ]);
    }

    /**
     * Delete method
     *
     * @param string|null $id Respondent id.
     * @return void Redirects to index.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $respondent = $this->Respondents->get($id);
        if ($this->request->is(['delete'])) {
            $respondent->active = false;
            if ($this->Respondents->save($respondent)) {
                $message = 'Deleted';
            } else {
                $message = 'Error';
            }
        }
        $this->set([
            'respondent' => $message,
            '_serialize' => ['respondent']
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
            'order' => ['Respondents.name' => 'ASC'],
            'limit' => $limit
        ];

        $query = trim(strtolower($name));

        if (!empty($query)) {
            $fetchDataOptions['conditions']['LOWER(Respondents.name) LIKE'] = '%' . $query . '%';
        }

        $respondent = $this->Respondents->find('all', $fetchDataOptions);

        if ($respondent->count() > 0) {
            $data = $respondent;
        }

        $this->set([
            'respondent' => $data,
            '_serialize' => ['respondent']
        ]);
    }
}
