<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Trades Controller
 *
 * @property \App\Model\Table\TradesTable $Trades
 */
class TradesController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Stocks', 'Files']
        ];
        $trades = $this->paginate($this->Trades);

        $this->set(compact('trades'));
        $this->set('_serialize', ['trades']);
    }

    /**
     * View method
     *
     * @param string|null $id Trade id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $trade = $this->Trades->get($id, [
            'contain' => ['Stocks', 'Files']
        ]);

        $this->set('trade', $trade);
        $this->set('_serialize', ['trade']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $trade = $this->Trades->newEntity();
        if ($this->request->is('post')) {
            $trade = $this->Trades->patchEntity($trade, $this->request->getData());
            if ($this->Trades->save($trade)) {
                $this->Flash->success(__('The trade has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The trade could not be saved. Please, try again.'));
        }
        $stocks = $this->Trades->Stocks->find('list', ['limit' => 200]);
        $this->set(compact('trade', 'stocks'));
        $this->set('_serialize', ['trade']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Trade id.
     * @return \Cake\Network\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $trade = $this->Trades->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $trade = $this->Trades->patchEntity($trade, $this->request->getData());
            if ($this->Trades->save($trade)) {
                $this->Flash->success(__('The trade has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The trade could not be saved. Please, try again.'));
        }
        $stocks = $this->Trades->Stocks->find('list', ['limit' => 200]);
        $this->set(compact('trade', 'stocks'));
        $this->set('_serialize', ['trade']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Trade id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $trade = $this->Trades->get($id);
        if ($this->Trades->delete($trade)) {
            $this->Flash->success(__('The trade has been deleted.'));
        } else {
            $this->Flash->error(__('The trade could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
