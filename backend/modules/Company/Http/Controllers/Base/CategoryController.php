<?php

namespace Modules\Company\Http\Controllers\Base;

use Modules\Core\Http\Controllers\BaseController;

class CategoryController extends BaseController
{
    protected $entityClassName = 'Modules\Company\Entities\Doctrine\Category';
    protected $validationRules = [
        'name' => 'required|unique:Modules\Company\Entities\Doctrine\Category,name|alpha_dash|min:3|max:255',
        'display_name' => 'required|string|min:1|max:255',
        'description' => 'nullable|string',
        'parent' => 'nullable|integer',
        'locale' => 'string|min:2|max:255',
        'type' => 'nullable|string'
    ];

    public function categoriesTree()
    {
        $result=[];$res=[];
        $categories = collect($this->repository->getAll())
            ->map(function($value){
                return $value->toArray();
            })
            ->sort(function($a, $b){
                return strcmp($a['parent'], $b['parent']);
            });

        foreach ($categories as $key=>$value){
            if (is_null($value['parent'])){
                $result[$value['id']]['id'] = $value['id'];
                $result[$value['id']]['name'] = $value['name'];
                $result[$value['id']]['display_name'] = $value['display_name'];
                $result[$value['id']]['type'] = $value['type'];
                $result[$value['id']]['child'] = [];
            }elseif(isset($result[$value['parent']])){
                $result[$value['parent']]['child'][] = [
                    'id' => $value['id'],
                    'name' => $value['name'],
                    'display_name' => $value['display_name'],
                    'type' => $value['type']
                ];
            }
        }
        foreach ($result as $value){
            $res[]=$value;
        }
        return $this->responseOkStatus('OK', 200, ['data'=>$res]);
    }
}
