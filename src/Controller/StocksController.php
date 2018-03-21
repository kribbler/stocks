<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * Stocks Controller
 *
 * @property \App\Model\Table\StocksTable $Stocks
 */
class StocksController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
        $stocks = $this->paginate($this->Stocks);

        $this->set(compact('stocks'));
        $this->set('_serialize', ['stocks']);
    }

    /**
     * View method
     *
     * @param string|null $id Stock id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $stock = $this->Stocks->get($id, [
            'contain' => []
        ]);

        $this->set('stock', $stock);
        $this->set('_serialize', ['stock']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $stock = $this->Stocks->newEntity();
        if ($this->request->is('post')) {
            $stock = $this->Stocks->patchEntity($stock, $this->request->getData());
            if ($this->Stocks->save($stock)) {
                $this->Flash->success(__('The stock has been saved.'));

                return $this->redirect('/stocks/');
            }
            $this->Flash->error(__('The stock could not be saved. Please, try again.'));
        }
        $this->set(compact('stock'));
        $this->set('_serialize', ['stock']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Stock id.
     * @return \Cake\Network\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $stock = $this->Stocks->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $stock = $this->Stocks->patchEntity($stock, $this->request->getData());
            if ($this->Stocks->save($stock)) {
                $this->Flash->success(__('The stock has been saved.'));

                return $this->redirect('/stocks/');
            }
            $this->Flash->error(__('The stock could not be saved. Please, try again.'));
        }
        $this->set(compact('stock'));
        $this->set('_serialize', ['stock']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Stock id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->deleteTradesByStockId($id);
        $this->request->allowMethod(['post', 'delete']);
        $stock = $this->Stocks->get($id);
        if ($this->Stocks->delete($stock)) {
            $this->Flash->success(__('The stock has been deleted.'));
        } else {
            $this->Flash->error(__('The stock could not be deleted. Please, try again.'));
        }

        return $this->redirect('/stocks/');
    }

    private function deleteFile($id)
    {
        $this->loadModel('Files');
        $files = $this->Files->find('list', array('conditions' => array(
            'Files.id' => $id
        )));
    	$files = $files->toArray(); 
        if ($files) {
            foreach ($files as $key => $value) {
                $file = $this->Files->get($key);
                $this->Files->delete($file);
            }
        }
    }

    private function deleteTradesByStockId($id)
    {
        $this->loadModel('Trades');
        $trades = $this->Trades->find('all', array('conditions' => array(
            'Trades.stock_id' => $id
        )));
    	$trades = $trades->toArray(); 
        $file_id = 0;
        if ($trades) {
            foreach ($trades as $trade) {
                $file_id = $trade['file_id'];
                $trade = $this->Trades->get($trade['id']);
                $this->Trades->delete($trade);
            }
        }
        $this->deleteFile($file_id);
    }
}
