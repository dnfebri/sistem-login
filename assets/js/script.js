// console.log("ok");

$('.custom-file-input').on('change', function() {
    let fileName = $(this).val().split('\\').pop();
    $(this).next('.custom-file-label').addClass("selected").html(fileName);
});

$('.form-check-input').on('click', function() {
    const menuId = $(this).data('menu');
    const roleId = $(this).data('role');
    const base_url = "http://localhost/dnfebri-github/sistem-login/admin/"

    $.ajax({
        url: base_url + "changeaccess",
        type: 'post',
        data: {
            menuId: menuId,
            roleId: roleId
        },
        success: function() {
            // console.log(base_url + "roleaccess/" + roleId);
            document.location.href = base_url + "roleaccess/" + roleId;
        }
    });

    // console.log(base_url + "changeaccess" + "/" + roleId);
});