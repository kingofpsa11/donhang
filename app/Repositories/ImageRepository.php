<?php
/**
 * Created by PhpStorm.
 * User: HA
 * Date: 12/08/2019
 * Time: 09:58
 */

namespace App\Repositories;

use App\Image;
use App\Store;
use Illuminate\Support\Facades\Storage;

class ImageRepository
{
    protected $image;

    public function __construct(Image $image)
    {
        $this->image = $image;
    }

    public function find($id)
    {
        return $this->image->find($id);
    }

    public function create($attributes, $id)
    {
        foreach ($attributes as $file) {
            $link = $file->storeAs('uploads', $file->getClientOriginalName());
            $this->image->create([
                'link' => $link,
                'name' => $file->getClientOriginalName(),
                'product_id' => $id
            ]);
        }
    }

    public function delete($id)
    {
        $image = $this->image->find($id);
        Storage::delete($image->link);
        return $image->delete();
    }
}