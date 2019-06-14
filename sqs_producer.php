<?php

require 'vendor/autoload.php';

use Aws\Sqs\SqsClient;

use Aws\Exception\AwsException;

$config = parse_ini_file('./config.ini');

$sqs = new Aws\Sqs\SqsClient([
    'version' => 'latest',
    'region' => $config['AWS_REGION'],
    'credentials' => [
        'key' => $config['AWS_ACCESS_KEY_ID'],
        'secret' => $config['AWS_SECRET_ACCESS_KEY']
    ]
]);

try{
    $result = $sqs->sendMessage([
        'MessageBody' => 'Sample Message',
        'QueueUrl' => $config['QUEUE_URL']
    ]);

    echo "Message Sent Successfully \n";
} catch(AwsException $e){
    echo $e->getAwsRequestId() . "\n";
    echo $e->getAwsErrorType() . "\n";
    echo $e->getAwsErrorCode() . "\n"; 
}

?>