<?php
  header('Content-type: application/json;');  
?>
{
    "name": "<?= $site_name ?>",
    "short_name": "<?= $site_name ?>",
    "icons": [
        {
            "src": "/public/images/favicon/android-chrome-192x192.png",
            "sizes": "192x192",
            "type": "image/png"
        },
        {
            "src": "/public/images/favicon/android-chrome-512x512.png",
            "sizes": "512x512",
            "type": "image/png"
        }
    ],
    "theme_color": "#ffffff",
    "background_color": "#ffffff",
    "orientation": "portrait",
    "start_url": "<?= base_url() ?>",
    "display": "standalone"
}