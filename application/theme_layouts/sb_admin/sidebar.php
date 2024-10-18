<!-- Sidebar -->
<div id="layoutSidenav_nav">
    <nav class="sidenav shadow-right sidenav-light">
        <div class="sidenav-menu">
            <div class="nav accordion" id="accordionSidenav">
                <!-- Sidenav Menu Heading (Core)-->
                <div class="sidenav-menu-heading">Navigasi</div>
                <!-- Navigation lists -->
                <?php
                if (isset($menus) && count($menus) > 0) {
                    $menu = '';
                    foreach ($menus as $key => $item) {
                        if ($item->link === 'logout') {
                            $menu .= '<div class="sidenav-menu-heading">SISTEM</div>';
                        };

                        if (count($item->childs) === 0) {
                            // Has no childs
                            $isNewTab = ($item->is_newtab == '1') ? 'target="_blank"' : '';
                            $activeLink = ($item->link_tobase) ? base_url($item->link) : ((strpos($item->link, 'http') !== false || strpos($item->link, 'www') !== false) ? $item->link : '#');
                            $routerSegment = ($this->uri->segment(1) === $item->link) ? true : false;
                            $class = ($this->router->fetch_class() === $item->link || $routerSegment) ? 'active' : '';
                            $menu .= '
                                        <a class="nav-link ' . $class . '" href="' . $activeLink . '" ' . $isNewTab . '>
                                            <div class="nav-link-icon"><i class="' . $item->icon . '"></i></div>
                                            ' . $item->name . '
                                        </a>
                                    ';
                        } else {
                            // Have a childs
                            $links = $item->link;
                            $links = rtrim($links, ',');
                            $links = preg_replace('/\s+/', '', $links);
                            $links = explode(',', $links);

                            if (count($links) == 0) {
                                $links = $item->link;
                            };

                            $class = (in_array($this->router->fetch_class(), $links)) ? 'active' : 'collapsed';
                            $classChildItem = ($class === 'active') ? 'show' : '';
                            $menu .= '
                                        <a class="nav-link ' . $class . '" href="javascript:void(0);" data-bs-toggle="collapse" data-bs-target="#nav-' . $item->id . '" aria-expanded="false" aria-controls="nav-' . $item->id . '">
                                            <div class="nav-link-icon"><i class="' . $item->icon . '"></i></div>
                                            ' . $item->name . '
                                            <div class="sidenav-collapse-arrow"><i class="zmdi zmdi-chevron-down"></i></div>
                                        </a>
                                        <div class="collapse ' . $classChildItem . '" id="nav-' . $item->id . '" data-bs-parent="#accordionSidenav">
                                            <nav class="sidenav-menu-nested nav accordion" id="accordion_nav-' . $item->id . '">
                                    ';

                            foreach ($item->childs as $index => $child) {
                                // Child link multiple segment (3)
                                $linkChild = $child->link;
                                $linkChild = explode('/', $linkChild);
                                $routerMultipleSegment = false;

                                if (count($linkChild) > 0) {
                                    // 2 level
                                    if (isset($linkChild[0]) && isset($linkChild[1])) {
                                        $routerMultipleSegment = ($this->uri->segment(1) . '/' . $this->uri->segment(2) === $linkChild[0] . '/' . $linkChild[1]) ? true : false;
                                    };
                                    // 3 level
                                    if (isset($linkChild[0]) && isset($linkChild[1]) && isset($linkChild[2])) {
                                        $routerMultipleSegment = ($this->uri->segment(1) . '/' . $this->uri->segment(2) . '/' . $this->uri->segment(3) === $linkChild[0] . '/' . $linkChild[1] . '/' . $linkChild[2]) ? true : false;
                                    };
                                    // 4 level
                                    if (isset($linkChild[0]) && isset($linkChild[1]) && isset($linkChild[2]) && isset($linkChild[3])) {
                                        $routerMultipleSegment = ($this->uri->segment(1) . '/' . $this->uri->segment(2) . '/' . $this->uri->segment(3) . '/' . $this->uri->segment(4) === $linkChild[0] . '/' . $linkChild[1] . '/' . $linkChild[2] . '/' . $linkChild[3]) ? true : false;
                                    };
                                };

                                $routerClass = $this->router->fetch_class();
                                $routerMethod = $this->router->fetch_method();
                                $routerSegment = ($this->uri->segment(1) . '/' . $this->uri->segment(2)) === $child->link ? true : false;
                                $isNewTab = ($child->is_newtab == '1') ? 'target="_blank"' : '';
                                $activeLink = ($child->link_tobase) ? base_url($child->link) : ((strpos($child->link, 'http') !== false || strpos($child->link, 'www') !== false) ? $child->link : '#');
                                $classChild = (($routerClass === $child->link) || ($routerClass . '/' . $routerMethod === $child->link || $routerSegment || $routerMultipleSegment)) ? 'active' : '';
                                $menu .= '<a class="nav-link ripple-effect ' . $classChild . '" href="' . $activeLink . '" ' . $isNewTab . '>' . $child->name . '</a>';
                            };

                            $menu .= '</nav></div>';
                        };
                    };
                    echo $menu;
                };
                ?>
                <!-- END ## Navigation lists -->
            </div>
        </div>
        <!-- Sidenav Footer-->
        <div class="sidenav-footer">
            <div class="sidenav-footer-content">
                <div class="sidenav-footer-subtitle">Logged in as:</div>
                <div class="sidenav-footer-title"><?php echo $this->session->userdata('user')['nama_lengkap'] ?></div>
            </div>
        </div>
    </nav>
</div>
<!-- END ## Sidebar -->