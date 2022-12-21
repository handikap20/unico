<div id="content" class="h-100">
    <div class="row h-100">
        <div class="col-sm-12 my-auto">
            <?= form_open('auth/do_login', 'id="formAjax" class="col-md-12"'); ?>
            <div class="form-group text-center">
                <img src="<?= base_url('assets/global/images/')?>LogoApps.png" alt="image" class="form-image"
                    style="width: 80px; height: 50px;">
            </div>
            <div class="form-group">
                <label class="text-muted" for="exampleInputEmail1">Email</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="">
            </div>
            <div class="form-group">
                <label class="text-muted" for="exampleInputPassword1">Password</label>
                <input type="password" name="password" class="form-control" id="password" data-toggle="password" />
            </div>
            <div class="form-group">
                <a href="" id="link-text">Forgot Password</a>
                <button type="submit" class="btn btn-primary"  id="submit-btn" >Log in</button>
            </div>
            <div class="form-group text-center">
                <p id="footer-text">Don't have account? <br>
                    <a href="<?= base_url('register')?>" id="link-text">Register</a>
                </p>
            </div>
            <?= form_close();?>
        </div>
    </div>
</div>
<script type="text/javascript">
$('#formAjax').submit(function(e) {
    e.preventDefault();
    option_save = {
        submit_btn: $('#submit-btn'),
        url: $(this).attr('action'),
        data: $(this).serialize(),
        redirect: "<?= base_url('auth') ?>"
    }
    btn_save_form(option_save);
});
</script>