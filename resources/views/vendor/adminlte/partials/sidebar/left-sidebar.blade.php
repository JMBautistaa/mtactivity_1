<aside class="main-sidebar">

    {{-- Sidebar brand logo --}}
    @if(config('.logo_img_xl'))
        @include('adminlte::partials.common.brand-logo-xl')
    @else
        @include('adminlte::partials.common.brand-logo-xs')
    @endif

    {{-- Sidebar menu --}}
    <div class="sidebar">
        <nav class="pt-2">
            <ul class="nav nav-pills nav-sidebar flex-column {{ config('adminlte.classes_sidebar_nav', '') }}"
                data-widget="treeview" role="menu"
                @if(config('adminlte.sidebar_nav_animation_speed') != 300)
                    data-animation-speed="{{ config('adminlte.sidebar_nav_animation_speed') }}"
                @endif
                @if(!config('adminlte.sidebar_nav_accordion'))
                    data-accordion="false"
                @endif>
                {{-- Configured sidebar links --}}
                @each('adminlte::partials.sidebar.menu-item', $adminlte->menu('sidebar'), 'item')
            </ul>
        </nav>
    </div>

</aside>

<style>
    .main-sidebar {
        color: white;
        background-color: #6BAF6B; 
        border: 2px groove #fff;
        overflow-x: hidden;
        padding-top: 20px;
        box-shadow: rgba(0, 0, 0, 0.15) 1.95px 1.95px 2.6px;
        transition: width 0.2s;
    }

    .main-sidebar .nav-link {
        color: white !important;
    }

    .main-sidebar .nav-link:hover,
    .main-sidebar .nav-link:focus {
        color: #e5e5e5 !important;
    }

    .main-sidebar .nav-treeview>.nav-link {
        color: white !important; 
    }

    .main-sidebar .sidebar-heading,
    .main-sidebar .nav-item a {
        color: white !important;
    }

    
    .main-sidebar .nav-link.active {
        color: white;
        background-color: #4F9F4F; 
        font-weight: bold;
    }


    .main-sidebar .nav-treeview .nav-link.active {
        color: #4F9F4F !important;
        background-color: white !important;
        font-weight: normal !important;
    }

    .main-sidebar .nav-treeview .nav-link:hover,
    .main-sidebar .nav-treeview .nav-link:focus {
        color: #4F9F4F !important;
        background-color: white;
    }

    /* .sidebar nav .nav .nav-link.active {
            background: var(--sidebar-active);
            color: var(--sidebar-active_text) !important;
            font-weight: bold;
            border-radius: 50px;
        }

        .sidebar nav .nav .nav-link.active p{
            color: var(--sidebar-active_text) !important;
            font-weight: bold;
        } */

</style>
