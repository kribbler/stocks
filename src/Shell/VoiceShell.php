<?php
namespace App\Shell;

use Cake\Console\ConsoleOptionParser;
use Cake\Console\Shell;
use Cake\Log\Log;
use Psy\Shell as PsyShell;

class VoiceShell extends Shell
{

    public function initialize()
	{
		parent::initialize();
	}

    public function main($phrase)
    {
        $urls = array(
            array(
                'url' => 'https://www.shareville.se/api/v1/profiles/75853/stream?page=1',      // mavrick
                'id' => '75853',
                'name' => 'mavrick',
                'except' => array(
                    //'African Petroleum Corporation',
                    //'Bergen Group'
                )
            ),
            array(
                'url' => 'https://www.shareville.no/api/v1/profiles/1662/stream?page=1',      // n-invest
                'id' => '1662',
                'name' => 'n-invest',
                'except' => array(
                    //'African Petroleum Corporation',
                    //'Bergen Group'
                )
            ),
//            array(
//                'url' => 'https://www.shareville.se/api/v1/profiles/12401/stream?page=1',   // simenw92
//                'id' => '12401',
//                'name' => 'simenw92',
//                'except' => array(
                    //'African Petroleum Corporation',
                    //'Bergen Group'
//                )
//            ),
//            array(
//                'url' => 'https://www.shareville.no/api/v1/profiles/65913/stream?page=1',   // it4ever
//                'id' => '65913',
//                'name' => 'it4ever',
//                'except' => array(
                    //'African Petroleum Corporation',
                    //'Bergen Group'
//                )
//            ),
//            array(
//                'url' => 'https://www.shareville.no/api/v1/profiles/35218/stream?page=1',    // boffel00
//                'id' => '35218',
//                'name' => 'boffel00',
//                'except' => array(
//                    //'African Petroleum Corporation',
//                    //'Bergen Group'
//                )
//            ),
        );
        foreach ($urls as $to_process) {
            $data = $this->readUrl($to_process['id']);
            $process = $this->processData($data->results[0], $to_process);
            sleep(5);
        }
    }

    private function processData($data, $to_process)
    {
        $result = null;
        $new_entry = [];
        $new_entry['name'] = $data->object->instrument->name;
        $new_entry['price'] = $data->object->price;
        
        if ($data->kind == '8')
            $new_entry['kind'] = 'Buy';
        if ($data->kind == '9')
            $new_entry['kind'] = 'Sell';

        $new_entry['created_at'] = $data->created_at;
        $new_entry['p_name'] = $to_process['name'];
        
        $old_data = $this->readFromFile(json_encode($new_entry), $to_process['id']);
        //if ($old_data->name == $new_entry['name'] && $old_data->created_at != $new_entry['created_at']) {
        //var_dump($old_data);
        if (!in_array($new_entry['name'], $to_process['except'])) {
            if (@$old_data->created_at != $new_entry['created_at']) {
                $this->alertSystem($new_entry);
                $this->sendSMS($new_entry);
                $this->saveNewData($new_entry, $to_process['id']);
            } else if ($old_data->name != $new_entry['name']) {
                //$this->alertSystemNewEntry($new_entry);
                $this->saveNewData($new_entry, $to_process['id']);
            }
        }
    }

    private function sendSMS($new_entry)
    {
        //if ($new_entry['name'] == 'Bergen Group' || $new_entry['name'] == 'I.M. Skaugen') {
            $message = str_ireplace(" ", "%20", $new_entry['name']) . '%20/%20' . $new_entry['price'] . '%20/%20' . $new_entry['kind'] . '%20/%20' . $new_entry['p_name'];
            $url = "https://sveve.no/SMS/SendMessage?user=hagenmed&passwd=zugnj&to=92282815&msg=" . $message;
            $curl = curl_init($url);
            curl_setopt($curl, CURLOPT_FAILONERROR, true);
            curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);  
            $result = curl_exec($curl); 
            $file = $file = WWW_ROOT . 'sms.txt';
            $fp = fopen($file, 'a');
            fwrite($fp, json_encode($new_entry));
            fclose($fp);
            curl_close($curl);
        //}
    }

    private function alertSystem($new_entry)
    {
        $this->out('System alert! - Previous entry matches new one');
        $message = $new_entry['name'] . ' / ' . $new_entry['price'] . ' / ' . $new_entry['kind'] . ' / ' . $new_entry['p_name'] . ' / ' . $new_entry['created_at'];
        $to = 'kjetiljose@gmail.com,kjetiljhagen@gmail.com,kribbler@gmail.com';
        $headers = 'From: no-reply@osebx.tv0.no' . "\r\n" .
            'Reply-To: no-reply@osebx.tv0.no' . "\r\n" .
            'X-Mailer: PHP/' . phpversion();
        mail($to, 'System alert! - Previous entry matches new one', $message);
    }

    private function alertSystemNewEntry($new_entry)
    {
        $this->out('System alert! - Previous entry different than new one');
        $message = $new_entry['name'] . ' / ' . $new_entry['price'] . ' / ' . $new_entry['kind'] . ' / ' . $new_entry['p_name'] . $new_entry['created_at'];
        $to = ' kjetiljose@gmail.com,kjetiljhagen@gmail.com,kribbler@gmail.com';
        $headers = 'From: no-reply@osebx.tv0.no' . "\r\n" .
            'Reply-To: no-reply@osebx.tv0.no' . "\r\n" .
            'X-Mailer: PHP/' . phpversion();
        mail($to, 'System alert! - Previous entry different than new one', $message);
    }

    private function saveNewData($new_entry, $id)
    {
        $file = $file = WWW_ROOT . 'voice_' . $id . '.json';
        $fp = fopen($file, 'w');
        fwrite($fp, json_encode($new_entry));
        fclose($fp);
    }

    private function readFromFile($data, $id)
    {
        $file = $file = WWW_ROOT . 'voice_' . $id . '.json';
        if (!file_exists($file)) {
            $myfile = fopen($file, "w");
        } else {
            $myfile = fopen($file, "r");
        }
        @$content = fread($myfile,filesize($file));
        fclose($myfile);
        return json_decode($content);
    }

    private function readUrl($id) 
    {
        $stock_url = 'https://www.shareville.se/api/v1/profiles/'.$id.'/stream?page=1';
        $curl = curl_init($stock_url);
        curl_setopt($curl, CURLOPT_FAILONERROR, true);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);  
        $result = curl_exec($curl); 
        curl_close($curl);
        return json_decode($result);
    }

}