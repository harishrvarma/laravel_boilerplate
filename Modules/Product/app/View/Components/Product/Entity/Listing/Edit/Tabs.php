<?php

namespace Modules\Product\View\Components\Product\Entity\Listing\Edit;

use Modules\Core\View\Components\Eav\Listing\Edit\Tabs as CoreTabs;
use Modules\Eav\Models\Eav\Attribute\Group;
use Modules\Eav\Models\Eav\Entity\Type;
use Modules\Product\Models\Product\Entity;

class Tabs extends CoreTabs
{
    public function __construct()
    {
        parent::__construct();
    }

    public function prepareTabs()
    {
        $request = request();

        $entityId = $request->route('id') ?? $request->get('id');

        $entity = null;
        if ($entityId) {
            $entity = Entity::with('values.attribute.translation')->find($entityId);
        }

        $entityTypeId = $entity?->entity_type_id 
            ?? $request->get('entity_type_id') 
            ?? Type::where('code', 'product')->value('entity_type_id');

        if (!$entityTypeId) {
            return $this;
        }

        $groups = Group::with([
                'translation',
                'attributes.translation',
                'attributes.options.translation',
            ])
            ->where('entity_type_id', $entityTypeId)
            ->orderBy('position', 'asc')
            ->get();

        foreach ($groups as $group) {
            $tabKey = 'group_' . $group->group_id;
            $this->tab($tabKey, [
                'key'          => $tabKey,
                'title'        => $group->code,
                'tabClassName' => \Modules\Product\View\Components\Product\Entity\Listing\Edit\Tabs\DynamicGroup::class,
                'tabData'      => [
                    'group'  => $group,
                    'entity' => $entity ?? null,
                ],
            ]);
        }
            

        return $this;
    }
}