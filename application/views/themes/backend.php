<!DOCTYPE html>
<html lang="en" class="h-100 justify-content-center align-items-center">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php if(strlen($page_title) > 0) {$title = $page_title." | ".$site_name;}else{$title = $site_name;} ?>
    <title><?= $title ?></title>

    <link rel="icon" href="<?= base_url('assets/global') ?>/images/LogoApps.png.png">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,user-scalable=0,minimal-ui">

    <?php if(!empty($page_description) && isset($meta['description'])) $meta['description'] = $page_description; ?>
    <?php if(!empty($meta)) : ?>
    <?php foreach($meta as $name=>$content) : ?>
    <meta name="<?php echo $name; ?>" content="<?php echo $content; ?>" />
    <?php endforeach;?>
    <?php endif;?>

    <?php if(empty($page_image)) $page_image = base_url('assets/global/images/LogoApps.png'); ?>

    <!-- Meta Facebook -->
    <meta property="og:title" content="<?= $title ?>" />
    <meta property="og:description" content="<?= (!empty($meta['description']))? $meta['description'] : "" ?>" />
    <meta property="og:site_name" content="<?= $site_name ?>" />
    <meta property="og:type" content="website" />
    <meta property="og:image" content="<?= $page_image ?>" />
    <meta property="og:image:alt" content="<?= $title ?>" />
    <meta property="og:url" content="<?= current_url() ?>" />
    <meta property='og:locale' content='id_ID' />
    <meta property='og:locale:alternate' content='en_US' />
    <meta property='og:locale:alternate' content='en_GB' />

    <!-- Meta Twitter -->
    <meta name='twitter:card' content='summary' />
    <meta name="twitter:title" content="<?= $title ?>" />
    <meta name="twitter:image:alt" content="<?= $title ?>" />
    <meta name="twitter:description" content="<?= (!empty($meta['description']))? $meta['description'] : "" ?>" />
    <meta name="twitter:image" content="<?= $page_image ?>" />
    <meta name="twitter:url" content="<?= current_url() ?>" />

    <!-- App favicon -->
    <link rel="shortcut icon" href="<?= $page_image ?>">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">


    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css"
        integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="<?= $global_custom_path?>/css/custom.css?v=15">
    <?php foreach ($css as $file) { ?>
    <link rel="stylesheet" href="<?php echo $file; ?>" type="text/css" />
    <?php } ?>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/bootstrap-show-password@1.2.1/dist/bootstrap-show-password.min.js"></script>

    <script src="<?= $global_plugin_path ?>/bootbox/bootbox.js?v=3"></script>

    <script type="text/javascript">
    var uri_dasar = '<?= base_url() ?>';
    var csrf_name = '<?php echo $this->security->get_csrf_token_name(); ?>';
    var csrf_value = '<?php echo $this->security->get_csrf_hash(); ?>';
    var grecaptcha;

    $(document).ready(function() {
        setTimeout(() => {
            $("#loader").fadeToggle(250);
        }, 700);
    });
    </script>

    <script src="<?= $global_plugin_path ?>/auto-csrf/auto-csrf.min.js"></script>
    <script src="<?= $global_custom_path ?>/js/FormAjax.init.js?v=3"></script>

</head>

<body class="h-100">
    <div id="loader">
        <img src="<?= $global_images_path ?>/Infinity-0.8s-224px.svg" alt="" width="80" height="80">
    </div>
    <?php if(empty($is_mobile)) {?>
    <div class="desktop">
        <div class="app bg-white h-100">
            <?php echo $this->load->get_section('header');?>
            <?= $output ?>
            <?php echo $this->load->get_section('footer');?>
        </div>
    </div>
    <?php }else { 
     echo $this->load->get_section('header');
     echo $output;
     echo $this->load->get_section('footer');   
    }?>

</body>
<footer>
    <script src="<?= $global_custom_path ?>/js/mycustom.js?v=4"></script>
</footer>

</html>