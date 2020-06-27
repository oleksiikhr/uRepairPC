<?php declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ManifestJsonResource extends JsonResource
{
    /**
     * @inheritDoc
     */
    public function toArray($request): array
    {
        return [
            'name' => $this['name'],
            'short_name' => $this['short_name'],
            'start_url' => '/',
            'orientation' => $this['orientation'],
            'display' => $this['display'],
            'background_color' => $this['background_color'],
            'theme_color' => $this['theme_color'],
            'icons' => collect($this['icons'])->map(static function ($item) {
                $item->src = url('storage/'.$item->src);

                return $item;
            }),
        ];
    }
}
