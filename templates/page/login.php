<div class="wrap">
    <h1>WooCommerce Search Chose an Login</h1>

    <form method="post" action="">
        <table class="form-table">
            <tbody>
            <tr>
                <th scope="row"><label
                            for="fs_secretkey"><?php _e("Secret Api Key", "ws-page-login"); ?></label>
                </th>
                <td><input name="fs_secretkey" type="text"
                           id="fs_secretkey"
                           value="<?php echo $secret_key; ?>"
                           class="regular-text">
                </td>
            </tr>
            </tbody>
        </table>

        <p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary"
                                 value="<?php echo count($api_keys) ? "Save" : "Login"; ?>"></p>
    </form>
</div>