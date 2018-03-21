<?php
namespace App\Shell;

use Cake\Console\ConsoleOptionParser;
use Cake\Console\Shell;
use Cake\Log\Log;
use Psy\Shell as PsyShell;

class MasterShell extends Shell
{

    public function initialize()
	{
		parent::initialize();
		$this->loadModel('Stocks');
        $this->loadModel('Trades');
        $this->loadModel('Files');
	}

    public function main()
    {
        $this->out('fuckoff');
    }

    public function master()
    {
        $stocks = $this->getStocksList();
        foreach ($stocks as $key=>$value) {
            $date = date('Ymd');
            $date = '20170822';
            $stock_info = $this->getStockInfo($date, $value);
            if (!$this->stockScrapedToday($date, $key) && strpos($stock_info, $date) !== false) {
                if (strlen($stock_info) > 100) {
                    $file_info = $this->saveResultAsCsvFile($stock_info, $value, $date);
                    if ($file_info) {
                        $this->saveResultToDatabase($key, $date, $file_info);
                    }
                } else {
                    echo "Avoid scraping stock id $key from $date";
                }
            } else {
                echo "Stock id $key from $date is already scraped today";
            }
        } 
    }

    public function individual($name)
    {
        $stocks = $this->getIndividulStocksList($name);
        foreach ($stocks as $key=>$value) {
            $date = date('Ymd');
            $stock_info = $this->getStockInfo($date, $value);
            if (!$this->stockScrapedToday($date, $key) && strpos($stock_info, $date) !== false) {
                //pr($stock_info);
                if ($stock_info) {
                    $file_info = $this->saveResultAsCsvFile($stock_info, $value, $date);
                    if ($file_info) {
                        $this->saveResultToDatabase($key, $date, $file_info);
                    }
                }
            }
        } 
    }

    public function specific($name, $date)
    {
        $stocks = $this->getIndividulStocksList($name);
        foreach ($stocks as $key=>$value) {
            $stock_info = $this->getStockInfo($date, $value);
            if (!$this->stockScrapedToday($date, $key) && strpos($stock_info, $date) !== false) {
                if ($stock_info) {
                    $file_info = $this->saveResultAsCsvFile($stock_info, $value, $date);
                    if ($file_info) {
                        $this->saveResultToDatabase($key, $date, $file_info);
                    }
                }
            }
        } 
    }

    private function getStockInfo($date, $stock_name)
    {
        $stock_url = 'http://hopey.netfonds.no/tradedump.php?date='.$date.'&paper='.$stock_name.'&csv_format=csv';
        $curl = curl_init($stock_url);
        curl_setopt($curl, CURLOPT_FAILONERROR, true);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);  
        $result = curl_exec($curl); 
        return $result;
    }

    private function saveResultAsCsvFile($stock_info, $stock_name, $date)
    {
        $this->saveBidSizeFile($stock_name, $date);
        $file_name = $stock_name . '-' . $date . '-new.csv';
        $file = WWW_ROOT . 'stocks/' . $file_name;
        if (file_put_contents($file, $stock_info, LOCK_EX)) {
            $file_id = $this->saveFileEntry($file_name);
            return array('file_name' => $file_name, 'file_id' => $file_id);
        }
        
        return null;
    }

    private function saveBidSizeFile($stock_name, $date)
    {
        $stock_url = 'http://hopey.netfonds.no/posdump.php?date='.$date.'&paper='.$stock_name.'&csv_format=csv';
        $curl = curl_init($stock_url);
        curl_setopt($curl, CURLOPT_FAILONERROR, true);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);  
        $result = curl_exec($curl); 
        $file_name = $stock_name . '-' . $date . '-posdump.csv';
        $file = WWW_ROOT . 'stocks/' . $file_name;
        file_put_contents($file, $result, LOCK_EX);
        return null;
    }

    private function saveResultToDatabase($stock_id, $date, $file_info)
    {
        $results = array_map('str_getcsv', file(WWW_ROOT . 'stocks/' . $file_info['file_name']));
        $k = 0;
        foreach ($results as $result) {
            if ($k == 0) {
                $k++;
            } else {
                $trade = $this->Trades->newEntity();
                $trade->stock_id    = $stock_id;
                $trade->scrap_date  = $date;
                $trade->file_id     = $file_info['file_id'];
                $trade->time        = $result[0];
                $trade->price       = $result[1];
                $trade->quantity    = $result[2];
                $trade->source      = $result[3];
                $trade->buyer       = $result[4];
                $trade->seller      = $result[5];
                $trade->initiator   = $result[6];

                $this->Trades->save($trade);
            }
        }
    }

    private function getStocksList()
    {
        $stocks = $this->Stocks->find('list');
    	$stocks = $stocks->toArray();        
        return $stocks;
    }

    private function getIndividulStocksList($name)
    {
        $stocks = $this->Stocks->find('list', array(
            'conditions' => array('Stocks.name' => $name)
        ));
        $stocks = $stocks->toArray();
        return $stocks;
    }

    private function stockScrapedToday($date, $key)
    {
        $trades = $this->Trades->find('list', array(
            'conditions' => array(
                'Trades.scrap_date' => date('Y-m-d', strtotime($date)),
                'Trades.stock_id' => $key
            )
        ));
        $trades = $trades->toArray();
        return ($trades) ? true : false;
    }

    private function saveFileEntry($name)
    {
        $file = $this->Files->newEntity();
        $file->name = $name;
        $file->date = date('Ymd');
        $result = $this->Files->save($file);
        return $result->id;
    }
}
