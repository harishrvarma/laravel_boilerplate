<?php

namespace Modules\Product\Http\Controllers\Api\V1;

use Exception;
use Illuminate\Http\Request;
use Modules\Core\Http\Controllers\BackendController;
use Modules\Product\Models\Product\Entity;
use Modules\Product\View\Components\Product\Entity\Listing;
use Modules\Product\View\Components\Product\Entity\Listing\Edit;
use Modules\Eav\Models\Eav\Entity\Type;
use Illuminate\Support\Facades\DB;
use Modules\Product\Models\Product\Value;
use Modules\Product\View\Components\Product\Entity\Listing\Grid;
use Modules\Eav\Models\Eav\Attribute;

class EntityController extends BackendController
{
    public function listing(Request $request)
    {
        try {
            $grid = $this->block(Grid::class);
            $grid->prepareCollection();
    
            return response()->json([
                'success'   => true,
                'rows'      => $grid->rows(),
                'page'      => $grid->page(),
                'perPage'   => $grid->perPage(),
                'totalCount' => $grid->totalCount(),
            ], 200);
    
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'error' => $th->getMessage(),
            ], 500);
        }
    }
    
    public function save(Request $request)
    {
        try {
            $params = ['entity_type_id' => $request->post('entity_type_id')];
            $entityId = $request->get('entity_id');
            $AttributeId = $request->post('attribute_id', []);
            $langId = $request->post('lang_id', []);
            $langType = $request->post('lang_type', []);
            $Value = $request->post('value', []);

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
                if (!isset($AttributeId)) continue;
    
                $castedValue = $attribute->castValue($Value);
    
                if ($langType === 'exist') {
                    $updateData[] = [
                        'attribute_id' => $AttributeId,
                        'lang_id'      => $langId,
                        'value'        => $castedValue,
                    ];
                } else {
                    $insertData[] = [
                        'entity_id'    => $entity->entity_id,
                        'attribute_id' => $AttributeId,
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
    
            return response()->json([
                'success' => true,
                'message' => 'Record saved successfully',
                'entity_id' => $entity->entity_id
            ], 200);
    
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'error' => $th->getMessage()
            ], 500);
        }
    }
    
    public function delete(Request $request)
    {
        try {
            $id = $request->id;
    
            if (!$id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid request. ID is required.',
                ], 400);
            }
    
            $row = Entity::find($id);
    
            if (!$row) {
                return response()->json([
                    'success' => false,
                    'message' => 'Record not found.',
                ], 404);
            }
    
            $row->delete();
    
            return response()->json([
                'success' => true,
                'message' => 'Record deleted successfully.',
                'id'       => $id,
            ], 200);
    
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
            ], 500);
        }
    }
    
}