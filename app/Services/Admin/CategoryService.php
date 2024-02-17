<?php

namespace App\Services\Admin;

use App\Models\Category;

class CategoryService extends BaseService
{

    /**
     * @param $data
     * @return bool
     */
    public function store($data)
    {
        return Category::create($data);
    }


    /**
     * @param $category
     * @param $data
     * @return mixed
     */
    public function update($category, $data)
    {
        $category->update($data);
        return $category;
    }


}
