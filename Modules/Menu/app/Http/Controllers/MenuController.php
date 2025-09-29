<?php

namespace Modules\Menu\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Modules\Core\Http\Controllers\BackendController;
use Modules\Menu\Models\Menu;
use Modules\Menu\View\Components\Menu\Listing\Edit as MenuEdit;
use Modules\Menu\View\Components\Menu\Listing\Tree as MenuTree;

class MenuController extends BackendController
{
    /**
     * Show the menu listing page
     */
    public function listing(Request $request)
    {
        $listing = $this->block(\Modules\Menu\View\Components\Menu\Listing::class);
        $layout  = $this->layout();
        $content = $layout->child('content');
        $content->child('listing', $listing);
        return $layout->render();
    }

    /**
     * Add new menu (root or child)
     */
    public function add(Request $request)
    {
        $menu = $this->model(Menu::class);

        if ($request->has('parent_id')) {
            $parent = Menu::findOrFail($request->get('parent_id'));
            $menu->parent_id = $parent->id;
            $menu->level     = $parent->level + 1;
            $menu->path_ids  = $parent->path_ids . '/' . $parent->id;
        }

        $edit = $this->block(MenuEdit::class);
        $edit->row($menu);

        $layout  = $this->layout();
        $content = $layout->child('content');
        $content->child('edit', $edit);

        return $layout->render();
    }

    /**
     * Save menu (new or update)
     */
    public function save(Request $request)
    {
        try {
            $params = $request->post('menu');
            $area = $params['area'] ?? 'admin';
            $resourceIds = $params['resource_ids'] ?? [];
    
            if (empty($resourceIds)) {
                return redirect()->back()->withInput()->with('error', 'Please select at least one menu item.');
            }
    
            foreach ($resourceIds as $resourceId) {
                $resource = \Modules\Admin\Models\Resource::find($resourceId);
                if (!$resource) continue;
    
                $title = $resource->label ?? ucfirst(str_replace('.', ' ', $resource->code));
    
                // Determine if it's a folder or file
                $isFolder = str_ends_with($resource->code, '.*'); // adjust based on your resource code logic
                $itemType = $isFolder ? 'folder' : 'file';
                $icon = $isFolder ? 'fas fa-folder' : 'fas fa-file';
    
                // Check if menu already exists for this resource in the area
                $menu = Menu::firstOrCreate(
                    ['resource_id' => $resourceId, 'area' => $area],
                    [
                        'title'     => $title,
                        'icon'      => $icon,
                        'item_type' => $itemType,
                        'is_active' => 1,
                        'order_no'  => 0,
                    ]
                );
    
                // Automatically create parent menus if needed
                $parts = explode('.', $resource->code);
                if (count($parts) > 1) {
                    $parentCode = implode('.', array_slice($parts, 0, -1));
                    $parentResource = \Modules\Admin\Models\Resource::where('code', $parentCode)->first();
    
                    if ($parentResource) {
                        $parentMenu = Menu::firstOrCreate(
                            ['resource_id' => $parentResource->id, 'area' => $area],
                            [
                                'title'     => ucfirst(str_replace('.', ' ', $parentCode)),
                                'icon'      => 'fas fa-folder',
                                'item_type' => 'folder',
                                'is_active' => 1,
                                'order_no'  => 0,
                            ]
                        );
    
                        // Attach parent to current menu if not already set
                        if (!$menu->parent_id) {
                            $menu->parent_id = $parentMenu->id;
                            $menu->saveQuietly();
                        }
                    }
                }
            }
    
            return redirect()
                ->route('admin.menu.listing')
                ->with('success', 'Menu(s) saved successfully.');
    
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }
    
    public function saveTree(Request $request)
    {
        try {
            $treeData = json_decode($request->post('tree'), true);
    
            if (!$treeData) {
                throw new \Exception("No tree data received");
            }
    
            $this->updateTree($treeData);
    
            return redirect()->route('admin.menu.listing')->with('success', 'Menu tree updated');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
    
    protected function updateTree(array $nodes, $parentId = null, $path = '', $level = 0)
    {
        foreach ($nodes as $index => $node) {
            $menu = Menu::find($node['id']);
            if (!$menu) continue;
    
            // Determine permission_key based on checkbox
            $permissionKey = !empty($node['checked']) ? 1 : 2;
    
            $pathIds = $path ? $path . '/' . $menu->id : $menu->id;
    
            // Update without changing parent_id
            $menu->update([
                'order_no'      => $index,
                'path_ids'      => $pathIds,
                'level'         => $level,
                'is_active'     => !empty($node['checked']) ? 1 : 0,
                'permission_key'=> $permissionKey,
            ]);
    
            // Recursive update for children
            if (!empty($node['children'])) {
                $this->updateTree($node['children'], $menu->id, $pathIds, $level + 1);
            }
        }
    }
    
    /**
     * Edit menu
     */
    public function edit($id)
    {
        try {
            $menu = Menu::find($id);
            if (!$menu || !$menu->id) {
                throw new Exception("Invalid Request");
            }

            $edit = $this->block(MenuEdit::class);
            $edit->row($menu);

            $layout  = $this->layout();
            $content = $layout->child('content');
            $content->child('edit', $edit);

            return $layout->render();
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Delete menu
     */
    public function delete(Request $request)
    {
        try {
            $menu = Menu::find($request->id);
            if (!$menu || !$menu->id) {
                throw new Exception("Invalid Request");
            }

            $menu->delete();
            return redirect()->route('admin.menu.listing')->with('success', 'Menu deleted');
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Mass delete menus
     */
    public function massDelete(Request $request)
    {
        try {
            $ids = request('mass_ids');
            if (is_null($ids)) {
                throw new Exception('Invalid Ids');
            }

            Menu::destroy($ids);
            return redirect()->route('admin.menu.listing')->with('success', 'Menus deleted');
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Tree View for managing menus
     */
    public function tree()
    {
        $menu = $this->model(Menu::class);
        $tree = $this->block(MenuTree::class);
        $tree->row($menu);
        $layout  = $this->layout();
        $content = $layout->child('content');
        $content->child('tree', $tree);

        return $layout->render();
    }
}
