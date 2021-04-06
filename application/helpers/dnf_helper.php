<?php

function is_logged_in()
{
    $overthis = get_instance();
    if (!$overthis->session->userdata('email')) {
        redirect('auth');
    } else {
        $role_id = $overthis->session->userdata('role_id');
        $menu = $overthis->uri->segment(1);

        $queryMenu = $overthis->db->get_where('user_menu', ['menu' => $menu]) ->row_array();
        $menu_id = $queryMenu['id'];

        $userAccess = $overthis->db->get_where('user_access_menu', [
            'role_id' => $role_id,
            'menu_id' => $menu_id
        ]);

        if ($userAccess->num_rows() < 1) {
            redirect('auth/blocked');
        }
    }
}

function check_access($role_id ,$menu_id)
{
    $overthis = get_instance();

    $result = $overthis->db->get_where('user_access_menu', [
        'role_id' => $role_id,
        'menu_id' => $menu_id
    ]);

    if ($result->num_rows() > 0 ) {
        return "checked='checked'";
    }
}