<?php

namespace Modules\Product\Http\Controllers\Product;

use Exception;
use Illuminate\Http\Request;
use Modules\Core\Http\Controllers\BackendController;
use Modules\Product\Models\Product\Entity;
use Modules\Product\View\Components\Product\Entity\Listing;
use Modules\Product\View\Components\Product\Entity\Listing\Edit;
use Modules\Eav\Models\Eav\Entity\Type;
use Illuminate\Support\Facades\DB;
use Modules\Product\Models\Product\Value;
use Modules\Eav\Models\Eav\Attribute;

class EntityController extends BackendController
{
    public function listing(Request $request)
    {
        try {
            $layout  = $this->layout();
            $layout->title('Manage Entities');
            $listing = $this->block(Listing::class);
            $layout->child('content')->child('listing', $listing);
            return $layout->render();
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    public function add(Request $request)
    {
        try {
            $layout = $this->layout();
            $layout->title('Add Entity');
    
            $entityTypeId = $request->get('entity_type_id');
            $entity = new Entity(['entity_type_id' => $entityTypeId]);
    
            $editBlock = $this->block(Edit::class)
                              ->row($entity);
    
            $layout->child('content')->child('edit', $editBlock);
    
            return $layout->render();
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }
    
    public function edit($id)
    {
        try {
            $layout = $this->layout();
            $layout->title('Edit Entity');
    
            if (!$id) {
                throw new \Exception("Invalid Request");
            }
    
            $entity = Entity::with(['values.attribute' => function ($q) {
                $q->select('attribute_id', 'code');
            }])->findOrFail($id);
    
            $attributeIds = $entity->values->pluck('attribute_id')->toArray();
            $translations = \Modules\Eav\Models\Eav\Attribute\Translation::whereIn('attribute_id', $attributeIds)
                                                                          ->get()
                                                                          ->keyBy('attribute_id');
            foreach ($entity->values as $value) {
                $value->attribute->translation = $translations[$value->attribute_id] ?? null;
            }
            $editBlock = $this->block(Edit::class)
                              ->row($entity);
    
            $layout->child('content')->child('edit', $editBlock);
    
            return $layout->render();
    
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }
    
    public function save(Request $request)
    {
        try {
            $params = $request->post('entity', []);
            $entityId = $request->get('id');
            $entityData = $request->post('entity_data', []);
    
            if (empty($params['entity_type_id'])) {
                $params['entity_type_id'] = Type::where('code', 'product')->value('entity_type_id');
            }
    
            $entity = $entityId ? Entity::findOrFail($entityId) : Entity::create($params);
            if ($entityId) {
                $entity->update($params);
            }
    
            $Attributes = Attribute::where('entity_type_id', $params['entity_type_id'])
                ->get()
                ->keyBy('attribute_id');
    
            $insertData = [];
            $updateData = [];
    
            foreach ($Attributes as $attributeId => $attribute) {
                if (!isset($entityData[$attributeId])) {
                    continue;
                }
    
                $langId = key($entityData[$attributeId]);
                $recordTypes = current($entityData[$attributeId]);
                $recordType = key($recordTypes);
                $value = current($recordTypes);
    
                $castedValue = $attribute->castValue($value);
    
                if ($recordType === 'exist') {
                    $updateData[] = [
                        'attribute_id' => $attributeId,
                        'lang_id'      => $langId,
                        'value'        => $castedValue,
                    ];
                } else {
                    $insertData[] = [
                        'entity_id'    => $entity->entity_id,
                        'attribute_id' => $attributeId,
                        'lang_id'      => $langId,
                        'value'        => $castedValue,
                    ];
                }
            }
    
            if (!empty($insertData)) {
                $entity->values()->insert($insertData);
            }
            
            if (!empty($updateData)) {
                $table = $entity->values()->getModel()->getTable();
                $entityId = $entity->entity_id;

                $cases = [];
                $whereConditions = [];

                foreach ($updateData as $row) {
                    $cases[] = "WHEN attribute_id = {$row['attribute_id']} AND lang_id = {$row['lang_id']} THEN '".addslashes($row['value'])."'";
                    $whereConditions[] = "({$row['attribute_id']}, {$row['lang_id']})";
                }

                $caseSql = implode(' ', $cases);
                $whereSql = implode(',', $whereConditions);

                $sql = "UPDATE {$table}
                        SET value = CASE {$caseSql} END
                        WHERE entity_id = {$entityId} 
                        AND (attribute_id, lang_id) IN ({$whereSql})";

                DB::statement($sql);
            }

            $redirectRoute = $request->get('continue')
                ? route('admin.product.entity.edit', ['id' => $entity->entity_id])
                : route('admin.product.entity.listing');
    
            return redirect($redirectRoute)->with('success', 'Record saved successfully.');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    public function delete(Request $request)
    {
        try {
            if (!$request->id) {
                throw new Exception("Invalid Request");
            }

            $row = Entity::find($request->id);
            if (!$row) {
                throw new Exception("Invalid Request");
            }

            $row->delete();

            return redirect()->route('admin.product.entity.listing')
                ->with('success', 'Record deleted');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    public function massDelete(Request $request)
    {
        try {
            $ids = $request->post('mass_ids');
            if (empty($ids)) {
                throw new Exception('Invalid Ids');
            }

            Entity::destroy($ids);

            return redirect()->route('admin.product.entity.listing')
                ->with('success', 'Records deleted');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    public function massExport(Request $request)
    {
        try {
            $ids = $request->post('mass_ids', []);

            $columns = $request->post('visible_columns', '');
            $columns = $columns ? explode(',', $columns) : ['id'];

            $columns = array_unique($columns);

            $modelInstance = new Entity();
            $tableColumns = $modelInstance->getConnection()->getSchemaBuilder()->getColumnListing($modelInstance->getTable());

            $columns = array_intersect($columns, $tableColumns);

            $query = Entity::query();

            if (!empty($ids)) {
                $query->whereIn('entity_id', $ids);
            }

            $records = $query->get($columns);

            $filename = 'entity_export_' . date('Y-m-d_H-i-s') . '.csv';
            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => "attachment; filename=\"$filename\"",
            ];

            $callback = function() use ($records, $columns) {
                $file = fopen('php://output', 'w');

                fputcsv($file, $columns);

                foreach ($records as $record) {
                    $row = [];
                    foreach ($columns as $col) {
                        $row[] = $record->{$col};
                    }
                    fputcsv($file, $row);
                }

                fclose($file);
            };

            return response()->stream($callback, 200, $headers);

        } catch (\Throwable $th) {
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

}
