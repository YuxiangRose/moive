<?php
namespace App\lib\Model;

use Illuminate\Support\Facades\Storage;

class ShowsAllViewModel
{
    public $shows;

    public $status;

    public $total;

    public function build($data)
    {
        foreach($data['shows'] as $show) {
            $show->poster_path =  Storage::url('poster'.$show->poster_path);
            $show->title = utf8_encode($show->title);
            $show->link = '/index.php/show-details/'.$show->id;
        }

        $this->shows = $data['shows'];
        $this->status = utf8_encode($data['status']);
        $this->total = count($data['shows']);

        return $this;
    }
}