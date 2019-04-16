<?php

namespace App\Http\Controllers;

use Backpack\CRUD\app\Http\Controllers\CrudController;

// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\ProductsRequest as StoreRequest;
use App\Http\Requests\ProductsRequest as UpdateRequest;

/**
 * Class ProductsCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
class ProductsCrudController extends CrudController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Http\Model\Products');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/products');
        $this->crud->setEntityNameStrings('products', 'products');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
//        $this->crud->setFromDb();
        $this->crud->addFields([
            ['name'  => 'product_name', // DB column name (will also be the name of the input)
                'label' => 'Tên sản phẩm', // the human-readable label for the input
                'type'  => 'text'],
            ['name'  => 'p_image', // DB column name (will also be the name of the input)
                'label' => 'Hình ảnh sản phẩm', // the human-readable label for the input
                'upload' => true,
                'type'=>'image',
                'crop' => false, // set to true to allow cropping, false to disable
                'aspect_ratio' => 0, // ommit or set to 0 to allow any aspect ratio
                'prefix' => ''],
            ['name'  => 'type_id', // DB column name (will also be the name of the input)
                'label' => 'Loại sản phẩm', // the human-readable label for the input
                'type'  => 'select2',
                'entity'=>'ProductType',
                'attribute'=>'product_type_name',
                'model'=>'App\Http\Model\ProductType'],
            ['name'  => 'price_origin', // DB column name (will also be the name of the input)
                'label' => 'Giá gốc', // the human-readable label for the input
                'type'  => 'number',
                'attributes' => ["step" => "any"],
                'suffix' => "VNĐ"],
            ['name'  => 'price', // DB column name (will also be the name of the input)
                'label' => 'Giá bán', // the human-readable label for the input
                'type'  => 'number',
                'attributes' => ["step" => "any"],
                'suffix' => "VNĐ"],
            ['name'  => 'p_quantity', // DB column name (will also be the name of the input)
                'label' => 'Số lượng Giá bán', // the human-readable label for the input
                'type'  => 'number'],
        ]);


        $this->crud->addColumns([
            ['name'  => 'product_name', // DB column name (will also be the name of the input)
                'label' => 'Tên sản phẩm', // the human-readable label for the input
                'type'  => 'text'],
            ['name'  => 'p_image', // DB column name (will also be the name of the input)
                'label' => 'Hình ảnh sản phẩm', // the human-readable label for the input
                'type'  => 'my-image',
                'prefix'=>'',
                'width'=>'100px',
                'height'=>'150px'],
            ['name'  => 'type_id', // DB column name (will also be the name of the input)
                'label' => 'Loại sản phẩm', // the human-readable label for the input
                'type'  => 'select',
                'entity'=>'ProductType',
                'attribute'=>'product_type_name',
                'model'=>'App\Http\Model\ProductType'],
            ['name'  => 'price_origin', // DB column name (will also be the name of the input)
                'label' => 'Giá gốc', // the human-readable label for the input
                'type'  => 'number',
                'suffix' => " VND"],
            ['name'  => 'price', // DB column name (will also be the name of the input)
                'label' => 'Giá bán', // the human-readable label for the input
                'type'  => 'number',
                'suffix' => " VND"],
            ['name'  => 'p_quantity', // DB column name (will also be the name of the input)
                'label' => 'Số lượng', // the human-readable label for the input
                'type'  => 'number'],
            ['name'  => 'created_at', // DB column name (will also be the name of the input)
                'label' => 'Ngày tạo', // the human-readable label for the input
                'type'  => 'text'],
            ['name'  => 'updated_at', // DB column name (will also be the name of the input)
                'label' => 'Ngày cập nhật', // the human-readable label for the input
                'type'  => 'text']
        ]);


        // add asterisk for fields that are required in ProductsRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
    }

    public function store(StoreRequest $request)
    {
        // your additional operations before save here
        $redirect_location = parent::storeCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        return $redirect_location;
    }

    public function update(UpdateRequest $request)
    {
        // your additional operations before save here
        $redirect_location = parent::updateCrud($request);
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        return $redirect_location;
    }
}
