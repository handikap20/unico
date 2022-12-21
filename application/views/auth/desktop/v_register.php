<div id="content">
    <div class="row h-100">
        <div class="col-sm-12">
        <?= form_open('auth/do_register', 'id="formAjax" class="col-md-12"'); ?>
                <div class="form-group">
                    <label class="text-muted" for="first_name">First Name</label>
                    <input type="text" class="form-control" id="first_name" name="first_name" placeholder="">
                </div>
                <div class="form-group">
                    <label class="text-muted" for="last_name">Last Name</label>
                    <input type="text" class="form-control" id="last_name" name="last_name" placeholder="">
                </div>
                <div class="form-group">
                    <label class="text-muted" for="email">Email</label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="">
                </div>
                <div class="form-group">
                    <label class="text-muted" for="password">Password</label>
                    <input type="password" class="form-control" id="password" data-toggle="password" name="password" />
                </div>
                <div class="form-group">
                    <label class="text-muted" for="confirm_password">Confirm Password</label>
                    <input type="password" class="form-control" id="confirm_password" data-toggle="password" name="confirm_password"/>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary col-12 mt-3" id="submit-btn"
                        name="register">Register</button>
                </div>
            <?=  form_close(); ?>
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