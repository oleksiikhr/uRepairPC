<?php declare(strict_types=1);

namespace App\Traits;

trait ModelHelperTrait
{
    /**
     * @return array
     */
    public function getChangesWithoutHiddenAttrs(): array
    {
        $data = $this->getChanges();

        foreach ($this->getHidden() as $attr) {
            unset($data[$attr]);
        }

        return $data;
    }
}
