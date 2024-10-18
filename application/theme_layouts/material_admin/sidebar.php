<?php
$profile_photo = $this->session->userdata('user')['profile_photo'];
$profile_photo_temp = (!is_null($profile_photo) && !empty($profile_photo)) ? $profile_photo : 'themes/_public/img/avatar/male-1.png';
?>

<aside class="sidebar">
    <div class="scrollbar-inner">
        <div class="user">
            <div class="user__info" data-toggle="dropdown">
                <img class="user__img" src="<?php echo base_url($profile_photo_temp) ?>" alt="Avatar" style="object-fit: cover;">
                <div>
                    <div class="user__name"><?php echo $this->session->userdata('user')['nama_lengkap'] ?></div>
                    <div class="user__email"><?php echo $this->session->userdata('user')['role'] ?></div>
                </div>
            </div>

            <div class="dropdown-menu">
                <a class="dropdown-item ripple-effect" href="<?php echo base_url('setting/account') ?>">Account</a>
                <a class="dropdown-item ripple-effect" href="<?php echo base_url('logout') ?>">Logout</a>
            </div>
        </div>

        <ul class="navigation">

            <?php
            if (isset($menus) && count($menus) > 0) {
                $menu = '';
                foreach ($menus as $key => $item) {
                    if (count($item->childs) === 0) {
                        // Has no childs
                        $isNewTab = ($item->is_newtab) ? 'target="_blank"' : '';
                        $activeLink = ($item->link_tobase) ? base_url($item->link) : ((strpos($item->link, 'http') || strpos($item->link, 'www')) ? $item->link : '#');
                        $routerSegment = ($this->uri->segment(1) === $item->link) ? true : false;
                        $class = ($this->router->fetch_class() === $item->link || $routerSegment) ? 'navigation__active' : '';
                        $menu .= '<li class="' . $class . '">';
                        $menu .= '<a class="ripple-effect" href="' . $activeLink . '" ' . $isNewTab . '><i class="' . $item->icon . '"></i> ' . $item->name . '</a>';
                        $menu .= '</li>';
                    } else {
                        // Have a childs
                        $links = $item->link;
                        $links = rtrim($links, ',');
                        $links = preg_replace('/\s+/', '', $links);
                        $links = explode(',', $links);

                        if (count($links) == 0) {
                            $links = $item->link;
                        };

                        $class = (in_array($this->router->fetch_class(), $links)) ? 'navigation__sub--active navigation__sub--toggled' : '';
                        $menu .= '<li class="navigation__sub ' . $class . '">';
                        $menu .= '<a class="ripple-effect" href="#"><i class="' . $item->icon . '"></i> ' . $item->name . '</a>';
                        $menu .= '<ul>';

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
                            $isNewTab = ($child->is_newtab) ? 'target="_blank"' : '';
                            $activeLink = ($child->link_tobase) ? base_url($child->link) : ((strpos($child->link, 'http') || strpos($child->link, 'www')) ? $child->link : '#');
                            $classChild = (($routerClass === $child->link) || ($routerClass . '/' . $routerMethod === $child->link || $routerSegment || $routerMultipleSegment)) ? 'navigation__active' : '';
                            $menu .= '<li class="' . $classChild . '">';
                            $menu .= '<a class="ripple-effect" href="' . $activeLink . '" ' . $isNewTab . ' style="font-size: .95rem;">' . $child->name . '</a>';
                            $menu .= '</li>';
                        };

                        $menu .= '</ul>';
                        $menu .= '</li>';
                    };
                };
                echo $menu;
            };
            ?>

        </ul>
    </div>
</aside>