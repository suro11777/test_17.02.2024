<?php

namespace App\Services\Admin;

use App\Models\Product;
use Faker\Core\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ProductService extends BaseService
{

    /**
     * @param $search
     * @param $filters
     * @return mixed
     */
    public function getAll($search, $filters)
    {
        return Product::when(!empty($search), function ($q) use ($search) {
            $q->where('title', 'like', '%' . $search . '%');
        })
            ->when(!empty($filters), function ($q) use ($filters) {
                foreach ($filters as $column => $filter) {
                    if ($column == 'price') {
                        $q->whereBetween($column, [$filter[0], $filter[1]]);
                    } else {
                        $q->where($column, $filter);
                    }
                }
            })
            ->paginate('15', [
                'id',
                'category_id',
                'title',
                'description',
                'price',
            ]);
    }

    /**
     * @param $data
     * @return bool
     */
    public function store($data)
    {
        try {
            DB::beginTransaction();
            $product = Product::create($data);
            $allImagesPaths = [];
            if (!empty($data['images'])) {
                foreach ($data['images'] as $image) {
                    $filename = $image->getClientOriginalName();
                    $image->move(storage_path('app/public/products'), $filename);
                    $allImagesPaths[]['path'] = asset('storage/products') . '/' . $filename;
                }

            }
            $product->product_images()->createMany($allImagesPaths);
            DB::commit();
            return $product;
        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error($exception->getMessage() . $exception->getFile() . $exception->getLine());
            return false;
        }

    }


    /**
     * @param $product
     * @param $data
     * @return mixed
     */
    public function update($product, $data)
    {
        try {
            DB::beginTransaction();
            $product->update($data);
            $allImagesPaths = [];
            if (!empty($data['images'])) {
                foreach ($data['images'] as $image) {
                    if (!is_string($image)) {
                        $filename = $image->getClientOriginalName();
                        $image->move(storage_path('app/public/products'), $filename);
                        $allImagesPaths[]['path'] = asset('storage/products') . '/' . $filename;
                    } else {
                        $allImagesPaths[]['path'] = $image;
                    }
                }

            }
            $product->product_images()->delete();
            $product->product_images()->createMany($allImagesPaths);
            DB::commit();
            return $product;
        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error($exception->getMessage() . $exception->getFile() . $exception->getLine());
            return false;
        }

    }


}
