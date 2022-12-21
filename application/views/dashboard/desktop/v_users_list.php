<div id="content">
    <div class="container" id="users-list"></div>
    <a href="<?= base_url('/users_list/create')?>" class="float" id="float">
        <i class="fa fa-plus my-float"></i>
    </a>
</div>
<script type="text/javascript">
$(document).ready(function(e) {
    aOption = {
        url: "<?= base_url($uri_mod.'/AjaxGetUsers')?>",
        // spinner: $('#loader'),
        type: "get",
        async: true,
        onSuccess: (data) => {
            let users_list = '';
            $.each(data.data, function(index, value) {
                users_list +=
                    '<div class="row text-center m-1 mb-3 text-break"><div class="card d-flex flex-row w-100 align-items-center pl-3 pt-2 pb-2 shadow bg-white rounded">';
                users_list +=
                    '<img class="card-img-left example-card-img-responsive" src="<?= base_url('assets/global/images/user.png')?>" width="60" height="60" />';
                users_list +=
                    '<div class="card-body text-left"><div class="font-weight-bold text-break" style="font-size: 17px;">' +
                    value.first_name + ' ' + value.last_name + '</div>';
                users_list += '<div class="text-break" style="font-size: 10px;"> ' + value
                    .email + '</div></div>';
                users_list += ' <div class="card-body text-right"><a href="<?= base_url('users_list/edit/')?>'+value.id+'" class="font-weight-bold"><i class="material-icons">edit</i></a><a href="#" class="button-delete font-weight-bold" data-id="'+value.id+'"><i class="material-icons">delete</i></a></div></div></div>';
            });
            $('#users-list').append(users_list);
        }
    };
    get_data_by_id(aOption);

    $(document).on("click", ".button-delete", function(e) {
        e.preventDefault();
        aOption = {
            title: "Delete Data?",
            message: "Are you sure delete this data ?",
            url: "<?= base_url('users_list/AjaxDel/')?>" + $(this).attr('data-id'),
            data: {
                uncio_c_token: csrf_value
            },
        };
        btn_confirm_action(aOption);
    });
});
</script>