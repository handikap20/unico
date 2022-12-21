<nav class="navbar navbar-light bg-white navbar-expand top-navbar">
    <div class="dropdown">
        <button role="button" type="button" class="btn dropdown" data-toggle="dropdown">
            <i class="material-icons" style="font-size:25px">menu</i>
        </button>
        <div class="dropdown-menu shadow bg-white rounded">
            <a class="dropdown-item" href="<?= base_url('users_list')?>">User List</a>
            <a class="dropdown-item" href="<?= base_url('auth/do_logout')?>">Log Out</a>
            <a class="dropdown-item" href="#" style="font-size:8px; margin-top: 10px;">V.1.0</a>
        </div>
    </div>
    <ul class="navbar-nav nav-justified w-100">
        <li class="nav-item">
            <a href="#" class="nav-link text-center text-dark">
                <img src="<?= base_url('assets/global/images/LogoApps.png')?>" alt="image" class="form-image" style="width: 70px; height: 40px; margin-left:-50px;">
            </a>
        </li>
    </ul>
</nav>