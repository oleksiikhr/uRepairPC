<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ManifestJsonResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'name' => $this['name'],
            'short_name' => $this['short_name'],
            'start_url' => '/',
            'orientation' => $this['orientation'],
            'display' => $this['display'],
            'background_color' => $this['background_color'],
            'theme_color' => $this['theme_color'],
            'icons' => collect($this['icons'])->map(function ($item) {
                $item->src = url('storage/'.$item->src);

                return $item;
            }),
        ];
    }
}
