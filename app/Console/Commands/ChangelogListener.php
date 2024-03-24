<?php

namespace App\Console\Commands;

use App\Models\UserInformation;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ChangelogListener extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'changelog:listener';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will listening for something new in the changelog and store or update in our db';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        ini_set('max_execution_time', '60');
        $kafkaConfig = config('services.kafka');
        $conf = new \RdKafka\Conf();
        $conf->set('group.id', 'userInfoGroup');
        $conf->set('metadata.broker.list', $kafkaConfig['broker_ip']);
        $conf->set('auto.offset.reset', 'earliest');
        $conf->set('enable.partition.eof', 'true');
        $conf->set('message.max.bytes', 22000000);
        $conf->set('max.partition.fetch.bytes', 22000000);
        $consumer = new \RdKafka\KafkaConsumer($conf);
        $consumer->subscribe([$kafkaConfig['topic']]);
        $message = $consumer->consume(120*1000);
        switch ($message->err) {
            case RD_KAFKA_RESP_ERR_NO_ERROR:
                $data = json_decode($message->payload, true);
                $storeData = [
                    'user_id' => @$data['entry']['id'],
                    'email' => @$data['entry']['email'],
                    'first_name' => @$data['entry']['first_name'],
                    'last_name' => @$data['entry']['last_name'],
                    'profile_image' => @$data['entry']['profile_image'],
                ];
                UserInformation::updateOrCreate(['user_id' => $storeData['user_id']], $storeData);
                break;
            case RD_KAFKA_RESP_ERR__PARTITION_EOF:
                Log::info("No more messages; will wait for more");
                break;
            case RD_KAFKA_RESP_ERR__TIMED_OUT:
                Log::info("Timed out");
                break;
            default:
                throw new \Exception($message->errstr(), $message->err);
                break;
        }
        return Command::SUCCESS;
    }
}
