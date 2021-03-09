<?php namespace App\Console\Commands;

use Illuminate\Console\Command;

# Imports the Google Cloud client library
use Google\Cloud\Speech\V1\SpeechClient;
use Google\Cloud\Speech\V1\RecognitionAudio;
use Google\Cloud\Speech\V1\RecognitionConfig;
use Google\Cloud\Speech\V1\RecognitionConfig\AudioEncoding;

class SttTest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gg-cloud:stt';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Sample speech to text";

    /**
     * Execute the console command.
     * @return mixed
     * @throws \Exception
     */
    public function handle()
    {
        $file = base_path('_vov000.mp3');
        $fileContent = file_get_contents($file);
        # set string as audio content
        $audio = (new RecognitionAudio())
            ->setContent($fileContent);


# The audio file's encoding, sample rate and language
        $config = new RecognitionConfig([
            'encoding' => AudioEncoding::ENCODING_UNSPECIFIED,
            'sample_rate_hertz' => 16000,
            'language_code' => 'vi-VN'
        ]);

# Instantiates a client
        $client = new SpeechClient();

# Detects speech in the audio file
        $response = $client->recognize($config, $audio);

# Print most likely transcription
        foreach ($response->getResults() as $result) {
            $alternatives = $result->getAlternatives();
            $mostLikely = $alternatives[0];
            $transcript = $mostLikely->getTranscript();
            printf('Transcript: %s' . PHP_EOL, $transcript);
        }

        $client->close();
    }

}
