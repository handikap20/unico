<div class="container">
    <div class="row justify-content-center align-items-start">
        <form class="col-md-12">
            <div class="row">
                <div class="input-group col-md-12">
                    <input class="form-control py-2" type="search" placeholder="Search" id="example-search-input">
                    <span class="input-group-append">
                        <i class="fa fa-search" style="padding-top: 10px; margin-left: -20px; position: absolute;"></i>
                    </span>
                </div>
            </div>
        </form>
    </div>
</div>
<div class="container mt-3">
    <div class="row advertise">
        <div class="col-md-12">
            <div id="advertiseIndicators" class="carousel slide" data-ride="carousel">
                <ol class="carousel-indicators">
                    <li data-target="#advertiseIndicators" data-slide-to="0" class="active"></li>
                    <li data-target="#advertiseIndicators" data-slide-to="1"></li>
                    <li data-target="#advertiseIndicators" data-slide-to="2"></li>
                </ol>
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <img src="<?= base_url('assets/global/images/no-image.jpg') ?>" class="d-block w-100" alt="img1"
                            width="100" height="130">
                    </div>
                    <div class="carousel-item">
                        <img src="<?= base_url('assets/global/images/no-image.jpg') ?>" class="d-block w-100" alt="img2"
                            width="100" height="130">
                    </div>
                    <div class="carousel-item">
                        <img src="<?= base_url('assets/global/images/no-image.jpg') ?>" class="d-block w-100" alt="img3"
                            width="100" height="130">
                    </div>
                </div>
                <a class="carousel-control-prev" href="#advertiseIndicators" role="button" data-slide="prev">
                    <i class="material-icons" style="font-size:36px; color: black;">chevron_left</i>
                </a>
                <a class="carousel-control-next" href="#advertiseIndicators" role="button" data-slide="next">
                    <i class="material-icons" style="font-size:36px; color: black;">chevron_right</i>
                </a>
            </div>
        </div>
    </div>
</div>
<div class="container">
    <div class="d-flex flex-row justify-content-center" style="margin-top: 40px;">
        <div class="card p-3" style="background-color: #f5f5f5;">
            <img src="<?= base_url('assets/global/images/card_giftcard_24pxsad.svg') ?>" class="mx-auto" width="30"
                height="30">
            <p class="card-text text-center text-primary">Material</p>
        </div>
        <div class="card p-3 mr-5 ml-5" style="background-color: #f5f5f5;">
            <img src="<?= base_url('assets/global/images/build_24px_outlined.svg')?>" class="mx-auto" width="30"
                height="30">
            <p class="card-text text-center text-primary">Tools</p>
        </div>
        <div class="card p-3" style="background-color: #f5f5f5;">
            <img src="<?= base_url('assets/global/images/perm_data_setting_24px_outlined.svg')?>" class="mx-auto"
                width="30" height="30">
            <p class="card-text text-center text-primary">Fitting</p>
        </div>
    </div>

    <div class="d-flex flex-row justify-content-center" style="margin-top: 20px;">
        <div class="card p-3" style="background-color: #f5f5f5;">
            <img src="<?= base_url('assets/global/images/card_giftcard_24pxsad.svg') ?>" class="mx-auto" width="30"
                height="30">
            <p class="card-text text-center text-primary">Ceramics</p>
        </div>
        <div class="card p-3 mr-5 ml-5" style="background-color: #f5f5f5;">
            <img src="<?= base_url('assets/global/images/layers_24px_outlined.svg')?>" class="mx-auto" width="30"
                height="30">
            <p class="card-text text-center text-primary">Acrylic</p>
        </div>
        <div class="card p-3" style="background-color: #f5f5f5;">
            <img src="<?= base_url('assets/global/images/category_24px_outlined.svg')?>" class="mx-auto" width="40"
                height="30">
            <p class="card-text text-center text-primary">Other</p>
        </div>
    </div>
</div>