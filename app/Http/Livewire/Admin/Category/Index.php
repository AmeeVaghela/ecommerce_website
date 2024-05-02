<?php

namespace App\Http\Livewire\Admin\Category;

use App\Models\category;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Files;

class Index extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $category_id;





    public function render()
    {
        $categories = category::orderBy('id','DESC')->paginate(10);
        return view('livewire.admin.category.index',['categories'=> $categories]);
    }
}
