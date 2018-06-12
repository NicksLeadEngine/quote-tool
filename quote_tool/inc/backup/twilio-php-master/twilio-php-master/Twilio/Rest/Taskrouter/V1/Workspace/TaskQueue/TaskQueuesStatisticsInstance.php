<?php

/**
 * This code was generated by
 * \ / _    _  _|   _  _
 * | (_)\/(_)(_|\/| |(/_  v1.0.0
 * /       /
 */

namespace Twilio\Rest\Taskrouter\V1\Workspace\TaskQueue;

use Twilio\Exceptions\TwilioException;
use Twilio\InstanceResource;
use Twilio\Values;
use Twilio\Version;

/**
 * @property string accountSid
 * @property array cumulative
 * @property array realtime
 * @property string taskQueueSid
 * @property string workspaceSid
 */
class TaskQueuesStatisticsInstance extends InstanceResource {
    /**
     * Initialize the TaskQueuesStatisticsInstance
     * 
     * @param \Twilio\Version $version Version that contains the resource
     * @param mixed[] $payload The response payload
     * @param string $workspaceSid The workspace_sid
     * @return \Twilio\Rest\Taskrouter\V1\Workspace\TaskQueue\TaskQueuesStatisticsInstance 
     */
    public function __construct(Version $version, array $payload, $workspaceSid) {
        parent::__construct($version);

        // Marshaled Properties
        $this->properties = array(
            'accountSid' => Values::array_get($payload, 'account_sid'),
            'cumulative' => Values::array_get($payload, 'cumulative'),
            'realtime' => Values::array_get($payload, 'realtime'),
            'taskQueueSid' => Values::array_get($payload, 'task_queue_sid'),
            'workspaceSid' => Values::array_get($payload, 'workspace_sid'),
        );

        $this->solution = array('workspaceSid' => $workspaceSid, );
    }

    /**
     * Magic getter to access properties
     * 
     * @param string $name Property to access
     * @return mixed The requested property
     * @throws TwilioException For unknown properties
     */
    public function __get($name) {
        if (array_key_exists($name, $this->properties)) {
            return $this->properties[$name];
        }

        if (property_exists($this, '_' . $name)) {
            $method = 'get' . ucfirst($name);
            return $this->$method();
        }

        throw new TwilioException('Unknown property: ' . $name);
    }

    /**
     * Provide a friendly representation
     * 
     * @return string Machine friendly representation
     */
    public function __toString() {
        return '[Twilio.Taskrouter.V1.TaskQueuesStatisticsInstance]';
    }
}