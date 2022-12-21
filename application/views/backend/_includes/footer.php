<nav class="navbar navbar-light bg-white navbar-expand bottom-navbar bottom-fixed fixed-bottom">
    <ul class="navbar-nav nav-justified w-100 menu">
        <li class="nav-item">
            <a href="<?= base_url('home')?>" class="nav-link <?= ($this->uri->segment(1) == "home") ? "text-primary" : "" ?>">
                <img src="<?php  echo base_url('assets/global/images/');  if($this->uri->segment(1) != "home") { echo "home_24px_outlined_black.svg"; }else { echo "home_24px_outlined.svg"; } ?>" width="25" height="25">
                <br>Home </a>
        </li>
        <li class="nav-item">
            <a href="#" class="nav-link  <?= ($this->uri->segment(1) == "cart") ? "text-primary" : "" ?>">
                <img src="<?= base_url('assets/global/images/add_shopping_cart_24px_outlined.svg') ?>" width="25"
                    height="25">
                <br>Cart </a>
        </li>
        <li class="nav-item">
            <a href="<?= base_url('profile')?>" class="nav-link  <?= ($this->uri->segment(1) == "profile") ? "text-primary" : "" ?>">
                <img src="<?php  echo base_url('assets/global/images/');  if($this->uri->segment(1) != "profile") { echo "perm_identity_24px_outlined_black.svg"; }else { echo "perm_identity_24px_outlined.svg"; } ?>"  width="25"
                    height="25">
                <br>Account </a>
        </li>
    </ul>
</nav>