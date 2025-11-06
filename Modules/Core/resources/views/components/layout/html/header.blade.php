@php
    use Modules\Translation\Models\TranslationLocale;
    $locales = TranslationLocale::orderBy('id')->get();
@endphp
<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button">
                <i class="fas fa-bars"></i>
            </a>
        </li>
    </ul>

    <ul class="navbar-nav ms-auto">
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="languageDropdown" role="button"
            data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fas fa-globe"></i> Language
            </a>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="languageDropdown">
                @foreach($locales as $locale)
                    <li>
                        <a class="dropdown-item" href="{{ url()->current() }}?lang={{ $locale->code }}">
                            {{ $locale->label }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </li>
    </ul>
</nav>


@php
use Illuminate\Support\Facades\App;

$user = auth('admin')->user();

$menuTree = App::make('menu.cache')->getGlobal();

// Recursive renderer with ordering
function renderMenuTree($items) {
    usort($items, function($a, $b) {
        return ($a['order_no'] ?? 0) <=> ($b['order_no'] ?? 0);
    });

    foreach ($items as $menu) {
        $hasChildren = !empty($menu['children']);

        // If there are children, sort them recursively
        if ($hasChildren) {
            usort($menu['children'], function($a, $b) {
                return ($a['order_no'] ?? 0) <=> ($b['order_no'] ?? 0);
            });
        }

        $routeName = $menu['resource']['route_name'] ?? null;

        $routeUrl = '#';
        if ($routeName && \Route::has($routeName)) {
            $route = \Route::getRoutes()->getByName($routeName);
            if (empty($route->parameterNames())) {
                $routeUrl = route($routeName);
            }
        }

        $isActive = $routeName && request()->routeIs($routeName) ? 'active' : '';

        $isMenuOpen = '';
        if ($hasChildren) {
            foreach ($menu['children'] as $child) {
                $childRoute = $child['resource']['route_name'] ?? null;
                if ($childRoute && request()->routeIs($childRoute)) {
                    $isMenuOpen = 'menu-open';
                    break;
                }
            }
        }

        echo '<li class="nav-item '.$isMenuOpen.'">';
        echo '<a href="'.$routeUrl.'" class="nav-link '.$isActive.'">';
        echo '<i class="nav-icon '.($menu['icon'] ?? 'fas fa-circle').'"></i>';
        echo '<p>'.$menu['title'];
        if ($hasChildren) echo '<i class="right fas fa-angle-left"></i>';
        echo '</p>';
        echo '</a>';

        if ($hasChildren) {
            echo '<ul class="nav nav-treeview">';
            renderMenuTree($menu['children']);
            echo '</ul>';
        }

        echo '</li>';
    }
}
@endphp


<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="#" class="brand-link">
        <span class="brand-text font-weight-light">Menu</span>
    </a>

    <div class="sidebar d-flex flex-column h-100">
        <nav class="flex-grow-1">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">
                @if(!empty($menuTree))
                    @php renderMenuTree($menuTree); @endphp
                @endif
            </ul>
        </nav>

        <div class="mt-auto p-3">
            <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-danger btn-block">
                    <i class="fas fa-sign-out-alt me-2"></i> Logout
                </button>
            </form>
        </div>
    </div>
</aside>
