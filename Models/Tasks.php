<?php
namespace Models;


use Interfaces\ConfigInterface;
use Models\DataStructures\FindSetup;
use Models\DataStructures\InsertSetup;

class Tasks {

    /**
     * User-specific Config Object
     * @var string
     */
    protected $config;

    public function __construct(ConfigInterface $config)
    {
        $this->config = $config;
    }

    /**
     * Method should prepare FindSetup structure from raw user info $params
     * @param array $params
     * @return FindSetup
     */
    public static function getFindSetup($params=[])
    {
        $findSetup = new FindSetup();
        $findSetup->setFields(['*']);
        //$findSetup->andToWhere();
        return $findSetup;
    }

    /**
    * Method should prepare InsertSetup structure from raw user info $params
    * We have to remember the structureof task documents:
     * [
     *      userPwd: somestring     - identifier of a user, to whom this document belongs
     *      category: catname       - string category to which document belongs
     *      cdate: 1234556632       - unixtime (in milliseconds) of document creation
     *      state: 'new'            - string current state of a document
     *      actual_from: 1234556632 - unixtime (in milliseconds) of task actuality start
     *      actual_to: 1234556632   - unixtime (in milliseconds) of task actuality end
     *      header: 'important task'- string with name of the task
     *      body: 'full description'- string with full description of a task
     *      tags: [tag, tag2, tag3] - array of tags for this task
     *      ..... various other fields. We are schema-less, aren't we?
     * ]
    * @param array $params
    * @return InsertSetup
    */
    public function getInsertSetup ($params=[])
    {
        $insertSetup = new InsertSetup();
        if (!isset($params['task']) || !is_array($params['task'])) throw new \InvalidArgumentException('New task\'s data was not found');

        $data = [];
        foreach ($params['task'] as $key=>$val){
            $data[$key] = $val;
        }

        $data['userPwd'] = $this->config->getUserPwd();
        if (!isset($data['category'])
            || !in_array($data['category'], $this->config->categories['name'])
        ) {
            $data['category'] = 'unsorted'; /// FIXME remove hardcode
        }
        if (!isset($data['cdate'])) {
            $data['cdate'] = time() * 1000;
        }
        $data['state'] = 'new';
        if (!isset($data['actual_from'])
            || !is_numeric($data['actual_from'])
            || intval($data['cdate']) > (intval($data['actual_from']))
        ) {
            $data['actual_from'] = $data['cdate'];
        }
        if (!isset($data['actual_to'])
            || !is_numeric($data['actual_to'])
            || intval($data['actual_from']) > (intval($data['actual_to']))
        ) {
            $data['actual_to'] = $data['actual_from'] + $this->config->categories['task_duration'];
        }
        if (!isset($data['header'])
            || !is_string($data['header'])
            || empty($data['header'])
        ) {
            $data['header'] = 'Please change this default name for something else';
        }
        if (!isset($data['body'])
            || !is_string($data['body'])
            || empty($data['body'])
        ) {
            $data['body'] = 'Please change this default description for something else';
        }
        if (!isset($data['tags'])
            || !is_array($data['tags'])
        ) {
            $data['tags'] = [];
        }


        foreach ($data as $key=>$val){
            $insertSetup->addField([$key=>$val]);
        }
        return $insertSetup;
    }
}