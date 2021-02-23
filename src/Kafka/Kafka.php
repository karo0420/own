<?php


namespace Karo\Own\Kafka;

class Kafka
{

    public $host;
    public $port;

    protected $topic;
    protected $producer;

    public function __construct($host, $port)
    {
        $this->host = $host;
        $this->port = $port;
        $this->producer = new RdKafka\Producer($this->confg());
    }

    public function confg() {
        $conf = new RdKafka\Conf();
        $conf->set('metadata.broker.list', $this->host.':'.$this->port);
        $conf->set('compression.type', 'snappy');
        $conf->set('log_level', LOG_DEBUG);
        $conf->set('debug', 'all');
        return $conf;
    }

    public function setTopic($topicName) {
        $this->topic = $topicName;
        return $this;
    }

    public function getTopic() {
        return $this->topic;
    }

    public function send($message, $key, $header) {
        $topic = $this->producer->newTopic($this->getTopic());
        $topic->produce(
            RD_KAFKA_PARTITION_UA,
            0,
            $this->buildPayload($message, $header),
            $key
        );
        $this->producer->poll(0);
        $this->flush();
    }

    public function flush($timeout = 10000) {
        $this->producer->flush($timeout);

    }

    public function buildPayload($message, $header) {
        return json_encode([
            'body'=> $message,
            'header'=> $header
        ]);
    }





}