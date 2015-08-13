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

    /**
     * Index method
     *
     * @return void
     */
    public function index()
    {
        $respondents = $this->Respondents->find('all', [
            'conditions' => ['Respondents.active' => true],
            'order' => ['Respondents.name' => 'ASC']
        ]);
        $this->set([
            'respondents' => $respondents,
            '_serialize' => ['respondents']
        ]);
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
}
