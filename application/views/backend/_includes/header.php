<nav class="navbar navbar-light bg-white navbar-expand top-navbar">
    <a href="<?= (!empty($url_back)) ? base_url($url_back) : base_url('home');?>">
        <i class="material-icons">navigate_before</i>
    </a>
    <ul class="navbar-nav nav-justified w-100">
        <li class="nav-item">
            <a href="#" class="nav-link text-center text-dark">
                <h4 class="font-weight-bold page-title"><?= $page_title;?></h4>
            </a>
        </li>
    </ul>
</nav>