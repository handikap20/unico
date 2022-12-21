<!DOCTYPE html>
<html lang="en" class="h-100 justify-content-center align-items-center">

<head>
    <title><?= (!empty($status) && $status == true)? "Notifikasi" : "Error" ?></title>

    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta content="Handika Putra" name="author">

    <!-- Favicon -->
    <link rel="icon" href="<?= base_url('assets/global') ?>/images/LogoApps.png.png">
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">


    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css"
        integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">


    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>


</head>

<body>
    <!-- bootbox code -->
    <script src="<?= base_url('assets/global/plugin/bootbox/bootbox.min.js') ?>"></script>
    <script src="<?php echo base_url('assets/global/plugin/bootbox/bootbox.js?v=6') ?>"></script>

    <?php
            if(!empty($error_login)){
                $link_ok =  base_url('auth');

                $protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
                $url = $protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
                $array = array(
                    'link_reference' => $url
                );
            
                $this->session->set_userdata( $array );
            }else{
                if(!empty($redirect_link))
                {
                    $link_ok =  $redirect_link;
                }else{
                    if(!empty($_SERVER['HTTP_REFERER'])) {
                        $link_ok =  $_SERVER['HTTP_REFERER'];

                        if(!empty($_SESSION["error_page"])) {
                           $link_ok =  base_url('auth');
                            unset($_SESSION["error_page"]);
                        }else{
                            $_SESSION["error_page"] = true;
                        }
                    }else{
                        $link_ok =  base_url('auth');
                    }
                }
            }
        ?>

    <script>
    $(document).ready(function(e) {
        bootbox.alert({
            size: "small",
            message: "<?= $message ?>",
             response: 404,
            callback: function() {
                window.location.replace("<?= $link_ok ?>");
            }
        })
    });
    </script>

</body>

</html>
<?php exit;?>