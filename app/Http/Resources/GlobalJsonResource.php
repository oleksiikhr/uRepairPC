<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class GlobalJsonResource extends JsonResource
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
            'meta_title' => $this['meta_title'],
            'app_name' => $this['app_name'],
            'logo_auth' => $this['logo_auth'] ? url('storage/'.$this['logo_auth']) : null,
            'logo_header' => $this['logo_header'] ? url('storage/'.$this['logo_header']) : null,
            'favicon' => $this['favicon'] ? url('storage/'.$this['favicon']) : null,
            'name_and_logo' => (bool) $this['name_and_logo'],
        ];
    }
}
