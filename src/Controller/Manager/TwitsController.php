<?php
namespace App\Controller\Manager;

use App\Controller\Manager\AppController;
use TwitterAPIExchange;

/**
 * Twits Controller
 *
 * @property \App\Controller\Component\TwitterComponent $Twitter
 */
class TwitsController extends AppController
{
    public $settingsTwitter = [
        'oauth_access_token' => '3517023912-KGWVbjoOBsz25wYk5fnLe59dtlsmVV1pOpcmrwI',
        'oauth_access_token_secret' => 'Pm1YHAkmmtYoxGcaJDSysvP4pX2yMM3SumqUmXrGCIrJQ',
        'consumer_key' => 'Wt34i2M5mY3Kf3KnlVGciFJ86',
        'consumer_secret' => 'KSQROhO6H7rw6niibW95z8mSEw7TaKNs2HzWKtvsClSTFh6oGH'
    ];

    private $baseTwitterUrl = 'https://api.twitter.com/1.1/';

    public function mention($since_id = null, $count = 800)
    {
        $Twitter = new TwitterAPIExchange($this->settingsTwitter);

        $url = $this->baseTwitterUrl . 'statuses/mentions_timeline.json';
        $getfield = '?count=' . $count;
        if ($since_id !== null) {
            $getfield = $getfield . '&since_id=' . $since_id;
        }
        $requestMethod = 'GET';

        $data = $Twitter->setGetfield($getfield)
            ->buildOauth($url, $requestMethod)
            ->performRequest();

        $data = json_decode($data);
        $meta = [
            'total' => count($data)
        ];

        $this->set([
            'mentions' => $data,
            'meta' => $meta,
            '_serialize' => ['mentions', 'meta']
        ]);
    }

    private function getMention($since_id = null, $count = 800)
    {
        $Twitter = new TwitterAPIExchange($this->settingsTwitter);

        $url = $this->baseTwitterUrl . 'statuses/mentions_timeline.json';
        $getfield = '?count=' . $count;
        if ($since_id !== null) {
            $getfield = $getfield . '&since_id=' . $since_id;
        }
        $requestMethod = 'GET';

        $data = $Twitter->setGetfield($getfield)
            ->buildOauth($url, $requestMethod)
            ->performRequest();

        return json_decode($data);
    }

    public function mentionToDB()
    {
        // first get the latest twitID from DB
    }

    /**
     * Index method
     *
     * @return void
     */
    public function index()
    {
        $this->set('twits', $this->paginate($this->Twits));
        $this->set('_serialize', ['twits']);
    }

    /**
     * View method
     *
     * @param string|null $id Twit id.
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function view($id = null)
    {
        $twit = $this->Twits->get($id, [
            'contain' => []
        ]);
        $this->set('twit', $twit);
        $this->set('_serialize', ['twit']);
    }

    /**
     * Add method
     *
     * @return void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $twit = $this->Twits->newEntity();
        if ($this->request->is('post')) {
            $twit = $this->Twits->patchEntity($twit, $this->request->data);
            if ($this->Twits->save($twit)) {
                $this->Flash->success(__('The twit has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The twit could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('twit'));
        $this->set('_serialize', ['twit']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Twit id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $twit = $this->Twits->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $twit = $this->Twits->patchEntity($twit, $this->request->data);
            if ($this->Twits->save($twit)) {
                $this->Flash->success(__('The twit has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The twit could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('twit'));
        $this->set('_serialize', ['twit']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Twit id.
     * @return void Redirects to index.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $twit = $this->Twits->get($id);
        if ($this->Twits->delete($twit)) {
            $this->Flash->success(__('The twit has been deleted.'));
        } else {
            $this->Flash->error(__('The twit could not be deleted. Please, try again.'));
        }
        return $this->redirect(['action' => 'index']);
    }
}
