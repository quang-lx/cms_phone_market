<?php namespace App\Console\Commands;

use App\Models\CacheKey;
use Bluerhinos\phpMQTT;
use Illuminate\Console\Command;

# Imports the Google Cloud client library
use Google\Cloud\Speech\V1\SpeechClient;
use Google\Cloud\Speech\V1\RecognitionAudio;
use Google\Cloud\Speech\V1\RecognitionConfig;
use Google\Cloud\Speech\V1\RecognitionConfig\AudioEncoding;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;
use PhpMqtt\Client\MqttClient;

class SttVov extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gg-cloud:stt-vov';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Stt vov";

    public $server = 'localhost';     // change if necessary
    public $port = 1883;                     // change if necessary
    public $username = '';                   // set your username
    public $password = '';                   // set your password
    public $client_id = 'phpMQTT-publisher'; // make sure this is unique for connecting to sever - you could use uniqid()

    public function __construct()
    {
        parent::__construct();
        $this->server = env('MQTT_HOST', 'localhost');
        $this->port = env('MQTT_PORT', 1883);
        $this->client_id = uniqid();
    }

    /**
     * Execute the console command.
     * @return mixed
     * @throws \Exception
     */
    public function handle()
    {
        $log = Log::setDefaultDriver('log_stt');

        # The audio file's encoding, sample rate and language
        $config = new RecognitionConfig([
            'encoding' => AudioEncoding::ENCODING_UNSPECIFIED,
            'sample_rate_hertz' => 16000,
            'language_code' => 'vi-VN'
        ]);
        $client = new SpeechClient();
        $vovId = 1;


        while (true) {
            $path = '/data/audio';
            $files = scandir($path);
            foreach ($files as $file) {
                $cacheKey = sprintf(CacheKey::VOV, $vovId, '*');
                // neu co ng nghe
                try {
                    if (!in_array($file, array(".", ".."))) {
                        if (!is_dir($path . DIRECTORY_SEPARATOR . $file)) {

                            Log::info($path . DIRECTORY_SEPARATOR . $file);
                            // xoa file cach thoi gian hien tai 2 phut
//                            if (filemtime($path . DIRECTORY_SEPARATOR . $file) < time()- 120) {
//                                unlink($path . DIRECTORY_SEPARATOR . $file);
//                                continue;
//                            }
                            if (filesize($path . DIRECTORY_SEPARATOR . $file)) {
                                if (count(Redis::keys($cacheKey)) || true) {

                                    $fileContent = file_get_contents($path . DIRECTORY_SEPARATOR . $file);
                                    # set string as audio content
                                    $audio = (new RecognitionAudio())
                                        ->setContent($fileContent);


                                    # Detects speech in the audio file
                                    $response = $client->recognize($config, $audio);

                                    # Print most likely transcription
                                    foreach ($response->getResults() as $key => $result) {
                                        $mqtt = new  MqttClient($this->server, $this->port, $this->client_id);
                                        $mqtt->connect();

                                        $alternatives = $result->getAlternatives();
                                        $mostLikely = $alternatives[0];
                                        $transcript = $mostLikely->getTranscript();
                                        Log::info('STT Trans', ['content' => $transcript]);
                                        $mqtt->publish(sprintf('vov_%s', $vovId), json_encode(['id' => $vovId, 'content' => $transcript], JSON_UNESCAPED_UNICODE), 0);
                                        Log::info('MQTT PUB', ['id' => $vovId, 'content' => $transcript]);


                                        $mqtt->disconnect();

                                    }
                                    sleep(1);
                                    unlink($path . DIRECTORY_SEPARATOR . $file);

                                }else {
                                    unlink($path . DIRECTORY_SEPARATOR . $file);
                                }

                            }


                        }

                    }
                } catch (\Exception $exception) {
                    Log::info('STT Error', ['error' => $exception->getMessage()]);

                }



            }
            sleep(3);
        }

        $client->close();


    }

}
