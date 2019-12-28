<?php
namespace Modules\Core\Interfaces;


interface DoctrineModelsInterface
{
    /**
     * set all data to Model
     * @param array $data
     * @return mixed
     */
    public function setData($data=[]);

    /**
     * convet model to array
     * @param array $data
     * @return array
     */
    public function toArray($data=[]);

    /**
     * get Model visible fields
     * @return array
     */
    public function getVisibleFields();

}