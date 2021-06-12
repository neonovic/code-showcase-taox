<?php

namespace App\Http\Livewire\Admin;

use App\Models\Category;
use App\Models\CategoryTranslation;
use Cviebrock\EloquentSluggable\Services\SlugService;
use Livewire\Component;

class TitleSluggable extends Component
{
    protected $rules = [
        'name' => 'required|string|min:3',
        'slug' => 'required|string|min:3',
    ];

    protected $messages = [
        'name.required' => 'Název je potřeba vyplnit.',
        'name.min' => 'Název je příliš krátký. Zadejte alespoň 3 znaky.',
        'slug.required' => 'Url odkaz je potřeba vyplnit.',
        'slug.min' => 'Url odkaz je příliš krátký. Zadejte alespoň 3 znaky.',
    ];

    public $name;
    public $slug;
    public $placeholderName = '';
    public $placeholderSlug = '';

    public bool $allowAutomaticSlugGeneration = false;

    public function mount(Category $category)
    {
        $this->name = old('name', $category->name);
        $this->slug = old('slug', $category->slug);

        if (empty($this->slug)) {
            $this->allowAutomaticSlugGeneration = true;
        }

        if (empty($this->name) && !app()->isLocale('cs')) {
            $this->placeholderName = $category->translate('cs')->name;
            $this->placeholderSlug = $category->translate('cs')->slug;
        }
    }

    public function updated($field, $newValue)
    {
        if ($field === 'slug' && empty($newValue)) {
            /**
             * Musim zde dat tuto vyjimku na validaci prazdneho slugu. Protoze kdyby nebyla, tak se mi nespousti updateProductSlug,
             * ve ktere chci aby se v empty stavu slugu znovu povolilo jeho automaticke generovani.
             * Validace na empty stav by zde vyhodila error a dal by se to neprobublalo.
             */
            return;
        }

        $this->validateOnly($field);
    }

    public function updatedName()
    {
        if ($this->allowAutomaticSlugGeneration) {
            $this->slug = SlugService::createSlug(CategoryTranslation::class, 'slug', $this->name);
            $this->validateOnly('slug');
        }
    }

    public function updatedSlug()
    {
        // Jakmile se sahlo na slug rucne, tak jej jiz automaticky negenerovat z title.
        $this->allowAutomaticSlugGeneration = false;

        // Lze ovsem opet vyresetovat tim, ze slug uplne smazu.
        if (empty($this->slug)) {
            $this->slug = SlugService::createSlug(CategoryTranslation::class, 'slug', $this->name);
            $this->allowAutomaticSlugGeneration = true;
        }
    }

    public function render()
    {
        return view('livewire.admin.title-sluggable');
    }
}
