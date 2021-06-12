<?php

namespace App\Models;

use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\File;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class BlueprintOption extends Model implements TranslatableContract, HasMedia
{
    use Translatable;
    use InteractsWithMedia;

    public $translatedAttributes = ['data'];
    public $fillable = ['key', 'blueprint'];

    public $timestamps = false;

    public function getFirstMediaById(int $id): Media
    {
        $media = $this->getMedia('blueprint-media')->where('id', $id);

        if (!$media instanceof Collection || $media->count() === 0) {
            throw new \InvalidArgumentException("V tabulce 'media' nebyla nalezena položka s
                collection_name = 'blueprint-media' a id = '{$id}'");
        }

        return $media->first();
    }

    public function getMediaUrlById(int $id)
    {
        try {
            $media = $this->getFirstMediaById($id);
        } catch (\InvalidArgumentException $e) {
            return '/galerie/photo_not_available.jpg';
        }

        return $media->getUrl();
    }


    /**
     * Nahraje yaml blueprint schema - tedy yaml vrati jako array
     */
    public function getBlueprintSchemaAttribute()
    {
        return \Symfony\Component\Yaml\Yaml::parse(
            File::get(resource_path('blueprints/' . $this->blueprint . '.yaml'))
        );
    }

    /**
     * Vraci blueprint yaml schema a doplni jej o realne values ulozene v DB.
     * Take o 'editable' boolean hodnotu - jestli se smi policko editovat (pro cizi jazyky se nektere veci neprekladaji).
     */
    public function getBlueprintDataAttribute()
    {
        $blueprintData = $this->blueprint_schema;

        foreach ($blueprintData as $key => $settings) {
            $blueprintData[$key]['editable'] = App::isLocale('cs') ?: !empty($blueprintData[$key]['localizable']);
        }

        foreach ($blueprintData as $key => $settings) {
            /**
             * Priorita nastaveni value pro polozku:
             * 1. Pokud existuje jiz ulozena hodnota v DB
             * 2. Pokud pole neni editovatelne (coz znamena, ze nejsme v cz jazyce), tak vezmi hodnotu z cz jazyka
             * 3. Vem defaultni hodnotu
             * 4. Prazdny retezec
             */
            $blueprintData[$key]['value'] = $this->data[$key] ?? $this->getDefaultOptionValue($blueprintData, $key);
        }

        return $blueprintData;
    }

    private function getDefaultOptionValue($blueprintData, $key)
    {
        if (!$blueprintData[$key]['editable'] && isset($this->translate('cs')->data[$key])) {
            return $this->translate('cs')->data[$key];
        }

        return $blueprintData[$key]['default'] ?? '';
    }

    /**
     * Vraci jednoduche pole ['yaml_klic' => 'hodnota | nebo Media objekt']
     * Pro jednoduchy vypis na frontendu.
     */
    public function getFrontendDataAttribute()
    {
        $blueprintData = $this->blueprint_data;

        $frontendData = [];
        foreach ($blueprintData as $key => $settings) {
            if ($blueprintData[$key]['type'] === 'file' && is_int($blueprintData[$key]['value'])) {
                $frontendData[$key] = $this->getFirstMediaById($blueprintData[$key]['value']);
            } else {
                $frontendData[$key] = $blueprintData[$key]['value'];
            }
        }

        return $frontendData;
    }

    public function registerMediaCollections(): void
    {
        $this
            ->addMediaCollection('blueprint-media')
            ->useDisk('blueprintMedia')
            ->registerMediaConversions(function (Media $media) {
                $this
                    ->addMediaConversion('thumb')
                    ->width(250)
                    ->height(250);
            });
    }
}
