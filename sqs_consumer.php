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
    $result = $sqs->receiveMessage([
        'MaxNumberOfMessages' => 1,
        'QueueUrl' => $config['QUEUE_URL']
    ]);
    if($result['Messages']){
        echo "Message Received Successfully \n";
        echo $result['Messages'][0]['Body']; 

        $result = $sqs->deleteMessage([
            'QueueUrl' => $config['QUEUE_URL'],
            'ReceiptHandle' => $result['Messages'][0]['ReceiptHandle']
        ]);
    } else{
        echo "No Message in Queue";
    }
    
} catch(AwsException $e){
    echo $e->getAwsRequestId() . "\n";
    echo $e->getAwsErrorType() . "\n";
    echo $e->getAwsErrorCode() . "\n"; 
}

?>